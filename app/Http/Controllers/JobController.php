<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Question;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $jobs = Job::when($organization, fn ($query) => $query->where('organization_id', $organization->id))
            ->with('organization')->get();

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $jobs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request)
    {
        $organization = Organization::findByToken(request()->bearerToken());

        $job = Job::create(
            [
                'organization_id' => $organization->id,
                'company_name' => $request->company_name ?? $organization->company_name,
                'company_address' => $request->company_address ?? $organization->company_address,
                'about_the_company' => $request->about_the_company ?? $organization->about_the_company,
                ...$request->only([
                    'title',
                    'salary',
                    'job_highlights',
                    'qualifications',
                    'how_to_apply',
                    'image_url',
                ])
            ]
        );

        if ($request->questions) {
            $questions = array_map(fn ($item) => ['job_id' => $job->id, 'description' => $item['description'], 'created_at' => now(), 'updated_at' => now()], $request->questions);
            Question::insert($questions);
        }

        return $this->responseSuccessJson('SUCCESSFULLY_CREATED', $job, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $job = Job::where('organization_id', $organization->id)->find($id);

        if (!$job) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobRequest $request, string $id)
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $job = Job::where('organization_id', $organization->id)->find($id);

        if (!$job) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        $job->update(
            [
                'organization_id' => $organization->id,
                ...$request->only([
                    'title',
                    'company_name',
                    'company_address',
                    'salary',
                    'job_highlights',
                    'qualifications',
                    'how_to_apply',
                    'about_the_company',
                    'image_url',
                ])
            ]
        );

        if ($request->questions) {
            Question::where('job_id', $job->id)->delete();
            $questions = array_map(fn ($item) => ['job_id' => $job->id, 'description' => $item['description'], 'created_at' => now(), 'updated_at' => now()], $request->questions);
            Question::insert($questions);
        }

        return $this->responseSuccessJson('SUCCESSFULLY_UPDATED', $job);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $job = Job::where('organization_id', $organization->id)->find($id);

        if (!$job) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        Question::where('job_id', $job->id)->delete();
        $job->delete();

        return $this->responseSuccessJson('SUCCESSFULLY_DELETED');
    }
}
