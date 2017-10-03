<div class="row" id="attendanceDiv">
	<form action="{{ route('update_raid_attendance') }}" accept-charset="UTF-8" method="POST" class="form-horizontal">
		{{ csrf_field() }}
		<input type="hidden" id="raid_id" name="raid_id" value="{{ $raid_id }}">
		
		<h2>{{ trans('management.modify_attendance') }}</h2>
		<div class="col-md-5">
			<h3>{{ trans('management.chars_not_selected') }}</h3>
			<select multiple id="characters" name="characters[]">
				@foreach ($not_in_raid as $character)
					<option value="{{ $character->char_id}}">{{ $character->char_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="col-md-2">
		<button id="add" class="btn btn-primary">{{ trans('management.add') }}</button>
		<button id="remove" class="btn btn-primary">{{ trans('management.remove') }}</button>
		</div>
		<div class="col-md-5">
			<h3>{{ trans('management.chars_selected') }}</h3>
			<select multiple id="selected_chars" name="selected_chars[]">
				@foreach ($raid_attends as $character)
					<option value="{{ $character->attend_character}}">{{ $character->char_name}}</option>
				@endforeach
			</select>
		</div>
		<div class="row">
			<input class="col-sm-offset-3 col-sm-6 btn btn-primary" type="submit" value="Update Raid Attendance" onclick="selectAllOptions('selected_chars');">
		</div>
	</form>
</div>