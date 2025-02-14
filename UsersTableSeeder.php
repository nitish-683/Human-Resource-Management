<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Seema Sharma',
            'email' => 'ssharma@beesolvertechnology.com',
            'password' => Hash::make('Seema@123#'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $data = [
            ['name' => 'Admin','guard_name' => 'web'],
            ['name' => 'Employee','guard_name' => 'web']
        ];
        
        Role::insert($data);
        $permissions = Permission::pluck('id','id')->all();
        $role = Role::first();
        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

        Role::create(['name' => 'Staff']);
    }
}
