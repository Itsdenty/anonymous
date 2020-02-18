<?php

namespace App\Http\Controllers;

use App\User;
use App\Form;
use App\Theme;
use App\Visitor;
use App\Subscription;
use App\Campaign;
use App\SmsCampaign;
use App\Contact;
use App\Template;
use ZipArchive;
use Image;
// use App\Reseller;
// use App\Subplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        $users_count = User::count();
        $forms_count = Form::where("is_template", false)->count();
        $visitors_count = Visitor::all()->sum('visit_count');
        $subscribers_count = Subscription::count();
        $campaigns_count = Campaign::count();
        $sms_campaign_count = SmsCampaign::count();
        $contacts_count = Contact::count();
        $unsubscribers_count = Contact::where('unsubscribed', true)->count();

        return view('admin.dashboard')->with(['users_count' => $users_count, 'forms_count' => $forms_count, 'visitors_count' => $visitors_count, 'subscribers_count' => $subscribers_count, 'campaigns_count' => $campaigns_count, 'sms_campaign_count' => $sms_campaign_count, 'contacts_count' => $contacts_count, 'unsubscribers_count' => $unsubscribers_count]);
    }

    public function customers()
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        $users = User::all();

        return view('admin.customers')->with('users', $users);
    }

    public function deleteUser($id)
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $user = User::find($id);
        if($user->role_id != 1)
        {
            $user->delete();
            return back()->with('status', 'User Deleted Successfully');
        }
        return back()->with('error', 'Cannot Delete Admin User');
    }

    public function editUser($id)
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $user = User::find($id);
        // if($user->role_id != 1)
        // {
            $subplan = $user->subplan;
            return view('admin.editcustomer')->with(['user' => $user, 'subplan' => $subplan]);
        // }
        // return back()->with('error', 'Cannot Edit Admin User');
    }

    public function registerForm()
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        return view('admin.register');
    }

    public function registerUser(Request $request)
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();
        $user = new User();
        if($request->validate([
            'email' => 'required|string|email|max:50|unique:users',
        ]))
        {
            $user->fill($data);        
            $code = str_random(12);
            $user->password = bcrypt($code);
            $user->save();

            // Subplan::create(['user_id'=>$user->id, 'fe'=>(int)$data['fe'], 'oto1'=>(int)$data['oto1'], 'oto2'=>(int)$data['oto2'], 'oto3'=> (int)$data['oto3'], 'oto4'=> (int)$data['oto4']]);


            // \Mail::send('emails.userwelcome', ['fullname'=> $user->name, 'email'=>$user->email, 'password'=>$code],function($m) use($user){$m->to($user->email)->subject("Welcome to Sendmunk! - Login details"); });

            return back()->with('status', 'User Registered Successfully');
        }

    }

    public function updateUser(Request $request)
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();
        $user = User::find($data['user_id']);
        if($user)
        {
            // $user->firstname = $data['firstname'];
            // $user->lastname = $data['lastname'];
            $user->name = $data['name'];
            $user->save();

            // Subplan::where('user_id',$user->id)->update(['user_id'=>$user->id, 'fe'=>(int)$data['fe'], 'oto1'=>(int)$data['oto1'], 'oto2'=>(int)$data['oto2'], 'oto3'=> (int)$data['oto3'], 'oto4'=> (int)$data['oto4']]);

            return back()->with('status', 'User Updated Successfully');
        }
    }

    public function pollTemplates()
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $polls = Form::where("is_template", true)->where("form_type", "poll")->get();
        
        return view('admin.polltemplate')->with(['polls' => $polls]);
    }

    public function createPollTemplate(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        $data = $request->all();

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'poll';
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
        $form->theme_id = Theme::where('form_type', 'poll')->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->is_template = true;
        $form->save();

        return redirect('admin/createpolltemplate/' . $form->id);
    }

    public function viewPollTemplate($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $form = Form::find($id);
        if ($form) {
            // $themes = Theme::where('form_type', $form->form_type)->first();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;

            return view('admin.createpolltemplate')->with(['user' => $user, 'form' => $form, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $user]);
        }
        return back()->with(['error' => 'Form not found']);
    }


    public function updateTemplate(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id != 1)
        {
            return redirect('dashboard');
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

            $form->sub_account_id = $user->currentAccount->id;
            $form->save();
            if($form->form_type == "poll")
            {
                return redirect("admin/polltemplates");
            }

            if($form->form_type == "quiz")
            {
                return redirect("admin/quiztemplates");
            }

            if($form->form_type == "calculator")
            {
                return redirect("admin/calculatortemplates");
            }
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function editPollTemplate($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $form = Form::find($id);
        if ($form) {
            // $themes = Theme::where('form_type', $form->form_type)->first();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;

            return view('admin.editpolltemplate')->with(['user' => $user, 'form' => $form, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $user]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function quizTemplates()
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $quizzes = Form::where("is_template", true)->where("form_type", "quiz")->get();
        
        return view('admin.quiztemplate')->with(['quizzes' => $quizzes]);
    }

    public function createQuizTemplate(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        $data = $request->all();

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'quiz';
        $form->headline = 'QUIZ HEADING GOES HERE';
        $form->description = 'Quiz Subheading goes here';
        $form->success_headline = 'Thank you';
        $form->success_description = 'Thanks for taking our Quiz.';
        $form->background_color = '#ffffff';
        $form->background_overlay = '#808080';
        $form->button_text_color = '#ffffff';
        $form->button_color = '#1678c2';
        $form->email_label = 'Email';
        $form->email_placeholder = 'Enter your email';
        $form->button_text = 'Click to Continue';
        $form->theme_id = Theme::where('form_type', 'quiz')->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->is_template = true;
        $form->save();

        return redirect('admin/createquiztemplate/' . $form->id);
    }

    public function viewQuizTemplate($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $form = Form::find($id);
        if ($form) {
            // $themes = Theme::where('form_type', $form->form_type)->first();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;
            $outcomes = $form->outcomes;

            return view('admin.createquiztemplate')->with(['user' => $user, 'form' => $form, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $user, 'outcomes' => $outcomes]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function editQuizTemplate($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $form = Form::find($id);
        if ($form) {
            // $themes = Theme::where('form_type', $form->form_type)->first();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;
            $outcomes = $form->outcomes;

            return view('admin.editquiztemplate')->with(['user' => $user, 'form' => $form, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $user, 'outcomes' => $outcomes]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function calculatorTemplates()
    {
        $admin_user = Auth::user();
        if($admin_user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $calculators = Form::where("is_template", true)->where("form_type", "calculator")->get();
        
        return view('admin.calculatortemplate')->with(['calculators' => $calculators]);
    }

    public function createCalculatorTemplate(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        $data = $request->all();

        $form = new Form();
        $form->title = $data['title'];
        $form->form_type = 'calculator';
        $form->headline = 'CALCULATOR HEADING GOES HERE';
        $form->description = 'Calculator Subheading goes here';
        $form->background_color = '#ffffff';
        $form->background_overlay = '#808080';
        $form->button_text_color = '#ffffff';
        $form->button_color = '#1678c2';
        $form->email_label = 'Email';
        $form->email_placeholder = 'Enter your email';
        $form->button_text = 'Click to Continue';
        $form->theme_id = Theme::where('form_type', 'calculator')->first()->id;
        $form->sub_account_id = $user->currentAccount->id;
        $form->is_template = true;
        $form->save();

        return redirect('admin/createcalculatortemplate/' . $form->id);
    }

    public function viewCalculatorTemplate($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $form = Form::find($id);
        if ($form) {
            // $themes = Theme::where('form_type', $form->form_type)->first();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;
            $results = $form->results;

            return view('admin.createcalculatortemplate')->with(['user' => $user, 'form' => $form, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $user, 'results' => $results]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function editCalculatorTemplate($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $form = Form::find($id);
        if ($form) {
            // $themes = Theme::where('form_type', $form->form_type)->first();

            $current_theme = Theme::where('form_type', $form->form_type)->first();
            if ($form->theme_id) {
                $current_theme = Theme::find($form->theme_id);
            }

            $rules = $form->displayRules;
            $questions = $form->questions;
            $results = $form->results;

            return view('admin.editcalculatortemplate')->with(['user' => $user, 'form' => $form, 'current_theme' => $current_theme, 'rules' => $rules, 'questions' => $questions, 'main_user' => $user, 'results' => $results]);
        }
        return back()->with(['error' => 'Form not found']);
    }

    public function themes()
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $themes = Theme::all()->sortByDesc('id');

        return view('admin.themes')->with(["themes" => $themes]);
    }

    public function createTheme(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();

        $theme = new Theme();
        $theme->name = $data['name'];
        $theme->form_type = $data['form_type'];

        if($data['form_type'] == 'popover' || $data['form_type'] == 'embedded' || $data['form_type'] == 'topbar' || $data['form_type'] == 'scrollbox' || $data['form_type'] == 'welcome_mat')
        {
            $theme->content = Theme::where('form_type', $data['form_type'])->first()->content;
        }
        else
        {
            if($data['poll_type'])
            {
                $theme->poll_type = $data['poll_type'];
                $theme->content = Theme::where('form_type', $data['form_type'])->where('poll_type', $data['poll_type'])->first()->content;
            }
            else
            {
                return back()->with('error', 'Type not Selected');
            }
        }

        $theme->save();

        return redirect('admin/createtheme/' . $theme->id);
    }

    public function viewTheme($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        
        $theme =  Theme::find($id);
        if($theme)
        {
            return view('admin.createtheme')->with(['theme' => $theme]);
        }

        return "Theme not found";
    }

    public function updateTheme(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();

        $theme = Theme::find($data['theme_id']);
        if($theme)
        {
            $theme->fill($data);
            $theme->content = $data['content'];
            $theme->save();

            return "successful";
        }
        
        return "An error occurred";
    }

    public function editTheme($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        
        $theme =  Theme::find($id);
        if($theme)
        {
            return view('admin.edittheme')->with(['theme' => $theme]);
        }

        return "Theme not found";
    }

    public function deleteTheme($id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }
        
        $theme =  Theme::find($id);
        if($theme)
        {
            if($theme->forms->count() > 0)
            {
                return back()->with('error', "You can't delete this theme, It already in use");
            }
            $theme->delete();
            return back()->with('status', "Theme Deleted Successfully");
        }
        return back()->with('error', "Theme not found");
    }

    // public function randomVoucher($length)
    // {    
    //     do
    //     {
    //         $randomCode = str_random($length);
    //         $reseller = Reseller::where('voucher_code', $randomCode)->first();
    //     }
    //     while(!empty($reseller));        
    //     return $randomCode; 
    // }

    // public function generate(Request $request)
    // {
    //     $admin_user = Auth::user();
    //     if($admin_user->role_id != 1)
    //     {
    //         return redirect('dashboard');
    //     }
        
    //     $data = $request->all();
    //     $uid = $data['user_id'];
    //     $number = $data['number'];
    //     $arr = [];  
    //     for ($i=0; $i < $number; $i++) 
    //     { 
    //         array_push($arr, $this->randomVoucher(10));
    //     }

    //     foreach ($arr as $a) 
    //     {
    //         Reseller::create(['voucher_code' => $a,'reseller_id' => $uid]);
    //     }
    //     return back()->with('status', "License(s) Generated Sucessfully");
    // }

    // public function viewGenerator()
    // {
    //     $admin_user = Auth::user();
    //     if($admin_user->role_id != 1)
    //     {
    //         return redirect('dashboard');
    //     }
    //     return view('admin.generator');
    // }
    public function viewWorkflows()
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $workflows = Workflow::where('is_workflow', true)->get();

        return view('admin.workflows.workflows')->with(['user' => $user, 'workflows' => $workflows]);
    }

    public function createWorkflow(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();

        $workflow = new Workflow();
        $workflow->name = $data['name'];
        $workflow->sub_account_id = $user->currentAccount->id;
        $workflow->is_workflow = true;
        $workflow->save();

        return redirect('admin/view/visual_automation/'. $workflow->id);
    }
    public function viewTemplates()
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $templates = Template::where('is_template', true)->get();

        return view('admin.templates.templates')->with(['user' => $user, 'templates' => $templates]);
    }

    public function createTemplate(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();

        $template = new Template();
        $template->name = $data['name'];
        $template->sub_account_id = $user->currentAccount->id;
        $template->is_template = true;
        $template->save();

        return redirect('admin/view/template/'. $template->id);
    }

    public function viewTemplateEditor($template_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $template = Template::find($template_id);
        if($template)
        {
            return view('admin.templates.dragdrop.index')->with(['user' => $user, 'template' => $template]);
        }
        return back()->with('error', "Template not found");
    }

    public function saveWorkflow(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();

        $workflow = Workflow::find($data['workflow_id']);
        if($workflow)
        {
            $screenshot_url = $this->screenshotAlias($response['preview_url']);

            $workflow->editable_content = $data['editable_content'];
            $workflow->preview_content = $data['preview_content'];
            $workflow->screenshot = $screenshot_url;
            $workflow->save();

            return;
        }
        if($request->ajax())
        {
            return response()->json(['error' => 'Automation Workflow not found'], 404);
        }
        return back()->with('error', "Automation Workflow not found");
    }

    public function saveTemplate(Request $request)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $data = $request->all();

        $template = Template::find($data['template_id']);
        if($template)
        {
            $response = $this->createHtmlandZipFile($data['preview_content']);
            $screenshot_url = $this->screenshotAlias($response['preview_url']);

            $template->editable_content = $data['editable_content'];
            $template->preview_content = $data['preview_content'];
            $template->screenshot = $screenshot_url;
            $template->save();

            return;
        }
        if($request->ajax())
        {
            return response()->json(['error' => 'Template not found'], 404);
        }
        return back()->with('error', "Template not found");
    }

    private function createHtmlandZipFile($html='')
    {
        $filePath = public_path() . '/exports/';
        if (!\File::isDirectory($filePath))
        {
            \File::makeDirectory($filePath);
        }

        $todayh = getdate();
        $filename= "email-editor-".$todayh['seconds'].$todayh['minutes'].$todayh['hours'].$todayh['mday']. $todayh['mon'].$todayh['year'];

        $newHtmlFilename = $filePath.$filename.'.html';
        $zipFilename = $filePath.$filename.'.zip';
        $zipFileUrl = url('exports').'/'.$filename.'.zip';
        $htmlFileUrl = url('exports').'/'.$filename.'.html';

        //read email template
        $templateContent=file_get_contents("template.html",true);

        //create new document
        $new_content =$html;

        //view in browser link
        $new_content=str_replace('#view_web',$htmlFileUrl,$new_content);


        $content=str_replace('[email-body]',$new_content,$templateContent);
        $fp = fopen($newHtmlFilename,"wb");
        fwrite($fp,$content);
        fclose($fp);

        //create zip document
        $zip = new ZipArchive();

        $zip->open($zipFilename, ZipArchive::CREATE);
        $zip->addFile($newHtmlFilename, 'index.html');
        $zip->close();
        //remove html file
        //unlink($newHtmlFilename);

        $response=array();
        $response['code']=0;
        $response['url']=$zipFileUrl;
        $response['preview_url']=$htmlFileUrl;
        $response['html']=$new_content;

        return $response;
    }

    public function screenshotAlias($url){
        $params = http_build_query(array(
            "url" => $url,
            "access_key" => $this->access_key
        ));

        try
        {
            $image_data = file_get_contents("https://api.apiflash.com/v1/urltoimage?" . $params);


            $filePath = public_path() . '/screenshot/';
            if (!\File::isDirectory($filePath))
            { 
                \File::makeDirectory($filePath);
            }

            $name = Str::random(32) . time();
            file_put_contents(public_path().'/screenshot/'.$name.'.jpeg', $image_data);

            return  'screenshot/'.$name.'.jpeg';
        }
        catch(\Exception $e)
        {
            \Log::info($e->getMessage());
            return null;
        }
    }

    public function deleteTemplate($template_id)
    {
        $user = Auth::user();
        if($user->role_id != 1)
        {
            return redirect('dashboard');
        }

        $template = Template::find($template_id);
        if($template)
        {
            $template->delete();
            return back()->with('status', "Template deleted successfully");
        }
        return back()->with('error', "Template not found");
    }

    public function loadTemplate(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $user_templates = $user->currentAccount->templates->where('is_template', false)->where('id', '!=', $data["templateId"]);
        $custom_templates = Template::where('is_template', true)->get();

        return response()->json(['user_templates' => $user_templates->toJson(), 'custom_templates' => $custom_templates->toJson()]);
    }

    public function getTemplateContent(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $template = Template::find($data["templateId"]);
        if($template)
        {
            if($template->sub_account_id == $user->currentAccount->id || $template->is_template)
            {
                if($request->ajax())
                {
                    return response()->json(['template' => $template->toJson()]);
                }
                return $template;
            }
            if($request->ajax())
            {
                return response()->json(['error' => 'Unauthorized Access'], 404);
            }
            return back()->with('error', "Unauthorized Access");
        }
        if($request->ajax())
        {
            return response()->json(['error' => 'Template not found'], 404);
        }
        return back()->with('error', "Template not found");
    }
}
