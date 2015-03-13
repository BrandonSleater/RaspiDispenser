<?php namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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


	/**
	 * Upload a sound file.
	 *
	 * @return Redirect
	 */
	public function upload()
	{
		// Storage location of sound file
  	$dest = public_path().'/sounds/';

  	// Input
	  $file  = ['image' => Input::file('image')];
	  $rules = ['image' => 'required'];
	  $flash = [];

	  $validator = Validator::make($file, $rules);
	  
	  if ($validator->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($validator);
	  } 
	  else 
	  {
      $extension = Input::file('image')->getClientOriginalExtension();

	    if (Input::file('image')->isValid() && $extension === 'mp3') 
	    {
	      $filename = Input::file('image')->getClientOriginalName();

	      try 
	      {
	  			$this->clear($dest);
	      	
	      	Input::file('image')->move($dest, $filename);

	      	$this->log($dest.$filename);
	      }
	      catch (Exception $exception)
	      {
	      	Log::error($exception);
	      }

	      $flash['type'] = 'success';
	      $flash['mess'] = 'Upload Successful!';
	    }
	    else 
	    {
	    	$flash['type'] = 'error';
	      $flash['mess'] = 'Upload Failed - ';

	      $flash['mess'] .= ($extension !== 'mp3') ? 'Incorrect Extension (need mp3)' : 'Invalid File';
	    }
	  }

    Session::flash($flash['type'], $flash['mess']);

	  return Redirect::to('home');
	}


	/**
	 * Record the sound file into the system
	 */
	public function log($path)
	{
		$name = Input::file('image')->getClientOriginalName();

		// Cleanup
		$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);

		DB::insert('insert into file (name, path) values (?, ?)', [$name, $path]);
	}


	/**
	 * Remove the old sound file
	 */
	public function clear($directory) 
	{
		if (!File::cleanDirectory($directory)) 
		{
			throw new Exception('Unable to clean sound directory');
		}
	}

}
