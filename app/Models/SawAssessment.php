<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SawAssessment extends Model
{
    protected $fillable = ['program_work_id','saw_criterion_id','membership_id','value'];
    protected function casts(): array { return ['value'=>'decimal:4']; }
}
