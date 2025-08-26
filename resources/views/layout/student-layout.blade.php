<!doctype html>
<html lang="en">

<head>
  <title>CoachPro LMS</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="{{ asset('image/fevicon1.png') }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>

  <div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
      <div class="custom-menu">
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
          <i class="fa fa-bars"></i>
          <span class="sr-only">Toggle Menu</span>
        </button>
      </div>
      <h1><a href="/dashboard" class="logo"><img src="https://coachproconsulting.com/image/logo-new.png" alt="CoachPro Consulting Logo" style="max-width: 100%; height: auto;"></a></h1>
      <ul class="list-unstyled components mb-5">
        <li class="active">
          <a href="/dashboard"><span class="fa fa-book mr-3"></span> Dashboard</a>
        </li>
        <li>
          <a href="{{route('student.mock.tests')}}"><span class="fa fa-list-alt mr-3"></span> Mock Tests</a>
        </li>
        <!-- Question Reviews -->
        <li class="menu-item" id="questionReview">
          <a href="#"><span class="fa fa-list-alt mr-3"></span> Question Review</a>
          <ul class="list-unstyled components sub-menu">
            <li class="sub-menu-item">
              <a href="{{route('review.test1')}}"><span class="fa fa-list-alt mr-3" style="margin-left: 30px;"></span> Review Test 1</a>
            </li>
            <li class="sub-menu-item">
              <a href="{{route('review.test2')}}"><span class="fa fa-list-alt mr-3" style="margin-left: 30px;"></span> Review Test 2</a>
            </li>
            <li class="sub-menu-item">
              <a href="{{route('review.test3')}}"><span class="fa fa-list-alt mr-3" style="margin-left: 30px;"></span> Review Test 3</a>
            </li>
            <li class="sub-menu-item">
              <a href="{{route('review.test4')}}"><span class="fa fa-list-alt mr-3" style="margin-left: 30px;"></span> Review Test 4</a>
            </li>
            <li class="sub-menu-item">
              <a href="{{route('review.test5')}}"><span class="fa fa-list-alt mr-3" style="margin-left: 30px;"></span> Review Test 5</a>
            </li>
          </ul>
        </li>
        <!-- Study Material -->
        <li class="menu-item" id="studyMaterial">
          <a href="#"><span class="fa fa-tasks mr-3"></span> Study Material</a>
          <ul class="list-unstyled components sub-menu">
            <li class="sub-menu-item">
              <a href="{{route('study.pdf')}}">
                <span class="fa fa-book mr-3" style="margin-left: 30px;"></span> Pdfs
              </a>
            </li>
            <li class="sub-menu-item">
              <a href="{{route('study.video')}}">
                <span class="fa fa-book mr-3" style="margin-left: 30px;"></span>Videos
              </a>
            </li>
            <!-- Add more study materials as needed -->
          </ul>
        </li>
        <li>
          <a href="{{route('flash.card')}}"><span class="fa fa-tasks mr-3"></span> flash Card</a>
        </li>
        <li>
          <a href="{{route('query.text')}}"><span class="fa fa-tasks mr-3"></span> Query</a>
        </li>
        <li>
          <a href="/logout"><span class="fa fa-sign-out mr-3"></span> Logout</a>
        </li>
      </ul>

    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px;">
        @auth
        <p style="margin-bottom: 0;">{{ Auth::user()->email }}</p>
        <p style="margin-bottom: 0;">({{ Auth::user()->is_admin == 1 ? 'Admin' : 'Student' }})</p>
        @endauth
      </div>
      @yield('space-work')
    </div>
  </div>
  <script>
    $(document).ready(function() {
      // By default, hide all sub-menus
      $('.sub-menu').hide();

      // When a top-level menu item is clicked, toggle its sub-menu
      $('.menu-item').click(function() {
        $(this).find('.sub-menu').toggle();
        // Hide other sub-menus
        $('.menu-item').not(this).find('.sub-menu').hide();
      });

      // Hide sub-menus when any sub-menu item is clicked
      $('.sub-menu-item').click(function() {
        $('.sub-menu').hide();
      });
    });
  </script>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</body>

</html>