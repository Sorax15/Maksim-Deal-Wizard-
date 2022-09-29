<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $table = 'tracking';

    protected $fillable = [
        's_id',
        'd_id',
        'page',
        'env',
        'created',
        'info',
        'type',
        'deal_id',
        'created_at'

    ];
}

