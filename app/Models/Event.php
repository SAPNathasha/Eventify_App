<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',  // The title of the event
        'description',  // A brief description of the event (optional)
        'start_time',  // The start time of the event
        'end_time',  // The end time of the event
        'venue',  // The location where the event takes place
        'createdUserId',  // The ID of the user who created the event
    ];

    public function creator()
    {
        //Links this event to the user who created 'createdUserId'
        return $this->belongsTo(User::class, 'createdUserId');
    }
}
