<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Auth;
use App\Template;
use ZipArchive;
use Image;

class TemplateController extends Controller
{
    //
    private $access_key = '943d88918b5f4b15b174f7f67d4b4822'; 

    public function viewTemplates()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $templates = $user->currentAccount->templates->where('is_template', false);

        return view('templates.templates')->with(['user' => $user, 'main_user' => $mainUser, 'templates' => $templates]);
    }

    public function createTemplate(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $template = new Template();
        $template->name = $data['name'];
        $template->sub_account_id = $user->currentAccount->id;
        $template->save();

        return redirect('view/template/'. $template->id);
    }

    public function viewTemplateEditor($template_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $template = Template::find($template_id);
        if($template)
        {
            if($template->sub_account_id == $user->currentAccount->id)
            {
                return view('templates.dragdrop.index')->with(['user' => $user, 'main_user' => $mainUser, 'template' => $template]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Template not found");
    }

    public function saveTemplate(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();

        $template = Template::find($data['template_id']);
        if($template)
        {
            if($template->sub_account_id == $user->currentAccount->id)
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
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $template = Template::find($template_id);
        if($template)
        {
            if($template->sub_account_id == $user->currentAccount->id)
            {
                $template->delete();
                return back()->with('status', "Template deleted successfully");
            }
            return back()->with('error', "Unauthorized Access");
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
