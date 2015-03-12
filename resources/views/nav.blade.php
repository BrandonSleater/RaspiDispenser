<!-- Navigation -->
<nav id="bt-menu" class="bt-menu">
	<a href="#" class="bt-menu-trigger"><span>Menu</span></a>
	<ul>
		<li class="tool tool-right">
			<a href="{{ url('/') }}" class="fa fa-home tool-item">Home</a>
			<span class="tool-content">Home</span>
		</li>
		@if (Auth::guest())
			<li class="tool tool-right">
				<a href="{{ url('/auth/register') }}" class="fa fa-user-plus tool-item">Register</a>
				<span class="tool-content">Register</span>
			</li>
		@else
			<li class="tool tool-right">
				<a href="{{ url('/settings') }}" class="fa fa-gear tool-item">Settings</a>
				<span class="tool-content">Settings</span>
			</li>
			<li class="tool tool-right">
				<a href="{{ url('/auth/logout') }}" class="fa fa-power-off tool-item">Log Out</a>
				<span class="tool-content">Log Out</span>
			</li>
		@endif
	</ul>
</nav>