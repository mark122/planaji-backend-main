@extends('layouts.app')

@section('content')

    <!-- ====== Banner Start ====== -->
    <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>Privacy Policy</h1>
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
              <p>In this Privacy policy, ‘us’, ‘we’ or ‘our’ means Planaji. We are committed to respecting your privacy. Our Privacy Policy sets out how we collect, use, store and disclose your personal information. We are bound by the Australian Privacy Principles contained in the Privacy Act 1988 (Cth) (the Privacy Act).</p>
              
              <br>
              <b>What personal data we collect and why we collect it​</b>
              <p>
                When visitors leave comments on the site, we collect the data shown in the comments form, and the visitor’s IP address and browser user agent string to help spam detection.
              </p>

              <br>
              <b>Media​</b>
              <p>
                If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.
              </p>

              <br>
              <b>Cookies</b>
              <p>
                If you leave a comment on our site, you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select “Remember Me”, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.
              </p>

              <br>
              <b>Embedded content from other websites</b>
              <p>
                Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.
              </p>

              <br>
              <b>Who we share your data with?</b>
              <p>
                We will never share your data with anyone unless you give us permission to do so. If you request a password reset, your IP address will be included in the reset email.
              </p>

              <br>
              <b>How long we retain your data</b>
              <p>
                If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.
              </p>

              <br>
              <b>What rights you have over your data</b>
              <p>
                If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.
              </p>
              <br>
              <p>
                Please contact us if you have any questions about or services or privacy policy.
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
