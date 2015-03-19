<?php namespace App\Http\Controllers;

use Datatable, SSH;

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
		$status = $this->getConnectionStatus(5000);

		$inputs = array_merge($table, $status);

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
	 * Make contact with the raspberry pi.
	 *
	 * @param array
	 */
	protected function getConnectionStatus($amount)
	{
		$command = env('RASPI_COMMAND').' '.$amount;

		try
		{
			SSH::run([$command], function($line) {
				$this->output = $line.PHP_EOL;
			});
		}
		catch (\ErrorException $e)
		{
			// Intentionally left blank
		}

		return ['status' => $this->output];
	}

}
