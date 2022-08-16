@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-custom">
          <div class="box-header box-header-custom">
            <h3 class="box-title">Invoices
            </h3>

          </div>
          <div class="box-body">
            <div class="crud-buttons">
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewInvoice"> View</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteInvoice"> Delete</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editInvoice"> Edit</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="duplicateInvoice"> Duplicate</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="updateInvoice"> Update Status</a>
              <!-- <button type="button" class="btn btn-success ml-5 " data-toggle="modal" data-target="#modal-invoice-status" id="updateInvoice">
                Update Status
              </button> -->
              <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewInvoice"> Add Invoice</a>
              <a class="btn btn-success ml-5 " style="float:right; margin-right: 8px" href="javascript:void(0)" id="validateInvoice"> Validate Invoice</a>
              <a class="btn btn-success ml-5 " style="float:right; margin-right: 8px" id="exportForQB" onclick="download_qb_file()"> Export for QB</a>
              <a class="btn btn-success ml-5 " style="float:right; margin-right: 8px" onclick="download_proda_file()" id="exportForPRODA"> Export for PRODA</a>
            </div>
            <div class="crud-buttons">
              <!-- Rounded switch -->
              Hide Paid Invoices
              <input class="checkbox-toggle" type="checkbox" checked data-toggle="toggle">
            </div>
            <table class="table table-bordered data-table display nowrap table-example1">
              <thead>
                <tr>
                  <th class="dt-body-center">
                    <!-- UPDATE: SELECT ALL FOR INVOICES - 2022-13-07 -->
                    <input type="checkbox" id="invoice-select-all" name="invoice-select-all">
                    <label for="invoice-select-all"> Select all</label>
                    <!-- CLOSING UPDATE-->
                  </th>
                  <th>NDIS Number</th>
                  <th>Participant Name</th>
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
          <!-- /.box-body -->
        </div>

      </div>

    </div>

  </section>
  <!-- /.content -->
</div>
<div class="modal fade" id="modal-invoice-status">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Invoice Status</h4>
      </div>
      <div class="modal-body">

        <label for="ndislist" class="form control">Batch Update</label>
        <select class="form-control select2" name="invoice_status_id" id="invoice_status_id" style="width:100%" required>
          <option value='0' selected>Select Invoice Status</option>
          @foreach ($invoice_status as $status)
          <option value="{{ $status->id }}">{{ $status->description}} </option>
          @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="updateStatusInvoice">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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
  $(function() {



    $.ajaxSetup({

      headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

      }

    });



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

    //Initialize Select2 Elements
    $('.select2').select2()


    $('#claimtype').on('change', function() {

      var id = $(this).val();

      if (id === '1') {
        $('#cancellationreason').prop('disabled', false);

        $.ajax({
          url: "{{ route('invoices.getcancellationreason') }}",
          data: {
            serviceprovider_id: id
          },
          type: "POST",
          dataType: 'json',
          success: function(data) {

            var html = "";

            html += "<option value='0'>Select Cancellation Reason</option>";

            $(data).each(function(index, value) {
              html += "<option value=" + value.id + "> " + value.code + "/ " + value.description + "</option>";

            });

            $('#cancellationreason').html(html);

          },
          error: function(data) {

          }
        });
      } else {

        $('#cancellationreason').prop('disabled', true);

        var html = "";

        html += "<option value='0'>Select Cancellation Reason</option>";

        $('#cancellationreason').html(html);
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

      ajax: "{{route('invoices.loadrecords')}}",
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
          data: 'ndis_number',
          name: 'ndis_number'
        },
        {
          data: 'participant_name',
          name: 'participant_name'
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
          data: 'abn',
          name: 'abn'
        },
        {
          data: 'invoice_amt',
          name: 'invoice_amt',
          'render': function(data, type, row) {
            // var amt = $.fn.dataTable.render.number(',', '.', 2)
            var amt = '$' + dollarUSLocale.format(row.invoice_amt);
            // console.log(amt);
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
        // var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        // $(firstrow).prop('checked', true);
      },
      drawCallback: function(settings) {
        // set first record selected
        // var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        // $(firstrow).prop('checked', true);
      }
    });
    // UPDATE: SELECT ALL FOR INVOICES - 2022-13-07 
    $('#invoice-select-all').on('click', function(){
      if ($(this).is( ":checked" )) {

         $('[name="invoice-selection"]').prop('checked', 'checked');

      } else {
        $('[name="invoice-selection"]').prop('checked', '');
      }
    })
    // CLOSING UPDATE

    $('#updateInvoice').on('click', function() {

      var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

      if ((selected_one == 0)) {
        OkayModal("Error", "Please select at least one item.");
        $('#clickmodal').click();
        $('#modal-invoice-status').modal('hide');
        return false;
      }
      $('#modal-invoice-status').modal('show');

    });
    $('#updateStatusInvoice').on('click', function() {

      var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

      if ((selected_one == 0)) {
        OkayModal("Error", "Please select at least one item.");
        $('#clickmodal').click();
        return false;
      }

      if ($('#invoice_status_id').val() == 0) {
        OkayModal("Error", "Please select invoice status.");
        $('#clickmodal').click();
        return false;
      }

      var array_checkedbox = [];
      $("input:checkbox[name=invoice-selection]:checked").each(function(value, index) {
        array_checkedbox.push($(index).val());
      })

      $.ajax({
        url: "{{ route('invoices.updateinvoicestatus') }}",
        data: {
          'invoice_ids': JSON.stringify(array_checkedbox),
          'invoice_status_id': $('#invoice_status_id').val()
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          if (data.success) {
            $('#modal-invoice-status').modal('hide');
            table.draw();
          } else {
            OkayModal("Error", data.msg);
            $('#clickmodal').click();
          }

        },
      });

    })




    $('.data-table').on('length.dt', function(e, settings, len) {

      if (isNaN(len)) len = 50;

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



    $('.checkbox-toggle').on('change', function() {
      var except_paid = 'New|Verified|Unverified|Pending|Open|Dispute';
      var is_hide = ($(this).is(':checked') == true) ? except_paid : '';

      $.ajax({
        url: "{{ route('invoices.hidepaidinvoices') }}",
        data: {
          hidepaidinvoices: $(this).is(':checked')
        },
        type: "POST",
        dataType: 'json',
        success: function(data) {
          table.column(8).search(is_hide, true, false).draw();
        },
        error: function(data) {

        }
      });

    })

    var is_hide_paid_invoices = '<?= ($user_data->hide_paid_invoices); ?>';
    if (is_hide_paid_invoices == '1') {
      $('.checkbox-toggle').prop('checked', true).trigger('change');

    } else {
      $('.checkbox-toggle').prop('checked', false).trigger('change');
    }


    var pageLengthSaved = '<?= ($user_data->page_length); ?>';
    if (pageLengthSaved != null) {

      if (isNaN(pageLengthSaved)) {
        pageLengthSaved = 50;
      }

      table.page.len(pageLengthSaved).draw();

    }


    $('#addNewInvoice').click(function() {
      var redirect = window.location.href + '/add';

      $(this).attr("href", redirect);

    });

    $('#quantity').on('keyup', function(index, value) {
      var qty = $(this).val();
      var unit = $('#unit_price').val();

      $('#stated_item_budget').val((parseInt(qty) * parseFloat(unit)));
    })

    $('#unit_price').on('keyup', function(index, value) {
      var qty = $('#quantity').val();
      var unit = $(this).val();

      $('#stated_item_budget').val((parseInt(qty) * parseFloat(unit)));
    })
    var edit_stated_items_id = [];
    $('#editInvoice').on('click', function() {

      var invoice_id = $("input:checkbox[name=invoice-selection]:checked").val()
      var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

      if (selected_one >= 2 || selected_one == 0) {
        OkayModal("Error", "Please select one item.");
        $('#clickmodal').click();
        return false;
      }

      var redirect = window.location.href + '/' + invoice_id + '/edit';

      $(this).attr("href", redirect);

    });

    // $('#editItem').on('click', function(){
    //   alert()
    // })

    $(".data-table-plan-details-stated-items").on('click', '.editItem', function() {
      // alert("edit");
      $('#btntextstateditem').text('Edit');
      // var id = $($(this)[0]).data('id');
      //   console.log(id);

      //   $.ajax({
      //     url: "{{ route('invoices.getsinglestateditem') }}",
      //     type: "POST",
      //     dataType: 'json',
      //     data:{
      //       id : id
      //     },
      //     success: function(data) {

      //       // isFilter_support_categories = false;
      //       // var html = "";
      //       // var countobject = data.length;

      //       // if (countobject >= 2) {
      //       //   html += "<option value='' selected>Select Participant</option>";
      //       // }
      //       // $(data).each(function(index, value) {

      //       //   if (value.id == participant_id) {
      //       //     html += "<option value=" + value.id + " selected> " + value.ndis_number + " / " + value.firstname + " " + value.lastname + "</option>";
      //       //   } else {
      //       //     html += "<option value=" + value.id + "> " + value.ndis_number + " / " + value.firstname + " " + value.lastname + "</option>";
      //       //   }
      //       // });

      //       // $('#participant_id').html(html);

      //     },
      //     error: function(data) {

      //     }
      //   });

    });

    $(".data-table-plan-details-stated-items").on('click', '.duplicateItem', function() {
      // alert("duplicate");

      $('#btntextstateditem').text('Duplicate');

    });

    $('#duplicateInvoice').on('click', function() {

      var invoice_id = $("input:checkbox[name=invoice-selection]:checked").val()
      var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

      if (selected_one >= 2 || selected_one == 0) {
        OkayModal("Error", "Please select one item.");
        $('#clickmodal').click();
        return false;
      }

      var redirect = window.location.href + '/' + invoice_id + '/duplicate';

      $(this).attr("href", redirect);


    });

    $('#ModalForm').submit(function(e) {
      e.preventDefault();
      let formdata = $('form').serialize();

      var stated_item_id = [];
      var stated_item_unit_price = [];
      var stated_item_quantity = [];
      var stated_item_gst_code = [];
      var description = [];
      var stated_item_budget = [];
      var service_start_date = [];
      var service_end_date = [];

      loadrecordplandetailstateditems.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        stated_item_id.push(data.stated_item_id);
        stated_item_unit_price.push(data.stated_item_unit_price);
        stated_item_quantity.push(data.stated_item_quantity);
        description.push(data.description);
        stated_item_budget.push(data.stated_item_budget);
        service_start_date.push(data.service_start_date);
        service_end_date.push(data.service_end_date);
        stated_item_gst_code.push(data.stated_item_gst_code);
        // ... do something with data(), or this.node(), etc
      });

      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      today = yyyy + '-' + mm + '-' + dd;

      $.ajax({
        url: "{{ route('invoices.saverecord') }}",
        data: {
          '_token': $('meta[name="csrf-token"]').attr('content'),
          'invoice_id': $('.invoice_id').val(),
          'participant_id': $('#participant_id').val(),
          'planmanager_subscriptions_id': $('#planmanager_subscriptions_id').val(),
          'invoice_number': $('#invoice_number').val(),
          'invoice_date': today,
          'due_date': $('#due_date').val(),
          'reference_number': $('#reference_number').val(),
          'service_provider_ABN': $('#abn').val(),
          'service_provider_acc_number': $('#service_provider_acc_number').val(),
          'serviceprovider_id': $('#serviceprovider_id').val(),
          'stated_item_id': JSON.stringify(stated_item_id),
          'stated_item_unit_price': JSON.stringify(stated_item_unit_price),
          'stated_item_quantity': JSON.stringify(stated_item_quantity),
          'description': JSON.stringify(description),
          'stated_item_budget': JSON.stringify(stated_item_budget),
          'stated_item_gst_code': JSON.stringify(stated_item_gst_code),
          'service_start_date': JSON.stringify(service_start_date),
          'service_end_date': JSON.stringify(service_end_date),
          'status': $('#status').val()
        },
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('#saveBtn').prop('disabled', true);
        },
        success: function(data) {
          if (data.msg) {
            OkayModal("Error", "Record already exists!");
            $('#clickmodal').click();
            // confirm("Record already exists!");
          } else {
            $('#ModalForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();
            $('#saveBtn').html('Save');
            $('#saveBtn').prop('disabled', false);
          }
        },
        error: function(data) {
          $('#saveBtn').html('Save');
        }
      });
    });

    $('#viewInvoice').on('click', function() {

      var invoice_id = $("input:checkbox[name=invoice-selection]:checked").val()
      var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

      if (selected_one >= 2 || selected_one == 0) {
        OkayModal("Error", "Please select one item.");
        $('#clickmodal').click();
        return false;
      }

      var redirect = window.location.href + '/' + invoice_id + '/view';

      $(this).attr("href", redirect);

    })

    $('#deleteInvoice').on('click', function() {
      var invoice_id = $("input:checkbox[name=invoice-selection]:checked").val();
      var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

      if (selected_one >= 2 || selected_one == 0) {
        OkayModal("Error", "Please select one item.");
        $('#clickmodal').click();
        return false;
      }
      // var isConfirmed = confirm("Are you sure you want to delete this item?");

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
              url: "{{ route('invoices.deleterecord')}}",
              data: {
                id: invoice_id
              },
              success: function(data) {
                if (data.success) {
                  table.draw();
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

    var counter = 1;
    var stated_items_id = [];
    var stated_items_budget = [];
    var stated_items = [];


    $('#add_stated_item').on('click', function() {

      var plan_stated_items_text = $('#stated_items_id :selected').text();
      var plan_stated_items_id = $('#stated_items_id :selected').val();
      var stated_item_unit_price = $('#unit_price').val();
      var stated_item_quantity = $('#quantity').val();
      var stated_item_gst_code = $('#gst').val();
      var total_stated_item_budget = 0;
      var total_previous_stated_item_budget = ($('#box-header-totalAmount').text()).replace(',', '');

      var foundduplicated = false;

      var stated_item_budget = $('#stated_item_budget').val();
      var description = $('#description').val();
      var service_start_date = $('#service_start_date').val();
      var service_end_date = $('#service_end_date').val();
      // console.log(description);
      if (plan_stated_items_id == 0 || stated_item_budget == 0) {
        return false;
      }

      // $(stated_items_id).each(function(index, value) {
      //   if (value == plan_stated_items_id) {
      //     alert("Existing record found, please try again!");
      //     foundduplicated = true;
      //     return false;
      //   }
      // });

      // $(edit_stated_items_id.filter(uniqueFilter)).each(function(index, value){
      //   if(value==plan_stated_items_id){
      //     alert("Existing record found, please try again!");
      //     foundduplicated = true;
      //     return false;
      //   }
      // })



      // (edit_stated_items_id
      // console.log(stated_items_id);


      // console.log(edit_stated_items_id);



      stated_items_budget.push(stated_item_budget);

      var total_stated_items_budget = 0;
      $(stated_items_budget).each(function(index, value) {
        total_stated_items_budget += parseInt(value);
      });



      //if (foundduplicated == false) {
      stated_items_id.push(plan_stated_items_id);
      loadrecordplandetailstateditems.row.add({
        "action": '<button data-id="' + counter + '" class="btn btn-danger btn-sm deleteItem " type="button"> <i class="fa fa-trash"></i> </button>',
        "stated_item_id": plan_stated_items_id,
        "stated_item": plan_stated_items_text,
        "stated_item_unit_price": stated_item_unit_price,
        'stated_item_quantity': stated_item_quantity,
        "stated_item_budget": stated_item_budget,
        "stated_item_gst_code": stated_item_gst_code,
        "description": description,
        "service_start_date": service_start_date,
        "service_end_date": service_end_date
      }).draw();


      $('#stated_items_id :selected').text();
      $('#stated_items_id :selected').val("");
      $('#unit_price').val("");
      $('#quantity').val("");
      $('#gst').val("");

      $('#stated_item_budget').val("");
      $('#description').val("");
      // $('#service_start_date').val("");
      // $('#service_end_date').val("");

      $.ajax({
        url: "{{ route('invoices.getstateditems') }}",
        type: "POST",
        dataType: 'json',
        success: function(data) {
          isFilter_support_categories = false;
          var html = "";
          var countobject = data.length;

          html += "<option value='0'>Select Support Item</option>";

          $(data).each(function(index, value) {

            html += "<option value=" + value.id + "> " + value.support_item_number + " / " + value.support_item_name + "</option>";

          });


          $('#stated_items_id').html(html);


        },
        error: function(data) {

        }
      });
      total_stated_item_budget += parseFloat(stated_item_budget);

      var new_total_stated_item_budget = total_stated_item_budget + parseFloat(total_previous_stated_item_budget)

      $('#box-header-totalAmount').text(ReplaceNumberWithCommas(
        parseFloat(new_total_stated_item_budget).toFixed(2)));
      //}

      counter++;
      // $('#statedSupportForm').trigger("reset");
    });




    $(".data-table-plan-details-stated-items").on('click', '.deleteItem', function() {
      loadrecordplandetailstateditems.row($(this).closest('tr')[0]).remove().draw(false);
      var total_previous_stated_item_budget = ($('#box-header-totalAmount').text()).replace(',', '');
      var get_amount = $($(this).closest('tr')[0]).find("td:eq(3)").text();
      var new_total_stated_item_budget = parseFloat(total_previous_stated_item_budget) - parseFloat(get_amount);
      var plan_stated_items_id = $('#stated_items_id :selected').val();



      $(stated_items_id).each(function(index, value) {
        if (value == plan_stated_items_id) {
          // stated_items_id[index].remove();
          stated_items_id.splice(index, 1);
        }
      })


      $('#box-header-totalAmount').text(ReplaceNumberWithCommas(
        parseFloat(new_total_stated_item_budget).toFixed(2)));

    });

    var loadrecordplandetailstateditems = $('.data-table-plan-details-stated-items').DataTable({
      responsive: true,
      processing: true,
      serverSide: false,
      ajax: {
        url: "{{route('participants.loadrecordplandetailstateditems')}}",
        type: "GET"
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
          data: 'description',
          name: 'description'
        },
        {
          data: 'service_start_date',
          name: 'service_start_date'
        },
        {
          data: 'service_end_date',
          name: 'service_end_date'
        },
        {
          data: 'stated_item_quantity',
          name: 'stated_item_quantity'
        },
        {
          data: 'stated_item_unit_price',
          name: 'stated_item_unit_price'
        },
        {
          data: 'stated_item_gst_code',
          name: 'stated_item_gst_code'
        },
        {
          data: 'stated_item_budget',
          name: 'stated_item_budget',
          render: $.fn.dataTable.render.number(',', '.', 2)
        }
      ],
      initComplete: function(data) {

        // set first record selected
        //var firstrow = $('.data-table-plan-details-stated-items tbody tr:eq(0) td').find("input");
        // $(firstrow).prop('checked', true);

      }
    });


    $('#validateInvoice').on('click', function() {
      // var isConfirmed = confirm("Are you sure you want to validate all invoices?");


      bootbox.confirm({
        message: "Are you sure you want to validate all invoices?",
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
              url: "{{ route('invoices.validateinvoices')}}",
              success: function(data) {
                if (data.success) {
                  table.draw();
                  // bootbox.alert("Successfully deleted!");
                  OkayModal("Success", data.msg);
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

      // if (isConfirmed) {
      //   $.ajax({
      //     type: "POST",
      //     url: "{{ route('invoices.validateinvoices')}}",
      //     success: function(data) {
      //       if (data.success) {
      //         alert(data.msg);
      //         table.draw();
      //       } else {
      //         // alert(data.msg);
      //       }
      //     },
      //     error: function(data) {
      //       // console.log('Error:', data);
      //     }
      //   });
      // }
    })




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
  function download_proda_file() {
    //console.log(formatDate('Sun May 11,2014'));

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yy = String(today.getFullYear()).substr(-2);

    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    var ms = String(today.getMilliseconds()).slice(-2);
    var filename = 'PD' + '' + mm + '' + dd + '' + yy + '_' + h + '' + m + '' + s + '' + '.csv';

    //today = 'Invoices_PRODA_' + yy + "" + mm + "" + dd + ".csv";
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
        hiddenElement.download = filename;
        hiddenElement.click();

      },
      error: function(data) {
        console.log('error:', data)
      }
    });

  }

  function download_qb_file() {
    //console.log(formatDate('Sun May 11,2014'));

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yy = String(today.getFullYear()).substr(-2);

    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    var ms = String(today.getMilliseconds()).slice(-2);
    var filename = 'QB' + '' + mm + '' + dd + '' + yy + '_' + h + '' + m + '' + s + '' + '.csv';


    var selected_one = $("input:checkbox[name=invoice-selection]:checked").length;

    // if ((selected_one == 0)) {
    //   OkayModal("Error", "Please select at least one item.");
    //   $('#clickmodal').click();
    //   return false;
    // }

    // if ($('#invoice_status_id').val() == 0) {
    //   OkayModal("Error", "Please select invoice status.");
    //   $('#clickmodal').click();
    //   return false;
    // }


    var array_checkedbox = [];
    $("input:checkbox[name=invoice-selection]:checked").each(function(value, index) {
      array_checkedbox.push($(index).val());
    })


    // today = 'Invoice_Quickbook_' + yyyy + "" + mm + "" + dd + ".csv";
    var csvFileData = [];

    $.ajax({
      url: "{{ route('invoices.exporttoqb') }}",
      data: {
        'invoice_ids': JSON.stringify(array_checkedbox)
      },
      type: "POST",
      dataType: 'json',
      success: function(data) {

        $contents = data.data;
        if (data.success) {

          if ($contents[0].connect == "plan_on_track") {
            csvFileData = [
              ['*BillNo', '*DueDate', '*LineDescription', 'Supplier', 'BillDate', 'Account', 'TotalAmount', 'TaxCode', 'InvoiceUrl']
            ];

            $($contents).each(function(index, value) {
              var itemDesc = value.description.replace(/'/g, '"');
              csvFileData.push([value.invoice_number, value.duedate, itemDesc, value.supplier, value.billdate, value.account, value.lineamount, value.linetaxcode, value.invoiceurl]);
  
            });
          } else {

            csvFileData = [
              ['InvoiceNo', 'DueDate', 'ServiceDate', 'ItemDescription', 'Supplier', 'BillDate', 'Account', 'LineAmount', 'LineTaxCode', 'InvoiceUrl']
            ];
            $($contents).each(function(index, value) {
              var itemDesc = value.description.replace(/'/g, '"');
              csvFileData.push([value.invoice_number, value.duedate, value.servicedate, itemDesc, value.supplier, value.billdate, value.account, value.lineamount, value.linetaxcode, value.invoiceurl]);
  
            });
          }


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
        } else {
          OkayModal("Error", data.msg);
        }

        $('#clickmodal').click();

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

@endsection