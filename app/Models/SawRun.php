<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SawRun extends Model
{
    protected $fillable = ['period_id','membership_id','input_snapshot'];
    protected function casts(): array { return ['input_snapshot'=>'array']; }
    public function results() { return $this->hasMany(SawResult::class)->orderBy('rank'); }
}
