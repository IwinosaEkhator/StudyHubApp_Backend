<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    // List groups the user belongs to
    public function index(Request $request)
    {
        $groups = $request->user()
            ->groups()
            ->with('lastMessage')
            ->get();

        return response()->json($groups);
    }

    // Create a new group
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $group = Group::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'owner_id'    => $request->user()->id,
        ]);

        // add owner as member via pivot relationship
        $group->users()->attach($request->user()->id);

        return response()->json($group, 201);
    }

    // Add user to group
    public function addUser(Request $request, $groupId)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $group = Group::findOrFail($groupId);

        // attach user via pivot relationship, ignoring duplicates
        $group->users()->syncWithoutDetaching($data['user_id']);

        return response()->json(['message' => 'User added'], 200);
    }
}
