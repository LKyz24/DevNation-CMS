<?php

namespace App\Http\Controllers; 
use App\Models\User;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function team()
    {
        $teamMember = User::with('socialAccounts')->get();
        // dd($teamMember);
        return view('team.index', ['teamMember' => $teamMember]);
    }
}