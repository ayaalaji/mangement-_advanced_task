<?php 

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UserService {
    /**
     * get all Users in system by admin.
     */
    public function getAllUsers()
    {
        try {
            $users = Cache::remember('users',3600,function(){      
                User::where('id','>',1)->get();
            });
            return $users;
        } catch (\Throwable $th) {
            Log::error($th);
            throw new \Exception('Unable to fetch users at this time. Please try again later.');
        }
    }
    public function getRoles()
    {
        try {
            return Role::pluck('name', 'name')->all();
        } catch (\Throwable $th) {
            Log::error($th);
            throw new \Exception('Unable to fetch roles at this time. Please try again later.');
        }
    }

    /**
     * create new User by admin.
     * @param array $data
     */
    public function createUser(array $data)
    {
        try {
            $data['password'] =Hash::make($data['password']);
            if ($data['role'] !== 'manager') 
            {
                $data['manages_type'] = null;
            }
            $user = User::create($data);
            $user->assignRole($data['role']);

            return $user;
        } catch (\Throwable $th) {
            Log::error($th);
            throw new \Exception('Unable to Add User at this time. Please try again later.');
        }
    }
    public function getUserWithRoles(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();

            return [
                'user' => $user,
                'roles' => $roles,
                'userRole' => $userRole
            ];
        } catch (\Throwable $th) {
            Log::error($th);
            throw new \Exception('Unable to retrieve user or roles at this time. Please try again later.');
        }
    }

    /**
     * update a specifice User by admin.
     * @param array $data
     * @param string $id
     */
    public function updateUser(array $data, string $id)
    {
        try {
            $user = User::findOrFail($id);
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                $data = Arr::except($data, ['password']);
            }

            $user->update($data);
            if (isset($data['role'])) 
            {
                $user->roles()->detach();
                $user->assignRole($data['role']);
            } else {
                Log::warning('No role provided for user update: ' . $id);
            }

            return $user;
        } catch (Exception $th) {
            Log::error($th);
            throw new \Exception('Unable to update user at this time. Please try again later.');
        }
    }

    public function deleteUser(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return true;
        } catch (\Throwable $th) {
            Log::error($th);
            throw new \Exception('Unable to delete user at this time. Please try again later.');
        }
    }
}