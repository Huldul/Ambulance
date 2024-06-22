<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $request->validate([
            'car' => 'required|string',
            'driver' => 'required|string',
            'feldsher' => 'required|string',
            'type' => 'required|string',
        ]);

        $team = Team::create($request->all());
        return response()->json($team, 201);
    }

    public function show($id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'car' => 'sometimes|required|string',
            'driver' => 'sometimes|required|string',
            'feldsher' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
        ]);

        $team = Team::findOrFail($id);
        $team->update($request->all());
        return response()->json($team);
    }
}
