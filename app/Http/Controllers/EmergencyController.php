<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emergency;

class EmergencyController extends Controller
{
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
            'for_whom' => 'required|string',
            'status' => 'required|string',
            'team_id' => 'nullable|exists:teams,id',
            'call_time' => 'required|date_format:Y-m-d H:i:s',
            'review' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $emergency = Emergency::create($request->all());
        return response()->json($emergency, 201);
    }

    public function show($id)
    {
        $emergency = Emergency::findOrFail($id);
        return response()->json($emergency);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'sometimes|required|string',
            'for_whom' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'team_id' => 'nullable|exists:teams,id',
            'call_time' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'review' => 'nullable|string',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $emergency = Emergency::findOrFail($id);
        $emergency->update($request->all());
        return response()->json($emergency);
    }
}
