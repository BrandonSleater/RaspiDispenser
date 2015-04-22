<?php namespace App\Http\Controllers;

use Datatable, Redirect, Session, SSH;

class HomeController extends Controller {

	private $output;

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
		$table  = $this->getScheduleTable();
		$supply = $this->getCurrentSuply();

		$inputs = array_merge($table, $supply);

		return view('home')->with($inputs);
	}

	/**
	 * Build the schedule html datatable.
	 *
	 * @return Chumper\Datatable\Datatable
	 */
	protected function getScheduleTable()
	{
		$table = Datatable::table()
	    ->addColumn('Event', 'Time', 'Amount', 'Enable', 'Remove')
	    ->setUrl(url('schedule/table'))
	    ->setOptions(['info' => false, 'pagingType' => 'simple', 'lengthChange' => false])
	    ->render();

		return ['table' => $table];
	}

	/**
	 * Get the current food amount. We return back the distance 
	 * to the nearest piece of food and convert it to the amount left.
	 * Yes this is not exactly the most accurate method. (╯°□°）╯︵ ┻━┻
	 *
	 * @param array
	 */
	protected function getCurrentSuply()
	{
		$supply = 0;

		try
		{
			SSH::run([env('RASPI_COMMAND_SUPPLY')], function($line) {
				$this->output = $line.PHP_EOL;
			});
		}
		catch (\ErrorException $ignored) { }

		if (!empty($this->output))
		{
			// Convert to amount left
			$temp 	= ($this->output / env('RASPI_FEEDER_HEIGHT')) * 100;
			$supply = floor(100 - $temp);

			// For use on other pages
			Session::put('supply', $supply);
		}

		return ['supply' => $supply];
	}

}
