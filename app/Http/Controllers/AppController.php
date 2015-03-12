<?php namespace App\Http\Controllers;

class AppController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Startup Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "start page" for the application and
	| is configured to only allow guests
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
	public function index()
	{
		return view('auth.login');
	}

}
