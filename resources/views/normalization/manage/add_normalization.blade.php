<div class="col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading">{{ trans('management.add_normalization') }}</div>
		<div class="panel-body">
			<form action="{{ route('create_normalization') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
				{{ csrf_field() }}
				<input type="hidden" name="user" id="user" value="{{ $id }}">
				
				<div class="form-group{{ $errors->has('percent') ? ' has-error' : '' }}">
					<label for="percent" class="col-sm-3 control-label">{{ trans('management.percent') }}</label>
					<div class="col-sm-3">
						<input id="percent" type="text" name="percent" value="{{ old('percent') }}" class="form-control" >
					</div>
					<label for="percent" class="col-sm-1">%</label>
					@if($errors->has('percent'))
					<div class="col-sm-5">
						<span class="help-block"><strong>{{ $errors->first('percent') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
					<label for="comment" class="col-sm-3 control-label">{{ trans('management.comment') }}</label>
					<div class="col-sm-6">
						<input id="comment" type="text" name="comment" value="{{ old('comment') }}" class="form-control" >
					</div>
					@if($errors->has('comment'))
					<div class="col-sm-3">
						<span class="help-block"><strong>{{ $errors->first('comment') }}</strong></span>
					</div>
					@endif
				</div>
				
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-6">
						<input class="btn btn-primary form-control" type="submit" value="{{ trans('management.add_normalization') }}">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>