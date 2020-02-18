<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\RssFeed;
use App\Campaign;
use Log;
use Carbon\Carbon;

class DigestFeedToCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeddigest:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RSS Feeds to Campaign (digest)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        RssFeed::where('settings', 'digest')->whereDate('sent_at', '<', Carbon::now())->orWhereNull('sent_at')->each(function($rss_feed){
            if($rss_feed->feedContents->where('sent', false)->count() > 0)
            {
                if($rss_feed->digest_option == 'daily')
                {
                    $this->processFeed($rss_feed);
                }
                else if($rss_feed->digest_option = 'weekly')
                {
                    $dayOfWeek = date("l", (strtotime(Carbon::now()) - ($rss_feed->subaccount->user->profile->timezone->offset * 60 * 60)));
                    if($rss_feed->day == strtolower($dayOfWeek))
                    {
                        $this->processFeed($rss_feed);
                    }
                }
            }
        });
    }

    public function processFeed($rss_feed)
    {
        $result = "";
        $subject = "";

        $campaign = new Campaign();
        $campaign->status = 'draft';
        $campaign->actual_send_date = Carbon::now();
        $campaign->from_reply_id = $rss_feed->from_reply_id;
        $campaign->mail_api_id = $rss_feed->mail_api_id;
        $campaign->smtp_id = $rss_feed->smtp_id;
        $campaign->sub_account_id = $rss_feed->sub_account_id;
        $campaign->save();

        foreach($rss_feed->feedContents as $feed_content)
        {
            // change sent state of the feed content
            if(!$feed_content->sent)
            {
                if($subject == "")
                {
                    $subject = $feed_content->title;
                }
                $feed_content->sent = true;
                $feed_content->campaign_id = $campaign->id;
                $feed_content->save();

                $has_image = preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $feed_content->content, $image);
                    
                $description = $feed_content->description;

                $main_description = "";
                if($has_image)
                {
                    $main_description = '<img style="max-width: 100%; margin: 20px auto; display: block; border: 1px solid #ddd; border-radius: 6px;" class="feed-item-image" src="' . $image['src'] . '" />';

                }
                $main_description .= '<div class="feed-description">' . $description;
                $main_description .= ' <a href="'.$feed_content->permanent_link.'" title="'.$feed_content->title.'">Continue Reading &raquo;</a>'.'</div>';

                $result .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background: none repeat scroll 0% 0% / auto padding-box border-box;">
                    <tbody>
                        <tr>
                            <td>
                                <div style="margin:0 auto;width:600px;padding:0px">
                                    <table class="main" width="100%" cellspacing="0" cellpadding="0" border="0" align="center"  style="border-spacing: 0px; border-collapse: collapse; text-size-adjust: 100%;">
                                        <tbody>
                                            <tr>
                                                <td align="left" class=" element-content" style="padding: 5px 50px; background-color: rgb(255, 255, 255); border-collapse: collapse; text-size-adjust: 100%;">
                                                    <hr/>
                                                    <h1 >'.$feed_content->title.'</h1>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="main" cellspacing="0" cellpadding="0" border="0" style="background-color: rgb(255, 255, 255); border-spacing: 0px; border-collapse: collapse; text-size-adjust: 100%;" align="center">
                                        <tbody>
                                            <tr>
                                                <td class="element-content"  style="padding: 30px; font-family: Arial; font-size: 13px; color: rgb(0, 0, 0); line-height: 22px; text-align: left; border-collapse: collapse; text-size-adjust: 100%;">'.$main_description.'
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>';
            }
        }
        if($result != "" && $subject != "")
        {
            
            $campaign->title = $subject . " (RSS)";
            $campaign->subject_a = $subject;
            $campaign->content = $result;

            $campaign->contacts()->detach();
            if($rss_feed->contacts){
                foreach($rss_feed->contacts as $ct) {
                    $campaign->contacts()->attach($ct->id);
                }

            }

            $campaign->filter_query = $rss_feed->filter_query_string;
            $campaign->status = 'sent';
            $campaign->save();

            $rss_feed->sent_at = Carbon::now();
            $rss_feed->save();
        }
    }
}
