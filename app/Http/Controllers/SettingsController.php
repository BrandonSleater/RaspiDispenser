<?php namespace App\Http\Controllers;

use Auth, Datatable, DB;

class SettingsController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Settings Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles application settings.
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
	 * Show the settings page.
	 *
	 * @return Response
	 */
	public function page()
	{
		$sound = $this->details();

		return view('settings')->with($sound);
	}

	/**
	 * Get details on the enabled sound file.
	 *
	 * @return array
	 */
	public function details()
	{
		$name = $path = '';

		// Determine if we have a set file
		$results = DB::select('select name, path from file where user = ? and enable = 1', [Auth::id()]);

		if (!empty($results))
		{
			$name  = $results[0]->name;
			$path  = $results[0]->path;
		}
		
		$table = $this->getFileTable();

		return ['sound_name' => $name, 'sound_path' => $path, 'sound_table' => $table];
	}

	/**
	 * Build the html datatable.
	 *
	 * @return Chumper\Datatable\Datatable
	 */
	public function getFileTable()
	{
		return Datatable::table()
	    ->addColumn('Filename', 'Enable')
	    ->setUrl(url('file/table'))
	    ->setOptions(['info' => false, 'pagingType' => 'simple', 'lengthChange' => false])
	    ->render();
	}

}
