<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>Nairobi IO @yield('title')</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="TechyTimo">
    <meta name="keyword" content="Nairobi IO">

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    
    <!-- CSS Global Compulsory-->
    {{ HTML::style('assets/plugins/bootstrap/css/bootstrap.min.css') }}

    {{ HTML::style('assets/css/style.css') }}
      
    <!-- CSS Implementing Plugins -->    
    {{ HTML::style('assets/plugins/font-awesome/css/font-awesome.css') }}

    <!-- CSS Page Style -->    
    {{ HTML::style('assets/css/pages/page_log_reg_v2.css') }}

    @yield('top')

</head> 

    <body>  

        @yield('account-content')

        <!-- Scripts -->
        {{ HTML::script('assets/plugins/jquery.js') }}

        <!-- JS Implementing Plugins -->  
        {{ HTML::script('assets/plugins/backstretch/jquery.backstretch.min.js') }}

        <!-- <img src="{{ asset('uploads/retailers/logos/image.png') }}" /> -->
        <script type="text/javascript">
            $.backstretch([
                // assets('assets/img/bg/5.jpg')
              "/assets/img/bg/nai-202.jpg",
              "/assets/img/bg/cheetah.jpg",
              "/assets/img/bg/nai-2.jpg",
              "/assets/img/bg/sunset.jpg",
              ], {
                fade: 1000,
                duration: 10000
            });
        </script>
        @yield('bottom')

        <!-- toastr stuff -->
        <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
        <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
        @include('partials.toastr')
    </body>
</html> 