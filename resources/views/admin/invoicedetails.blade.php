@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
  .import-div {
    position: relative;
    overflow: hidden;
  }

  .import {
    position: absolute;
    opacity: 0;
    right: 0;
    top: 0;
  }

  .upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    margin-top: 25px;
  }

  .invoice-url {
    display: flex;
    margin-top: 25px;
  }

  .invoice-url p {
    overflow: hidden;
  }

  .fa-clone {
    font-size: 26px;
    margin-top: 4px;
    margin-right: 4px;
    margin-left: -30px;
    position: relative;
    display: inline-block;
  }

  .fa-clone .tooltiptext {
    visibility: hidden;
    width: 70px;
    background-color: #00a65a;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 104%;
    left: 50%;
    margin-left: -40px;
    opacity: 0;
    font-size: 14px;
    transition: opacity 0.3s;
  }

  .btn {
    color: #fff;
    padding: 3px 20px;
    border-radius: 0px;
    font-size: 14px;
    font-weight: normal;
  }

  .upload-btn-wrapper input[type=file] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
  }
</style>

@endsection

@section('content')

<!-- {{var_dump($route)}} -->

<div class="content-wrapper">
  <section class="content-header">
    <!-- <h1>
      Participants
    </h1> -->
    <a href="{{route('invoices.loadrecords')}}" style="margin:3px; float:left"><i class="fa fa-arrow-left margin-r-5"></i>Back to Invoices</a>
    <ol class="breadcrumb">
      <li><a href="{{route('invoices.loadrecords')}}"><i class="fa fa-file-text margin-r-5"></i> Invoice</a></li>
      <li class="active"> Details</li>
    </ol><br />
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-custom">
          <div class="box-header box-header-custom">
            <h3 class="box-title">Invoice Details
            </h3>
            <span style="float:right">{{$route}} form</span>
          </div>
          <div class="box-body">
            <div class="crud-buttons">

            </div>

            <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#success_tic">Open Modal</button> -->



            <form class="form-horizontal" id="ModalForm" name="ModalForm">

              <div class="box-body">
                <div class="row" data-select2-id="15" style="padding: 5px">

                  <input type="hidden" name="planmanager_subscriptions_id" id="planmanager_subscriptions_id" value={{Auth::user()->plan_manager_subscription_id}}>
                  <input class="invoice_id" type="hidden" name="invoice_id" id="invoice_id" value="{{@$response['invoices']->id}}">
                  <!-- <input type="hidden" class="form-control" id="status" name="status" required="" value="{{@$response['invoices']->status}}" placeholder="Status" class="form-control form-control-required" maxlength="15" disabled> -->
                  <div class="form-group">
                    <div class="col-md-4">
                      <label for="ndislist" class="form control">NDIS Number</label>
                      <select class="form-control select2" name="participant_id" id="participant_id" style="width:100%" required>
                        <option value='' selected>Select Participant</option>
                        @if(isset($participant_detail->id))
                        @foreach ($participants as $participant)
                        <option value="{{ $participant->id }}" {{ ($participant_detail->id == $participant->id)? 'selected': '' }}>{{ $participant->ndis_number}} / {{$participant->firstname}} {{$participant->lastname}}</option>
                        @endforeach
                        @else
                        @foreach ($participants as $participant)
                        <option value="{{ $participant->id }}" {{ (@$response['invoices']->participant_id == $participant->id)? 'selected': '' }}>{{ $participant->ndis_number}} / {{$participant->firstname}} {{$participant->lastname}}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label for="invoice_number">Invoice Number</label>
                      <input type="text" id="invoice_number" name="invoice_number" value="{{@$response['invoices']->invoice_number}}" required="" placeholder="Enter Invoice No" class="form-control form-control-required" maxlength="50"></input>
                    </div>

                    <div class="col-md-3">
                      <label for="providerslist" class="form control">Service Provider</label>
                      <select class="form-control select2" name="serviceprovider_id" id="serviceprovider_id" style="width:100%">
                        <option value='' selected>Select Service Provider</option>
                        @foreach ($service_providers as $service_provider)
                        <option value="{{ $service_provider->id }}" {{ (@$response['invoices']->serviceprovider_id == $service_provider->id)? 'selected': '' }}> {{ $service_provider->firstname }}
                        </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-3">
                      <label for="abn">Service Provider ABN</label>
                      <input type="text" class="form-control" id="abn" name="abn" required="" value="{{@$response['invoices']->abn}}" placeholder="Enter Service Provider ABN" class="form-control form-control-required" maxlength="15" disabled>
                    </div>

                  </div>
                  <div class="form-group">
                    <div class="col-md-5">
                      <input class="invoice_linkemails_id" type="hidden" name="invoice_linkemails_id" id="invoice_linkemails_id" value="{{@$response['invoice_linkemails']->id}}">
                      <label for="invoiceemaillist" class="form control">Link Email</label>
                      <select class="form-control select2" name="invoice_email_id" id="invoice_email_id" style="width:100%">
                        <option value='' selected>Select Email to Link</option>
                        @foreach ($invoice_emails as $email)
                        <option value="{{ $email->id }}" {{ (@$response['invoice_linkemails']->invoice_email_id == $email->id)? 'selected': '' }}>{{ $email->from_email }}: {{ $email->subject }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-3">
                      <label for="statuslist" class="form control">Status</label>
                      <select class="form-control select" name="status_id" id="status_id" style="width:100%">
                      </select>
                    </div>
                    @if(@$response['invoices']->id)
                    <div class="col-md-2">
                      <div class="upload-btn-wrapper">
                        <button class="btn btn-success">{{ empty($invoice_document) ? 'Upload Invoice' : 'Replace Invoice' }}</button>
                        <input type="file" id="invoice" onchange="uploadInvoice()" data-id="{{@$response['invoices']->id}}" />
                      </div>
                      <span class="spin-loader-wrap" style="display:none;">
                        <i class="fa fa-spinner fa-spin text-info fa-2x"></i>
                      </span>
                    </div>
                    @if (!empty($invoice_document))
                    <div class="col-md-2">
                      <div class="invoice-url">
                        <i class="fa fa-clone" id="copy-text-btn"><span class="tooltiptext">Copied</span></i><input type="hidden" id="invoice-link" value="{{$invoice_url}}" />
                      </div>
                    </div>
                    @endif
                    @endif
                  </div>
                  @if (!empty(@$response['invoices']->invoice_date))
                  <div class="form-group">
                    <div class="col-md-5">
                      <label for="invoice_date">Invoice Date</label>
                      <input type="date" value="{{@$response['invoices']->invoice_date}}" min="1950-01-01" max="9999-12-31" id="invoice_date" name="invoice_date" placeholder="Enter Invoice Date" class="form-control">
                      </input>
                    </div>
                  </div>
                  @endif
                  <!-- <br /> -->
                  <hr class="solid" style="border-top: 1px solid #dcdcdc">
                  <!-- <br /> -->
                  <div class="col-md-12">
                    <br>
                    <table class="table table-bordered display data-table-plan-details-stated-items nowrap table-example1" style="width:100%">
                      <thead>
                        <tr>
                          <th id="action">Action</th>
                          <th>Item</th>
                          <th>Start</th>
                          <th>End</th>
                          <th>Qty</th>
                          <th>Unit Price</th>
                          <th>GST</th>
                          <th>Amount</th>
                          <th>Hours</th>
                          <th>Claim Type</th>
                          <th>Cancellation Reason</th>
                          <th>Description</th>
                          <th>Id</th>
                          <th>ClaimRef</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                      <!-- <tfoot>
                        <tr>
                          <th colspan="4" style="text-align:right">Total:</th>
                          <th></th>
                        </tr>
                      </tfoot> -->
                    </table>
                  </div>
                </div>
              </div>
          </div>

          <!-- /.box-body -->
          <div class="box-footer">
            <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
            <button class="btn btn-primary" id="add_line" type="button"> Add Line</button>
            <?php
            if ($user_auth == 'plan_on_track') {
            ?>
              <div class="import-div btn btn-primary">
                <i style="display:none !important;" class="loading fa fa-spinner fa-pulse"></i> Import
                <input class="import" type="file" name="file" accept=".csv" />
              </div>
            <?php
            } ?>
            <button type="submit" class="btn btn-info pull-right save-close" value="" style="margin-left:5px;">Save & close</button>
            <button type="submit" class="btn btn-info pull-right saveBtn" value="save">Save</button> &nbsp;&nbsp;

            <!-- <button type="submit" class="btn btn-info pull-right" id="saveBtn" value="create" data-toggle="modal" data-target="#success_tic">Save</button> -->
            <label for="totalAmount" style="float: right; margin-right: 20px; margin-top: 7px;">Total Invoice Amount: $<span id="box-header-totalAmount">{{(!empty($data->totalInvoiceAmt)? number_format($data->totalInvoiceAmt,2): number_format(0, 2))}}</span></label>
          </div>
          <!-- /.box-footer -->
          </form>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

  </section>
  <!-- /.Left col -->
</div>
<!-- /.row (main row) -->

</section>
<!-- /.content -->
</div>
@endsection

@section('js')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://unpkg.com/imask"></script>

<script>
  $(function() {

    $.ajaxSetup({

      headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

      }

    });

    var statuses = <?php echo json_encode(@$invoice_status); ?>;
    var invoiceStatus = <?php echo json_encode(@$response['invoices']->status); ?>;
    var statusDropdown = '';


    if (invoiceStatus == null || '<?= @$route ?>' == "Duplicate") {
      statusDropdown += "<option value=" + 1 + " selected>New</option>";
      $('#status_id').html(statusDropdown);
      $('#status_id').prop('disabled', true);
    } else if (invoiceStatus == 1) {
      statusDropdown += "<option value=" + 1 + " selected>New</option>";
      $('#status_id').html(statusDropdown);
      $('#status_id').prop('disabled', true);
    } else if (invoiceStatus == 3) {
      statusDropdown += "<option value=" + 3 + " selected>Unverified</option>";
      $('#status_id').html(statusDropdown);
      $('#status_id').prop('disabled', true);
    } else {
      $(statuses).each(function(index, value) {

        if (value.id != 1 && value.id != 3) {
          if (value.id == invoiceStatus) {
            statusDropdown += "<option value=" + value.id + " selected> " + value.description + "</option>";
          } else {

            statusDropdown += "<option value=" + value.id + "> " + value.description + "</option>";
          }
        }
        $('#status_id').html(statusDropdown);
        $('#status_id').prop('disabled', false);

      });
    }

    const dollarUSLocale = new Intl.NumberFormat('en-US', {
      currency: 'USD',
      minimumFractionDigits: 2
    })


    var loadrecordplandetailstateditems = $('.data-table-plan-details-stated-items').DataTable({
      scrollX: true,
      processing: true,
      serverSide: false,
      order: [],
      paging: false,
      ajax: "{{route('participants.loadrecordplandetailstateditems')}}",
      'columnDefs': [{
          'targets': 0,
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          'type': "GET"
        },
        {
          'targets': [12],
          'visible': false,
        },
        {
          'targets': [13],
          'visible': false,
        }
      ],
      lengthMenu: [5],
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
          data: 'service_start_date',
          name: 'service_start_date',
          'render': function(data, type, row) {

            //return <input type="date" value="yyyy-mm-dd" min="1950-01-01" max="9999-12-31" id="ndis_plan_end_date" name="ndis_plan_end_date" placeholder="Enter NDIS Plan End Date" class="form-control form-control-required"></input>
            return '<input type="date" value="' + row.service_start_date + '"  min="1800-01-01" max="9999-12-31" class="form-control form-control" id="service_start_date" name="service_start_date" placeholder="Enter Service Start Date" value="" maxlength="50" required>';
          }
        },
        {
          data: 'service_end_date',
          name: 'service_end_date',
          'render': function(data, type, row) {

            return '<input type="date" value="' + row.service_end_date + '"  min="1800-01-01" max="9999-12-31" class="form-control form-control" id="service_end_date" name="service_end_date" placeholder="Enter Service End Date" value="" maxlength="50" required>';
          }
        },
        {
          data: 'stated_item_quantity',
          name: 'stated_item_quantity',
          'render': function(data, type, row) {

            return '<input type="text" step="0.0000001" value="' + parseFloat(row.stated_item_quantity).toFixed(2) + '" class="form-control" id="quantity" name="quantity" placeholder="0" class="form-control form-control" maxlength="15"></input>';
          }
        },
        {
          data: 'stated_item_unit_price',
          name: 'stated_item_unit_price',
          'render': function(data, type, row) {

            return '<input type="text" step="0.0000001" value="' + parseFloat(row.stated_item_unit_price).toFixed(2) + '" class="form-control" id="unit_price" name="unit_price" placeholder="0" class="form-control form-control" maxlength="15">';
          }
        },
        {
          data: 'stated_item_gst_code',
          name: 'stated_item_gst_code',
          'render': function(data, type, row) {

            return row.stated_item_gst_code;

          }
        },
        {
          data: 'stated_item_budget',
          name: 'stated_item_budget',
          'render': function(data, type, row) {

            var budget = 0;

            if (row.stated_item_budget != '' && row.stated_item_budget != undefined) {
              budget = parseFloat(row.stated_item_budget).toFixed(2);
            }

            updateTotalAmount();

            return '<input type="text" step="0.0000001" value="' + parseFloat(budget).toFixed(2) + '" id="stated_item_budget" name="stated_item_budget" placeholder="0" class="form-control form-control"></input>';
          }
        },
        {
          data: 'hours',
          name: 'hours',
          'render': function(data, type, row) {

            var hrs = row.hours;
            if (row.hours == '' || row.hours == undefined || row.hours == null) {
              hrs = 0;
            }
            return '<input type="text" step="0.001" class="form-control" value="' + hrs + '" id="hours" name="hours" placeholder="0" class="form-control form-control" maxlength="15">';
          }
        },
        {
          data: 'claim_type',
          name: 'claim_type'
        },
        {
          data: 'cancellation_reason',
          name: 'cancellation_reason',
          'render': function(data, type, row) {

            return row.cancellation_reason;
          }
        },
        {
          data: 'description',
          name: 'description',
          'render': function(data, type, row) {

            if (row.description == null) {
              row.description = "";
            }
            return '<textarea id="description" value=' + row.description + ' name="description" placeholder="Enter Description" class="form-control form-control description">' + row.description + '</textarea>';
          }
        },
        {
          data: 'invoice_details_id',
          name: 'invoice_details_id'

        },
        {
          data: 'claim_reference',
          name: 'claim_reference'
        }
      ],

      initComplete: function(data) {


        var head = $(this).parent().parent().find('.dataTables_scrollHead');
        $(head).find('#action').removeClass("sorting_disabled");
        $(head).find('#action').removeClass("sorting_asc");
        // loadrecordplandetailstateditems.columns.adjust().draw();
        $(".select2").select2({});

      }

    });

    var invoice_id = $("input[name=invoice_id]").val();


    var counter = 0;

    var getgstcode = "";

    var gstDropdownshow;

    var gstDropdown;

    $.ajax({
      url: "{{ route('invoices.getgstcode') }}",
      type: "POST",
      dataType: 'json',
      success: function(data) {

        gstDropdown = data;

        gstDropdownshow = true;


      },
      error: function(data) {
        return data;
      }
    });


    var invoice_id = $("input[name=invoice_id]").val();

    var invoice_details = <?php echo json_encode(@$response['invoice_details']); ?>;

    var total_invoices_details_amount = 0;

    setTimeout(function() {
      // $('.select2').select2();

      loadrecordplandetailstateditems.columns.adjust().draw();
    }, 1000);

    $(invoice_details).each(function(index, value) {

      var cancellationreason = "";
      var claimtype = '<select class="form-control select claim_type_id" name="claim_type_id" style="width:200px"><option value=' + value.claim_type_id + '>' + value.claimtypecode + " / " + value.claimtypedesc + '</option>' + '</select>';
      var supportitem = '<select class="form-control stated_items_id select2" name="stated_items_id" width: "500px" required><option value=' + value.ndis_pricingguide_id + '>' + value.support_item_number + " / " + value.support_item_name + '</option>' + '</select>';

      if (value.cancellation_reason == null) {
        cancellationreason = '<select class="form-control select cancel_id" name="cancel_id"  style="width:100%" disabled>';
        cancellationreason += "<option value='0'>Select Cancellation Reason</option></select>";
      }

      if (value.claim_type_id == '1') {
        cancellationreason = '<select class="form-control select cancel_id" name="cancel_id" style="width:200px"><option value=' + value.cancellation_reason_id + '>' + value.cancelcode + " / " + value.canceldesc + '</option>' + '</select>';
      } else {
        cancellationreason = '<select class="form-control select cancel_id" name="cancel_id" style="width:200px" disabled><option value="0" selected>Select Cancellation Reason</option></select>';
      }

      if (value.claim_type_id == null || value.claim_type_id == 0) {
        claimtype = '<select class="form-control select claim_type_id" name="claim_type_id"  style="width:100%">';
        claimtype += "<option value='0'>Select Claim Type</option></select>";
      }


      if (value.ndis_pricingguide_id == null || value.ndis_pricingguide_id == 0) {
        supportitem = '<select class="form-control stated_items_id select2" name="stated_items_id" style="width: 500px" required>';
        supportitem += '<option value="0">Select Support Item &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</option></select>';
      }


      loadrecordplandetailstateditems.row.add({
        "action": '<button data-id="' + value.id + '" class="btn btn-danger btn-sm deleteItem " type="button"> <i class="fa fa-trash"></i> </button> <button data-id="' + value.id + '" class="btn btn-success btn-sm duplicateItem " type="button"> <i class="fa fa-copy"></i> </button>',
        "invoice_details_id": value.id,
        "claim_reference": value.claim_reference,
        "stated_item": supportitem,
        "stated_item_unit_price": value.unit_price,
        'stated_item_quantity': value.quantity,
        "stated_item_budget": value.amount,
        "stated_item_gst_code": '<select class="form-control stated_item_gst_id select" id = "stated_item_gst_id" name="stated_item_gst_id" style="width:170px"><option value=' + value.gst_code + '>' + value.gstcode + " / " + value.gstdesc + '</option>' + '</select>',
        "stated_item_gst_id": value.gst_code,
        "description": value.description,
        "service_start_date": value.service_start_date,
        "service_end_date": value.service_end_date,
        "hours": value.hours,
        "claim_type_id": value.claim_type_id,
        "claim_type": claimtype,
        "cancellation_reason": cancellationreason //'<select class="form-control select cancel_id" name="cancel_id" style="width:200px"><option value=' + value.cancellation_reason_id + '>' + value.cancelcode + " / " + value.canceldesc + '</option>' + '</select>',
      }).draw();

      total_invoices_details_amount += parseFloat(value.amount).toFixed(2);
    });

    $('#box-header-totalAmount').text(ReplaceNumberWithCommas(
      parseFloat(total_invoices_details_amount).toFixed(2)));

    $('#serviceprovider_id').on('change', function() {

      var id = $(this).val();

      $.ajax({
        url: "{{ route('invoices.getproviderABN') }}",
        data: {
          serviceprovider_id: id
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          $('#abn').val(data.abn);
        },
        error: function(data) {

        }
      });

    });

    var getstateditems = "";
    var getstateditemsarray = [];
    var gst = "";


    $.ajax({
      url: "{{ route('invoices.getstateditems') }}",
      type: "POST",
      dataType: 'json',
      success: function(data) {
        getstateditemsarray = data

      },
      error: function(data) {}

    }).done(function() {

    });

    //as click..
    $(document).on("select2:open", '.data-table-plan-details-stated-items tr .stated_items_id', function(e) {
      // $(document).on('click', '.data-table-plan-details-stated-items tr .stated_items_id select2:open', function() {
      var thisbtn = $(this);

      getstateditems += '<select class="form-control stated_items_id select2" name="stated_items_id" style="width: 500px" required>';
      getstateditems += '<option value="0">Select Support Item &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</option>';
      $(getstateditemsarray).each(function(index, value) {
        if (value.id == thisbtn.val()) {
          // console.log(thisbtn.val());
          getstateditems += "<option value=" + value.id + " selected> " + value.support_item_number + " / " + value.support_item_name + "</option>";
        } else {
          getstateditems += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";
        }
      });
      getstateditems += '</select>';

      $(thisbtn).html(getstateditems);
      getstateditems = "";
      // console.log(getstateditems);

      loadrecordplandetailstateditems.columns.adjust().draw();

    });


    $(document).on("select2:selecting", '.data-table-plan-details-stated-items tr .stated_items_id', function(e) {

      setTimeout(function() {

        loadrecordplandetailstateditems.columns.adjust().draw();

        $('#stated_item_budget').trigger("change");
      }, 200);

    });

    $('.data-table-plan-details-stated-items').on('change', 'tr #stated_item_budget', function() {

      var col = $(this).index(),
        row = $(this).parent().parent().index();

      var cur_unitprice = 0;
      cur_unitprice = parseFloat(($(this).parent().parent().find("#unit_price").val()).replace(',', '')).toFixed(2);

      var cur_amt = 0;
      cur_amt = parseFloat(($(this).parent().parent().find("#stated_item_budget").val()).replace(',', '')).toFixed(2);


      var cal_quantity = 0;
      cal_quantity = parseFloat(cur_amt / cur_unitprice).toFixed(2);



      if (isNaN(cal_quantity) || cal_quantity == Infinity) {
        cal_quantity = parseFloat(0).toFixed(2);
      }

      $(this).parent().parent().find("#quantity").val(cal_quantity ? cal_quantity : 0);
      $(this).parent().parent().find("#stated_item_budget").val(cur_amt ? cur_amt : 0);
      $(this).parent().parent().find("#stated_item_budget").value = cur_amt;

      updateTotalAmount();

      $(this).parent().parent().find("#quantity").val();
      $(this).parent().parent().find("#stated_item_budget").val();
      $(this).parent().parent().find("#unit_price").val();

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 4).data(cal_quantity ? cal_quantity : 0);
      $('.data-table-plan-details-stated-items').DataTable().cell(row, 7, {
        order: 'applied'
      }).data(cur_amt ? cur_amt : 0);

    });


    $('.data-table-plan-details-stated-items').on('click', 'tr #stated_item_gst_id', function() {

      //Initialize Select2 Elements
      // $('.select2').select2()

      var thisbtn = $(this);
      var gst = "";

      $.ajax({
        url: "{{ route('invoices.getgstcode') }}",
        type: "POST",
        dataType: 'json',
        success: function(data) {
          gst += '<select class="form-control select stated_item_gst_id" name="stated_item_gst_id"  style="500px !important">';
          gst += "<option value='0'>Select GST</option>";
          $(data).each(function(index, value) {
            if (value.id == thisbtn.val()) {
              // console.log(thisbtn.val());
              gst += "<option value=" + value.id + " selected> " + value.code + " / " + value.description + "</option>";
            } else {
              gst += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";
            }
          });
          gst += '</select>';
          $(thisbtn).html(gst);

        },
        error: function(data) {}

      });


    });


    $('.data-table-plan-details-stated-items').on('click', 'tr .claim_type_id', function() {

      var thisbtn = $(this);
      var claimtype = "";

      $.ajax({
        url: "{{ route('invoices.getclaimtype') }}",
        type: "POST",
        dataType: 'json',
        success: function(data) {
          claimtype += '<select class="form-control select claim_type_id" name="claim_type_id"  style="width:100%">';
          claimtype += "<option value='0'>Select Claim Type</option>";
          $(data).each(function(index, value) {
            if (value.id == thisbtn.val()) {
              // console.log(thisbtn.val());
              claimtype += "<option value=" + value.id + " selected> " + value.code + " / " + value.description + "</option>";
            } else {
              claimtype += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";
            }
          });
          claimtype += '</select>';
          // console.log()

          $(thisbtn).html(claimtype);

        },
        error: function(data) {}

      });

    });

    $('.data-table-plan-details-stated-items').on('click', 'tr .cancel_id', function() {

      if ($(this).parent().parent().find(".claim_type_id").val() == '1') {
        var thisbtn = $(this);
        var cancel = "";

        $.ajax({
          url: "{{ route('invoices.getcancellationreason') }}",
          type: "POST",
          dataType: 'json',
          success: function(data) {
            cancel += '<select class="form-control select cancel_id" name="cancel_id"  style="width:100%">';
            cancel += "<option value='0'>Select Cancellation Reason</option>";
            $(data).each(function(index, value) {
              if (value.id == thisbtn.val()) {
                // console.log(thisbtn.val());
                cancel += "<option value=" + value.id + " selected> " + value.code + " / " + value.description + "</option>";
              } else {
                cancel += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";
              }
            });
            cancel += '</select>';
            // console.log()

            $(thisbtn).html(cancel);

          },
          error: function(data) {}

        });
      }

    });

    $('.data-table-plan-details-stated-items').on('change', 'tr .cancel_id', function() {

      var id = $(this).find(":selected").val();

      var col = $(this).index(),
        row = $(this).parent().parent().index();

      var canceloptions = '';

      if (id == 0) {
        $(this).parent().parent().find(".cancel_id").prop('disabled', true);
        canceloptions += "<select class='form-control select cancel_id' name='cancel_id'  style='width:100%' disabled><option value='0'>Select Cancellation Reason</option></select>";

      } else {

        $(this).parent().parent().find(".cancel_id").prop('disabled', false);
        canceloptions += '<select class="form-control select cancel_id" name="cancel_id" style="width:200px"><option value=' + $(this).parent().parent().find(".cancel_id").find(":selected").val() + '>' + $(this).parent().parent().find(".cancel_id").find(":selected").text() + '</option>' + '</select>';
      }

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 10, {
        order: 'applied'
      }).data(canceloptions);


    });

    $('.data-table-plan-details-stated-items').on('change', 'tr .claim_type_id', function() {

      var id = $(this).find(":selected").val();
      var html = "";

      var col = $(this).index(),
        row = $(this).parent().parent().index();

      if (id == '1') { //if claim type is canc

        $(this).parent().parent().find(".cancel_id").prop('disabled', false);

        $.ajax({
          url: "{{ route('invoices.getcancellationreason') }}",
          data: {
            serviceprovider_id: id
          },
          type: "POST",
          dataType: 'json',
          success: function(data) {

            html += "<select class='form-control select cancel_id' name='cancel_id'  style='width:100%''>";
            html += "<option value='0'>Select Cancellation Reason</option>";

            $(data).each(function(index, value) {
              html += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";

            });

            html += "</select>";


            $(this).parent().parent().find(".cancel_id").html(html);

            $('.data-table-plan-details-stated-items').DataTable().cell(row, 10, {
              order: 'applied'
            }).data(html);
            $(this).parent().parent().find(".cancel_id").prop('disabled', false);


          },
          error: function(data) {

          }
        });


      } else {

        $(this).parent().parent().find(".cancel_id").prop('disabled', true);

        html = "";

        html += "<select class='form-control select cancel_id' name='cancel_id'  style='width:100%' disabled><option value='0'>Select Cancellation Reason</option></select>";


        $(this).parent().parent().find(".cancel_id").html(html);


        $('.data-table-plan-details-stated-items').DataTable().cell(row, 10, {
          order: 'applied'
        }).data(html);

      }


      var claimoptions = '<select class="form-control select claim_type_id" name="claim_type_id" style="width:200px"><option value=' + id + '>' + $(this).parent().parent().find(".claim_type_id").find(":selected").text() + '</option>' + '</select>';

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 9, {
        order: 'applied'
      }).data(claimoptions);


    });

    $('.data-table-plan-details-stated-items').on('change', 'tr #quantity', function(e) {

      var col = $(this).index(),
        row = $(this).parent().parent().index();


      var unitprice = parseFloat(($(this).parent().parent().find("#unit_price").val()).replace(',', '')).toFixed(2);
      var originalBudget = parseFloat($(this).parent().parent().find("#quantity").val() * unitprice);
      var budget = parseFloat($(this).parent().parent().find("#stated_item_budget").val()).toFixed(2);
      var gst = $(this).parent().parent().find("#stated_item_gst_id").find(":selected").val();

      if (gst == '1') {
        budget = originalBudget + (originalBudget * .10);
      } else {
        budget = originalBudget;
      }

      var d = loadrecordplandetailstateditems.row($(this).closest('tr')[0]).data();


      $(this).parent().parent().find("#stated_item_budget").val(budget);
      $(this).parent().parent().find("#stated_item_budget").value = budget;

      updateTotalAmount();

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 4).data($(this).parent().find("#quantity").val());

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 7, {
        order: 'applied'
      }).data(budget);


      isTabClicked = false;
      setTimeout(function() {

        if (!isTabClicked) {
          $('#unit_price').focus();
          isTabClicked = true;
        }

      }, 200);

    });

    $('.data-table-plan-details-stated-items tbody').on('change', 'td .stated_items_id', function() {

      var col = $(this).index(),
        row = $(this).parent().parent().index();
      var stateditemid = $(this).parent().find(".stated_items_id").val();



      setTimeout(function() {
        $.ajax({

          url: "{{route('invoices.setunitprice') }}",
          type: "POST",
          data: {
            id: stateditemid,
            participant_id: $('#participant_id').val()
          },
          beforeSend: function() {
            // $('.loading').show();
          },
          success: function(data) {
            // $('.loading').hide();
          },
          error: function(data) {
            console.log(data);

          },
        }).done(function(data) {

          if (data == undefined || data == '') {
            data = 0;
          }

          var uprice = parseFloat((data).replace(',', '')).toFixed(2);
          $('.data-table-plan-details-stated-items').DataTable().cell(row, 5).data(uprice);
          $(this).parent().find("#unit_price").val(uprice);
          $('#stated_item_budget').trigger("change");

        });

      }, 200);



    });

    $('.data-table-plan-details-stated-items tbody').on('change', 'td', function() {
      var col = $(this).index(),
        row = $(this).parent().index();


      var value = $('.dataTables_filter input').val();
      if (value == '') {
        var d = loadrecordplandetailstateditems.row($(this).closest('tr')[0]).data();

        var desc = $(this).parent().parent().find("#description").val();
        var stateditemsoptions = '<select class="form-control select2 stated_items_id" name="stated_items_id" style="width:500px" required><option value=' + $(this).parent().find(".stated_items_id").find(":selected").val() + '>' + $(this).parent().find(".stated_items_id").find(":selected").text() + '</option>' + '</select>';
        var gstoptions = '<select class="form-control select stated_item_gst_id" id = "stated_item_gst_id" name="stated_item_gst_id" style="width:170px"><option value=' + $(this).parent().find("#stated_item_gst_id").find(":selected").val() + '>' + $(this).parent().find("#stated_item_gst_id").find(":selected").text() + '</option>' + '</select>';

        $('.data-table-plan-details-stated-items').DataTable().cell(row, 1, {
          order: 'applied'
        }).data(stateditemsoptions);



        $('.data-table-plan-details-stated-items').DataTable().cell(row, 8, {
          order: 'applied'
        }).data($(this).parent().find("#hours").val());

        $('.data-table-plan-details-stated-items').DataTable().cell(row, 11, {
          order: 'applied'
        }).data($(this).parent().find("#description").val());

        $('.data-table-plan-details-stated-items').DataTable().cell(row, 12, {
          order: 'applied'
        }).data($(this).parent().find("#invoice_details_id").val());

        // $('.data-table-plan-details-stated-items').DataTable().cell(row, 4).data($(this).parent().find("#quantity").val());

        // $('.data-table-plan-details-stated-items').DataTable().cell(row, 7, {
        //   order: 'applied'
        // }).data(budget);

        $(".stated_items_id").select2({

        });

        if (($("*:focus").attr("id") != "service_start_date") && ($("*:focus").attr("id") != "service_end_date")) {
          // console.log('test', $("*:focus").attr("id"));

          $('.data-table-plan-details-stated-items').DataTable().cell(row, 2, {
            order: 'applied'
          }).data($(this).parent().find("#service_start_date").val());

          $('.data-table-plan-details-stated-items').DataTable().cell(row, 3, {
            order: 'applied'
          }).data($(this).parent().find("#service_end_date").val());

          setTimeout(function() {
            loadrecordplandetailstateditems.columns.adjust().draw();
          }, 200);
        }


      }

    });


    $('.data-table-plan-details-stated-items').on('change', 'tr #unit_price', function() {

      var col = $(this).index(),
        row = $(this).parent().parent().index();

      var originalBudget = parseFloat($(this).parent().parent().find("#quantity").val() * $(this).parent().parent().find("#unit_price").val()).toFixed(2);
      var budget = parseFloat($(this).parent().parent().find("#stated_item_budget").val()).toFixed(2);
      var gst = $(this).parent().parent().find("#stated_item_gst_id").find(":selected").val();

      if (gst == '1') {
        budget = originalBudget + (originalBudget * .10);
      } else {
        budget = originalBudget;
      }

      $(this).parent().parent().find("#stated_item_budget").val(budget);
      $(this).parent().parent().find("#stated_item_budget").value = budget;

      updateTotalAmount();

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 5).data($(this).parent().find("#unit_price").val());

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 7, {
        order: 'applied'
      }).data(budget);
    });


    function updateTotalAmount() {
      var table = $(".data-table-plan-details-stated-items").DataTable();
      table.rows().data().map((row) => {

      });

      var totalBudget = 0;

      loadrecordplandetailstateditems.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();

        //var amountNode = loadrecordplandetailstateditems.cells(rowLoop, 7).nodes().to$()[0];

        var amountNode = $('.data-table-plan-details-stated-items').DataTable().cell(rowLoop, 7).data();

        //var amount_val = ($('input', amountNode).val() == null) ? 0 : ($('input', amountNode).val());

        var amount_val = (amountNode == 0) ? 0 : amountNode;

        if (amount_val != undefined && amount_val != '' && amount_val != null) {
          totalBudget = parseFloat(totalBudget) + parseFloat(amount_val);
        }
      });

      $('#box-header-totalAmount').text(ReplaceNumberWithCommas(
        parseFloat(totalBudget).toFixed(2)));

    }


    $('.data-table-plan-details-stated-items').on('change', 'tr #stated_item_gst_id', function() {

      var col = $(this).index(),
        row = $(this).parent().parent().index();

      var originalBudget = parseFloat($(this).parent().parent().find("#quantity").val() * $(this).parent().parent().find("#unit_price").val()).toFixed(2);
      var budget = parseFloat($(this).parent().parent().find("#stated_item_budget").val()).toFixed(2);
      var gst = $(this).parent().parent().find("#stated_item_gst_id").find(":selected").val();

      if (gst == '1') {
        budget = originalBudget + (originalBudget * .10);
      } else {
        budget = originalBudget;
      }

      // $(this).parent().parent().find("#stated_item_budget").val(budget);
      // $(this).parent().parent().find("#stated_item_budget").value = budget;

      updateTotalAmount();


      var gstoptions = '<select class="form-control select stated_item_gst_id" id = "stated_item_gst_id" name="stated_item_gst_id" style="width:170px"><option value=' + gst + '>' + $(this).parent().parent().find("#stated_item_gst_id").find(":selected").text() + '</option>' + '</select>';


      $('.data-table-plan-details-stated-items').DataTable().cell(row, 6, {
        order: 'applied'
      }).data(gstoptions);

      $('.data-table-plan-details-stated-items').DataTable().cell(row, 7, {
        order: 'applied'
      }).data(budget);

    });

    $('.data-table-plan-details-stated-items').on('click', 'tr .duplicateItem', function() {
      var d = loadrecordplandetailstateditems.row($(this).closest('tr')[0]).data();

      var desc = $(this).parent().parent().find("#description").val();
      var stateditemsoptions = '<select class="form-control select2 stated_items_id" name="stated_items_id" style="width: 500px" required><option value=' + $(this).parent().parent().find(".stated_items_id").find(":selected").val() + '>' + $(this).parent().parent().find(".stated_items_id").find(":selected").text() + '</option>' + '</select>';
      var gstoptions = '<select class="form-control select stated_item_gst_id" id ="stated_item_gst_id" name="stated_item_gst_id" style="width:170px"><option value=' + $(this).parent().parent().find("#stated_item_gst_id").find(":selected").val() + '>' + $(this).parent().parent().find("#stated_item_gst_id").find(":selected").text() + '</option>' + '</select>';
      var claimoptions = '<select class="form-control select claim_type_id" name="claim_type_id" style="width:200px"><option value=' + $(this).parent().parent().find(".claim_type_id").find(":selected").val() + '>' + $(this).parent().parent().find(".claim_type_id").find(":selected").text() + '</option>' + '</select>';
      var canceloptions = '';

      if ($(this).parent().parent().find(".claim_type_id").find(":selected").val() == 1) {
        canceloptions = '<select class="form-control select cancel_id" name="cancel_id" style="width:200px"><option value=' + $(this).parent().parent().find(".cancel_id").find(":selected").val() + '>' + $(this).parent().parent().find(".cancel_id").find(":selected").text() + '</option>' + '</select>';
      } else {
        canceloptions = '<select class="form-control select cancel_id" name="cancel_id" style="width:200px" disabled><option value=' + $(this).parent().parent().find(".cancel_id").find(":selected").val() + '>' + $(this).parent().parent().find(".cancel_id").find(":selected").text() + '</option>' + '</select>';
      }

      loadrecordplandetailstateditems.row.add({
        "action": '<button data-id="' + "1" + '" class="btn btn-danger btn-sm deleteItem " type="button"> <i class="fa fa-trash"></i> </button> <button data-id="' + "1" + '" class="btn btn-success btn-sm duplicateItem " type="button"> <i class="fa fa-copy"></i> </button>',
        "invoice_details_id": 0,
        "stated_item": stateditemsoptions,
        "stated_item_unit_price": $(this).parent().parent().find("#unit_price").val(),
        'stated_item_quantity': $(this).parent().parent().find("#quantity").val(),
        "stated_item_budget": $(this).parent().parent().find("#stated_item_budget").val(),
        "stated_item_gst_code": gstoptions,
        "stated_item_gst_id": $(this).parent().parent().find("#stated_item_gst_id").find(":selected").val(),
        "description": $(this).parent().parent().find("#description").val(),
        "service_start_date": $(this).parent().parent().find("#service_start_date").val(),
        "service_end_date": $(this).parent().parent().find("#service_end_date").val(),
        "hours": $(this).parent().parent().find("#hours").val(),
        "claim_type": claimoptions,
        "claim_type_id": $(this).parent().parent().find(".claim_type_id").find(":selected").val(),
        "cancellation_reason": canceloptions,
        "claim_reference": null
      }).draw();

      var currentPage = loadrecordplandetailstateditems.page();


      var index = table.row(0).index(),
        rowCount = table.data().length - 1,
        insertedRow = table.row(rowCount).data(),
        tempRow;

      for (var i = rowCount; i > index; i--) {
        tempRow = table.row(i - 1).data();
        table.row(i).data(tempRow);
        table.row(i - 1).data(insertedRow);

      }

      updateTotalAmount();

      $(".stated_items_id").select2({


      });

    });

    $(".data-table-plan-details-stated-items").on('click', '.deleteItem', function() {
      loadrecordplandetailstateditems.row($(this).closest('tr')[0]).remove().draw(false);
      updateTotalAmount();
    });


    var addstateditems = "";
    var addgst = "";
    var addclaimtype = "";
    var addstateditemsshow = false;
    var gsthtml = "";
    var claimtypehtml = "";

    var count = 0;

    var table = $('.data-table-plan-details-stated-items').DataTable();

    var gstData = <?php echo json_encode(@$gst); ?>;
    var claimTypeData = <?php echo json_encode(@$claim_type); ?>;

    $(document).on('click', '#add_line', function() {

      //var d = loadrecordplandetailstateditems.row($(this).closest('tr')[0]).data();
      $.ajax({
        url: "{{ route('invoices.getstateditems') }}",
        type: "POST",
        dataType: 'json',
        success: function(data) {
          if (addstateditems == "") {
            addstateditems += '<select class="form-control select2 stated_items_id" name="stated_items_id"  style="width: 500px" required>';
            addstateditems += "<option value='0'>Select Support Item &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</option>";
            $(data).each(function(index, value) {

              addstateditems += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";

            });
            addstateditems += '</select>';

            addstateditemsshow = true;
            // $(thisbtn).html(getstateditems);
          }

          if (addgst == "") {

            gsthtml = '<select class="form-control select stated_item_gst_id" id = "stated_item_gst_id" name="stated_item_gst_id"  style="width: 170px" !important">';
            gsthtml += "<option value='0'>Select GST</option>";
            $(gstData).each(function(index, value) {
              if (value.id == '2') {
                // console.log(thisbtn.val());
                gsthtml += "<option value=" + value.id + " selected> " + value.code + " / " + value.description + "</option>";
              } else {
                gsthtml += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";
              }
            });
            gsthtml += '</select>';

            addgst = gsthtml;

          }


          if (addclaimtype == "") {

            claimtypehtml += '<select class="form-control select claim_type_id" name="claim_type_id"  style="width: 200px">';
            claimtypehtml += "<option value='0'>Select Claim Type</option>";
            $(claimTypeData).each(function(index, value) {

              claimtypehtml += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";

            });
            claimtypehtml += '</select>';
            // console.log()

            addclaimtype = claimtypehtml;

          }

        },
        error: function(data) {}

      }).done(function(data) {

        console.log(5);


        var currentPage = loadrecordplandetailstateditems.page();

        var cancelreason = "";

        cancelreason += "<select class='form-control select cancel_id' name='cancel_id'  style='width:100%' disabled><option value='0'>Select Cancellation Reason</option></select>";

        loadrecordplandetailstateditems.row.add({
          "action": '<button data-id="' + "0" + '" class="btn btn-danger btn-sm deleteItem " type="button"> <i class="fa fa-trash"></i> </button> <button data-id="' + "1" + '" class="btn btn-success btn-sm duplicateItem " type="button"> <i class="fa fa-copy"></i> </button>',
          "invoice_details_id": 0,
          "stated_item": addstateditems,
          "stated_item_unit_price": 0,
          'stated_item_quantity': 0,
          "stated_item_budget": 0,
          "stated_item_gst_code": addgst,
          "description": "",
          "service_start_date": "",
          "service_end_date": "",
          "hours": 0,
          "claim_type": addclaimtype,
          "cancellation_reason": cancelreason,
          "claim_reference": null
        }).draw();


        var index = table.row(0).index(),
          rowCount = table.data().length - 1,
          insertedRow = table.row(rowCount).data(),
          tempRow;

        for (var i = rowCount; i > index; i--) {
          tempRow = table.row(i - 1).data();
          table.row(i).data(tempRow);
          table.row(i - 1).data(insertedRow);
        }

        $(".select2").select2({});

        setTimeout(function() {
          loadrecordplandetailstateditems.columns.adjust().draw();

          var value = $('.dataTables_filter input').val();
          if (value != '') {
            $(".select2").select2({});
          }
        }, 200);

      });




    });

    var recordSaved = false;
    var isSaveandClose = false;
    $('#ModalForm').submit(function(e) {
      e.preventDefault();
      let formdata = $('form').serialize();

      var stated_item_id = [];
      var invoice_details_id = [];
      var claim_reference = [];
      var stated_item_unit_price = [];
      var stated_item_quantity = [];
      var stated_item_gst_code = [];
      var description = [];
      var stated_item_budget = [];
      var service_start_date = [];
      var service_end_date = [];
      var amount = [];
      var hours = [];
      var claim_type = [];
      var cancellation_reason = [];


      loadrecordplandetailstateditems.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        // console.log(loadrecordplandetailstateditems.cells(rowLoop, 11).nodes().to$()[0]);
        var stated_item_idNode = loadrecordplandetailstateditems.cells(rowLoop, 1).nodes().to$()[0];
        var service_start_dateNode = loadrecordplandetailstateditems.cells(rowLoop, 2).nodes().to$()[0];
        var service_end_dateNode = loadrecordplandetailstateditems.cells(rowLoop, 3).nodes().to$()[0];
        var stated_item_quantityNode = loadrecordplandetailstateditems.cells(rowLoop, 4).nodes().to$()[0];
        var stated_item_unit_priceNode = loadrecordplandetailstateditems.cells(rowLoop, 5).nodes().to$()[0];
        var stated_item_gst_codeNode = loadrecordplandetailstateditems.cells(rowLoop, 6).nodes().to$()[0];
        var amountNode = loadrecordplandetailstateditems.cells(rowLoop, 7).nodes().to$()[0];
        var hoursNode = loadrecordplandetailstateditems.cells(rowLoop, 8).nodes().to$()[0];
        var claim_typeNode = loadrecordplandetailstateditems.cells(rowLoop, 9).nodes().to$()[0];
        var cancellation_reasonNode = loadrecordplandetailstateditems.cells(rowLoop, 10).nodes().to$()[0];
        var descriptionNode = loadrecordplandetailstateditems.cells(rowLoop, 11).nodes().to$()[0];

        var stated_item_id_val = ($('select', stated_item_idNode).val());
        var description_val = ($('textarea', descriptionNode).val());
        var service_start_date_val = ($('input', service_start_dateNode).val());
        var service_end_date_val = ($('input', service_end_dateNode).val());
        var stated_item_quantity_val = parseFloat($('input', stated_item_quantityNode).val());
        var stated_item_unit_price_val = parseFloat($('input', stated_item_unit_priceNode).val());
        var stated_item_gst_code_val = ($('select', stated_item_gst_codeNode).val());
        var amount_val = ($('input', amountNode).val());
        var hours_val = parseFloat($('input', hoursNode).val());
        var claim_type_val = ($('select', claim_typeNode).val());
        var cancellation_reason_val = ($('select', cancellation_reasonNode).val());

        stated_item_id.push(stated_item_id_val);
        invoice_details_id.push(data.invoice_details_id);
        claim_reference.push(data.claim_reference);
        description.push(description_val);
        service_start_date.push(service_start_date_val);
        service_end_date.push(service_end_date_val);
        stated_item_quantity.push(stated_item_quantity_val);
        stated_item_unit_price.push(stated_item_unit_price_val);
        stated_item_gst_code.push(stated_item_gst_code_val);
        amount.push(amount_val);
        hours.push(hours_val);
        claim_type.push(claim_type_val);
        cancellation_reason.push(cancellation_reason_val);

      });

      // console.log(stated_item_id);

      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      today = yyyy + '-' + mm + '-' + dd;

      // alert($('#invoice_email_id').find(":selected").val());
      $.ajax({
        url: "{{ route('invoices.saverecord') }}",
        data: {
          'invoice_id': $('.invoice_id').val(),
          // 'claim_reference': $('.claim_reference').val(),
          'participant_id': $('#participant_id').val(),
          'planmanager_subscriptions_id': $('#planmanager_subscriptions_id').val(),
          'invoice_number': $('#invoice_number').val(),
          'invoice_linkemails_id': $('#invoice_linkemails_id').val(),
          'invoice_email_id': $('#invoice_email_id').find(":selected").val(), //$('#invoice_email_id').val(),
          'invoice_date': $('#invoice_date').val(),
          'due_date': $('#due_date').val(),
          // 'reference_number': $('#reference_number').val(),
          'service_provider_ABN': $('#abn').val(),
          // 'service_provider_acc_number': $('#service_provider_acc_number').val(),
          'serviceprovider_id': $('#serviceprovider_id').val(),
          'invoice_details_id': JSON.stringify(invoice_details_id),
          'stated_item_id': JSON.stringify(stated_item_id),
          'description': JSON.stringify(description),
          'service_start_date': JSON.stringify(service_start_date),
          'service_end_date': JSON.stringify(service_end_date),
          'stated_item_quantity': JSON.stringify(stated_item_quantity),
          'stated_item_unit_price': JSON.stringify(stated_item_unit_price),
          'stated_item_gst_code': JSON.stringify(stated_item_gst_code),
          'stated_item_budget': JSON.stringify(amount),
          'hours': JSON.stringify(hours),
          'claim_type': JSON.stringify(claim_type),
          'cancellation_reason': JSON.stringify(cancellation_reason),
          'claim_reference': JSON.stringify(claim_reference),
          'route': '<?= @$route ?>',
          'status': ($('#status_id').val() == '') ? "1" : $('#status_id').val()

        },
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('.saveBtn').attr('disabled', true);
          $('.save-close').attr('disabled', true);

        },
        success: function(data) {

          if (data.has_error) {

            OkayModal("Error", data.msg);
            if (!data.insufficient_budget) {
              $('#invoice_number').val("");
            }

            // confirm(data.msg);

            recordSaved = false;

            $('#clickmodal').click();
          } else {

            $('.invoice_id').val(data.return_id);
            $('.saveBtn').html('Save');


            if (isSaveandClose) {
              window.location.href = "{{route('invoices.loadrecords')}}";

              isSaveandClose = false;
            } else {
              OkayModal("Success", "Record saved successfully.");

              $('#clickmodal').click();
              // $('#clickmodal').click();
            }
            recordSaved = true;

          }
          $('.saveBtn').attr('disabled', false);
            $('.save-close').attr('disabled', false);
          //$('#clickmodal').click();
          // loadrecordplandetailstateditems.draw();
        },
        error: function(data) {
          $('.saveBtn').html('Save');
        }
      });
    });

    $('.save-close').on('click', function() {


      $('#ModalForm').click();
      isSaveandClose = true;

    })

    // $(".popupModalButton").click(function(event) {

    //   clicked = true;
    //   console.log('click');

    // });

    // $(document).on('click', '.popupModalButton', function() {

    //   if (recordSaved) {
    //     var redirect = window.location.host + '/invoices';
    //     window.location.href = window.location.protocol + '//' + redirect;

    //     //location.reload();
    //     $('#ModalForm').trigger("reset");
    //     $('#ajaxModel').modal('hide');


    //     $('.saveBtn').prop('disabled', false);


    //     $('.saveBtn').html('Save');
    //     $('.saveBtn').prop('disabled', false);
    //   }
    // });


    <?php
    if ($route == "View") {
    ?> $('input').each(function(index, value) {
        $(value).prop('disabled', true);
      })

      $('select').each(function(index, value) {
        $(value).prop('disabled', true);
      })


      $('.deleteItem ').each(function(index, value) {
        $(value).prop('disabled', true);
      })

      $('.duplicateItem ').each(function(index, value) {
        $(value).prop('disabled', true);
      })

      $('#add_line').hide();
      $('.saveBtn').hide();
    <?php } else if ($route == "Duplicate") {  ?> $('#invoice_number').val('');
      $('#invoice_id').val('0');

    <?php } ?>

    // $('.loading').show();

    $('.import').on('change', function() {

      var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
      if (regex.test($(this).val().toLowerCase())) {
        if (typeof(FileReader) != "undefined") {
          var reader = new FileReader();
          reader.onload = function(e) {
            var invoices = new Array();
            var rows = e.target.result.split("\r\n");
            for (var i = 0; i < rows.length; i++) {
              var cells = rows[i].split(",");
              if (cells.length > 2) {
                var invoice = {};
                invoice.SupportNumber = cells[4];
                invoice.DateStart = cells[2];
                invoice.DateEnd = cells[3];
                invoice.Qty = cells[6];
                invoice.UnitPrice = cells[8];
                invoice.Gst = cells[9];
                invoice.Hrs = cells[7];
                invoice.Amount = 0;

                invoices.push(invoice);
              }
            }
            // $("#dvCSV").html('');
            // $("#dvCSV").append(JSON.stringify(customers));

            // console.log((JSON.stringify(invoices)));


            var cancelreason = "<select class='form-control select cancel_id' name='cancel_id'  style='width:100%' disabled><option value='0'>Select Cancellation Reason</option></select>";

            var claimtypehtml = "";

            claimtypehtml += '<select class="form-control select claim_type_id" name="claim_type_id"  style="width: 200px">';
            claimtypehtml += "<option value='0'>Select Claim Type</option>";
            $(claimTypeData).each(function(index, value) {

              claimtypehtml += "<option value=" + value.id + "> " + value.code + " / " + value.description + "</option>";

            });
            claimtypehtml += '</select>';
            // console.log()

            addstateditems = "";

            // addstateditems += '<select class="form-control select2 stated_items_id" name="stated_items_id"  style="width: 500px" required>';
            // addstateditems += "<option value='0'>Select Support Item &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</option>";
            // $(stateditems).each(function(index, value) {

            //   addstateditems += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";

            // });
            // addstateditems += '</select>';
            var counter = 0;
            var targetcompleted = (invoices.length - 1);
            var progress = 0;




            $.ajax({

              url: "{{ route('invoices.getstateditems') }}",
              type: "POST",
              dataType: 'json',
              beforeSend: function() {
                $('.loading').show();
              },
              success: function(data) {
                $('.loading').hide();
              },
            }).done(function(data) {
              var stateditems = [];
              $(data).each(function(index, value) {
                stateditem = {};
                stateditem.id = value.id;
                stateditem.support_item_number = value.support_item_number;
                stateditem.support_item_name = value.support_item_name;
                stateditems.push(stateditem);
              });


              $(invoices).each(function(index, value) {
                if (index != 0) {
                  // console.log(value);

                  var DateStart = (value.DateStart).split('.');
                  var service_start_date = DateStart[2] + '-' + DateStart[1] + '-' + DateStart[0];

                  var DateEnd = (value.DateEnd).split('.');
                  var service_end_date = DateEnd[2] + '-' + DateEnd[1] + '-' + DateEnd[0];


                  counter++;



                  gsthtml = '<select class="form-control select stated_item_gst_id" id = "stated_item_gst_id" name="stated_item_gst_id"  style="width: 170px" !important">';
                  gsthtml += "<option value='0'>Select GST</option>";
                  $(gstData).each(function(index, data) {
                    if (value.Gst == data.code) {
                      // console.log(thisbtn.val());
                      gsthtml += "<option value=" + data.id + " selected> " + data.code + " / " + data.description + "</option>";
                    } else {
                      gsthtml += "<option value=" + data.id + "> " + data.code + " / " + data.description + "</option>";
                    }
                  });
                  gsthtml += '</select>';

                  addstateditems = "";
                  addstateditems += '<select class="form-control select2 stated_items_id" name="stated_items_id"  style="width: 500px !important" required>';
                  addstateditems += "<option value='0'>Select Support Item &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</option>";
                  $(stateditems).each(function(index, data) {

                    if (value.SupportNumber == data.support_item_number) {
                      // console.log(thisbtn.val());
                      addstateditems += "<option value=" + data.id + " selected> " + data.support_item_number + " / " + data.support_item_name + "</option>";
                    } else {
                      addstateditems += "<option value=" + data.id + "> " + data.support_item_number + " / " + data.support_item_name + "</option>";

                    }

                  });
                  addstateditems += '</select>';
                  var amount = 0;
                  var qty = 1;
                  if (value.Qty == "") {
                    amount = 1 * parseFloat(value.UnitPrice).toFixed(2);
                  } else {
                    amount = parseInt(value.Qty).toFixed(2) * parseFloat(value.UnitPrice).toFixed(2);
                    qty = value.Qty;
                  }


                  loadrecordplandetailstateditems.row.add({
                    "action": '<button data-id="' + "0" + '" class="btn btn-danger btn-sm deleteItem " type="button"> <i class="fa fa-trash"></i> </button> <button data-id="' + "1" + '" class="btn btn-success btn-sm duplicateItem " type="button"> <i class="fa fa-copy"></i> </button>',
                    "invoice_details_id": 0,
                    "stated_item": addstateditems,
                    "stated_item_unit_price": value.UnitPrice,
                    'stated_item_quantity': qty,
                    "stated_item_budget": amount,
                    "stated_item_gst_code": gsthtml,
                    "description": "",
                    "service_start_date": service_start_date,
                    "service_end_date": service_end_date,
                    "hours": value.Hrs,
                    "claim_type": claimtypehtml,
                    "cancellation_reason": cancelreason,
                    "claim_reference": null
                  }).draw();

                }
              })


              statusDropdown += "<option value=" + 6 + " selected>Paid</option>";
              $('#status_id').html(statusDropdown);
              $('#status_id').prop('disabled', true);

            });
          }
          reader.readAsText($(this)[0].files[0]);
        } else {
          alert("This browser does not support HTML5.");
        }
      } else {
        alert("Please upload a valid CSV file.");
      }


    });

  });


  function uniqueFilter(value, index, self) {
    return self.indexOf(value) === index;
  }

  function ReplaceNumberWithCommas(yourNumber) {
    //Seperates the components of the number
    var n = yourNumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
  }
</script>


<script>
  // function validateInvoiceNo() {

  //   alert('onblur');
  // }

  function download_csv_file() {
    //console.log(formatDate('Sun May 11,2014'));

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = yyyy + "" + mm + "" + dd + ".csv";
    console.log(today);
    var csvFileData = [
      ['RegistrationNumber', 'NDISNumber', 'SupportsDeliveredFrom', 'SupportsDeliveredTo', 'SupportNumber', 'ClaimReference', 'Quantity', 'Hours', 'UnitPrice', 'GSTCode', 'AuthorisedBy', 'ParticipantApproved', 'InKindFundingProgram', 'ClaimType', 'CancellationReason', 'ProviderABN']
    ];

    $.ajax({
      url: "{{ route('invoices.exporttoproda') }}",
      type: "POST",
      dataType: 'json',
      success: function(data) {
        $(data).each(function(index, value) {
          csvFileData.push([value.registrationnumber, value.ndis_number, value.service_start_date, value.service_end_date, value.support_item_number, value.reference_number, value.quantity, value.hours, value.unit_price, value.gst_code, value.authorizedby, value.participantapproved, value.inkindfundingprogram, value.claimtype, value.cancellationreason, value.abn]);
        });

        var csv = '';
        csvFileData.forEach(function(row) {
          csv += row.join(',');
          csv += "\n";
        });

        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';
        hiddenElement.download = today;
        hiddenElement.click();

      },
      error: function(data) {
        console.log('error:', data)
      }
    });

  }

  function formatDate(date) {
    var d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

    if (month.length < 2)
      month = '0' + month;
    if (day.length < 2)
      day = '0' + day;

    var filename = [year, month, day].join('-');
    filename = filename.replace(/-/g, "");
    return filename;
  }
</script>

<script>
  function uploadInvoice() {
    var invoice_id = <?php echo json_encode(@$response['invoices']->id); ?>;
    toastr.options = {
      "closeButton": true,
      "newestOnTop": true,
      "positionClass": "toast-top-right"
    };

    $('.spin-loader-wrap').show();
    $('#invoice').prop('disabled', true);
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });
    $('#ModalForm').submit();
    var invoice = document.getElementById('invoice').files[0];
    var invoice_name = invoice.name;
    var invoice_extension = invoice_name.split('.').pop().toLowerCase();
    if (invoice && invoice.size > 2097152) {
      $('.spin-loader-wrap').hide();
      toastr.error('File size should be less than 2MB');
      return false;
    }
    if (jQuery.inArray(invoice_extension, ['pdf']) == -1) {
      $('.spin-loader-wrap').hide();
      toastr.error('Only Pdf File Type Allowed')
      return false;
    }
    var form_data = new FormData();
    form_data.append("file", invoice);
    form_data.append("invoice_id", invoice_id);

    $.ajax({
      url: "{{ route('invoice.uploadInvoice') }}",
      method: 'POST',
      data: form_data,
      contentType: false,
      cache: false,
      processData: false,
      success: function(resp) {
        $('.spin-loader-wrap').hide();
        if (resp.status == true) {
          toastr.success(resp.msg);
          setTimeout(function() {
            location.reload(true);
          }, 2000);
        } else {
          toastr.error(resp.error_msg);
        }
        console.log(resp);
      }
    });
  }
  <?php if (!empty($invoice_document)) { ?>
    document.querySelector('#copy-text-btn').onclick = function() {
      let inputElement = document.getElementById('invoice-link');
      inputElement.type = 'text';
      inputElement.select();
      document.execCommand('copy');
      inputElement.type = 'hidden';
      document.querySelector('.tooltiptext').style.visibility = 'visible';
      document.querySelector('.tooltiptext').style.opacity = 1;
    }
  <?php } ?>
</script>

@endsection