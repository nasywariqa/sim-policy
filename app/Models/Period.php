<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Period extends Model
{
    protected $fillable = ['name','starts_on','ends_on','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
}
