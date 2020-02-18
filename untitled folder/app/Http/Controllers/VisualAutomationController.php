<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;
use App\Template;
use Image;

class VisualAutomationController extends Controller
{
    //
    public function viewWorkflows()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $templates = $user->currentAccount->templates->where('is_template', false);

        return view('automation.visual_automation')->with(['user'=> $user, 'main_user' => $mainUser, 'templates' => $templates]);
    }

    public function createWorkflow()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        return view('automation.create_workflow')->with(['user'=> $user, 'main_user' => $mainUser]);
    }
}
