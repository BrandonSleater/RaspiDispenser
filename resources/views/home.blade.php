@extends('app')

@section('content')
	<div class="row home">
		<div class="col-md-12">@include('schedule')</div>
		<div class="col-md-12">@include('amount')</div>
		<div class="col-md-12">@include('supply')</div>
	</div>
@endsection
