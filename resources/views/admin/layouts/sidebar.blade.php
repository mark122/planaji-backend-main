<aside class="main-sidebar">
  <?php
  $dashboard = (request()->is('dashboard') || request()->is('dashboard/*') ||request()->is('dashboard/*/*'))?true:false;
  $participants = (request()->is('participants') || request()->is('participants/*') || request()->is('participants/*/*'))?true:false;
  $service_providers = (request()->is('service-providers') || request()->is('service-providers/*') || request()->is('service-providers/*/*'))?true:false;
  $support_coordinators = (request()->is('support-coordinators') || request()->is('support-coordinators/*') || request()->is('support-coordinators/*/*'))?true:false;
  $invoices = (request()->is('invoices') || request()->is('invoices/*') || request()->is('invoices/*/*'))?true:false;
  $pricing = (request()->is('pricing') || request()->is('pricing/*') ||request()->is('pricing/*/*'))?true:false;
  $reconciliation = (request()->is('reconciliation') || request()->is('reconciliation/*') ||request()->is('reconciliation/*/*'))?true:false;
  $users = (request()->is('users') || request()->is('users/*') || request()->is('users/*/*'))?true:false;


  $user_data= auth()->user();

  ?>
  <section class="sidebar">
      <!-- Sidebar user panel -->
      {{-- <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('assets/admin/dist/img/kaushik.png')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Kaushik Saksena</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div> --}}
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="{{ ($dashboard) ? 'active' : '' }}">
          <a href="{{ url('/dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="{{ ($participants) ? 'active' : '' }}">
          <a href="{{ url('/participants')}}">
            <i class="fa fa-wheelchair-alt"></i> <span>Participants</span>
          </a>
        </li>
        <li class="{{ ($service_providers) ? 'active' : '' }}">
          <a href="{{ url('/service-providers')}}">
            <i class="fa fa-user-md"></i> <span>Service Providers</span>
          </a>
        </li>
        <li class="{{ ($support_coordinators) ? 'active' : '' }}">
          <a href="{{ url('/support-coordinators')}}">
            <i class="fa fa-id-card"></i> <span>Support Coordinators</span>
          </a>
        </li>
        <li class="{{ ($invoices) ? 'active' : '' }}">
          <a href="{{ url('/invoices')}}">
            <i class="fa fa-file-text"></i> <span>Invoices</span>
          </a>
        </li>
        <li class="{{ ($pricing) ? 'active' : '' }}">
          <a href="{{ url('/pricing')}}">
            <i class="fa fa-id-card"></i> <span>Pricing Guide</span>
          </a>
        </li>
        <li class="{{ ($reconciliation) ? 'active' : '' }}">
          <a href="{{ url('/reconciliation')}}">
            <i class="fa fa-files-o"></i> <span>Reconciliation</span>
          </a>
        </li>
        <?php if($user_data->id==1){?>
          <li class="{{ ($users) ? 'active' : '' }}">
            <a href="{{ url('/users')}}">
              <i class="fa fa-users"></i> <span>User accounts</span>
            </a>
          </li>
        <?php } ?>
        {{-- <li class="{{ (request()->is('reconciliation')) ? 'active' : '' }}">
          <a>
            <i class="fa fa-file-text"></i> <span>Reconciliation</span>
          </a>
        </li> --}}
        <!-- <li class="{{ (request()->is('invoicesa')) ? 'active' : '' }}">
          <a>
            <i class="fa fa-file-text"></i> <span>Statements</span>
          </a>
        </li> -->
        {{-- <li class="{{ (request()->is('users')) ? 'active' : '' }}">
          <a href="{{ url('/users')}}">
            <i class="fa fa-users"></i> <span>Users</span>
          </a>
        </li> --}}
      </ul>
    </section>
</aside>
