@extends('emails/layouts/default')

@section('content')
<p>Hello {{ $user->first_name }},</p>

<p>Welcome to {{ env('APP_NAME') }}! Please click on the following link to confirm your account:</p>

<p><a href="{{ env('APP_URL').'/auth/verify-email/'.$user->email_token }}">{{ env('APP_URL').'/auth/verify-email/'.$user->email_token }}</a></p>

<p>Best regards,</p>

<p>{{ env('APP_NAME') }} Team</p>
@stop
