@extends('layout/layout-common')

@section('space-work')
<style>
html, body {
    height: 100%;
    margin: 0;
    overflow: hidden;
}

    .background-image {
        background-image: url("{{ asset('image/5086999.jpg') }}");
        background-size: cover;
        background-position: center;
        width:100%;
        height: 100vh; /* Set the background to cover the entire viewport height */
    }

    .navbar {
        background-color: #ffffff;
        color: #333; /* Text color */
        padding: 0px 0; /* Increase the padding to increase the height of the navbar */
        width:100%;
        margin-bottom: 10px; /* Add margin at the bottom to push the content below the navbar */
    }

    .navbar img {
        max-width: 220px; /* Increase the logo size */
        margin-left: 80px; /* Add left margin to move the logo a bit to the right */
    }

   .login-box {
    background-color: rgba(248, 248, 248, 0.6); /* Use rgba to add transparency (0.9 means 90% opaque) */
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 30px;
    max-width: 600px;
    margin: 0 auto; /* Center the login box horizontally */
    margin-top: 90px; /* Adjust the top margin to push it below the navbar */
}

</style>

<div class="background-image">
    <div class="navbar">
    <img src="{{ asset('image/logost.png') }}" alt="Logo">
   <a href="https://coachproconsulting.com/" class="btn btn-info" style="margin-right: 80px; background-color: #EB7923;">Go to Home</a>

</div>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="login-box">
                <h1 style="text-align:center;">Forget Password</h1>
               @if($errors->any())
    @foreach($errors->all() as $error)
    <p style="color:red;">{{ $error }}</p>
    @endforeach
@endif

@if(Session::has('error'))
    <p style="color:red;">{{ Session::get('error') }}</p>
@endif

@if(Session::has('success'))
    <p style="color:green;">{{ Session::get('success') }}</p>
@endif

<form action="{{ route('forgetPassword') }}" method="POST">
    @csrf
    <!--<input type="email" class="w-50" name="email" placeholder="Enter Email" style="border-radius: 5px;">-->
   <div class="form-group">
    <label for="email" style="color:black;">Email</label>
    <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" style="border-radius: 10px; color: black;" required>
</div>

   
    <!--<input type="submit" class="btn btn-success" >-->
    <div class="forgetbutton"><button class="btn btn-success" value="Forget Password" type="submit" >Forget Password</button></div>

</form>

<div class="login"><a href="/" style=color:white;>Login</a></div></div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<style>
/* Existing styles... */

/* Added styles for centering the button */
.forgetbutton {
    display: flex;
    justify-content: center;
}

.btn-login {
    font-size: 14px;
    padding: 8px 16px;
}
.login {
    display: flex;
    justify-content: center;
}
</style>

@endsection
