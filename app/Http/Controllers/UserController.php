<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // optionally exclude the current user:
        $me = $request->user()->id;

        // you may want to paginate or filter by search term in $request->q
        $q = User::where('id','!=',$me);

        if ($search = $request->query('q')) {
            $q->where('username','like',"%{$search}%");
        }

        // return all or paginate
        $users = $q->select(['id','username','profile_photo'])->get();

        return response()->json($users);
    }
}
