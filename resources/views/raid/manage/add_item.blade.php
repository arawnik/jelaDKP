<div class="col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading">{{ trans('management.add_item') }}</div>
		<div class="panel-body">
			<form action="{{ route('create_raid_item') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
				{{ csrf_field() }}
				<input type="hidden" id="raid_id" name="raid_id" value="{{ $raid_id }}">
				
				<div class="form-group{{ $errors->has('character') ? ' has-error' : '' }}">
					<label for="character" class="col-sm-3 control-label">{{ trans('data.character') }}</label>
					<div class="col-sm-6">
						<select class="form-control" id="character" name="character">
							@foreach ($characters as $character)
								<option value="{{ $character->char_id}}" @if ($character->char_id == old('character')) selected @endif>{{ $character->char_name}}</option>
							@endforeach
						</select>
					</div>
					@if($errors->has('character'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('character') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('use_amount') ? ' has-error' : '' }}">
					<label for="use_amount" class="col-sm-3 control-label">{{ trans('data.item_price') }}</label>
					<div class="col-sm-6">
						<input id="use_amount" type="text" name="use_amount" value="{{ old('use_amount') }}" class="form-control" >
					</div>
					@if($errors->has('use_amount'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('use_amount') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('use_desc') ? ' has-error' : '' }}">
					<label for="use_desc" class="col-sm-3 control-label">{{ trans('data.item_name') }}</label>
					<div class="col-sm-6">
						<input id="use_desc" type="text" name="use_desc" value="{{ old('use_desc') }}" class="form-control" >
					</div>
					@if($errors->has('use_desc'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('use_desc') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-6">
						<input class="btn btn-primary form-control" type="submit" value="{{ trans('management.add_item') }}">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>