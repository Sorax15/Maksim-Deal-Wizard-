<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    protected $table = 'track';

    protected $fillable = [
        'deal_id',
        's_id',
        'd_id',
        'vin',
        'source',
        'contact',
        'trade',
        'payment',
        'appointment',
        'credit',
        'views',
        'ip',
        'is_mobile',
        'created_at',
        'updated_at'

    ];
}

