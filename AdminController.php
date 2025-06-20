<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $count['users'] = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Sub Admin','Admin']);
            })->count();
        $count['candidate'] = Candidate::count();
        $count['posts_read'] = 0;
        $newPosts = 0;
        $topPosts = 0;

        return view('admin.index', compact('count'));
    }
}
