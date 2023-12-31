<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\UserAccount;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Enums\UserAccountEnum;
use App\Enums\EmployeeStatusEnum;
use App\Http\Requests\EmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $employees = Employee::where('organization_id', $organization->id)->get();

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $employees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $organization = Organization::findByToken(request()->bearerToken());

        if ($request->employee_type == EmployeeStatusEnum::BUS_DRIVER->value) {
            $user = User::create([
                'email' => $request->email,
                'phone_number' => $request->contact_number,
                'display_name' => $request->full_name,
                'password' => bcrypt('busyan@1234')
            ]);

            UserAccount::create([
                'user_id' => $user->id,
                'account_role' => UserAccountEnum::DRIVER,
                'is_verified' => true
            ]);
        }

        $employee = Employee::create([
            'organization_id' => $organization->id,
            'employee_id' => $user ? $user->id : null,
            ...$request->only([
                'id_number',
                'full_name',
                'email',
                'contact_number',
                'profile_url',
                'employee_type',
            ])
        ]);

        return $this->responseSuccessJson('SUCCESSFULLY_CREATED', $employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $employee = Employee::where('organization_id', $organization->id)->find($id);

        if (!$employee) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
