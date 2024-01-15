<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\BusSchedule;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

class BusScheduleController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $organization = Organization::findByToken(request()->bearerToken());
        $busSchedules = BusSchedule::whereHas('bus', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->with(['bus', 'driver.user'])->get();
        return $this->responseSuccessJson('SUCCESSFULLY_RETRIEVED', $busSchedules);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bus_id' => ['required', 'exists:buses,id'],
            'driver_id' => ['required', 'exists:employees,id'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->responseErrorJson('VALIDATION_ERROR', $validator->errors());
        }

        $busSchedule = BusSchedule::create($request->only([
            'bus_id',
            'driver_id',
            'start_date',
            'end_date',
            'start_time',
            'end_time',
        ]));

        $auth = Firebase::auth();
        $database = Firebase::database();
        $employee = Employee::find($request->driver_id);
        $user = $auth->getUserByEmail($employee->user->email);

        $database->getReference('BusDrivers')->push([
            'busCode' => $busSchedule->bus->bus_code,
            'driverId' => $user->uid,
            'email' => $employee->user->email,
            'fullName' => $user->displayName,
            'plateNumber' => $busSchedule->bus->plate_number,
            'route' =>  $busSchedule->bus->start_point . " - " . $busSchedule->bus->end_point
        ]);

        // $reference = $database->getReference('BusDrivers');
        // $values = $reference->getValue();
        // $mappedData = [];
        // foreach ($values as $key => $value) {
        //     $mappedData[] = (object)[
        //         'uid' => $key,
        //         ...$value
        //     ];
        // }

        // $collectionData = collect($mappedData);

        return $this->responseSuccessJson('SUCCESSFULLY_CREATED', $busSchedule->load(['bus', 'driver']), 201);
    }

    public function destroy(string $id)
    {
        $busSchedule = BusSchedule::find($id);

        if (!$busSchedule) {
            return $this->responseErrorJson('RESOURCE_NOT_FOUND', [], 404);
        }

        $busSchedule->delete();

        return $this->responseSuccessJson('SUCCESSFULLY_DELETED', []);
    }
}
