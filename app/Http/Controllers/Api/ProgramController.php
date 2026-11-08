<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\ProgramDecision;
use App\Models\ProgramNote;
use App\Models\ProgramWork;
use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProgramController extends Controller
{
    private function visible(Request $request, ProgramWork $program): Membership
    {
        abort_unless(Access::period()?->id === $program->period_id,403,'Hanya periode aktif.');
        $member = Access::membership($request->user(),$program->period_id);
        abort_unless($member,403,'Keanggotaan untuk periode ini tidak ditemukan.');
        $canReview = Access::can($request->user(),'programs.review',$program->period_id);
        abort_unless($canReview || $member->department_id === $program->department_id,403);
        return $member;
    }

    private function details(ProgramWork $program): ProgramWork
    {
        return $program->load(['department:id,name','pics.user:id,name', 'notes','decisions']);
    }

    public function index(Request $request)
    {
        $member = Access::membership($request->user());
        if (! $member) return response()->json(['data'=>[]]);
        $query = ProgramWork::where('period_id',$member->period_id);
        if (! Access::can($request->user(),'programs.review') && ! Access::can($request->user(),'programs.decide')) {
            $query->where('department_id',$member->department_id ?? 0);
        }
        return response()->json(['data'=>$query->with(['department:id,name','pics.user:id,name','notes','decisions'])->latest()->get()]);
    }

    private function validated(Request $request): array
    {
        $rules = [
            'name'=>'required|string|max:200', 'purpose'=>'required|string|max:3000',
            'description'=>'nullable|string|max:5000','scheduled_at'=>'nullable|date',
            'budget'=>'required|numeric|min:0|max:999999999999', 'target'=>'nullable|string|max:255',
            'pic_ids'=>'sometimes|array', 'pic_ids.*'=>'integer|distinct|exists:memberships,id',
        ];
        return $request->validate($rules);
    }

    private function validatePics(array $ids, Membership $member): void
    {
        if (! $ids) return;
        $count = Membership::whereIn('id',$ids)->where('period_id',$member->period_id)
            ->where('department_id',$member->department_id)->count();
        if ($count !== count($ids)) {
            throw ValidationException::withMessages(['pic_ids'=>'PIC harus berasal dari bidang dan periode yang sama untuk versi demo.']);
        }
    }

    public function store(Request $request)
    {
        $member = Access::require($request->user(),'programs.create');
        abort_unless($member->department_id,403);
        $data = $this->validated($request);
        $pics = $data['pic_ids'] ?? [];
        $this->validatePics($pics,$member);
        unset($data['pic_ids']);
        $program = DB::transaction(function() use ($data,$pics,$member) {
            $program = ProgramWork::create([...$data,
                'period_id'=>$member->period_id, 'department_id'=>$member->department_id,
                'created_by_membership_id'=>$member->id,'status'=>'draft',
            ]);
            $program->pics()->sync($pics);
            return $program;
        });
        return response()->json(['data'=>$this->details($program)],201);
    }

    public function update(Request $request, ProgramWork $program)
    {
        $member = $this->visible($request,$program);
        Access::require($request->user(),'programs.edit',$program->period_id);
        abort_unless($member->department_id === $program->department_id,403);
        abort_unless(in_array($program->status,['draft','revision'],true),409,'Status tidak mengizinkan perubahan.');
        $data = $this->validated($request);
        $pics = $data['pic_ids'] ?? null;
        if ($pics !== null) $this->validatePics($pics,$member);
        unset($data['pic_ids']);
        DB::transaction(function() use ($program,$data,$pics) {
            $program->update($data);
            if ($pics !== null) $program->pics()->sync($pics);
        });
        return response()->json(['data'=>$this->details($program)]);
    }

    public function submit(Request $request, ProgramWork $program)
    {
        $member = $this->visible($request,$program);
        Access::require($request->user(),'programs.submit',$program->period_id);
        abort_unless($member->department_id === $program->department_id,403);
        abort_unless(in_array($program->status,['draft','revision'],true),409);
        if (! $program->scheduled_at || ! $program->target || ! $program->pics()->exists()) {
            throw ValidationException::withMessages(['program'=>'Isi tanggal, target, dan minimal satu PIC sebelum pengajuan.']);
        }
        $program->update(['status'=>'submitted']);
        return response()->json(['data'=>$this->details($program)]);
    }

    public function note(Request $request, ProgramWork $program)
    {
        abort_unless(Access::period()?->id === $program->period_id,403,'Hanya periode aktif.');
        $member = Access::require($request->user(),'programs.review',$program->period_id);
        abort_unless($program->status === 'submitted',409,'Hanya program diajukan dapat direvisi.');
        $data = $request->validate(['content'=>'required|string|max:5000']);
        DB::transaction(function() use ($program,$member,$data) {
            ProgramNote::create(['program_work_id'=>$program->id,'membership_id'=>$member->id,'content'=>$data['content']]);
            ProgramDecision::create(['program_work_id'=>$program->id,'membership_id'=>$member->id,'decision'=>'revision','note'=>$data['content']]);
            $program->update(['status'=>'revision']);
        });
        return response()->json(['data'=>$this->details($program)]);
    }

    public function decision(Request $request, ProgramWork $program)
    {
        abort_unless(Access::period()?->id === $program->period_id,403,'Hanya periode aktif.');
        $member = Access::require($request->user(),'programs.decide',$program->period_id);
        abort_unless($program->status === 'submitted',409,'Program harus diajukan sebelum keputusan.');
        $data = $request->validate([
            'decision'=>['required',Rule::in(['approved','rejected'])],
            'note'=>'nullable|string|max:5000',
        ]);
        DB::transaction(function() use ($program,$member,$data) {
            ProgramDecision::create(['program_work_id'=>$program->id,'membership_id'=>$member->id,
                'decision'=>$data['decision'],'note'=>$data['note'] ?? null]);
            $program->update(['status'=>$data['decision']]);
        });
        return response()->json(['data'=>$this->details($program)]);
    }

    public function complete(Request $request, ProgramWork $program)
    {
        $member = $this->visible($request,$program);
        Access::require($request->user(),'programs.submit',$program->period_id);
        abort_unless($member->department_id === $program->department_id,403);
        abort_unless($program->status === 'approved',409,'Hanya program disetujui dapat diselesaikan.');
        $program->update(['status'=>'completed']);
        return response()->json(['data'=>$this->details($program)]);
    }
}
