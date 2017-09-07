<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Laravel Shopping Cart')</title>
    <meta name="description" content="Laravel Shopping Cart">

    <!-- Mobile Specific Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Store CSRF token for AJAX calls -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">	
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style_common.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/style6.css') }}" />	
    <link rel="stylesheet" type="text/css" href="{{ asset('css/demo.css') }}" />

    @yield('extra-css')

    <!-- Favicon and Apple Icons -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <style>

        .spacer {
            margin-bottom: 100px;
        }

        .cart-image {
            width: 100px;
        }

        footer {
            /*background-color: #f5f5f5;
            padding: 20px 0;*/
        }

        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .side-by-side {
            display: inline-block;
        }
    </style>
</head>
<body>

    <header>

        <nav class="navbar navbar-default navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <!--<a class="navbar-brand" href="{{ url('/') }}">Laravel Shopping Cart</a>-->
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li class="{{ set_active('shop') }}"><a href="{{ url('/') }}">Home</a></li>
                <!--<li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>-->

              </ul>
			  
              <ul class="nav navbar-nav navbar-right">			 
                <li class="{{ set_active('wishlist') }}"><a href="{{ url('/wishlist') }}">Wishlist ({{ Cart::instance('wishlist')->count(false) }})</a></li>
                <li class="{{ set_active('cart') }}"><a href="{{ url('/cart') }}">Cart ({{ Cart::instance('default')->count(false) }})</a></li>
				 <!-- Authentication Links -->
				@if (Auth::guest())
					<li><a href="{{ route('login') }}">Login</a></li>
					<li><a href="{{ route('register') }}">Register</a></li>
				@else
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							{{ Auth::user()->name }} <span class="caret"></span>
						</a>

						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
											 document.getElementById('logout-form').submit();">
									Logout
								</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						</ul>
					</li>
				@endif
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </nav>

    </header>

    @yield('content')

    <footer>
      <!--<div class="container">
        <p class="text-muted">Footer</p>
      </div>-->
    </footer>

<!-- JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

@yield('extra-js')

</body>
</html>
