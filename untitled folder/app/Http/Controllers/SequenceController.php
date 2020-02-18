<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sequence;
use App\SequenceEmail;

class SequenceController extends Controller
{
    //
    public function viewSequences()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $sequences = $user->currentAccount->sequences->sortByDesc('id');

        return view('sequences.sequences')->with(['user' => $user, 'main_user' => $mainUser, 'sequences' => $sequences]);
    }

    public function createSequence()
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
        $smtps = $user->currentAccount->smtps;
        $mail_apis = $user->currentAccount->mailApis;

        if($from_reply_emails->isEmpty())
        {
            return redirect('from_reply')->with('status', "Please Add & Verify From/Reply Email(s) to create Sequence(s)");
        }

        if($smtps->isEmpty() && $mail_apis->isEmpty())
        {
            return redirect('integration')->with('status', "Add Integration(s) to create Sequence(s)");
        }

        $sequence = new Sequence();
        $sequence->sub_account_id = $user->currentAccount->id;
        $sequence->title = "New Sequence";
        $sequence->save();

        return redirect('create/'.$sequence->id.'/sequence')->with(['sequence' => $sequence]);
    }

    public function viewSequence($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence = Sequence::find($id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
                $smtps = $user->currentAccount->smtps;
                $mail_apis = $user->currentAccount->mailApis;

                $contacts = $user->currentAccount->contacts;

                $sequence->contacts()->detach();
                foreach($contacts as $contact) {
                    $sequence->contacts()->attach($contact->id);
                }

                return view('sequences.create')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'sequence' => $sequence, 'contacts' => $contacts]);
            }
            return redirect('sequences')->with('error', 'Unauthorized access to Campaign');
        }
        return redirect('sequences')->with('error', 'Campaign not found');
    }

    public function editSequence($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence = Sequence::find($id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
                $smtps = $user->currentAccount->smtps;
                $mail_apis = $user->currentAccount->mailApis;

                $contacts = $sequence->contacts;

                return view('sequences.edit')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'sequence' => $sequence, 'contacts' => $contacts]);
            }
            return redirect('sequences')->with('error', 'Unauthorized access to Campaign');
        }
        return redirect('sequences')->with('error', 'Campaign not found');
    }

    public function saveNewSequence(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $data = $request->all();

        $sequence = Sequence::find($data['sequence_id']);
        if(!$sequence)
        {
            $sequence = new Sequence();
        }
        $sequence->title = $data['title'];
        $sequence->from_reply_id = $data['from_reply_id'];

        $integration = explode('_', $data['integration']);
        if($integration[0] == 'smtp')
        {
            $sequence->smtp_id = $integration[1];
            $sequence->mail_api_id = null;
        }
        else
        {
            $sequence->mail_api_id = $integration[1];
            $sequence->smtp_id = null;
        }

        $sequence->sub_account_id = $user->currentAccount->id;
        $sequence->save();

        return redirect('sequences')->with('status', 'Sequence Saved');
    }

    
    public function activateSequence($sequence_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence = Sequence::find($sequence_id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence->status = "active";
                $sequence->save();

                return response()->json(['success' => "Sequence Activated"], 200);
            }
            return response()->json(['error' => "Unauthorized access"], 404);
        }
        return response()->json(['error' => "Sequence not found"], 404);
    }

    public function deactivateSequence($sequence_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $sequence = Sequence::find($sequence_id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence->status = "inactive";
                $sequence->save();
                
                return response()->json(['success' => "Sequence Deactivated"], 200);
            }
            return response()->json(['error' => "Unauthorized access"], 404);
        }
        return response()->json(['error' => "Sequence not found"], 404);
    }

    public function store(Sequence $sequence, Request $request)
    {
        $sequence->contacts()->detach();
        if($request->sequence_contacts){
            foreach($request->sequence_contacts as $ct) {
                $sequence->contacts()->attach($ct['id']);
            }

        }

        if($request->filter_query_string)
        {
            $sequence->filter_query = $request->filter_query_string;
            $sequence->save();
        }
    }

    public function removeSequenceContact($contact_id, $sequence_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $sequence = Sequence::find($sequence_id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                $sequence->contacts()->detach($contact_id);
                return response()->json(['campaign_contacts_count' => $sequence->contacts->count()], 200);
            }
        }
        return response()->json(['error' => "An error occured"], 404);
    }

    public function deleteSequence($sequence_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        
        $sequence = Sequence::find($sequence_id);
        if($sequence)
        {
            if($sequence->sub_account_id == $user->currentAccount->id)
            {
                if($sequence->status != 'active')
                {
                    $sequence->delete();
                    return back()->with('status', "Sequence Deleted Successfully");
                }
                return back()->with('error', "Can't delete an active sequence");
            }
            return back()->with('error', "Unathorized Access");
        }
        return back()->with('error', 'Sequence not found');
    }
}
