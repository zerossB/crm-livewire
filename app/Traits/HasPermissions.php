<?php

namespace App\Traits;

use App\Enums\Can;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    protected function getCachePermissionsKey(): string
    {
        return sprintf('%s::%d::permissions', class_basename($this), $this->id);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->orderBy('name');
    }

    public function givePermissionTo(Can|string $permissionName): Permission
    {
        $permissionKey = $permissionName instanceof Can ? $permissionName->value : $permissionName;

        $permission = Permission::firstOrCreate(['name' => $permissionKey]);

        $this->permissions()->syncWithoutDetaching($permission);

        $this->refreshPermissionsCache();

        return $permission;
    }

    public function hasPermissionTo(Can|string $permissionName): bool
    {
        $permissionKey = $permissionName instanceof Can ? $permissionName->value : $permissionName;

        $permissions = $this->getCachedPermissions();

        return $permissions->contains('name', $permissionKey);
    }

    protected function getCachedPermissions()
    {
        return Cache::rememberForever($this->getCachePermissionsKey(), function () {
            return $this->permissions;
        });
    }

    protected function refreshPermissionsCache(): void
    {
        Cache::forget($this->getCachePermissionsKey());
        $this->getCachedPermissions();
    }
}
