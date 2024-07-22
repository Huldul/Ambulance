<?php

namespace App\Services;

use App\Models\Emergency;
use App\Models\Team;
use Illuminate\Support\Facades\Http;

class TeamService
{
    public function assignTeam(Emergency $emergency)
    {
        // Получаем все команды
        $teams = Team::all();

        // Находим ближайшую команду с учетом приоритета
        $assignedTeam = $this->findBestTeam($emergency, $teams);

        if ($assignedTeam) {
            // Назначаем команду вызову
            $emergency->team_id = $assignedTeam->id;
            $emergency->save();

            // Отправляем push-уведомление команде
            $this->sendPushNotification($assignedTeam->fcmid, 'New emergency assigned', 'You have a new emergency assigned with priority ' . $emergency->priority);

            return $assignedTeam;
        }

        return null;
    }

    private function findBestTeam($emergency, $teams)
    {
        $bestTeam = null;
        $bestDistance = PHP_INT_MAX;

        foreach ($teams as $team) {
            $distance = $this->calculateDistance($team->current_coordinates, $emergency->address);

            if ($team->status === 'free' || ($team->status === 'busy' && $team->priority === 'LOW' && $emergency->priority === 'HIGH')) {
                if ($distance < $bestDistance) {
                    $bestTeam = $team;
                    $bestDistance = $distance;
                }
            }
        }

        return $bestTeam;
    }

    private function calculateDistance($coords1, $coords2)
    {
        list($lat1, $lon1) = explode(',', $coords1);
        list($lat2, $lon2) = explode(',', $coords2);

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $earthRadius = 6371000;

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

    public function sendPushNotification($fcmid, $title, $message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FCM_SERVER_KEY');

        $data = [
            'to' => $fcmid,
            'notification' => [
                'title' => $title,
                'body' => $message
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json'
        ])->post($url, $data);

        return $response->json();
    }
}
