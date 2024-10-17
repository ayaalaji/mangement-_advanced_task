<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Admin', 
            'email' => 'admin@gmail.com',
            'password' =>Hash::make('12345678'),
            'role'=> 'admin',
        ]);
        $user2 = User::create([
            'name'=>'Muna Ali',
            'email'=>'munaali@gmail.com',
            'password'=>Hash::make('11111111'),
            'role' => 'manager',
            'manages_type'=>'bug',
            
        ]);
        $user3 = User::create([
            'name'=>'Alaa Essa',
            'email'=>'alaaessa@gmail.com',
            'password'=>Hash::make('22222222'),
            'role' => 'manager',
            'manages_type'=>'feature',
            
        ]);
        $user4 = User::create([
            'name'=>'Ahmad Deeb',
            'email'=>'ahmaddeeb@gmail.com',
            'password'=>Hash::make('33333333'),
            'role' => 'manager',
            'manages_type'=>'improvement',
            
        ]);
        $user5 = User::create([
            'name'=>'User',
            'email'=>'user@gmail.com',
            'password'=>Hash::make('33333333'),
            'role' => 'user',
        ]);
        
    $role = Role::create(['name' => 'admin']);
    $roleManager = Role::create(['name' => 'manager']);
    $roleUser = Role::create(['name' => 'user']);
     
        $permissions = Permission::pluck('id','id')->all();
        
        $excludedPermissions = [12, 13, 14, 15];

// تصفية الصلاحيات للحصول على الصلاحيات المسموحة فقط
$adminPermissions = array_diff($permissions, $excludedPermissions);


        $permissionsmanager = Permission::whereIn('id', array_merge(range(5, 7), range(12, 14), [10]))->pluck('id')->all();

        $permissionsUser = Permission::whereIn('id', [15, 5, 6, 7, 10])->pluck('id')->all();
   
        $role->syncPermissions($adminPermissions);
        $roleManager->syncPermissions($permissionsmanager);
        $roleUser->syncPermissions($permissionsUser);
     
        $user1->assignRole([$role->id]);
        $user2->assignRole($roleManager->id);
        $user3->assignRole($roleManager->id);
        $user4->assignRole($roleManager->id);
        $user5->assignRole($roleUser->id);
        
    }
    }

