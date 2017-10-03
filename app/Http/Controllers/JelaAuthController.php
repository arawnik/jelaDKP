<?php
namespace jelaDkp\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Auth;
use Validator;

class JelaAuthController extends Controller {
	
	/**
     * Show the Login screen.
     *
     * @return Response
     */
	public function LoginView() {
		return view('auth.login');
	}
	
	/**
     * Logout and redirect home.
     *
     * @return	Redirect
     */
	public function Logout() {
		Auth::logout();
		return redirect()->route('home');
	}
	
    /**
     * Handle an authentication attempt, set data and redirect.
	 *
	 * @param	Request	$request	The request that user has sent for login.
	 * @param	Request	$route		The route where we redirect on successful login. Defaults to home.
     *
     * @return	Redirect	Redirect to $route on successful login, or back if login fails.
     */
    public function authenticate(Request $request, $route = 'home') {
		//Create validator for the details.
		$validator = Validator::make($request->all(),[
			'name' => 'bail|required',
			'password' => 'required',
		]);
		
		//Gather the info.
		$name = $request->get('name');
		$password = $request->get('password');
		
		//And attempt auth.
		if (Auth::attempt(['name' => $name, 'password' => $password])) {
            // Authentication passed...
            return redirect()->route($route);
        } else {
			// redirect our user back to the form with the errors from the validator
			$validator->errors()->add('login', trans('auth.failed'));
			
			return redirect()->back()->withErrors($validator)->withInput();
		}
    }
}