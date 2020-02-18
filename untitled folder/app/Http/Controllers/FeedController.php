<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\RssFeed;
use Feeds;
use Log;

class FeedController extends Controller
{
    //
    public function viewRss()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $rss_feeds = $user->currentAccount->rssFeeds;

        return view('automation.rss')->with(['user' => $user, 'main_user' => $mainUser, 'rss_feeds' => $rss_feeds]);
    }

    public function createFeed()
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
            return redirect('from_reply')->with('status', "Please Add & Verify From/Reply Email(s) to create campaign(s)");
        }

        if($smtps->isEmpty() && $mail_apis->isEmpty())
        {
            return redirect('integration')->with('status', "Add Integration(s) to create campaign(s)");
        }

        $rss_feed = new RssFeed();
        $rss_feed->sub_account_id = $user->currentAccount->id;
        $rss_feed->save();

        return redirect('create/'.$rss_feed->id.'/rss')->with(['rss' => $rss_feed]);
    }

    public function viewFeed($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $rss_feed = RssFeed::find($id);
        if($rss_feed)
        {
            if($rss_feed->sub_account_id == $user->currentAccount->id)
            {
                $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
                $smtps = $user->currentAccount->smtps;
                $mail_apis = $user->currentAccount->mailApis;

                $contacts = $user->currentAccount->contacts->where('unsubscribed', false);

                $rss_feed->contacts()->detach();
                foreach($contacts as $contact) {
                    $rss_feed->contacts()->attach($contact->id);
                }

                return view('automation.createfeed')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'rss_feed' => $rss_feed, 'contacts' => $contacts]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "RSS Feed not found");
    
        
        return view('automation.createfeed')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'contacts' => $contacts]);
    }

    public function editFeed($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $rss_feed = RssFeed::find($id);
        if($rss_feed)
        {
            if($rss_feed->sub_account_id == $user->currentAccount->id)
            {
                $from_reply_emails = $user->currentAccount->fromReplyEmails->where('confirmed', true);
                $smtps = $user->currentAccount->smtps;
                $mail_apis = $user->currentAccount->mailApis;

                $contacts = $rss_feed->contacts;

                return view('automation.editfeed')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'rss_feed' => $rss_feed, 'contacts' => $contacts]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "RSS Feed not found");
    
        
        return view('automation.createfeed')->with(['user' => $user, 'main_user' => $mainUser, 'from_reply_emails' => $from_reply_emails, 'smtps' => $smtps, 'mail_apis' => $mail_apis, 'contacts' => $contacts]);
    }

    public function store($feed_id, Request $request)
    {
        $feed = RssFeed::find($feed_id);
        if($feed)
        {
            $feed->contacts()->detach();
            if($request->feed_contacts){
                foreach($request->feed_contacts as $ct) {
                    $feed->contacts()->attach($ct['id']);
                }

            }

            if($request->filter_query_string)
            {
                $feed->filter_query = $request->filter_query_string;
                $feed->save();
            }
        }
    }

    public function saveFeed(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $data = $request->all();

        // Check if feed url is invalid
        $feed = Feeds::make($data['url']);
        if($feed->error())
        {
            return back()->with('error', $feed->error());
        }

        $rss_feed = RssFeed::find($data['feed_id']);
        if(!$rss_feed)
        {
            $rss_feed = new RssFeed();
        }

        $rss_feed->from_reply_id = $data['from_reply_id'];

        $integration = explode('_', $data['integration']);
        if($integration[0] == 'smtp')
        {
            $rss_feed->smtp_id = $integration[1];
            $rss_feed->mail_api_id = null;
        }
        else
        {
            $rss_feed->mail_api_id = $integration[1];
            $rss_feed->smtp_id = null;
        }

        $rss_feed->url = $data['url'];
        $rss_feed->sub_account_id = $user->currentAccount->id;
        $rss_feed->settings = $data['settings'];

        if($data['settings'] == 'single')
        {
            $rss_feed->digest_option = null;
            $rss_feed->day = null;
        }
        else
        {
            $rss_feed->digest_option = $data['digest_option'];
            $rss_feed->day = $data['day'];
        }

        $rss_feed->save();

        return redirect('rss')->with('status', 'Feed Saved Successfully');
    }

    public function deleteFeed($feed_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id  == 3)
        {
            $user =  $user->userLeader;
        }

        $rss_feed = RssFeed::find($feed_id);
        if($rss_feed)
        {
            if($rss_feed->sub_account_id == $user->currentAccount->id)
            {
                $rss_feed->contacts()->detach();
                $rss_feed->delete();

                return back()->with('status', 'Campaign deleted Successfully');
            }
            return back()->with('error', 'Unauthorized Access');
        }
        return back()->with('error', 'Feed not found');
    }
}
