<?php

namespace Database\Factories;

use App\Models\PermissionRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionRoleFactory extends Factory
{
    protected $model = PermissionRole::class;

    public function definition()
    {
        return [
            'role_id' => \App\Models\Role::factory(),
            'permission_id' => \App\Models\Permission::factory(),
        ];
    }
}
