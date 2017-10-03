@extends('layouts.master')

@section('content')

<h1>{{ trans('management.modify_character') }}</h1>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">{{ trans('management.character_info') }}</div>
			<div class="panel-body">
				<form action="{{ route('update_character') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
					{{ csrf_field() }}
					<input type="hidden" id="char_id" name="char_id" value="{{ $char_id }}">
					
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label for="name" class="col-sm-3 control-label">{{ trans('data.name') }}</label>
						<div class="col-sm-6">
							<input id="name" type="text" name="name" value="{{ $char_data->char_name}}" class="form-control"  autofocus>
						</div>
						@if($errors->has('name'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('class') ? ' has-error' : '' }}">
						<label for="class" class="col-sm-3 control-label">{{ trans('data.class') }}</label>
						<div class="col-sm-6">
							<select class="form-control" id="class" name="class">
								@foreach ($classes as $class)
									<option value="{{ $class->class_id}}" @if ($class->class_id == $char_data->char_class) selected @endif>{{ $class->class_name}}</option>
								@endforeach
							</select>
						</div>
						@if($errors->has('class'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('class') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
						<label for="role" class="col-sm-3 control-label">{{ trans('data.role') }}</label>
						<div class="col-sm-6">
							<select class="form-control" id="role" name="role">
								@foreach ($roles as $role)
									<option value="{{ $role->role_id}}" @if ($role->role_id == $char_data->char_role) selected @endif>{{ $role->role_name}}</option>
								@endforeach
							</select>
						</div>
						@if($errors->has('role'))
						<div class="col-sm-3">
							<span class="help-block"><strong>{{ $errors->first('role') }}</strong></span>
						</div>
						@endif
					</div>
					
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-6">
							<input class="btn btn-primary form-control" type="submit" value="{{ trans('management.update_character') }}">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('javascript')
<script>

</script>
@endsection