@extends('layouts.master')

@section('content')

<h1 style="color: #{{ $char_data->class_color }};" class="class_name">{{ $char_data->char_name }}<small>, {{ $char_data->role_name }} {{ $char_data->class_name }}</small></h1>

<div class="row">
	<div class="col-md-6">
		<h2>{{ trans('public.items_acquired') }}</h2>
		@if (count($items) > 0)
			<table class="table table-hover" id="table_items">
				<thead>
					<tr>
						<th>{{ trans('data.item') }}</th>
						<th>{{ trans('data.price') }}</th>
						<th>{{ trans('public.source') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($items as $item)
					<tr>
						<td>{{ $item->use_desc}}</td>
						<td>{{ $item->use_amount}}</td>
						<td><a href="{{ route('raid/{id}', ['id' => $item->use_raid]) }}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<h4>{{ trans('public.no_items') }}</h4>
		@endif
	</div>
	<div class="col-md-6">
		<h2>{{ trans('common.currency') }} {{ trans('common.status') }}</h2>
		<table class="table table-hover" id="table_items">
				<thead>
					<tr>
						<th>{{ trans('data.variable') }}</th>
						<th>{{ trans('data.value') }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ trans('data.earned') }}</td>
						<td>{{ $char_data->earned}}</td>
					</tr>
					<tr>
						<td>{{ trans('data.spent') }}</td>
						<td>{{ $char_data->spent}}</td>
					</tr>
					<tr>
						<td>{{ trans('data.normalization') }}</td>
						<td>{{ $char_data->normalized}}</td>
					</tr>
					<tr>
						<td>{{ trans('data.current') }}</td>
						<td>{{ $char_data->current}}</td>
					</tr>
				</tbody>
			</table>
	</div>
</div>

<h2>{{ trans('public.raids_attended') }}</h2>
@if (count($raids_attended) > 0)
	<table class="table table-hover" id="table_attend">
		<thead>
			<tr>
				<th>{{ trans('data.info') }}</th>
				<th>{{ trans('data.comment') }}</th>
				<th>{{ trans('data.value') }}</th>
				<th>{{ trans('data.date') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($raids_attended as $ra)
			<tr>
				<td><a href="{{ route('raid/{id}', ['id' => $ra->attend_raid]) }}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
				<td>{{ $ra->raid_comment}}</td>
				<td>{{ $ra->raid_value}}</td>
				<td>{{ $ra->formed_raid_date}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@else
	<h4>{{ trans('public.not_attended') }}</h4>
@endif

@endsection

@section('javascript')

@endsection