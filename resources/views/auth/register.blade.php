@extends('layouts.app')

@section('content')

    <!-- ====== Banner Start ====== -->
    <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>Register your Account</h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Banner End ====== -->

    <!-- ====== Sign up Start ====== -->
    <section class="ud-register">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">

            <div class="ud-register-wrapper">
              <div class="ud-register-logo">
                <img src="assets/images/logo/logo_w_text_final.png" alt="logo" />
              </div>
              <form class="ud-register-form row">
                <div class="ud-form-group col-6">
                  <input
                    type="text"
                    name="fname"
                    placeholder="First name"
                  />
                </div>
                <div class="ud-form-group col-6">
                  <input
                    type="text"
                    name="lname"
                    placeholder="Last name"
                  />
                </div>

                <div class="ud-form-group col-6">
                  <input
                    type="text"
                    name="Phonenumner"
                    placeholder="Phone number"
                  />
                </div>
                <div class="ud-form-group col-6">
                  <input
                    type="text"
                    name="Phonenumner"
                    placeholder="Telephone number"
                  />
                </div>
                <div class="ud-form-group col-6">
                  <input
                    type="email"
                    name="email"
                    placeholder="Email Address"
                  />
                </div>
                <div class="ud-form-group col-6">
                  <input
                    type="password"
                    name="password"
                    placeholder="Enter password"
                  />
                </div>
                <div class="ud-form-group col-6">
                  <input
                    type="text"
                    name="state"
                    placeholder="State"
                  />
                </div>
                <div class="ud-form-group col-6">
                  <input
                    type="text"
                    name="zipcode"
                    placeholder="Zipcode"
                  />
                </div>
                <div class="ud-form-group">
                  <button type="submit" class="ud-main-btn w-100">Sign Up</button>
                </div>
              </form>

              <div class="ud-socials-connect">
                <p>Register With</p>

                <ul>
                  <li>
                    <a href="javascript:void(0)" class="facebook">
                      <i class="lni lni-facebook-filled"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="twitter">
                      <i class="lni lni-twitter-filled"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)" class="google">
                      <i class="lni lni-google"></i>
                    </a>
                  </li>
                </ul>
              </div>

              <a class="forget-pass" href="javascript:void(0)">
                Forget Password?
              </a>
              <p class="signup-option">
                Existing account? <a href="javascript:void(0)"> Sign In </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Sign up End ====== -->

@endsection
