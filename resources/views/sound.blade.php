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
	
			<div class="row">		
				<div class="col-md-5 uploader">
			    <form class="form-horizontal" role="form" method="POST" action="{{ url('/file/upload') }}" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
		  			
		  			<button class="btn btn-primary btn-sm btn-upload" type="submit">Upload File</button>
		  			<input type="file" class="inline-file" name="image"></input>
			  	</form>
			  </div>

				<div class="col-md-5">
					@if (!empty($sound_path))
			    	<!-- <hr> -->
						<p>Current File: <b>{{ $sound_name }}</b></p>
			    	<audio controls>
			    		<source src="{{ $sound_path }}" type="audio/mpeg">
			    	</audio>
			    @endif	  
			  </div>
			</div>

			<hr>
			<p>Uploaded Files</p>
			@if (isset($sound_table))
				{!! $sound_table !!}
	  	@endif
	  </div>
  </div>
</div>