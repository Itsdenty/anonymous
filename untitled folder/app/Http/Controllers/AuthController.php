<?php

namespace App\Http\Controllers;

use App\User;
use App\SubAccount;
// use App\Subplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        if(Auth::check())
        {
            return redirect('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->get('email');
        if (Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ])) {
            $user = User::where('email', $email)->first();

            if($user->profile)
            {
                return redirect('dashboard');
            }
            return redirect('settings');
        } else {
            return back()->with('error', 'Login Failed');
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $user = new User();
        if ($request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6',
        ])) {
            $user->fill($data);
            $user->password = bcrypt($user->password);
            $user->save();

            // Create a default sub account for new users
            $subAccount = new SubAccount();
            $subAccount->account_name = "Default";
            $subAccount->user_id = $user->id;
            $subAccount->confirmed = 1;
            $subAccount->save();

            //update current sub account of users
            $user->current_sub_account_id = $subAccount->id;
            $user->save();

            // Subplan::create(['user_id'=>$user->id, 'fe'=>2, 'oto1'=>1]);

            Auth::loginUsingId($user->id);
            if($user->profile)
            {
                return redirect('dashboard');
            }
            return redirect('settings');
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect('login');
    }

    /**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
    public function getRemind()
    {
        return view('auth/passwords/email');
    }

    /**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
    public function postRemind(Request $request)
    {
        $data = $request->all();
        $hasher = \App::make('hash');
        $reminders = new DatabaseTokenRepository(\DB::connection(), $hasher, \Config::get('auth.passwords.users.table'), \Config::get('app.key'));
        $user = \Password::getUser(['email' => $data['email']]);
        if($user != null)
        {
            $token = $reminders->create($user);
            $data = $user->toArray();
            $data['token'] = $token;

            \Mail::send('emails.reminder', $data, function($mail) use($data){
                $mail->to($data['email'], $data['name']);
                $mail->from('help@Sendmunk.com');
                $mail->subject('Your Sendmunk Password');
            });
        }
        return back()->with('status', \Lang::get("If this email exists, we have sent a password reset. Check your email to continue"));
    }

    /**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
    public function getReset($token = null)
    {
        if (is_null($token)) App::abort(404);

        return view('/auth/passwords/reset')->with('token', $token);
    }

    /**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
    public function postReset(Request $request)
    {
        $data = $request->all();
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
            'password_confirmation' => $data['password_confirmation'],
            'token' => $data['token']
        ];

        $response = \Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);

            $user->save();
        });

        switch ($response)
        {
            case \Password::INVALID_PASSWORD:
            case \Password::INVALID_TOKEN:
            case \Password::INVALID_USER:
                return back()->with('error', \Lang::get($response));

            case \Password::PASSWORD_RESET:
                return redirect('login')->with('status', 'Password Changed Successfully, Login to continue');
        }
    }
}
