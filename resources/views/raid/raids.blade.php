@extends('layouts.master')

@section('content')
<h1>{{ trans('common.raids') }}</h1>
@if (count($raids) > 0)
	<table class="table table-hover">
		<thead>
			<tr>
				<th>{{ trans('data.info') }}</th>
				<th>{{ trans('data.value') }}</th>
				<th>{{ trans('data.comment') }}</th>
				<th>{{ trans('data.attendees_count') }}</th>
				<th>{{ trans('data.date') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($raids as $raid)
			<tr>
				<td><a href="{{ route('raid/{id}', ['id' => $raid->raid_id]) }}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
				<td>{{ $raid->raid_value }}</td>
				<td>{{ $raid->raid_comment }}</td>
				<td>{{ $raid->raid_attendees_count }}</td>
				<td>{{ $raid->formed_raid_date }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@else
	<h4>{{ trans('public.no_raids') }}</h4>
@endif

@endsection