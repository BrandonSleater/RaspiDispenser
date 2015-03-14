<?php namespace App\Http\Controllers;

use Auth, Datatable, DB, File, Input, Redirect, Request, Session, Validator;
use Illuminate\Database\Eloquent\Collection;

class FileController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| File Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles uploading sound files
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


	public function getTable()
	{
		$query = DB::select('select id, name, enable from file where user = ?', [Auth::id()]);

		return Datatable::collection(new Collection($query))
			->showColumns('name')
			->addColumn('enable', function($model) 
			{
				return '<a href="'.url("/file/edit/".$model->id."-".$model->enable).'">'.$model->enable.'</a>';
			})
      ->searchColumns('name')
      ->orderColumns('enable', 'name')
      ->make();
	}


	public function edit($id, $enable)
	{
		// If enabling, make sure everything else is zeroed out
		if ($enable == 0) {
			DB::table('file')
				->where('user', Auth::id())
				->where('enable', 1)
				->update(['enable' => 0]);
		}

		// Now change the value
		DB::table('file')
			->where('id', $id)
			->update(['enable' => !$enable]);

	  return Redirect::to('home');
	}


	/**
	 * Upload a sound file.
	 *
	 * @return Redirect
	 */
	public function upload()
	{
  	// Setup
		$dest = public_path().'/sounds/'.Auth::id().'/';
	  $sess = [];

	  // input
	  $file  = ['image' => Input::file('image')];
	  $rules = ['image' => 'required'];

	  $validator = Validator::make($file, $rules);
	  
	  if ($validator->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($validator);
	  } 
	  else 
	  {
      $extension = Input::file('image')->getClientOriginalExtension();

      // Ensure its a sound file
	    if (Input::file('image')->isValid() && $extension === 'mp3')
	    {
	      $filename = Input::file('image')->getClientOriginalName();

	      // Setup the file
	      try 
	      {	      	
	      	Input::file('image')->move($dest, $filename);

	      	$this->log($dest.$filename);
	      }
	      catch (Exception $exception)
	      {
	      	Log::error($exception);
	      }

	      $sess['type'] = 'success';
	      $sess['mess'] = 'Upload Successful!';
	    }
	    else 
	    {
	    	$sess['type'] = 'error';
	      $sess['mess'] = 'Upload Failed - ';

	      $sess['mess'] .= ($extension !== 'mp3') ? 'Incorrect Extension (need mp3)' : 'Invalid File';
	    }
	  }

    Session::flash($sess['type'], $sess['mess']);

	  return Redirect::to('home');
	}


	/**
	 * Record the sound file into the system
	 */
	public function log($path)
	{
		$name = Input::file('image')->getClientOriginalName();

		// Disable the old record
		DB::update('update file set enable = ? where user = ? and enable = 1', [0, Auth::id()]);

		// We want relative
		$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

		// Create the record
		DB::insert('
			insert into file 
				(name, user, path, created_at) 
			values 
				(?, ?, ?, NOW())
			on duplicate key update
				path = ?,
				enable = 1, 
				updated_at = NOW()', [$name, Auth::id(), $path, $path]);
	}

}
