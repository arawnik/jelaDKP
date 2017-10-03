@extends('layouts.master')

@section('content')
<h1>{{ trans('common.raid_management') }}</h1>

@include('errors.flash-div')

@include('raid.manage.add_raid')

<table class="table table-hover">
	<thead>
		<tr>
			<th>{{ trans('data.info') }}</th>
			<th>{{ trans('data.value') }}</th>
			<th>{{ trans('data.comment') }}</th>
			<th>{{ trans('data.attendees_count') }}</th>
			<th>{{ trans('data.date') }}</th>
			<th>{{ trans('management.modify') }}</th>
			<th>{{ trans('management.delete') }}</th>
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
			<td><a href="{{ route('modify_raid/{id}', ['id' => $raid->raid_id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
			<td>
				<form action="{{ route('delete_raid') }}" accept-charset="UTF-8" method="post" class="form-horizontal deleteForm">
					{{ csrf_field() }}
					<input type="hidden" id="raid_id" name="raid_id" value="{{ $raid->raid_id }}">
					<button type="submit" class="deleteBtn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

@endsection