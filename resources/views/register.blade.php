
@extends('layout/layout-common')

@section('space-work')

   <div class="container text-center" style="margin-top:150px; background-color:#FFF8DC">
   <img src="{{ asset('image/logost.png') }}" alt="Image"  width="20%" height="20%">
   <h3>Register</h3>

@if($errors->any())
    @foreach($errors->all() as $error)
    <p style="color:red;">{{ $error }}</p>
    @endforeach
@endif

<form action="{{ route('studentRegister') }}" method="POST">
    @csrf

    <input type="text"  class="w-50" name="name" placeholder="Enter Name">
    <br><br>
    <input type="email"  class="w-50" name="email" placeholder="Enter Email">
    <br><br>
    <input type="password"  class="w-50" name="password" placeholder="Enter Password">
    <br><br>
    <input type="password"  class="w-50" name="password_confirmation" placeholder="Enter Confirm Password">
    <br><br>
    <input type="submit" class="btn btn-success" value="Register">

</form>

@if(Session::has('success'))
    <p style="color:green;">{{ Session::get('success') }}</p>
@endif
<br>
   </div>

@endsection