<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;

class AdminController extends Controller
{
    // delete a user with their events
    public function deleteUser($id)
    {
        try {
            // find the user
            $user = User::findOrFail($id);
            // delete all events created by the user
            Event::where('createdUserId', $id)
            ->delete();
            // delete user from DB
            $user->delete();
            // return success
            return response()->json([
                "status" => 200,
                "message" => "User has been deleted successfully!"
            ]);

        // return errors
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'error' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // add a new event
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
                'userId' => 'required|integer|exists:users,id',
            ]);


            // extract validated data
            $title = $validated['title'];
            $description = $validated['description']?? null;
            $startDateTime = $validated['startDateTime'];
            $endDateTime = $validated['endDateTime'];
            $venue = $validated['venue'];
            $userId = $validated['userId'];

            // create a new event in DB
            Event::create([
                'title' => $title,
                'description' => $description,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'venue' => $venue,
                'createdUserId' => $userId,
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

    //fetch all users
    public function getAllUsers()
    {
        try {
            // retrieve all users by id and name
            $users = User::select('id', 'name')->get();
            // return success
            return response()->json([
                'status' => 200,
                'data' => $users,
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
