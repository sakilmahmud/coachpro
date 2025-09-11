<!doctype html>
<html lang="en">

<head>
  <title>CoachPro Admin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('image/fevicon1.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="{{ asset('js/multiselect-dropdown.js') }}"></script>
  <style>
    .multiselect-dropdown {
      width: 100% !important;
    }

    .dataTables_filter {
      padding-bottom: 10px;
    }

    table.dataTable {
      border-top: 1px solid;
    }
  </style>
   <!-- summernote css -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #f2761e;
        --secondary-color: #000;
        --light-gray: #f8f9fa;
        --dark-gray: #343a40;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--light-gray);
    }

    .content-wrapper {
        padding: 20px;
        background-color: var(--light-gray);
    }

    .page-title {
        color: var(--dark-gray);
        font-weight: 700;
        margin-bottom: 20px;
    }

    .custom-card {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        border: none;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 15px 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-gray);
    }

    .card-body {
        padding: 20px;
    }

    .action-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px; /* For responsiveness */
    }

    .btn-theme-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-theme-primary:hover {
        background-color: darken(var(--primary-color), 10%);
        border-color: darken(var(--primary-color), 10%);
        color: #fff;
    }

    .btn-theme-secondary {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-theme-secondary:hover {
        background-color: lighten(var(--secondary-color), 20%);
        border-color: lighten(var(--secondary-color), 20%);
        color: #fff;
    }

    .custom-nav-pills .nav-link {
        color: var(--dark-gray);
        font-weight: 500;
        border-radius: 5px;
        margin-right: 5px;
        transition: all 0.3s ease;
    }

    .custom-nav-pills .nav-link.active {
        background-color: var(--primary-color);
        color: #fff;
    }

    .custom-nav-pills .nav-link:hover:not(.active) {
        color: var(--primary-color);
    }

    .custom-table thead th {
        background-color: var(--dark-gray);
        color: #fff;
        border-color: var(--dark-gray);
    }

    .custom-table tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .custom-table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.07);
    }

    .custom-modal-content {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .custom-modal-header {
        background-color: var(--primary-color);
        color: #fff;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom: none;
    }

    .custom-modal-header .modal-title {
        color: #fff;
    }

    .custom-close-btn {
        color: #fff; /* Ensure close button is visible */
        opacity: 1;
        background: none;
        border: none;
        font-size: 1.5rem;
    }

    .custom-close-btn:hover {
        color: #eee;
    }

    .theme-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        transition: all 0.3s ease;
    }

    .theme-primary:hover {
        background-color: #da6a1b;
        border-color: #da6a1b;
        color: #fff;
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
      <h1><a href="/admin/dashboard" class="logo"><img src="https://coachproconsulting.com/image/logo-new.png" alt="CoachPro Consulting Logo" style="max-width: 100%; height: auto;"></a></h1>
      <ul class="list-unstyled components mb-5">
        <li class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"> <a href="{{ route('admin.dashboard') }}"><span class="fa fa-tachometer mr-3"></span> Dashboard</a> </li>
        <li class="{{ Request::routeIs('courses.*') ? 'active' : '' }}">
          <a href="{{ route('courses.index') }}"><span class="fa fa-book mr-3"></span> Courses</a>
        </li>
        <li class="{{ Request::routeIs('batches') || Request::routeIs('batchDetail') ? 'active' : '' }}"> <a href="{{ route('batches') }}"><span class="fa fa-users mr-3"></span> Batches</a></li>
        <li class="{{ Request::routeIs('mock-tests.*') || Request::routeIs('questions.*') ? 'active' : '' }}">
          <a href="{{ route('mock-tests.index') }}"><span class="fa fa-question-circle mr-3"></span> Mock Test</a>
        </li>
        <li class="{{ Request::is('admin/students') ? 'active' : '' }}">
          <a href="/admin/students"><span class="fa fa-graduation-cap mr-3"></span> Students</a>
        </li>
        <li class="{{ Request::is('admin/flash') ? 'active' : '' }}">
          <a href="/admin/flash"><span class="fa fa-question-circle mr-3"></span> Flash Cards</a>
        </li>
        <li class="{{ Request::is('admin/studentquery') ? 'active' : '' }}">
          <a href="/admin/studentquery"><span class="fa fa-info mr-3"></span> Student Queries</a>
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
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/main.js') }}"></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <!-- summernote js -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
  @stack('scripts')
</body>

</html>