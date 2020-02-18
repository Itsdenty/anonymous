@extends('layouts.user')
@section('title', 'Sendmunk | Sequences')

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
        <a id="create_sequence_icon" href="{{ url('createsequence') }}" class="right floated ui button"><i class="plus icon"></i>New Sequence</a>
        <h2>Sequences</h2>
    </div>

    <br/>

    <div class="ui segment">
        @if(!$sequences->isEmpty())
        <table class="ui table">
            <thead>
                <tr>
                    <th>TITLE</th>
                    <th width="12%" style="text-align: center">No. of Emails</th>
                    <th width="12%" style="text-align: center">No. of contacts</th>
                    <th width="12%" style="text-align: center">No. of Started</th>
                    <th width="12%" style="text-align: center">STATUS</th>
                    <th width="12%" style="text-align: center">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sequences as $sequence)
                <tr>
                    <td><a href="{{ url('sequence/'. $sequence->id . '/contents') }}"><h3>{{ $sequence->title }}</h3></a>
                        <p>
                            <?php
                                echo 'Created: '.date('M j, Y H:i:s', (strtotime($sequence->created_at) + ($user->profile->timezone->offset * 60 * 60)));
                            ?>
                        </p>
                    </td>
                    <td style="text-align: center">{{ $sequence->sequenceEmails->count() }}</td>
                    <td style="text-align: center">{{ $sequence->contacts()->count() }}</td>
                    <td style="text-align: center">{{ $sequence->contacts()->wherePivot('started', true)->count() }}</td>
                    <td style="text-align: center">  
                        <div class="ui toggle checkbox">
                            <input data-sequence="{{ $sequence->id }}" class="sequence_status" type="checkbox" {{ $sequence->status == 'active' ? 'checked' : '' }} />
                            <label></label>
                        </div>
                    </td>
                    <td style="text-align: center">
                        <a href="{{ url('edit/'.$sequence->id.'/sequence') }}"><i data-content="Settings" class="setting icon"></i></a>
                        <a href="{{ url('delete/'.$sequence->id.'/sequence') }}"><i data-content="Delete Sequence" class="delete icon"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="ui message"><p>You don't have any Sequence(s) yet</p></div>
        @endif
    </div>
@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>
    <script>
        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('i').popup();

        $('.sequence_status').on('change', function(){
            let status = $(this).is(':checked');
            let checkbox = $(this);
            let sequence_id = $(this).data('sequence');
            if(status)
            {
                $.ajax({
                    url: "{{ url('activatesequence') }}" + '/' + sequence_id,
                    method: 'get',
                    success: function(){
                        $.notify('Sequence Activated', 'success');
                    },
                    error: function(){
                        checkbox.prop('checked', false);
                        $.notify('An error occurred while activating sequence', 'error');
                    }
                });
            }
            else
            {
                $.ajax({
                    url: "{{ url('deactivatesequence') }}" + '/' + sequence_id,
                    method: 'get',
                    success: function(){
                        $.notify('Sequence Dectivated', 'success');
                    },
                    error: function(){
                        checkbox.prop('checked', true);
                        $.notify('An error occurred while deactivating sequence', 'error');
                    }
                });
            }
        });
    </script>
@endsection
