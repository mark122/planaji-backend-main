@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<style>
  .import-div {
    position: relative;
    overflow: hidden;
    float: right;
  }

  .import {
    position: absolute;
    opacity: 0;
    right: 0;
    top: 0;
  }
</style>
@endsection

@section('content')

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-custom">
          <div class="box-header box-header-custom">
            <h3 class="box-title">Reconciliation
            </h3>

          </div>
          <div class="box-body">
            <div class="crud-buttons">
              <!-- <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewInvoice"> View</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteInvoice"> Delete</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editInvoice"> Edit</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="duplicateInvoice"> Duplicate</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="updateInvoice"> Update Status</a> -->
              <!-- <button type="button" class="btn btn-success ml-5 " data-toggle="modal" data-target="#modal-invoice-status" id="updateInvoice">
                Update Status
              </button> -->
              <!-- <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewInvoice"> Add Invoice</a> -->
              <!-- <a class="btn btn-success ml-5 " style="float:right; margin-right: 8px" href="javascript:void(0)" id="validateInvoice"> Import PRODA</a> -->
              <div class="import-div btn btn-success">
                <i style="display:none !important;" class="loading fa fa-spinner fa-pulse"></i> Import PRODA
                <input class="import" type="file" name="file" accept=".csv" />
              </div>
              <a class="btn btn-success ml-5 " style="float:right; margin-right: 8px" id="exportForQB" onclick="download_proda_file()"> Export PRODA</a>
            </div>
            <div class="crud-buttons">
              <!-- Rounded switch -->
              Hide Successful Invoices
              <input class="checkbox-toggle hidesuccessful-toggle" type="checkbox" checked data-toggle="toggle">
            </div>
            <table class="table table-bordered data-table display nowrap table-example1">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>Invoice Number</th>
                  <th>Support Item</th>
                  <th>PRODA Status</th>
                  <th>Service Start Date</th>
                  <!-- <th>Due Date</th>
                  <th>Reference Number</th> -->
                  <th>Service End Date</th>
                  <th>Quantity</th>
                  <th>Unit Price</th>
                  <th>GST Code</th>
                  <th>Amount</th>
                  <th>Description</th>
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

  </section>
  <!-- /.content -->
</div>

<div class="modal fade " id="log_imported" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Logs Imported</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
          <table class="table table-bordered data-table-logs-imported display nowrap table-example1" style="width:100%">
            <thead>
              <tr>
                <th>Claim Reference</th>
                <th>Capped Price</th>
                <th>Error Message</th>
                <th>Payment Request Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
          <button type="submit" class="btn btn-info pull-right" data-dismiss="modal">Close</button>
        </div>
        <!-- /.box-footer -->
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<script src="https://unpkg.com/imask"></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


<script>
  function download_proda_file() {
    //console.log(formatDate('Sun May 11,2014'));
    // alert();
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yy = String(today.getFullYear()).substr(-2);

    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    var ms = String(today.getMilliseconds()).slice(-2);
    var filename = 'PRODA' + '' + mm + '' + dd + '' + yy + '_' + h + '' + m + '' + s + '' + '.csv';

    //today = 'Invoices_PRODA_' + yy + "" + mm + "" + dd + ".csv";
    var csvFileData = [
      ['RegistrationNumber', 'NDISNumber', 'SupportsDeliveredFrom', 'SupportsDeliveredTo', 'SupportNumber', 'ClaimReference', 'Quantity', 'Hours', 'UnitPrice', 'GSTCode', 'AuthorisedBy', 'ParticipantApproved', 'Capped Price', 'Payment Request Status', 'Error Message', 'ClaimType', 'CancellationReason', 'ProviderABN']
    ];

    $.ajax({
      url: "{{ route('reconciliation.exporttoproda') }}",
      type: "POST",
      dataType: 'json',
      success: function(data) {
        $(data).each(function(index, value) {
          csvFileData.push([value.registrationnumber, value.ndis_number, value.service_start_date, value.service_end_date, value.support_item_number, value.reference_number, value.quantity, value.hours, value.unit_price, value.gst_code, value.authorizedby, value.participantapproved, value.capped_price, value.payment_request_status, value.error_message, value.claimtype, value.cancellationreason, value.abn]);

        });

        var csv = '';
        csvFileData.forEach(function(row) {
          csv += row.join(',');
          csv += "\n";
        });

        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';
        hiddenElement.download = filename;
        hiddenElement.click();

      },
      error: function(data) {
        console.log('error:', data)
      }
    });

  }
  $(function() {

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    const dollarUSLocale = new Intl.NumberFormat('en-US', {
      currency: 'USD',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })

    var table = $('.data-table').DataTable({

      responsive: true,

      processing: true,

      serverSide: true,

      pageLength: 50,

      ajax: "{{route('reconciliation.loadrecords')}}",
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          // return '<input type="radio" name="invoice-selection" value="' + $('<div/>').text(data.id).html() + '">';
          return '<input type="checkbox" name="invoice-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
        },
        {
          data: 'invoice_number',
          name: 'invoice_number'
        },
        {
          data: 'item',
          name: 'item'
        },
        {
          data: 'payment_request_status',
          name: 'payment_request_status',
          'render': function(data, type, row) {

            $_status = '';

            if (data == "SUCCESSFUL") {
              $_status = '<span style="color: green">' + row.payment_request_status + '</span>';
            } else if (data == "ERROR") {
              $_status = '<span style="color: red">' + row.payment_request_status + '</span>';
            } else {
              $_status = '<span style="color: black">' + row.payment_request_status + '</span>';
            }

            return $_status;
          }
        },
        {
          data: 'service_start_date',
          name: 'service_start_date'
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
          data: 'service_end_date',
          name: 'service_end_date'
        },
        {
          data: 'quantity',
          name: 'quantity'
        },
        {
          data: 'unit_price',
          name: 'unit_price'
        },
        {
          data: 'gstcode',
          name: 'gstcode'
        },
        {
          data: 'amount',
          name: 'amount',
          'render': function(data, type, row) {

            return parseFloat(row.amount).toFixed(2);
          }
        },
        {
          data: 'description',
          name: 'description'
        }

      ],
      initComplete: function(data) {

        // set first record selected
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);
      },
      drawCallback: function(settings) {
        // set first record selected
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);
      }
    });

    $('.data-table').on('length.dt', function(e, settings, len) {
      // console.log('New page length: ' + len);
      $.ajax({
        url: "{{ route('invoices.savepagelength') }}",
        data: {
          page_length: (len != undefined) ? len : 50
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {},
        error: function(data) {
          console.log('Error: ', data.msg);
        }
      });

    });


    $('.hidesuccessful-toggle').on('change', function() {
      var except_success = 'ERROR|NEW';
      var is_hide = ($('.hidesuccessful-toggle').is(':checked') == true) ? except_success : '';

      $.ajax({
        url: "{{ route('reconciliation.hidesuccessfulinvoices') }}",
        data: {
          hidesuccessfulinvoices: $('.hidesuccessful-toggle').is(':checked')
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          table.column(3).search(is_hide, true, false).draw();
        },
        error: function(data) {
          console.log('error', data.msg);
        }
      });

    })


    var hide_successful_invoices = '<?= (@$user_data->hide_successful_invoices); ?>';

    if (hide_successful_invoices == '1') {
      $('.hidesuccessful-toggle').prop('checked', true).trigger('change');

    } else {
      $('.hidesuccessful-toggle').prop('checked', false).trigger('change');
    }

    var pageLengthSaved = '<?= (@$user_data->page_length); ?>';
    if (pageLengthSaved != null) {

      table.page.len(pageLengthSaved).draw();

    }

    var table_logs_imported = $('.data-table-logs-imported').DataTable({
      responsive: true,
      scrollX: true,
      pageLength: 50
    });

    $('.import').on('change', function() {
      var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv)$/;
      if (regex.test($(this).val().toLowerCase())) {
        if (typeof(FileReader) != "undefined") {
          var reader = new FileReader();
          reader.onload = function(e) {
            var reconciliations = new Array();
            var rows = e.target.result.split("\r\n");
            for (var i = 0; i < rows.length; i++) {
              var cells = rows[i].split(",");
              if (i > 0) {
                var reconciliation = {};
                reconciliation.ClaimReference = cells[5];
                reconciliation.CappedPrice = cells[12];
                reconciliation.PaymentRequestStatus = cells[13];
                reconciliation.ErrorMessage = cells[14];
                reconciliations.push(reconciliation);
              }
            }
            $.ajax({
              url: "{{ route('reconciliation.upload') }}",
              method: 'POST',
              data: {
                reconciliations: reconciliations
              },
              dataType: 'json',
              success: function(e) {
                if (e.success) {
                  OkayModal("Success", e.msg);
                  $('#log_imported').modal({
                    backdrop: 'static',
                    keyboard: false
                  })
                  data_table_logs_imported(e.get_affected_data);

                  table.draw();
                } else {
                  OkayModal("Info Message", e.msg);
                  $('#clickmodal').click();
                }
              }
            });
          }
          reader.readAsText($(this)[0].files[0]);
        } else {
          OkayModal("Error", "This browser does not support HTML5.");
          $('#clickmodal').click();
        }
      } else {
        OkayModal("Error", "Please upload a valid CSV file.");
        $('#clickmodal').click();
      }

      $(this).val('');
      table_logs_imported.clear().draw();
    });

    function data_table_logs_imported(oData) {

      var arrayData = [];
      $(oData).each(function(index, value) {
        arrayData[index] = [
          value.ClaimReference,
          value.capped_price,
          value.error_message,
          value.payment_request_status,
        ]
      })
      table_logs_imported.rows.add(arrayData).draw();

      setTimeout(function() {
        // $('.select2').select2();

        table_logs_imported.columns.adjust().draw();
      }, 200);
    }
  })
</script>

@endsection