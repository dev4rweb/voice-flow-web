<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class AdminUserManager
{
    public function ensureFromConfig(): User
    {
        $email = strtolower(trim((string) config('voice_flow.admin.email')));
        $password = (string) config('voice_flow.admin.password');
        $name = trim((string) config('voice_flow.admin.name'));

        if ($email === '' || $password === '') {
            throw new \InvalidArgumentException('ADMIN_EMAIL and ADMIN_PASSWORD must be set in .env.');
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = $name !== '' ? $name : 'Voice Flow Admin';
        $user->password = Hash::make($password);
        $user->is_admin = true;
        $user->save();

        return $user;
    }
}
