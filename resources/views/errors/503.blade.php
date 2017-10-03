@extends('layouts.master')

@section('above')
@endsection

@section('content')
	<div class="maintenanceBlock">
		<img src="/assets/img/under_construction.png" alt="{{ trans('error.construction_img_alt') }}" class="maintenanceImg">
		<h1>{{ trans('error.maintenance_header') }}</h1>
		
		<div class="messageBlock">{{ $exception->getMessage() }}</div>
		
		<p>{{ trans('error.maintenance_message') }}</p>
	</div>
@endsection