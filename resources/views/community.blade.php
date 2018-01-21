@extends('layouts.master')

@section('content')

    <section id="intro" class="page">

        <img src="/img/bg/nairobi.jpg" class="w100"/>
        
        <h1 class="top">Connect &</h1>
        <h1 class="bottom">Interact</h1>

    </section>

    <section id="folio" class="page">

        <section class="section padding-bottom-off">

            <div class="container">

                <div class="row">

                    <div class="col-xs-12">
                        <hgroup class="section-title align-center">
                            <h1>Coming soon...</h1>
                        </hgroup>                           
                    </div>

                </div><!--/ .row-->
            </div><!--/ .container-->
        
        </section><!--/ .section-->

    </section><!--/ .page-->

    <!-- @ include('about.intro') -->

    <!-- @ include('about.mission') -->

    <!-- @ include('about.team') -->

    <!-- @ include('about.partners') -->

@stop

@section('css')
@stop

@section('js')
    <script src="/plugins/jquery.queryloader2.js"></script>
    <script src="/plugins/jquery.easing.1.3.min.js"></script>
    <script src="/js/custom.js"></script>
@stop
