<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends Controller
{
    // Add new event
    public function addEvent(Request $request)
    {
        try {
            // validate requested data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'startDateTime' => 'required|date|after_or_equal:now',
                'endDateTime' => 'required|date|after:startDateTime',
                'venue' => 'required|string|max:255',
            ]);


            // assign validated data
            $title = $validated['title'];
            $description = $validated['description']?? null;
            $startDateTime = $validated['startDateTime'];
            $endDateTime = $validated['endDateTime'];
            $venue = $validated['venue'];

            // create a new event
            Event::create([
                'title' => $title,
                'description' => $description,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'venue' => $venue,
                'createdUserId' => auth()->user()->id,
            ]);

            // return success
            return response()->json([
                "status" => 200,
                "message" => "Event has created susccessfully!"
            ]);
        } catch (\Exception $e) {
            // return errors
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // edit the event
    public function editEvent(Request $request)
    {
        try {
            // validate requested data
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

            // fetch the event
            $event = Event::where('id', $eventId)
            ->where('createdUserId', $userId)
            ->firstOrFail();

            // build updates
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
            // update the event in DB
            $event->update($updates);

            // return success
            return response()->json([
                "status" => 200,
                "message" => "Event has updated susccessfully!"
            ]);
            // return errors
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => "Event can't be found or you do not have permission to update it.",
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // delete the event
    public function deleteEvent(Request $request)
    {
        try {
            // validate requested data
            $validated = $request->validate([
                'eventId' => 'required|exists:events,id',
            ]);

            $userId = auth()->id();
            $eventId = $validated['eventId'];

            // fetch the event
            $event = Event::where('id', $eventId)
                ->where('createdUserId', $userId)
                ->firstOrFail();
            // delete event in DB
            $event->delete();
            // return success
            return response()->json([
                "status" => 200,
                "message" => "Event has been deleted successfully!",
            ]);
        // return errors
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


    // fetch all the events
    public function getAllEvents()
    {
        try {
        // Retrieve all events with creator's ID and name
        $events = Event::with('creator:id,name')->get();
        // return success
        return response()->json([
            'status' => 200,
            'data' => $events,
        ]);
        // return errors
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEventsByUser()
    {
        try {
            $userId = auth()->id();

            $events = Event::where('createdUserId', $userId)
            ->select('id','title')->get();
            return response()->json([
                'status' => 200,
                'data' => $events,
            ]);

        // return errors
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
