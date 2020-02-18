<?php

namespace App\Http\Controllers;

use App\Form;
use App\Theme;
use App\DisplayRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Carbon\Carbon;
use App\Visitor;
use App\Subscription;

class FormController extends Controller
{
    //
    public function viewForm($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }
        
        $form = Form::find($id);
        if ($form) {
            if($form->sub_account_id != $user->currentAccount->id)
            {
                return redirect('forms');
            }

            $themes = Theme::where('form_type', $form->form_type)->get();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;

            return view('forms.createform')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'main_user' => $mainUser]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function createForm(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        $data = $request->all();

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = $data['form_type'];
        $form->theme_id = Theme::where('form_type', $data['form_type'])->first()->id;
        $form->sub_account_id = $user->currentAccount->id;

        if ($data['form_type'] == 'popover' || $data['form_type'] == 'embedded' || $data['form_type'] == 'scrollbox' || $data['form_type'] == 'welcome_mat') {
            $form->headline = 'Join Our Newsletter';
            $form->description = 'Signup today for free and be the first to get notified on new updates';
            $form->foot_note = "And don't worry, we hate spam too! you can unsubscribe at anytime";
            $form->success_headline = 'Thank you';
            $form->success_description = 'Thank you for subscribing with us';
            $form->background_color = '#ffffff';
            $form->background_overlay = '#808080';
            $form->button_text_color = '#ffffff';
            $form->button_color = '#1678c2';
            $form->email_label = 'Email';
            $form->email_placeholder = 'Enter your email';
            $form->button_text = 'Subscribe';
        } elseif ($data['form_type'] == 'topbar') {
            $form->headline = 'Join Our Newsletter';
            $form->success_headline = 'Thank you';
            $form->background_color = '#1678c2';
            $form->button_text_color = '#ffffff';
            $form->button_color = '#000000';
            $form->email_label = 'Email';
            $form->email_placeholder = 'Enter your email';
            $form->button_text = 'Subscribe';
        } 

        $form->save();  

        return redirect('createform/' . $form->id);
    }

    public function updateForm(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        $data = $request->all();
        $form = Form::find($data['form_id']);
        if ($form) {
            $form->fill($data);

            if (isset($data['headline'])) {
                if (!empty($data['headline'])) {
                    $dom = new \domdocument();
                    $dom->loadHtml($data['headline'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $detail = $dom->savehtml();
                    $form->headline = $detail;
                }
            }
            if (isset($data['description'])) {
                if (!empty($data['description'])) {
                    $dom = new \domdocument();
                    $dom->loadHtml($data['description'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $detail = $dom->savehtml();
                    $form->description = $detail;
                }
            }
            if (isset($data['foot_note'])) {
                if (!empty($data['foot_note'])) {
                    $dom = new \domdocument();
                    $dom->loadHtml($data['foot_note'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $detail = $dom->savehtml();
                    $form->foot_note = $detail;
                }
            }
            if (isset($data['success_headline'])) {
                if (!empty($data['success_headline'])) {
                    $dom = new \domdocument();
                    $dom->loadHtml($data['success_headline'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $detail = $dom->savehtml();
                    $form->success_headline = $detail;
                }
            }
            if (isset($data['success_description'])) {
                if (!empty($data['success_description'])) {
                    $dom = new \domdocument();
                    $dom->loadHtml($data['success_description'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                    $detail = $dom->savehtml();
                    $form->success_description = $detail;
                }
            }
            if (isset($data['redirect_url'])) {
                $form->redirect_url = $data['redirect_url'];
            }
            if (isset($data['list_id'])) {
                // $form->list_id = $data['list_id'];÷\
            }

            $form->autohide = false;
            $form->allow_closing = false;
            $form->desktop_device = false;
            $form->tablet_device = false;
            $form->mobile_device = false;
            $form->page_load = false;
            $form->page_exit = false;
            $form->show_phone_field = false;

            if (isset($data['autohide'])) {
                $form->autohide = boolval($data['autohide']);
            }
            if (isset($data['allow_closing'])) {
                $form->allow_closing = boolval($data['allow_closing']);
            }
            if (isset($data['desktop_device'])) {
                $form->desktop_device = boolval($data['desktop_device']);
            }
            if (isset($data['tablet_device'])) {
                $form->tablet_device = boolval($data['tablet_device']);
            }
            if (isset($data['mobile_device'])) {
                $form->mobile_device = boolval($data['mobile_device']);
            }
            if (isset($data['page_load'])) {
                $form->page_load = boolval($data['page_load']);
            }
            if (isset($data['page_exit'])) {
                $form->page_exit = boolval($data['page_exit']);
            }
            if (isset($data['first_visit'])) {
                $form->page_exit = boolval($data['first_visit']);
            }
            if (isset($data['tracking_pixel'])) {
                $form->tracking_pixel = $data['tracking_pixel'];
            }
            if(isset($data['show_phone_field'])){
                $form->show_phone_field = boolval($data['show_phone_field']);
            }

            $form->save();

            return redirect('sitecode/' . $form->id)->with(['status' => 'Form Created Successfully']);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function deleteForm($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }
        
        $form = Form::find($id);
        if ($form) {
            if($user->currentAccount->id == $form->sub_account_id)
            {
                $form->delete();
                return back()->with(['status', 'Form Deleted Successfully']);
            }
            return back()->with('error', "Authorized Access");
        }

        return back()->with(['error', 'Form not found']);
    }

    public function editForm($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        $form = Form::find($id);
        if ($form) {

            if($form->sub_account_id != $user->currentAccount->id)
            {
                return redirect('forms');
            }

            $themes = Theme::where('form_type', $form->form_type)->get();
            if(!$themes)
            {
                return view('errors.default');;
            }
            
            $current_theme = Theme::find($form->theme_id);
            $rules = $form->displayRules;

            return view('forms.editform')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'main_user' => $mainUser]);

        }
        return back()->with(['error', 'Form not found']);
    }

    public function viewSiteCode($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        $form = Form::find($id);
        if ($form) {
            if($form->sub_account_id == $user->currentAccount->id)
            {
                return view('forms.sitecode')->with(['user' => $user, 'form' => $form, 'main_user' => $mainUser]);
            }
            return redirect('forms');
        }
        return back()->with(['error', 'Form not found']);
    }

    public function addRule(Request $request)
    {
        $data = $request->all();

        $rule = new DisplayRule();
        $rule->show = $data['show'];
        $rule->match = $data['match'];
        $rule->page_name = $data['page_name'];
        $rule->form_id = $data['form_id'];
        $rule->save();

        return $rule;
    }

    public function deleteRule($id)
    {
        $rule = DisplayRule::find($id);
        if ($rule) {
            $rule->delete();
            return 'success';
        }
        return 'error';
    }

    public function accountForms()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }

        if(!$user->profile)
        {
            return redirect('settings')->with('status', "Please update your profile");
        }

        $forms = Form::where('sub_account_id', $user->currentAccount->id)
                    ->where('poll_type', null)->where('is_template', false)->get();

        $form_array = [];
        foreach($forms as $form){
            array_push($form_array, $form->id);
        }

        $carbon = \Carbon\Carbon::now()->subMonth();

        $subscriptions = \DB::table('subscriptions')->whereIn('form_id', $form_array)->whereDate('created_at', '>=', $carbon)->groupBy('date')->orderBy('date', 'DESC')->get(array(\DB::raw('Date(created_at) as date'),\DB::raw('COUNT(*) as "count"')));

        $visits = \DB::table('visitors')->whereIn('form_id', $form_array)->whereDate('created_at', '>=', $carbon)->groupBy('date')->orderBy('date', 'DESC')->get(array(\DB::raw('Date(created_at) as date'),\DB::raw('SUM(visit_count) as "count"')));

        $labels = $this->generateDateRange(Carbon::now()->subMonth(), Carbon::now());
        $subs = [];
        $vis = [];

        foreach($labels as $label)
        {
            $checkVisit = $visits->where('date', $label)->first();
            if($checkVisit)
            {
                array_push($vis, intval($checkVisit->count));
            }
            else
            {
                array_push($vis, 0);
            }

            $checkSub = $subscriptions->where('date', $label)->first();
            if($checkSub)
            {
                array_push($subs, $checkSub->count);
            }
            else
            {
                array_push($subs, 0);
            }
        }

        $forms_count = $forms->count();
        $subs_count = Subscription::whereIn('form_id', $form_array)->count();
        $vis_count = Visitor::whereIn('form_id', $form_array)->sum('visit_count');
        if($vis_count != 0)
        {
            $conversion_rate = $subs_count / $vis_count * 100;
        }
        else
        {
            $conversion_rate = 0;
        }
        return view('forms.forms')->with(['user' => $user, 'forms' => $forms, 'main_user' => $mainUser, 'labels' => $labels, 'subscriptions' => $subs, 'visits' => $vis, 'forms_count' => $forms_count, 'subs_count' => $subs_count, 'vis_count' => $vis_count, 'conversion_rate' => round($conversion_rate, 2)]);
    }

    public function uploadImage(Request $request)
    {
        $data = $request->all();
        $form = Form::find($data['form_id']);

        if ($form) {
            if ($request->hasFile('file')) {
                $filePath = public_path() . '/bgimages/';
                if (!\File::isDirectory($filePath)) {
                    \File::makeDirectory($filePath);
                }
                $name = time() . $form->id;
                $img = Image::make($data['file'])->encode('png', 100);
                $img->save($filePath . $name . '.png');
                $form->background_image = 'bgimages/' . $name . '.png';
                $form->save();

                return $form->background_image;
            }
        }
        return 'error';
    }

    public function removeImage($id)
    {
        $form = Form::find($id);
        if ($form) {
            $form->background_image = null;
            $form->save();

            return 'success';
        }
        return 'error';
    }

    public function viewResponses($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $all_options = [];
        $form = Form::find($id);
        if($form)
        {
            if($user->currentAccount->id == $form->sub_account_id)
            {
                return view('forms.responses')->with(['questions' => $form->questions, 'user' => $user, 'main_user' => $mainUser]);
            }
        }
    }

    public function setTheme(Request $request)
    {
        $data = $request->all();
        $form = Form::find($data['form_id']);
        $theme = Theme::find($data['theme_id']);

        if($form)
        {
            if($theme)
            {
                $form->theme_id = $theme->id;
                if($theme->background_color)
                {
                    $form->background_color = $theme->background_color;
                }
                if($theme->background_overlay)
                {
                    $form->background_overlay = $theme->background_overlay;
                }
                if($theme->button_text_color)
                {
                    $form->button_text_color = $theme->button_text_color;
                }
                if($theme->button_color)
                {
                    $form->button_color = $theme->button_color;
                }
                if($theme->border_style)
                {
                    $form->border_style = $theme->border_style;
                }
                if($theme->border_size)
                {
                    $form->border_size = $theme->border_size;
                }
                if($theme->border_color)
                {
                    $form->border_color = $theme->border_color;
                }
                if($theme->button_font_size)
                {
                    $form->button_font_size = $theme->button_font_size;
                }
                if($theme->button_font_family)
                {
                    $form->button_font_family = $theme->button_font_family;
                }
                $form->save();

                return "Successfull";
            }
        }
        return view('errors.default');;
    }

    public function formsAnalysis(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        if($mainUser->member)
        {
            if($mainUser->member->role == 2)
            {
                return back()->with('error', 'This page can only be accessed by owners');
            }
        }
        
        $data = $request->all();

        $forms = $user->currentAccount->forms->where('is_template', false);
        $form_array = [];
        foreach($forms as $form){
            array_push($form_array, $form->id);
        }

        $start = \Carbon\Carbon::parse($data['start_date']);
        $end = \Carbon\Carbon::parse($data['end_date']);
        
        $subscriptions = \DB::table('subscriptions')->whereIn('form_id', $form_array)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->groupBy('date')->orderBy('date', 'DESC')->get(array(\DB::raw('Date(created_at) as date'),\DB::raw('COUNT(*) as "count"')));

        $visits = \DB::table('visitors')->whereIn('form_id', $form_array)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->groupBy('date')->orderBy('date', 'DESC')->get(array(\DB::raw('Date(created_at) as date'),\DB::raw('SUM(visit_count) as "count"')));

        $labels = $this->generateDateRange($start, $end);
        $subs = [];
        $vis = [];

        foreach($labels as $label)
        {
            $checkVisit = $visits->where('date', $label)->first();
            if($checkVisit)
            {
                array_push($vis, intval($checkVisit->count));
            }
            else
            {
                array_push($vis, 0);
            }

            $checkSub = $subscriptions->where('date', $label)->first();
            if($checkSub)
            {
                array_push($subs, $checkSub->count);
            }
            else
            {
                array_push($subs, 0);
            }
        }

        return  response()->json(['labels'=> $labels, 'subscriptions' => $subs, 'visits' => $vis]);
    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
}
