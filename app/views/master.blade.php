<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);
        @import url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css);
	</style>
</head>
<body>
    <div class="content">
       <header>
           @yield('header')
       </header>

        @yield('content')

        <footer>
            @yield('footer')

            <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        </footer>
    </div>
</body>
</html>
