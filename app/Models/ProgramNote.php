<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProgramNote extends Model
{
    protected $fillable = ['program_work_id','membership_id','content'];
    public const UPDATED_AT = null;
    public function author() { return $this->belongsTo(Membership::class,'membership_id'); }
}
