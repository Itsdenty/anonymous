<?php

namespace App\Http\Controllers;

use App\Form;
use App\Visitor;
use App\Question;
use App\Option;
use App\SelectedOption;
use App\Subscription;
use App\UnverifiedSubscriber;
use App\Outcome;
use Illuminate\Http\Request;
use App\APIIntegration;
use App\Click;
use Stripe\Stripe;
use App\PushSubscription;
use App\Contact;
use Carbon\Carbon;
use App\Activity;

class ApiController extends Controller
{
    //
    public function getFormData($id)
    {
        $form = Form::find($id);
        if ($form) {
            $user_ipaddress = \Request::ip();
            $visitor = Visitor::where(['ip' => $user_ipaddress, 'form_id' => $form->id])->whereDate('created_at', \Carbon\Carbon::now())->first();
            if ($visitor) {
                $visitor->visit_count += 1;
                $visitor->save();

                if($form->frequency && $form->frequency > 0)
                {
                    $visitor_first_visit = Visitor::where(['ip' => $user_ipaddress, 'form_id' => $form->id])->latest()->first();
                    if($visitor_first_visit)
                    {
                        $num_of_days = Carbon::parse($visitor_first_visit->created_at)->diffInDays(Carbon::now());

                        if($num_of_days % $form->frequency != 0)
                        {
                            return response()->json(['error' => 'Popup Frequency rule'], 404);
                        }
                        if($form->first_visit && $visitor->visit_count > 1)
                        {
                            return response()->json(['error' => 'First Visit rule'], 404);
                        }
                    }
                }
            } else {
                $visitor = new Visitor();
                $visitor->ip = $user_ipaddress;
                $visitor->form_id = $form->id;
                $visitor->save();
            }

            $questions = $form->questions;
            if (!$questions->isempty()) {
                $questions->map(function ($question) {
                    $question['options'] = $question->options;
                });
            }

            $enable_gdpr = $form->subaccount->user->gdpr;

            return response()->json(['form' => $form->toJson(), 'theme' => $form->theme->content, 'rules' => $form->displayRules->toJson(), 'questions' => $questions->toJson(), 'results' => $form->results->toJson(), 'enable_gdpr' => $enable_gdpr]);

        }
        return response()->json(['error' => 'Form not found'], 404);
    }

    public function subscribe(Request $request)
    {
        $data = $request->all();

        $form = Form::find($data['form_id']);
        if ($form) 
        {
            if ($form->subaccount->user->double_optin) 
            {
                do {
                    $token = str_random();
                } while (UnverifiedSubscriber::where('token', $token)->first());

                $subscription = new UnverifiedSubscriber();
                $subscription->email = $data['email'];
                $subscription->form_id = $data['form_id'];
                $subscription->phone = $data['phone'];
                $subscription->token = $token;
                $subscription->save();

                if ($subscription) {
                    $data['form_name'] = $form->title;
                    $data['subscriber_email'] = $data['email'];
                    $data['token'] = $subscription->token;
                    \Mail::send('emails.subscribe', $data, function ($mail) use ($data) {
                        $mail->to($data['email']);
                        $mail->from('help@optinjoyapp.com');
                        $mail->subject('Confirm Your Subscription');
                    });

                    return response()->json(['message' => 'Subscription Successful, awaiting verification'], 200);
                }
                return $subscription;
            }
            else 
            {
                $subscription = new Subscription();
                $subscription->email = $data['email'];
                $subscription->form_id = $data['form_id'];
                $subscription->save();

                $vdata = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));

                $contact = new Contact();
                $contact->email = $data['email'];
                $contact->phone = $data['phone'];
                $contact->sub_date = Carbon::now();
                $contact->sub_account_id = $form->sub_account_id;
                $contact->country_name = $vdata['geoplugin_countryName'];
                $contact->form_id = $form->id;
                $contact->save();
                Activity::create(['content' => 'Added to Contact List from '. $form->title .' ('. $form->form_type .')', 'category' => 'added', 'contact_id' => $contact->id]);
            }

            return $subscription;
        }

        return response()->json(['error' => 'Error in Subscribing'], 404);
    }

    public function storeOptions(Request $request)
    {
        $data = $request->all();

        $selected_options = [];

        $form;

        foreach ($data as $key => $value) {
            $option = Option::find($value);
            if ($option) {
                $option->count += 1;
                $option->save();
                array_push($selected_options, $value);
            }
        }

        foreach ($data as $key => $value) {
            $question = Question::find($key);
            if ($question) {
                $form = $question->form;
                if ($form) {
                    break;
                }
            }
        }

        $questions = $form->questions;
        if (!$questions->isempty()) {
            $questions->map(function ($question) {
                $question['options'] = $question->options;
                $question['total'] = $question->options->sum('count');
            });
        }
        sort($selected_options);
        $stringOptions = implode(',', $selected_options);

        if ($stringOptions != '') {
            $options = SelectedOption::where('options_selected', $stringOptions)->first();
            if ($options) {
                $options->count += 1;
                $options->save();
            } else {
                $options = new SelectedOption();
                $options->options_selected = $stringOptions;
                $options->count = 1;
                $options->form_id = $form->id;
                $options->save();
            }

            $total_votes = SelectedOption::where('form_id', $form->id)->sum('count');

            return response()->json(['result' => $questions->toJson(), 'supportedVotes' => $options->count, 'totalVotes' => $total_votes]);
        }

        return response()->json(['result' => $questions->toJson(), 'supportedVotes' => 0, 'totalVotes' => 0]);
    }

    public function getOutcome(Request $request)
    {
        $data = $request->all();

        $selected_options = [];
        $outcomes = [];

        foreach ($data as $key => $value) {
            $option = Option::find($value);
            if ($option) {
                $option->count += 1;
                $option->save();
                array_push($selected_options, $value);

                if ($option->outcome_id) {
                    array_push($outcomes, $option->outcome_id);
                }
            }
        }

        if (empty($outcomes)) {
            return 'No OutCome';
        }

        $values = array_count_values($outcomes);
        arsort($values);

        $first_value = reset($values);
        $main_outcome_id = key($values);

        $outcome = Outcome::find($main_outcome_id);

        return response()->json(['outcome' => $outcome]);
    }

    public function clicks(Request $request)
    {
        $data = $request->all();
        // return $data;
        $form = Form::find($data['form_id']);
        if ($form) {
            // return $form;
            $click = new Click();
            $click->form_id = $data['form_id'];
            $click->redirect_url = $data['redirect_url'];
            $click->save();

            return $click;
        }

        return response()->json(['error' => 'Error in clicking'], 404);
    }
}
