<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading">Music</div>
		<div class="panel-body">
			@if (session('success'))
				<div class="alert alert-success">
					{{ session('success') }}
				</div>
			@elseif (session('error'))
				<div class="alert alert-danger">
					{{ session('error') }}
				</div>
			@endif

    	<p>Current Uploaded File: {{ $sound_name }}</p>
    	<audio controls>
    		<source src="{{ $sound_path }}" type="audio/mpeg">
    	</audio>

	    <form class="form-horizontal" role="form" method="POST" action="{{ url('/file/upload') }}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
  			<input type="file" class="inline-file" name="image"></input>

  			<button class="btn btn-primary btn-sm" type="submit">Upload</button>
	  	</form>
	  </div>
  </div>
</div>