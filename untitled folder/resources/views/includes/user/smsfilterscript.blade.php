<script>
// Filter component
    <?php
        $tags = $user->currentAccount->tags->toArray();
        $countries = $user->currentAccount->contacts->where('country_name', '!=', null)->uniqueStrict('country_name')->pluck('country_name')->toArray();
        $forms = $user->currentAccount->forms->where("is_template", false);
        $form_subset = $forms->map->only(['id', 'title', 'form_type'])->toArray();
        $segments = $user->currentAccount->segments->toArray();
        $user_camp = $user->currentAccount->campaigns->where('status', 'sent');
        $user_campaigns = $user_camp->map->only(['id', 'title'])->toArray();
        $all_links = $user->currentAccount->campaigns->map(function($campaign){
            return $campaign->links->map(function($link){
                return $link;
            });
        })->flatten()->toArray();
        $attributes = $user->currentAccount->contactAttributes->toArray();
    ?>

    let contacts_data = [];
    let filter_query_string = "";

    $('#filter_reset_btn').hide();

    $('#filter_form_button').on('click', function(e){
        $('#create_form_modal_5').modal('show');
    });

    $(document).delegate('.close_filter_rule', 'click', function(e){
        e.preventDefault();    
        $(this).closest('.filter_rule').remove();
    });

    $(document).delegate('.filter_option', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        filter_rule.find('.new_filter_fields').remove();
        // let option = $(this);
        if(filter_value == 'name' || filter_value == 'email')
        {
            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown"><option value="equal_to">equal to</option><option value="not_equal_to">not equal to</option><option value="contains" selected>contains</option><option value="starts_with">starts with</option><option value="ends_with">ends with</option></select></div><div style="width: 35%;" class="field new_filter_fields"><input name="query_1" type="text"/></div>');
        }
        else if(filter_value == 'sub_date')
        {
            filter_rule.find('.close_button_div').before('<div style="width: 27%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown filter_time_option"><option value="in_the_past" selected>in the past</option><option value="in_the_next">in the next</option><option value="in_the_period">in the period</option></select></div><div style="width: 15%;" class="field new_filter_fields new_time_fields"><input name="query_1" type="number" value="28" min="1" /></div><div class="field new_filter_fields new_time_fields"><select name="query_2" class="ui fluid dropdown"><option value="hours">hours</option><option value="days">days</option><option value="months">months</option><option value="years">years</option></select></div>');
        }
        else if(filter_value == 'tag')
        {
            let tags = '<?php echo json_encode($tags); ?>';
            let tags_array = JSON.parse(tags);

            let tag_options = "";
            $.each(tags_array, function(index, value){
                tag_options += '<option value="'+ value['id'] +'">'+ value['name'] +'</option>'
            });

            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown"><option value="has_tag">equal to</option><option value="has_not_tag">not equal to</option></select></div><div style="width: 35%;" class="field new_filter_fields"><select name="query_1" class="ui fluid dropdown">' + tag_options + '</select></div>');
        }
        else if(filter_value == 'country_name')
        {
            let countries = '<?php echo json_encode($countries); ?>';
            let countries_array = JSON.parse(countries);
            let country_options = "";
            $.each(countries_array, function(index, value){
                country_options += '<option value="'+ value +'">'+ value +'</option>'
            });

            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown"><option value="equal_to">equal to</option><option value="not_equal_to">not equal to</option></select></div><div style="width: 35%;" class="field new_filter_fields"><select name="query_1" class="ui fluid dropdown">' + country_options + '</select></div>');
        }
        else if(filter_value == 'form_id')
        {
            let forms = '<?php echo json_encode($form_subset); ?>';
            let forms_array = JSON.parse(forms);
            let form_options = "";
            $.each(forms_array, function(index, value){
                let form_type = "";
                if(value['form_type'] == "popover" || value['form_type'] == "embedded" || value['form_type'] == "topbar" || value['form_type'] == "scrollbox" || value['form_type'] == "welcome_mat")
                {
                    form_type = "Basic";
                }
                else if(value['form_type'] == "poll")
                {
                    form_type = "Poll";
                }
                else if(value['form_type'] == "quiz")
                {
                    form_type = "Quiz";
                }
                else if(value['form_type'] == "calculator")
                {
                    form_type = "Calculator";
                }

                forms_array += '<option value="'+ value['id'] +'">'+ value['title'] +' ('+ form_type +')</option>'
            });

            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown"><option value="equal_to">equal to</option><option value="not_equal_to">not equal to</option></select></div><div style="width: 35%;" class="field new_filter_fields"><select name="query_1" class="ui fluid dropdown">' + forms_array + '</select></div>');
        }
        else if(filter_value == 'segment')
        {
            let segments = '<?php echo json_encode($segments); ?>';
            let segments_array = JSON.parse(segments);

            let segment_options = "";
            $.each(segments_array, function(index, value){
                segment_options += '<option value="'+ value['id'] +'">'+ value['name'] +'</option>'
            });

            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown"><option value="in_segment">equal to</option><option value="not_in_segment">not equal to</option></select></div><div style="width: 35%;" class="field new_filter_fields"><select name="query_1" class="ui fluid dropdown">' + segment_options + '</select></div>');
        }
        else if(filter_value == 'email_openers' || filter_value == 'non_email_openers')
        {

            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown openers_campaign_option"><option value="all_campaigns">All Campaigns</option><option value="specific_campaign">Specific Campaign</option></select></div><div style="width: 35%" class="field new_filter_fields campaign_time_field"><select name="query_1" class="ui fluid dropdown campaign_time_option"><option value="whenever">Whenever</option><option value="between">Between</option></select></div>');
        }
        else if(filter_value == 'link_clickers' || filter_value == 'non_link_clickers')
        {
            filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields"><select name="operator" class="ui fluid dropdown clickers_campaign_option"><option value="all_campaigns">All Campaigns</option><option value="specific_campaign">Specific Campaign</option></select></div><div style="width: 35%;" class="field new_filter_fields campaign_link_field"><select class="ui fluid dropdown clicker_link_dropdown"><option value="all_links">All Links</option><option value="specific_link">Specific Link</option></select></div><div style="width: 35%" class="field new_filter_fields campaign_time_field"><select name="query_1" class="ui fluid dropdown campaign_time_option"><option value="whenever">Whenever</option><option value="between">Between</option></select></div>')
        }
        else if(filter_value == 'custom')
        {
            let attributes = '<?php echo json_encode($attributes); ?>';
            let attributes_array = JSON.parse(attributes);

            console.log(attributes_array);

            let attributes_options = "";
            $.each(attributes_array, function(index, value){
                attributes_options += '<option value="'+ value['id'] +'">'+ value['name'] +'</option>'
            });

            filter_rule.find('.close_button_div').before('<div style="width: 35%;" class="field new_filter_fields custom_option"><select name="query_1" class="ui fluid dropdown">' + attributes_options + '</select></div><div style="width: 25%" class="field new_filter_fields custom_fields"><select name="operator" class="ui fluid dropdown"><option value="in_custom">equal to</option><option value="not_in_custom">not equal to</option></select></div><div style="width: 35%;" class="field new_filter_fields custom_fields"><input name="query_2" type="'+ attributes_array[0]['type'] +'" placeholder="'+ attributes_array[0]['type'] +'" /></div>');
        }

        $('.ui.dropdown').dropdown();
    });

    $(document).delegate('.custom_option', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        let attributes = '<?php echo json_encode($attributes); ?>';
        let attributes_array = JSON.parse(attributes);

        current_attribute_prop = attributes_array.find((element)=>{
            return element.id == parseInt(filter_value);
        });
        
        filter_rule.find('.custom_fields').remove();

        filter_rule.find('.close_button_div').before('<div style="width: 25%" class="field new_filter_fields custom_fields"><select name="operator" class="ui fluid dropdown"><option value="in_custom">equal to</option><option value="not_in_custom">not equal to</option></select></div><div style="width: 35%;" class="field new_filter_fields custom_fields"><input name="query_2" type="'+ current_attribute_prop.type +'" placeholder="'+ current_attribute_prop.type +'" /></div>');

        $('.ui.dropdown').dropdown();
    });

    $(document).delegate('.clickers_campaign_option', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        if(filter_value == "specific_campaign")
        {
            let user_campaigns = '<?php echo json_encode($user_campaigns); ?>';
            let user_campaigns_array = JSON.parse(user_campaigns);

            let user_campaigns_options = "";
            $.each(user_campaigns_array, function(index, value){
                user_campaigns_options += '<option value="'+ value['id'] +'">'+ value['title'] +'</option>'
            });

            filter_rule.find('.campaign_link_field').remove();
            filter_rule.find('.campaign_time_field').remove();
            filter_rule.find('.new_campaign_time_fields').remove();
            filter_rule.find('.link_list_field').remove();

            filter_rule.find('.close_button_div').before('<div class="field new_filter_fields campaign_list"><select name="campaign_id" class="ui dropdown user_campaigns_list">'+ user_campaigns_options +'</select></div><div style="width: 35%;" class="field new_filter_fields campaign_link_field"><select class="ui fluid dropdown clicker_link_dropdown"><option value="all_links">All Links</option><option value="specific_link">Specific Link</option></select></div><div style="width: 35%" class="field new_filter_fields campaign_time_field"><select name="query_1" class="ui fluid dropdown campaign_time_option"><option value="whenever">Whenever</option><option value="between">Between</option></select></div>');
        }
        else
        {
            filter_rule.find('.campaign_link_field').remove();
            filter_rule.find('.campaign_list').remove();
            filter_rule.find('.campaign_time_field').remove();
            filter_rule.find('.new_campaign_time_fields').remove();
            filter_rule.find('.link_list_field').remove();

            filter_rule.find('.close_button_div').before('<div style="width: 35%;" class="field new_filter_fields campaign_link_field"><select class="ui fluid dropdown clicker_link_dropdown"><option value="all_links">All Links</option><option value="specific_link">Specific Link</option></select></div><div style="width: 35%" class="field new_filter_fields campaign_time_field"><select name="query_1" class="ui fluid dropdown campaign_time_option"><option value="whenever">Whenever</option><option value="between">Between</option></select></div>');
        }
        $('.ui.dropdown').dropdown();
    });

    $(document).delegate('.clicker_link_dropdown', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        let closest_div = $(this).closest('.campaign_link_field');

        if(filter_value == "specific_link")
        {
            let campaign_option = filter_rule.find('.clickers_campaign_option').find(":selected").val();
            if(campaign_option == "all_campaigns")
            {
                let all_links = '<?php echo json_encode($all_links); ?>';
                let all_links_array = JSON.parse(all_links);

                let all_links_options = "";
                $.each(all_links_array, function(index, value){
                    all_links_options += '<option value="'+ value['id'] +'">' + value['campaign_id'] + '. ' + value['actual_url'] +'</option>';
                });

                filter_rule.find('.link_list_field').remove();
                closest_div.after('<div class="field new_filter_fields link_list_field"><select class="ui fluid dropdown" name="link_id">'+ all_links_options +'</select></div>')
            }
            else
            {
                let all_links = '<?php echo json_encode($all_links); ?>';
                let all_links_array = JSON.parse(all_links);
                
                let campaign_id = filter_rule.find('.user_campaigns_list').find(":selected").val();
                if(campaign_id)
                {
                    let specific_links_array = all_links_array.filter(function(item){
                        return item.campaign_id == campaign_id;
                    });

                    let all_links_options = "";
                    $.each(specific_links_array, function(index, value){
                        all_links_options += '<option value="'+ value['id'] +'">' + value['campaign_id'] + '. ' + value['actual_url'] +'</option>';
                    });

                    filter_rule.find('.link_list_field').remove();
                    closest_div.after('<div class="field new_filter_fields link_list_field"><select class="ui fluid dropdown" name="link_id">'+ all_links_options +'</select></div>')
                }
            }
        }
        else
        {
            filter_rule.find('.link_list_field').remove();
        }
        $('.ui.dropdown').dropdown();
    });

    $(document).delegate('.user_campaigns_list', 'change', function(){
        let filter_rule = $(this).closest('.filter_rule');
        filter_rule.find('.clicker_link_dropdown').change();
    });

    $(document).delegate('.openers_campaign_option', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        if(filter_value == "specific_campaign")
        {
            let user_campaigns = '<?php echo json_encode($user_campaigns); ?>';
            // console.log(user_campaigns);
            let user_campaigns_array = JSON.parse(user_campaigns);
            // let user_campaigns_array = user_campaigns;

            let user_campaigns_options = "";
            $.each(user_campaigns_array, function(index, value){
                user_campaigns_options += '<option value="'+ value['id'] +'">'+ value['title'] +'</option>'
            });

            filter_rule.find('.campaign_time_field').remove();
            filter_rule.find('.new_campaign_time_fields').remove();


            filter_rule.find('.close_button_div').before('<div class="field new_filter_fields campaign_list"><select name="campaign_id" class="ui dropdown user_campaigns_list">'+ user_campaigns_options +'</select></div><div style="width: 35%" class="field new_filter_fields campaign_time_field"><select name="query_1" class="ui fluid dropdown campaign_time_option"><option value="whenever">Whenever</option><option value="between">Between</option></select></div>');
        }
        else
        {
            filter_rule.find('.campaign_list').remove();
            filter_rule.find('.campaign_time_field').remove();
            filter_rule.find('.new_campaign_time_fields').remove();

            filter_rule.find('.close_button_div').before('<div style="width: 35%" class="field new_filter_fields campaign_time_field"><select name="query_1" class="ui fluid dropdown campaign_time_option"><option value="whenever">Whenever</option><option value="between">Between</option></select></div>');
        }
        $('.ui.dropdown').dropdown();
    });

    $(document).delegate('.campaign_time_option', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        filter_rule.find('.new_campaign_time_fields').remove();
        if(filter_value == 'between')
        {
            filter_rule.find('.close_button_div').before('<div style="width: 40%;" class="field new_filter_fields new_campaign_time_fields"><input name="start" type="date" /></div><div style="width: 5%; text-align: center;" class="new_campaign_time_fields">and</div><div style="width: 40%;" class="field new_filter_fields new_campaign_time_fields"><input name="end" type="date" /></div>');
        }
        else
        {
            filter_rule.find('.new_campaign_time_fields').remove();
        }

        $('.ui.dropdown').dropdown();
    })

    $(document).delegate('.filter_time_option', 'change', function(){
        let filter_value = $(this).find(":selected").val();
        let filter_rule = $(this).closest('.filter_rule');
        filter_rule.find('.new_time_fields').remove();
        if(filter_value == 'in_the_past' || filter_value == 'in_the_next')
        {
            filter_rule.find('.close_button_div').before('<div class="field new_filter_fields new_time_fields"><input name="query_1" type="number" value="28" min="1" /></div><div class="field new_filter_fields new_time_fields"><select name="query_2" class="ui fluid dropdown"><option value="hours">hours</option><option value="days">days</option><option value="months">months</option><option value="years">years</option></select></div>');
        }
        else if(filter_value == 'in_the_period')
        {
            filter_rule.find('.close_button_div').before('<div class="field new_filter_fields new_time_fields"><select name="query_1" class="ui fluid dropdown"><option value="yesterday">yesterday</option><option value="today">today</option><option value="tomorrow">tomorrow</option><option value="last_month">last month</option><option value="this_month">this month</option><option value="next_month">next month</option><option value="last_year">last year</option><option value="this_year">this year</option><option value="next_year">next year</option></select></div>');
        }

        $('.ui.dropdown').dropdown();
    });

    $(document).delegate('#add_filter_rule', 'click', function(e){
        e.preventDefault();

        $('#all_filter_rules').append('<div class="filter_rule fields"  style="display: flex; flex-wrap: wrap; border: 2px solid #f2f2f2; padding: 1px;"><div class="field" style="width: 26%;"><select name="column" class="ui fluid dropdown filter_option"><option value="">Select filter</option><option value="name">Name</option><option value="email">Email</option><option value="sub_date">Subscription Date</option><option value="tag">Tag</option><option value="segment">Segment</option><option value="country_name">Country</option><option value="form_id">Form Subscribed to</option><option value="email_openers">Openers</option><option value="non_email_openers">Non-openers</option><option value="link_clickers">Clickers</option><option value="non_link_clickers">Non-clickers</option>@if(!$user->currentAccount->contactAttributes->isEmpty())<option value="custom">Custom Attributes</option>@endif</select></div><div class="field close_button_div"><button class="close_filter_rule ui button">x</button></div></div>');

        $('.ui.dropdown').dropdown();
    });

    $('#filter_campaign_btn').on('click', function(e){
        e.preventDefault();

        let filter_button = $(this);

        let query = [];
        let filter_rule_fields = $('#all_filter_rules').find('.filter_rule');
        $.each(filter_rule_fields, function(index, value){
            let column = $(value).find(':input[name="column"]');
            let column_value = column.find(":selected").val();
            if(column_value)
            {
                if(column_value  == "name" || column_value == "email")
                {
                    let operator = $(value).find(':input[name="operator"]');
                    let operator_value = operator.find(":selected").val();
                    if(operator_value)
                    {
                        let query_1 = $(value).find(':input[name="query_1"]');
                        let query_1_value = query_1.val();
                        if(query_1_value)
                        {
                            query.push("f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + operator_value + "&f[" + index + "][query_1]=" + query_1_value);
                        }
                    }
                }
                else if(column_value == "sub_date")
                {
                    let operator = $(value).find(':input[name="operator"]');
                    let operator_value = operator.find(":selected").val();
                    if(operator_value)
                    {
                        if(operator_value == 'in_the_past' || operator_value == 'in_the_next')
                        {
                            let query_1 = $(value).find(':input[name="query_1"]');
                            let query_1_value = query_1.val();
                            if(query_1_value)
                            {
                                let query_2 = $(value).find(':input[name="query_2"]');
                                let query_2_value = query_2.find(":selected").val();
                                
                                if(query_2_value)
                                {
                                    query.push("f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + operator_value + "&f[" + index + "][query_1]=" + query_1_value + "&f[" + index + "][query_2]=" + query_2_value);
                                }
                            }
                        }
                        else
                        {
                            let query_1 = $(value).find(':input[name="query_1"]');
                            let query_1_value = query_1.find(":selected").val();
                            if(query_1_value)
                            {
                                query.push("f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + operator_value + "&f[" + index + "][query_1]=" + query_1_value);
                            }
                        }
                    }
                }
                else if(column_value == "email_openers" || column_value == "non_email_openers")
                {
                    let operator = $(value).find(':input[name="operator"]');
                    let operator_value = operator.find(":selected").val();
                    if(operator_value)
                    {
                        let query_1 = $(value).find(':input[name="query_1"]');
                        let query_1_value = query_1.find(":selected").val();
                        
                        if(query_1_value)
                        {
                            let query_string = "";

                            query_string += "f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + column_value + "&f[" + index + "][query_1]=" + operator_value;

                            let campaign_id = $(value).find(':input[name="campaign_id"]');
                            let campaign_id_value = campaign_id.find(":selected").val();

                            if(campaign_id_value)
                            {
                                query_string += "&f[" + index + "][campaign_id]=" + campaign_id_value;
                            }

                            let start = $(value).find(':input[name="start"]');
                            let end = $(value).find(':input[name="end"]');
                            if(start.val() && end.val())
                            {
                                query_string += "&f[" + index + "][start]=" + start.val() + "&f[" + index + "][end]=" + end.val();
                            }

                            query.push(query_string);
                        }

                    }
                }
                else if(column_value == "link_clickers" || column_value == "non_link_clickers")
                {
                    let operator = $(value).find(':input[name="operator"]');
                    let operator_value = operator.find(":selected").val();
                    if(operator_value)
                    {
                        let query_1 = $(value).find(':input[name="query_1"]');
                        let query_1_value = query_1.find(":selected").val();
                        
                        if(query_1_value)
                        {
                            let query_string = "";

                            query_string += "f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + column_value + "&f[" + index + "][query_1]=" + operator_value;

                            let campaign_id = $(value).find(':input[name="campaign_id"]');
                            let campaign_id_value = campaign_id.find(":selected").val();

                            if(campaign_id_value)
                            {
                                query_string += "&f[" + index + "][campaign_id]=" + campaign_id_value;
                            }

                            let link_option =$(value).find('.clicker_link_dropdown');
                            let link_option_value = link_option.find(":selected").val();

                            if(link_option_value)
                            {
                                query_string += "&f[" + index + "][link_option]=" + link_option_value;
                            }

                            let link_id = $(value).find(':input[name="link_id"]');
                            let link_id_value = link_id.find(":selected").val();

                            if(link_id_value)
                            {
                                query_string += "&f[" + index + "][link_id]=" + link_id_value;
                            }

                            let start = $(value).find(':input[name="start"]');
                            let end = $(value).find(':input[name="end"]');
                            if(start.val() && end.val())
                            {
                                query_string += "&f[" + index + "][start]=" + start.val() + "&f[" + index + "][end]=" + end.val();
                            }

                            query.push(query_string);
                        }

                    }
                }
                else if(column_value == "custom")
                {
                    let operator = $(value).find(':input[name="operator"]');
                    let operator_value = operator.find(":selected").val();
                    if(operator_value)
                    {
                        let query_1 = $(value).find(':input[name="query_1"]');
                        let query_1_value = query_1.find(":selected").val();
                        if(query_1_value)
                        {
                            let query_2= $(value).find(':input[name="query_2"]');
                            let query_2_value = query_2.val();
                            if(query_2_value)
                            {
                                query.push("f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + operator_value + "&f[" + index + "][query_1]=" + query_1_value+ "&f[" + index + "][query_2]=" + query_2_value);
                            }
                        }
                    }
                }
                else
                {
                    let operator = $(value).find(':input[name="operator"]');
                    let operator_value = operator.find(":selected").val();
                    if(operator_value)
                    {
                        let query_1 = $(value).find(':input[name="query_1"]');
                        let query_1_value = query_1.find(":selected").val();
                        if(query_1_value)
                        {
                            query.push("f[" + index + "][column]=" + column_value + "&f[" + index + "][operator]=" + operator_value + "&f[" + index + "][query_1]=" + query_1_value);
                        }
                    }
                }
            }
        });

        if(query.length != 0)
        {
            filter_button.addClass('loading');
            $('.filter_ctrl').prop('disabled', true);
    
            match_value = $('#filter_match').find(":selected").val();

            let filter_url = "{{ url('contacts/smsfilters').'?' }}" + query.join('&') + "&order_column=sub_date&order_direction=desc&filter_match="+ match_value;
            $.ajax({
                url: filter_url,
                method: 'get',
                success: function(response){
                    filter_button.removeClass('loading');
                    $('.filter_ctrl').prop('disabled', false);
                    contacts_data = response.collection.data;
                    filter_query_string = query.join('&') + "&filter_match=" + match_value;
                    $('#num_filter_subscriber').text(contacts_data.length)
                    $('#filter_reset_btn').show();

                },
                error: function(response){
                    filter_button.removeClass('loading');
                    $('.filter_ctrl').prop('disabled', false);
                }
            });
        }        
    });

    $('#filter_reset_btn').on('click', function(e){
        e.preventDefault();

        let rest_button = $(this);
        rest_button.addClass('loading');
        $('.filter_ctrl').prop('disabled', true);

        $.ajax({
            url: "{{ url('contacts/smsfilters').'?' }}",
            method: 'get',
            success: function(response){
                contacts_data = response.collection.data;
                filter_query_string = "";

                $.ajax({
                    url: "{{ url('contacts/smscampaign').'/'.$sms_campaign->id }}",
                    method: 'post',
                    data: {'smscampaign_contacts' : contacts_data, 'filter_query_string' : filter_query_string,  _token: '{{ csrf_token() }}'},
                    success:function(response){
                        rest_button.removeClass('loading');
                        $('.filter_ctrl').prop('disabled', false);
                        $('#num_filter_subscriber').text(contacts_data.length)
                        $('.campaign_contacts_count').text(contacts_data.length);
                    },
                    error: function(response){
                        rest_button.removeClass('loading');
                        $('.filter_ctrl').prop('disabled', false);
                    }
                });

            },
            error: function(response){
                rest_button.removeClass('loading');
                $('.filter_ctrl').prop('disabled', false);
            }
        });

        $('#all_filter_rules').empty();

        $('#all_filter_rules').append('<div class="filter_rule fields"  style="display: flex; flex-wrap: wrap; border: 2px solid #f2f2f2; padding: 1px;"><div class="field" style="width: 26%;"><select name="column" class="ui fluid dropdown filter_option"><option value="">Select filter</option><option value="name">Name</option><option value="email">Email</option><option value="sub_date">Subscription Date</option><option value="tag">Tag</option><option value="segment">Segment</option><option value="country_name">Country</option><option value="form_id">Form Subscribed to</option><option value="email_openers">Openers</option><option value="non_email_openers">Non-openers</option><option value="link_clickers">Clickers</option><option value="non_link_clickers">Non-clickers</option>@if(!$user->currentAccount->contactAttributes->isEmpty())<option value="custom">Custom Attributes</option>@endif</select></div><div class="field close_button_div"><button class="close_filter_rule ui button">x</button></div></div>');

        $('.ui.dropdown').dropdown();

        rest_button.hide();

    });

    $('#apply_filter').on('click', function(e){
        e.preventDefault();

        let apply_button = $(this);
        apply_button.addClass('loading');
        $('.filter_ctrl').prop('disabled', true);

        $.ajax({
            url: "{{ url('contacts/smscampaign').'/'.$sms_campaign->id }}",
            method: 'post',
            data: {'smscampaign_contacts' : contacts_data, 'filter_query_string' : filter_query_string, _token: '{{ csrf_token() }}'},
            success:function(response){
                apply_button.removeClass('loading');
                $('.filter_ctrl').prop('disabled', false);
                $('.campaign_contacts_count').text(contacts_data.length);
                $('#num_filter_subscriber').text(contacts_data.length)
                $('#create_form_modal_5').modal('hide');
            },
            error: function(response){
                apply_button.removeClass('loading');
                $('.filter_ctrl').prop('disabled', false);
            }
        });
    });

    $('#view_campaign_contacts').on('click', function(e){
        e.preventDefault();

        $('#create_form_modal_6').modal('show');
    });

    $(document).delegate('.remove_contact', 'click', function(e){
        e.preventDefault();
        $action_link = $(this);
        $.ajax({
            url: $action_link.attr('href'),
            method: 'get',
            success: function(response){
                if(response.campaign_contacts_count == 0)
                {
                    $('#create_form_modal_6 .content').empty();
                    $('#create_form_modal_6 .content').append('<div class="ui message">You have not contacts for this campaign</div>');
                }
                else
                {
                    $action_link.closest('tr').remove();
                }
                $('.campaign_contacts_count').text(response.campaign_contacts_count);
            },
            error: function(response){

            }
        });
    });
</script>