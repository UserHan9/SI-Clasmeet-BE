<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void 
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@contoh.com',
            'password' => bcrypt('password'),
        ]); 
        $admin->assignRole('admin');
        $permission =[
            'roles.index',
            'roles.create',
            'roles.delete',
            'roles.update',
        ];
        $admin->syncPermissions($permission);


        $user = User::create([
            'name' => 'User',
            'email' => 'user@contoh.com',
            'password' => bcrypt('12345678'),
        ]);

        $user->assignRole('user');
    }
}
