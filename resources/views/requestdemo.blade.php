@extends('layouts.app')

@section('content')

    <!-- ====== Banner Start ====== -->
    <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>REQUEST DEMO</h1>
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
            <!-- @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            
    
            @if(session('success'))

            <div class="ud-request-demo-wrapper">
              <p><img src="{{asset('assets/images/brands/icons8-check-64.png')}}" alt="check" /> <br> {{session('success')}}</p>
              <div class="ud-request-demo-logo">
              </div>          
            </div>
         
 
            @else  
            <div class="ud-request-demo-wrapper">
              <p>Leave your details here, we will call you at any moment and offer you the best option you want.</p>
              <div class="ud-request-demo-logo">
              </div>
                <form class="ud-request-demo-form row" action="{{ route('demodata') }}" method="post">
                  @csrf
                <div class="ud-form-group">
                  <input
                    type="text"
                    name="firstname"
                    placeholder="Full name"
                    required
                  />
                </div>
                <div class="ud-form-group">
                  <input
                    type="text"
                    name="company"
                    placeholder="Company Name"
                    required
                  />
                </div>
                <div class="ud-form-group">
                  <input
                    type="text"
                    name="email"
                    placeholder="Email Address"
                    required
                  />
                </div>
                <div class="ud-form-group">
                  <input
                    type="text"
                    name="website"
                    placeholder="Website"
                    required
                  />
                </div>
                <div class="ud-form-group">
                  <input
                   type="text" 
                   name="contact" 
                  placeholder="Contact Number"
                  required
                  />
                </div>
                <div class="ud-form-group">
                  <button type="submit" class="ud-main-btn w-100">Submit</button>
                </div>
              </form>
            </div>
            @endif
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
