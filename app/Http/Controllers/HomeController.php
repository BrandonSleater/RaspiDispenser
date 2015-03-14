<?php namespace App\Http\Controllers;

use Auth, Datatable, DB;

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
		$sound = $this->getSound();

		return view('home')->with($sound);
	}


	public function getSound()
	{
		$name = $path = $table = '';

		$results = DB::select('select name, path from file where user = ? and enable = 1', [Auth::id()]);

		if (!empty($results))
		{
			$name  = $results[0]->name;
			$path  = $results[0]->path;
		}
		
		$table = $this->getSoundTable();

		return ['sound_name' => $name, 'sound_path' => $path, 'sound_table' => $table];
	}



	public function getSoundTable()
	{
		return Datatable::table()
	    ->addColumn('Filename', 'Enable')
	    ->setUrl(url('file/sound'))
	    ->setOptions(['info' => false, 'pagingType' => 'simple', 'lengthChange' => false])
	    ->render();
	}
}
