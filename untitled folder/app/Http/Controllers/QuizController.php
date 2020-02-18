<?php

namespace App\Http\Controllers;

use App\Form;
use App\Theme;
use App\Outcome;
use App\Question;
use App\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Carbon\Carbon;
use App\Visitor;
use App\Subscription;

class QuizController extends Controller
{
    //
    public function userQuizzes()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $quizzes = $user->currentAccount->forms->where('form_type', 'quiz')->where('is_template', false);

        $form_array = [];
        foreach($quizzes as $form){
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

        $forms_count = $quizzes->count();
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

        $templates = Form::where('form_type', 'quiz')->where('is_template', true)->get();

        return view('quizzes.quizzes')->with(['user' => $user, 'quizzes' => $quizzes, 'main_user' => $mainUser, 'templates' => $templates, 'labels' => $labels, 'subscriptions' => $subs, 'visits' => $vis, 'forms_count' => $forms_count, 'subs_count' => $subs_count, 'vis_count' => $vis_count, 'conversion_rate' => round($conversion_rate, 2)]);
    }

    public function createQuiz(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'quiz';
        $form->poll_type = $data['quiz_type'];
        $form->headline = 'QUIZ HEADING GOES HERE';
        $form->description = 'Quiz Subheading goes here';
        $form->success_headline = 'Thank you';
        $form->success_description = 'Thank you for subscribing with us';
        $form->background_color = '#ffffff';
        $form->background_overlay = '#808080';
        $form->button_text_color = '#ffffff';
        $form->button_color = '#1678c2';
        $form->email_label = 'Email';
        $form->email_placeholder = 'Enter your email';
        $form->button_text = 'Click to Continue';
        $form->theme_id = Theme::where('form_type', 'quiz')->where('poll_type', $data['quiz_type'])->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->save();

        return redirect('createquiz/' . $form->id);
    }

    public function viewQuiz($id)
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
                return redirect('quizzes');
            }

            $themes = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->get();
            if(!$themes)
            {
                return view('errors.default');;
            }

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;
            $outcomes = $form->outcomes;

            return view('quizzes.createquiz')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'outcomes' => $outcomes, 'main_user' => $mainUser]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function addOutcome(Request $request)
    {
        $data = $request->all();

        $outcome = new Outcome();
        if (isset($data['title'])) {
            if (!empty($data['title'])) {
                $dom = new \domdocument();
                $dom->loadHtml($data['title'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                $detail = $dom->savehtml();
                $outcome->title = $detail;
            }
        }
        if (isset($data['description'])) {
            if (!empty($data['description'])) {
                $dom = new \domdocument();
                $dom->loadHtml($data['description'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                $detail = $dom->savehtml();
                $outcome->description = $detail;
            }
        }

        $outcome->type = $data['outcome_type'];
        $outcome->form_id = $data['form_id'];
        $outcome->redirect_url = $data['redirect_url'];
        $outcome->save();

        return $outcome;
    }

    public function deleteOutcome($id)
    {
        $outcome = Outcome::find($id);
        if ($outcome) {
            $outcome->delete();
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
        $option->next_question_id = $data['next_question_id'];
        $option->outcome_id = $data['outcome_id'];

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

        $next_question = $option->next_question_id ? \App\Question::find($option->next_question_id)->title : 'Next Question on the List';
        $outcome = $option->outcome_id ? \App\Outcome::find($option->outcome_id)->title : '';

        return response()->json(['option' => $option, 'next_question' => $next_question, 'outcome' => $outcome]);
    }

    public function getQuestionsAndOutcomes($id)
    {
        $form = Form::find($id);
        if ($form) {
            $questions = $form->questions;
            $outcomes = $form->outcomes;

            return response()->json(['questions' => $questions, 'outcomes' => $outcomes]);
        }

        return response()->json(404, 'An Error Occured');
    }

    public function editQuiz($id)
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
                return redirect('quizzes');
            }
            $themes = Theme::where('form_type', $form->form_type)->where('poll_type', $form->poll_type)->get();
                        
            $current_theme = Theme::find($form->theme_id);
            $rules = $form->displayRules;
            $questions = $form->questions;
            $outcomes = $form->outcomes;

            return view('quizzes.editquiz')->with(['user' => $user, 'form' => $form, 'themes' => $themes, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'outcomes' => $outcomes, 'main_user' => $mainUser]);
        }
        return back()->with(['error', 'Quiz not found']);
    }

    public function createQuizFromTemplate(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $template = Form::find($data['quiz_template']);

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'quiz';
        $form->poll_type = $data['quiz_type'];
        $form->theme_id = Theme::where('form_type', 'quiz')->where('poll_type', $data['quiz_type'])->first()->id;
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

            $form_options = [];
            $template_options = [];

            foreach($template->outcomes as $t_outcome)
            {
                $outcome = new Outcome();
                $outcome->title = $t_outcome->title;
                $outcome->description = $t_outcome->description;
                $outcome->type = $t_outcome->type;
                $outcome->form_id = $form->id;
                $outcome->redirect_url = $t_outcome->redirect_url;
                $outcome->save();
            }

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

                    array_push($form_options, $option);
                    array_push($template_options, $t_option);
                }
            }
            
            for($i = 0; $i < count($form_options); $i++)
            {
                if(Question::find($template_options[$i]->next_question_id))
                {
                    $form_options[$i]->next_question_id = $form->questions->where('title', Question::find($template_options[$i]->next_question_id)->title)->first()->id;
                }
                if(Outcome::find($template_options[$i]->outcome_id))
                {
                    $form_options[$i]->outcome_id = $form->outcomes->where('title', Outcome::find($template_options[$i]->outcome_id)->title)->first()->id;
                }
                $form_options[$i]->save();
            }

        }
        else
        {
            $form->headline = 'QUIZ HEADING GOES HERE';
            $form->description = 'Quiz Subheading goes here';
            $form->success_headline = 'Thank you';
            $form->success_description = 'Thanks for taking our quiz.';
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
        
        
        return redirect('editquiz/' . $form->id);
    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];

        for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    public function quizzesAnalysis(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $forms = $user->currentAccount->forms->where('form_type', 'quiz')->where('is_template', false);
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
