<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['user_id', 'role_id', 'period_id', 'department_id', 'is_chairperson'];

    protected function casts(): array
    {
        return ['is_chairperson' => 'boolean'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function role() { return $this->belongsTo(Role::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function period() { return $this->belongsTo(Period::class); }
    public function overrides() { return $this->hasMany(MembershipPermissionOverride::class); }
}
