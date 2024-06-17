<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'address', 'for_whom', 'status', 'team_id', 'call_time', 'review', 'rating'
    ];
}
