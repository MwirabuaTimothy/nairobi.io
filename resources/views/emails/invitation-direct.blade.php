@extends('emails/layouts/default')

@section('content')

<p>Hi {!! $user->first_name !!},</p>

You have been invited by <b>{!! $auth->name() !!}</b> to collaborate on the {{ $item['type'] }} below:

<h2>
	<a href="{{ env('APP_URL').'/'.$item['type'].'s/'.$item['slug'] }}">
		{{ $item['title'] }}
	</a>
</h2>

<p>Click through to start collaborating.</p>

Warm Regards,
<br/>
<a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>

@stop
