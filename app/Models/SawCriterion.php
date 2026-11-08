<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SawCriterion extends Model
{
    protected $fillable = ['period_id','code','name','type','scale','weight'];
    protected function casts(): array { return ['weight'=>'decimal:4']; }
}
