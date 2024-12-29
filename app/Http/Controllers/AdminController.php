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
}
