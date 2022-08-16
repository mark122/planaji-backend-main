<!DOCTYPE html>
<html lang="en">

  

<head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZWFDJ42DT8%22%3E"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-ZWFDJ42DT8');
</script>

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

<body>
  <!-- ====== Header Start ====== -->
  <header class="ud-header bg-light text-dark">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <nav id = "navbar_top" class="navbar navbar-expand-lg">
              <a class="navbar-brand" href="{{ url('/')}}">
              <img src="{{asset('assets/images/logo/logo_planaji.svg')}}" alt="Logo" />
            </a>
            <button class="navbar-toggler">
              <span class="toggler-icon"> </span>
              <span class="toggler-icon"> </span>
              <span class="toggler-icon"> </span>
            </button>

            <div class="navbar-collapse">
              <ul id="nav" class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="ud-menu-scroll text-dark" href="{{ url('/')}}" >Home</a>
                </li>

                <!--<li class="nav-item">
                    <a class="ud-menu-scroll text-dark" href="#about">About</a>
                  </li>-->
                <li class="nav-item">
                    <a class="ud-menu-scroll text-dark" href="{{ url('/home')}}#pricing">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="ud-menu-scroll text-dark" href="{{ url('/home')}}#contact" >Contact</a>
                </li>
                <li class="nav-item">
                    <a class="ud-menu-scroll text-dark" href="{{ url('/home')}}#about" >About Us</a>
                </li>
                <!-- <li class="nav-item nav-item-has-children ">
                    <a class="text-dark" href="javascript:void(0)"> Pages </a>
                    <ul class="ud-submenu">
                      <li class="ud-submenu-item">
                        <a href="about.html" class="ud-submenu-link ">
                          About Page
                        </a>
                      </li>
                      <li class="ud-submenu-item">
                        <a href="pricing.html" class="ud-submenu-link">
                          Pricing Page
                        </a>
                      </li>
                      <li class="ud-submenu-item">
                        <a href="contact.html" class="ud-submenu-link">
                          Contact Page
                        </a>
                      </li>
                      <li class="ud-submenu-item">
                        <a href="blog.html" class="ud-submenu-link">
                          Blog Grid Page
                        </a>
                      </li>
                      <li class="ud-submenu-item">
                        <a href="blog-details.html" class="ud-submenu-link">
                          Blog Details Page
                        </a>
                      </li>
                      <li class="ud-submenu-item">
                        <a href="login.html" class="ud-submenu-link">
                          Sign In Page
                        </a>
                      </li>
                      <li class="ud-submenu-item">
                        <a href="404.html" class="ud-submenu-link">404 Page</a>
                      </li>
                    </ul>
                  </li> -->
              </ul>
            </div>

            <div class="navbar-btn d-none d-sm-inline-block">
              <a href="{{ url('/login')}}" class="ud-main-btn ud-login-btn text-dark">
                Sign In
              </a>
              <a class="ud-main-btn ud-white-btn border-danger" href="{{ url('/requestdemo')}}">
                REQUEST DEMO
              </a>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ====== Header End ====== -->
  @yield('content')
  <!-- ====== Footer Start ====== -->
  <footer class="ud-footer wow fadeInUp" data-wow-delay=".15s">
    <div class="shape shape-1">
      <img src="{{asset('assets/images/footer/shape-1.svg')}}" alt="shape" />
    </div>
    <div class="shape shape-2">
      <img src="{{asset('assets/images/footer/shape-2.svg')}}" alt="shape" />
    </div>
    <div class="shape shape-3">
      <img src="{{asset('assets/images/footer/shape-3.svg')}}" alt="shape" />
    </div>
    <div class="ud-footer-widgets">
      <div class="container">
        <div class="row">
          <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="ud-widget">
              <a href="{{ url('/')}}" class="ud-footer-logo">
                <img src="{{asset('assets/images/logo/Logo_w_text_website_revised.png')}}" alt="logo" />
              </a>
              <p class="ud-widget-desc">
                We create digital experiences for Plan Managers, Participants and Nominees using technology.
              </p>
              <a class="ud-main-btn-footer ud-white-btn " href="{{ url('/requestdemo')}}">
                REQUEST DEMO 
              </a>

            </div>
          </div>
          <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6">
            <div class="ud-widget">
              <br /><br />
              <h5 class="ud-widget-title">About Us</h5>
              <ul class="ud-widget-links">
                <li>
                  <a href="{{ url('/home')}}">Home</a>
                </li>
                <li>
                  <a href="{{ url('/home')}}#features">Features</a>
                </li>
                <li>
                  <a href="{{ url('/home')}}#about">About</a>
                </li>
              </ul>
            </div>
          </div>
          
          <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6">
            <div class="ud-widget">
              <br /><br />
              <h5 class="ud-widget-title">Features</h5>
              <ul class="ud-widget-links">

                <li>
                    <a href="{{ url('/privacy-policy')}}">Privacy Policy</a>
                </li>
                <li>
                  <a href="{{ url('/support-policy')}}">Support Policy</a>
                </li>
                <li>
                    <a href="{{ url('/terms-of-service')}}">Terms of Service</a>
                </li>

              </ul>
            </div>

          </div>

          <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6">
            <div class="ud-widget">
              <br /><br />
              <h5 class="ud-widget-title">Our Pricing</h5>
              <ul class="ud-widget-links">
                <li>
                  <a href="{{ url('/home')}}#pricing">Basic</a>
                </li>
                <li>
                  <a href="{{ url('/home')}}#pricing">Professional</a>
                </li>
                <li>
                  <a href="{{ url('/home')}}#pricing">Enterprise</a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-xl-2 col-lg-4 col-md-6">
            <div class="ud-widget">
              <br /><br />
              <h5 class="ud-widget-title">Follow Us</h5>
              <ul class="ud-widget-socials">
                <li>
                  <a href="https://www.facebook.com/planaji" target="_blank">
                    <!-- <i class="lni lni-facebook-original"></i> -->
                    <img src="https://img.icons8.com/color/48/000000/facebook.png"/>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/planaji/" target="_blank">
                    <!-- <i class="lni lni-instagram-filled"></i> -->
                    <img src="https://img.icons8.com/color/48/000000/instagram-new--v1.png"/>
                  </a>
                </li>
                <li>
                  <a href="https://www.linkedin.com/in/kaushiksaksena/" target="_blank">
                    <!-- <i class="lni lni-linkedin-original"></i> -->
                    <img src="https://img.icons8.com/color/48/000000/linkedin.png"/>
                  </a>
                </li>
              </ul>
              <br/>
              <h5 style = "color: white">Send us an email</h5>
              <ul class="ud-widget-links">
                <li>
                  <a href="mailto:sales@planaji.com">sales@planaji.com</a>
                </li></ul>
            </div>


          </div>
          <div class="ud-footer-bottom">
            <div class="container">
              <div class="row">
                <div class="col-md-8">
                  <ul class="ud-footer-bottom-left">
                    <li>
                  <a href="{{ url('/privacy-policy')}}">Privacy Policy</a>
                    </li>
                    <li>
                  <a href="{{ url('/support-policy')}}">Support Policy</a>
                    </li>
                    <li>
                  <a href="{{ url('/terms-of-service')}}">Terms of Service</a>
                    </li>
                  </ul>
                </div>
                <div class="col-md-4">
                  <p class="ud-footer-bottom-right">
                    Designed and Developed by
                    <a href="https://zithera.com.au" rel="nofollow">Zithera</a>
                  </p>
                </div>

              </div>
            </div>
          </div>
  </footer>
  <!-- ====== Footer End ====== -->

  <!-- ====== Back To Top Start ====== -->
  <a href="#" class="back-to-top">
    <i class="lni lni-chevron-up"> </i>
  </a>
  <!-- ====== Back To Top End ====== -->

  @yield('script')

</body>

</html>