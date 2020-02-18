<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Member;
use App\User;
use App\Country;
use App\TimeZones;
use App\Profile;
use App\FromReplyEmail;
use App\Campaign;

class SettingsController extends Controller
{
    //
    public function viewProfile()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $profile = $user->profile;
        $countries = Country::all();
        $time_zones = TimeZones::all();

        return view('settings.profile')->with(['user'=> $user, 'main_user' => $mainUser, 'countries' => $countries, 'time_zones' => $time_zones, 'profile' => $profile]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $profile = $user->profile;
        if(!$profile)
        {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }
        $profile->address= $data['address'];
        $profile->country_id = $data['country_id'];
        $profile->state = $data['state'];
        $profile->city = $data['city'];
        $profile->zip_code = $data['zip_code'];
        $profile->time_zone_id = $data['time_zone_id'];
        $profile->save();

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function userTeam(){
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id == 3)
        {
            return view('errors.default');;
        }

        if(!$user->profile)
        {
            return redirect('settings')->with('status', "Please update your profile");
        }

        $members = $user->members;

        return view('settings.team')->with(['user'=> $user, 'members' => $members, 'main_user' => $mainUser]);
    }

    public function addTeamMember(Request $request){
        $data = $request->all();
        $user = Auth::user();
        if($user->role_id == 3)
        {
            return view('errors.default');;
        }

        if(User::where('email', $data['email'])->first()){
            return back()->with('error', 'Already a user of Sendmunk');
        }

        if(Member::where('email', $data['email'])->first()){
            return back()->with('error', 'Invitation Sent already');
        }

        do{
            $token = str_random();
        }
        while(Member::where('token', $token)->first());

        $member = new Member();
        $member->email = $data['email'];
        $member->token = $token;
        $member->role = $data['role'];
        $member->leader_user_id = $user->id;
        $member->save();

        if($member){
            $data['leader_name'] = $user->name;
            $data['token'] = $member->token;
            \Mail::send('emails.invite', $data, function($mail) use($data){
                $mail->to($data['email']);
                $mail->from('support@pixlypro.com');
                $mail->subject('Sendmunk Invitation');
            });
            return back()->with('status','Invitation Sent Successfully');
        }
        return back()->with('status','Invitation not sent');
    }

    public function deleteTeamMember($id){
        $user = Auth::user();
        if($user->role_id == 3)
        {
            return view('errors.default');;
        }

        $member = Member::find($id);
        if($member){
            if($user->id == $member->leader_user_id)
            {
                if($member->user_id)
                {
                    $user = User::find($member->user_id);
                    if($user)
                    {
                        $user->delete();
                    }
                }
                $member->delete();
                return back()->with('status','Member Removed Sucessfully');
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error','Member not found');
    }

    //update member role
    public function updateMemberRole(Request $request)
    {
        $user = Auth::user();
        if($user->role_id == 3)
        {
            return view('errors.default');;
        }

        $data = $request->all();
        $member = Member::find($data['member_id']);
        if($member)
        {
            if($user->id == $member->leader_user_id)
            {
                $member->role = $data["role"];
                $member->save();
                return back()->with('status', "Member role updated Successfully");
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Member not found");
    }

    // accept members
    public function accept($token)
    {
        if(!$member = Member::where('token', $token)->first()){
            abort(404);
        }
        return view('auth.accept')->with(['member' => $member]);
    }

    public function registerMember(Request $request)
    {
        $data = $request->all();
        $member = Member::find($data['member_id']);

        if($member)
        {
            $user = new User();
            $user->fill($data);
            $user->password = bcrypt($user->password);
            $user->role_id = 3;
            $user->leader_id = $member->leader_user_id;
            $user->save();

            $member->confirmed = 1;
            $member->name = $user->name;
            $member->token = null;
            $member->user_id = $user->id;
            $member->save();

            Auth::loginUsingId($user->id);
            return redirect('forms');
        }
        return back()->with(['error' => 'An error occurred']);
    }

    public function changePassword(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        $current_password = $user->password;
        if(Hash::check($data['current_password'], $current_password))
        {
            $user->password = Hash::make($data['new_password']);;
            $user->save();
            return back()->with(['status' => 'Password Changed successfully']);
        }
        return back()->with(['error' => 'Password Update Failed']);
    }

    public function viewFromReply()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if(!$user->profile)
        {
            return redirect('settings')->with('status', "Please update your profile");
        }

        $emails = $user->currentAccount->fromReplyEmails;
        // dd($emails);

        // return view('settings.from_reply')->with(['user' => $user, 'main_user' => $mainUser, 'emails' => $emails]);
        return view('settings.from_reply')->with(['user' => $user, 'main_user' => $mainUser, 'emails' => $emails]);
    }

    public function addFromReplyEmail(Request $request){
        $data = $request->all();
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }


        if(FromReplyEmail::where('email', $data['email'])->where('sub_account_id', $user->currentAccount->id)->first()){
            return back()->with('error', 'Verification email sent already');
        }

        do{
            $token = str_random();
        }
        while(FromReplyEmail::where('token', $token)->first());

        $from_reply_email = new FromReplyEmail();
        $from_reply_email->email = $data['email'];
        $from_reply_email->token = $token;
        $from_reply_email->sub_account_id = $user->currentAccount->id;
        $from_reply_email->save();

        if($from_reply_email){
            $data['leader_name'] = $user->name;
            $data['token'] = $from_reply_email->token;
            \Mail::send('emails.verify', $data, function($mail) use($data){
                $mail->to($data['email']);
                $mail->from('support@pixlypro.com');
                $mail->subject('Sendmunk Email Verification');
            });
            return back()->with('status','Verification Email Sent Successfully');
        }
        return back()->with('error','Verification Email not sent');
    }

    // accept members
    public function verifyEmail($token)
    {
        $from_reply_email = FromReplyEmail::where('token', $token)->first();

        if(!$from_reply_email){
            abort(404);
        }

        $from_reply_email->token = null;
        $from_reply_email->confirmed = 1;
        $from_reply_email->save();

        return redirect('from_reply')->with('status', "Email Verified Successfully");
    }

    public function deleteFromReplyEmail($id){
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $from_reply_email = FromReplyEmail::find($id);
        if($from_reply_email){
            $campaigns = Campaign::where('from_reply_id', $from_reply_email->id)->where('sub_account_id', $user->currentAccount->id)->get();
            if($campaigns->isEmpty())
            {
                $from_reply_email->delete();
                return back()->with('status','Email Removed Sucessfully');
            }
            return back()->with('error', "Sorry, you can't delete an email used in a campaign");
        }
        return back()->with('error','Error on Removing Email');
    }

    public function enableDoubleOptin()
    {
        $user = Auth::user();
        if($user->role_id == 3)
        {
            $user = $user->userLeader;
        }
        
        $user->double_optin = 1;
        $user->save();
    }

    public function disableDoubleOptin()
    {
        $user = Auth::user();
        if($user->role_id == 3)
        {
            $user = $user->userLeader;
        }
        $user->double_optin = 0;
        $user->save();
    }

    public function enableGdpr()
    {
        $user = Auth::user();
        if($user->role_id == 3)
        {
            $user = $user->userLeader;
        }
        
        $user->gdpr = 1;
        $user->save();
    }

    public function disableGdpr()
    {
        $user = Auth::user();
        if($user->role_id == 3)
        {
            $user = $user->userLeader;
        }
        $user->gdpr = 0;
        $user->save();
    }
}
