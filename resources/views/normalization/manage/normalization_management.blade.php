@extends('layouts.master')

@section('content')
<h1>{{ trans('common.normalization_management') }}</h1>

@include('errors.flash-div')

<div class="row">
	@include('normalization.manage.add_normalization')

	<div class="col-md-6">
		<h2>{{ trans('management.info_normalization') }}</h2>
		<p>{!! trans('management.info_normalization_desc') !!}</p>
		<a href="{{ route('modify_latest_normalization') }}" class="btn btn-primary">{{ trans('management.modify_latest_normalization') }}</a>
	</div>
</div>
<table class="table table-hover">
	<thead>
		<tr>
			<th>Added by</th>
			<th>{{ trans('data.amount') }}</th>
			<th>{{ trans('data.comment') }}</th>
			<th>{{ trans('data.date') }}</th>
			<th>{{ trans('management.delete') }}</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($normalizations as $normalization)
		<tr>
			<td>{{ $name }}</td>
			<td>{{ $normalization->normalization_percent }}%</td>
			<td>{{ $normalization->normalization_comment }}</td>
			<td>{{ $normalization->formed_normalization_date }}</td>
			<td>
				<form action="{{ route('delete_normalization') }}" accept-charset="UTF-8" method="post" class="form-horizontal deleteForm">
					{{ csrf_field() }}
					<input type="hidden" id="normalization_id" name="normalization_id" value="{{ $normalization->normalization_id }}">
					<button type="submit" class="deleteBtn"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
				</form>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

@endsection