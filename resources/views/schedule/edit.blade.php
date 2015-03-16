@extends('app')

@section('content')
	<div class="row home">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default panel-form">
				<div class="panel-heading">Update Schedule</div>
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/schedule/update') }}">
						<input type="hidden" name="id" value="{{ $id }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-3 control-label">Event</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="event" value="{{ $event }}">
							</div>
						</div>

						<div class="form-group">
					    <label class="col-md-3 control-label">Dispense @</label>
					    <div class="input-group bootstrap-timepicker">
				      	<input type="text" class="form-control schedule-time" name="time">
					      <span class="form-control-feedback"><i class="fa fa-clock-o"></i></span>
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="col-md-3 control-label">Amount</label>
							<div class="col-md-3">
								<input type="text" class="form-control" name="amount" value="{{ $amount }}">
							</div>
					  </div>

						<div class="form-group">
					    <label class="col-md-3 control-label">Enable</label>
				    	<div class="col-md-6">
					    	@if ($enable == 1)
					      	<input type="checkbox" name="enable" checked>
					      @else
					      	<input type="checkbox" name="enable">
					      @endif
					    </div>
					  </div>

						<div class="form-group">
							<div class="col-md-3 col-md-offset-3">
								<button type="submit" class="btn btn-pastel">Update</button>
							</div>
						</div>
					</form>

					{{-- Start our timepicker --}}
			    <script type="text/javascript">
			      $('.schedule-time').timepicker({
			        minuteStep: 1,
			        showSeconds: false,
			        defaultTime: '{{ $time }}'
			      });
			    </script>
				</div>
			</div>
		</div>
	</div>
@endsection
