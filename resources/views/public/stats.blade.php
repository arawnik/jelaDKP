@extends('layouts.master')

@section('content')
<h1>{{ trans('public.statistics') }}</h1>

<h2>{{ trans('data.attendance') }}</h2>
<table class="table table-hover">
	<thead>
		<tr>
			<th>{{ trans('data.name') }}</th>
			<th>{{ trans('data.class') }}</th>
			<th>{{ trans('data.role') }}</th>
			<th>{{ trans('public.lifetime_attendance') }}</th>
			<th>{{ trans('public.last_ten_attendance') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($characters as $char)
		<tr>
			<td><a style="color:#{{ $char->class_color }}" class="class_name" href="{{ route('char/{id}', ['id' => $char->char_id]) }}">{{ $char->char_name }}</a></td>
			<td>{{ $char->class_name }}</td>
			<td>{{ $char->role_name }}</td>
			<td>{{ $char->attendance_lifetime }} / {{ $raids_lifetime_count }}</td>
			<td>{{ $char->attendance_last_ten }} / 10</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endsection