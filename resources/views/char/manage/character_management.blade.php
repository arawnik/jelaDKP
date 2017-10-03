@extends('layouts.master')

@section('content')
<h1>{{ trans('common.character_management') }}</h1>

@include('errors.flash-div')

@include('char.manage.add_character')

<p><input type="text" id="searchNames" placeholder="{{ trans('data.search_placeholder') }}"></p>

<table class="table table-hover table-striped table-bordered" id="table-characters">
	<thead>
		<tr>
			<th>{{ trans('data.name') }}</th>
			<th>{{ trans('data.class') }}</th>
			<th>{{ trans('data.role') }}</th>
			<th>{{ trans('management.modify') }}</th>
			<th>{{ trans('management.delete') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($characters as $character)
		<tr>
			<td><a style="color:#{{ $character->class_color }}" class="class_name" href="{{ route('char/{id}', ['id' => $character->char_id]) }}">{{ $character->char_name }}</a></td>
			<td>{{ $character->class_name }}</td>
			<td>{{ $character->role_name }}</td>
			<td><a href="{{ route('modify_character/{id}', ['id' => $character->char_id]) }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
			<td>
				<form action="{{ route('delete_character') }}" accept-charset="UTF-8" method="post" class="form-horizontal deleteForm">
					{{ csrf_field() }}
					<input type="hidden" id="char_id" name="char_id" value="{{ $character->char_id }}">
					<button type="submit" class="deleteBtn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

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