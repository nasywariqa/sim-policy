<?php

namespace App\Support;

use App\Models\Membership;
use App\Models\Period;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

final class Access
{
    public static function period(): ?Period
    {
        return Period::where('is_active', true)->first();
    }

    public static function membership(User $user, ?int $periodId = null): ?Membership
    {
        $periodId ??= self::period()?->id;
        return $periodId ? Membership::with(['role.permissions','department','overrides'])
            ->where('user_id', $user->id)->where('period_id',$periodId)->first() : null;
    }

    public static function permissions(User $user, ?int $periodId = null): array
    {
        $m = self::membership($user, $periodId);
        if (! $m) return [];
        $slugs = $m->role->permissions->pluck('slug')->all();
        $overrides = $m->overrides->keyBy('permission_id');
        foreach (\App\Models\Permission::all() as $perm) {
            $effect = $overrides->get($perm->id)?->effect;
            if ($effect === 'deny') $slugs = array_diff($slugs, [$perm->slug]);
            if ($effect === 'grant') $slugs[] = $perm->slug;
        }
        return array_values(array_unique($slugs));
    }

    public static function can(User $user, string $permission, ?int $periodId = null): bool
    {
        return in_array($permission, self::permissions($user, $periodId), true);
    }

    public static function require(User $user, string $permission, ?int $periodId = null): Membership
    {
        if (! self::can($user, $permission, $periodId)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk tindakan ini.');
        }
        return self::membership($user, $periodId);
    }

    public static function admin(User $user): void
    {
        if (! $user->is_super_admin) {
            throw new AuthorizationException('Khusus Super Admin.');
        }
    }
}
