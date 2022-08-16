@extends('layouts.app')

@section('content')

    <!-- ====== Banner Start ====== -->
    <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>Support Policy</h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Banner End ====== -->

    <section class="ud-privacy-policy">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          
            <div class="ud-privacy-policy-wrapper">
              <p>Planaji support is based on the following 3 subscription levels:</p>
              
              <br>
              <ul class="uldisc">
                <li>
                  Basic - support hours will be Monday – Friday 10am to 4pm AEST (unless otherwise stated in your agreement)
                </li>
                <li>
                Professional - support hours will be Monday – Friday 9am to 6pm AEST (unless otherwise stated in your agreement)
                </li>
                <li>
                Enterprise – Monday – Friday 6am to 6pm AEST (unless otherwise stated in your agreement)
                </li>
              </ul>
              <br>
              <p>
              Please talk to the Planaji sales team on sales@planaji.com if you require specific support arrangements that will suit your business and participants.
              </p>
              <br>
              <p>
              Planaji support team will make best efforts to respond to your support enquires within 4 business hours.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

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
