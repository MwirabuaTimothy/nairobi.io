<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<!--<![endif]-->

<html class="no-js">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="TechyTimo">
		<meta name="keyword" content="Nairobi IO">
		<link rel="shortcut icon" href="../favicon.ico">

		<title>
		    @section('title')
		        Chippp | Dashboard
		    @show
		</title>

		<!-- Bootstrap core CSS -->
	    {{ HTML::style(All::dashboard().'css/bootstrap.min.css') }}  
	    {{ HTML::style(All::dashboard().'css/bootstrap-reset.css') }} 

	    <!--external css-->
		{{ HTML::style(All::dashboard().'assets/font-awesome/css/font-awesome.css') }}  
		{{ HTML::style(All::dashboard().'assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}  
		{{ HTML::style(All::dashboard().'css/owl.carousel.css') }} 

		<!-- template css -->
		{{ HTML::style(All::dashboard().'css/style.css') }}  
		{{ HTML::style(All::dashboard().'css/style-responsive.css') }}  

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->

		@yield('css')
		
	</head>

	
	<body>
		<!-- Container Section starts here -->
		<section id="container" class="">

			@include('partials.header')
			@include('partials.sidebar')
			@yield('content')
			@include('partials.footer')

		</section>
		<!-- Container section ends here -->

	    <!-- js placed at the end of the document so the pages load faster -->
	    {{ HTML::script(All::dashboard().'js/jquery.js') }}
	    {{ HTML::script(All::dashboard().'js/bootstrap.js') }}
	    {{ HTML::script(All::dashboard().'js/jquery.dcjqaccordion.2.7.js') }}
	    {{ HTML::script(All::dashboard().'js/jquery.scrollTo.min.js') }}
	    {{ HTML::script(All::dashboard().'js/jquery.nicescroll.js') }}
	    {{ HTML::script(All::dashboard().'js/respond.min.js') }}


		@yield('js')

	</body>

</html>