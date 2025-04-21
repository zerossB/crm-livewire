<?php

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function getCachePermissionsKey(): string
    {
        $model = class_basename($this);

        return "{$model}::{$this->id}::permissions";
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)
            ->orderBy('name');
    }

    public function givePermissionTo(string $permissionName): Model
    {
        $permission = $this->permissions()->firstOrCreate(['name' => $permissionName]);

        Cache::forget($this->getCachePermissionsKey());
        Cache::rememberForever($this->getCachePermissionsKey(), function () {
            return $this->permissions;
        });

        return $permission;
    }

    public function hasPermissionTo(string $permissionName): bool
    {
        /** @var Collection $permissions */
        $permissions = Cache::get($this->getCachePermissionsKey(), function () {
            return $this->permissions;
        });

        return $permissions->where('name', $permissionName)->isNotEmpty();
    }
}
