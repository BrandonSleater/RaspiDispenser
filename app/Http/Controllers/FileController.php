<?php namespace App\Http\Controllers;

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
	  $file  = ['image' => Request::file('image')];
	  $rules = ['image' => 'required'];
	  $flash = [];

	  $validator = Validator::make($file, $rules);
	  
	  if ($validator->fails())
	  {
	    return Redirect::to('home')->withInput()->withErrors($validator);
	  } 
	  else 
	  {
	  	$destination = public_path().'/sounds/';

	    if (Input::file('image')->isValid()) 
	    {
	      $extension = Input::file('image')->getClientOriginalExtension();
	      $filename  = Input::file('image')->getClientOriginalName();

	      try 
	      {
	      	Input::file('image')->move($destination, $filename);
	      }
	      catch (Exception $exception)
	      {
	      	Log::error($exception);
	      }

	      $flash['type'] = 'success';
	      $flash['mess'] = 'Upload successful';
	    }
	    else 
	    {
	    	$flash['type'] = 'error';
	      $flash['mess'] = 'Upload file is not valid';
	    }
	  }

    Session::flash($flash['type'], $flash['mess']);

	  return Redirect::to('home');
	}

}
