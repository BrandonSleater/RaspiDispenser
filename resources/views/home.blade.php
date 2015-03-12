@extends('app')

@section('content')
	<div class="row home">
		@include('schedule')
		@include('amount')
		@include('sound')
		@include('supply')
	</div>
@endsection
