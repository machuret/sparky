<?php

namespace Modules\Client\Controllers\Auth;

use Illuminate\Http\Request;
use Modules\Controller as BaseController;
use Auth;

class LoginController extends BaseController
{
    public function __construct()
    {
      $this->middleware('guest:client', ['except' => ['logout']]);
    }

    public function showLoginForm($tenant)
    {
      return view('modules.client.login',['tenant' => $tenant]);
    }

    public function login(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required|min:6'
      ]);

      // Attempt to log the user in
      if (Auth::guard('client')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        // if successful, then redirect to their intended location
        return redirect()->intended(route('client.dashboard',['tenant' => $request->username]));
      }

      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        return redirect(config('app.url'));
    }
}