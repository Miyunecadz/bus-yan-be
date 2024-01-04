<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $currentJobseekerUser = User::getUserByRoleToken('jobseeker', request()->bearerToken());

        $applications = Application::whereHas('job', 
            fn ($query) => $query->when($organization,
                fn ($query2) => $query2->where('organization_id', $organization->id)
            )->when($currentJobseekerUser,
                fn ($query2) => $query2->where('user_id', $currentJobseekerUser->id))
        )->get();

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $applications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $applicant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $applicant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $applicant)
    {
        //
    }
}
