@if (Auth::guest())
	<p class="welcome">Welcome to RaspiDispenser</p>
@else
	<p class="welcome">Welcome {{ Auth::user()->name }}!</p>
	<p style="color: #777; font-size: 20px">You are currently <b>
	{!! !empty($status) ? 'connected</b> to' : 'disconnected</b> from' !!} your feeder</p>
@endif
