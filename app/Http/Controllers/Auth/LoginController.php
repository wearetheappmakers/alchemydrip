<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\SuAdmin;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $check = SuAdmin::where('email',$request->email)->first();
        if(isset($check) && $check->status == 1)
        {
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->sendLoginResponse($request);
            }
        }else{
            return $this->sendFailedLoginResponseStatus($request);
        }
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('login');
    }

    protected function sendFailedLoginResponseStatus(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['User is Inactive. Please contact admin.'],
        ]);
    }
}
