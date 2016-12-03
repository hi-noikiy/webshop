@extends('email.templates.widgets')

@section('content')
    @include('email.templates.widgets.articleStart')

        <h4 class="secondary"><strong>Test mail</strong></h4>
        <p>Dit is een test email, als deze goed onvangen is, dan staan alle instellingen goed!</p>

    @include('email.templates.widgets.articleEnd')
@endsection