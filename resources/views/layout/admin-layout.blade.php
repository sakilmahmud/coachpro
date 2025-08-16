<!doctype html>
<html lang="en">

<head>
  <title>CoachPro Admin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/x-icon" href="{{ asset('image/fevicon1.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="{{ asset('js/multiselect-dropdown.js') }}"></script>
  <style>
    .multiselect-dropdown {
      width: 100% !important;
    }
  </style>
  <!--    <script>-->
  <!-- // Disable right-click on the page -->
  <!--document.addEventListener("contextmenu", function(e) {-->
  <!--    e.preventDefault();-->
  <!--});-->
  <!--</script>-->
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
      <h1><a href="/admin/dashboard" class="logo">Admin Dashboard</a></h1>
      <ul class="list-unstyled components mb-5">
        <li class="active">
          <a href="/admin/dashboard"><span class="fa fa-book mr-3"></span>Batches</a><i class="fa-solid fa-people-group"></i>
        </li>
        <!-- <li class="active">
            <a href="/admin/exam"><span class="fa fa-tasks mr-3"></span> Exams</a>
          </li> -->
        <!-- <li class="active">
            <a href="{{ route('packageDashboard') }}"><span class="fa fa-gift mr-3"></span> Exam Packages</a>
          </li> -->
        <!-- <li class="active">
            <a href="/admin/marks"><span class="fa fa-check mr-3"></span> Marks</a>
          </li> -->
        <li class="active">
          <a href="/admin/qna-ans"><span class="fa fa-question-circle mr-3"></span>Mock Test</a>
        </li>
        <li class="active">
          <a href="/admin/students"><span class="fa fa-graduation-cap mr-3"></span> Students</a>
        </li>
        <li class="active">
          <a href="/admin/flash"><span class="fa fa-question-circle mr-3"></span>Flash Cards</a>
        </li>
        <li class="active">
          <a href="/admin/studentquery"><span class="fa fa-info mr-3"></span>View Student Query</a>
        </li>
        <li>
          <a href="/logout"><span class="fa fa-sign-out mr-3"></span> Logout</a>
        </li>
      </ul>
    </nav>
    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-5 pt-5">
      @yield('space-work')
    </div>
  </div>
  <!-- <script src="{{ asset('js/jquery.min.js') }}"></script> -->
  <script src="{{ asset('js/popper.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>