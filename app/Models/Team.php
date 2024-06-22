<?php
// app/Models/Team.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['car', 'driver', 'feldsher', 'type', 'current_coordinates'];

    // Связь с вызовами
    public function emergencies()
    {
        return $this->hasMany(Emergency::class);
    }
}


