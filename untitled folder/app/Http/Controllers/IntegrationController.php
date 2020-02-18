<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SmtpType;
use App\Smtp;
use App\ApiChannel;
use App\MailApi;
use Swift_SmtpTransport;
use Swift_Mailer;
use App\Campaign;

class IntegrationController extends Controller
{
    //
    public function viewIntegrations()
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $smpt_types = SmtpType::all();
        $smtps = $user->currentAccount->smtps;
        $mail_apis = $user->currentAccount->mailApis;
        $api_channels = ApiChannel::all();

        return view('settings.integration')->with(['user' => $user, 'main_user' => $mainUser, 'smpt_types' => $smpt_types, 'smtps' => $smtps,'api_channels' => $api_channels, 'mail_apis' => $mail_apis]);
    }

    public function storeSmtp(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        $smtp = new Smtp();

        $response = $this->verifySmtp($data['server'], $data['port'], $data['user'], $data['password'], $data['encryption']);
        if($response['status'] != 200)
        {
            return back()->with('error', $response['message']);
        }

        $smtp->fill($data);
        if($data['is_limited'] == 0)
        {
            $smtp->time_in_seconds = 0;
        }
        else
        {
            if($data['time_unit'] == 'seconds')
            {
                $smtp->time_in_seconds = intval($data['time_value']);
            }
            else if($data['time_unit'] == 'minute')
            {
                $smtp->time_in_seconds = intval($data['time_value']) * 60;
            }
            else if($data['time_unit'] == 'hour')
            {
                $smtp->time_in_seconds = intval($data['time_value']) * 60 * 60;
            }
            else
            {
                $smtp->time_in_seconds = intval($data['time_value']) * 60 * 60 * 24;
            }
        }

        $smtp->sub_account_id = $user->currentAccount->id;
        $smtp->save();

        return back()->with('status', "SMTP Config added successfully");
    }

    public function updateSmtp(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        $data = $request->all();
        
        $smtp = Smtp::find($data['smtp_id']);
        if($smtp)
        {
            if($smtp->sub_account_id == $user->currentAccount->id)
            {
                $response = $this->verifySmtp($data['server'], $data['port'], $data['user'], $data['password'], $data['encryption']);
                if($response['status'] != 200)
                {
                    return back()->with('error', $response['message']);
                }
                
                $smtp->fill($data);
                if($data['is_limited'] == 0)
                {
                    $smtp->time_in_seconds = 0;
                }
                else
                {
                    if($data['time_unit'] == 'seconds')
                    {
                        $smtp->time_in_seconds = intval($data['time_value']);
                    }
                    else if($data['time_unit'] == 'minute')
                    {
                        $smtp->time_in_seconds = intval($data['time_value']) * 60;
                    }
                    else if($data['time_unit'] == 'hour')
                    {
                        $smtp->time_in_seconds = intval($data['time_value']) * 60 * 60;
                    }
                    else
                    {
                        $smtp->time_in_seconds = intval($data['time_value']) * 60 * 60 * 24;
                    }
                }

                $smtp->save();
                return back()->with('status', "SMTP Config Updated");
            }
            return back()->with('error', "Not allowed");
        }
        return back()->with('error', "Config not found");
    }

    public function deleteSmtp($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $smtp = Smtp::find($id);
        if($smtp)
        {
            $campaigns = Campaign::where('smtp_id', $smtp->id)->where('sub_account_id', $user->currentAccount->id)->get();
            if($smtp->sub_account_id == $user->currentAccount->id)
            {
                if($campaigns->isEmpty())
                {
                    $smtp->delete();
                    return back()->with('status', "SMTP Config deleted");
                }
                return back()->with('error', "Sorry, you can't delete config used in a campaign");
            }
            return back()->with('error', "Not allowed");
        }
        return back()->with('error', "Config not found");
    }

    public function storeApi(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $data = $request->all();
        $mail_api = new MailApi();

        if($data['api_channel_id'] == '1') // validate sendgrid api
        {
            $response = $this->verifySendgridApiKey($data['api_key']);
            if($response['status'] != 200)
            {
                return back()->with('error', $response['message']);
            }
        }        
        else if($data['api_channel_id'] == '2') // Validate mailgun api
        {
            $response = $this->verifyMailgunDomain($data['api_key'], $data['domain']);
            if($response['status'] != 200)
            {
                return back()->with('error', $response['message']);
            }
        }

        $mail_api->fill($data);
        $mail_api->sub_account_id = $user->currentAccount->id;
        $mail_api->save();

        return back()->with('status', "Mail API Config added successfully");
    }

    public function updateApi(Request $request)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }
        $data = $request->all();
        
        $mail_api = MailApi::find($data['api_id']);
        if($mail_api)
        {
            if($mail_api->sub_account_id == $user->currentAccount->id)
            {
                if($data['api_channel_id'] == '1') // validate sendgrid api
                {
                    $response = $this->verifySendgridApiKey($data['api_key']);
                    if($response['status'] != 200)
                    {
                        return back()->with('error', $response['message']);
                    }
                }        
                else if($data['api_channel_id'] == '2') // Validate mailgun api
                {
                    $response = $this->verifyMailgunDomain($data['api_key'], $data['domain']);
                    if($response['status'] != 200)
                    {
                        return back()->with('error', $response['message']);
                    }
                }

                $mail_api->fill($data);
                $mail_api->save();
                return back()->with('status', "Mail API Config Updated");
            }
            return back()->with('error', "Not allowed");
        }
        return back()->with('error', "Config not found");
    }

    public function deleteApi($id)
    {
        $user = Auth::user();
        $mainUser = $user;
        if ($user->role_id == 3) {
            $user = $user->userLeader;
        }

        $mail_api = MailApi::find($id);
        if($mail_api)
        {
            $campaigns = Campaign::where('mail_api_id', $mail_api->id)->where('sub_account_id', $user->currentAccount->id)->get();
            if($mail_api->sub_account_id == $user->currentAccount->id)
            {
                if($campaigns->isEmpty())
                {
                    $mail_api->delete();
                    return back()->with('status', "Mail API Config deleted");
                }
                return back()->with('error', "Sorry, you can't delete config used in a campaign");
            }
            return back()->with('error', "Not allowed");
        }
        return back()->with('error', "Config not found");
    }

    public function verifyMailgunDomain($key, $domain)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mailgun.net/v3/domains/". $domain ."/verify",
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => 'api:'. $key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => "",
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);
        $response_code =  curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        $result = [];

        if ($err) {
            $result['status'] = 404;
            $result['message'] = "cURL Error #:" . $err;
        } else {
            if($response_code != 200)
            {
                $result['status'] = $response_code;
                $result['message'] = json_decode($response, true)['message'];
            }
            else
            {
                $result['status'] = 200;
                $result['message'] = "Valid";
            }
        }

        return $result;
    }
    
    public function verifySendgridApiKey($key) 
    {
		try 
		{
			$session = curl_init('https://api.sendgrid.com/v3/templates');
			
			curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
			curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $key));
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($session);
			curl_close($session);

			$ret = json_decode($response, true);

			if (array_key_exists("errors", $ret))
			{
				return array('status' => '404', 'message' => $ret['errors'][0]['message']);
			}

			return array('status' => '200', 'message' => $ret);
		} 
		catch (Exception $e) {
			Log::error($e->getMessage());
		}

    }
    
    public function verifySmtp($server, $port, $username, $password, $encryption)
    {
        try
        {
            if($encryption == 'null')
            {
                $transport = new Swift_SmtpTransport($server, $port);
            }
            else
            {
                $transport = new Swift_SmtpTransport($server, $port, $encryption);
            }

            $transport->setUsername($username);
            $transport->setPassword($password);
            
            $mailer = new Swift_Mailer($transport);
            $mailer->getTransport()->start();
            return array('status' => '200', 'message' => 'Valid');
        }
        catch(\Swift_TransportException $e)
        {
            $pos = strpos($e->getMessage().'.', '.');
            return array('status' => '404', 'message' => substr($e->getMessage().'.', 0, $pos+1));
        }
        catch(Exception $e)
        {
            $pos = strpos($e->getMessage().'.', '.');
            return array('status' => '404', 'message' => substr($e->getMessage().'.', 0, $pos+1));
        }
    }
}
