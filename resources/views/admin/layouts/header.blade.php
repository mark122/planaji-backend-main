<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Planaji</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/skins/_all-skins.min.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/morris.js/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/Content.css')}}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!--====== Favicon Icon ======-->
  <link rel="shortcut icon" href="{{asset('assets/images/logo/favicon.png')}}" type="image/png" />

  <link rel="stylesheet" href="{{asset('assets/admin/bk/css/font-face.css')}}" />

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" />
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/select2/dist/css/select2.min.css')}}" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <?php
  if (auth()->user()->role == "Plan Manager") {
    $planmanger = DB::table('planmanager_subscriptions')->where('id', auth()->user()->plan_manager_subscription_id)->first();
  }

  ?>
  <style>
    .box-participant-header {
      background-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#d83c51" ?>;
      color: <?php echo isset($planmanger->dashboard_side_color)  ?  "black" :  "white" ?>;
    }

    .skin-blue .sidebar-menu>li.active>a,
    .skin-blue .sidebar-menu>li.menu-open>a {
      color: #fff;
      background: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "black" ?>;
    }

    .skin-blue .main-header .logo {
      background: <?php echo isset($planmanger->header_color)  ?  "$planmanger->header_color" :  "white" ?>;
    }

    .skin-blue .main-header .logo:hover {
      background: <?php echo isset($planmanger->header_color)  ?  "$planmanger->header_color" :  "white" ?>;
    }

    .skin-blue .main-header .navbar .sidebar-toggle {
      color: <?php echo isset($planmanger->header_color)  ?  "white" :  "black" ?>;
    }

    .skin-blue .main-header .navbar .sidebar-toggle:hover {
      background: rgba(0, 0, 0, 0.1);
    }

    .skin-blue .main-header .navbar .nav>li>a {
      color: <?php echo isset($planmanger->header_color)  ?  "white" :  "black" ?>;
    }


    .skin-blue .main-header .navbar {
      background: <?php echo isset($planmanger->header_color)  ?  "$planmanger->header_color" :  "white" ?>;
    }

    .skin-blue .sidebar-menu>li:hover>a {
      color: <?php echo auth()->user()->name == 'axial'  ?  "black" :  "white" ?>;
      background: <?php echo isset($planmanger->secondary_color)  ?  "$planmanger->secondary_color" :  "black" ?>;
    }

    .btn-success {
      background-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#00a65a" ?>;
      border-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#008d4c" ?>;
    }

    .btn-success:hover {
      background-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#00a65a" ?>;
      border-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#008d4c" ?>;
    }

    .btn-success:focus {
      background-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#00a65a" ?> !important;
      border-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#008d4c" ?> !important;
    }

    .btn-success:active {
      background-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#00a65a" ?> !important;
      border-color: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#008d4c" ?> !important;
    }

    .box-header-custom {
      background-color: <?php echo isset($planmanger->secondary_color)  ?  "$planmanger->secondary_color" :  "#d83c51" ?> !important;
      color: <?php echo auth()->user()->name == 'axial'  ?  "black" :  "white" ?>;
    }

    .user-header {
      background: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#d83c51" ?> !important;
    }

    .bg-light-blue-gradient {
      background: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#3c8dbc" ?> !important;
    }

    .map-buttons {
      background: <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->dashboard_side_color" :  "#3c8dbc" ?> !important;
    }

    .box.box-primary {
      border-top-color:  <?php echo isset($planmanger->dashboard_side_color)  ?  "$planmanger->header_color" :  "#242627" ?> !important;
    }

    .box-title {
      color:  <?php echo isset($planmanger->fontheader_color)  ?  "$planmanger->fontheader_color" :  "#242627" ?> !important;
    }
  </style>

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" />
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('assets/admin/bower_components/select2/dist/css/select2.min.css')}}" />
  <link rel="stylesheet" href="{{asset('assets/css/jqvmap.css')}}" />
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="{{ url('/dashboard')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
          <img src="{{asset('assets/admin/dist/img/heart.png')}}" class="img-square" alt="User Image">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
          <img src="{{ isset($planmanger->custom_logo) ? url($planmanger->custom_logo) : url('assets/images/logo/logo_planaji.svg') }}" class="img-rectangle" alt="User Image">
        </span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Notifications: style can be found in dropdown.less -->
            <!-- <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning">10</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 10 notifications</li>
                <li>
                   inner menu: contains the actual data -->
            <!-- <ul class="menu">
                    <li>
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                        page and may cause design problems
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-users text-red"></i> 5 new members joined
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <i class="fa fa-user text-red"></i> You changed your username
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="footer"><a href="#">View all</a></li>
              </ul>
            </li> -->
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ isset(auth()->user()->profile_image) ? asset(auth()->user()->profile_image) : asset('assets/admin/dist/img/avatar5.png')}}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{auth()->user()->name}}</span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="{{ isset(auth()->user()->profile_image) ? asset(auth()->user()->profile_image) : asset('assets/admin/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">

                  <p>
                    {{auth()->user()->name}} - {{auth()->user()->role}}
                    {{-- <small>Member since Nov. 2012</small> --}}
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                  <div class="row">
                    <div class="col-xs-6 text-center userMenu">
                      <a href="{{ route('account') }}">Account</a>
                    </div>
                    <!-- <div class="col-xs-4 text-center">
                      <a href="#">Billing</a>
                    </div> -->
                    <div class="col-xs-6 text-center userMenu">
                      <a href="{{ route('settings') }}">Settings</a>
                    </div>
                  </div>
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->
                  <div class="pull-right">
                    <!-- <a href="{{ url('/login')}}" class="btn btn-default btn-flat">Sign out</a> -->
                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                    </form>

                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>