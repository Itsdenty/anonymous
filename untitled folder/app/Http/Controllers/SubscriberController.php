<?php

namespace App\Http\Controllers;

use App\Form;
use App\Subscription;
use App\UnverifiedSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Campaign;
use App\SequenceEmail;
use App\Contact;
use App\GeneralUnsubscriber;
use App\UnsubscribeReason;
use App\Activity;
use Carbon\Carbon;

class SubscriberController extends Controller
{
    //
    public function viewSubscribers($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id == 3)
        {
            $user = $user->userLeader;
        }
        $form = Form::find($id);
        if($form)
        {
            if($form->sub_account_id != $user->currentAccount->id)
            {
                return redirect('forms');
            }
            $subscribers = $form->subscriptions;
            return view('forms.subscribers')->with(['user' => $user, 'subscribers' => $subscribers, 'main_user' => $mainUser]);
        }

        return back()->with('error', 'Form not found');
    }

    public function verify($token)
    {
        $subscriber = UnverifiedSubscriber::where('token', $token)->first();

        if(!$subscriber){
            abort(404);
        }

        $main_subscriber = new Subscription();
        $main_subscriber->email = $subscriber->email;
        $main_subscriber->phone = $subscriber->phone;
        $main_subscriber->form_id = $subscriber->form_id;
        $main_subscriber->save();

        $vdata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));

        $contact = new Contact();
        $contact->email = $subscriber->email;
        $contact->phone = $subscriber->phone;
        $contact->sub_date = Carbon::now();
        $contact->sub_account_id = $form->sub_account_id;
        $contact->country_name = $vdata['geoplugin_countryName'];
        $contact->form_id = $form->id;
        $contact->save();

        $subscriber->delete();

        $form = Form::find($subscriber->form_id);
        if($form)
        {
            Activity::create(['content' => 'Added to Contact List from '. $form->title .'('. $form->form_type .')', 'category' => 'added', 'contact_id' => $contact->id]);
        }

        return "Thank you for Subscribing";
    }

    // unsubscribe for Sendmunk contacts (Campaign)
    public function unsubscribeContactView($campaign_id, $contact_id)
    {
        $campaign = Campaign::find($campaign_id);
        $contact = Contact::find($contact_id);

        if($contact)
        {
            return view('unsubscribe.unsubscribe')->with(['campaign' => $campaign, 'contact' => $contact]);
        }

        return "An error occured";
    }

    public function unsubscribeContact(Request $request)
    {
        $data = $request->all();

        if(isset($data['reason_id']))
        {
            $unsubscribe_reason = UnsubscribeReason::find($data['reason_id']);
            if($unsubscribe_reason)
            {
                $unsubscribe_reason->count += 1;
                $unsubscribe_reason->save();
            }
        }

        $campaign = Campaign::find($data['campaign_id']);
        $contact = Contact::find($data['contact_id']);
        if($contact)
        {
            $user = $campaign->subaccount->user;
            if($contact->unsubscribed)
            {
                if($campaign)
                {
                    $campaign->contacts()->updateExistingPivot($contact->id, ['unsubscribed' => false]);
                    $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
                    if($general_unsubscriber)
                    {
                        $general_unsubscriber->delete();
                    }
                }
                $contact->unsubscribed = false;
                $message = "Subscription Successfull";
            }
            else
            {
                if($campaign)
                {
                    $campaign->contacts()->updateExistingPivot($contact->id, ['unsubscribed' => true]);
                    $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
                    if(!$general_unsubscriber)
                    {
                        $general_unsubscriber = new GeneralUnsubscriber();
                        $general_unsubscriber->email = $contact->email;
                        $general_unsubscriber->user_id = $user->id;
                        $general_unsubscriber->save();
                    }
                    Activity::create(['content' => 'Unsubscribed Campaign "'. $campaign->title .'"', 'category' => 'unsubscribes', 'contact_id' => $contact->id]);
                }
                $contact->unsubscribed = true;

                $message = "Unsubscription Successfull";
            }
            $contact->save();
            return back()->with('status', $message);
        }
        return back()->with('error', "Contact not found");
    }

    public function changeEmailView($campaign_id, $contact_id)
    {
        $campaign = Campaign::find($campaign_id);
        $contact = Contact::find($contact_id);

        if($contact)
        {
            return view('unsubscribe.changeemail')->with(['campaign' => $campaign, 'contact' => $contact]);
        }

        return "An error occured";
    }

    public function changeEmail(Request $request)
    {
        $data = $request->all();

        $contact = Contact::find($data['contact_id']);
        if($contact)
        {
            $user = $contact->subaccount->user;
            $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
            if($general_unsubscriber)
            {
                $general_unsubscriber->email = $data['email'];
                $general_unsubscriber->save();
            }
            
            $contact->email = $data['email'];
            $contact->save();
            return back()->with('status', "Email Changed Successfully");
        }
        return back()->with('error', "Contact not found");
    }

    // unsubscribe for Sendmunk contacts (SequenceEmail)
    public function unsubscribeContactViewSeq($email_id, $contact_id)
    {
        $email = SequenceEmail::find($email_id);
        $contact = Contact::find($contact_id);

        if($contact)
        {
            return view('unsubscribe.unsubscribeseq')->with(['email' => $email, 'contact' => $contact]);
        }

        return "An error occured";
    }

    public function unsubscribeContactSeq(Request $request)
    {
        $data = $request->all();

        if(isset($data['reason_id']))
        {
            $unsubscribe_reason = UnsubscribeReason::find($data['reason_id']);
            if($unsubscribe_reason)
            {
                $unsubscribe_reason->count += 1;
                $unsubscribe_reason->save();
            }
        }

        $email = SequenceEmail::find($data['email_id']);
        $contact = Contact::find($data['contact_id']);
        if($contact)
        {
            $user = $campaign->subaccount->user;
            if($contact->unsubscribed)
            {
                if($email)
                {
                    $email->contacts()->updateExistingPivot($contact->id, ['unsubscribed' => false]);
                    $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
                    if($general_unsubscriber)
                    {
                        $general_unsubscriber->delete();
                    }
                }
                $contact->unsubscribed = false;
                $message = "Subscription Successfull";
            }
            else
            {
                if($email)
                {
                    $email->contacts()->updateExistingPivot($contact->id, ['unsubscribed' => true]);
                    $general_unsubscriber = new GeneralUnsubscriber();
                    $general_unsubscriber->email = $contact->email;
                    $general_unsubscriber->user_id = $user->id;
                    $general_unsubscriber->save();

                    Activity::create(['content' => 'Unsubscribed Sequence "'. $email->sequence->title .'"', 'category' => 'unsubscribes', 'contact_id' => $contact->id]);
                }
                $contact->unsubscribed = true;

                $message = "Unsubscription Successfull";
            }
            $contact->save();
            return back()->with('status', $message);
        }
        return back()->with('error', "Contact not found");
    }

    public function changeEmailViewSeq($email_id, $contact_id)
    {
        $email = SequenceEmail::find($email_id);
        $contact = Contact::find($contact_id);

        if($contact)
        {
            return view('unsubscribe.changeemailseq')->with(['email' => $email, 'contact' => $contact]);
        }

        return "An error occured";
    }

    public function changeEmailSeq(Request $request)
    {
        $data = $request->all();

        $contact = Contact::find($data['contact_id']);
        if($contact)
        {
            $user = $contact->subaccount->user;
            $general_unsubscriber = GeneralUnsubscriber::where('email', $contact->email)->where('user_id', $user->id)->first();
            if($general_unsubscriber)
            {
                $general_unsubscriber->email = $data['email'];
                $general_unsubscriber->save();
            }
            
            $contact->email = $data['email'];
            $contact->save();
            return back()->with('status', "Email Changed Successfully");
        }
        return back()->with('error', "Contact not found");
    }
}
