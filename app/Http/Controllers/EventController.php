<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            $description = $validated['description']?? null;
            $startDateTime = $validated['startDateTime'];
            $endDateTime = $validated['endDateTime'];
            $venue = $validated['venue'];


            Event::create([
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

    public function editEvent(Request $request)
    {
        try {

            $validated = $request->validate([
                'eventId' => 'required|exists:events,id',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'startDateTime' => 'nullable|date|after_or_equal:now',
                'endDateTime' => 'nullable|date|after:startDateTime',
                'venue' => 'nullable|string|max:255',
            ]);

            $userId = auth()->id();
            $eventId = $validated['eventId'];

            $event = Event::where('id', $eventId)
            ->where('createdUserId', $userId)
            ->firstOrFail();

            $updates = [];
            if (isset($validated['title'])) {
                $updates['title'] = $validated['title'];
            }
            if (isset($validated['description'])) {
                $updates['description'] = $validated['description'];
            }
            if (isset($validated['startDateTime'])) {
                $updates['start_time'] = $validated['startDateTime'];
            }
            if (isset($validated['endDateTime'])) {
                $updates['end_time'] = $validated['endDateTime'];
            }

            $event->update($updates);


            return response()->json([
                "status" => 200,
                "message" => "Event has updated susccessfully!"
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => "Event can't be found or you do not have permission to update it.",
            ], 404);

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteEvent(Request $request)
{
    try {

        $validated = $request->validate([
            'eventId' => 'required|exists:events,id',
        ]);

        $userId = auth()->id();
        $eventId = $validated['eventId'];

        $event = Event::where('id', $eventId)
            ->where('createdUserId', $userId)
            ->firstOrFail();

        $event->delete();

        return response()->json([
            "status" => 200,
            "message" => "Event has been deleted successfully!",
        ]);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'status' => 404,
            'message' => "Event can't be found or you do not have permission to delete it.",
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Server error',
            'message' => $e->getMessage(),
        ], 500);
    }
}
}
