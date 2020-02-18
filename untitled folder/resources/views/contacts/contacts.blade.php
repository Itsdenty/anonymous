@extends('layouts.user')
@section('title', 'Sendmunk | Contacts')

@section('styles')
    <style>
        .summary{
            background: white;
            border: 2px solid #E8E9EE;
            text-align: center;
            padding: 10px 0px;
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
        <a id="import_contact_icon" href="#" class="right floated ui button"><i class="plus icon"></i>Import Contacts</a>
        <a id="create_contact_icon" href="#" class="right floated ui button"><i class="plus icon"></i>Create Contact</a>
        <a id="purge_list_icon" href="#" class="right floated ui button"><i class="delete icon"></i>Purge List</a>
        <h2>Contacts</h2>
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="four column row">
            <div class="summary column">
                <h3><i class="users icon"></i></h3>
                <h2>{{ $num_of_contacts }}</h2>
                <h3>Total Subscribers</h3>
            </div>
            <div class="summary column">
                <h3><i class="eye icon"></i></h3>
                <h2>{{ $num_of_contacts == 0 || $num_of_opens == 0 ? 0 : number_format((float)$num_of_opens / $num_of_contacts * 100, 2, '.', '') }}%</h2>
                <h3>Average Open Rate</h3>
            </div>
            <div class="summary column">
                <h3><i class="hand pointer outline icon"></i></h3>
                <h2>{{ $num_of_contacts == 0 || $num_of_clicks == 0 ? 0 : number_format((float)$num_of_clicks / $num_of_contacts * 100, 2, '.', '') }}%</h2>
                <h3>Average Click Rate</h3>
            </div>
            <div class="summary column">
                <h3><i class="mail icon"></i></h3>
                <h2>{{ $num_of_sent }}</h2>
                <h3>Emails Sent</h3>
            </div>
        </div>
    </div>
    <br/>
    <div class="ui vertically stackable grid">
        <div class="two column row">
            <div class="separate column">
                <a id="create_tag_icon" href="#" style="float:right;clear:both;padding-top:25px;"><i class="plus icon"></i>Create Tag</a>
                <h3>Tags</h3>
                <hr/>
                @if(!$tags->isEmpty())
                <div class="ui middle aligned divided list">
                    @foreach($tags as $tag)
                    <div class="item">
                        <div class="right floated content">
                            <a href="{{ url('tag/edit').'/'.$tag->id }}">{{ $tag->contacts->count() }} Subscribers </a>
                        </div>
                        <div class="content">
                            {{ $tag->name }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="ui message"><p>You don't have any Tag(s) yet</p></div>
                @endif
            </div>
            <div class="separate separate2 column">
                <a id="create_segment_icon" href="#" style="float:right;clear:both;padding-top:25px;"><i class="plus icon"></i>Create Segment</a>
                <h3>Segments</h3>
                <hr/>
                @if(!$segments->isEmpty())
                <div class="ui middle aligned divided list">
                    @foreach($segments as $segment)
                    <div class="item">
                        <div class="right floated content">
                            <a href="{{ url('segment/edit').'/'.$segment->id }}">{{ $segment->contacts->count() }} Subscribers </a>
                        </div>
                        <div class="content">
                            {{ $segment->name }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="ui message"><p>You don't have any Segment(s) yet</p></div>
                @endif
            </div>
        </div>
    </div>
    <div class="ui vertically stackable grid">
        <div class="sixteen column">
            <div class="ui segment">
                <button type="button" id="export_selected_icon" onclick='location.href="{{ url("contacts/exports") }}";' class="right floated ui button" {{ $contacts->where('marked', true)->count() > 0 && $contacts->count() > 0 ? "" : "disabled" }}>Export Selected</button>
                <button type="button" id="delete_selected_icon" class="right floated ui button" {{ $contacts->where('marked', true)->count() > 0 && $contacts->count() > 0 ? "" : "disabled" }}>Delete Selected</button>
                <h3>Contacts</h3>
                <hr/>
                @if(!$contacts->isEmpty())
                <table id="contacts_table" class="ui table">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align: center"><i data-content="All Contacts"><input type="checkbox" id="select_all_contact" {{ $contacts->where('marked', false)->count() == 0 && $contacts->count() > 0 ? "checked" : "" }} /></i></th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th width="15%" style="text-align: center">SUBCRIPTION DATE</th>
                            <th width="15%" style="text-align: center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                            <td><input class="contact_check" data-id="{{ $contact->id }}" type="checkbox" {{ $contact->marked == true ? "checked" : "" }}/></td>
                            <td>
                                @if($contact->unsubscribed)
                                <del>{{ $contact->name }}</del>
                                @else
                                {{ $contact->name }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('contact/edit').'/'.$contact->id }}">
                                @if($contact->unsubscribed)
                                <del>{{ $contact->email }}</del>
                                @else
                                {{ $contact->email }}
                                @endif
                                </a>
                            </td>
                            <td style="text-align: center">{{ $contact->sub_date }}</td>
                            <td style="text-align: center">
                                <a href="{{ url('contact/edit').'/'.$contact->id }}"><i data-content="Edit Contact" class="edit icon"></i></a>
                                <a href="{{ url('contact/delete').'/'.$contact->id }}"><i data-content="Delete Contact" class="delete icon"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $contacts->links('includes.pagination') }}

                @else
                <div class="ui message"><p>You don't have any Contact(s) yet</p></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Create New Tag Modal --}}
    <div id="create_tag_modal" class="ui tiny modal">
        <div class="header">Create Tag</div>
        <div class="content">
            <form action="{{ url('addtag') }}" id="create_new_tag" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>Tag Title</label>
                    <input id="tag_title" type="text" name="name" placeholder="Title of Tag" required/>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_tag_btn" type="submit" value="Create" form="create_new_tag" class="ui primary button" />
        </div>
    </div>

    {{-- Create New Contact Modal --}}
    <div id="create_contact_modal" class="ui tiny modal">
        <div class="header">Create Contact</div>
        <div class="content">
            <form action="{{ url('contacts') }}" id="create_new_contact" class="ui form" method="post" role="form">
                {{ csrf_field() }}
                <div class="field">
                    <label>First Name</label>
                    <input id="contact_first_name" type="text" name="firstname" placeholder="Contact's First Name" />
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <input id="contact_last_name" type="text" name="lastname" placeholder="Contact's Last Name" />
                </div>
                <div class="field">
                    <label>Email</label>
                    <input id="contact_email" type="email" name="email" placeholder="Contact's Email" required/>
                </div>
                <div class="field">
                    <label>Phone No.</label>
                    <input id="contact_email" type="text" name="phone" placeholder="Contact's Phone No." />
                </div>
                <div class="field">
                    <label>Tag(s)</label>
                    <select id="add_tag_contact" name="tags[]" multiple="" class="ui fluid dropdown">
                        <option value="">Add Tag(s)</option>
                        @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                @foreach($contact_attributes as $attribute)
                <div class="field">
                    <label>{{ ucwords(str_replace('_', ' ', $attribute->name)) }}</label>
                    <input type="{{ $attribute->type }}" name="custom_{{ $attribute->id }}" placeholder="{{ ucwords(str_replace('_', ' ', $attribute->name)) }}" />
                </div>
                @endforeach
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="create_contact_btn" type="submit" value="Create" form="create_new_contact" class="ui primary button" />
        </div>
    </div>

    {{-- Purge List Modal --}}
    <div id="purge_list_modal" class="ui tiny modal">
        <div class="header">Purge List</div>
        <div class="content">
            <div class="ui form">
                <div>
                    <div class="filter_rule fields"  style="display: flex;">
                        <div class="field" style="width: 60%;">
                            Remove Subscribers that haven't opened the last
                        </div>
                        <div class="ui right labeled input">
                            <input id="purge_num_of_campaign" style="width:20%" type="number" min="1" placeholder="No." name="num_of_campaign">
                            <div class="ui basic label">Campaigns</div>
                        </div>
                    </div>
                </div>
                <div class="" style="margin-top: 10px; margin-bottom: 10px">
                    <button type="button" id="purge_filter_contact_btn" class="ui primary button filter_ctrl">Filter</button>
                </div>
                <div class="field" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div><h3>Number of Contacts to Purge: </h3></div>
                    <div>
                    <h3> <span id="purge_list_count">0</span> contacts</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="ui large cancel button filter_ctrl">Cancel</button>
            <button id="purge_btn" type="button" class="ui large primary button btn-send filter_ctrl">Purge</button>
        </div>
    </div>

    {{-- Delete Selected Modal --}}
    <div id="delete_selected_modal" class="ui basic mini modal">
        <div class="ui icon header">
            <i class="trash icon"></i>
            Delete Selected Contact(s)
        </div>
        <div class="content">
            <p>Are you sure you want to delete select contact(s)?</p>
        </div>
        <div class="actions">
            <div class="ui basic cancel inverted button">
            <i class="remove icon"></i> No
            </div>
            <a href="{{ url('contactsDeleteMarked') }}" class="ui blue ok inverted button">
                <i class="checkmark icon"></i> Yes
            </a>
        </div>
    </div>

    {{-- Import Contact Modal --}}
    <div id="import_contact_modal" class="ui tiny modal">
        <div class="header">Import Contacts</div>
        <div class="content">
            <form action="{{ url('contact_process_import') }}" id="import_new_contact" class="ui form" method="post" role="form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div style="text-align: justify">
                    Please ensure that the header column is present. Allow header columns are : 
                    <strong><em>
                        email , firstname , 'lastname', 'phone' 
                        @foreach($contact_attributes as $attribute)
                        , {{ $attribute->name }}
                        @endforeach
                    </em></strong>.
                    <br/>
                    NB: Column names are case sensitive and each record must have an <strong><em>email</em></strong>.
                </div>
                <br/>
                <div class="field">
                    <label>CSV File</label>
                    <input id="csv_file_input" type="file" name="csv_file" required/>
                </div>
                <div class="field">
                    <label>Tag(s)</label>
                    <select id="add_tag_contact" name="tags[]" multiple="" class="ui fluid dropdown">
                        <option value="">Add Tag(s) to Imported Contact(s)</option>
                        @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui cancel button">Cancel</div>
            <input id="import_contact_btn" type="submit" value="Parse CSV" form="import_new_contact" class="ui primary button" />
        </div>
    </div>

    @include('includes.user.segmentfilter')
@endsection

@section('footerscripts')
    <script type="text/javascript" src="{{ url('js/notify.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>

    <script>
        let purgelist = [];

        $('.message .close').on('click', function() {
            $(this).closest('.message').transition('fade');
        });

        $('i').popup();

        $('.ui.checkbox').checkbox();

        $('#create_tag_icon').on('click', function(){
            $('#tag_title').val('');
            $('#create_tag_modal').modal('show');
        });

        $('#create_contact_icon').on('click', function(){
            $('#create_new_contact').trigger('reset');
            $('#add_tag_contact').dropdown('clear');
            $('#create_contact_modal').modal('show');
        });

        $('#purge_list_icon').on('click', function(){
            purgelist = [];
            $('#purge_num_of_campaign').val('1');
            $('#purge_list_count').text('0');
            $('#purge_list_modal').modal('show');
        });

        $('#purge_filter_contact_btn').on('click', function(e){
            e.preventDefault();
            let filter_btn = $(this);
            let num_of_campaigns = $('#purge_num_of_campaign').val();
            if(num_of_campaigns && num_of_campaigns != 0)
            {
                filter_btn.addClass('loading');
                filter_btn.prop('disabled', true);

                $.ajax({
                    url: "{{ url('purgelist') }}" + '/' + num_of_campaigns,
                    method: 'get',
                    success: function(response){
                        let filter_result = response.result;
                        purgelist = [];
                        $.each(filter_result, function(index, value){
                            purgelist.push(value);
                        });
                        filter_btn.removeClass('loading');
                        filter_btn.prop('disabled', false);
                        $('#purge_list_count').text(purgelist.length);
                    },
                    error: function(response){
                        filter_btn.removeClass('loading');
                        filter_btn.prop('disabled', false);
                        console.log('an error occurred');
                    }
                });
            }
            else
            {
                $('#purge_list_count').text('0');
                purgelist = [];
            }
        });

        $('#purge_btn').on('click', function(e){
            e.preventDefault();
            let purge_btn = $(this);
            if(purgelist.length != 0)
            {
                purge_btn.addClass('loading');
                purge_btn.prop('disabled', true);

                $.ajax({
                    url: "{{ url('contactsDeleteMarked') }}",
                    type: 'delete',
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}'
                    },
                    data: {'thecontacts':purgelist},
                    success: function(data){
                        purge_btn.removeClass('loading');
                        purge_btn.prop('disabled', false);
                        $('#purge_list_modal').modal('hide');
                        $.notify('Purge Successfull', 'success');
                        setTimeout(function(){
                            window.location.href = "{{ url('contacts_main') }}";
                        }, 3000);
                    },
                    error: function(data){
                        purge_btn.removeClass('loading');
                        purge_btn.prop('disabled', false);
                        console.log('error');
                    }
                });
            }
        });

        $('.contact_check').change(function(){
            let contact_id = $(this).data('id');
            let marked = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ url('contacts') }}" + "/" + contact_id + "/check",
                method: "patch",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                data: {'marked' : marked},
                success: function(response){
                    $('#select_all_contact').prop('checked', response.all_checked);
                    $('#delete_selected_icon').prop('disabled', !response.one_marked);
                    $('#export_selected_icon').prop('disabled', !response.one_marked);
                },
                error: function(response){
                    console.log(response);
                }
            });
        });

        $('#delete_selected_icon').on('click', function(e){
            e.preventDefault();
            $("#delete_selected_modal").modal('show');
        });

        $('#select_all_contact').change(function(){
            $('.contact_check').prop('checked', $(this).is(':checked'));

            let marked = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ url('contactCheckAll') }}",
                method: "patch",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                data: {'marked' : marked},
                success: function(response){
                    $('#select_all_contact').prop('checked', response.all_checked);
                    $('#delete_selected_icon').prop('disabled', !response.one_marked);
                    $('#export_selected_icon').prop('disabled', !response.one_marked);
                },
                error: function(response){
                    console.log(response);
                }
            });
        });

        $('#import_contact_icon').on('click', function(e){
            e.preventDefault();
            $('#import_new_contact').trigger("reset");
            $('#header_row_input').prop('checked', true);
            $("#import_contact_modal").modal('show');
        });

        $('#contacts_table').DataTable();
    </script>

    @include('includes.user.segmentfilterscript')
@endsection
