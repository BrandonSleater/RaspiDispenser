<div class="row">
  <div class="col-md-8">
    @if (Session::has('success'))
      <div class="alert-box success">
      	<h2>{{ Session::get('success') }}</h2>
      </div>
    @endif

	  <div class="secure">Upload form</div>

    {!! Form::open(['url' => 'file/upload', 'method'=>'POST', 'files' => true]) !!}
	    {!! Form::token() !!}
	  
      {!! Form::file('image') !!}
			
			<p class="errors">{{$errors->first('image')}}</p>
			
			@if (Session::has('error'))
				<p class="errors">{{ Session::get('error') !!}</p>
			@endif

	  	<div id="success"></div>

	  	{!! Form::submit('Submit', ['class' => 'send-btn']) !!}
  	{!! Form::close() !!}
  </div>
</div>