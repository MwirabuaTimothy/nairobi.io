@extends('emails/layouts/default')

@section('content')
<p>Hello {{ $user->first_name }},</p>

<p>Please click on the following link to updated your password:</p>

<p><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>

<p>Best regards,</p>

<p>{{ env('APP_NAME') }} Team</p>
@stop
