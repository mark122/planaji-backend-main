@extends('admin.layouts.app')

@section('content')

<style>
  .label-new {
    background: linear-gradient(to bottom left, #faec70 10%, #b7d411 90%);
  }

  .label-verified {
    background: linear-gradient(to bottom left, #59ff99 10%, #2ac450 90%);
  }

  .label-unverified {
    background: linear-gradient(to bottom left, #f76e6e 10%, #ec2f2f 90%);
  }

  .label-pending {
    background: linear-gradient(to bottom left, #61b8ff 10%, #3963ee 90%);
  }

  .label-open {
    background: linear-gradient(to bottom left, #f3a974 10%, #e29219 90%);
  }

  .label-paid {
    background: linear-gradient(to bottom left, #f363e7 10%, #891294 90%);
  }

  .label-dispute {
    background: linear-gradient(to bottom left, #8d6767 10%, #683a50 90%);
  }
</style>

<?php
$connection = $this->connection = auth()->user()['connection']; // returns user

$planmanger = DB::connection($connection)->table('participants')->whereNull('participants.deleted_at')->where('planmanager_subscriptions_id', Auth::user()->plan_manager_subscription_id)->get();
$serviceprovider = DB::connection($connection)->table('service_providers')->whereNull('service_providers.deleted_at')->where('planmanager_subscriptions_id', Auth::user()->plan_manager_subscription_id)->get();
$supportcoordinators = DB::connection($connection)->table('support_coordinators')->whereNull('support_coordinators.deleted_at')->where('planmanager_subscriptions_id', Auth::user()->plan_manager_subscription_id)->get();
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      {{-- <small>Control panel</small> --}}
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{count($planmanger)}}</h3>

            <p>Participants</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-stalker"></i>
          </div>
          <a href="/participants" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{count($serviceprovider)}}</h3>

            <p>Service Providers</p>
          </div>
          <div class="icon">
            <i class="ion ion-medkit"></i>
          </div>
          <a href="/service-providers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>{{count($supportcoordinators)}}</h3>

            <p>Support Coordinators</p>
          </div>
          <div class="icon">
            <i class="ion ion-chatbox-working"></i>
          </div>
          <a href="/support-coordinators" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{($invoicesCount != null) ? $invoicesCount[0]->count : 0}}</h3>

            <p>Invoices</p>
          </div>
          <div class="icon">
            <i class="ion ion-clipboard"></i>
          </div>
          <a href="/invoices" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <section class="col-lg-7 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->

        <!-- TO DO List -->
        <div class="box box-primary" style="min-height: 439px;">
          <div class="box-header">
            <i class="ion ion-clipboard"></i>

            <h3 class="box-title" style="color: #333 !important;">Invoices list</h3>

            {{-- <div class="box-tools pull-right">
                <ul class="pagination pagination-sm inline">
                  <li><a href="#">&laquo;</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">&raquo;</a></li>
                </ul>
              </div> --}}
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
            <ul class="todo-list">
              @foreach($invoices as $invoice)
              <li>
                <!-- drag handle -->
                <span class="handle">
                  <i class="fa fa-ellipsis-v"></i>
                  <i class="fa fa-ellipsis-v"></i>
                </span>
                <!-- checkbox -->
                {{-- <input type="checkbox" value=""> --}}
                <!-- todo text -->
                <span class="text">Invoice Number: {{$invoice->invoice_number}}</span>
                <!-- Emphasis label -->
                @if($invoice->status == '1')
                <small class="label label-new">New</small>

                @elseif($invoice->status == '2')
                <small class="label label-verified">Verified</small>

                @elseif($invoice->status == '3')
                <small class="label label-unverified">Unverified</small>

                @elseif($invoice->status == '4')
                <small class="label label-pending">Pending</small>

                @elseif($invoice->status == '5')
                <small class="label label-open">Open</small>

                @elseif($invoice->status == '6')
                <small class="label label-paid">Paid</small>

                @elseif($invoice->status == '7')
                <small class="label label-dispute">Dispute</small>

                @endif
                <!-- General tools such as edit or delete-->
                {{-- <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                  </div> --}}
              </li>
              @endforeach
            </ul>
          </div>
          <div class="box-footer clearfix">
            <a href="/invoices" class="btn btn-sm btn-default btn-flat pull-right">View All Invoices</a>
          </div>
          <!-- /.box-body -->
          {{-- <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
            </div>
          </div> --}}
          <!-- /.box -->


      </section>
      <!-- /.Left col -->
      <!-- right col (We are only adding the ID to make the widgets sortable)-->
      <section class="col-lg-5 connectedSortable">

        <!-- Map box -->
        <div class="box box-solid bg-light-blue-gradient">
          <div class="box-header">
            <!-- tools box -->
            <div class="pull-right box-tools">
              <button type="button" class="btn btn-primary btn-sm daterange pull-right map-buttons" data-toggle="tooltip" title="Date range">
                <i class="fa fa-calendar"></i></button>
              <button type="button" class="btn btn-primary btn-sm pull-right map-buttons" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                <i class="fa fa-minus"></i></button>
            </div>
            <!-- /. tools -->

            <!-- <i class="fa fa-map-marker"></i> -->

            <h3 class="box-title">
              Australia Map
            </h3>
          </div>
          <div class="box-body" style="height: 308px">
            <!-- <i class="fa fa-map-marker" style="position: absolute;right: 390px;top: 152px;color: #FB5792;z-index: 1;"></i>
              <i class="fa fa-map-marker" style="position: absolute;right: 328px;top: 152px;color: #8A6CED;z-index: 1;"></i>
              <i class="fa fa-map-marker" style="position: absolute;right: 328px;top: 152px;color: #8A6CED;z-index: 1;"></i> -->
            {{-- <p>Participants</p></i> --}}
            <!-- <i class="fa fa-map-marker" style="position: absolute;right: 268px;top: 131px;color: #60CD73;z-index: 1;"></i>
              <i class="fa fa-map-marker" style="position: absolute;right: 318px;top: 97px;color: #8A6CED;z-index: 1;"></i>
              <i class="fa fa-map-marker" style="position: absolute;right: 323px;top: 180px;color: #60CD73;z-index: 1;"></i>
              <i class="fa fa-map-marker" style="position: absolute;right: 250px;top: 200px;color: #FB5792;z-index: 1;"></i> -->
            <div id="world-map2" style="height: 400px; width: 100%; margin-top: -15px;"></div>
          </div>
          <!-- /.box-body-->
          <div class="box-footer no-border">
            <div class="row">
              <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                <div id="sparkline-1"></div>
                <div class="knob-label">Participants</div>
              </div>
              <!-- ./col -->
              <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                <div id="sparkline-2"></div>
                <div class="knob-label">Service Providers</div>
              </div>
              <!-- ./col -->
              <div class="col-xs-4 text-center">
                <div id="sparkline-3"></div>
                <div class="knob-label">Support Coordinators</div>
              </div>
              <!-- ./col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!-- /.box -->

      </section>
      <!-- right col -->
    </div>
    <!-- /.row (main row) -->
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title" style="color: #333 !important;">Invoices Email List
          @if($invoiceEmailAddress != null)
          @if($invoiceEmailAddress[0]->invoice_email != null)
          ({{$invoiceEmailAddress[0]->invoice_email}})
          @else
          (0)
          @endif
          @endif

          <span style="float: right; margin-left: 550px;">Total: {{($invoiceEmailCount != null) ? $invoiceEmailCount[0]->count : 0}}</span>
        </h3>

      </div>
      <div class="box-body">
        <?php if($isAuthenticated) {?>
        <table class="table table-bordered data-table display nowrap table-example1">
          <thead>
            <tr>
              {{-- <th>Select</th> --}}
              {{-- <th>Invoice Email</th> --}}
              <th>Email From</th>
              <th>Subject</th>
              <th>Body</th>
              <th>Received Date</th>
              <th>Attachment</th>
            </tr>
          </thead>
          <tbody>
            @if($invoiceEmail)
            @foreach ($invoiceEmail as $email)
            <tr>
              {{-- <td>{{$email->invoice_email}}</td> --}}
              <td>{{$email->from_email}}</td>
              <td>{{$email->subject}}</td>
              <td>{{$email->body}}</td>
              <td>{{$email->received_date}}</td>
              <td>
                @if($email->attachment != "")
                <a style="display: block" href="{{ route('dashboard.download',$email->id) }}" target="_blank">Download</a>
                @endif
                @if($email->attachment2 != "")
                <a style="display: block" href="{{ route('dashboard.download',$email->id) }}" target="_blank">Download (2)</a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        </tbody>
        </table>
        <div style="text-align: center; margin: 15px">No Data</div>
        @endif
      </div>
      <div class="box-footer text-center">
        <a href="/dashboard/emails" class="uppercase">View All Emails</a>
      </div>

      <?php } else { ?>

        <h4 class="text-center">Something went wrong.</h4>

      <?php } ?>

      <!-- /.box-body -->
    </div>

  </section>
  <!-- /.content -->
</div>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="dist/js/pages/dashboard.js"></script> --}}
<!-- jQuery 3 -->
<script src="assets/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/admin/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script type="text/javascript" src="assets/admin/dist/js/pages/dashboard.js"></script>

<!-- Example Map Above  -->
<script src="assets/admin/map/jquery.vmap.js"></script>
<script src="assets/admin/map/maps/continents/jquery.vmap.australia.js"></script>
{{-- <script src="assets/admin/map/jquery.vmap.sampledata.js"></script> --}}

<script>
  // jvectormap data
  var gdpData = {
    'AU-SA': '#4E7387',
    'AU-WA': '#333333',
    'AU-VIC': '#89AFBF',
    'AU-TAS': '#817F8E',
    'AU-QLD': '#344B5E',
    'AU-NSW': '#344B5E',
    'AU-ACT': '#344B5E',
    'AU-NT': '#344B5E'
  };

  jQuery('#world-map2').vectorMap({
    map: 'au_mill',
    scaleColors: ['#fff', '#fff'],
    normalizeFunction: 'polynomial',
    hoverOpacity: 0.5,
    hoverColor: false,
    zoomOnScroll: true,
    backgroundColor: 'transparent',
    onRegionTipShow: function(e, el, code) {
      el.html(el.html() + ' (GDP - ' + gdpData[code] + ')');
    }
  });
</script>
@endsection