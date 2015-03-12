@if (Auth::guest())
	<p class="welcome">Welcome to RaspiDispenser!</p>
@else
	<p class="welcome">Welcome {{ Auth::user()->name }}!</p>
@endif
