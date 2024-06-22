<?php
// app/Http/Controllers/RouteController.php

// app/Http/Controllers/RouteController.php
// app/Http/Controllers/RouteController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleRoutesService;
use App\Models\Emergency;
use App\Models\Team;

class RouteController extends Controller
{
    protected $googleRoutesService;

    public function __construct(GoogleRoutesService $googleRoutesService)
    {
        $this->googleRoutesService = $googleRoutesService;
    }

    // Пациент видит координаты машины
    public function getRoute($teamId)
    {
        $team = Team::findOrFail($teamId);
        $emergency = Emergency::where('team_id', $teamId)->firstOrFail();
        $origin = $team->current_coordinates; // Получаем текущие координаты команды из базы данных

        // Проверяем, что координаты машины существуют
        if (empty($origin)) {
            return response()->json(['error' => 'Team coordinates are not available'], 400);
        }

        // Получаем маршрут от машины до пациента
        $route = $this->googleRoutesService->getRoute($origin, $emergency->address, $teamId);

        if ($route) {
            return response()->json(array_merge($route, ['team_location' => $origin]));
        }

        return response()->json(['error' => 'No route found'], 404);
    }

    // Водитель обновляет текущие координаты
    public function updateRoute(Request $request, $teamId)
    {
        $request->validate([
            'current_coordinates' => 'required|string', // текущие координаты машины
        ]);

        $currentCoordinates = $request->input('current_coordinates');
        $team = Team::findOrFail($teamId);
        $team->current_coordinates = $currentCoordinates;
        $team->save();

        $emergency = Emergency::where('team_id', $teamId)->firstOrFail();

        // Получаем маршрут от текущих координат машины до пациента
        $route = $this->googleRoutesService->getRoute($currentCoordinates, $emergency->address, $teamId);

        if ($route) {
            // Обновляем статус вызова, если машина ближе 100 метров к пациенту
            $currentCoordinatesArray = [
                'lat' => floatval(explode(',', $currentCoordinates)[0]),
                'lon' => floatval(explode(',', $currentCoordinates)[1]),
            ];
            $emergency->updateStatusIfNearDestination($currentCoordinatesArray);

            return response()->json(array_merge($route, ['patient_location' => $emergency->address]));
        }

        return response()->json(['error' => 'No route found'], 404);
    }
}
