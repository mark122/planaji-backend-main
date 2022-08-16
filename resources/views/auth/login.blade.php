@extends('layouts.app')

@section('content')

    <!-- ====== Banner Start ====== -->
    <!-- <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>Log in your Account</h1>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- ====== Banner End ====== -->

    <!-- ====== Login Start ====== -->
<br/><br/>
<section class="ud-login">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-login-wrapper">
              <!-- <div class="ud-login-logo">
                <img src="assets/images/logo/logo_planaji.svg" alt="logo" />
              </div> -->
              <form class="ud-login-form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
		              <div class="ud-form-group">
                  <input id="email" type="email" placeholder="Email address" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <div class="ud-form-group">
                   <input id="password" placeholder="*********" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <div class="ud-form-group">
                  <button type="submit" class="ud-main-btn w-100">Login</button>
                  <!--<a type="submit" class="ud-main-btn w-100" href="{{ url('/admindashboard')}}"> Login </a>-->
                </div>
              </form>
              <p style="font-size:12px;">
                  <a style="color: #3a58f8" href="{{ url('/forgotpassword')}}">Forgot Password</a>
                </p>
              <p class="signup-option">
                Not a member yet? <a style="color: #3a58f8" href="{{ url('/requestdemo')}}">Request Demo</a>
              </p>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- ====== Login End ====== -->
<!-- ====== All Javascript Files ====== -->
<script>
 
 document.addEventListener("DOMContentLoaded", function() {
   window.addEventListener('scroll', function() {
     if (window.scrollY > 50) {
       document.getElementById('navbar_top').classList.add('fixed-top');
       // add padding top to show content behind navbar
       navbar_height = document.querySelector('.navbar').offsetHeight;
       document.body.style.paddingTop = navbar_height + 'px';
     } else {
       document.getElementById('navbar_top').classList.remove('fixed-top');
       // remove padding top from body
       document.body.style.paddingTop = '0';
     }
   });
 });
</script>

@endsection
