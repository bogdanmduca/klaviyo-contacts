<?php

namespace App\Models;

use App\Events\ContactCreated;
use App\Events\ContactUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = ['id']; //faster

    protected $dispatchesEvents = [
        'created' => ContactCreated::class,
        'updated' => ContactUpdated::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
