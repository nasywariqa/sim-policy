<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProgramWork extends Model
{
    protected $fillable = ['period_id','department_id','created_by_membership_id','name','purpose','description','scheduled_at','budget','target','status'];
    protected function casts(): array { return ['budget'=>'decimal:2', 'scheduled_at'=>'date:Y-m-d']; }
    public function department() { return $this->belongsTo(Department::class); }
    public function period() { return $this->belongsTo(Period::class); }
    public function pics() { return $this->belongsToMany(Membership::class, 'program_work_pics', 'program_work_id', 'membership_id')->with('user'); }
    public function notes() { return $this->hasMany(ProgramNote::class)->with('author.user'); }
    public function decisions() { return $this->hasMany(ProgramDecision::class)->with('author.user'); }
}
