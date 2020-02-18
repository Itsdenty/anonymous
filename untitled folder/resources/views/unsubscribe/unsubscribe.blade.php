<html lang="en">
      <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">
            {{-- <link rel="icon" href='{{ asset("logo/Sendmunk_icon.png") }}'/> --}}
            {{-- <link rel="stylesheet" type="text/css" href='{{ url("Semantic-UI-CSS-master/semantic.min.css") }}' /> --}}
            {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" />> --}}

            <link rel="stylesheet" type="text/css" href='{{ asset("Semantic-UI-CSS-master/semantic.min.css") }}' />
            {{-- <link rel="stylesheet" type="text/css" href='{{ url("css/user.css") }}'/> --}}


            <title>{{ $contact->unsubscribed ? 'Subscribe to' : 'Unsubscribe from'}} {{ $campaign->title }} - Sendmunk</title>

      </head>
      <body>
            <?php
                $reasons = $contact->subaccount->unsubscribeReasons;
            ?>

            <div class="container-fluid">
                <br>
                <br>
                <div class="ui vertically stackable grid">
                    <div class="three column row">
                        <div class="column"></div>
                        <div class="column">
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
                            {!! $contact->subaccount->unsubscribe_page_content !!}
                            <br>
                            <div class="ui segment" style="padding: 30px;">
                                <form class="ui form" method="post" action="{{ url('unsubscribe_contact') }}" role="form">
                                    {{ csrf_field() }}
                                    <h1>{{ $contact->unsubscribed ? 'Subscribe' : 'Unsubscribe'}} {{ $contact->email }}</h1>
                                    
                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}" />
                                    <input type="hidden" name="campaign_id" value="{{ $campaign->id }}" />
                                    @if(!$contact->unsubscribed)
                                        @if(!$reasons->isEmpty())
                                        <div class="field">
                                            <label>Reason</label>
                                            <select name="reason_id" class="ui dropdown">
                                                @foreach($reasons as $reason)
                                                <option value="{{ $reason->id }}">{{ $reason->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                    @endif
                                    <button type="submit" class="fluid ui large button">Confirm {{ $contact->unsubscribed ? 'Subscription' : 'Unsubscription'}}</button>
                                </form>
                            </div>
                        </div>
                        <div class="column"></div>
                    </div>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            {{-- <script src="{{ url("js/jquery-3.2.1.min.js") }}"></script> --}}
            <script src='{{ url("Semantic-UI-CSS-master/semantic.min.js") }}'></script>
            {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script> --}}
            <script>
                $('.message .close').on('click', function() {
                    $(this).closest('.message').transition('fade');
                });
                $('.ui.dropdown').dropdown();
            </script>
      </body>
  </html>