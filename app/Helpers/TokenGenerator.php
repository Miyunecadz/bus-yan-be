<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class TokenGenerator
{
    /**
     * Validate the token 
     */
    public static function validate($token)
    {
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        // index 0 - expected to be the userId
        if (!User::find($data[0])) {
            return false;
        }

        // index 1 - expected to be the application key
        if (config('app.key') !== $data[1]) {
            return false;
        }

        return true;
    }

    public static function validateTokenBasedOnRole($token, $role)
    {
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        // index 0 - expected to be the userId
        if (!$user = User::find($data[0])) {
            return false;
        }

        // index 1 - expected to be the application key
        if (config('app.key') !== $data[1]) {
            return false;
        }

        if ($user->userAccount->account_role !== $role) {
            return false;
        }

        return true;
    }

    public static function invalidateToken($token)
    {
        if (!self::validate($token)) {
            return false;
        }

        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        $user = User::find($data[0]);
        $user->update(['token' => null]);
        auth()->logout();
        return true;
    }

    /**
     * Generate token based on user and application key
     */
    public static function generate(User $user)
    {
        $appKey = config('app.key');
        $userId = $user->id;

        $token = Crypt::encrypt($userId . '-' . $appKey);

        $user->update(['token' => $token]);

        return $token;
    }
}
