@extends('layouts.user')
@section('title', 'Sendmunk | SMS/MMS Campaign Analysis')

@section('styles')
    <style>
        .summary{
            text-align: center;
        }
        .ui.divided.list>.item{
            padding: 5px !important;
        }
    </style>
@endsection

@section('content')
    @if(Session::has('error'))
    <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
            {{ Session::get('error') }}
        </div>
    </div>
    @endif
    @if(Session::has('status'))
    <div class="ui positive message">
        <i class="close icon"></i>
        <div class="header">
            {{ Session::get('status') }}
        </div>
    </div>
    @endif
    <div class="ui vertical segment">
        <h2>SMS/MMS Campaign Analysis</h2>
    </div>
    <br/>
    <div class="ui pointing secondary stackable menu">
        <a class="item active" data-tab="summary">Summary</a>
        <a class="item" data-tab="content">Content</a>
        <a class="item" data-tab="openers_clickers">Clickers</a>
        <a class="item" data-tab="clicked_link">Clicked Links</a>
    </div>

    <div class="ui tab active" data-tab="summary">
        <div class="ui vertically stackable grid">
            <div class="two column row">
                    <div class="summary column">
                        <div class="ui segment">
                            <div class="ui tiny progress" data-percent="{{ $sms_campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$sms_campaign->contacts()->wherePivot('sent', true)->count() / $sms_campaign->contacts()->count() * 100, 2, '.', '')}}">
                                <div class="bar"></div>
                                <div class="label">{{ $sms_campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$sms_campaign->contacts()->wherePivot('sent', true)->count() / $sms_campaign->contacts()->count() * 100, 2, '.', '')}}%</div>
                            </div>
                            <h1 style="font-size: 50px;">{{ $sms_campaign->contacts()->wherePivot('sent', true)->count() }}</h1>
                            <p>Sent</p>
                        </div>
                    </div>
                <div class="summary column">
                    <div class="ui segment">
                        <div class="ui tiny progress" data-percent="{{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() == 0 || $sms_campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() / $sms_campaign->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}">
                            <div class="bar"></div>
                            <div class="label">{{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() == 0 || $sms_campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() / $sms_campaign->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</div>
                        </div>
                        <h1 style="font-size: 50px;">{{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() }}</h1>
                        <p>Clicked</h3>
                    </div>
                </div>
            </div>
            <div class="two column row">
                <div class="column">
                    <div class="ui segment">
                        <h3>Details</h3>
                        <hr/>
                        <p>
                            <strong>From</strong>
                            <br/>
                            {{ \App\SmsMmsIntegration::find($sms_campaign->sms_mms_integration_id) ? \App\SmsMmsIntegration::find($sms_campaign->sms_mms_integration_id)->twilio_number : "" }}
                        </p>
                        <p>
                            <strong>No of Contacts</strong>
                            <br/>
                            {{ $sms_campaign->contacts->count() }}
                        </p>
                        <P>
                            <strong>Delivered on</strong>
                            <br/>
                            {{ (new DateTime($sms_campaign->send_date))->format('M j, Y H:i:s') }}
                        </P>
                    </div>
                    <div class="ui segment">
                        <h3>Filters</h3>
                        <hr/>
                        @if(!$sms_campaign->filter_query)
                        No Filters added
                        @else
                        <?php
                            $output = "";
                            parse_str($sms_campaign->filter_query, $get_array);
                            $query_columns = $get_array["f"];
                            
                            foreach($query_columns as $column)
                            {
                                foreach($column as $key=>$col)
                                {
                                    $value = $col;

                                    if($key == "query_1" || $key == "query_2")
                                    {
                                        $value = "<strong><em>$col</em></strong>";
                                    }
                                    $output .= $value . " ";
                                }
                                $output .= "<br/>";
                                $output .= $get_array["filter_match"];
                                $output .= "<br/>";
                            }

                            echo rtrim($output, 'and<br/>');
                        ?>
                        @endif
                    </div>
                </div>
                <div class="column">
                    <div class="ui segment">
                        <div class="ui middle aligned divided list">
                            <div class="item">
                                <div class="right floated content">
                                    <?php
                                        $time = $average_time_to_click;
                                        $output = "";
                                        $days = floor($time / (60 * 60 * 24));
                                        if($days > 0)
                                        {
                                            $output = $output.$days."d, ";
                                        }
                                        $time -= $days * (60 * 60 * 24);
                
                                        $hours = floor($time / (60 * 60));
                                        $output = $output.$hours."h, ";

                                        $time -= $hours * (60 * 60);

                                        $minutes = floor($time / 60);
                                        $output = $output.$minutes."m, ";
                                        $time -= $minutes * 60;

                                        $seconds = floor($time);
                                        $time -= $seconds;
                                        $output = $output.$seconds."s";

                                        echo $output;
                                    ?>
                                </div>
                                <div class="content">
                                    Average Time to Click 
                                </div>
                            </div>
                        </div>

                        <div class="ui middle aligned divided list">
                            <div class="item">
                                <div class="right floated content">
                                    <h3>{{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() == 0 || $sms_campaign->contacts()->wherePivot('sent', true)->count() == 0 ? '0' :  number_format((float)$sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() / $sms_campaign->contacts()->wherePivot('sent', true)->count() * 100, 2, '.', '')}}%</h3>
                                </div>
                                <div class="content">
                                    <h3>Click Rate</h3>
                                </div>
                            </div>
                            <div class="item">
                                <div class="right floated content">
                                    {{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() }}
                                </div>
                                <div class="content">
                                    Unique Clicks 
                                </div>
                            </div>
                            <div class="item">
                                <div class="right floated content">
                                    {{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->sum('pivot.click_count') }}
                                </div>
                                <div class="content">
                                    Total Clicks 
                                </div>
                            </div>
                            <div class="item">
                                <div class="right floated content">
                                    {{ $last_click_time }}
                                </div>
                                <div class="content">
                                    Last Clicked 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui tab" data-tab="content">
        <div style="min-height:300px" class="ui segment">
            {!! $sms_campaign->content !!}
            <br/>
            @if($sms_campaign->image_url)
            <div id="picture_div" class="ui vertically stackable grid">
                <div class="three column row">
                    <div class="column"></div>
                    <div class="column">
                        <div class="ui small image">
                            <img style="width:100%" id="profilePic" src="{{ url($sms_campaign->image_url) }}" alt="MMS Image" />
                        </div>
                    </div>
                    <div class="column"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="ui tab" data-tab="openers_clickers">
        <div class="ui top attached tabular menu">
            <a class="item active" data-tab="clickers">Clickers</a>
        </div>
        <div class="ui bottom attached active tab segment" data-tab="clickers">
            <h4>No of Clickers: {{ $sms_campaign->links->map(function($link){return $link->contacts;})->flatten()->unique('id')->count() }} <a class="ui mini button" href="{{ url('downloadsmsclickerslist').'/'.$sms_campaign->id }}"><i class="download icon"></i> Download Clickers List (CSV)</a></h4>
            <div style="height: 400px" id="clickers_map"></div>
            <br/>
            <div class="ui vertically stackable grid">
                <div class="ten wide column">
                    <table class="ui celled table">
                        <thead>
                            <tr>
                                <th width="70%">COUNTRY</th>
                                <th>CLICKERS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($country_click_list as $key=>$country)
                            <tr>
                                <td>{{ $key ? $key : 'localhost' }}</td>
                                <td>{{ $country }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="six wide column">
                    <div class="ui top attached tabular menu">
                        <a class="item active" data-tab="domain_names">Domain Names</a>
                        <a class="item" data-tab="device">Device</a>
                        <a class="item" data-tab="browsers">Browsers</a>
                    </div>
                    <div class="ui bottom attached active tab segment" data-tab="domain_names">
                        <div class="ui middle aligned divided list">
                            @foreach($domain_click_list as $key=>$domain)
                            <div class="item">
                                <div class="right floated content">
                                    {{ $domain }}
                                </div>
                                <div class="content">
                                    {{ $key }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="ui bottom attached tab segment" data-tab="device">
                        <div class="ui middle aligned divided list">
                            @foreach($device_click_list as $key=>$device)
                            <div class="item">
                                <div class="right floated content">
                                    {{ $device }}
                                </div>
                                <div class="content">
                                    {{ $key }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="ui bottom attached tab segment" data-tab="browsers">
                        <div class="ui middle aligned divided list">
                            @foreach($browser_click_list as $key=>$browser)
                            <div class="item">
                                <div class="right floated content">
                                    {{ $browser }}
                                </div>
                                <div class="content">
                                    {{ $key }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ui tab" data-tab="clicked_link">
        <div class="ui segment">
            @if(!$sms_campaign->links->isEmpty())
            <table class="ui celled table">
                <thead>
                    <tr>
                        <th width="60%">LINKS</th>
                        <th>CLICK COUNT</th>
                        <th>CLICK RATE</th>
                        <th>CONTACTS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sms_campaign->links as $link)
                    <tr>
                        <td>{{ $link->actual_url }}</td>
                        <td>{{ $link->contacts()->sum('click_count') }}</td>
                        <td>{{ $link->contacts()->sum('click_count') == 0 || $total_clicks == 0 ? '0' :  number_format((float)$link->contacts()->sum('click_count') / $total_clicks * 100, 2, '.', '')}}%</td>
                        <td><a class="ui mini button" href="{{ url('downloadsmslinklist').'/'.$link->id }}"><i class="download icon"></i> Download Link List (CSV)</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="ui message"><p>You don't have any Link(s)</p></div>
            @endif
        </div>
    </div>
@endsection

@section('footerscripts')
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('a,i').popup();

        $('.menu .item').tab();

        $('.ui.tiny.progress').progress();
    </script>
    <script>
        var openmap;
        var clickmap;
        function initMap() {

            clickmap = new google.maps.Map(document.getElementById('clickers_map'), {
                center: {lat: 33.8352384, lng: 59.31900329999999},
                zoom: 2
            });

            var click_country_data = '<?php echo json_encode($country_click_list); ?>';
            var obj_click_country = JSON.parse(click_country_data);
            $.each(obj_click_country, function(index, value){
                if(index && value != 0 )
                {
                    $.ajax({
                        url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + index + "&key=AIzaSyCL4LWlepGYhAZLxFgLf3FN9nSon7XIdr8",
                        method: 'get',
                        success: function(data){
                            if(data.status == "OK")
                            {
                                var marker = new google.maps.Marker({
                                    position: data.results[0].geometry.location,
                                    map: clickmap,
                                    icon: "https://mt.google.com/vt/icon/name=icons/onion/SHARED-mymaps-container-bg_4x.png,icons/onion/SHARED-mymaps-container_4x.png,icons/onion/1738-blank-sequence_4x.png&highlight=ff000000,0288D1,ff000000&scale=1.0&color=ffffffff&psize=15&text="+ value +"&font=fonts/Roboto-Medium.ttf"
                                });

                            }
                        },
                        error: function(data){
                            console.log("An error occurred");
                        }
                    });
                }
            });


        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCL4LWlepGYhAZLxFgLf3FN9nSon7XIdr8&callback=initMap"
    async defer></script>
@endsection
