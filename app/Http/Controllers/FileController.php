<?php namespace App\Http\Controllers;

use Auth, Datatable, DB, File, Redirect, Session, Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class FileController extends Controller {

	private $destination;
	private $path;

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

		$this->destination = public_path().'/sounds/'.Auth::id().'/';
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
	public function table()
	{
		$sql = '
			select 
				id, 
				name, 
				enable 
			from 
				file 
			where 
				user = ?';

		$results = DB::select($sql, [Auth::id()]);

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
	 * Process adding a sound file.
	 *
	 * @return Redirect
	 */
	public function add(Request $request)
	{
	  $v = Validator::make($request->all(), [
	  	'image' => 'required'
	  ]);
	  
	  if ($v->fails())
	  {
	    return Redirect::to('settings')->withInput()->withErrors($v);
	  } 
	  else 
	  {
	  	$message = $this->upload($request);
	  }

    Session::flash($message['type'], $message['content']);

	  return Redirect::to('settings');
	}

	/**
	 * Upload a sound file into storage.
	 *
	 * @param  Request $request
	 * @return array
	 */
	protected function upload(Request $request)
	{
    $extension = $request->file('image')->getClientOriginalExtension();
    $filename  = $request->file('image')->getClientOriginalName();

    $this->path = $this->destination.$filename;

    // Ensure its a sound file
    if ($request->file('image')->isValid() && $extension === 'mp3')
    {
      try 
      {
      	$request->file('image')->move($this->destination, $filename);

      	// Record entry
      	$this->doAdd($filename);
      }
      catch (Exception $exception)
      {
      	Log::error($exception);
      }

      $content = 'Upload Successful!';
      $type = 'success';
    }
    else 
    {
      $desc = ($extension !== 'mp3') ? 'Incorrect Extension (need mp3)' : 'Invalid File';

      $content = 'Upload Failed - '.$desc;
    	$type = 'error';
    }

    return ['content' => $content, 'type' => $type];
	}

	/**
	 * Insert the sound file into to the file table.
	 *
	 * @param  string $path
	 * @return void
	 */
	private function doAdd($filename)
	{
		$sql = '
			update file set 
				enable = 0
			where 
				user = ? and 
				enable = 1';

		// Disable the old record
		DB::update($sql, [Auth::id()]);

		// We want relative
		$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->path);

		$sql = '
			insert into file 
				(name, user, path, created_at) 
			values 
				(?, ?, ?, NOW())
			on duplicate key update
				path = ?,
				enable = 1, 
				updated_at = NOW()';

		DB::insert($sql, [$filename, Auth::id(), $path, $path]);
	}

}
