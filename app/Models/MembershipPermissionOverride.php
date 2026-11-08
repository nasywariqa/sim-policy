<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MembershipPermissionOverride extends Model
{
    public $timestamps = false;
    protected $fillable = ['membership_id', 'permission_id', 'effect'];
}
