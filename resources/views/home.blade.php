@extends('layouts.app')


@section('content')

<!-- ====== Hero Start ====== -->
<section class="ud-hero" id="home">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="ud-hero-content wow fadeInUp" data-wow-delay=".2s">
          <h1 class="ud-hero-title">
            PLAN MANAGEMENT
          </h1>
          <p class="ud-hero-desc">
            Planaji is a Plan Management Software that will allow NDIS Plan Managers to help their Participants manage their NDIS budget by providing support, invoice processing activities, producing/maintaining statements and managing budget limits.
          </p>
          <!--<ul class="ud-hero-buttons">
                <li>
                  <a href="https://links.uideck.com/play-bootstrap-download" rel="nofollow noopener" target="_blank" class="ud-main-btn ud-white-btn">
                    LEARN MORE
                  </a>
                </li>
              </ul>-->
        </div>
        <div class="ud-hero-brands-wrapper wow fadeInUp" data-wow-delay=".3s">
          <!--<img src="{{asset('assets//images/hero/brand.svg')}}" alt="brand" />-->
        </div>
        <div class="ud-hero-image wow fadeInUp" data-wow-delay=".25s">
          <img src="{{asset('assets//images/hero/dashboard_img.png')}}" alt="hero-image" />
          <img src="{{asset('assets//images/hero/dotted-shape.svg')}}" alt="shape" class="shape shape-1" />
          <img src="{{asset('assets//images/hero/dotted-shape.svg')}}" alt="shape" class="shape shape-2" />
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ====== Hero End ====== -->

<!-- ====== Features Start ====== -->
<section id="features" class="ud-features">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="ud-section-title">
          <h2>Main Features of Planaji</h2>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 col-lg-3 col-sm-6">
        <div class="ud-single-feature wow fadeInUp" data-wow-delay=".1s">
          <div class="ud-feature-icon">
            <i class="lni lni-credit-cards"></i>
          </div>
          <div class="ud-feature-content">
            <h3 class="ud-feature-title">Invoice Management</h3>
            <p class="ud-feature-desc">
              Receive and process invoices received from Service Providers
            </p>

          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-sm-6">
        <div class="ud-single-feature wow fadeInUp" data-wow-delay=".15s">
          <div class="ud-feature-icon">
            <i class="lni lni-layout"></i>
          </div>
          <div class="ud-feature-content">
            <h3 class="ud-feature-title">Plan Details</h3>
            <p class="ud-feature-desc">
              Manage Participants Plan details anywhere and anytime
            </p>

          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-sm-6">
        <div class="ud-single-feature wow fadeInUp" data-wow-delay=".2s">
          <div class="ud-feature-icon">
            <i class="lni lni-lock"></i>
          </div>
          <div class="ud-feature-content">
            <h3 class="ud-feature-title">Multi tier login</h3>
            <p class="ud-feature-desc">
              Allows relevant information to be easily shared with Service Providers and Support Coordinators
            </p>

          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-sm-6">
        <div class="ud-single-feature wow fadeInUp" data-wow-delay=".2s">
          <div class="ud-feature-icon">
            <i class="lni lni-user"></i>
          </div>
          <div class="ud-feature-content">
            <h3 class="ud-feature-title">Single View of Participant</h3>
            <p class="ud-feature-desc">
              All the Participants plan details in one single dashboard so that better care and support can be provided to them
            </p>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ====== Features End ====== -->

<!-- ====== Pricing Start ====== -->
<section id="pricing" class="ud-pricing">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="ud-section-title mx-auto text-center">
          <h2>Our Pricing Plans</h2>
        </div>
      </div>
    </div>

    <div class="row g-0 align-items-center justify-content-center">
      <div class="col-lg-4 col-md-6 col-sm-10">
        <div class="ud-single-pricing first-item wow fadeInUp" data-wow-delay=".15s" style="height: 560px">
          <div class="ud-pricing-header">
            <h3>BASIC</h3>
            <!--<h4>$ 19.99/mo</h4>-->
          </div>
          <div class="ud-pricing-body">
            <ul>
              <li>Up to 5 Operations Users</li>
              <li>Up to 50 Service Providers</li>
              <!-- <li>Up to 50 Support Coordinators</li> -->
              <li>Up to 50 Participants</li>
              <li>Support Hours
                <ul>
                  <li>Monday – Friday 9am to 6pm AEST</li>
                </ul>
              </li>
              <li>Hosted service includes the following environments:
                <ul>
                  <li>Production environment</li>
                  <li>Disaster Recovery environment</li>
                </ul>
              </li>
              </li>
            </ul>
          </div>
          <br /><br /><br />
          <div class="ud-pricing-footer">
            <a href="{{ url('/requestdemo')}}" class="ud-main-btn ud-border-btn">
              Request Demo
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-10">
        <div class="ud-single-pricing active wow fadeInUp" data-wow-delay=".1s">
          <span class="ud-popular-tag">PROFESSIONAL</span>
          <!-- <div class="ud-pricing-header">
            <h3>STARTING FROM</h3>
            <h4>$ 30.99/mo</h4>
          </div> -->
          
          <div class="ud-pricing-body">
          
            <ul>
              <li>Up to 20 Operations Users</li>
              <li>Up to 500 Service Providers</li>
              <li>Up to 500 Participants</li>
              <li>Support Hours
                <ul>
                  <li>Monday – Friday 9am to 6pm AEST</li>
                </ul>
              </li>
              <li>Hosted service includes the following environments:
                <ul>
                  <li>Production environment</li>
                  <li>Disaster Recovery environment</li>
                </ul>
              </li>
              </li>
            </ul>
          </div>
          <div class="ud-pricing-footer">
          <br/>
            <a href="{{ url('/requestdemo')}}" class="ud-main-btn ud-white-btn">
              Request Demo
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-10">
        <div class="ud-single-pricing last-item wow fadeInUp" data-wow-delay=".15s" style="height: 560px">
          <div class="ud-pricing-header">
            <h3>ENTERPRISE</h3>
            <!--<h4>$ 70.99/mo</h4>-->
          </div>
          <div class="ud-pricing-body">
            <ul>
              <li>Up to 100 Operations Users</li>
              <li>Unlimited Service Providers</li>
              <li>Unlimited Participants</li>
              <li>Support Hours
                <ul>
                  <li>Monday – Friday 24hrs</li>
                </ul>
              </li>
              <li>Custom Branding</li>
              <li>Private Cloud Infrastructure</li>
              <li>Hosted service includes the following environments:
                <ul>
                  <li>Production environment</li>
                  <li>Disaster Recovery environment</li>
                </ul>
              </li>
              </li>
            </ul>
          </div>
          <div class="ud-pricing-footer">
            <a href="{{ url('/requestdemo')}}" class="ud-main-btn ud-border-btn">
              Request Demo
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ====== Pricing End ====== -->

<!-- ====== FAQ Start ====== -->
<section id="faq" class="ud-faq">
  <div class="shape">
    <img src="{{asset('assets//images/faq/shape.svg')}}" alt="shape" />
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="ud-section-title text-center mx-auto">
          <h2>Any Questions? Answered</h2>
          <p>
            Please see below for any questions that you have, or you may send an email to <a href="mailto:sales@planaji.com">sales@planaji.com</a>.
          </p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="ud-single-faq wow fadeInUp" data-wow-delay=".1s">
          <div class="accordion">
            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne">
              <span class="icon flex-shrink-0">
                <i class="lni lni-chevron-down"></i>
              </span>
              <span>How do I register?</span>
            </button>
            <div id="collapseOne" class="accordion-collapse collapse">
              <div class="ud-faq-body">
                Request for a demo and enter the required details. Once finished, someone from Planaji will contact and guide you through the registration process.
              </div>
            </div>
          </div>
        </div>
        <div class="ud-single-faq wow fadeInUp" data-wow-delay=".15s">
          <div class="accordion">
            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
              <span class="icon flex-shrink-0">
                <i class="lni lni-chevron-down"></i>
              </span>
              <span>Is there a trial period?</span>
            </button>
            <div id="collapseTwo" class="accordion-collapse collapse">
              <div class="ud-faq-body">
                No there isn't a trial period but you can sign up on a month to month basis and if you feel you don't want to continue then you can cancel anytime. Please speak to a Planaji consultant if there is something the app is not doing and we will see if we can build it for you.
              </div>
            </div>
          </div>
        </div>
        <div class="ud-single-faq wow fadeInUp" data-wow-delay=".2s">
          <div class="accordion">
            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree">
              <span class="icon flex-shrink-0">
                <i class="lni lni-chevron-down"></i>
              </span>
              <span>Once I registered to a plan, can I change it within the period?</span>
            </button>
            <div id="collapseThree" class="accordion-collapse collapse">
              <div class="ud-faq-body">
                Yes. Once you have an ongoing subscription plan on Planaji, you can still upgrade or downgrade your subscription depending on your preference but you have to be aware on the inclusions of the subscription packages. For more guidance, you may reach out to <a href="mailto:sales@planaji.com">sales@planaji.com</a>.
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ud-single-faq wow fadeInUp" data-wow-delay=".1s">
          <div class="accordion">
            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour">
              <span class="icon flex-shrink-0">
                <i class="lni lni-chevron-down"></i>
              </span>
              <span>Can my participants log-in?</span>
            </button>
            <div id="collapseFour" class="accordion-collapse collapse">
              <div class="ud-faq-body">
                Yes. Your participants can log-in to Planaji app.
              </div>
            </div>
          </div>
        </div>
        <div class="ud-single-faq wow fadeInUp" data-wow-delay=".15s">
          <div class="accordion">
            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFive">
              <span class="icon flex-shrink-0">
                <i class="lni lni-chevron-down"></i>
              </span>
              <span>Can the nominees of the participants can log-in?</span>
            </button>
            <div id="collapseFive" class="accordion-collapse collapse">
              <div class="ud-faq-body">
                Yes. The nominees of the participants can log-in to Planaji app.
              </div>
            </div>
          </div>
        </div>
        <div class="ud-single-faq wow fadeInUp" data-wow-delay=".2s">
          <div class="accordion">
            <button class="ud-faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSix">
              <span class="icon flex-shrink-0">
                <i class="lni lni-chevron-down"></i>
              </span>
              <span>Can I use my own logo?</span>
            </button>
            <div id="collapseSix" class="accordion-collapse collapse">
              <div class="ud-faq-body">
                Yes, for enterprise subscription only.
                You can customize the application with your
                preferred logo as well as the look and feel
                of the system. You may refer to <a href="#pricing">Our Pricing</a> page
                for more details.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ====== FAQ End ====== -->

<!-- ====== Contact Start ====== -->
<section id="contact" class="ud-contact">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-xl-8 col-lg-7">
        <div class="ud-contact-content-wrapper">
          <div class="ud-contact-title">

            <h2>
              Let’s talk. <br />
              Love to hear from you!
            </h2>
          </div>
          <div class="ud-contact-info-wrapper">
            <div class="ud-single-info">
              <div class="ud-info-icon">
                <i class="lni lni-map-marker"></i>
              </div>
              <div class="ud-info-meta">
                <h5>Our Location</h5>
                <p>124 Railway Street, Granville, NSW, 2142, Australia</p>
              </div>
            </div>
            <div class="ud-single-info">
              <div class="ud-info-icon">
                <i class="lni lni-envelope"></i>
              </div>
              <div class="ud-info-meta">
                <h5>How Can We Help?</h5>
                <p> <a href="mailto:sales@planaji.com">sales@planaji.com</a></p>
                <p>+61 452 519 169</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-lg-5">
 

      <div class="ud-request-demo-wrapper" style="display: none" id="successsent">
        <p><img src="{{asset('assets/images/brands/icons8-check-64.png')}}" alt="check" /> <br> Thank you for your message. Somebody from Planaji will contact you ASAP.</p>
        <div class="ud-request-demo-logo">
        </div>          
      </div>



      <div class="ud-contact-form-wrapper wow fadeInUp" data-wow-delay=".2s" style="display: block"id="failedsend">
          <h3 class="ud-contact-form-title">Send us a Message</h3>
            <form class="ud-contact-form" id = "messageus">
              @csrf
            <div class="ud-form-group">
              <label for="fullName">Full Name*</label>
              <input type="text" name="fname" placeholder="Enter Full Name" required />
            </div>
            <div class="ud-form-group">
              <label for="email">Email*</label>
              <input type="email" name="email" placeholder="example@yourmail.com" required />
            </div>
            <div class="ud-form-group">
              <label for="phone">Phone*</label>
              <input type="text" name="phone" placeholder="+61 452 519 169" required/>
            </div>
            <div class="ud-form-group">
              <label for="message">Message*</label>
              <textarea name="message" rows="1" placeholder="type your message here" required></textarea>
            </div>
            <div class="ud-form-group mb-0">
              <!-- <button onclick="sendmessage()" type="button" class="ud-main-btn" required>
                Send Message
              </button> -->
              <button type="submit" class="ud-main-btn" id = "btnmessageus">
                Send Message
              </button>
            </div>
          </form>
        </div>
        

      </div>
    </div>
  </div>
</section>
<!-- ====== Contact End ====== -->
<!-- ====== About Start ====== -->
<section id="about" class="ud-about">
  <div class="container">
    <div class="ud-about-wrapper wow fadeInUp" data-wow-delay=".2s">
      <div class="ud-about-content-wrapper">
        <div class="ud-about-content">

          <h2>About Us</h2>
          <!-- <ul>
                <li>iOS App for iPhone and iPad which Participants and their Nominees can login to view their plan details, utilisations, etc.</li>       
                <br/><li>Web Based Dashboard for Operations users to manage:</li>
                  </br/>&nbsp;&nbsp;• Participants/Nominee Login
                  </br/>&nbsp;&nbsp;• Providers
                  <br/>&nbsp;&nbsp;• Invoices
                  <br/>&nbsp;&nbsp;• Upload and download CSV files for Quick Books and PRODA
              </ul> -->
          <p>
            The Planaji.com platform is designed and owned by the Zithera Group Pty Limited (ACN: 648689169). Zithera Group is an Australian IT Consulting Company which assists the grass-root consumers and businesses to leverage the current IT technology to boost their business needs and idea’s.

            <br /><br />Planaji was conceived by the Zithera Group team after being introduced to the wonderful world of NDIS, and they were immediately intrigued by the amazing work that everyone was doing to make the lives of the differently abled and their support networks around them.

            <br /><br />Since the inception the team has been driven by a single goal and purpose – to use technology to make the participants lives a little easier.
          </p>

        </div>
      </div>
      <div class="ud-about-image">
        <img id = "about-img" src="{{asset('assets/images/about/about-us-vector.png')}}" alt="about-image" />
        
      </div>
    </div>
  </div>
</section>
<!-- ====== About End ====== -->


@endsection



@section('script')

<!-- ====== All Javascript Files ====== -->
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/wow.min.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
  // ==== for menu scroll
  // const pageLink = document.querySelectorAll(".ud-menu-scroll");

  // console.log(pageLink);

  // pageLink.forEach((elem) => {
  //   elem.addEventListener("click", (e) => {
  //     e.preventDefault();
  //     document.querySelector(elem.getAttribute("href")).scrollIntoView({
  //       behavior: "smooth",
  //       offsetTop: 1 - 60,
  //     });
  //   });
  // });

  // // section menu active
  // function onScroll(event) {
  //   const sections = document.querySelectorAll(".ud-menu-scroll");
  //   const scrollPos =
  //     window.pageYOffset ||
  //     document.documentElement.scrollTop ||
  //     document.body.scrollTop;

  //   for (let i = 0; i < sections.length; i++) {
  //     const currLink = sections[i];
  //     const val = currLink.getAttribute("href");
  //     const refElement = document.querySelector(val);
  //     const scrollTopMinus = scrollPos + 73;
  //     if (
  //       refElement.offsetTop <= scrollTopMinus &&
  //       refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
  //     ) {
  //       document
  //         .querySelector(".ud-menu-scroll")
  //         .classList.remove("active");
  //       currLink.classList.add("active");
  //     } else {
  //       currLink.classList.remove("active");
  //     }
  //   }
  // }

  // window.document.addEventListener("scroll", onScroll);

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

  $("#messageus").submit(function(e) {
     e.preventDefault();
     let data = $('form').serializeArray();
 
     $.ajax({
         type: "post",
         url: "{{url('home')}}",
         data: data,
         beforeSend: function(e){
           $('#btnmessageus').text('Sending message now');
           $('#btnmessageus').prop('disabled', true);

           $('#btnmessageus').removeClass('ud-main-btn');
           $('#btnmessageus').addClass('btn btn-secondary');

           $('#btnmessageus').css("padding", "15px");
         },
         success: function(e){
   
           $('#btnmessageus').text('Message Sent');
           $('#btnmessageus').prop('disabled', true);

           $('#btnmessageus').removeClass('ud-main-btn');
           $('#btnmessageus').addClass('btn btn-secondary');

           $('#btnmessageus').css("padding", "15px");
         }
     });
     return;
   });

</script>

@endsection