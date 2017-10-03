{{-- Author: Jere Junttila <junttila.jere@gmail.com>, License: GPL 3.0 https://www.gnu.org/licenses/gpl-3.0.en.html --}}
<!DOCTYPE html>
<html lang="{{ trans('common.lang_code') }}">
	<head>
		@include('layouts.meta')
		@section('extraMeta')
		@show
	</head>
    <body>
		<div id="above">
			@section('above')@show
		</div>
		<div class="container">
			<header>
				<a href="{{ route('home') }}" id="bannerImg"><img src="/assets/css/img/banner.png" alt="{{ trans('common.banner_image') }}"></a>
				
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#souldkp-navbar-collapse" aria-expanded="false">
								<span class="sr-only">{{ trans('common.toggle_navigation') }}</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="souldkp-navbar-collapse">
							<ul class="nav navbar-nav">
								<li><a href="{{ route('home') }}">{{ trans('common.dashboard') }}</a></li>
								<li><a href="{{ route('raids') }}">{{ trans('common.raids') }}</a></li>
								<li><a href="{{ route('stats') }}">{{ trans('common.stats') }}</a></li>
							</ul>
							@if (Auth::check())
							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('common.manage') }} <span class="caret"></span></a>
									<ul class="dropdown-menu">
										<!--li class="dropdown-header">{{ trans('common.data_management') }}</li-->
										<li><a href="{{ route('raid_management') }}">{{ trans('common.raid_management') }}</a></li>
										<li><a href="{{ route('normalization_management') }}">{{ trans('common.normalization_management') }}</a></li>
										<li><a href="{{ route('character_management') }}">{{ trans('common.character_management') }}</a></li>
										<!--li class="dropdown-header">{{ trans('common.detail_management') }}</li>
										<li><a href="{{ route('home') }}">{{ trans('common.class_management') }}</a></li>
										<li><a href="{{ route('home') }}">{{ trans('common.role_management') }}</a></li>
										<li><a href="{{ route('home') }}">{{ trans('common.settings') }}</a></li-->
									</ul>
								</li>
								<li><a href="{{ route('logout') }}">{{ trans('common.logout') }}</a></li>
							</ul>
							@else
							<ul class="nav navbar-nav navbar-right">
								<li><a href="{{ route('login') }}">{{ trans('common.login') }}</a></li>
							</ul>
							@endif
						</div><!-- /.navbar-collapse -->
					</div><!-- /.container-fluid -->
				</nav>
			</header>
			
			<main class="well">
				@yield('content')
			</main>
			
			
			<footer id="footer">
			@section('footer')
				<a href="https://jerejunttila.fi">{{ trans('common.copyright') }}</a>
			@show
			</footer>
		</div>
		<!-- Jquery -->
		<script src="/assets/external/jquery/jquery.min.js"></script>
		<!-- Bootstrap -->
		<script src="/assets/external/bootstrap/js/bootstrap.min.js"></script>
		<!-- JavaScripts -->
		<script src="/assets/js/global.js"></script>
		@section('javascript')
		@show
    </body>
</html>