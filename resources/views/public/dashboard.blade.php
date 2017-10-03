@extends('layouts.master')

@section('content')
<h1>{{ trans('common.dashboard') }}</h1>

<p>
	<input type="text" id="searchNames" placeholder="{{ trans('data.search_placeholder') }}">
</p>
@if (count($characters) > 0)
	<table class="table table-hover table-striped table-bordered" id="table-characters">
		<thead>
			<tr>
				<th>{{ trans('data.name') }}</th>
				<th>{{ trans('data.class') }}</th>
				<th>{{ trans('data.role') }}</th>
				<th>{{ trans('data.earned') }}</th>
				<th>{{ trans('data.spent') }}</th>
				<th>{{ trans('data.normalization') }}</th>
				<th>{{ trans('data.current') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($characters as $character)
			<tr>
				<td><a style="color:#{{ $character->class_color }}" class="class_name" href="{{ route('char/{id}', ['id' => $character->char_id]) }}">{{ $character->char_name }}</a></td>
				<td>{{ $character->class_name }}</td>
				<td>{{ $character->role_name }}</td>
				<td class="dkpEarned">{{ $character->earned }}</td>
				<td class="dkpSpent">{{ $character->spent }}</td>
				<td class="dkpNormalized">{{ $character->normalized }}</td>
				<td class="dkpCurrent">{{ $character->current }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@else
	<h4>{{ trans('public.no_chars') }}</h4>
@endif
@endsection

@section('javascript')
<script>
//Introduce variables for timed search. We will call searchNames after user stops typing.
var searchTimer; //Actual timer.
var searchTimerTarget = 800; //Timer target time.
/**
 * Reset timer when input is given.
 */
$('#searchNames').on('input', function() {
	clearTimeout(searchTimer);
	searchTimer = setTimeout(searchNames, searchTimerTarget);
});

/**
 * Helper function that calls more used searchTable function with right parameters.
 */
function searchNames() {
	searchTable('searchNames', 'table-characters');
}
</script>
@endsection