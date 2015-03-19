@extends('app')

@section('content')
	<div class="row home">
	{{ $status }}
		<div class="col-md-12">@include('schedule/time')</div>
		<div class="col-md-12">@include('supply')</div>
	</div>
@endsection
