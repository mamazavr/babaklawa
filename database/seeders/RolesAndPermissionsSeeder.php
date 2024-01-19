<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем роли
        Role::create(['name' => RoleEnum::ADMIN]);
        Role::create(['name' => RoleEnum::EDITOR]);

        // Создаем правила
        Permission::create(['name' => 'create_posts']);
        Permission::create(['name' => 'edit_posts']);
        Permission::create(['name' => 'delete_posts']);

        // Привязываем правила к ролям
        Role::findByName(RoleEnum::ADMIN)->givePermissionTo([
            'create_posts',
            'edit_posts',
            'delete_posts',
        ]);

        Role::findByName(RoleEnum::EDITOR)->givePermissionTo([
            'create_posts',
            'edit_posts',
        ]);
    }
}
