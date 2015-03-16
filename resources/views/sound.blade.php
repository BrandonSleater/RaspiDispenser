<div class="col-md-12">
	<div class="panel panel-default panel-form">
		<div class="panel-heading">Dispense Track</div>
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
	
			<p>Add File</p>
	    <form class="form-horizontal" role="form" method="POST" action="{{ url('/file/add') }}" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
  			
  			<button class="btn btn-pastel btn-sm btn-upload" type="submit">Upload File</button>
  			<input type="file" class="inline-file" name="image"></input>
	  	</form>

			@if (!empty($sound_path))
	    	<hr>
				<p>Enabled File: <b>{{ $sound_name }}</b></p>
	    	<audio controls>
	    		<source src="{{ $sound_path }}" type="audio/mpeg">
	    	</audio>
	    @endif	  

			<hr>
			<p>My Files</p>
			{!! $sound_table !!}
	  </div>
  </div>
</div>