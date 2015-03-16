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
		@endif

		<form class="form-inline" role="form" method="POST" action="{{ url('/schedule/add') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="form-group">
				<label class="control-label" for="dispense-event">Event</label>
				<input type="text" class="form-control" id="dispense-event" name="event">
			</div>

		  <div class="form-group">
		    <label class="control-label time-label" for="dispense-time">Dispense @</label>
		    <div class="input-group bootstrap-timepicker">
	      	<input type="text" class="form-control" id="dispense-time" name="time">
		      <span class="form-control-feedback"><i class="fa fa-clock-o"></i></span>
		    </div>
		  </div>
			
			<button type="submit" class="btn btn-pastel">Add Time</button>
		</form>
		<hr>

		{!! $schedule_table !!}	

		{{-- Start our timepicker --}}
    <script type="text/javascript">
      $('#dispense-time').timepicker({
      	defaultTime: false,
        minuteStep: 1,
        showSeconds: false
      });
    </script>
	</div>
</div>
