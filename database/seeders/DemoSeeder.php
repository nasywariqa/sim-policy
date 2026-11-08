<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Membership;
use App\Models\MembershipPermissionOverride;
use App\Models\Period;
use App\Models\Permission;
use App\Models\ProgramWork;
use App\Models\Role;
use App\Models\SawCriterion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new \RuntimeException('DemoSeeder dilarang untuk production.');
        }
        $catalog = [
            'programs.create'=>'Buat draft', 'programs.edit'=>'Edit draft',
            'programs.submit'=>'Ajukan program', 'programs.review'=>'Evaluasi program',
            'programs.decide'=>'Putuskan program', 'saw.view'=>'Lihat ranking',
            'saw.assess'=>'Nilai SAW', 'saw.calculate'=>'Hitung SAW',
            'saw.weights.manage'=>'Atur bobot SAW',
        ];
        foreach ($catalog as $slug=>$name) Permission::firstOrCreate(['slug'=>$slug],['name'=>$name]);
        $definition = [
            'pejabat_teras'=>['Pejabat Teras',['programs.review','programs.decide','saw.view','saw.assess','saw.calculate']],
            'ketua_bidang'=>['Ketua Bidang',['programs.create','programs.edit','programs.submit']],
            'pengurus_bidang'=>['Pengurus Bidang',['programs.create','programs.edit']],
        ];
        foreach ($definition as $slug=>[$name,$perms]) {
            $r=Role::firstOrCreate(['slug'=>$slug],['name'=>$name]);
            $r->permissions()->sync(Permission::whereIn('slug',$perms)->pluck('id'));
        }
        $period=Period::firstOrCreate(['name'=>'2026/2027'],[
            'starts_on'=>'2026-08-01','ends_on'=>'2027-07-31','is_active'=>true,
        ]);
        $dep=Department::firstOrCreate(['name'=>'Bidang Pengembangan']);
        $users = [
            'admin@plc.test'=>['Admin PLC','',true],
            'ketum@plc.test'=>['Ketua Umum Demo','pejabat_teras',false],
            'teras@plc.test'=>['Pejabat Teras Demo','pejabat_teras',false],
            'ketua@plc.test'=>['Ketua Bidang Demo','ketua_bidang',false],
            'pengurus@plc.test'=>['Pengurus Bidang Demo','pengurus_bidang',false],
        ];
        $members = [];
        foreach ($users as $email=>[$name,$roleSlug,$isAdmin]) {
            $user=User::firstOrCreate(['email'=>$email],[
                'name'=>$name,'password'=>Hash::make('Demo12345!'),
                'is_active'=>true,'is_super_admin'=>$isAdmin,
            ]);
            if (! $roleSlug) continue;
            $role=Role::where('slug',$roleSlug)->firstOrFail();
            $m=Membership::firstOrCreate(['user_id'=>$user->id,'period_id'=>$period->id],[
                'role_id'=>$role->id,
                'department_id'=>in_array($roleSlug,['ketua_bidang','pengurus_bidang'],true) ? $dep->id : null,
                'is_chairperson'=>$email==='ketum@plc.test',
            ]);
            $members[$roleSlug][]=$m;
            if ($email==='ketum@plc.test') {
                MembershipPermissionOverride::updateOrCreate([
                    'membership_id'=>$m->id,
                    'permission_id'=>Permission::where('slug','saw.weights.manage')->value('id'),
                ], ['effect'=>'grant']);
            }
        }
        $criteria = [
            ['V1','Kesesuaian visi dan kebutuhan anggota','benefit','score',0.25],
            ['S1','Kesiapan sumber daya manusia','benefit','score',0.20],
            ['A1','Estimasi anggaran biaya','cost','currency',0.15],
            ['U1','Urgensi terhadap kalender kegiatan','benefit','score',0.25],
            ['R1','Kerumitan dan risiko pelaksanaan','cost','score',0.15],
        ];
        foreach ($criteria as [$code,$name,$type,$scale,$weight]) {
            SawCriterion::firstOrCreate(['period_id'=>$period->id,'code'=>$code],[
                'name'=>$name,'type'=>$type,'scale'=>$scale,'weight'=>$weight,
            ]);
        }
        $member=$members['ketua_bidang'][0];
        $data=[
            ['Workshop Linux Dasar','Meningkatkan literasi Linux','Pelatihan praktis instalasi dan CLI','2026-12-12',250000,'30 peserta','submitted'],
            ['Linux Community Sharing','Membangun komunitas belajar','Sesi berbagi pengetahuan dan pengalaman','2027-01-15',0,'40 peserta','draft'],
        ];
        foreach ($data as [$name,$purpose,$description,$date,$budget,$target,$status]) {
            $work=ProgramWork::firstOrCreate(['period_id'=>$period->id,'name'=>$name],[
                'department_id'=>$dep->id,'created_by_membership_id'=>$member->id,
                'purpose'=>$purpose,'description'=>$description,'scheduled_at'=>$date,
                'budget'=>$budget,'target'=>$target,'status'=>$status,
            ]);
            $work->pics()->syncWithoutDetaching([$member->id]);
        }
    }
}
