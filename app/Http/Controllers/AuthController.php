<?php

namespace App\Http\Controllers;

use App\Helpers\TokenGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\LoginCredentialRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function loginWithCredentials(LoginCredentialRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return $this->responseErrorJson('INVALID_CREDENTIALS', [], 401);
        }

        $user = auth()->user();

        if ($user->userAccount->account_role !== $request->role) {
            return $this->responseErrorJson('INVALID_CREDENTIALS', [], 401);
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
}
