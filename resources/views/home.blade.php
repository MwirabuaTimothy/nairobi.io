@extends('layouts.master')


@section('content')

    <!-- @ include('partials.intro') -->


    <section id="intro" class="page">

        <img src="/img/bg/nairobi.jpg"/>
        
        <h1 class="top">We Create</h1>

        <h1 class="bottom">Great Products</h1>

    </section>

    @include('partials.about')

    <!-- @ include('partials.details') -->

    @include('partials.folio')

    <!-- @ include('partials.partners') -->

    <!-- @ include('partials.testimonials') -->

    <!-- @ include('partials.team') -->

@stop


@section('css')

    <link href="/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
    <link href="/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />

@stop

@section('js')
    <!--[if lt IE 9]>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="/plugins/jquery.queryloader2.js"></script>
    <!-- <script src="/plugins/waypoints.min.js"></script> -->
    <script src="/plugins/jquery.easing.1.3.min.js"></script>
    <script src="/plugins/jquery.mixitup.js"></script>
    <script src="/plugins/fancybox/source/jquery.fancybox.pack.js"></script>

    <script src="/js/custom.js"></script>
@stop
