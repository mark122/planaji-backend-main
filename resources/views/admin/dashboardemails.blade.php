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

?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <a href="/dashboard" style="margin:3px; float:left"><i class="fa fa-arrow-left margin-r-5"></i>Back to Dashboard</a>
    <ol class="breadcrumb">
      <li><a href="/dashboard"><i class="fa fa-dashboard margin-r-5"></i> Dashboard</a></li>
      <li class="active">Invoice Emails</li>
    </ol><br />
  </section>

  <!-- Main content -->
  <section class="content">

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

          <span style="float: right; margin-left: 550px;">Total: {{count($invoiceEmail)}}</span>
        </h3>

      </div>
      <div class="box-body">
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

      <!-- <div class="box-footer text-center">
        <a href="javascript:void(0)" class="uppercase">View All Emails</a>
      </div> -->
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



@endsection