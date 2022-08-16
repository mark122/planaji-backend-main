@extends('layouts.app')

@section('content')

    <!-- ====== Banner Start ====== -->
    <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>RESET PASSWORD</h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Banner End ====== -->

    <!-- ====== Sign up Start ====== -->
    <section class="ud-request-demo">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
           
            
           
              <h5>Successfully reset password. Please Sign In again.</h5>

            
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Sign up End ====== -->
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
