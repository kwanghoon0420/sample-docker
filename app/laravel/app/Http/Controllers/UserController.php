<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::all();

//        dd($users->first()->toArray());

        return Inertia::render('User/Index', [
            'users' => $users,
        ]);
    }
}
