<!DOCTYPE html>
<!--[if lte IE 8]>               <html class="ie8 no-js" lang="en">    <![endif]-->
<!--[if lte IE 10]>              <html class="ie10 no-js" lang="en">   <![endif]-->
<!--[if !IE]>-->                 <html class="not-ie no-js" lang="en"> <!--<![endif]-->
<head>

<head>
    <title>
        @section('title')
           Nairobi IO
        @show
    </title>

    <!-- Google Web Fonts
    ================================================== -->
    <link href='/fonts/julius.ttf' rel='stylesheet' type='text/css'>

    <!-- Meta -->
    <!-- Basic Page Needs
    ================================================== -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="TechyTimo">
    <meta name="keyword" content="Nairobi IO">

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="/assets/img/favicon.png">

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS
    ================================================== -->
    <link media="all" type="text/css" rel="stylesheet" href="/css/style.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="/css/grid.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="/css/layout.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="/css/fontello.css"/>
    <link media="all" type="text/css" rel="stylesheet" href="/css/animation.css"/>
    <link href="/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
        
    @yield('css')

    <!-- HTML5 Shiv
    ================================================== -->
    <script src="/plugins/modernizr.custom.js"></script>

</head> 
<body data-spy="scroll" data-target="#navigation" class="home">

    <!-- Facebook like box -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=525227957610582&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

   


    <!-- - - - - - - - - - - - - - Wrapper - - - - - - - - - - - - - - - - -->

    <div id="wrapper">
        @include('partials.header')
        @yield('content')
    </div><!--/ #wrapper-->

    <!-- - - - - - - - - - - - - end Wrapper - - - - - - - - - - - - - - - -->

    @include('partials.footer')

    <!-- backtotop -->
    <div class="backtotop">
        <a href="#welcome" class="scroll">
            <span></span>
        </a>
    </div>
    <a href="#" id="back-top" title="Back To Top"></a>
    <!-- /backtotop -->

    <!-- JS Global Compulsory -->     
    <script src="/plugins/jquery.min.js"></script>
    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
   
    <!-- new flame -->
    <script src="/plugins/jquery.nicescroll.js"></script>
    <script src="/plugins/waypoints.min.js"></script>

    <!-- JS Page Level -->           
    <script src="/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="/js/app.js"></script>


    @yield('js')


    <script type="text/javascript">
        jQuery(document).ready(function() {
            App.init();
            App.scrollSweet();
            
         /* Vector Map */
        // var jvm_wm = new jvm.WorldMap({container: $('#africa'),
        $('#africa').vectorMap({
            map: 'world_mill_en', 
            backgroundColor: 'rgba(255, 255, 255, 0)',                                      
            regionsSelectable: true,
            regionStyle: {selected: {fill: '#B64645'},
                            initial: {fill: '#ddd'}},

            markerStyle: {initial: {fill: '#00c2a9',
                           stroke: '#00c2a9'}},
            // markers: [{latLng: [51.51, -0.13], name: 'London'},
            //           {latLng: [40.71, -74.00], name: 'New York'},
            //           {latLng: [-1.2833, 36.8167], name: 'Nairobi'}],
            markers: [{latLng: [-1.2833, 36.8167], name: 'Nairobi City'}],
            selectedRegions: ["KE"],
            focusOn: {
                  x: 0.52,
                  y: 0.65,
                  scale: 4
                },
            zoomOnScroll: false,
            zoomMax: 20,
            hoverColor: '#o9baff',
        });    

        /* END Vector Map */

        });
    </script>

    <!-- toastr stuff -->
    <link media="all" type="text/css" rel="stylesheet" href="/plugins/toastr/toastr.min.css"/>
    <script src="/plugins/toastr/toastr.min.js"></script>
    @include('partials.toastr')
    <!--[if lt IE 9]>
        <script src="/plugins/respond.js"></script>
    <![endif]-->
</body>

<!-- Common JS Libs -->

</html>
