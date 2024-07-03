<?php
// app/Models/Emergency.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\GoogleRoutesService;


class Emergency extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'address', 'for_whom', 'status', 'driver_name', 'team_id', 'call_time', 'review', 'rating','priority'
    ];
    protected $appends = ['route'];

    public function getRouteAttribute()
    {
        if ($this->team_id) {
            $team = Team::find($this->team_id);
            $origin = $team->current_coordinates;
            $googleRoutesService = resolve(GoogleRoutesService::class);
            return $googleRoutesService->getRoute($origin, $this->address, $this->team_id);
        }

        return null;
    }
    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с командой
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Метод для обновления статуса, если команда находится в пределах 100 метров от пациента
    public function updateStatusIfNearDestination($currentCoordinates)
    {
        $destinationCoordinates = $this->getDestinationCoordinates();
        $distance = $this->calculateDistance($currentCoordinates, $destinationCoordinates);

        if ($distance < 100) {
            $this->status = 'Delivered Time to Patient';
            $this->save();
        }
    }

    // Метод для получения координат пациента из адреса
    protected function getDestinationCoordinates()
    {
        // Предполагается, что address содержит координаты в формате "lat,lon"
        $parts = explode(',', $this->address);
        return [
            'lat' => floatval($parts[0]),
            'lon' => floatval($parts[1]),
        ];
    }

    // Метод для расчета расстояния между двумя точками (координаты)
    protected function calculateDistance($point1, $point2)
    {
        $earthRadius = 6371000; // Радиус Земли в метрах

        $latFrom = deg2rad($point1['lat']);
        $lonFrom = deg2rad($point1['lon']);
        $latTo = deg2rad($point2['lat']);
        $lonTo = deg2rad($point2['lon']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
          cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}

