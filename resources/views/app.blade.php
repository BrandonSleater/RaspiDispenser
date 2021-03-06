<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Pet Dispenser Tool</title>

		@include('styles')

		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
		<script src="{{ asset('/js/timepicker.js') }}"></script>

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="container">
			@include('nav')

			<div class="row text-center">
				@include('greeting')
			</div>

			@yield('content')
		</div>
		@include('scripts')
	</body>
</html>
