<?php namespace App\Http\Controllers;

use Auth, Datatable, DB, File, Input, Redirect, Request, Session, Validator;
use Illuminate\Database\Eloquent\Collection;

class FileController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| File Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles uploading sound files.
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
	 * Process adding a sound file.
	 *
	 * @return Redirect
	 */
	public function add()
	{
  	// Setup
		$destination = public_path().'/sounds/'.Auth::id().'/';
	  $message = [];

	  // Input
	  $file  = ['image' => Input::file('image')];
	  $rules = ['image' => 'required'];

	  $validator = Validator::make($file, $rules);
	  
	  if ($validator->fails())
	  {
	    return Redirect::to('settings')->withInput()->withErrors($validator);
	  } 
	  else 
	  {
	  	$message = $this->upload($destination);
	  }

    Session::flash($message['type'], $message['content']);

	  return Redirect::to('settings');
	}

	/**
	 * Edit a sound file.
	 *
	 * @param  int  $id
	 * @param  int  $enable
	 * @return Redirect
	 */
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

	  return Redirect::to('settings');
	}

	/**
	 * Build the datatable of uploaded sound files.
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getTable()
	{
		$results = DB::select('select id, name, enable from file where user = ?', [Auth::id()]);

		return Datatable::collection(new Collection($results))
			->showColumns('name')
			->addColumn('enable', function($model) 
			{
				return '<a href="'.url("/file/edit&ID=".$model->id."&EN=".$model->enable).'">'.$model->enable.'</a>';
			})
      ->searchColumns('name')
      ->orderColumns('enable', 'name')
      ->make();
	}

	/**
	 * Insert the sound file into to the file table.
	 *
	 * @param  string $path
	 * @return void
	 */
	public function record($path)
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

	/**
	 * Upload a sound file into storage.
	 *
	 * @param  string $destination
	 * @return array
	 */
	private function upload($destination)
	{
    $extension = Input::file('image')->getClientOriginalExtension();

    // Message details
    $content = $type = '';

    // Ensure its a sound file
    if (Input::file('image')->isValid() && $extension === 'mp3')
    {
      $filename = Input::file('image')->getClientOriginalName();

      // Insert file
      try 
      {
      	$path = $destination.$filename;

      	Input::file('image')->move($destination, $filename);

      	$this->record($path);
      }
      catch (Exception $exception)
      {
      	Log::error($exception);
      }

      $type = 'success';
      $content = 'Upload Successful!';
    }
    else 
    {
      $desc = ($extension !== 'mp3') ? 'Incorrect Extension (need mp3)' : 'Invalid File';

    	$type = 'error';
      $content = 'Upload Failed - '.$desc;
    }

    return ['content' => $content, 'type' => $type];
	}

}
