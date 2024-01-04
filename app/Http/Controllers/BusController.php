<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusRequest;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::getUserByToken(request()->bearerToken());

        $buses = Bus::where('organization_id', $user->organization->id)->get();
        return $this->responseSuccessJson('RETRIEVE_SUCCESS', $buses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusRequest $request)
    {
        $user = User::getUserByToken(request()->bearerToken());
        $bus = Bus::create([
            'organization_id' => $user->organization->id,
            ...$request->validated()
        ]);

        return $this->responseSuccessJson('SAVED_SUCCESS', $bus, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::getUserByToken(request()->bearerToken());

        $bus = Bus::where('organization_id', $user->organization->id)->find($id);

        if (!$bus) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        return $this->responseSuccessJson('RETRIEVE_SUCCESS', $bus);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BusRequest $request, string $id)
    {
        $user = User::getUserByToken(request()->bearerToken());
        $bus = Bus::where('organization_id', $user->organization->id)->find($id);

        if (!$bus) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        $bus->update([
            'organization_id' => $user->organization->id,
            ...$request->validated()
        ]);

        return $this->responseSuccessJson('SAVED_SUCCESS', $bus, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::getUserByToken(request()->bearerToken());
        $bus = Bus::where('organization_id', $user->organization->id)->find($id);

        if (!$bus) {
            return $this->responseErrorJson('NOT_FOUND', [], 404);
        }

        $bus->delete();

        return $this->responseSuccessJson('DELETE_SUCCESS', []);
    }
}
