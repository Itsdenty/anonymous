<div class="filterable">
    <div>
        <h3 style="width:30%; float:right; text-align:right;"><span class="campaign_contacts_count">{{ $contacts->count() }}</span> subscribers 
            {{-- <a id="view_campaign_contacts" href="#">
            <i class="eye icon"></i></a> --}}
        </h3>
        <div style="display:flex; justify-content: space-between; margin-bottom: 10px;">
            <div><h3>The Campaign will have: </h3></div>  
        </div>
    </div>

    <div>
        <a id="filter_form_button" href="#" class="ui button" style="margin-bottom: 10px;">
        <i class=""></i>Filter Subscribers</a>
    </div>
    <div id="create_form_modal_5" class="ui tiny modal">
        <div class="header">Filter Campaign Subscribers</div>
        <div class="content">
            <div class="ui form">
                <div style="display: flex; justify-content: center; margin-bottom: 10px; background: #f2f2f2;">
                <div  style="margin-right: 10px; margin-top: 10px;">Contact Match</div>
                <select id="filter_match" name="filter_match" class="" style="width: 15%;">
                    <option value="and">And</option>
                    <option value="or">Or</option>
                </select>
                <div style="margin-left: 10px; margin-top: 10px;">of the following Filters: </div>
                </div>
                <div id="all_filter_rules">
                    <div class="filter_rule fields"  style="display: flex; flex-wrap: wrap; border: 2px solid #f2f2f2; padding: 1px;">
                        <div class="field" style="width: 26%;">
                            <select name="column" class="ui fluid dropdown filter_option">
                                <option value="">Select filter</option>
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                                <option value="sub_date">Subscription Date</option>
                                <option value="tag">Tag</option>
                                <option value="segment">Segment</option>
                                <option value="country_name">Country</option>
                                <option value="form_id">Form Subscribed to</option>
                                <option value="email_openers">Openers</option>
                                <option value="non_email_openers">Non-openers</option>
                                <option value="link_clickers">Clickers</option>
                                <option value="non_link_clickers">Non-clickers</option>
                                @if(!$user->currentAccount->contactAttributes->isEmpty())
                                <option value="custom">Custom Attributes</option>
                                @endif
                            </select>
                        </div>
                        <div class="field close_button_div">
                            <button class="close_filter_rule ui button">x</button>
                        </div>
                    </div>
                </div>
                <div class="" style="margin-top: 10px; margin-bottom: 10px">
                <button type="button" id="add_filter_rule" class="ui  button filter_ctrl">+</button>
                <button type="button" id="filter_reset_btn" class="ui  button filter_ctrl">Reset</button>
                <button type="button" id="filter_campaign_btn" class="ui primary button filter_ctrl">Filter</button>
                </div>
            </div>
            <div class="field" style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <div><h3>This Campaign will have: </h3></div>
                <div>
                <h3> <span id="num_filter_subscriber">{{ $contacts->count() }}</span> subscribers</h3>
                </div>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="ui large cancel button filter_ctrl">Cancel</button>
            <button id="apply_filter" type="button" class="ui large primary button btn-send filter_ctrl">Apply Filter</button>
        </div>
    </div>
    <div id="create_form_modal_6" class="ui tiny modal">
        <i class="close icon"></i>
        <div class="header">
            Subscribers
        </div>
        <div class="scrolling content">
            @if(!$contacts->isEmpty())
            <table class="ui table">
                <thead>
                    <tr>
                        <th width="40%">Name</th>
                        <th width="40%">Email</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td style="text-align: center"><a class="remove_contact" href="{{ url('remove_contact/'.$contact->id.'/smscampaign'.'/'.$sms_campaign->id) }}"><i class="delete icon"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="ui message">You have not contacts for this campaign</div>
            @endif
        </div>
    </div>

</div>

{{-- @include('includes.user.footerscripts')
<script>   
    
</script> --}}