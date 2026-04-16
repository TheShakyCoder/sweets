<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Privilege;
use App\Models\Right;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'is_admin' => true
        ]);
        $user = User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => Hash::make(env('EDITOR_PASSWORD', 'password')),
        ]);

        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        Privilege::create([
            'role_id' => $role->id,
            'user_id' => $user->id,
        ]);

        Right::create([
            'role_id' => $role->id,
            'controller_method_name' => 'internal.posts.index',
        ]);
    }
}
