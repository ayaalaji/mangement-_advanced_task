<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'add_user',
            'update_user',
            'delete_user',
            'show_user',
            'add_comment',
            'update_comment',
            'delete_comment',
            'add_task',
            'update_task',
            'show_task',
            'delete_task',
            'add_attachement',
            'update_attachement',
            'dalete_attachement',
            'do_task',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }    
    }
}
