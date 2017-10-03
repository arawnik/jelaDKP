@extends('layouts.master')

@section('content')
<h1>{{ $raid_data->raid_comment }} <small>{{ $raid_data->formed_raid_date }}</small></h1>
<h4>{{ $raid_data->raid_value }} {{ trans('common.currency') }}.</h4>

<div class="row">
	<div class="col-md-6">
		<h2>{{ trans('public.raid_items') }}</h2>
		@if (count($raid_items) > 0)
			<table class="table table-hover">
				<thead>
					<tr>
						<th>{{ trans('data.character') }}</th>
						<th>{{ trans('data.price') }}</th>
						<th>{{ trans('data.item') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($raid_items as $ri)
					<tr>
						<td><a style="color:#{{ $ri->class_color }}" class="class_name" href="{{ route('char/{id}', ['id' => $ri->use_character]) }}">{{ $ri->char_name }}</a></td>
						<td>{{ $ri->use_amount}}</td>
						<td>{{ $ri->use_desc}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<h4>{{ trans('public.raid_no_items') }}</h4>
		@endif
	</div>
	<div class="col-md-6">
		<h2>{{ trans('public.raid_adjustments') }}</h2>
		@if (count($raid_adjustments) > 0)
			<table class="table table-hover">
				<thead>
					<tr>
						<th>{{ trans('data.character') }}</th>
						<th>{{ trans('data.amount') }}</th>
						<th>{{ trans('data.reason') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($raid_adjustments as $ra)
					<tr>
						<td><a style="color:#{{ $ra->class_color }}" class="class_name" href="{{ route('char/{id}', ['id' => $ra->adjust_character]) }}">{{ $ra->char_name }}</a></td>
						<td>{{ $ra->adjust_value}}</td>
						<td>{{ $ra->adjust_comment}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<h4>{{ trans('public.raid_no_adjustments') }}</h4>
		@endif
	</div>
</div>

<h2>Raid attends</h2>
@if (count($raid_attends) > 0)
	<table class="table table-hover">
		<thead>
			<tr>
				<th>{{ trans('data.name') }}</th>
				<th>{{ trans('data.class') }}</th>
				<th>{{ trans('data.role') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($raid_attends as $ra)
				<tr>
					<td><a style="color:#{{ $ra->class_color }}" class="class_name" href="/char/{{ $ra->attend_character }}">{{ $ra->char_name }}</a></td>
					<td>{{ $ra->class_name}}</td>
					<td>{{ $ra->role_name}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	<h4>{{ trans('public.raid_no_attendants') }}</h4>
@endif

@endsection