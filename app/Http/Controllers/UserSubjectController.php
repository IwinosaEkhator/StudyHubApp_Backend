<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserSubjectController extends Controller
{

    public function index(User $user)
    {
        // eager-load or just return the pivot
        return response()->json(
            $user->subjects()->get(['id', 'name'])
        );
    }

     public function store(Request $req, User $user)
    {
        $req->validate(['subjects' => 'required|array']);
        $user->subjects()->sync($req->input('subjects'));
        return response()->json(['message' => 'Preferences saved']);
    }
}
