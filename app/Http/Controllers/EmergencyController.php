<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emergency;
use App\Services\GoogleRoutesService;
use App\Models\Team;



class EmergencyController extends Controller
{
    protected $googleRoutesService;
    public function __construct(GoogleRoutesService $googleRoutesService)
    {
        $this->googleRoutesService = $googleRoutesService;
    }

    public function index()
    {
        $emergencies = Emergency::all();
        return response()->json($emergencies);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'for_whom' => 'required|boolean',
            'status' => 'required|string',
            'driver_name' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id',
            'call_time' => 'required|date_format:Y-m-d H:i:s',
            'review' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,5',
            'priority' => 'required|string|in:HIGH,MEDIUM,LOW',
        ]);

        $emergency = Emergency::create($request->all());
        return response()->json($emergency, 201);
    }

    public function show($id)
    {
        $emergency = Emergency::with(['user', 'team'])->findOrFail($id);

        if ($emergency->team_id) {
            $team = Team::find($emergency->team_id);
            $origin = $team->current_coordinates;
            $route = $this->googleRoutesService->getRoute($origin, $emergency->address, $emergency->team_id);

            $emergency->route = $route;
        }

        return response()->json($emergency);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'sometimes|required|string',
            'for_whom' => 'sometimes|required|boolean',
            'status' => 'sometimes|required|string',
            'driver_name' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id',
            'call_time' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'review' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $emergency = Emergency::findOrFail($id);
        $emergency->update($request->all());
        return response()->json($emergency);
    }
    public function accept(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:emergencies,id',
        ]);

        $emergency = Emergency::findOrFail($request->id);
        $emergency->status = 'accepted';
        $emergency->save();

        return response()->json(['message' => 'Emergency status updated to accepted', 'emergency' => $emergency]);
    }
}
