<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class BotController extends Controller
{
    //

    public function viewLogic()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $templates = $user->currentAccount->templates->where('is_template', false);

        return view('bot.bot_logic')->with(['user'=> $user, 'main_user' => $mainUser, 'templates' => $templates]);

    }

    public function createBotLogic()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        return view('bot.bots')->with(['user' => $user, 'main_user' => $mainUser]);
    }
}
