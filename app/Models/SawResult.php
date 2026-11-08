<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SawResult extends Model
{
    public $timestamps = false;
    protected $fillable = ['saw_run_id','program_work_id','score','rank'];
    protected function casts(): array { return ['score'=>'decimal:8']; }
    public function program() { return $this->belongsTo(ProgramWork::class,'program_work_id'); }
}
