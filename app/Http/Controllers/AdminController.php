<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;

class AdminController extends Controller
{
    public function deleteUser($id)
    {
        try {
            // Check if the user exists
            $user = User::findOrFail($id);

            Event::where('createdUserId', $id)
            ->delete();

            $user->delete();

            return response()->json([
                "status" => 200,
                "message" => "User has been deleted successfully!"
            ]);
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

    public function addEvent(Request $request)
    {
        try {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'startDateTime' => 'required|date|after_or_equal:now',
                'endDateTime' => 'required|date|after:startDateTime',
                'venue' => 'required|string|max:255',
                'userId' => 'required|integer|exists:users,id',
            ]);



            $title = $validated['title'];
            $description = $validated['description']?? null;
            $startDateTime = $validated['startDateTime'];
            $endDateTime = $validated['endDateTime'];
            $venue = $validated['venue'];
            $userId = $validated['userId'];


            Event::create([
                'title' => $title,
                'description' => $description,
                'start_time' => $startDateTime,
                'end_time' => $endDateTime,
                'venue' => $venue,
                'createdUserId' => $userId,
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

    public function getAllUsers()
    {
        try {

            $users = User::select('id', 'name')->get();

            return response()->json([
                'status' => 200,
                'data' => $users,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
