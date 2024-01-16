<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Helpers\TokenGenerator;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\LoginCredentialRequest;

class AuthController extends Controller
{
    public function loginWithCredentials(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return $this->responseErrorJson('INVALID_CREDENTIALS', [], 401);
        }

        $user = auth()->user();

        if (!in_array($user->userAccount->account_role, is_array($request->role) ? $request->role : [$request->role])) {
            auth()->logout();
            return $this->responseErrorJson('INVALID_CREDENTIALS', [], 401);
        }

        if (!$user->userAccount->is_verified) {
            auth()->logout();
            return $this->responseErrorJson('ACCOUNT_NOT_VERIFIED', [], 401);
        }

        $token = TokenGenerator::generate($user);

        return $this->responseSuccessJson('SUCCESSFUL_LOGIN', [
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout()
    {
        TokenGenerator::invalidateToken(request()->bearerToken());

        return $this->responseSuccessJson('SUCCESSFUL_LOGOUT', []);
    }

    public function me()
    {
        $user = User::getUserByToken(request()->bearerToken());
        $user->load(['userAccount', 'organization.employees.user']);

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $user);
    }

    public function loginWithGoogleRedirect(Request $request)
    {
        $result = Socialite::driver('google')->userFromToken($request->token);
        dd($result);
    }

    public function loginWithGoogleCallback()
    {
        return response()->json(['user' => Socialite::driver('google')->stateless()->user()]);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'email' => $request->email,
            'phone_number' => $request->contact_number,
            'display_name' => $request->full_name,
            'password' => bcrypt('busyan@1234')
        ]);

        UserAccount::create([
            'user_id' => $user->id,
            'account_role' => $request->type,
            'is_verified' => false
        ]);

        return $this->responseSuccessJson('SUCCESSFULLY_REGISTERED', $user);
    }
}
