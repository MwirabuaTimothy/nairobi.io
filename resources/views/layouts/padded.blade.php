@extends('articles.blog')

@section('sub-content')

    <!-- article -->
	<section class="article">

	    <div class="panel">

	        <div class="panel-heading">
	          	<h2 class="title text-center"><span class="icon-sun-2"></span></h2>
	        </div>

	        <div class="panel-body">

	            <div class="body">
	                @yield('padded')
	            </div>

	        </div>
	      
	    </div>
	    <!--tab nav start--> 
	                   
	</section>
	<!-- page end-->

@stop

