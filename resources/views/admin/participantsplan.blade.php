@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://unpkg.com/imask"></script>
<style>
  .dropzone-wrapper {
    border: 2px dashed #91b0b3;
    color: #92b0b3;
    position: relative;
    height: 150px;
  }

  .dropzone-desc {
    position: absolute;
    margin: 0 auto;
    left: 0;
    right: 0;
    text-align: center;
    width: 40%;
    top: 50px;
    font-size: 16px;
  }

  .dropzone,
  .dropzone:focus {
    position: absolute;
    outline: none !important;
    width: 100%;
    height: 150px;
    cursor: pointer;
    opacity: 0;
  }

  .dropzone-wrapper:hover,
  .dropzone-wrapper.dragover {
    background: #ecf0f5;
  }

  .preview-zone {
    text-align: center;
  }

  .preview-zone .box {
    box-shadow: none;
    border-radius: 0;
    margin-bottom: 0;
  }
</style>
@endsection

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <!-- <h1>
      Participants
    </h1>  -->
    <a href="{{ route('participant.profile',$participant_id) }}" style="margin:3px; float:left"><i class="fa fa-arrow-left margin-r-5"></i>Back to Plans</a>
    <ol class="breadcrumb">
      <li><a href="{{route('participants.loadrecords')}}"><i class="fa fa-file-text margin-r-5"></i> Participants</a></li>
      <li><a href="{{ route('participant.profile',$participant_id)}}">Plans</a></li>
      <li class="active">Budget Overview</li>
    </ol><br />
  </section>

  <!-- Main content -->
  <section class="content">
    @if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">
      {{ Session::get('message') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
    <div class="tab-participant-detail-header">
      <div class="col-md-4 col-md-4 col-md-4"> <b>First Name: </b> {{@$profile->firstname}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Last Name: </b> {{@$profile->lastname}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS Number: </b> {{@$profile->ndis_number}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS Start Date: </b> {{@$profile->ndis_plan_start_date}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS Review Date: </b> {{@$profile->ndis_plan_review_date}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS End Date: </b> {{@$profile->ndis_plan_end_date}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Managed Start Date: </b> {{@$plan->plan_date_start}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Total Plan Budget: </b> ${{number_format(@$plan->total_funding,2)}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Managed End Date: </b> {{@$plan->plan_date_end}}</div>
    </div>
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs nav-participant">
        <li class="active"><a href="#participant-plans" data-toggle="tab" aria-expanded="false">Plans</a></li>
        <li class=""><a href="#participant-about" data-toggle="tab" aria-expanded="true">Details</a></li>
        <li class=""><a href="#participant-providers" data-toggle="tab" aria-expanded="false">Service Providers</a></li>
        <li class=""><a href="#participant-coordinators" data-toggle="tab" aria-expanded="false">Support Coordinators</a></li>
        <li class=""><a href="#participant-invoices" data-toggle="tab" aria-expanded="false">Invoices</a></li>
        <li class=""><a href="#participant-plan-documents" data-toggle="tab" aria-expanded="false">Documents</a></li>
        <!-- <li class=""><a href="#participant-notes" data-toggle="tab" aria-expanded="false">Notes </a></li>
        <li class=""><a href="#participant-email" data-toggle="tab" aria-expanded="false">Email </a></li> -->
      </ul>
      <!-- /.tab-content -->
      <div class="tab-content">
        <!-- /.tab-pane -->
        <div class="active tab-pane" id="participant-plans">
          <div class="box-body">
            <div class="row" data-select2-id="15">
              <!-- <div class="box-header">
                <h3 class="box-title planHeader">Budget Overview</h3>
              </div> -->


              <div class="col-md-12">
                <div class="tab-participant-plans" id="tab-participant-plans">
                  <div class="col-md-4 col-md-4 col-md-4" id="totalAllocated-budget">
                    <h4 class="box-title" style="width:500px; color: #333 !important;"><b>Total Allocated: </b>$<span id="box-header-totalAllocated">{{(($newTotalAllocated >= 0 ) ? number_format($newTotalAllocated,2) : number_format(0, 2))}}</span></h4>
                  </div>
                  <div class="col-md-4 col-md-4 col-md-4" id="spent-budget">
                    <h4 class="box-title" style="width:500px; color: #333 !important;"><b>Total Delivered: </b>$<span id="box-header-totalDelivered">{{(($totalClaimed >= 0 ) ? number_format($totalClaimed,2) : number_format(0, 2))}}</span></h4>
                  </div>
                  <div class="col-md-4 col-md-4 col-md-4" id="remaining-budget">
                    <h4 class="box-title" style="width:500px; color: #333 !important;"><b>Remaining Allocated: </b>$<span id="box-header-remainingBudget">{{(($remaining_allocated >= 0 ) ? number_format($remaining_allocated,2) : number_format(0, 2))}}</span></h4>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4 col-md-4 col-md-4">
                    <div type="hidden" id="core-budget" value="{{$core_support_total_budget[0]->total_budget}}"></div>
                    <canvas id="chart_core" class="chart_plan"></canvas>
                    <!-- <h5 class="text-center" style="margin-left: -160px">Core Support </h5> -->
                  </div>
                  <div class="col-md-4 col-md-4 col-md-4">
                    <div type="hidden" id="capital-budget" value="{{$capital_total_budget[0]->total_budget}}"></div>
                    <canvas id="chart_capital" class="chart_plan"></canvas>
                    <!-- <h5 class="text-center" style="margin-left: -160px">Capital Support</h5> -->
                  </div>
                  <div class="col-md-4 col-md-4 col-md-4" id="canvas_capacity">
                    <div type="hidden" id="capacity-budget" value="{{$capacity_building_total_budget[0]->total_budget}}"></div>
                    <canvas id="chart_capacity" class="chart_plan"></canvas>
                    <!-- <div class="col-md-5">
                      <h5 class="text-center">Capacity Building </h5>
                    </div> -->
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="box-header">
                  <h1 class="box-title planHeader">Support Purpose</h1>
                  <a class="btn btn-success ml-5 addPlan-button" style="float:right" href="javascript:void(0)" id="addNewCategory"> Add Support</a>
                  <a class="btn btn-success ml-5 addPlan-button" style="float:right; margin-right:5px;" href="javascript:void(0)" id="generatepdf">Generate Statement</a>
                </div>

                <div class="box box-default collapsed-box box-core">
                  <div class="box-header">
                    <h3 class="box-title" id="box-core-header">Core Support: &nbsp ${{ number_format($core_support_total_budget[0]->total_budget,2)}}</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool box-tool-participants" data-widget="collapse"><i class="fa fa-plus"></i>
                      </button>
                    </div>
                  </div>

                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="crud-buttons">
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewCoreSupport"> View</a>
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteCoreSupport"> Delete</a>
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editCoreSupport"> Edit</a>
                      <!-- <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewParticipant"> Add Participant</a> -->
                    </div>
                    <table class="table table-bordered data-table-core-supports display nowrap table-example1" style="width:100%">
                      <thead>
                        <tr>
                          <th style="width: 10px" class="select-plans-header" style="text-align: center;">Select</th>
                          <th style="width: 10px">#</th>
                          <th style="width: 150px">Support Category</th>
                          <th style="width: 50px">SI</th>
                          <th style="width: 50px">Item Number</th>
                          <th style="width: 50px">Item Name</th>
                          <th style="width: 50px">QF</th>
                          <th style="width: 50px">SP Name</th>
                          <th style="width: 50px">Total Budget</th>
                          <th style="width: 50px">Remaining Budget</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>

                <div class="box box-default collapsed-box box-capital">
                  <div class="box-header">
                    <h3 class="box-title" id="box-capital-header">Capital Support: &nbsp ${{ number_format($capital_total_budget[0]->total_budget,2) }}</h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool box-tool-participants" data-widget="collapse"><i class="fa fa-plus"></i>
                      </button>
                    </div>

                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="crud-buttons">
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewCapitalSupport"> View</a>
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteCapitalSupport"> Delete</a>
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editCapitalSupport"> Edit</a>
                      <!-- <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewParticipant"> Add Participant</a> -->
                    </div>

                    <table class="table table-bordered data-table-capital display nowrap table-example1" style="width:100%">
                      <thead>
                        <tr>
                          <th style="width: 10px" class="select-plans-header" style="text-align: center;">Select</th>
                          <th style="width: 10px">#</th>
                          <th style="width: 150px">Support Category</th>
                          <th style="width: 50px">SI</th>
                          <th style="width: 50px">Item Number</th>
                          <th style="width: 50px">Item Name</th>
                          <th style="width: 50px">QF</th>
                          <th style="width: 50px">SP Name</th>
                          <th style="width: 50px">Total Budget</th>
                          <th style="width: 50px">Remaining Budget</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>

                <div class="box box-default collapsed-box box-capacity">
                  <div class="box-header">
                    <h3 class="box-title" id="box-capacity-header">Capacity Building: &nbsp ${{ number_format($capacity_building_total_budget[0]->total_budget, 2)}} </h3>

                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool box-tool-participants" data-widget="collapse"><i class="fa fa-plus"></i>
                      </button>
                    </div>

                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="crud-buttons">
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewCapacityBuilding"> View</a>
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteCapacityBuilding"> Delete</a>
                      <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editCapacityBuilding"> Edit</a>
                      <!-- <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewParticipant"> Add Participant</a> -->
                    </div>
                    <table class="table table-bordered data-table-capacity-building display nowrap table-example1" style="width:100%">
                      <thead>
                        <tr>
                          <th style="width: 10px" class="select-plans-header" style="text-align: center;">Select</th>
                          <th style="width: 10px">#</th>
                          <th style="width: 150px">Support Category</th>
                          <th style="width: 50px">SI</th>
                          <th style="width: 50px">Item Number</th>
                          <th style="width: 50px">Item Name</th>
                          <th style="width: 50px">QF</th>
                          <th style="width: 50px">SP Name</th>
                          <th style="width: 50px">Total Budget</th>
                          <th style="width: 50px">Remaining Budget</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>


              </div>
            </div>
          </div>
        </div>

        <!-- /.tab-pane -->
        <div class=" tab-pane" id="participant-about">
          <div class="box box-default collapsed-box box-about-me">
            <div class="box-header with-border box-participant-header">
              <h3 class="box-title">My Profile</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool box-tool-participants" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <strong><i class="fa fa-book margin-r-5"></i> About Me</strong>

              <p class="text-muted">
                {{@$profile->aboutme}}
              </p>

              <hr>

              <strong><i class="fa fa-calendar-check-o margin-r-5"></i> Date of Birth </strong>

              <p class="text-muted">{{@$profile->dateofbirth}}</p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Address </strong>

              <p class="text-muted">{{@$profile->address1}}, {{@$profile->address2}}, {{@$profile->state}}, {{@$profile->postcode}}</p>

              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i> Home number</strong>

              <p class="text-muted">
                {{@$profile->homenumber}}
              </p>

              <hr>

              <strong><i class="fa fa-mobile margin-r-5"></i> Mobile number</strong>

              <p class="text-muted">
                {{@$profile->phonenumber}}
              </p>

            </div>
            <!-- /.box-body -->
          </div>
          <div class="box box-default collapsed-box box-ndis-details">
            <div class="box-header with-border box-participant-header">
              <h3 class="box-title">NDIS Details</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool box-tool-participants" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> NDIS Number</strong>

              <p class="text-muted">
                {{@$profile->ndis_number}}
              </p>

              <hr>

              <strong><i class="fa fa-calendar-check-o margin-r-5"></i> NDIS Plan Start Date </strong>

              <p class="text-muted">{{@$profile->ndis_plan_start_date}}</p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> NDIS Plan Review Due Date </strong>

              <p class="text-muted">2021-12-30</p>

              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i> NDIS Plan End Date</strong>

              <p class="text-muted">
                {{@$profile->ndis_plan_end_date}}
              </p>

            </div>
            <!-- /.box-body -->
          </div>
          <div class="box box-default collapsed-box box-goals">
            <div class="box-header with-border box-participant-header">
              <h3 class="box-title">Goals</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool box-tool-participants" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Short Term</strong>
              <p class="text-muted">{{$profile->short_term_goals}}</p>
              <hr>
              <strong><i class="fa fa-calendar-check-o margin-r-5"></i> Long Term </strong>
              <p class="text-muted">{{$profile->long_term_goals}}
              </p>
            </div>
            <!-- /.box-body -->
          </div>
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane " id="participant-providers">
          <div class="box-body">
            <div class="crud-buttons">
              <!-- <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewServiceProvider"> View Service Provider</a> -->
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteParticipantServiceProvider"> Delete Service Provider</a>
              <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewParticipantServiceProvider"> Add Service Provider</a>
            </div>
            <table class="table table-bordered data-table-service-providers display nowrap table-example1" style="width:100%">
              <thead>
                <tr>
                  <th class="select-provider-header" style="text-align: center;">Select</th>
                  <th>Provider Type</th>
                  <th>Company Name</th>

                  <th>Contact Number</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

          </div>
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="participant-coordinators">
          <div class="box-body">
            <div class="crud-buttons">
              <!-- <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewServiceProvider"> View Service Provider</a> -->
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteSupportCoordinator"> Delete Support Coordinator</a>
              <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewSupportCoordinator"> Add Support Coordinator</a>
            </div>
            <table class="table table-bordered data-table-support-coordinators display nowrap table-example1" style="width:100%">
              <thead>
                <tr>
                  <th class="select-provider-header" style="text-align: center;">Select</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Contact Number</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

          </div>
        </div>

        <div class="tab-pane" id="participant-invoices">
          <div class="box-body">
            <table class="table table-bordered data-table-invoices display nowrap table-example1" style="width:100%">
              <thead>
                <tr>
                  <th>NDIS Number</th>
                  <th>Invoice Number</th>
                  <th>Invoice Date</th>
                  <!-- <th>Due Date</th>
                  <th>Reference Number</th> -->
                  <th>Provider ABN</th>
                  <th>Invoice Amount</th>
                  <th>SP Name</th>
                  <th>Status</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="participant-notes">
          <div class="box-body">

            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Participant Notes
                  <!-- <small>Simple and fast</small> -->
                </h3>
                <!-- tools box -->
                <div class="pull-right box-tools">
                  <button type="button" class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                    <i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-default btn-sm" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                    <i class="fa fa-times"></i></button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body pad">
                <form>
                  <ul class="wysihtml5-toolbar">
                    <li class="dropdown">
                      <a class="btn btn-default dropdown-toggle " data-toggle="dropdown">

                        <span class="glyphicon glyphicon-font"></span>

                        <span class="current-font">Normal text</span>
                        <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p" tabindex="-1" href="javascript:;" unselectable="on" class="wysihtml5-command-active">Normal text</a></li>
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" tabindex="-1" href="javascript:;" unselectable="on">Heading 1</a></li>
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" tabindex="-1" href="javascript:;" unselectable="on">Heading 2</a></li>
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" tabindex="-1" href="javascript:;" unselectable="on">Heading 3</a></li>
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h4" tabindex="-1" href="javascript:;" unselectable="on">Heading 4</a></li>
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h5" tabindex="-1" href="javascript:;" unselectable="on">Heading 5</a></li>
                        <li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h6" tabindex="-1" href="javascript:;" unselectable="on">Heading 6</a></li>
                      </ul>
                    </li>
                    <li>
                      <div class="btn-group">
                        <a class="btn btn-default" data-wysihtml5-command="bold" title="CTRL+B" tabindex="-1" href="javascript:;" unselectable="on">Bold</a>
                        <a class="btn btn-default" data-wysihtml5-command="italic" title="CTRL+I" tabindex="-1" href="javascript:;" unselectable="on">Italic</a>
                        <a class="btn btn-default" data-wysihtml5-command="underline" title="CTRL+U" tabindex="-1" href="javascript:;" unselectable="on">Underline</a>

                        <a class="btn btn-default" data-wysihtml5-command="small" title="CTRL+S" tabindex="-1" href="javascript:;" unselectable="on">Small</a>

                      </div>
                    </li>
                    <li>
                      <a class="btn btn-default" data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="blockquote" data-wysihtml5-display-format-name="false" tabindex="-1" href="javascript:;" unselectable="on">

                        <span class="glyphicon glyphicon-quote"></span>

                      </a>
                    </li>
                    <li>
                      <div class="btn-group">
                        <a class="btn btn-default" data-wysihtml5-command="insertUnorderedList" title="Unordered list" tabindex="-1" href="javascript:;" unselectable="on">

                          <span class="glyphicon glyphicon-list"></span>

                        </a>
                        <a class="btn btn-default" data-wysihtml5-command="insertOrderedList" title="Ordered list" tabindex="-1" href="javascript:;" unselectable="on">

                          <span class="glyphicon glyphicon-th-list"></span>

                        </a>
                        <a class="btn btn-default" data-wysihtml5-command="Outdent" title="Outdent" tabindex="-1" href="javascript:;" unselectable="on">

                          <span class="glyphicon glyphicon-indent-right"></span>

                        </a>
                        <a class="btn btn-default" data-wysihtml5-command="Indent" title="Indent" tabindex="-1" href="javascript:;" unselectable="on">

                          <span class="glyphicon glyphicon-indent-left"></span>

                        </a>
                      </div>
                    </li>
                    <li>
                      <div class="bootstrap-wysihtml5-insert-link-modal modal fade" data-wysihtml5-dialog="createLink">
                        <div class="modal-dialog ">
                          <div class="modal-content">
                            <div class="modal-header">
                              <a class="close" data-dismiss="modal">×</a>
                              <h3>Insert link</h3>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <input value="http://" class="bootstrap-wysihtml5-insert-link-url form-control" data-wysihtml5-dialog-field="href">
                              </div>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" class="bootstrap-wysihtml5-insert-link-target" checked="">Open link in new window
                                </label>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <a class="btn btn-default" data-dismiss="modal" data-wysihtml5-dialog-action="cancel" href="#">Cancel</a>
                              <a href="#" class="btn btn-primary" data-dismiss="modal" data-wysihtml5-dialog-action="save">Insert link</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <a class="btn btn-default" data-wysihtml5-command="createLink" title="Insert link" tabindex="-1" href="javascript:;" unselectable="on">

                        <span class="glyphicon glyphicon-share"></span>

                      </a>
                    </li>
                    <li>
                      <div class="bootstrap-wysihtml5-insert-image-modal modal fade" data-wysihtml5-dialog="insertImage">
                        <div class="modal-dialog ">
                          <div class="modal-content">
                            <div class="modal-header">
                              <a class="close" data-dismiss="modal">×</a>
                              <h3>Insert image</h3>
                            </div>
                            <div class="modal-body">
                              <div class="form-group">
                                <input value="http://" class="bootstrap-wysihtml5-insert-image-url form-control" data-wysihtml5-dialog-field="src">
                              </div>
                            </div>
                            <div class="modal-footer">
                              <a class="btn btn-default" data-dismiss="modal" data-wysihtml5-dialog-action="cancel" href="#">Cancel</a>
                              <a class="btn btn-primary" data-dismiss="modal" data-wysihtml5-dialog-action="save" href="#">Insert image</a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <a class="btn btn-default" data-wysihtml5-command="insertImage" title="Insert image" tabindex="-1" href="javascript:;" unselectable="on">

                        <span class="glyphicon glyphicon-picture"></span>

                      </a>
                    </li>
                  </ul><textarea class="textarea" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; display: none;" placeholder="Place some text here"></textarea><input type="hidden" name="_wysihtml5_mode" value="1"><iframe class="wysihtml5-sandbox" security="restricted" allowtransparency="true" frameborder="0" width="0" height="0" marginwidth="0" marginheight="0" style="display: inline-block; background-color: rgb(255, 255, 255); border-collapse: separate; border-color: rgb(221, 221, 221); border-style: solid; border-width: 1px; clear: none; float: none; margin: 0px; outline: rgb(51, 51, 51) none 0px; outline-offset: 0px; padding: 10px; position: static; inset: auto; z-index: auto; vertical-align: baseline; text-align: start; box-sizing: border-box; box-shadow: none; border-radius: 0px; width: 100%; height: 200px;"></iframe>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="participant-email">
          <div class="box-body">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Inbox</h3>

                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                    </button>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div>
                    <!-- /.btn-group -->
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div>
                      <!-- /.btn-group -->
                    </div>
                    <!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                      <tbody>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">5 mins ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">28 mins ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">11 hours ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">15 hours ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">Yesterday</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">2 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">2 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">2 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">2 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">2 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">4 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date">12 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">12 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">14 days ago</td>
                        </tr>
                        <tr>
                          <td>
                            <div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                          </td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                          <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                          </td>
                          <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                          <td class="mailbox-date">15 days ago</td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- /.table -->
                  </div>
                  <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                    </button>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div>
                    <!-- /.btn-group -->
                    <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button type="button" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div>
                      <!-- /.btn-group -->
                    </div>
                    <!-- /.pull-right -->
                  </div>
                </div>
              </div>
              <!-- /. box -->
            </div>
          </div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="participant-plan-documents">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">

                <div class="box-header with-border">
                  <h3 class="box-title" style="color: Black !important">Plan Documents</h3>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                  <form id="addPlanDocumentForm" action="{{ route('participants.uploadPlanDocument') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ Request::segment(4) }}" />
                    <div class="dropzone-wrapper">
                      <div class="dropzone-desc">
                        <i class="glyphicon glyphicon-download-alt"></i>
                        <p>Choose file or drag it here.</p>
                      </div>
                      <input type="file" name="plan_document" class="dropzone" onclick="this.value=null" />
                    </div>

                    <div id="ajaxResp" class="alert" style="display:none;"></div>
                    <div class="plan-doc-uploader flex-v-center">
                      <button type="submit" class="btn btn-success" style="margin-right:10px; display:none;" href="javascript:void(0)" id="upload_document_btn"> Upload File</button>
                      <span class="spin-loader-wrap" style="display:none;">
                        <i class="fa fa-spinner fa-spin text-info fa-2x"></i>
                      </span>
                    </div>


                  </form>

                  <h4 style="margin-top:20px; margin-bottom:20px;"> Uploaded Documents </h4>
                  <table class="table table-bordered display nowrap" id="plan_document_table" style="width:100%">
                    <thead>
                      <tr>
                        <th class="plan-document-header">File Name</th>
                        <th>File Type</th>
                        <th>S3 File key</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($plan_documents->count())
                      @foreach($plan_documents as $plan_document)
                      <tr>
                        <td> <a href="javascript:void(0)" onclick="getPlanDocument('{{ $plan_document->s3_key }}', '{{ $plan_document->s3_filepath }}');"> {{ $plan_document->file_name }} </a> </td>
                        <td> {{ $plan_document->file_type }} </td>
                        <td> {{ $plan_document->s3_key }} </td>
                        <td>
                          <form class="deletePlanDocumentForm" action="{{ route('participants.deletePlanDocument', $plan_document->id) }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-default">Remove</button>
                          </form>
                        </td>
                      </tr>
                      @endforeach
                      @else
                      <tr>
                        <td colspan="4" class="text-center text-bold text-danger"> No record found </td>
                      </tr>
                      @endif
                    </tbody>
                  </table>



                </div>
                <!-- /.box-body -->


                <!-- /. box -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.tab-content -->
    </div>

    <div class="modal fade" id="ajaxModalProvider" aria-hidden="true">
      <div class="modal-dialog modal-participant-provider">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modelHeadingProvider"></h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" id="providerForm" name="providerForm">
              <input type="hidden" name="planmanager_subscriptions_id" id="planmanager_subscriptions_id" value={{Auth::user()->plan_manager_subscription_id}}>
              <input type="hidden" name="plan_id" class="plan_id" value="{{$plan_id}}">
              <input type="hidden" name="participant_id" id="participant_id" value="{{$participant_id}}">
              <input type="hidden" name="participantserviceprovider_id" id="participantserviceprovider_id">

              <div class="form-group">
                <div class="col-sm-12">
                  <label for="providerslist" class="form control">Service Provider List</label>
                  <select class="form-control select" name="serviceprovider_id" id="serviceprovider_id" required>
                    <option value="" selected>Select sevice provider list</option>
                    @foreach ($service_providers as $service_provider)
                    <option value="{{ $service_provider->id }}">{{ $service_provider->firstname }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="saveBtnProvider" value="add">Save</button>
              </div>
              <!-- /.box-footer -->
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ajaxModalCoordinator" aria-hidden="true">
      <div class="modal-dialog modal-participant-coordinator">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modelHeadingCoordinator"></h4>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" id="coordinatorForm" name="coordinatorForm">
              <input type="hidden" name="planmanager_subscriptions_id" id="planmanager_subscriptions_id" value={{Auth::user()->plan_manager_subscription_id}}>
              <input type="hidden" name="plan_id" class="plan_id" value="{{$plan_id}}">
              <input type="hidden" name="participant_id" id="participant_id" value="{{$participant_id}}">
              <input type="hidden" name="participantsupport_coordinator_id" id="participantsupport_coordinator_id">
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="coordinatorslist" class="form control">Support Coordinator list</label>
                  <select class="form-control select" name="support_coordinator_id" id="support_coordinator_id" required>
                    <option value="" selected>Select support coordinator list</option>
                    @foreach ($support_coordinators as $support_coordinator)
                    <option value="{{ $support_coordinator->id }}">{{ $support_coordinator->firstname }}
                      {{ $support_coordinator->lastname }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>


              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right" id="saveBtnCoordinator" value="add">Save</button>
              </div>
              <!-- /.box-footer -->
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ajaxModalSupportPurpose" aria-hidden="true">
      <div class="modal-dialog modal-participant-supportpurpose">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modelHeadingSupportPurpose">Add Support</h4>
          </div>
          <div class="modal-body" style="padding: 20px !important;">
            <form class="form-horizontal" id="planDetails" name="planDetails">
              <div class="box-body">
                <div class="row" data-select2-id="15">
                  <input type="hidden" id="planDetails_id" name="planDetails_id" placeholder="Enter" class="form-control"></input>

                  <div class="form-group">
                    <div class="col-md-6">
                      <label for="support_category">Select Support Category</label>
                      <select class="form-control select" name="support_categories_id" id="support_categories_id" required style="width:100%">
                        <option value="" selected>Select Category</option>
                        @foreach ($support_categories as $plan_support_category)
                        <option value="{{ $plan_support_category->id }}">{{ $plan_support_category->support_category }}
                        </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-6">
                      <label for="support_category">Select Outcome Domain</label>
                      <select class="form-control select" name="outcome_domains_id" id="outcome_domains_id" required style="width:100%">
                        <option value="" selected>Select Outcome Domain</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="quarantinefunds"> Quarantine Funds</label>
                      <input class="quarantinefunds" id="radiobtnYESquarantinefunds" style="margin:10px !important" type="radio" name="quarantine-funds-selection" value="1"></input>
                      <label for="radiobtnYESquarantinefunds">YES </label>
                      &nbsp; &nbsp; &nbsp;
                      <input class="quarantinefunds" id="radiobtnNOquarantinefunds" style="margin:10px !important" type="radio" name="quarantine-funds-selection" value="0" required checked="checked"></input>
                      <label for="radiobtnNOquarantinefunds">NO </label>
                    </div>
                  </div>

                  <div class="form-group" id="participant_serviceproviderForm" style="display: none;">
                    <div class="col-md-12">
                      <label for="participant_serviceprovider"> If yes, please specify.</label>
                      <select class="form-control select" name="participant_serviceproviders_id" id="participant_serviceproviders_id">
                        <option value="" selected>Select sevice provider list</option>
                        @foreach ($service_providers as $service_provider)
                        <option value="{{ $service_provider->id }}">{{ $service_provider->firstname }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="stateditems"> Stated Items</label>
                      <input class="stated-support" id="radiobtnYESstatedsupport" style="margin:10px !important" type="radio" name="stated-support-selection" value="1"></input>
                      <label for="radiobtnYESstatedsupport">YES </label>
                      &nbsp; &nbsp; &nbsp;
                      <input class="stated-support" id="radiobtnNOstatedsupport" style="margin:10px !important" type="radio" name="stated-support-selection" value="0" required></input>
                      <label for="radiobtnNOstatedsupport">NO </label>
                    </div>
                  </div>

                  <div class="form-group" id="statedSupportForm" style="display: none;">
                    <div class="col-md-12">
                      <label for="stateditems"> If yes, please specify.</label>
                      <select class="form-control select" name="stated_items_id" id="stated_items_id" style="width:100%">
                      </select>
                    </div>
                  </div>



                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="category_budget">Budget</label>
                      <input type="text" step="0.0000001" id="category_budget" name="category_budget" placeholder="Enter budget" class="form-control form-control-required" required></input>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="details">Details</label>
                      <textarea id="details" name="details" placeholder="Enter Details" class="form-control form-control-required"></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="support_payment">How will this support be paid?</label>
                      <textarea id="support_payment" name="support_payment" placeholder="How will this support be paid?" class="form-control form-control-required"></textarea>
                    </div>
                  </div>


                </div>
              </div>
          </div>
          <div class="box-footer">
            <button type="saveBtnPlanDetails" class="btn btn-info pull-right" id="saveBtnPlanDetails" value="add">Save</button>
          </div>
          </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="generatepdfmodal" aria-hidden="true">
      <div class="modal-dialog modal-participant-supportpurpose">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modelHeadingSupportPurpose">Generate Statement</h4>
          </div>
          <div class="modal-body" style="padding: 20px !important;">
            <form method="GET" action="{{ action('Admin\StatementController@generatePDF')}}" enctype="multipart/form-data">
              <input type="hidden" name="participant_id" value={{$participant_id}}>
              <input type="hidden" name="plan_id" value={{$plan_id}}>
              <div class="box-body">
                <div class="form-group">
                  <div class="col-md-6">
                    <label for="ndis_plan_start_date">Select Start Date</label>
                    <input type="date" value="yyyy-mm-dd" min="1950-01-01" max="9999-12-31" id="start_date" name="start_date" placeholder="Enter Start Date" class="form-control form-control-required" required>
                    </input>
                  </div>

                  <div class="col-md-6">
                    <label for="end_date">Select End Date</label>
                    <input type="date" value="yyyy-mm-dd" min="1950-01-01" max="9999-12-31" id="end_date" name="end_date" placeholder="Enter End Date" class="form-control form-control-required" required>
                    </input>
                  </div>

                </div>
              </div>
          </div>
          <div class="box-footer">
            {{-- <button type="saveBtnPlanDetails" class="btn btn-info pull-right" id="savegeneratepdf" value="add">Save</button> --}}
            <button type="submit" id="btn_generatePDF" class="btn btn-info pull-right" formtarget="_blank">Generate PDF</button>
          </div>
          </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="previewDocumentModal" aria-hidden="true">
      <div class="modal-dialog modal-participant-supportpurpose">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modelHeadingSupportPurpose">Plan Document</h4>
          </div>
          <div class="modal-body" style="padding: 20px !important;">
            <div class="preview-container text-center">
              <embed id="preview_document">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ajaxResponseModal" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="padding: 20px !important;">
            <div id="ajaxResponseMessage"></div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
  Chart.plugins.register({
    beforeInit: function(chart) {
      var data = chart.data.datasets[0].data;
      var isAllZero = data.reduce((a, b) => a + b) > 0 ? false : true;
      if (!isAllZero) return;
      // when all data values are zero...
      chart.data.datasets[0].data = data.map((e, i) => i > 0 ? 0 : 1); //add one segment
      chart.data.datasets[0].backgroundColor = '#595959'; //change bg color
      chart.data.datasets[0].borderWidth = 0; //no border
      chart.options.tooltips = false; //disable tooltips
      chart.options.legend.onClick = null; //disable legend click
    }
  });

  var ctx = document.getElementById('chart_core');
  ctx.height = 180;
  Chart.defaults.global.defaultFontColor = '#2b2b2b';
  Chart.defaults.global.defaultFontSize = 10;
  var AvailableCore = parseFloat('{{$core_remaining}}') || 0;
  var SpentCore = parseFloat('{{$core_spent[0]->amount}}') || 0;


  var planchart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [
        'Available',
        'Spent'
      ],
      datasets: [{
        label: 'Core Support',
        data: [AvailableCore, SpentCore],
        backgroundColor: [
          '#00aeef',
          '#9c9c9c'
        ],
        hoverOffset: 4
      }]
    },
    options: {
      responsive: false
      // title: {
      //   display: true,
      //   text: 'Core Support'
      // }
    }

  });

  planchart.update();
</script>

<script>
  var ctx = document.getElementById('chart_capital');
  ctx.height = 180;
  Chart.defaults.global.defaultFontColor = '#2b2b2b';
  Chart.defaults.global.defaultFontSize = 10;
  var AvailableCapital = parseFloat('{{$capital_remaining}}') || 0;
  var SpentCapital = parseFloat('{{$capital_spent[0]->amount}}') || 0;

  var planchart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [
        'Available',
        'Spent'
      ],
      datasets: [{
        label: 'Capital Support',
        data: [AvailableCapital, SpentCapital],
        backgroundColor: [
          '#fcba13',
          '#9c9c9c'
        ],
        hoverOffset: 4
      }]
    },
    options: {
      responsive: false,
      // title: {
      //   display: true,
      //   text: 'Capital Support'
      // }
    }
  });

  planchart.update();
</script>

<script>
  var ctx = document.getElementById('chart_capacity');
  ctx.height = 180;
  var AvailableCapacity = parseFloat('{{$capacity_remaining}}') || 0;
  var SpentCapacity = parseFloat('{{$capacity_spent[0]->amount}}') || 0;

  Chart.defaults.global.defaultFontColor = '#2b2b2b';
  Chart.defaults.global.defaultFontSize = 10;

  var planchart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [
        'Available',
        'Spent'
      ],
      datasets: [{
        label: 'Capacity Building',
        data: [AvailableCapacity, SpentCapacity],
        backgroundColor: [
          '#97ca4b',
          '#9c9c9c'
        ],
        hoverOffset: 4
      }]
    },
    options: {
      responsive: false,
      // title: {
      //   display: true,
      //   text: 'Capacity Building'
      // }
    }
  });

  planchart.update();
</script>


<script>
  function updateChart() {
    //var updateValues = 

  }
</script>

<script>
  $('#support_categories_id').on('change', function() {

    var id = $(this).val();

    // $(this).append("<option>12312312</option>");
    // $('#outcome_domains_id').append('<option value="1">One</option>');



    $.ajax({
      url: "{{ route('participants.getoutcomedomains') }}",
      data: {
        support_categories_id: id
      },
      type: "POST",
      dataType: 'json',
      success: function(data) {
        var html = "";
        var countobject = data.length;
        if (countobject >= 2) {
          html += "<option value='' selected>Select Outcome Domain</option>";
        }
        $(data).each(function(index, value) {
          if (countobject == 1) {
            html += "<option value='' >Select Outcome Domain</option>";
            html += "<option value=" + value.id + " selected>" + value.outcome_domain + "</option>";
          } else if (countobject == 2) {
            html += "<option value=" + value.id + ">" + value.outcome_domain + "</option>";
          }
        });

        $('#outcome_domains_id').html(html);
      },
      error: function(data) {

      }
    });

    $.ajax({
      url: "{{ route('participants.getstateditems') }}",
      data: {
        support_categories_id: id
      },
      type: "POST",
      dataType: 'json',
      success: function(data) {
        var html = "";
        var countobject = data.length;

        html += "<option value='0'>Select Stated item</option>";
        $(data).each(function(index, value) {

          html += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";

        });

        // html += "<option value='1' selected> 2</option>";

        $('#stated_items_id').html(html);
        // console.log(html);
      },
      error: function(data) {

      }
    });



  });

  $('#radiobtnYESquarantinefunds').on('change', function() {
    $('input:radio[name=stated-support-selection][value=1]').click();
    $('input:radio[name=stated-support-selection][value=0]').attr('disabled', true);
  })

  $('#radiobtnNOquarantinefunds').on('change', function() {
    $('input:radio[name=stated-support-selection][value=0]').attr('disabled', false);
    $('input:radio[name=stated-support-selection][value=0]').click();
  })

  //Initialize Select2 Elements
  $('.select2').select2()

  var loadrecordplandetailstateditems = $('.data-table-plan-details-stated-items').DataTable({
    responsive: true,
    processing: true,
    serverSide: false,
    ajax: {
      url: "{{route('participants.loadrecordplandetailstateditems')}}",
      type: "GET",
      data: function(data) {
        data.plan_id = '{{$plan_id}}';
      },
    },
    lengthMenu: [3],
    columns: [{
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
      },
      {
        data: 'stated_item',
        name: 'stated_item'
      },
      {
        data: 'stated_item_budget',
        name: 'stated_item_budget'
      }
    ],
    initComplete: function(data) {

      // set first record selected
      var firstrow = $('.data-table-plan-details-stated-items tbody tr:eq(0) td').find("input");
      $(firstrow).prop('checked', true);

    },
    drawCallback: function(settings) {
      // set first record selected
      var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
      $(firstrow).prop('checked', true);
    }
  });

  function editSupportPurpose(planDetail_id) {

    $("#radiobtnYESquarantinefunds").prop('disabled', false);
    $("#radiobtnNOquarantinefunds").prop('disabled', false);
    $('#participant_serviceproviders_id').prop('disabled', false);
    $("#radiobtnYESstatedsupport").prop('disabled', false);
    $("#radiobtnNOstatedsupport").prop('disabled', false);
    $('#stated_items_id').prop('disabled', false);
    $('#support_categories_id').prop('disabled', false);
    $('#outcome_domains_id').prop('disabled', false);
    $('#category_budget').prop('disabled', false);
    $('#details').prop('disabled', false);
    $('#support_payment').prop('disabled', false);

    $.get("{{ route('participants.loadrecordplandetailstateditems') }}" + '/' + planDetail_id + '/editrecordplandetails', function(data) {

      var data = data[0];

      // $('#category_budget').trigger('focus');

      // $('#txt_PhoneNumber').trigger('input');

      // currencyMask.destroy();

      $('#modelHeadingSupportPurpose').html("Edit Support Purpose");

      $('#ajaxModalSupportPurpose').modal('show');

      if (data.has_stated_item == 1) {

        $("#radiobtnYESstatedsupport").prop('checked', true);
        $("#radiobtnNOstatedsupport").prop('checked', false);
        $('#statedSupportForm').show();
        // console.log(data.ndis_pricingguides_id);

      } else {
        $('#statedSupportForm').hide();
        $('#stated_items_id').val(0).change();
        $("#radiobtnYESstatedsupport").prop('checked', false);
        $("#radiobtnNOstatedsupport").prop('checked', true);
      }

      if (data.has_quarantine_fund == 1) {
        $('#participant_serviceproviderForm').show();
        $("#radiobtnYESquarantinefunds").prop('checked', true);
        $("#radiobtnNOquarantinefunds").prop('checked', false);
        $('#participant_serviceproviders_id').val(data.participant_serviceproviders_id).change();
      } else {
        $('#participant_serviceproviderForm').hide();
        $("#radiobtnYESquarantinefunds").prop('checked', false);
        $("#radiobtnNOquarantinefunds").prop('checked', true);
        $('#participant_serviceproviders_id').val(0).change();

      }

      var outcome_domains_id = data.outcome_domains_id

      $.ajax({
        url: "{{ route('participants.getoutcomedomains') }}",
        data: {
          support_categories_id: data.support_categories_id
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          var html = "";
          var countobject = data.length;
          if (countobject >= 2) {
            html += "<option value='' selected>Select Outcome Domain</option>";
          }
          $(data).each(function(index, value) {

            if (value.id == outcome_domains_id) {
              html += "<option value=" + value.id + " selected> " + value.outcome_domain + "</option>";
            } else {
              html += "<option value=" + value.id + "> " + value.outcome_domain + "</option>";
            }
          });

          $('#outcome_domains_id').html(html);
        },
        error: function(data) {

        }
      });

      // console.log();
      // $('#stated_items_id').val(2)

      var ndis_pricingguides_id = data.ndis_pricingguides_id
      if (data.has_stated_item == 1) {
        $.ajax({
          url: "{{ route('participants.getstateditems') }}",
          data: {
            support_categories_id: data.support_categories_id
          },
          type: "POST",
          dataType: 'json',
          success: function(data) {
            isFilter_support_categories = false;
            var html = "";
            var countobject = data.length;

            html += "<option value='0'>Select Stated item</option>";

            $(data).each(function(index, value) {
              if (value.id == ndis_pricingguides_id) {
                html += "<option value=" + value.id + " selected> " + value.support_item_number + " / " + value.support_item_name + "</option>";
              } else {
                html += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";
              }
            });


            $('#stated_items_id').html(html);

            // console.log(html);
            // console.log(html);
          },
          error: function(data) {

          }
        });
      } else {
        $('#statedSupportForm').hide();
        $('#stated_items_id').val(0).change();

        $("#radiobtnYESquarantinefunds").prop('checked', false);
        $("#radiobtnNOquarantinefunds").prop('checked', true);
      }
      var participant_serviceproviders_id = data.participant_serviceproviders_id


      $.ajax({
        url: "{{ route('participants.getserviceproviders') }}",
        data: {
          service_provider_id: data.id
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {

          var html = "";
          var countobject = data.length;

          html += "<option value='0'>Select Service Provider</option>";

          $(data).each(function(index, value) {
            if (value.id == participant_serviceproviders_id) {
              html += "<option value=" + value.id + " selected> " + value.firstname + "</option>";
            } else {
              html += "<option value=" + value.id + "> " + value.firstname + "</option>";
            }
          });


          $('#participant_serviceproviders_id').html(html);
        },
        error: function(data) {

        }
      });


      $('#planDetails_id').val(data.id);
      $('#support_categories_id').val(data.support_categories_id);
      $('#outcome_domains_id').val(data.outcome_domains_id).change();
      $('#category_budget').val(data.category_budget);
      $('#details').val(data.details);
      $('#support_payment').val(data.support_payment);

      $('#saveBtnPlanDetails').show();


      var currencyMask = IMask(
        document.getElementById('category_budget'), {
          mask: '$num',
          blocks: {
            num: {
              // nested masks are available!
              mask: Number,
              thousandsSeparator: ',',
              min: 0,
              radix: '.', // fractional delimiter
              normalizeZeros: true, // appends or removes zeros at ends,
              padFractionalZeros: false, // if true, then pads zeros at end to the length of scale
              scale: 2, // digits after point, 0 for integers
            }
          }
        });
      // currencyMask.updateValue();
      // currencyMask.updateControl();
    })
  }

  $('#editCoreSupport').on('click', function() {

    var planDetail_id = $("input:radio[name=core-supports-selection]:checked").val();
    if (planDetail_id == undefined) {
      OkayModal("Error", "Please select an item.");
      $('#clickmodal').click();
      return false;
    }

    editSupportPurpose(planDetail_id);

  });

  $('#editCapitalSupport').on('click', function() {

    var planDetail_id = $("input:radio[name=capital-selection]:checked").val();
    if (planDetail_id == undefined) {
      OkayModal("Error", "Please select an item.");
      $('#clickmodal').click();
      return false;
    }

    editSupportPurpose(planDetail_id);

  });

  $('#editCapacityBuilding').on('click', function() {

    var planDetail_id = $("input:radio[name=capacity-building-selection]:checked").val();
    if (planDetail_id == undefined) {
      OkayModal("Error", "Please select an item.");
      $('#clickmodal').click();
      return false;
    }

    editSupportPurpose(planDetail_id);

  });


  function viewSupportPurpose(planDetail_id) {
    $.get("{{ route('participants.loadrecordplandetailstateditems') }}" + '/' + planDetail_id + '/editrecordplandetails', function(data) {

      var data = data[0];
      $('#modelHeadingSupportPurpose').html("View Support Purpose");

      $('#ajaxModalSupportPurpose').modal('show');

      if (data.has_stated_item == 1) {

        $('#statedSupportForm').show();
        $("#radiobtnYESstatedsupport").prop('checked', true);
        $("#radiobtnNOstatedsupport").prop('checked', false);

      } else {
        $('#statedSupportForm').hide();
        $('#stated_items_id').val(0).change();
        $("#radiobtnYESstatedsupport").prop('checked', false);
        $("#radiobtnNOstatedsupport").prop('checked', true);

      }


      if (data.has_quarantine_fund == 1) {
        $('#participant_serviceproviderForm').show();

        $("#radiobtnYESquarantinefunds").prop('checked', true);
        $("#radiobtnNOquarantinefunds").prop('checked', false);
        $('#participant_serviceproviders_id').val(data.participant_serviceproviders_id).change();
      } else {
        $('#participant_serviceproviderForm').hide();

        $("#radiobtnYESquarantinefunds").prop('checked', false);
        $("#radiobtnNOquarantinefunds").prop('checked', true);
        $('#participant_serviceproviders_id').val(0).change();

      }

      var outcome_domains_id = data.outcome_domains_id

      $.ajax({
        url: "{{ route('participants.getoutcomedomains') }}",
        data: {
          support_categories_id: data.support_categories_id
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          var html = "";
          var countobject = data.length;
          if (countobject >= 2) {
            html += "<option value='' selected>Select Outcome Domain</option>";
          }
          $(data).each(function(index, value) {

            if (value.id == outcome_domains_id) {
              html += "<option value=" + value.id + " selected> " + value.outcome_domain + "</option>";
            } else {
              html += "<option value=" + value.id + "> " + value.outcome_domain + "</option>";
            }
          });

          $('#outcome_domains_id').html(html);
        },
        error: function(data) {

        }
      });

      // console.log();
      // $('#stated_items_id').val(2)
      var ndis_pricingguides_id = data.ndis_pricingguides_id
      $.ajax({
        url: "{{ route('participants.getstateditems') }}",
        data: {
          support_categories_id: data.support_categories_id
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          isFilter_support_categories = false;
          var html = "";
          var countobject = data.length;

          html += "<option value='0'>Select Stated item</option>";

          $(data).each(function(index, value) {
            if (value.id == ndis_pricingguides_id) {
              html += "<option value=" + value.id + " selected> " + value.support_item_number + " / " + value.support_item_name + "</option>";
            } else {
              html += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";
            }
          });


          $('#stated_items_id').html(html);

          // console.log(html);
          // console.log(html);
        },
        error: function(data) {

        }
      });

      var participant_serviceproviders_id = data.participant_serviceproviders_id


      $.ajax({
        url: "{{ route('participants.getserviceproviders') }}",
        data: {
          service_provider_id: data.id
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {

          var html = "";
          var countobject = data.length;

          html += "<option value='0'>Select Service Provider</option>";

          $(data).each(function(index, value) {
            if (value.id == participant_serviceproviders_id) {
              html += "<option value=" + value.id + " selected> " + value.firstname + "</option>";
            } else {
              html += "<option value=" + value.id + "> " + value.firstname + "</option>";
            }
          });


          $('#participant_serviceproviders_id').html(html);
        },
        error: function(data) {

        }
      });

      $('#planDetails_id').val(data.id);
      $('#support_categories_id').val(data.support_categories_id);
      $('#outcome_domains_id').val(data.outcome_domains_id).change();
      $('#category_budget').val(data.category_budget);
      $('#details').val(data.details);
      $('#support_payment').val(data.support_payment);


      $('#saveBtnPlanDetails').hide();
      $('#participant_serviceproviders_id').prop('disabled', true);
      $("#radiobtnYESquarantinefunds").prop('disabled', true);
      $("#radiobtnNOquarantinefunds").prop('disabled', true);

      $("#radiobtnYESstatedsupport").prop('disabled', true);
      $("#radiobtnNOstatedsupport").prop('disabled', true);
      $('#stated_items_id').prop('disabled', true);
      $('#support_categories_id').prop('disabled', true);
      $('#outcome_domains_id').prop('disabled', true);
      $('#category_budget').prop('disabled', true);
      $('#details').prop('disabled', true);
      $('#support_payment').prop('disabled', true);

      var currencyMask = IMask(
        document.getElementById('category_budget'), {
          mask: '$num',
          blocks: {
            num: {
              // nested masks are available!
              mask: Number,
              thousandsSeparator: ',',
              min: 0,
              radix: '.', // fractional delimiter
              normalizeZeros: true, // appends or removes zeros at ends,
              padFractionalZeros: false, // if true, then pads zeros at end to the length of scale
              scale: 2, // digits after point, 0 for integers
            }
          }
        });
    })

  }

  $('#viewCoreSupport').on('click', function() {

    var planDetail_id = $("input:radio[name=core-supports-selection]:checked").val();
    if (planDetail_id == undefined) {
      OkayModal("Error", "Please select an item.");
      $('#clickmodal').click();
      return false;
    }

    viewSupportPurpose(planDetail_id);


  });

  $('#viewCapitalSupport').on('click', function() {

    var planDetail_id = $("input:radio[name=capital-selection]:checked").val();
    if (planDetail_id == undefined) {
      OkayModal("Error", "Please select an item.");
      $('#clickmodal').click();
      return false;
    }
    viewSupportPurpose(planDetail_id);


  })

  $('#viewCapacityBuilding').on('click', function() {


    var planDetail_id = $("input:radio[name=capacity-building-selection]:checked").val();
    if (planDetail_id == undefined) {
      OkayModal("Error", "Please select an item.");
      $('#clickmodal').click();
      return false;
    }
    viewSupportPurpose(planDetail_id);


  })

  $(function() {



    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });

    var loadrecordserviceprovider = $('.data-table-service-providers').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{route('participants.loadrecordserviceprovider')}}",
        type: "GET",
        data: function(data) {
          data.plan_id = '{{$plan_id}}';
          data.participant_id = '{{$participant_id}}';
        },
      },
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          return '<input type="radio" name="service-provider-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false
        },
        {
          data: 'typename',
          name: 'typename'
        },
        {
          data: 'firstname',
          name: 'firstname'
        },
        {
          data: 'mobile',
          name: 'mobile'
        },
        {
          data: 'email',
          name: 'email'
        }
      ],
      initComplete: function(data) {

        // set first record selected
        var firstrow = $('.data-table-service-providers tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);

      }
    });

    var loadrecordinvoices = $('.data-table-invoices').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{route('participants.loadrecordinvoices')}}",
        type: "GET",
        data: function(data) {
          data.plan_id = '{{$plan_id}}';
          data.participant_id = '{{$participant_id}}';
        },
      },
      columns: [{
          data: 'ndis_number',
          name: 'ndis_number'
        },
        {
          data: 'invoice_number',
          name: 'invoice_number'
        },
        {
          data: 'invoice_date',
          name: 'invoice_date'
        },
        // {
        //   data: 'due_date',
        //   name: 'due_date'
        // },
        // {
        //   data: 'reference_number',
        //   name: 'reference_number'
        // },
        {
          data: 'service_provider_ABN',
          name: 'service_provider_ABN'
        },
        {
          data: 'invoice_amt',
          name: 'invoice_amt',
          'render': function(data, type, row) {
            var amt = '$' + dollarUSLocale.format(row.invoice_amt);
            return amt;
          }

        },
        {
          data: 'service_provider_first_name',
          name: 'service_provider_first_name'
        },
        {
          data: 'status',
          name: 'status',
          'render': function(data, type, row) {

            $_status = '';

            if (data == "Verified") {
              $_status = '<span style="color: green">' + row.status + '</span>';
            } else if (data == "Unverified") {
              $_status = '<span style="color: red">' + row.status + '</span>';
            } else {
              $_status = '<span style="color: black">' + row.status + '</span>';
            }

            return $_status;
          }
        },
        {
          data: 'remarks',
          name: 'remarks'
        }
      ],
      initComplete: function(data) {

        // set first record selected
        var firstrow = $('.data-table-invoices tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);

      }
    });

    $('.data-table-invoices tbody').on('click', 'tr', function() {
      var invoice_detail = loadrecordinvoices.row(this).data();
      if (invoice_detail != null) {
        var redirect = window.location.origin + '/invoices/' + invoice_detail.id + '/view';
        window.location.href = redirect
      }

    });


    var counter = 1;
    var stated_items_ids = [];
    var stated_items_budget = [];
    var stated_items = [];


    $('#add_stated_item').on('click', function() {
      var plan_stated_items_text = $('#stated_items_id :selected').text();
      var plan_stated_items_id = $('#stated_items_id :selected').val();
      var foundduplicated = false;

      var stated_item_budget = $('#stated_item_budget').val();
      var category_budget = $('#category_budget').val();

      if (plan_stated_items_id == 0 || stated_item_budget == 0) {
        return false;
      }


      $(plan_stated_items_id).each(function(index, value) {
        if (plan_stated_items_id == value) {
          foundduplicated = true;
        }
      })

      if (foundduplicated) {
        return false;
      }

      if (parseInt(stated_item_budget) > (parseInt(category_budget))) {
        OkayModal("Error", "Entered value is overbudget.");
        $('#clickmodal').click();
        //alert("Entered value is overbudget");
        return false;
      }

      if (parseInt(total_stated_items_budget) > (parseInt(category_budget))) {
        OkayModal("Error", "Entered value is overbudget.");
        $('#clickmodal').click();
        //alert("Entered value is overbudget");
        return false;
      }

      stated_items_ids.push(plan_stated_items_id);
      stated_items_budget.push(stated_item_budget);

      var total_stated_items_budget = 0;
      $(stated_items_budget).each(function(index, value) {
        total_stated_items_budget += parseInt(value);
      });

      loadrecordplandetailstateditems.row.add({
        "action": '<button data-id="' + counter + '" class="btn btn-danger btn-sm deleteItem" type="button" ><i class="fa fa-trash"><i/></button>',
        "stated_item": plan_stated_items_text,
        "stated_item_budget": stated_item_budget,
      }).draw();

      counter++;
    })

    // $(document).on('click','.deleteItem', function(){
    //   alert();
    // })

    $('.data-table-plan-details-stated-items').on('click', 'tbody tr', function() {
      loadrecordplandetailstateditems.row(this).remove().draw(false);
    });

    $('#addNewParticipantServiceProvider').click(function() {

      $('#saveBtnProvider').val("create-serviceprovider");
      $('#participantserviceprovider_id').val('');
      $('#providerForm').trigger("reset");
      $('#modelHeadingProvider').html("Add Service Provider");
      $('#ajaxModalProvider').modal('show');

      $('#saveBtnProvider').show();

    })

    $('#providerForm').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();

      $.ajax({
        data: $(this).serialize(),
        url: "{{ route('participants.saverecordserviceprovider') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('#saveBtnProvider').prop('disabled', true);
        },
        success: function(data) {
          if (data.msg) {
            // isConfirmed = confirm("Record already exists!");
            // if (!isConfirmed) {
            //   $('#providerForm').trigger("reset");
            //   $('#ajaxModalProvider').modal('hide');
            //   loadrecordserviceprovider.draw();
            // }

            OkayModal("Error", "Record already exists!");
            $('#clickmodal').click();
          } else {
            $('#providerForm').trigger("reset");
            $('#ajaxModalProvider').modal('hide');
            loadrecordserviceprovider.draw();
            $('#saveBtnProvider').html('Save');
          }
          $('#saveBtnProvider').prop('disabled', false);
        },
        error: function(data) {
          console.log('ErrorLog:', data);
          $('#saveBtnProvider').html('Save');
        }
      });
    });

    $('#deleteParticipantServiceProvider').click(function() {
      var participantserviceprovider_id = $("input:radio[name=service-provider-selection]:checked").val();

      if (participantserviceprovider_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      // var isConfirmed = confirm("Are you sure you want to delete this item?");

      // if (isConfirmed) {
      //   $.ajax({
      //     type: "POST",
      //     url: "{{ route('participants.deleterecordserviceprovider')}}",
      //     data: {
      //       id: participantserviceprovider_id
      //     },
      //     success: function(data) {
      //       loadrecordserviceprovider.draw();
      //     },
      //     error: function(data) {
      //       console.log('Error:', data);
      //     }
      //   });
      // }

      bootbox.confirm({
        message: "Are you sure you want to delete this item?",
        buttons: {
          confirm: {
            label: 'Yes',
            className: 'btn-success'
          },
          cancel: {
            label: 'No',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          console.log('This was logged in the callback: ' + result);
          if (result) {
            $.ajax({
              type: "POST",
              url: "{{ route('participants.deleterecordserviceprovider')}}",
              data: {
                id: participantserviceprovider_id
              },
              success: function(data) {
                if (data.success) {
                  loadrecordserviceprovider.draw();
                  // bootbox.alert("Successfully deleted!");
                  OkayModal("Success", "Record successfully deleted!");
                } else {
                  // alert(data.msg);
                  OkayModal("Error", data.msg);
                }

                $('#clickmodal').click();
              },
              error: function(data) {
                console.log('Error:', data);
              }
            });
          }
        }

      });


    })

    var loadrecordsupportcoordinator = $('.data-table-support-coordinators').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{route('participants.loadrecordsupportcoordinator')}}",
        type: "GET",
        data: function(data) {
          data.plan_id = '{{$plan_id}}';
          data.participant_id = '{{$participant_id}}';
        },
      },
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          return '<input type="radio" name="support-coordinator-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false
        },
        {
          data: 'firstname',
          name: 'firstname'
        },
        {
          data: 'lastname',
          name: 'lastname'
        },
        {
          data: 'mobile',
          name: 'mobile'
        },
        {
          data: 'email',
          name: 'email'
        }
      ],
      initComplete: function(data) {

        // set first record selected
        var firstrow = $('.data-table-support-coordinators tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);

      }
    });

    $('#deleteSupportCoordinator').click(function() {
      var participantsupportcoordinator_id = $("input:radio[name=support-coordinator-selection]:checked").val();
      if (participantsupportcoordinator_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }
      //   var isConfirmed = confirm("Are you sure you want to delete this item?");

      //   if (isConfirmed) {
      //     $.ajax({
      //       type: "POST",
      //       url: "{{ route('participants.deleterecordsupportcoordinator')}}",
      //       data: {
      //         id: participantsupportcoordinator_id
      //       },
      //       success: function(data) {
      //         loadrecordsupportcoordinator.draw();
      //       },
      //       error: function(data) {
      //         console.log('Error:', data);
      //       }
      //     });
      //   }
      // })

      bootbox.confirm({
        message: "Are you sure you want to delete this item?",
        buttons: {
          confirm: {
            label: 'Yes',
            className: 'btn-success'
          },
          cancel: {
            label: 'No',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          console.log('This was logged in the callback: ' + result);
          if (result) {
            $.ajax({
              type: "POST",
              url: "{{ route('participants.deleterecordsupportcoordinator')}}",
              data: {
                id: participantsupportcoordinator_id
              },
              success: function(data) {
                if (data.success) {
                  loadrecordsupportcoordinator.draw();
                  // bootbox.alert("Successfully deleted!");
                  OkayModal("Success", "Record successfully deleted!");
                } else {
                  // alert(data.msg);
                  OkayModal("Error", data.msg);
                }

                $('#clickmodal').click();
              },
              error: function(data) {
                console.log('Error:', data);
              }
            });
          }
        }

      });
    });


    $('#addNewSupportCoordinator').click(function() {

      $('#saveBtnCoordinator').val("create-support-coordinator");
      $('#participantsupport_coordinator_id').val('');
      $('#CoordinatorForm').trigger("reset");
      $('#modelHeadingCoordinator').html("Add Support Coordinator");
      $('#ajaxModalCoordinator').modal('show');

      $('#saveBtnCoordinator').show();

    })

    $('#coordinatorForm').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();
      // console.log(data);
      $.ajax({
        data: $(this).serialize(),
        url: "{{ route('participants.saverecordsupportcoordinator') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('#saveBtnCoordinator').prop('disabled', true);
        },
        success: function(data) {
          if (data.msg) {
            // isConfirmed = confirm("Record already exists!");
            // if (!isConfirmed) {
            //   $('#coordinatorForm').trigger("reset");
            //   $('#ajaxModalCoordinator').modal('hide');
            //   loadrecordsupportcoordinator.draw();
            // }
            OkayModal("Error", "Record already exists!");
            $('#clickmodal').click();
          } else {
            $('#coordinatorForm').trigger("reset");
            $('#ajaxModalCoordinator').modal('hide');
            loadrecordsupportcoordinator.draw();
            $('#saveBtnCoordinator').html('Save');
          }
          $('#saveBtnCoordinator').prop('disabled', false);
        },
        error: function(data) {
          console.log('ErrorLog:', data);
          $('#saveBtnCoordinator').html('Save');
        }
      });
    });

    $('#generatepdf').click(function() {
      $('#generatepdfmodal').modal('show');
      $('#savegeneratepdf').show();
      // $('#generatepdfmodal').hide();
    });

    $('#btn_generatePDF').click(function() {
      $('#generatepdfmodal').modal('hide');
    })

    $('#addNewCategory').click(function() {

      $('#saveBtnPlanDetails').val("create-support");
      // $('#participantsupport_coordinator_id').val('');
      // $('#CoordinatorForm').trigger("reset");

      $('#modelHeadingSupportPurpose').html("Add Support");

      $('#statedSupportForm').hide();
      $('#participant_serviceproviderForm').hide();
      $("#radiobtnYESstatedsupport").prop('disabled', false);
      $("#radiobtnYESquarantinefunds").prop('disabled', false);
      $("#radiobtnNOquarantinefunds").prop('disabled', false);
      $("#radiobtnNOstatedsupport").prop('disabled', false);
      $('#stated_items_id').prop('disabled', false);
      $('#support_categories_id').prop('disabled', false);
      $('#outcome_domains_id').prop('disabled', false);
      $('#category_budget').prop('disabled', false);
      $('#details').prop('disabled', false);
      $('#support_payment').prop('disabled', false);

      $('#planDetails_id').val(0);
      $("#radiobtnYESstatedsupport").prop('checked', false);
      $("#radiobtnNOstatedsupport").prop('checked', true);
      $("#radiobtnYESquarantinefunds").prop('checked', false);
      $("#radiobtnNOquarantinefunds").prop('checked', true);
      $('#participant_serviceproviders_id').prop('disabled', false);
      $('#participant_serviceproviders_id').val(0).change();
      $('#support_categories_id').val(0).change();
      $('#outcome_domains_id').val(0).change();
      $('#category_budget').val(0);
      $('#details').val("");
      $('#support_payment').val("");
      $('#ajaxModalSupportPurpose').modal('show');

      $('#saveBtnPlanDetails').show();

    })

    $('.stated-support').change(function(index, value) {
      var isYes = $(this).val();
      if (isYes == 1) {
        $('#statedSupportForm').show();
        $('#stated_items_id').prop('required', true);
      } else {
        $('#stated_items_id').prop('required', false);
        $('#statedSupportForm').hide();
      }
    })

    $('.quarantinefunds').change(function(index, value) {
      var isYes = $(this).val();
      if (isYes == 1) {
        $('#participant_serviceproviderForm').show();
        // $('#participant_serviceproviders_id').prop('required', true);

        // //heres
        // $.ajax({
        //   url: "{{ route('participants.getparticipantserviceprovider') }}",
        //   data: {
        //     plan_id: $('.plan_id').val()
        //   },
        //   type: "POST",
        //   dataType: 'json',
        //   success: function(data) {
        //     isFilter_support_categories = false;
        //     var html = "";
        //     var countobject = data.length;

        //     html += "<option value='0'>Select Service Provider</option>";

        //     $(data).each(function(index, value) {

        //       html += "<option value=" + value.id + "> " + value.firstname + "  " + value.lastname + "</option>";

        //     });


        //     $('#participant_serviceproviders_id').html(html);
        //   },
        //   error: function(data) {

        //   }
        // });

      } else {
        $('#participant_serviceproviderForm').hide();
        $('#participant_serviceproviders_id').prop('required', false);

      }
    })

    var currencyMask = IMask(
      document.getElementById('category_budget'), {
        mask: '$num',
        blocks: {
          num: {
            // nested masks are available!
            mask: Number,
            thousandsSeparator: ',',
            min: 0,
            radix: '.', // fractional delimiter
            normalizeZeros: true, // appends or removes zeros at ends,
            padFractionalZeros: false, // if true, then pads zeros at end to the length of scale
            scale: 2, // digits after point, 0 for integers
          }
        }
      });

    currencyMask.updateValue();
    currencyMask.updateControl();

    const dollarUSLocale = new Intl.NumberFormat('en-US', {
      currency: 'USD',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })

    $('#ajaxModalSupportPurpose').on('hidden.bs.modal', function() {
      currencyMask.unmaskedValue = ""; //UPDATE: ALWAYS SET TO EMPTY STRING TO AVOID REMAINING VALUE.
    })

    $('#planDetails').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();
      // console.log(data);
      var category_budget = $('#category_budget').val();
      var has_stated_item = $("input:radio[name=stated-support-selection]:checked").val();

      var has_quarantine_funds = $("input:radio[name=quarantine-funds-selection]:checked").val();

      var total_stated_items_budget = 0;
      $(stated_items_budget).each(function(index, value) {
        total_stated_items_budget += parseInt(value);
      });

      // if (parseInt(total_stated_items_budget) < parseInt(category_budget)) {
      //   alert("Entered value is insufficient.");
      //   return false;
      // }
      $.ajax({
        data: {
          plan_id: $('.plan_id').val(),
          planDetails_id: $('#planDetails_id').val(),
          support_categories_id: $('#support_categories_id').val(),
          outcome_domains_id: $('#outcome_domains_id').val(),
          has_stated_item: has_stated_item,
          stated_items_id: $('#stated_items_id :selected').val(),
          has_quarantine_funds: has_quarantine_funds,
          participant_serviceproviders_id: $('#participant_serviceproviders_id :selected').val(),
          category_budget: currencyMask.unmaskedValue,
          details: $('#details').val(),
          support_payment: $('#support_payment').val()
        },

        url: "{{ route('participants.saverecordplandetails') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('#saveBtnCategory').prop('disabled', true);

        },
        success: function(data) {

          $data = data.data;
          if (!data.has_error) {
            $('#planDetails').trigger("reset");
            $('#modelHeadingCategory').modal('hide');

            $('#ajaxModalSupportPurpose').modal('hide');
            $('#saveBtnCategory').html('Save');

            // alert(data.success);

            // if ($data.support_purposes_id == 1) { // core supports 
            loadrecordcoresupports.draw();
            loadrecordcapital.draw();
            loadrecordcapacitybuilding.draw();

            // console.log('here1');
            // } else if ($data.support_purposes_id == 2) { //capital

            $("#box-core-header").load(location.href + " #box-core-header");
            $("#box-capital-header").load(location.href + " #box-capital-header");
            $("#box-capacity-header").load(location.href + " #box-capacity-header");

            // console.log('here2');
            // } else if ($data.support_purposes_id == 3) { // Capacity building

            reloadCoreChart($data.coreBudget);
            reloadCapitalChart($data.capitalBudget);
            reloadCapacityChart($data.capacityBudget);

            //let dollarUSLocale = Intl.NumberFormat('en-US');
            document.getElementById("box-header-totalAllocated").innerHTML = dollarUSLocale.format($data.totalAllocated);
            document.getElementById("box-header-remainingBudget").innerHTML = dollarUSLocale.format((($data.totalRemaining >= 0) ? $data.totalRemaining : 0));
            // document.getElementById("box-header-spent").innerHTML = $data.totalSpent;

          } else {
            //alert(data.msg);
            OkayModal("Error", data.msg);
            $('#clickmodal').click();
          }


          $('#saveBtnCategory').prop('disabled', false);
        },
        error: function(data) {
          console.log('ErrorLog:', data);
          $('#saveBtnCategory').html('Save');
        }
      });
    });

    function reloadCoreChart(coreBudget) {
      $('#chart_core').replaceWith(' <canvas id="chart_core" class="chart_plan"></canvas>');
      var ctx = document.getElementById("chart_core");
      ctx.height = 180;
      Chart.defaults.global.defaultFontColor = '#2b2b2b';
      Chart.defaults.global.defaultFontSize = 10;
      var Available = coreBudget;

      var planchart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [
            'Available',
            'Spent'
          ],
          datasets: [{
            label: 'Core Support',
            data: [Available, 0],
            backgroundColor: [
              '#00aeef',
              '#9c9c9c'
            ],
            hoverOffset: 4
          }]
        },
        options: {
          responsive: false,
          // title: {
          //   display: true,
          //   text: 'Core Support'
          // }
        }
      });

    }

    function reloadCapitalChart(capitalBudget) {
      $('#chart_capital').replaceWith(' <canvas id="chart_capital" class="chart_plan"></canvas>');
      var ctx = document.getElementById("chart_capital");
      ctx.height = 180;
      Chart.defaults.global.defaultFontColor = '#2b2b2b';
      Chart.defaults.global.defaultFontSize = 10;
      var Available = capitalBudget;

      var planchart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [
            'Available',
            'Spent'
          ],
          datasets: [{
            label: 'Capital Support',
            data: [Available, 0],
            backgroundColor: [
              '#fcba13',
              '#9c9c9c'
            ],
            hoverOffset: 4
          }]
        },
        options: {
          responsive: false,
          // title: {
          //   display: true,
          //   text: 'Core Support'
          // }
        }
      });

    }

    function reloadCapacityChart(capacityBudget) {
      $('#chart_capacity').replaceWith(' <canvas id="chart_capacity" class="chart_plan"></canvas>');
      var ctx = document.getElementById("chart_capacity");
      ctx.height = 180;
      Chart.defaults.global.defaultFontColor = '#2b2b2b';
      Chart.defaults.global.defaultFontSize = 10;
      var Available = capacityBudget;

      var planchart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: [
            'Available',
            'Spent'
          ],
          datasets: [{
            label: 'Capacity Building',
            data: [Available, 0],
            backgroundColor: [
              '#97ca4b',
              '#9c9c9c'
            ],
            hoverOffset: 4
          }]
        },
        options: {
          responsive: false,
          // title: {
          //   display: true,
          //   text: 'Core Support'
          // }
        }
      });

    }



    function deleteSupportPurpose(planDetail_id, plan_id) {

      bootbox.confirm({
        message: "Are you sure you want to delete this item?",
        buttons: {
          confirm: {
            label: 'Yes',
            className: 'btn-success'
          },
          cancel: {
            label: 'No',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          console.log('This was logged in the callback: ' + result);
          if (result) {
            $.ajax({
              type: "POST",
              url: "{{ route('participants.deleterecordsupportpurpose')}}",
              data: {
                id: planDetail_id,
                plan_id: $('.plan_id').val(),
                planDetails_id: $('#planDetails_id').val(),
                support_categories_id: $('#support_categories_id').val(),
                has_quarantine_funds: 0,
                participant_serviceproviders_id: $('#participant_serviceproviders_id :selected').val(),
                category_budget: currencyMask.unmaskedValue,
                details: $('#details').val(),
                support_payment: $('#support_payment').val()
              },
              success: function(data) {
                if (data.success) {

                  $data = data.data;

                  // alert(data.msg);

                  if ($data.support_purposes_id == 1) { // core supports 
                    loadrecordcoresupports.draw();
                    $("#box-core-header").load(location.href + " #box-core-header");
                    reloadCoreChart($data.coreBudget);
                  } else if ($data.support_purposes_id == 2) { //capital
                    loadrecordcapital.draw();
                    $("#box-capital-header").load(location.href + " #box-capital-header");
                    reloadCapitalChart($data.capitalBudget);
                  } else if ($data.support_purposes_id == 3) { // Capacity building
                    loadrecordcapacitybuilding.draw();
                    $("#box-capacity-header").load(location.href + " #box-capacity-header");
                    reloadCapacityChart($data.capacityBudget);
                  }

                  let dollarUSLocale = Intl.NumberFormat('en-US');
                  document.getElementById("box-header-totalAllocated").innerHTML = dollarUSLocale.format($data.totalAllocated);
                  document.getElementById("box-header-remainingBudget").innerHTML = dollarUSLocale.format(($data.totalRemaining >= 0) ? $data.totalRemaining : 0);
                  // document.getElementById("box-header-spent").innerHTML = $data.totalSpent;

                } else {
                  // alert(data.msg);
                }
              },
              error: function(data) {
                console.log('Error:', data);
              }
            });
          }
        }

      });



      // var isConfirmed = confirm("Are you sure you want to delete this item?");
      // if (isConfirmed) {
      //   $.ajax({
      //     type: "POST",
      //     url: "{{ route('participants.deleterecordsupportpurpose')}}",
      //     data: {
      //       id: planDetail_id,
      //       plan_id: $('.plan_id').val(),
      //       planDetails_id: $('#planDetails_id').val(),
      //       support_categories_id: $('#support_categories_id').val(),
      //       has_quarantine_funds: 0,
      //       participant_serviceproviders_id: $('#participant_serviceproviders_id :selected').val(),
      //       category_budget: currencyMask.unmaskedValue,
      //       details: $('#details').val(),
      //       support_payment: $('#support_payment').val()
      //     },
      //     success: function(data) {
      //       if (data.success) {

      //         $data = data.data;

      //         // alert(data.msg);

      //         if ($data.support_purposes_id == 1) { // core supports 
      //           loadrecordcoresupports.draw();
      //           $("#box-core-header").load(location.href + " #box-core-header");
      //           reloadCoreChart($data.coreBudget);
      //         } else if ($data.support_purposes_id == 2) { //capital
      //           loadrecordcapital.draw();
      //           $("#box-capital-header").load(location.href + " #box-capital-header");
      //           reloadCapitalChart($data.capitalBudget);
      //         } else if ($data.support_purposes_id == 3) { // Capacity building
      //           loadrecordcapacitybuilding.draw();
      //           $("#box-capacity-header").load(location.href + " #box-capacity-header");
      //           reloadCapacityChart($data.capacityBudget);
      //         }

      //         let dollarUSLocale = Intl.NumberFormat('en-US');
      //         document.getElementById("box-header-totalAllocated").innerHTML = dollarUSLocale.format($data.totalAllocated);
      //         document.getElementById("box-header-remainingBudget").innerHTML = dollarUSLocale.format(($data.totalRemaining >= 0) ? $data.totalRemaining : 0);
      //         // document.getElementById("box-header-spent").innerHTML = $data.totalSpent;

      //       } else {
      //         // alert(data.msg);
      //       }
      //     },
      //     error: function(data) {
      //       console.log('Error:', data);
      //     }
      //   });
      // }
    }

    $('#deleteCoreSupport').on('click', function() {
      var planDetail_id = $("input:radio[name=core-supports-selection]:checked").val()
      if (planDetail_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      var $plan_id = $('.plan_id').val();

      // console.log(planDetail_id);
      deleteSupportPurpose(planDetail_id, $plan_id);

    });

    $('#deleteCapitalSupport').on('click', function() {
      var planDetail_id = $("input:radio[name=capital-selection]:checked").val()
      if (planDetail_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      var $plan_id = $('.plan_id').val();

      // console.log(planDetail_id);
      deleteSupportPurpose(planDetail_id, $plan_id);

    });

    $('#deleteCapacityBuilding').on('click', function() {
      var planDetail_id = $("input:radio[name=capacity-building-selection]:checked").val()
      if (planDetail_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      var $plan_id = $('.plan_id').val();

      // console.log(planDetail_id);
      deleteSupportPurpose(planDetail_id, $plan_id);

    });

    var loadrecordcapacitybuilding = $('.data-table-capacity-building').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{route('participants.loadrecordcapacitybuilding')}}",
        type: "GET",
        data: function(data) {
          data.plan_id = '{{$plan_id}}';
          data.participant_id = '{{$participant_id}}';
        },
      },
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          return '<input type="radio" name="capacity-building-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false
        },
        {
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        // {
        //   data: 'outcome_domain',
        //   name: 'outcome_domain'
        // },
        {
          data: 'support_category',
          name: 'support_category'
        },
        {
          data: 'has_stated_items',
          name: 'has_stated_items'
        },
        {
          data: 'support_item_number',
          name: 'support_item_number'
        },
        {
          data: 'support_item_name',
          name: 'support_item_name'
        },
        {
          data: 'has_quarantine_funds',
          name: 'has_quarantine_funds'
        },
        {
          data: 'serviceprovider_firstname',
          render: function(data, type, row) {
            // var serviceprovider_name = row.serviceprovider_lastname + ", " + row.serviceprovider_firstname;
            var serviceprovider_name = (row.serviceprovider_firstname) ? row.serviceprovider_firstname : 'N/A';


            return serviceprovider_name;
          }
        },
        {
          data: 'category_budget',
          name: 'category_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        },
        {
          data: 'remaining_budget',
          name: 'remaining_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        }

      ]
    });


    var loadrecordcapital = $('.data-table-capital').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: "{{route('participants.loadrecordcapital')}}",
        type: "GET",
        data: function(data) {
          data.plan_id = '{{$plan_id}}';
          data.participant_id = '{{$participant_id}}';
        },
      },
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          return '<input type="radio" name="capital-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false
        },
        {
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        // {
        //   data: 'outcome_domain',
        //   name: 'outcome_domain'
        // },
        {
          data: 'support_category',
          name: 'support_category'
        },
        {
          data: 'has_stated_items',
          name: 'has_stated_items'
        },
        {
          data: 'support_item_number',
          name: 'support_item_number'
        },
        {
          data: 'support_item_name',
          name: 'support_item_name'
        },
        {
          data: 'has_quarantine_funds',
          name: 'has_quarantine_funds'
        },
        {
          data: 'serviceprovider_firstname',
          render: function(data, type, row) {
            // var serviceprovider_name = row.serviceprovider_lastname + ", " + row.serviceprovider_firstname;
            var serviceprovider_name = (row.serviceprovider_firstname) ? row.serviceprovider_firstname : 'N/A';

            return serviceprovider_name;
          }
        },
        {
          data: 'category_budget',
          name: 'category_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        },
        {
          data: 'remaining_budget',
          name: 'remaining_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        }
      ]
    });


    var loadrecordcoresupports = $('.data-table-core-supports').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,

      ajax: {
        url: "{{route('participants.loadrecordcoresupports')}}",
        type: "GET",
        data: function(data) {
          data.plan_id = '{{$plan_id}}';
          data.participant_id = '{{$participant_id}}';
        },
      },
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          return '<input type="radio" name="core-supports-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false
        },
        {
          data: 'DT_RowIndex',
          name: 'DT_RowIndex'
        },
        // {
        //   data: 'outcome_domain',
        //   name: 'outcome_domain'
        // },
        {
          data: 'support_category',
          name: 'support_category'
        },
        {
          data: 'has_stated_items',
          name: 'has_stated_items'
        },
        {
          data: 'support_item_number',
          name: 'support_item_number'
        },
        {
          data: 'support_item_name',
          name: 'support_item_name'
        },
        {
          data: 'has_quarantine_funds',
          name: 'has_quarantine_funds'
        },
        {
          data: 'serviceprovider_firstname',
          render: function(data, type, row) {
            var serviceprovider_name = (row.serviceprovider_firstname) ? row.serviceprovider_firstname : 'N/A';
            return serviceprovider_name;
          }
        },
        {
          data: 'category_budget',
          name: 'category_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        },
        {
          data: 'remaining_budget',
          name: 'remaining_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        }
      ]
    });

  });
</script>

<script>
  //functionality to upload document of plan
  $('#loaderIcon').show();
  $(".dropzone").on('change', function() {
    input = this;
    inputFile = this.files[0];
    if (input.files && inputFile && inputFile.size < 2097152) {
      let filename = inputFile.name;
      $(input).parent().find('.dropzone-desc').html('<p>' + filename + '</p>');
      $('#upload_document_btn').show();
    } else {
      $(input).parent().find('.dropzone-desc').html('<p class="text-danger">File size should be less than 2MB</p>');
      $('#upload_document_btn').hide();
    }
    if (inputFile.type != 'application/pdf') {
      $(input).parent().find('.dropzone-desc').html('<p class="text-danger">Only Pdf Document Allowed</p>');
      $('#upload_document_btn').hide();
    }
  });

  //Upload plan document with ajax 
  $('#addPlanDocumentForm').on('submit', function(e) {
    e.preventDefault();
    $('#upload_document_btn').prop('disabled', true);
    $('.spin-loader-wrap').show();
    $('#ajaxResp').hide();
    var formData = new FormData($(this)[0]);
    $.ajax({
      type: "POST",
      dataType: "json",
      processData: false,
      contentType: false,
      url: "{{ route('participants.uploadPlanDocument') }}",
      data: formData,
      success: function(resp) {
        $('.spin-loader-wrap').hide();
        $('#upload_document_btn').prop('disabled', false);
        $('#upload_document_btn').hide();
        if (resp.status) {
          document.querySelector('.dropzone-desc p').innerHTML = "Choose file or drag it here.";
          $('#ajaxResp').removeClass('alert-danger').addClass('alert-success').text('document has been uploaded successfully!').show();
          displayUploadedDocument(resp.data.id, resp.data.plan_id);
          setTimeout(() => {
            $('#ajaxResp').removeClass('alert-danger').addClass('alert-success').text('document has been uploaded successfully!').hide();

          }, 2000);
        } else {
          $('#ajaxResp').removeClass('alert-success').addClass('alert-danger').show().text(resp.error_msg);
          setTimeout(() => {
            $('#ajaxResp').removeClass('alert-success').addClass('alert-danger').hide().text(resp.error_msg);
          }, 2000)
        }
      }
    });
  });


  //Display current uploaded document in table using ajax
  function displayUploadedDocument(id, plan_id) {
    $.ajax({
      type: "POST",
      url: "{{ route('participants.getlatestplandocumentajax') }}",
      data: {
        id: id,
        plan_id: plan_id
      },
      success: function(resp) {
        if (resp.status) {
          var url = '{{ route("participants.deletePlanDocument", ":id") }}';
          url = url.replace(':id', resp.data.id);
          var removeButtonHTML = '<form class="deletePlanDocumentForm" action="' + url + '" method="POST">';
          removeButtonHTML += '{{ csrf_field() }}';
          removeButtonHTML += '{{ method_field("DELETE") }}';
          removeButtonHTML += '<button type="submit" class="btn btn-default">Remove</button>';
          removeButtonHTML += '</form>';
          var html = '<tr>';
          html += '<td> <a href="javascript:void(0)" onclick="getPlanDocument(\'' + resp.data.s3_key + '\');">' + resp.data.file_name + '</a></td>';
          html += '<td>' + resp.data.file_type + '</td>';
          html += '<td>' + resp.data.s3_key + '</td>';
          html += '<td>' + removeButtonHTML + '</td>';
          html += '</tr>';
          $('#plan_document_table tbody tr:first').before(html).fadeIn("slow");
          $("#plan_document_table").load(location.href + " #plan_document_table");
        } else {
          console.log(resp.error_msg);
        }
      }
    });
  }

  $('.dropzone-wrapper').on('dragover', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass('dragover');
  });

  $('.dropzone-wrapper').on('dragleave', function(e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
  });

  //Functionality to display the document
  function getPlanDocument(url, filePath) {
    $('#preview_document').attr("src", '{{ asset("assets/images/loader.gif") }}');
    $('#previewDocumentModal').modal('show');
    var key = url;
    var path = filePath;
    $.ajax({
      method: "POST",
      url: '{{ route("participants.getPlanDocument") }}',
      data: {
        key: key,
        path: path
      },
      success: function(response) {
        var documentURL = response.document_url;
        $('#preview_document').css({
          "max-width": "740px",
          "max-height": "400px"
        });
        $('#preview_document').attr("src", documentURL);

      }
    });
  }

  //Delete document from table using ajax
  $(document).on('submit', '.deletePlanDocumentForm', function(e) {
    e.preventDefault();
    var currentEl = $(this);
    var url = $(this).attr('action');
    $.ajax({
      type: "DELETE",
      url: url,
      success: function(resp) {
        if (resp.status) {
          $('#ajaxResponseMessage').html('<b class="text-danger">Document has been deleted!</b>');
          currentEl.closest("tr").remove();
          $("#plan_document_table").load(location.href + " #plan_document_table");
        } else {
          $('#ajaxResponseMessage').html(resp.error_msg);
        }
        $('#ajaxResponseModal').modal('show');
      }
    });
    return false;
  });
</script>

@endsection