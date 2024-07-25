<?php

namespace App\Services\Admin\User;

use App\Models\Users\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Service
{
    public function store($userData): string
    {
        try {
            DB::beginTransaction();

            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'role_id' => $userData['role_id']
            ]);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update(User $user, $newUserData)
    {
        try {
            DB::beginTransaction();

            if ($newUserData['password']) {
                $user->update([
                    'name' => $newUserData['name'],
                    'email' => $newUserData['email'],
                    'password' => Hash::make($newUserData['password']),
                    'role_id' => $newUserData['role_id']
                ]);
            } else {
                $user->update([
                    'name' => $newUserData['name'],
                    'email' => $newUserData['email'],
                    'role_id' => $newUserData['role_id']
                ]);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function delete(User $user)
    {
        try {
            DB::beginTransaction();

            $user->delete();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }
}
