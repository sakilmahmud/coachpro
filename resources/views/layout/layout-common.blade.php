<!DOCTYPE html>
<html lang="en">
<head>
    <title>CoachPro LMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="{{ asset('image/fevicon1.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Disable right-click on the page
        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
        });
    </script>
</head>
<body>
    @yield('space-work')
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>