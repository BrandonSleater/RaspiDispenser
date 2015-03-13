<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">Music</div>
		<div class="panel-body">
	    @if (Session::has('success'))
	      <div class="alert-box success">
	      	<h2>{{ Session::get('success') }}</h2>
	      </div>
	    @endif

    	<p>Current Uploaded File: {{ $sound_name }}</p>

	    <form class="form-horizontal" role="form" method="POST" action="{{ url('/file/upload') }}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
		  
				<p class="errors">{{ $errors->first('image') }}</p>
		  	<div id="success"></div>

  			<input type="file" class="inline-file" name="image"></input>
  			<button class="btn btn-primary btn-sm" type="submit">Upload</button>
	  	</form>
	  </div>
  </div>
</div>