<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Operator;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use App\Enums\UserAccountEnum;
use App\Http\Requests\OperatorRequest;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $operators = Operator::get();
        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $operators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperatorRequest $request)
    {
        $user = User::create([
            ...$request->only([
                'email',
                'phone_number',
                'display_name',
            ]),
            'password' => bcrypt($request->password)
        ]);

        UserAccount::create([
            'user_id' => $user->id,
            'account_role' => UserAccountEnum::BUS_OPERATOR,
            'is_verified' => auth()->user()->isAdmin() ? true : false
        ]);

        Operator::create([
            'organization_id' => $request->organization_id,
            'user_id' => $user->id
        ]);

        return $this->responseSuccessJson('SUCCESSFULLY_CREATED', $user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::whereHas('userAccount', function ($query) {
            $query->where('account_role', UserAccountEnum::BUS_COOPERATIVE->value);
        })->with(['userAccount', 'operator'])->find($id);

        if (!$user) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperatorRequest $request, string $id)
    {
        $user = User::whereHas('userAccount', function ($query) {
            $query->where('account_role', UserAccountEnum::BUS_COOPERATIVE->value);
        })->with(['userAccount', 'operator'])->find($id);

        if (!$user) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        try {
            DB::beginTransaction();
            $user->update($request->only(['email', 'phone_number', 'display_name']));
            Operator::where('user_id', $user->id)->first()->update(['organization_id', $request->organization_id]);
            DB::commit();

            $user->refresh();
            return $this->responseSuccessJson('SUCCESSFULLY_UPDATED', $user, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseErrorJson('TRANSACTION_ERROR', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::whereHas('userAccount', function ($query) {
            $query->where('account_role', UserAccountEnum::BUS_COOPERATIVE->value);
        })->with(['userAccount', 'operator'])->find($id);

        if (!$user) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        $user->delete();

        return $this->responseSuccessJson('SUCCESSFULLY_DELETED', $user, 200);
    }
}
