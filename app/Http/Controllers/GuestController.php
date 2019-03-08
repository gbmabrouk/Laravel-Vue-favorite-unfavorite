<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class GuestController extends Controller
{   
    public function index()
    {
        $weekMap = [
        0 => 'SU',
        1 => 'MO',
        2 => 'TU',
        3 => 'WE',
        4 => 'TH',
        5 => 'FR',
        6 => 'SA',
        ];
        
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $weekday = $weekMap[$dayOfTheWeek];
        $users = User::orderBy('counter', 'DESC')->take(10)->get();
        if (Auth::user()){
            return redirect()->route('home');
        }
        return view('guest', [
            'day' => $weekday,
            'users' => $users
            ]);
    }
}
