<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Planaji - NDIS Plan Management Software </title>

  <!-- Primary Meta Tags -->
  <meta name="title" content="Planaji - NDIS Plan Management Software">
  <meta name="description" content="Planaji is a Plan Management Software that will allow NDIS Plan Managers to help their Participants manage their NDIS budget by providing support, invoice processing activities, producing/maintaining statements and managing budget limits.">

   
 <!-- Open Graph -->
 <meta property="og:type" content="website">
 <meta property="og:url" content="http://planaji.com/">
 <meta property="og:title" content="Planaji - NDIS Plan Management Software">
 <meta property="og:description" content="Planaji is a Plan Management Software that will allow NDIS Plan Managers to help their Participants manage their NDIS budget by providing support, invoice processing activities, producing/maintaining statements and managing budget limits.">
 <meta property="og:image" content="{{asset('assets/images/logo/thumbnail_planaji.png')}}">
 


  <!--====== Favicon Icon ======-->
  <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.png')}}" type="image/png" />

  <!-- ===== All CSS files ===== -->
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/admin/bk/css/font-face.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/animate.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/lineicons.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/ud-styles.css')}}" />


</head>

<body style="background: #DDFFC2;">
<section class="ud-login" >
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-login-wrapper" style="border-radius: 6px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
              <!-- <div class="ud-login-logo">
                <img src="assets/images/logo/logo_planaji.svg" alt="logo" />
              </div> -->
              <form class="ud-login-form" method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}
		              <div class="ud-form-group">
                    <img src="{{asset('assets/images/enterpriselogin/axial.png')}}" alt="Logo" class="mb-4" />
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
                  <button style="background: #FF7302;" type="submit" class="ud-main-btn w-100">Login</button>
                </div>
              </form>
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

</body>

</html>
