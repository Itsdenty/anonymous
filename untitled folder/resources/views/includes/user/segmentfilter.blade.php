<div class="filterable">
    <div id="create_form_modal_5" class="ui tiny modal">
        <div class="header">Create New Segment</div>
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
                <div><h3>This Segment will have: </h3></div>
                <div>
                <h3> <span id="num_filter_subscriber">0</span> subscribers</h3>
                </div>
            </div>
            <div class="ui form field">
                <label>Title of Segment</label>
                <input id="segment_input_name" type="text" name="name" placeholder="Title of Segment"/>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="ui large cancel button filter_ctrl">Cancel</button>
            <button id="apply_filter" type="button" class="ui large primary button btn-send">Create Segment</button>
        </div>
    </div>
</div>
