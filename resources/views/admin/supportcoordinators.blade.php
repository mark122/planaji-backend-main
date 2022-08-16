@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />


@endsection

@section('content')

<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-custom">
          <div class="box-header box-header-custom">
            <h3 class="box-title">Support Coordinators
            </h3>

            <!-- <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewServiceProvider"> Add Service Provider</a> -->
          </div>

          <div class="box-body">
            <div class="crud-buttons">
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewSupportCoordinator"> View</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteSupportCoordinator"> Delete</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editSupportCoordinator"> Edit</a>
              <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addNewSupportCoordinator"> Add Support Coordinator</a>
            </div>
            <table class="table table-bordered data-table display nowrap table-example1">
              <thead>
                <tr>
                  <th class="select-provider-header" style="text-align: center;">Select</th>
                  <th width="280px">First Name</th>
                  <th>Last Name</th>
                  <th>Office</th>
                  <th>Contact Number</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <div class="modal fade" id="ajaxModel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modelHeading"></h4>
              </div>
              <div class="modal-body">
                <form class="form-horizontal" id="coordinatorForm" name="coordinatorForm">
                  <div class="box-body">
                    <div class="form-group">
                      <input type="hidden" name="supportcoordinator_id" id="supportcoordinator_id">
                      <input type="hidden" name="planmanager_subscriptions_id" id="planmanager_subscriptions_id" value={{Auth::user()->plan_manager_subscription_id}}>
                      <label for="firstname" class="col-sm-3 control-label">First Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-required" id="firstname" name="firstname" placeholder="Enter First Name" value="" maxlength="50" required="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="lastname" class="col-sm-3 control-label">Last Name</label>
                      <div class="col-sm-9">
                        <input type="text" id="lastname" name="lastname" placeholder="Enter Last Name" class="form-control form-control-required" maxlength="50" required="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="office" class="col-sm-3 control-label">Office</label>
                      <div class="col-sm-9">
                        <input type="text" id="office" name="office" placeholder="Enter Office Name" class="form-control form-control-required" maxlength="50" required="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="mobile" class="col-sm-3 control-label">Contact Number</label>
                      <div class="col-sm-9">
                        <input type="text" id="mobile" name="mobile" placeholder="Enter Contact Number" class="form-control form-control-required" maxlength="15" required="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="email" class="col-sm-3 control-label">Email</label>
                      <div class="col-sm-9">
                        <input type="email" id="email" name="email" placeholder="Enter Email Address" class="form-control form-control" maxlength="50">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="address1" class="col-sm-3 control-label">Address 1</label>
                      <div class="col-sm-9">
                        <input type="text" id="address1" name="address1" placeholder="Enter Address 1" class="form-control form-control-required" maxlength="50">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="address2" class="col-sm-3 control-label">Address 2</label>
                      <div class="col-sm-9">
                        <input type="text" id="address2" name="address2" placeholder="Enter Address 2" class="form-control form-control" maxlength="50">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="state" class="col-sm-3 control-label">State</label>
                      <div class="col-sm-9">
                        <input type="text" id="state" name="state" placeholder="Enter State" class="form-control form-control-required" maxlength="50" required="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="postcode" class="col-sm-3 control-label">Postcode</label>
                      <div class="col-sm-9">
                        <input type="text" id="postcode" name="postcode" placeholder="Enter Postcode" class="form-control form-control-required" maxlength="50" required="">
                      </div>
                    </div>

                    <!-- <div class="form-group">
                      <label for="participant_ndis" class="col-sm-3 control-label">Participant NDIS Number</label>
                      <div class="col-sm-9">
                        <input type="text" id="participant_ndis" name="participant_ndis" placeholder="Enter Participant NDIS Number" class="form-control form-control-required" maxlength="20" required="">
                      </div>
                    </div> -->

                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                    <button type="submit" class="btn btn-info pull-right" id="saveBtn" value="create">Save</button>
                  </div>
                  <!-- /.box-footer -->
                </form>

              </div>
            </div>
          </div>
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


<script>
  $(function() {



    $.ajaxSetup({

      headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

      }

    });

    var table = $('.data-table').DataTable({

      responsive: true,

      processing: true,

      serverSide: true,

      pageLength: 50,

      ajax: "{{route('supportcoordinators.loadrecords')}}",
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
          data: 'office',
          name: 'office'
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
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);

      },
      drawCallback: function(settings) {
        // set first record selected
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);
      }
    });

    $('#addNewSupportCoordinator').click(function() {
      $('#saveBtn').val("create-supportcoordinator");
      $('#supportcoordinator_id').val('');
      $('#coordinatorForm').trigger("reset");
      $('#modelHeading').html("Add Support Coordinator");
      $('#ajaxModel').modal({
    backdrop: 'static',
    keyboard: false
})

      $('#firstname').prop('disabled', false);
      $('#lastname').prop('disabled', false);
      $('#office').prop('disabled', false);
      $('#mobile').prop('disabled', false);
      $('#address1').prop('disabled', false);
      $('#address2').prop('disabled', false);
      $('#state').prop('disabled', false);
      $('#postcode').prop('disabled', false);
      $('#email').prop('disabled', false);
      // $('#participant_ndis').prop('disabled', false);

      $('#saveBtn').show();
    });


    $('#editSupportCoordinator').on('click', function() {
      var supportcoordinator_id = $("input:radio[name=support-coordinator-selection]:checked").val()

      if (supportcoordinator_id == undefined) {
        // alert("Please select an item.");
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      $.get("{{ route('supportcoordinators.loadrecords') }}" + '/' + supportcoordinator_id + '/editrecord', function(data) {
        $('#modelHeading').html("Edit Support Coordinator");
        $('#saveBtn').val("edit-supportcoordinator");
        $('#ajaxModel').modal({
    backdrop: 'static',
    keyboard: false
})
        $('#supportcoordinator_id').val(data.id);

        $('#firstname').val(data.firstname);
        $('#lastname').val(data.lastname);
        $('#office').val(data.office);
        $('#mobile').val(data.mobile);
        $('#address1').val(data.address1);
        $('#address2').val(data.address2);
        $('#state').val(data.state);
        $('#postcode').val(data.postcode);
        $('#email').val(data.email);
        // $('#participant_ndis').val(data.participant_ndis);

        $('#firstname').prop('disabled', false);
        $('#lastname').prop('disabled', false);
        $('#office').prop('disabled', false);
        $('#mobile').prop('disabled', false);
        $('#address1').prop('disabled', false);
        $('#address2').prop('disabled', false);
        $('#state').prop('disabled', false);
        $('#postcode').prop('disabled', false);
        $('#email').prop('disabled', false);
        // $('#participant_ndis').prop('disabled', false);

        $('#saveBtn').show();
      })
    });

    $('#viewSupportCoordinator').click(function() {
      console.log('here');
      var supportcoordinator_id = $("input:radio[name=support-coordinator-selection]:checked").val()

      if (supportcoordinator_id == undefined) {
        // alert("Please select an item.");
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      $.get("{{ route('supportcoordinators.loadrecords') }}" + '/' + supportcoordinator_id + '/editrecord', function(data) {
        $('#modelHeading').html("View Support Coordinator");
        $('#saveBtn').val("edit-supportcoordinator");
        $('#ajaxModel').modal({
    backdrop: 'static',
    keyboard: false
})
        $('#supportcoordinator_id').val(data.id);

        $('#firstname').val(data.firstname);
        $('#lastname').val(data.lastname);
        $('#office').val(data.office);
        $('#mobile').val(data.mobile);
        $('#address1').val(data.address1);
        $('#address2').val(data.address2);
        $('#state').val(data.state);
        $('#postcode').val(data.postcode);
        $('#email').val(data.email);
        // $('#participant_ndis').val(data.participant_ndis);

        $('#firstname').prop('disabled', true);
        $('#lastname').prop('disabled', true);
        $('#office').prop('disabled', true);
        $('#mobile').prop('disabled', true);
        $('#address1').prop('disabled', true);
        $('#address2').prop('disabled', true);
        $('#state').prop('disabled', true);
        $('#postcode').prop('disabled', true);
        $('#email').prop('disabled', true);
        // $('#participant_ndis').prop('disabled', true);

        $('#saveBtn').hide();
      })
    });

    $('#coordinatorForm').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();

      $.ajax({
        data: $(this).serialize(),
        url: "{{ route('supportcoordinators.saverecord') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          $('#saveBtn').prop('disabled', true);
        },
        success: function(data) {
          var isConfirmed = true;
          if (data.msg) {
            // isConfirmed = confirm("Record already exists!");
            // if (!isConfirmed) {
            // $('#coordinatorForm').trigger("reset");
            // $('#ajaxModel').modal('hide');
            // table.draw();
            // }

            OkayModal("Error", data.msg);
            $('#clickmodal').click();
          } else {
            $('#coordinatorForm').trigger("reset");
            $('#ajaxModel').modal('hide');
            table.draw();
            $('#saveBtn').html('Save');
          }
          $('#saveBtn').prop('disabled', false);
        },
        error: function(data) {
          console.log('ErrorLog:', data);
          $('#saveBtn').html('Save');
        }
      });
    });

    $('#deleteSupportCoordinator').on('click', function() {

      var supportcoordinator_id = $("input:radio[name=support-coordinator-selection]:checked").val()

      if (supportcoordinator_id == undefined) {
        // alert("Please select an item.");
        OkayModal("Error", "Please select an item.");
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
              url: "{{ route('supportcoordinators.deleterecord')}}",
              data: {
                id: supportcoordinator_id
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

      // if (isConfirmed) {
      //   $.ajax({
      //     type: "POST",
      //     url: "{{ route('supportcoordinators.deleterecord')}}",
      //     data: {
      //       id: supportcoordinator_id
      //     },
      //     success: function(data) {
      //       table.draw();
      //     },
      //     error: function(data) {
      //       console.log('Error:', data);
      //     }
      //   });
      // }
    });
  });
</script>

@endsection