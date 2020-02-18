<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Form;
use App\Theme;
use App\PushSubscription;
use App\PushMessage;
use App\Notifications\PushNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Log;

class PushNotificationController extends Controller
{
    //
    public function pushes()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $form = $user->currentAccount->forms->where('form_type', 'push_notification')->first();

        $push_count = $user->currentAccount->pushSubscriptions->count();
        
        return view('push_notification.index')->with([
            'user' => $user, 
            'main_user' => $mainUser, 
            'form' => $form, 
            'push_count' => $push_count
        ]);
    }

    public function createPushForm(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'push_notification';
        $form->poll_type = $data['poll_type'];
        $form->redirect_url = null;
        $form->headline = 'Push Notification';
        $form->description = 'Subscribe here to receive our push notifications';
        $form->background_color = '#ffffff';
        $form->background_overlay = '#808080';
        $form->button_text_color = '#ffffff';
        $form->button_color = '#1678c2';
        $form->button_text = 'Subscribe';
        $form->theme_id = Theme::where('form_type', 'push_notification')->where('poll_type', $data['poll_type'])->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->autohide = false;
        $form->allow_closing = true;
        $form->desktop_device = true;
        $form->tablet_device = false;
        $form->mobile_device = false;
        $form->page_load = true;
        $form->page_exit = false;
        $form->loading_delay = 3;

        $form->save();

        $form->redirect_url = 'https://' . $form->title . '.sendmunk.com/notif/' . $form->id;
        $form->save();

        return redirect('create_push/' . $form->id)->with(['form' => $form]);
    }

    public function viewPushForm($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        $form = Form::find($id);

        if ($form) {
            if($form->sub_account_id == $user->currentAccount->id)
            {
                $themes = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->get();

                $current_theme = Theme::where('form_type', $form->form_type)->first();
                if ($form->theme_id) {
                    $current_theme = Theme::find($form->theme_id);
                }

                $rules = $form->displayRules;

                return view('push_notification.create_push')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'main_user' => $mainUser]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function editPushForm()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        $form = Form::where('form_type', 'push_notification')->where('sub_account_id', $user->currentAccount->id)->first();

        if ($form) {
            if($form->sub_account_id == $user->currentAccount->id)
            {
                $themes = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->get();

                $current_theme = Theme::where('form_type', $form->form_type)->first();
                if ($form->theme_id) {
                    $current_theme = Theme::find($form->theme_id);
                }

                $rules = $form->displayRules;

                return view('push_notification.edit_push')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'main_user' => $mainUser]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function sendNote(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $form = $user->currentAccount->forms->where('form_type', 'push_notification')->where('id', $request->form_id);

        if($form)
        {
            $subs = $user->pushSubscriptions->count();

            $push_message = new PushMessage;
            $push_message->company = $request->company;
            $push_message->user_id = $user->id;
            $push_message->title = $request->title;
            $push_message->body = $request->body;
            $push_message->website = $request->website;
            $push_message->form_id = $request->form_id;
            $push_message->push_count_recent = $subs;
            $push_message->send_date = date("Y-m-d H:i", strtotime($request->send_date));
            $push_message->date_string = date("Y-m-d H:i", (strtotime($request->send_date)  - ($user->profile->timezone->offset * 60 * 60)));

            $name = time() . '.' . Str::random(32).'.jpeg';
            
            $filePath = public_path() . '/pushImages/';
            if (!\File::isDirectory($filePath))
            { 
                \File::makeDirectory($filePath);
            }

            $location = public_path("pushImages/" . $name);
            \Image::make($request->photo)->resize(800,400)->save($location);

            $request->merge(['photo' => $name]);

            $push_message->photo = $name;

            $push_message->save();

            $now = Carbon::now();

            if($request->sending == "now")
            {
                // $request->user()->notify(new PushNotification($push_message->title, $push_message->body, $push_message->photo, $push_message->website));
                $user->notify(new PushNotification($push_message->title, $push_message->body, $push_message->photo, $push_message->website));

                $push_message->update(['delivered' => 1, 'send_date' => Carbon::now()]);
                
                if($request->ajax())
                {
                    return response()->json('Notification sent.', 201);
                }
                return back()->with('status', "Notification Sent");

            }
            else
            {
                if($request->ajax())
                {
                    return response()->json('Notification will be sent later.', 201);
                }
                return back()->with('status', "Notification will be sent later");
            }
        }
    }

    public function acceptNoti($company, $id)
    {
        $form = Form::find($id);
        return view('push_notification.notification', compact('form'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $form = Form::find($data['form_id']);
        
        // return $form->subaccount;
        if(!$form)
        {
            return;
        }

        $form->subaccount->user->updatePushSubscription(
            $data['endpoint'],
            $data['key'],
            $data['token'],
            $data['push_count']
        );

        // PushSubscription::where('form_id', $form->id)->update(['sub_account_id' => $form->sub_account_id]);

        PushSubscription::where('endpoint', $data['endpoint'])->update(['form_id' => $data['form_id'], 'sub_account_id' => $form->sub_account_id]);
        // if($inserted_form) {
        //     $inserted_form->update(['form_id' => $data['form_id']]);
        // }
    }

    public function checkCompany($company)
    {
        $form = Form::where('form_type', 'push_notification')->where('title', $company)->first();
        if($form)
        {
            return response()->json(['error' => 'Not available'], 404);
        }
        return response()->json(['message' => "Available"]);
    }
}
