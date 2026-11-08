<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Membership;
use App\Models\MembershipPermissionOverride;
use App\Models\Period;
use App\Models\Permission;
use App\Models\ProgramWork;
use App\Models\Role;
use App\Models\User;
use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $p = Access::period();
        $m = Access::membership($request->user());
        $isAdmin = $request->user()->is_super_admin;
        return response()->json(['data'=>[
            'periods'=>Period::orderByDesc('starts_on')->get(),
            'departments'=>Department::orderBy('name')->get(),
            'roles'=> $isAdmin ? Role::all() : [],
            'permissions'=> $isAdmin ? Permission::all() : [],
            'users'=> $isAdmin ? User::orderBy('name')->get(['id','name','email','is_active','is_super_admin']) : [],
            'memberships'=> $isAdmin
                ? Membership::with(['user:id,name,email','role:id,name,slug','department:id,name','overrides'])->where('period_id',$p?->id)->get()
                : ($m && $m->department_id
                    ? Membership::with(['user:id,name','role:id,name,slug'])->where('period_id',$p->id)->where('department_id',$m->department_id)->get()
                    : []),
        ]]);
    }

    public function overview(Request $request)
    {
        $user = $request->user();
        $period = Access::period();
        $m = Access::membership($user);
        $works = ProgramWork::query()->where('period_id', $period?->id ?? 0);
        if (! $user->is_super_admin && ! Access::can($user,'programs.review') && $m?->department_id) {
            $works->where('department_id',$m->department_id);
        } elseif (! $user->is_super_admin && ! Access::can($user,'programs.review')) {
            $works->whereRaw('1=0');
        }
        return response()->json(['data'=>[
            'period'=>$period, 'total'=>$works->count(),
            'draft'=>(clone $works)->where('status','draft')->count(),
            'submitted'=>(clone $works)->where('status','submitted')->count(),
            'revision'=>(clone $works)->where('status','revision')->count(),
            'approved'=>(clone $works)->where('status','approved')->count(),
            'completed'=>(clone $works)->where('status','completed')->count(),
            'recent'=>$works->with('department:id,name')->latest()->limit(5)->get(),
            'users_count'=>$user->is_super_admin ? User::count() : null,
        ]]);
    }

    public function createPeriod(Request $request)
    {
        Access::admin($request->user());
        $data = $request->validate([
            'name'=>'required|string|max:100|unique:periods,name',
            'starts_on'=>'required|date',
            'ends_on'=>'required|date|after_or_equal:starts_on',
        ]);
        return response()->json(['data'=>Period::create([...$data, 'is_active'=>false])],201);
    }

    public function activatePeriod(Request $request, Period $period)
    {
        Access::admin($request->user());
        DB::transaction(function() use ($period) {
            Period::query()->update(['is_active'=>false]);
            $period->update(['is_active'=>true]);
        });
        return response()->json(['data'=>$period->refresh()]);
    }

    public function createDepartment(Request $request)
    {
        Access::admin($request->user());
        $data = $request->validate(['name'=>'required|string|max:100|unique:departments,name']);
        return response()->json(['data'=>Department::create($data)],201);
    }

    public function createUser(Request $request)
    {
        Access::admin($request->user());
        $data = $request->validate([
            'name'=>'required|string|max:150',
            'email'=>'required|email|max:190|unique:users,email',
            'password'=>'required|string|min:8',
            'is_super_admin'=>'sometimes|boolean',
        ]);
        $user = User::create($data);
        return response()->json(['data'=>$user->only('id','name','email','is_active','is_super_admin')],201);
    }

    public function createMembership(Request $request)
    {
        Access::admin($request->user());
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'role_id'=>'required|exists:roles,id',
            'period_id'=>'required|exists:periods,id',
            'department_id'=>'nullable|exists:departments,id',
            'is_chairperson'=>'sometimes|boolean',
        ]);
        $role = Role::findOrFail($data['role_id']);
        if (in_array($role->slug,['ketua_bidang','pengurus_bidang'],true) && empty($data['department_id'])) {
            throw ValidationException::withMessages(['department_id'=>'Role bidang harus memiliki bidang.']);
        }
        if (($data['is_chairperson'] ?? false) && $role->slug !== 'pejabat_teras') {
            throw ValidationException::withMessages(['is_chairperson'=>'Ketua Umum harus memiliki role Pejabat Teras.']);
        }
        $already = Membership::where('user_id',$data['user_id'])->where('period_id',$data['period_id'])->exists();
        if ($already) throw ValidationException::withMessages(['user_id'=>'Pengguna sudah terdaftar pada periode tersebut.']);
        $membership = DB::transaction(function() use ($data) {
            $membership = Membership::create($data);
            if ($membership->is_chairperson) {
                $permission = Permission::where('slug','saw.weights.manage')->firstOrFail();
                MembershipPermissionOverride::create([
                    'membership_id'=>$membership->id,'permission_id'=>$permission->id,'effect'=>'grant',
                ]);
            }
            return $membership;
        });
        return response()->json(['data'=>$membership],201);
    }

    public function toggleUser(Request $request, User $user)
    {
        Access::admin($request->user());
        $data = $request->validate(['is_active'=>'required|boolean']);
        if ($user->id === $request->user()->id && ! $data['is_active']) {
            throw ValidationException::withMessages(['is_active'=>'Akun sendiri tidak dapat dinonaktifkan.']);
        }
        $user->update($data);
        return response()->json(['data'=>$user->only('id','name','email','is_active')]);
    }

    public function setOverride(Request $request, Membership $membership)
    {
        Access::admin($request->user());
        $data = $request->validate([
            'permission_id'=>'required|exists:permissions,id',
            'effect'=>['required', Rule::in(['inherit','grant','deny'])],
        ]);
        if ($data['effect'] === 'inherit') {
            MembershipPermissionOverride::where('membership_id',$membership->id)
                ->where('permission_id',$data['permission_id'])->delete();
        } else {
            MembershipPermissionOverride::updateOrCreate(
                ['membership_id'=>$membership->id,'permission_id'=>$data['permission_id']],
                ['effect'=>$data['effect']]
            );
        }
        return response()->json(['message'=>'Permission berhasil diperbarui.']);
    }
}
