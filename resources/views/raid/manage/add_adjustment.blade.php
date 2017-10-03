<div class="col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading">{{ trans('management.add_adjustment') }}</div>
		<div class="panel-body">
			@if($errors->has('has_adjustment'))
				<div class="col-sm-12 alert alert-warning" role="alert">
					<span class="help-block"><strong>{{ $errors->first('has_adjustment') }}</strong></span>
				</div>
			@endif
		
			<form action="{{ route('create_raid_adjustment') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
				{{ csrf_field() }}
				<input type="hidden" id="raid_id" name="raid_id" value="{{ $raid_id }}">
				
				<div class="form-group{{ $errors->has('adjust_character') ? ' has-error' : '' }}">
					<label for="adjust_character" class="col-sm-3 control-label">{{ trans('data.character') }}</label>
					<div class="col-sm-6">
						<select class="form-control" id="adjust_character" name="adjust_character">
							@foreach ($characters as $adjust_character)
								<option value="{{ $adjust_character->char_id}}" @if ($adjust_character->char_id == old('adjust_character')) selected @endif>{{ $adjust_character->char_name}}</option>
							@endforeach
						</select>
					</div>
					@if($errors->has('adjust_character'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('adjust_character') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('adjust_value') ? ' has-error' : '' }}">
					<label for="adjust_value" class="col-sm-3 control-label">{{ trans('common.currency') }} {{ trans('data.price') }}</label>
					<div class="col-sm-6">
						<input id="adjust_value" type="text" name="adjust_value" value="{{ old('adjust_value') }}" class="form-control" >
					</div>
					@if($errors->has('adjust_value'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('adjust_value') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('adjust_comment') ? ' has-error' : '' }}">
					<label for="adjust_comment" class="col-sm-3 control-label">{{ trans('data.comment') }}</label>
					<div class="col-sm-6">
						<input id="adjust_comment" type="text" name="adjust_comment" value="{{ old('adjust_comment') }}" class="form-control" >
					</div>
					@if($errors->has('adjust_comment'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('adjust_comment') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-6">
						<input class="btn btn-primary form-control" type="submit" value="{{ trans('management.add_adjustment') }}">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>