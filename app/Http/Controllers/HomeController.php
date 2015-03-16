<?php namespace App\Http\Controllers;

use Datatable;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the application's dashboard.
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
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function page()
	{
		$table = $this->getScheduleTable();

		return view('home')->with($table);
	}

	/**
	 * Build the schedule html datatable.
	 *
	 * @return Chumper\Datatable\Datatable
	 */
	public function getScheduleTable()
	{
		$table = Datatable::table()
	    ->addColumn('Event', 'Time', 'Enable')
	    ->setUrl(url('schedule/table'))
	    ->setOptions(['info' => false, 'pagingType' => 'simple', 'lengthChange' => false])
	    ->render();

		return ['schedule_table' => $table];
	}

}
