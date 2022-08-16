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

            
              <div class="ud-request-demo-wrapper" style="text-align:left;">
                <h6>Create new password here:</h6>
                <br>
                <ul style="list-style-type:square">
                  <li>Must be at least 6 characters in length</li>
                  <li>Must contain at least one lowercase letter</li>
                  <li>Must contain at least one uppercase letter</li>
                  <li>Must contain at least one digit</li>
                  <li>Must contain a special character</li>
                </ul>
                  <form class="ud-request-demo-form row" action="{{ route('resetpassword.reset') }}" method="post">
                    @csrf
                  <div class="ud-form-group">
                    <input
                      type="password"
                      name="get_token"
                      placeholder="token"
                      required
                      value="{{ @$get_token }}"
                      hidden
                    />
                  </div>
                  <div class="ud-form-group">
                    <input
                      type="email"
                      name="email"
                      placeholder="Enter registered email address"
                      required
                      value="{{ @$row->email }}"
                      readonly="readonly" 
                    />
                  </div>
                  <div class="ud-form-group">
                    <input
                      type="password"
                      name="new_password"
                      placeholder="Enter new password"
                      required
                    />
                  </div>
                  <div class="ud-form-group">
                    <input
                      type="password"
                      name="retype_new_password"
                      placeholder="Enter Retype new password "
                      required
                    />
                  </div>
                  <div class="ud-form-group">
                    <button type="submit" class="ud-main-btn w-100">Submit</button>
                  </div>
                </form>
              </div>
            
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
