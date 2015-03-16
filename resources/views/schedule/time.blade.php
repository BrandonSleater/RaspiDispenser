<div class="panel panel-default panel-form">
	<div class="panel-heading">Daily Schedule</div>
	<div class="panel-body">
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<strong>Whoops!</strong> There were some problems with your input.<br><br>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@elseif (session('time_error'))
			<div class="alert alert-danger">
				{{ session('time_error') }}
			</div>
		@endif

		<form class="form-horizontal" role="form" method="POST" action="{{ url('/schedule/add') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="form-group">
				<label class="col-md-2 control-label">Event</label>
				<div class="col-md-4">
					<input type="text" class="form-control" name="event">
				</div>
			</div>

			<div class="form-group">
		    <label class="col-md-2 control-label">Dispense @</label>
		    <div class="input-group bootstrap-timepicker">
	      	<input type="text" class="form-control schedule-time" name="time">
		      <span class="form-control-feedback"><i class="fa fa-clock-o"></i></span>
		    </div>
		  </div>

		  <div class="form-group">
		    <label class="col-md-2 control-label">Amount</label>
				<div class="col-md-2">
					<input type="text" class="form-control" name="amount" placeholder="seconds">
				</div>
		  </div>

			<div class="form-group">
				<div class="col-md-2 col-md-offset-2">
					<button type="submit" class="btn btn-pastel">Add</button>
				</div>
			</div>
		</form>
		<hr>

		{!! $table !!}	

		{{-- Start our timepicker --}}
    <script type="text/javascript">
      $('.schedule-time').timepicker({
      	defaultTime: false,
        minuteStep: 1,
        showSeconds: false
      });
    </script>
	</div>
</div>
