@if (Auth::guest())
	<p class="welcome">Welcome to RaspiDispenser!</p>
	<p style="color: #777; font-size: 14px">You are currently <b>{{ /* connected/disconnected to raspi */ }}</b></p>
@else
	<p class="welcome">Welcome {{ Auth::user()->name }}!</p>
@endif
