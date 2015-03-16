<?php namespace App\Http\Controllers;

class AppController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Root Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the login page for the application.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application start screen to the user.
	 *
	 * @return Response
	 */
	public function page()
	{
		return view('auth.login');
	}

}
