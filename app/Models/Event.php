<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'createdUserId',
    ];

    // Define the relationship with the User model
    public function creator()
    {
        return $this->belongsTo(User::class, 'createdUserId');
    }
}
