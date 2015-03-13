<?php namespace App\Http\Controllers;

use DB;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sound = $this->getSoundFilename();

		return view('home')->with($sound);
	}


	public function getSoundFilename()
	{
		$name = $path = '';

		$results = DB::select('select name, path from file order by id desc limit 1');

		if (!empty($results))
		{
			$name = $results[0]->name;
			$path = $results[0]->path;
		}

		return [
			'sound_name' => $name,
			'sound_path' => $path
		];
	}
}
