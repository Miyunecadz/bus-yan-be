<?php

namespace App\Http\Controllers;

use App\Enums\UserAccountEnum;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationRequest;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = Organization::when(request()->verified, function ($query) {
            $query->whereHas('user', function ($subQuery) {
                $subQuery->whereHas('userAccount', fn ($query) => $query->where('is_verified', request()->verified));
            });
        })->get();

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $organizations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrganizationRequest $request)
    {
        $userData = $request->only([
            'email',
            'phone_number',
            'display_name',
        ]);

        $user = User::create([
            ...$userData,
            'password' => bcrypt($request->password)
        ]);

        UserAccount::create([
            'user_id' => $user->id,
            'account_role' => UserAccountEnum::BUS_COOPERATIVE,
            'is_verified' => auth()->user()->isAdmin() ? true : false
        ]);

        Organization::create([
            ...$request->only(['company_name', 'company_address', 'company_description']),
            'owner_user_id' => $user->id
        ]);

        return $this->responseSuccessJson('SUCCESSFULLY_CREATED', $user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = Organization::with('user.userAccount')->find($id);

        if (!$organization) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $organization = Organization::with('user.userAccount')->find($id);

        if (!$organization) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        $organization->user->update([
            ...$request->only(['email', 'phone_number', 'display_name',]),
        ]);

        $organization->udpate([
            ...$request->only(['company_name', 'company_address', 'company_description']),
        ]);

        return $this->responseSuccessJson('SUCCESSFULLY_UPDATED', $organization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = Organization::with('user.userAccount')->find($id);

        if (!$organization) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }
        DB::beginTransaction();
        try {
            $organization->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseErrorJson('INTERNAL_SERVER_ERROR');
        }

        return $this->responseSuccessJson('SUCCESSFULLY_DELETED');
    }
}
