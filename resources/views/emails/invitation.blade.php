@extends('emails/layouts/default')

@section('content')

<p>Hi {!! $user->first_name !!},</p>

You have been invited by <b>{!! $auth->name() !!}</b> to collaborate on the {{ $item['type'] }} below:

<h2>{{ $item['title'] }}</h2>

<p>Please click on the following link to confirm your account and set your password:</p>

<p>
	<a href="{{ env('APP_URL').'/auth/set-password/'.$user->email_token }}">
		{{ env('APP_URL').'/auth/set-password/'.$user->email_token }}
	</a>
</p>

Warm Regards,
<br/>
<a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>

@stop
