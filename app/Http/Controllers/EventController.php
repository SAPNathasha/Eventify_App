<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function addEvent(Request $request)
    {
        try {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'startDateTime' => 'required|date|after_or_equal:now',
                'endDateTime' => 'required|date|after:startDateTime',
                'venue' => 'required|string|max:255',
            ]);



            $title = $validated['title'];
            $description = bcrypt($validated['description'])?? null;
            $startDateTime = $validated['startDateTime'];
            $endDateTime = $validated['endDateTime'];
            $venue = $validated['venue'];


            $user = Event::create([
                'title' => $title,
                'description' => $description,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'venue' => $venue,
                'createdUserId' => auth()->user()->id,
            ]);

            return response()->json([
                "status" => 200,
                "message" => "Event has created susccessfully!"
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
