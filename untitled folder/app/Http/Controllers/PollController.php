<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Form;
use App\Theme;
use App\Question;
use App\Option;
use Illuminate\Support\Facades\Auth;
use Image;
use Carbon\Carbon;
use App\Visitor;
use App\Subscription;

class PollController extends Controller
{
    //
    public function userPolls()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }


        $polls = $user->currentAccount->forms->where('form_type', 'poll')->where('is_template', false);

        $templates = Form::where('form_type', 'poll')->where('is_template', true)->get();

        $form_array = [];
        foreach($polls as $form){
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

        $forms_count = $polls->count();
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

        return view('polls.polls')->with(['user' => $user, 'polls' => $polls, 'main_user' => $mainUser, 'templates' => $templates, 'labels' => $labels, 'subscriptions' => $subs, 'visits' => $vis, 'forms_count' => $forms_count, 'subs_count' => $subs_count, 'vis_count' => $vis_count, 'conversion_rate' => round($conversion_rate, 2)]);
    }

    public function createPoll(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'poll';
        $form->poll_type = $data['poll_type'];
        $form->headline = 'POLL HEADING GOES HERE';
        $form->description = 'Poll Subheading goes here';
        $form->success_headline = 'Thank you';
        $form->success_description = 'Thanks for taking our poll.';
        $form->background_color = '#ffffff';
        $form->background_overlay = '#808080';
        $form->button_text_color = '#ffffff';
        $form->button_color = '#1678c2';
        $form->email_label = 'Email';
        $form->email_placeholder = 'Enter your email';
        $form->button_text = 'Click to Continue';
        $form->theme_id = Theme::where('form_type', 'poll')->where('poll_type', $data['poll_type'])->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->save();

        return redirect('createpoll/' . $form->id);
    }

    public function viewPoll($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $form = Form::find($id);
        if ($form) {
            if($form->sub_account_id != $user->currentAccount->id)
            {
                return redirect('polls');
            }

            $themes = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->get();
            $current_theme = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;


            return view('polls.createpoll')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $mainUser]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function addQuestion(Request $request)
    {
        $data = $request->all();

        $question = new Question();
        $question->title = $data['title'];
        $question->form_id = $data['form_id'];
        $question->options_type = $data['options_type'];
        $question->save();

        return $question;
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if ($question) {
            $question->delete();
            return 'success';
        }
        return 'error';
    }

    public function addOption(Request $request)
    {
        $data = $request->all();

        $option = new Option();
        if (isset($data['title'])) {
            $option->title = $data['title'];
        }
        $option->question_id = $data['question_id'];

        if ($request->hasFile('option_image')) {
            $filePath = public_path() . '/optionimages/';
            if (!\File::isDirectory($filePath)) {
                \File::makeDirectory($filePath);
            }
            $name = time() . $data['question_id'];
            $img = Image::make($data['option_image'])->encode('png', 100);
            $img->save($filePath . $name . '.png');
            $option->image_url = 'optionimages/' . $name . '.png';

            $option->title = 'image';
        }

        $option->save();

        return $option;
    }

    public function deleteOption($id)
    {
        $option = Option::find($id);
        if ($option) {
            $option->delete();
            return 'success';
        }
        return 'error';
    }

    public function editPoll($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }


        $form = Form::find($id);
        if ($form) {
            if($form->sub_account_id != $user->currentAccount->id)
            {
                return redirect('polls');
            }
            $themes = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->get();            
            $current_theme = Theme::find($form->theme_id);
            $rules = $form->displayRules;
            $questions = $form->questions;

            return view('polls.editpoll')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $mainUser]);
        }
        return back()->with(['error', 'Poll not found']);
    }

    public function createPollFromTemplate(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $template = Form::find($data['poll_template']);

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'poll';
        $form->poll_type = $data['poll_type'];
        $form->theme_id = Theme::where('form_type', 'poll')->where('poll_type', $data['poll_type'])->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->page_load = true;
        $form->loading_delay = 3;

        if($template)
        {
            $form->headline = $template->headline;
            $form->description = $template->description;
            $form->success_headline = $template->success_headline;
            $form->success_description = $template->success_description;
            $form->background_color = $template->background_color;
            $form->background_overlay = $template->background_overlay;
            $form->button_text_color = $template->button_text_color;
            $form->button_color = $template->button_color;
            $form->email_label = $template->email_label;
            $form->email_placeholder = $template->email_placeholder;
            $form->button_text = $template->button_text;
            $form->foot_note = $template->foot_note;
            $form->background_image = $template->background_image;
            $form->border_style = $template->border_style;
            $form->button_font_size = $template->button_font_size;
            $form->button_font_family = $template->button_font_family;
            $form->option_font_size = $template->option_font_size;
            $form->option_font_family = $template->option_font_family;
            $form->option_color = $template->option_color;

            $form->desktop_device = 1;
            $form->mobile_device = 1;
            $form->tablet_device = 1;

            $form->save();

            foreach($template->questions as $question)
            {
                $form_question = new Question();
                $form_question->title = $question->title;
                $form_question->form_id = $form->id;
                $form_question->options_type = $question->options_type;
                $form_question->save();

                foreach($question->options as $t_option)
                {
                    $option = new Option();
                    if ($t_option->title) {
                        $option->title = $t_option->title;
                    }
                    $option->question_id = $form_question->id;

                    if ($t_option->image_url) 
                    {
                        $option->image_url = $t_option->image_url;
                        $option->title = 'image';
                    }

                    $option->save();
                }
            }

        }
        else
        {
            $form->headline = 'POLL HEADING GOES HERE';
            $form->description = 'Poll Subheading goes here';
            $form->success_headline = 'Thank you';
            $form->success_description = 'Thanks for taking our poll.';
            $form->background_color = '#ffffff';
            $form->background_overlay = '#808080';
            $form->button_text_color = '#ffffff';
            $form->button_color = '#1678c2';
            $form->email_label = 'Email';
            $form->email_placeholder = 'Enter your email';
            $form->button_text = 'Click to Continue';
            $form->desktop_device = 1;
            $form->mobile_device = 1;
            $form->tablet_device = 1;
            $form->save();
        }
        
        
        return redirect('editpoll/' . $form->id);
    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function pollsAnalysis(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $forms = $user->currentAccount->forms->where('form_type', 'poll')->where('is_template', false);
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
}
