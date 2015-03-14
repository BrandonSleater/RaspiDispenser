<?php namespace App\Http\Controllers;

use Auth, Datatable, DB;

class SettingsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Startup Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "start page" for the application and
	| is configured to only allow guests
	|
	*/

	/*
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/*
	 * Show the application start screen to the user.
	 *
	 * @return Response
	 */
	public function home()
	{
		$sound = $this->getSound();

		return view('settings')->with($sound);
	}

	/*
	 *
	 */
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

	/*
	 *
	 */
	public function getSoundTable()
	{
		return Datatable::table()
	    ->addColumn('Filename', 'Enable')
	    ->setUrl(url('file/sound'))
	    ->setOptions(['info' => false, 'pagingType' => 'simple', 'lengthChange' => false])
	    ->render();
	}

}
