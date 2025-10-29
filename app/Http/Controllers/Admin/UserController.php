<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modelss\User;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
        ]);

        // Create a new user
        $user = User::create($validatedData);

        return response()->json($user, 201);
    }

    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }
    

    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required',
        ]);

        // Update the user
        $user->update($validatedData);

        return response()->json($user);
    }

    public function destroy($id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Delete the user
        $user->delete();

        return response()->json(null, 204);
    }
    public function addPreferredDate(Request $request, $id)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'preferred_date' => 'required|date',
        ]);

        // Add the preferred date to the user
        $user->preferred_dates()->create($validatedData);

        return response()->json($user->preferred_dates, 201);
    }
}
