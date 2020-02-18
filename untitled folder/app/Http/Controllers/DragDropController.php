<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Campaign;

use ZipArchive;

class DragDropController extends Controller
{
    //
    public function index($campaign_id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $campaign = Campaign::find($campaign_id);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                return view('campaigns.dragdrop.index')->with(['user' => $user, 'main_user' => $mainUser, 'campaign' => $campaign]);
            }
            return back()->with('error', "Unauthorized Access");
        }
        return back()->with('error', "Campaign not found");
    }

    public function templateLoadPage()
    {
        return view('campaigns.dragdrop.template-load-page');
    }

    public function templateBlankPage()
    {
        return view('campaigns.dragdrop.template-blank-page');
    }

    public function elementPageHeader()
    {
        return view('campaigns.dragdrop.elements.page-header');
    }

    public function elementHeading()
    {
        return view('campaigns.dragdrop.elements.heading');
    }

    public function elementParagraph()
    {
        return view('campaigns.dragdrop.elements.paragraph');
    }

    public function elementImageLeft()
    {
        return view('campaigns.dragdrop.elements.image-left-text');
    }

    public function elementImageRight()
    {
        return view('campaigns.dragdrop.elements.image-right-text');
    }

    public function elementTwoColumnText()
    {
        return view('campaigns.dragdrop.elements.2-column-text');
    }

    public function elementUnorderedList()
    {
        return view('campaigns.dragdrop.elements.unordered-list');
    }

    public function elementOrderedList()
    {
        return view('campaigns.dragdrop.elements.ordered-list');
    }

    public function elementJumbotron()
    {
        return view('campaigns.dragdrop.elements.jumbotron');
    }

    public function elementFeatures()
    {
        return view('campaigns.dragdrop.elements.features');
    }

    public function elementServiceList()
    {
        return view('campaigns.dragdrop.elements.service-list');
    }

    public function elementPriceTable()
    {
        return view('campaigns.dragdrop.elements.price-table');
    }

    public function elementImageFull()
    {
        return view('campaigns.dragdrop.elements.image-full');
    }

    public function elementImageTwoColumn()
    {
        return view('campaigns.dragdrop.elements.image-2-column');
    }

    public function elementVideo()
    {
        return view('campaigns.dragdrop.elements.video');
    }

    public function elementDivider()
    {
        return view('campaigns.dragdrop.elements.divider');
    }

    public function elementDividerDotted()
    {
        return view('campaigns.dragdrop.elements.divider-dotted');
    }

    public function elementDividerDashed()
    {
        return view('campaigns.dragdrop.elements.divider-dashed');
    }

    public function elementViewBrowser()
    {
        return view('campaigns.dragdrop.elements.view-browser');
    }

    public function elementButton1()
    {
        return view('campaigns.dragdrop.elements.button-1');
    }

    public function elementButton2()
    {
        return view('campaigns.dragdrop.elements.button-2');
    }

    public function elementButton3()
    {
        return view('campaigns.dragdrop.elements.button-3');
    }

    public function elementSocial1()
    {
        return view('campaigns.dragdrop.elements.social-1');
    }

    public function elementSocial2()
    {
        return view('campaigns.dragdrop.elements.social-2');
    }

    public function elementSocial3()
    {
        return view('campaigns.dragdrop.elements.social-3');
    }

    public function elementAddress()
    {
        return view('campaigns.dragdrop.elements.address');
    }

    public function elementAddressLogo()
    {
        return view('campaigns.dragdrop.elements.address-logo');
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

    public function exportHtml(Request $request)
    {
        $data = $request->all();

        $response = $this->createHtmlandZipFile($data['html']);

        return $response;
    }

    public function saveTemplate(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        $campaign = Campaign::find($data['campaign_id']);
        if($campaign)
        {
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $campaign->content = $data["content"];
                $campaign->editable_content = $data["editable_content"];
                $campaign->save();

                return 'ok';
            }
            if($request->ajax())
            {
                return response()->json(['error' => 'Unauthorized Access'], 404);
            }
            return back()->with('error', "Unauthorized Access");
        }

        if($request->ajax())
        {
            return response()->json(['error' => 'Campaign not found'], 404);
        }
        return back()->with('error', "Campaign not found");
    }

    public function saveUserTemplate(Request $request)
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
            if($campaign->sub_account_id == $user->currentAccount->id)
            {
                $template->content = $data["content"];
                $template->save();

                return 'ok';
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
