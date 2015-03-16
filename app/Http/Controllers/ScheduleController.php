<?php namespace App\Http\Controllers;

ini_set('display_errors', '1');

use Auth, Datatable, DB, File, Input, Redirect, Request, Session, Validator;
use Illuminate\Database\Eloquent\Collection;

class ScheduleController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Schedule Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles adding dispense times.
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
	 * Insert the time into to the schedule table.
	 *
	 * @param  array @args
	 * @return Redirect
	 */
	public function add()
	{
	  $rules = ['event' => 'required', 'time' => 'required'];

		$schedule = [
			'event' => Input::get('event'),
			'time'	=> Input::get('time')
		];

		// Format to 24h
		$schedule['time'] = date('H:i', strtotime($schedule['time']));

	  $validator = Validator::make($schedule, $rules);
	  
	  if ($validator->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($validator);
	  } 
	  else 
	  {
	  	$this->record($schedule);
	  }

	  Session::flash('success', 'Schedule Added Successfully');

	  return Redirect::to('home');
	}

	/**
	 * Load the event time edit form.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function edit($id)
	{
		$results = DB::select('select event, DATE_FORMAT(time, "%h:%i %p") AS "time", enable from schedule where id = ?', [$id]);

		$values = [
			'id'     => $id,
			'event'  => $results[0]->event,
			'time'   => $results[0]->time,
			'enable' => $results[0]->enable
		];

		return view('schedule/edit')->with($values);
	}

	/**
	 * Build the datatable of scheduled times.
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getTable()
	{
		$sql = '
			select 
				id, 
				event, 
				DATE_FORMAT(time, "%h:%i %p") AS "time", 
				enable 
			from 
				schedule 
			where 
				user = ?
			order by
				time desc';

		$results = DB::select($sql, [Auth::id()]);

		return Datatable::collection(new Collection($results))
			->addColumn('event', function($model) 
			{
				return '<a href="'.url("/schedule/edit&ID=".$model->id).'">'.$model->event.'</a>';
			})
			->showColumns('time', 'enable')
      ->searchColumns('event', 'time', 'enable')
      ->orderColumns('time')
      ->make();
	}

	/**
	 * Insert the event and time into the schedule table.
	 *
	 * @param  array $schedule
	 * @return void
	 */
	public function record($schedule)
	{
		DB::insert('
			insert into schedule 
				(event, time, user, created_at) 
			values 
				(?, ?, ?, NOW())
			on duplicate key update
				event = ?,
				enable = 1, 
				updated_at = NOW()', [$schedule['event'], $schedule['time'], Auth::id(), $schedule['event']]);
	}


	/**
	 * Update an event time.
	 *
	 * @return Redirect
	 */
	public function update()
	{
	  $rules = ['event' => 'required', 'time' => 'required'];

		$schedule = [
			'event' => Input::get('event'),
			'time'	=> Input::get('time')
		];

		// Format to 24h
		$schedule['time'] = date('H:i', strtotime($schedule['time']));

	  $validator = Validator::make($schedule, $rules);
	  
	  if ($validator->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($validator);
	  } 
	  else 
	  {
	  	// Get other inputs
	  	$schedule['id']			= Input::get('id');
	  	$schedule['enable'] = Input::get('enable');

	  	$this->revise($schedule);
	  }

	  Session::flash('success', 'Schedule Updated Successfully');

	  return Redirect::to('home');
	}

	/**
	 * Update the scheduled event.
	 *
	 * @param  array $args
	 * @return void
	 */
	private function revise($args)
	{
		$enable = $args['enable'] == 'on' ? 1 : 0;

		DB::table('schedule')
			->where('id', $args['id'])
			->update([
				'event'  => $args['event'],
				'time'   => $args['time'],
				'enable' => $enable
			]);
	}

}
