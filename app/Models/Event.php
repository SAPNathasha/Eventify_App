<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',  // title of the event
        'description',  // brief description of the event 
        'start_time',  // start time of the event
        'end_time',  // end time of the event
        'venue',  // location of the event takes place
        'createdUserId',  // The ID of the user who created the event
    ];

    public function creator()
    {
        //Links this event to the user who created 'createdUserId'
        return $this->belongsTo(User::class, 'createdUserId');
    }
}
