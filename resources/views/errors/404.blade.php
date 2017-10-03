@extends('layouts.master')

@section('above')
@endsection

@section('content')
	<div style="text-align:center;">
		<h1 >{{ trans('error.404_code') }}</h1>
		<h2>{{ trans('error.404_message') }}</h2>
	</div>
@endsection