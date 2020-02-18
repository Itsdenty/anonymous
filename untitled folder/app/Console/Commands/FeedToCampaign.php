<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RssFeed;
use App\FeedContent;
use Carbon\Carbon;
use Log;
use Feeds;

class FeedToCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RSS Feeds to Feed Content';

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
        RssFeed::whereNotNull('url')->each(function($rss_feed){
            
            $feed = Feeds::make($rss_feed->url, true);
            if($feed->error())
            {
                // Ignore
            }
            else
            {
                $data = array(
                    'title'     => $feed->get_title(),
                    'permalink' => $feed->get_permalink(),
                    'items'     => $feed->get_items(),
                );
                // Log::info($data['title']);
    
                foreach($data['items'] as $item)
                {
                    $feedcontent = FeedContent::where('permanent_link', $item->get_permalink())->where('rss_feed_id', $rss_feed->id)->first();

                    if(!$feedcontent)
                    {
                        if(strtotime($item->get_gmdate()) >= strtotime($rss_feed->created_at))
                        {
                            $feedcontent = new FeedContent();
                            $feedcontent->permanent_link = $item->get_permalink();
                            $feedcontent->title = $item->get_title();
                            $feedcontent->content = $item->get_content();
                            $feedcontent->description = $item->get_description();
                            if($item->get_author())
                            {
                                $feedcontent->author = $item->get_author()->get_name();
                            }
                            $feedcontent->rss_feed_id = $rss_feed->id;
                            $feedcontent->save();
                        }
                    }
                }
            }
        });

    }
}
