@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />

<script src="https://unpkg.com/imask"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
@endsection

@section('content')


<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <!-- <h1>
      Participants
    </h1> -->
    <a href="{{route('participants.loadrecords')}}" style="margin:3px; float:left"><i class="fa fa-arrow-left margin-r-5"></i>Back to Participants</a>
    <ol class="breadcrumb">
      <li><a href="{{route('participants.loadrecords')}}"><i class="fa fa-file-text margin-r-5"></i> Participants</a></li>
      <li class="active">Plans</li>
    </ol><br />
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="tab-participant-detail-header" style = "height: 65px !important">
      <div class="col-md-4 col-md-4 col-md-4"> <b>First Name: </b> {{@$profile->firstname}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Last Name: </b> {{@$profile->lastname}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS Number: </b> {{@$profile->ndis_number}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS Start Date: </b> {{@$profile->ndis_plan_start_date}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS Review Date: </b> {{@$profile->ndis_plan_review_date}}</div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>NDIS End Date: </b> {{@$profile->ndis_plan_end_date}}</div>
      <!-- <div class="col-md-4 col-md-4 col-md-4"> <b>Managed Start Date: </b></div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Total Plan Budget:</b></div>
      <div class="col-md-4 col-md-4 col-md-4"> <b>Managed End Date: </b></div> -->
    </div>



    <div class="row">
      <div class="col-xs-12">
        <div class="box box-custom">
          <div class="box-body">
            <div class="crud-buttons">
              <h1 class="box-title planHeader">My Plan</h1>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="{{route('participant.plans',['participant_id'=>1,'plan_id'=>1])}}" id="viewPlan"> View</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deletePlan"> Delete</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editPlan"> Edit</a>
              <a class="btn btn-success ml-5 addPlan-button" style="float:right" href="javascript:void(0)" id="addNewPlan"> Add Plan</a>
            </div>

            <table class="table table-bordered data-table-plans display nowrap table-example1">
              <thead>
                <tr>
                  <th class="select-provider-header" style="text-align: center;">Action</th>
                  <th>Plan Contract</th>
                  <th>Status</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Total Funding</th>
                  <th>Total Allocated</th>
                  <th>Total Remaining</th>
                  <th>Total Delivered</th>
                  <th>Total Claimed</th>
                  <th>Total Unclaimed</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>


        <div class="modal fade" id="ajaxModalPlan" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modelHeadingPlan"></h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="planForm" name="planForm">
                  <input type="hidden" name="participant_id" id="participant_id" value="{{$id}}">
                  <input type="hidden" name="plan_id" id="plan_id" value="">
                  <input type="hidden" name="plan_contract_no" id="plan_contract_no" value="{{$plan_contract_generator}}">


                  <div class="form-group">
                    <label for="typename" class="col-sm-4 control-label">Plan Contract</label>
                    <div class="col-sm-7">
                      <div class="form-group">
                        <input type="text" id="plan_contract" name="plan_contract" required="" placeholder="" value="{{$plan_contract_generator}}" class="form-control" required disabled>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="status" class="col-sm-4 control-label">Status</label>
                    <div class="col-sm-7">
                      <div class="form-group">
                        <select class="form-control select" name="plan_status_id" id="plan_status_id" required>
                          <option value='' selected>Select Status</option>
                          @foreach ($plan_status as $statuses)
                          <option value="{{ $statuses->description }}">{{ $statuses->description }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>


                  <div class="form-group">
                    <label for="typename" class="col-sm-4 control-label">Total plan funding</label>
                    <div class="col-sm-7">
                      <div class="form-group">
                        <input type="number" step="0.0000001" id="total_funding" name="total_funding" required="" placeholder="" value="Enter total funding" class="form-control form-control-required">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="typename" class="col-sm-4 control-label">Plan Date Start</label>
                    <div class="col-sm-7">
                      <div class="form-group">
                        <input type="date" id="plan_date_start" name="plan_date_start" required="" placeholder="" value="Enter total funding" class="form-control form-control-required">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="typename" class="col-sm-4 control-label">Plan Date End</label>
                    <div class="col-sm-7">
                      <div class="form-group">
                        <input type="date" id="plan_date_end" name="plan_date_end" required="" placeholder="" value="Enter total funding" class="form-control form-control-required">
                      </div>
                    </div>
                  </div>

                  <!-- /.box-body -->
                  <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right" id="savePlan" value="add">Save</button>
                  </div>
                  <!-- /.box-footer -->
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- /.content-wrapper -->
  </section>
</div>
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
  //Initialize Select2 Elements
  $('.select2').select2()
  $(function() {


    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }

    });
    //let dollarUSLocale = Intl.NumberFormat('en-US');

    const dollarUSLocale = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2
    })
    var loadrecordplans = $('.data-table-plans').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,

      ajax: {
        url: "{{route('participants.loadrecordplans')}}",
        type: "GET",
        data: function(data) {
          data.participant_id = '{{$id}}';
        },
      },

      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          return '<input type="radio" name="plan-selection" value="' + $('<div/>').text(data.id).html() + '">';
        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false
        },
        {
          data: 'plan_contract',
          name: 'plan_contract'
        },
        {
          data: 'status',
          name: 'status'
        },
        {
          data: 'plan_date_start',
          name: 'plan_date_start'
        },

        {
          data: 'plan_date_end',
          name: 'plan_date_end'
        },
        {
          data: 'total_funding',
          render: function(data, type, row) {
            var total_funding = dollarUSLocale.format(row.total_funding);
            return total_funding;
          }
        },
        {
          data: 'total_allocated',
          render: function(data, type, row) {
            var total_allocated = dollarUSLocale.format(row.total_allocated);
            return total_allocated;
          }
        },
        {
          data: 'total_remaining',
          render: function(data, type, row) {

            var total_remaining = dollarUSLocale.format(row.total_remaining);
            return total_remaining;
          }
        },
        {
          data: 'total_delivered',
          render: function(data, type, row) {
            var total_delivered = dollarUSLocale.format(row.total_delivered);
            return total_delivered;
          }
        },
        {
          data: 'total_claimed',
          render: function(data, type, row) {
            var total_claimed = dollarUSLocale.format(row.total_claimed);
            return total_claimed;
          }
        },
        {
          data: 'total_unclaimed',
          render: function(data, type, row) {
            var total_unclaimed = dollarUSLocale.format(row.total_unclaimed);
            return total_unclaimed;
          }
        }
      ],
      initComplete: function(data) {

        // set first record selected
        var firstrow = $('.data-table-plans tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);

      },
      drawCallback: function(settings) {
        // set first record selected
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);
      }
    });




    $('#viewPlan').on('click', function() {
      var participant_id = $("#participant_id").val();
      var plan_id = $("input:radio[name=plan-selection]:checked").val();

      if (plan_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }
      var redirect = window.location.origin + '/participants/' + participant_id + '/plans' + '/' + plan_id;

      $(this).attr("href", redirect);



    })

    $('#addNewPlan').click(function() {

      $('#savePlan').val("create-plan");
      $('#participantserviceprovider_id').val('');
      $('#planForm').trigger("reset");
      $('#modelHeadingPlan').html("Add Plan");
      $('#ajaxModalPlan').modal('show');

      $('#savePlan').show();
      $.get("{{ route('participants.getplan_contractno') }}", function(data) {
        $('#plan_id').val(0);
        $('#plan_contract').val(data.getnewcontactno);
      });

      // var currencyMask = IMask(
      //   document.getElementById('total_funding'), {
      //     mask: '$num',
      //     blocks: {
      //       num: {
      //         // nested masks are available!
      //         mask: Number,
      //         thousandsSeparator: ',',
      //         min: 0,
      //         radix: '.', // fractional delimiter
      //         normalizeZeros: true, // appends or removes zeros at ends,
      //         padFractionalZeros: false, // if true, then pads zeros at end to the length of scale
      //         scale: 2, // digits after point, 0 for integers
      //       }
      //     }
      //   });
      // currencyMask.updateValue();
      // currencyMask.updateControl();
    })

    $('#planForm').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();

      $.ajax({
        data: $(this).serialize(),

        url: "{{ route('participants.saverecordplan') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('#savePlan').prop('disabled', true);
        },
        success: function(data) {
          if (data.msg) {
            // isConfirmed = confirm("Record already exists!");
            // if (!isConfirmed) {
            //   $('#planForm').trigger("reset");
            //   $('#ajaxModalPlan').modal('hide');
            //   loadrecordplans.draw();
            // }
            OkayModal("Error", "Record already exists!");
            $('#clickmodal').click();
          } else {
            $('#planForm').trigger("reset");
            $('#ajaxModalPlan').modal('hide');
            loadrecordplans.draw();
            $('#savePlan').html('Save');
          }
          $('#savePlan').prop('disabled', false);
        },
        error: function(data) {
          console.log('ErrorLog:', data);
          $('#savePlan').html('Save');
        }
      });
    });

    $('#editPlan').on('click', function() {

      var plan_id = $("input:radio[name=plan-selection]:checked").val();
      if (plan_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }
      $.get("{{ route('participants.loadrecordplans') }}" + '/' + plan_id + '/editrecordplan', function(data) {
        $('#modelHeadingPlan').html("Edit plan");
        $('#saveBtn').val("edit-plan");
        $('#ajaxModalPlan').modal('show');

        $('#plan_id').val(data.id);
        $('#plan_status_id').val(data.status).change();
        $('#plan_contract').val(data.plan_contract);
        $('#plan_contract_no').val(data.plan_contract);
        $('#status').val(data.status);
        $('#plan_date_start').val(data.plan_date_start);
        $('#plan_date_end').val(data.plan_date_end);
        $('#total_funding').val(data.total_funding);
        $('#total_allocated').val(data.total_allocated);
        $('#total_remaining').val(data.total_remaining);
        $('#total_delivered').val(data.total_delivered);
        $('#total_claimed').val(data.total_claimed);
        $('#total_unclaimed').val(data.total_unclaimed);

        $('#plan_contract').prop('disabled', true);

        $('#saveBtn').show();
      })
    });

    $('#deletePlan').on('click', function() {
      var plan_id = $("input:radio[name=plan-selection]:checked").val()
      if (plan_id == undefined) {
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }
      // var isConfirmed = confirm("Are you sure you want to delete this item?");
      // if (isConfirmed) {
      //   $.ajax({
      //     type: "POST",
      //     url: "{{ route('participants.deleterecordplan')}}",
      //     data: {
      //       id: plan_id
      //     },
      //     success: function(data) {
      //       if (data.success) {
      //         loadrecordplans.draw();
      //       } else {
      //         alert(data.msg);
      //       }
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
              url: "{{ route('participants.deleterecordplan')}}",
              data: {
                id: plan_id
              },
              success: function(data) {
                if (data.success) {
                  // table.draw();
                  loadrecordplans.draw();
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

  });
</script>

@endsection