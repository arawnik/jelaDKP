@extends('layouts.master')

@section('content')
@if($errors->first('login'))
	<div class="alert alert-info" role="alert">{{ $errors->first('login') }}</div>
@else
	<div class="alert alert-info" style="visibility: hidden;" role="alert">&nbsp;</div>
@endif
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('common.login') }}</div>
                <div class="panel-body">
                    <form action="{{ route('authenticate') }}" class="form-horizontal" role="form" method="POST">
                        {{ csrf_field() }}
						
						<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">{{ trans('auth.username') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>

                                @if($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">{{ trans('auth.password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">{{ trans('common.login') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
