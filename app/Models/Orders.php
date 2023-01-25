<?php

namespace App\Models;

use App\Providers\OrderEvent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Orders extends Model
{
    use HasFactory;
    use Notifiable;

    protected $dispatchesEvents = [
        'created' => OrderEvent::class,
        'saved' => OrderEvent::class,
    ];
}
