@extends('emails/layouts/default')

@section('content')
<p>{!! $data['body'] !!}</p>
<p>From "{{ $data['reply_name'] }}" , {{ $data['reply_email'] }}</p>
@stop
