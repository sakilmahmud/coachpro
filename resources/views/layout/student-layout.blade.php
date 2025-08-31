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
      <h1><a href="{{ route('student.dashboard') }}" class="logo"><img src="https://coachproconsulting.com/image/logo-new.png" alt="CoachPro Consulting Logo" style="max-width: 100%; height: auto;"></a></h1>
      <ul class="list-unstyled components mb-5">
        <li class="{{ Request::routeIs('student.dashboard') ? 'active' : '' }}">
          <a href="{{ route('student.dashboard') }}"><span class="fa fa-book mr-3"></span> Dashboard</a>
        </li>
        <li class="{{ Request::routeIs('student.courses') ? 'active' : '' }}">
          <a href="{{ route('student.courses') }}"><span class="fa fa-book mr-3"></span> My Courses</a>
        </li>
        <li class="{{ Request::routeIs('student.mock.tests') ? 'active' : '' }}">
          <a href="{{route('student.mock.tests')}}"><span class="fa fa-list-alt mr-3"></span> Mock Tests</a>
        </li>
        <li class="{{ Request::routeIs('student.mock.tests.attempted') ? 'active' : '' }}">
          <a href="{{route('student.mock.tests.attempted')}}"><span class="fa fa-list-alt mr-3"></span> Review Attempted Tests</a>
        </li>
        <!-- Study Material -->
        <li class="menu-item {{ Request::routeIs('student.study-materials.pdfs') || Request::routeIs('student.study-materials.videos') ? 'active' : '' }}" id="studyMaterial">
          <a href="#"><span class="fa fa-tasks mr-3"></span> Study Material</a>
          <ul class="list-unstyled components sub-menu">
            <li class="sub-menu-item">
              <a href="{{route('student.study-materials.pdfs')}}" class="{{ Request::routeIs('student.study-materials.pdfs') ? 'active' : '' }}">
                <span class="fa fa-book mr-3" style="margin-left: 30px;"></span> Pdfs
              </a>
            </li>
            <li class="sub-menu-item">
              <a href="{{route('student.study-materials.videos')}}" class="{{ Request::routeIs('student.study-materials.videos') ? 'active' : '' }}">
                <span class="fa fa-book mr-3" style="margin-left: 30px;"></span>Videos
              </a>
            </li>
            <!-- Add more study materials as needed -->
          </ul>
        </li>
        <li class="{{ Request::routeIs('flash.card') ? 'active' : '' }}">
          <a href="{{route('flash.card')}}"><span class="fa fa-tasks mr-3"></span> Flash Card</a>
        </li>
        <li class="{{ Request::routeIs('student.profile') ? 'active' : '' }}" style="display: none;">
            <a href="{{ route('student.profile') }}"><span class="fa fa-user mr-3"></span> My Profile</a>
        </li>
        <li class="{{ Request::routeIs('student.change-password') ? 'active' : '' }}" style="display: none;">
            <a href="{{ route('student.change-password') }}"><span class="fa fa-key mr-3"></span> Change Password</a>
        </li>
        <li class="{{ Request::routeIs('query.text') ? 'active' : '' }}">
          <a href="{{route('query.text')}}"><span class="fa fa-tasks mr-3"></span> Query</a>
        </li>
        <li>
          <a href="/logout"><span class="fa fa-sign-out mr-3"></span> Logout</a>
        </li>
      </ul>

    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px; align-items: center;">
        @auth
        @if(Auth::user()->image)
            <img src="{{ asset('images/'.Auth::user()->image) }}" alt="Profile Picture" class="img-thumbnail rounded-circle" width="40" height="40">
        @endif
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