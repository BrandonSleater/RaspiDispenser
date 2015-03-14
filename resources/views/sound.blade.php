<div class="col-md-6">
	<div class="panel panel-default panel-form">
		<div class="panel-heading">Dispensing Sound</div>
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
			
	    <form class="form-horizontal" role="form" method="POST" action="{{ url('/file/upload') }}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
  			<input type="file" class="inline-file" name="image"></input>

  			<button class="btn btn-primary btn-sm" type="submit">Upload File</button>
	  	</form>

			@if (!empty($sound_path))
	    	<hr>
				<p>Current File: <b>{{ $sound_name }}</b></p>
	    	<audio controls>
	    		<source src="{{ $sound_path }}" type="audio/mpeg">
	    	</audio>
	    @endif	  
	    	
			<hr>
			<p>Uploaded Files</p>
			{!! $sound_table !!}
	  </div>
  </div>
</div>