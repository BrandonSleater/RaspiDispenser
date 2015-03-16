<?php namespace App\Http\Controllers;

use Auth, Datatable, DB, Redirect, Session, Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
	 * Load the event time edit form.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function edit($id)
	{
		$sql = '
			select 
				event, 
				DATE_FORMAT(time, "%h:%i %p") AS "time",
				amount,
				enable 
			from 
				schedule 
			where 
				id = ?';

		$results = DB::select($sql, [$id]);

		return view('schedule/edit')->with([
			'id'     => $id,
			'event'  => $results[0]->event,
			'time'   => $results[0]->time,
			'amount' => $results[0]->amount,
			'enable' => $results[0]->enable
		]);
	}

	/**
	 * Build the datatable of scheduled times.
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function table()
	{
		$sql = '
			select 
				id, 
				event, 
				DATE_FORMAT(time, "%h:%i %p") AS "time",
				amount,
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
			->showColumns('time', 'amount', 'enable')
			->addColumn('remove', function($model) 
			{
				return '<a href="'.url("/schedule/delete&ID=".$model->id).'"><i class="fa fa-times"></i></a>';
			})
      ->searchColumns('event', 'time', 'amount', 'enable')
      ->orderColumns('event', 'time', 'amount', 'enable')
      ->make();
	}

	/**
	 * Insert the time into to the schedule table.
	 *
	 * @param  Request  $request
	 * @return Redirect
	 */
	public function add(Request $request)
	{
	  $v = Validator::make($request->all(), [
	  	'amount' => 'required|Numeric',
	  	'event'  => 'required',
	  	'time'	 => 'required'
	  ]);
	  
	  if ($v->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($v);
	  } 
	  else 
	  {
	  	$this->doAdd($request->all());
	  }

	  Session::flash('success', 'Schedule Added Successfully');

	  return Redirect::to('home');
	}

	/**
	 * Update an event time.
	 *
	 * @param  Request  $request
	 * @return Redirect
	 */
	public function update(Request $request)
	{
		$v = Validator::make($request->all(), [
	  	'amount' => 'required|Numeric',
	  	'event'  => 'required',
	  	'time'	 => 'required'
	  ]);
	  
	  if ($v->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($v);
	  } 
	  else 
	  {
	  	$this->doUpdate($request->all());
	  }

	  Session::flash('success', 'Schedule Updated Successfully');

	  return Redirect::to('home');
	}

	/**
	 * Insert the event and time into the schedule table.
	 *
	 * @param  array $inputs
	 * @return void
	 */
	private function doAdd($inputs)
	{
		// Convert format
		$inputs['time'] = date('H:i', strtotime($inputs['time']));

		$sql = '
			insert into schedule 
				(event, time, amount, user, created_at) 
			values 
				(?, ?, ?, ?, NOW())
			on duplicate key update
				event = ?,
				amount = ?,
				enable = 1, 
				updated_at = NOW()';

		DB::insert($sql, [
			$inputs['event'], 
			$inputs['time'], 
			$inputs['amount'], 
			Auth::id(), 
			$inputs['event'],
			$inputs['amount'] 
		]);
	}

	/**
	 * Update the scheduled event.
	 *
	 * @param  array $inputs
	 * @return void
	 */
	private function doUpdate($inputs)
	{
		$enable = isset($inputs['enable']) ? 1 : 0;

		// Convert form
		$inputs['time'] = date('H:i', strtotime($inputs['time']));
	
		try 
		{
			DB::table('schedule')
				->where('id', $inputs['id'])
				->update([
					'event'      => $inputs['event'],
					'time'       => $inputs['time'],
					'amount'     => $inputs['amount'],
					'enable'     => $enable,
					'updated_at' => new \DateTime()
				]);
		}
		catch (\Illuminate\Database\QueryException $e)
		{
			Session::flash('time_error', 'Time Already Exists');
		  return Redirect::to('home');
		}
	}

	/**
	 * Delete a scheduled time.
	 *
	 * @param  int $id
	 * @return Redirect
	 */
	public function delete($id)
	{
		DB::table('schedule')->where('id', $id)->delete();

		return Redirect::to('home');
	}

}
