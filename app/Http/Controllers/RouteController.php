<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleRoutesService;

class RouteController extends Controller
{
    protected $googleRoutesService;

    public function __construct(GoogleRoutesService $googleRoutesService)
    {
        $this->googleRoutesService = $googleRoutesService;
    }

    public function getRoute(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
        ]);

        $route = $this->googleRoutesService->getRoute($request->origin, $request->destination);

        if ($route) {
            return response()->json($route);
        }

        return response()->json(['error' => 'No route found'], 404);
    }
}

