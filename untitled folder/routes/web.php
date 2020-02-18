<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Register Routes
Route::get('register', 'AuthController@showRegistrationForm');
Route::post('register', 'AuthController@register');

// Login Routes
Route::get('/', 'AuthController@showLoginForm');
Route::get('login', 'AuthController@showLoginForm')->name('login');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');

// Password Reset Routes...
Route::get('password/reset', 'AuthController@getRemind')->name('password.request');
Route::post('password/email', 'AuthController@postRemind')->name('password.email');
Route::get('password/reset/{token}', 'AuthController@getReset')->name('password.reset');
Route::post('password/reset', 'AuthController@postReset')->name('password.request');

// Register Invited Members
Route::get('accept/{token}', 'SettingsController@accept')->name('accept');
Route::post('registermember', 'SettingsController@registerMember');

// Confirm From & Reply Email Route
Route::get('verifyemail/{token}', 'SettingsController@verifyEmail')->name('verifyemail');

//Hosted Form routes
Route::get('hostedform/{id}', 'HostedFormController@viewForm');

// Tracked Opened Mail
Route::get('trackopened/{campaign_id}/{contact_id}', 'CampaignController@trackEmail');
Route::get('tracksequenceemail/{email_id}/{contact_id}', 'SequenceEmailController@trackEmail');

// Track Clicked Links
Route::get('link/{replacement_url}/{contact_id}', 'CampaignController@trackLink');
Route::get('contentlink/{replacement_url}/{contact_id}', 'SequenceEmailController@trackLink');
Route::get('smslink/{replacement_url}/{contact_id}', 'SmsCampaignController@trackLink');

// Unsubscribe and change contact's email for campaigns
Route::get('unsubscribe/{campaign_id}/{contact_id}', 'SubscriberController@unsubscribeContactView');
Route::post('unsubscribe_contact', 'SubscriberController@unsubscribeContact');
Route::get('changesubscriber/{campaign_id}/{contact_id}', 'SubscriberController@changeEmailView');
Route::post('change_email', 'SubscriberController@changeEmail');

// Unsubscribe and change contact's email for sequence
Route::get('unsubscribe_seq/{email_id}/{contact_id}', 'SubscriberController@unsubscribeContactViewSeq');
Route::post('unsubscribe_contact_seq', 'SubscriberController@unsubscribeContactSeq');
Route::get('changesubscriber_seq/{email_id}/{contact_id}', 'SubscriberController@changeEmailViewSeq');
Route::post('change_email_seq', 'SubscriberController@changeEmailSeq');

// Verify Subscription
Route::get('verify/{token}', 'SubscriberController@verify')->name('verify');

// Webhook for bounce mail, spam mail, recieve sms
Route::post('campaignwebhook', 'WebhookController@campaignHook');
Route::post('sequencewebhook', 'WebhookController@sequenceEmailHook');
Route::post('receive_messages', 'WebhookController@receiveMessage');

Route::group(['middleware' => ['auth']], function () {
    // Dashboard Route
    Route::get('dashboard', 'DashboardController@viewDashboard');

    // Form Routes
    Route::get('forms', 'FormController@accountForms');
    Route::post('createform', 'FormController@createForm');
    Route::get('createform/{id}', 'FormController@viewForm');
    Route::get('editform/{id}', 'FormController@editForm');
    Route::post('updateform', 'FormController@updateForm');
    Route::post('addrule', 'FormController@addRule');
    Route::post('forms_analysis', 'FormController@formsAnalysis');
    Route::prefix('form')->group(function () {
        Route::post('uploadbgimage', 'FormController@uploadImage');
        Route::get('removebgimage/{id}', 'FormController@removeImage');
        Route::get('responses/{id}', 'FormController@viewResponses');
    });
    Route::prefix('delete')->group(function () {
        Route::get('form/{id}', 'FormController@deleteForm');
        Route::get('rule/{id}', 'FormController@deleteRule');
    });
    Route::post('settheme', 'FormController@setTheme');
    Route::get('subscribers/form/{id}', 'SubscriberController@viewSubscribers');
    Route::get('sitecode/{id}', 'FormController@viewSiteCode');

    // Polls routes
    Route::get('polls', 'PollController@userPolls');
    Route::post('createpoll', 'PollController@createPoll');
    Route::get('createpoll/{id}', 'PollController@viewPoll');
    Route::post('addquestion', 'PollController@addQuestion');
    Route::get('delete/question/{id}', 'PollController@deleteQuestion');
    Route::post('addoption', 'PollController@addOption');
    Route::get('delete/option/{id}', 'PollController@deleteOption');
    Route::get('editpoll/{id}', 'PollController@editPoll');
    Route::post('createpolltemplate', 'PollController@createPollFromTemplate');
    Route::get('poll/result/{id}', 'PollController@viewPollResult');
    Route::post('polls_analysis', 'PollController@pollsAnalysis');

    // Quizzes routes
    Route::get('quizzes', 'QuizController@userQuizzes');
    Route::post('createquiz', 'QuizController@createQuiz');
    Route::get('createquiz/{id}', 'QuizController@viewQuiz');
    Route::post('addoutcome', 'QuizController@addOutcome');
    Route::get('delete/outcome/{id}', 'QuizController@deleteOutcome');
    Route::post('addquizoption', 'QuizController@addOption');
    Route::get('questions/outcomes/{id}', 'QuizController@getQuestionsAndOutcomes');
    Route::get('editquiz/{id}', 'QuizController@editQuiz');
    Route::post('createquiztemplate', 'QuizController@createQuizFromTemplate');
    Route::post('quizzes_analysis', 'QuizController@quizzesAnalysis');

    // Calculators routes
    Route::get('calculators', 'CalculatorController@userCalculators');
    Route::post('createcalculator', 'CalculatorController@createCalculator');
    Route::get('createcalculator/{id}', 'CalculatorController@viewCalculator');
    Route::post('addcalculatorquestion', 'CalculatorController@addQuestion');
    Route::get('questions/maxvalue/{id}', 'CalculatorController@getQuestionMaxValue');
    Route::post('addcalculatoroption', 'CalculatorController@addOption');
    Route::post('addresult', 'CalculatorController@addResult');
    Route::get('delete/result/{id}', 'CalculatorController@deleteResult');
    Route::get('questions/results/{id}', 'CalculatorController@getQuestionsAndResults');
    Route::get('editcalculator/{id}', 'CalculatorController@editCalculator');
    Route::post('createcalculatortemplate', 'CalculatorController@createCalculatorFromTemplate');
    Route::post('calculators_analysis', 'CalculatorController@calculatorsAnalysis');


    /*
    *   Settings Routes
    */

    // Subaccount Routes
    Route::get('subaccount', 'SubAccountController@index');
    Route::post('setaccount', 'SubAccountController@selectSubAccount');
    Route::post('addaccount', 'SubAccountController@addSubAccount');
    Route::get('delete/account/{id}', 'SubAccountController@deleteSubAccount');

    // Team Routes
    Route::get('team', 'SettingsController@userTeam')->name('team');
    Route::post('team', 'SettingsController@addTeamMember');
    Route::get('delete/member/{id}', 'SettingsController@deleteTeamMember');
    Route::post('update_member_role', 'SettingsController@updateMemberRole');

    // Settings Routes
    Route::get('settings', 'SettingsController@viewProfile');
    Route::post('settings', 'SettingsController@updateProfile');
    Route::get('enabledoubleoptin', 'SettingsController@enableDoubleOptin');
    Route::get('disabledoubleoptin', 'SettingsController@disableDoubleOptin');
    Route::get('enablegdpr', 'SettingsController@enableGdpr');
    Route::get('disablegdpr', 'SettingsController@disableGdpr');

    // Password Route
    Route::post('changepassword', 'SettingsController@changePassword');

    // From & Reply Email Routes
    Route::get('from_reply', 'SettingsController@viewFromReply');
    Route::post('from_reply', 'SettingsController@addFromReplyEmail');
    Route::get('delete/email/{id}','SettingsController@deleteFromReplyEmail');

    // Integration Routes
    Route::get('integration', 'IntegrationController@viewIntegrations');
    Route::post('createsmtp', 'IntegrationController@storeSmtp');
    Route::post('updatesmtp', 'IntegrationController@updateSmtp');
    Route::get('delete/smtp/{id}', 'IntegrationController@deleteSmtp');
    Route::post('createapi', 'IntegrationController@storeApi');
    Route::post('updateapi', 'IntegrationController@updateApi');
    Route::get('delete/api/{id}', 'IntegrationController@deleteApi');

    // SMS MMS Setting's Routes
    Route::get('sms_mms_settings', 'SmsMmsController@viewSmsMmsIntegrations');
    Route::post('create_sms_mms', 'SmsMmsController@createSmsMssIntegration');
    Route::get('delete/sms_mms/{id}', 'SmsMmsController@deleteSmsMms');

    // Unsubscribe Page Setting's Routes
    Route::get('unsubscribe_page_settings', 'UnsubscribePageController@unsubscribePageSettings');
    Route::post('reasons', 'UnsubscribePageController@addReason');
    Route::post('updatereason', 'UnsubscribePageController@updateReason');
    Route::post('updatepagecontent', 'UnsubscribePageController@updatePageContent');
    Route::get('delete/reason/{id}', 'UnsubscribePageController@deleteReason');

    // Contact Attributes Routes
    Route::get('contact_attributes', 'ContactAttributesController@viewAttributes');
    Route::post('createattribute', 'ContactAttributesController@createAttribute');
    Route::get('attribute/delete/{attribute_id}', 'ContactAttributesController@deleteAttribute');
    Route::post('updateattribute', 'ContactAttributesController@updateAttribute');

    // Template Routes
    Route::get('templates', 'TemplateController@viewTemplates');
    Route::post('createtemplate', 'TemplateController@createTemplate');
    Route::get('view/template/{template_id}', 'TemplateController@viewTemplateEditor');
    Route::get('delete/template/{template_id}', 'TemplateController@deleteTemplate');

    // RSS Feeds Routes
    Route::get('rss', 'FeedController@viewRss');
    Route::get('createfeed', 'FeedController@createFeed');
    Route::get('create/{rss_id}/rss', 'FeedController@viewFeed');
    Route::post('/contacts/rss/{rss}', 'FeedController@store');
    Route::post('createfeed', 'FeedController@saveFeed');
    Route::get('delete/feed/{feed_id}', 'FeedController@deleteFeed');
    Route::get('edit/feed/{feed_id}', 'FeedController@editFeed');

    // Visual Automation Routes
    Route::get('visual_automation', 'VisualAutomationController@viewWorkflows');
    Route::get('create_workflow', 'VisualAutomationController@createWorkflow');

    // Bot Routes
    Route::get('bot', 'BotController@viewLogic');
    Route::get('createlogic', 'BotController@createBotLogic');

    // SMS Messages Routes
    Route::get('sms_messages', 'SmsMessageController@viewSmsMessages');
    Route::get('get_sms_messages/{contact_id}', 'SmsMessageController@getMessages');
    Route::post('set_active_integration', 'SmsMessageController@setActiveIntegration');
    Route::post('send_contact_message', 'SmsMessageController@sendContactMessage');


    // SMS Campaigns Routes
    Route::get('sms_campaigns', 'SmsCampaignController@viewSmsCampaigns');
    Route::get('createsmscampaign', 'SmsCampaignController@createSmsCampaign');
    Route::get('create/{id}/smscampaign', 'SmsCampaignController@viewSmsCampaign');
    Route::get('edit/{id}/smscampaign', 'SmsCampaignController@editSmsCampaign');
    Route::post('/contacts/smscampaign/{smscampaign}', 'SmsCampaignController@store');
    Route::post('savesmscampaign', 'SmsCampaignController@saveSmsCampaign');
    Route::post('updatesmscampaign', 'SmsCampaignController@updateSmsCampaign');
    Route::get('delete/{id}/smscampaign', 'SmsCampaignController@deleteSmsCampaign');
    Route::get('delete/mms_image/{id}', 'SmsCampaignController@deleteMmsImage');
    Route::get('duplicate/{id}/smscampaign', 'SmsCampaignController@duplicateSmsCampaign');
    Route::get('view_analysis_sms/{sms_campaign_id}', 'SmsCampaignController@viewAnalysisSms');
    Route::get('downloadsmsclickerslist/{campaign_id}','SmsCampaignController@downloadClickersList');
    Route::get('downloadsmslinklist/{link_id}','SmsCampaignController@downloadLinkList');


    // Campaign Routes
    Route::get('campaigns', 'CampaignController@viewCampaigns');
    Route::get('createcampaign', 'CampaignController@createCampaign');
    Route::get('create/{id}/campaign', 'CampaignController@viewCampaign');
    Route::get('edit/{id}/campaign', 'CampaignController@editCampaign');
    Route::get('delete/{id}/campaign', 'CampaignController@deleteCampaign');
    Route::post('updatecampaign', 'CampaignController@updateCampaign');
    Route::post('savecampaign', 'CampaignController@saveCampaign');
    Route::post('save_rich_content', 'CampaignController@saveRichContent');
    Route::get('duplicate/{id}/campaign', 'CampaignController@duplicateCampaign');
    Route::post('send_sender', 'CampaignController@sendToSender');
    Route::post('/contacts/campaign/{campaign}', 'CampaignController@store');
    Route::get('remove_contact/{contact_id}/campaign/{campaign_id}', 'CampaignController@removeCampaignContact');
    Route::get('view_analysis/{campaign_id}', 'CampaignController@viewAnalysis');
    //Download Routes
    Route::get('downloadopenerslist/{campaign_id}', 'CampaignController@downloadOpenersList');
    Route::get('downloadclickerslist/{campaign_id}','CampaignController@downloadClickersList');
    Route::get('downloadlinklist/{link_id}','CampaignController@downloadLinkList');
    Route::get('downloadcomplainedlist/{campaign_id}', 'CampaignController@downloadComplainedList');
    Route::get('downloadbouncedlist/{campaign_id}', 'CampaignController@downloadBouncedList');
    Route::get('downloadhardbouncedlist/{campaign_id}', 'CampaignController@downloadHardBouncedList');

    // Sequence Routes
    Route::get('sequences', 'SequenceController@viewSequences');
    Route::get('createsequence', 'SequenceController@createSequence');
    Route::get('create/{id}/sequence', 'SequenceController@viewSequence');
    Route::get('edit/{id}/sequence', 'SequenceController@editSequence');
    Route::get('delete/{id}/sequence', 'SequenceController@deleteSequence');
    Route::post('createsequence', 'SequenceController@saveNewSequence');
    Route::get('activatesequence/{sequence_id}', 'SequenceController@activateSequence');
    Route::get('deactivatesequence/{sequence_id}', 'SequenceController@deactivateSequence');
    Route::post('/contacts/sequence/{sequence}', 'SequenceController@store');
    Route::get('remove_contact/{contact_id}/sequence/{sequence_id}', 'SequenceController@removeSequenceContact');

    // Sequence Email Routes
    Route::get('sequence/{sequence_id}/contents', 'SequenceEmailController@viewContents');
    Route::get('sequence/{sequence_id}/editcontent/{content_id}', 'SequenceEmailController@editContent');
    Route::post('sequence/{sequence_id}/sortemail', 'SequenceEmailController@sortEmail');
    Route::post('createsequencecontent', 'SequenceEmailController@saveContent');
    Route::get('delete/emailcontent/{content_id}', 'SequenceEmailController@deleteContent');
    Route::get('duplicate/emailcontent/{content_id}', 'SequenceEmailController@duplicateContent');
    Route::get('activatesequenceemail/{content_id}', 'SequenceEmailController@activateSequenceEmail');
    Route::get('deactivatesequenceemail/{content_id}', 'SequenceEmailController@deactivateSequenceEmail');
    Route::get('view_email_analysis/{content_id}', 'SequenceEmailController@viewAnalysis');
    //Download Routes
    Route::get('downloadopenerslistcontent/{content_id}', 'SequenceEmailController@downloadOpenersList');
    Route::get('downloadclickerslistcontent/{content_id}','SequenceEmailController@downloadClickersList');
    Route::get('downloadlinklistcontent/{link_id}','SequenceEmailController@downloadLinkList');
    Route::get('downloadcomplainedlistcontent/{content_id}', 'SequenceEmailController@downloadComplainedList');
    Route::get('downloadbouncedlistcontent/{content_id}', 'SequenceEmailController@downloadBouncedList');
    Route::get('downloadhardbouncedlistcontent/{content_id}', 'SequenceEmailController@downloadHardBouncedList');

    //Contacts Routes
    Route::get('contacts', 'ContactController@contactDashboard');
    Route::post('contacts', 'ContactController@store')->name('contacts');
    Route::get('contact/edit/{contact_id}', 'ContactController@editContact');
    Route::get('contacts/exports', 'ContactController@contactsexport');
    Route::post('contact/update', 'ContactController@updateContact');
    Route::patch('/contacts/{contact}/check', 'ContactController@updateCheck');
    Route::patch('/contactCheckAll', 'ContactController@updateAll');
    Route::get('/contactsDeleteMarked', 'ContactController@destroyMarked');
    Route::get('/contact/delete/{contact_id}', 'ContactController@deleteContact');
    Route::get('purgelist/{number}', 'ContactController@purgeList');
    Route::post('update_activities', 'ContactController@updateActivities');
    Route::get('unsubscribe_contact/{contact_id}', 'ContactController@unsubscribeContact');

    //Contacts Import Routes
    Route::post('contact_process_import', 'ContactCsvController@myParseImport');

    //Tag Routes
    Route::post('addtag', 'TagController@store')->name('addtag');
    Route::get('tag/edit/{tag_id}', 'TagController@editTag');
    Route::post('tag/update', 'TagController@updateTag');
    Route::get('tag/delete/{tag_id}', 'TagController@deleteTag');
    Route::get('tag/{tag_id}/remove/{contact_id}', 'TagController@removeTagFromContact');

    //Segment Routes
    Route::get('segment/edit/{segment_id}', 'SegmentController@editSegment');
    Route::post('segment/update', 'SegmentController@updateSegment');
    Route::get('segment/delete/{segment_id}', 'SegmentController@deleteSegment');
    Route::get('/contacts/filters', 'SegmentController@contact_segment');
    Route::get('/contacts/smsfilters', 'SegmentController@sms_contact_segment');
    Route::post('/contacts/segment/save', 'SegmentController@store');

    //DragDrop Routes
    Route::get('dragdropeditor/{campaign_id}', 'DragDropController@index');
    Route::get('template-load-page', 'DragDropController@templateLoadPage');
    Route::get('template-blank-page', 'DragDropController@templateBlankPage');
    Route::group(['prefix' => 'dragdropeditor'], function(){
        Route::group(['prefix' => 'elements'], function(){
            Route::get('page-header', 'DragDropController@elementPageHeader');
            Route::get('heading', 'DragDropController@elementHeading');
            Route::get('paragraph', 'DragDropController@elementParagraph');
            Route::get('image-left-text', 'DragDropController@elementImageLeft');
            Route::get('image-right-text', 'DragDropController@elementImageRight');
            Route::get('2-column-text', 'DragDropController@elementTwoColumnText');
            Route::get('unordered-list', 'DragDropController@elementUnorderedList');
            Route::get('ordered-list', 'DragDropController@elementOrderedList');
            Route::get('jumbotron', 'DragDropController@elementJumbotron');
            Route::get('features', 'DragDropController@elementFeatures');
            Route::get('service-list', 'DragDropController@elementServiceList');
            Route::get('price-table', 'DragDropController@elementPriceTable');
            Route::get('image-full', 'DragDropController@elementImageFull');
            Route::get('image-2-column', 'DragDropController@elementImageTwoColumn');
            Route::get('video', 'DragDropController@elementVideo');
            Route::get('divider', 'DragDropController@elementDivider');
            Route::get('divider-dotted', 'DragDropController@elementDividerDotted');
            Route::get('divider-dashed', 'DragDropController@elementDividerDashed');
            Route::get('view-browser', 'DragDropController@elementViewBrowser');
            Route::get('button-1', 'DragDropController@elementButton1');
            Route::get('button-2', 'DragDropController@elementButton2');
            Route::get('button-3', 'DragDropController@elementButton3');
            Route::get('social-1', 'DragDropController@elementSocial1');
            Route::get('social-2', 'DragDropController@elementSocial2');
            Route::get('social-3', 'DragDropController@elementSocial3');
            Route::get('address', 'DragDropController@elementAddress');
            Route::get('address-logo', 'DragDropController@elementAddressLogo');
        });

        Route::post('export', 'DragDropController@exportHtml');
        Route::post('save_template', 'DragDropController@saveTemplate');
        Route::post('load_user_templates', 'TemplateController@loadTemplate');
        Route::post('get_template_content', 'TemplateController@getTemplateContent');
    });

     # Templates
    Route::get('dragdropeditor/{template_id}', 'DragDropController@index');
    Route::group(['prefix' => 'view/template'], function(){
        Route::group(['prefix' => 'elements'], function(){
            Route::get('page-header', 'DragDropController@elementPageHeader');
            Route::get('heading', 'DragDropController@elementHeading');
            Route::get('paragraph', 'DragDropController@elementParagraph');
            Route::get('image-left-text', 'DragDropController@elementImageLeft');
            Route::get('image-right-text', 'DragDropController@elementImageRight');
            Route::get('2-column-text', 'DragDropController@elementTwoColumnText');
            Route::get('unordered-list', 'DragDropController@elementUnorderedList');
            Route::get('ordered-list', 'DragDropController@elementOrderedList');
            Route::get('jumbotron', 'DragDropController@elementJumbotron');
            Route::get('features', 'DragDropController@elementFeatures');
            Route::get('service-list', 'DragDropController@elementServiceList');
            Route::get('price-table', 'DragDropController@elementPriceTable');
            Route::get('image-full', 'DragDropController@elementImageFull');
            Route::get('image-2-column', 'DragDropController@elementImageTwoColumn');
            Route::get('video', 'DragDropController@elementVideo');
            Route::get('divider', 'DragDropController@elementDivider');
            Route::get('divider-dotted', 'DragDropController@elementDividerDotted');
            Route::get('divider-dashed', 'DragDropController@elementDividerDashed');
            Route::get('view-browser', 'DragDropController@elementViewBrowser');
            Route::get('button-1', 'DragDropController@elementButton1');
            Route::get('button-2', 'DragDropController@elementButton2');
            Route::get('button-3', 'DragDropController@elementButton3');
            Route::get('social-1', 'DragDropController@elementSocial1');
            Route::get('social-2', 'DragDropController@elementSocial2');
            Route::get('social-3', 'DragDropController@elementSocial3');
            Route::get('address', 'DragDropController@elementAddress');
            Route::get('address-logo', 'DragDropController@elementAddressLogo');
        });
        Route::post('save_and_exit_template', 'TemplateController@saveTemplate');
        Route::post('load_user_templates', 'TemplateController@loadTemplate');
        Route::post('get_template_content', 'TemplateController@getTemplateContent');
        Route::post('export', 'DragDropController@exportHtml');

    });


    // Push Notification Routes
    Route::get('push_notification', 'PushNotificationController@pushes');
    Route::post('create_push', 'PushNotificationController@createPushForm');
    Route::get('edit_push', 'PushNotificationController@editPushForm');
    Route::post('notifications', 'PushNotificationController@sendNote');
    Route::get('create_push/{id}', 'PushNotificationController@viewPushForm');
    Route::get('company/check/{company_name}', 'PushNotificationController@checkCompany');

    //Admin routes
    Route::group(['prefix' => 'admin'], function () {
        Route::get('dashboard', 'AdminController@index');
        Route::get('customers', 'AdminController@customers');
        Route::get('edit/user/{id}', 'AdminController@editUser');
        Route::get('delete/user/{id}', 'AdminController@deleteUser');
        Route::post('updateuser', 'AdminController@updateUser');
        Route::get('register', 'AdminController@registerForm');
        Route::post('registeruser', 'AdminController@registerUser');
        Route::get('generateLicense', 'AdminController@viewGenerator');
        Route::post('generateLicense', 'AdminController@generate');

        // Poll Templates
        Route::get('polltemplates', 'AdminController@pollTemplates');
        Route::post('createpolltemplate', 'AdminController@createPollTemplate');
        Route::get('createpolltemplate/{id}', 'AdminController@viewPollTemplate');
        Route::get('editpolltemplate/{id}', 'AdminController@editPollTemplate');

        // Quiz Templates
        Route::get('quiztemplates', 'AdminController@quizTemplates');
        Route::post('createquiztemplate', 'AdminController@createQuizTemplate');
        Route::get('createquiztemplate/{id}', 'AdminController@viewQuizTemplate');
        Route::get('editquiztemplate/{id}', 'AdminController@editQuizTemplate');

        // Calculator Templates
        Route::get('calculatortemplates', 'AdminController@calculatorTemplates');
        Route::post('createcalculatortemplate', 'AdminController@createCalculatorTemplate');
        Route::get('createcalculatortemplate/{id}', 'AdminController@viewCalculatorTemplate');
        Route::get('editcalculatortemplate/{id}', 'AdminController@editCalculatorTemplate');

        // Update Template
        Route::post('updatetemplate', 'AdminController@updateTemplate');

        // Theme Templates
        Route::get('themes', 'AdminController@themes');
        Route::post('createtheme', 'AdminController@createTheme');
        Route::get('createtheme/{id}', 'AdminController@viewTheme');
        Route::get('edittheme/{id}', 'AdminController@editTheme');
        Route::post('updatetheme', 'AdminController@updateTheme');
        Route::get('deletetheme/{id}', 'AdminController@deleteTheme');

        // Template Routes
        Route::get('templates', 'AdminController@viewTemplates');
        Route::post('createtemplate', 'AdminController@createTemplate');
        Route::get('view/template/{template_id}', 'AdminController@viewTemplateEditor');
        Route::get('delete/template/{template_id}', 'AdminController@deleteTemplate');

        Route::get('dragdropeditor/{template_id}', 'DragDropController@index');
        Route::group(['prefix' => 'view/template'], function(){
            Route::group(['prefix' => 'elements'], function(){
                Route::get('page-header', 'DragDropController@elementPageHeader');
                Route::get('heading', 'DragDropController@elementHeading');
                Route::get('paragraph', 'DragDropController@elementParagraph');
                Route::get('image-left-text', 'DragDropController@elementImageLeft');
                Route::get('image-right-text', 'DragDropController@elementImageRight');
                Route::get('2-column-text', 'DragDropController@elementTwoColumnText');
                Route::get('unordered-list', 'DragDropController@elementUnorderedList');
                Route::get('ordered-list', 'DragDropController@elementOrderedList');
                Route::get('jumbotron', 'DragDropController@elementJumbotron');
                Route::get('features', 'DragDropController@elementFeatures');
                Route::get('service-list', 'DragDropController@elementServiceList');
                Route::get('price-table', 'DragDropController@elementPriceTable');
                Route::get('image-full', 'DragDropController@elementImageFull');
                Route::get('image-2-column', 'DragDropController@elementImageTwoColumn');
                Route::get('video', 'DragDropController@elementVideo');
                Route::get('divider', 'DragDropController@elementDivider');
                Route::get('divider-dotted', 'DragDropController@elementDividerDotted');
                Route::get('divider-dashed', 'DragDropController@elementDividerDashed');
                Route::get('view-browser', 'DragDropController@elementViewBrowser');
                Route::get('button-1', 'DragDropController@elementButton1');
                Route::get('button-2', 'DragDropController@elementButton2');
                Route::get('button-3', 'DragDropController@elementButton3');
                Route::get('social-1', 'DragDropController@elementSocial1');
                Route::get('social-2', 'DragDropController@elementSocial2');
                Route::get('social-3', 'DragDropController@elementSocial3');
                Route::get('address', 'DragDropController@elementAddress');
                Route::get('address-logo', 'DragDropController@elementAddressLogo');
            });
            Route::post('save_and_exit_template', 'TemplateController@saveTemplate');
            Route::post('load_user_templates', 'TemplateController@loadTemplate');
            Route::post('get_template_content', 'TemplateController@getTemplateContent');
            Route::post('export', 'DragDropController@exportHtml');

        });
    });
});

//API Routes
Route::group(['middleware' => 'cors', 'prefix' => 'api/v1'], function () {
    Route::get('/formdata/{id}', 'ApiController@getFormData');
    Route::post('/subscribe', 'ApiController@subscribe');
    Route::post('/clicks', 'ApiController@clicks');
    Route::post('/option/store', 'ApiController@storeOptions');
    Route::post('/outcome', 'ApiController@getOutcome');
});

// Manifest file (optional if VAPID is used)
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'gcm_sender_id' => config('webpush.gcm.sender_id')
    ];
});

Route::domain('{company_name}.sendmunk.com')->group(function () {
    Route::get('/notif/{id}', 'PushNotificationController@acceptNoti');
    Route::post('subscriptions', 'PushNotificationController@update');
});

Route::get('test_push', function(){
    return view('test');
});

