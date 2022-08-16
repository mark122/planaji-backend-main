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
            <h3 class="box-title">Participants
            </h3>

          </div>
          <div class="box-body">
            <div class="crud-buttons">
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="" id="viewparticipant"> View</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteParticipant"> Delete</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editParticipant"> Edit</a>
              <a class="btn btn-success ml-5 " style="margin-left:5px;float:right" href="javascript:void(0)" id="addNewParticipant"> Add Participant</a>
            </div>
            <table class="table table-bordered data-table display nowrap table-example1">
              <thead>
                <tr>
                  <th>Select</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>NDIS Number</th>
                  <th>Date of Birth</th>
                  <th>Email Address</th>
                  <th>Status</th>
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
          <div class="modal-dialog modal-participants">
            <div class="modal-content">
              <form class="form-horizontal" id="ModalForm" name="ModalForm">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body" style="padding: 20px !important;">
                  <div class="box-body">
                    <div class="row" data-select2-id="15">

                      <div class="form-group">
                        <div class="col-md-6">
                          <input type="hidden" name="participant_id" id="participant_id">
                          <input type="hidden" name="planmanager_subscriptions_id" id="planmanager_subscriptions_id" value={{Auth::user()->plan_manager_subscription_id}}>
                          <label for="firstname">First Name</label>
                          <input type="text" class="form-control form-control-required" id="firstname" name="firstname" placeholder="Enter First Name" value="" maxlength="50" required="">
                        </div>

                        <div class="col-md-6">
                          <label for="lastname">Last Name</label>
                          <input type="text" id="lastname" name="lastname" required="" placeholder="Enter Last Name" class="form-control form-control-required" maxlength="50"></input>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="address1">Address 1</label>
                          <input type="text" id="address1" name="address1" required="" placeholder="Enter Address 1" class="form-control form-control-required" maxlength="50">
                        </div>
                        <div class="col-md-6">
                          <label for="address2">Address 2</label>
                          <input type="text" id="address2" name="address2" required="" placeholder="Enter Address 2" class="form-control form-control-required" maxlength="50">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="state">State</label>
                          <input type="text" id="state" name="state" required="" placeholder="Enter State" class="form-control form-control-required" maxlength="50">
                        </div>
                        <div class="col-md-6">
                          <label for="postcode">Postcode</label>
                          <input type="text" id="postcode" name="postcode" required="" placeholder="Enter Postcode" class="form-control form-control-required" maxlength="50">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="dateofbirth">Date of Birth</label>
                          <input type="date" value="yyyy-mm-dd" min="1950-01-01" max="9999-12-31" id="dateofbirth" name="dateofbirth" required="" placeholder="Enter Date of Birth" class="form-control form-control-required">
                          <!-- <input id="dateofbirth" name="dateofbirth" type="text" value="" placeholder="dd.mm.yyyy" class="form-control form-control-required"> -->
                        </div>
                        <div class="col-md-6">
                          <label for="email">Email Address</label>
                          <input type="email" id="email" name="email" placeholder="Enter Email Address" class="form-control form-control" maxlength="50">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="homenumber">Home Number</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-phone"></i>
                            </div>
                            <input type="text" id="homenumber" name="homenumber" placeholder="Enter Home Number" class="form-control" maxlength="50" data-inputmask="&quot;mask&quot;: &quot;(999) 999-9999&quot;" data-mask="">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <label for="phonenumber">Phone Number</label>
                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-phone"></i>
                            </div>
                            <input type="text" id="phonenumber" name="phonenumber" placeholder="Enter Phone Number" class="form-control" maxlength="50" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="">
                          </div>
                        </div>

                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="ndis_plan_start_date">NDIS Plan Start Date</label>
                          <input type="date" value="yyyy-mm-dd" min="1950-01-01" max="9999-12-31" id="ndis_plan_start_date" name="ndis_plan_start_date" placeholder="Enter NDIS Plan Start Date" class="form-control form-control-required">
                          </input>
                        </div>

                        <div class="col-md-6">
                          <label for="ndis_plan_end_date">NDIS Plan End Date</label>
                          <input type="date" value="yyyy-mm-dd" min="1950-01-01" max="9999-12-31" id="ndis_plan_end_date" name="ndis_plan_end_date" placeholder="Enter NDIS Plan End Date" class="form-control form-control-required">
                          </input>
                        </div>

                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="ndis_number">NDIS Number</label>
                          <input type="text" class="form-control" id="ndis_number" name="ndis_number" required="" placeholder="Enter NDIS Number" class="form-control form-control-required" maxlength="9
                          
                          ">
                        </div>

                        <div class="col-md-6">
                          <label for="aboutme">About me</label>
                          <textarea id="aboutme" name="aboutme" placeholder="Enter About Me" class="form-control form-control-required"></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <label for="short_term_goals">Short Term Goals</label>
                          <textarea id="short_term_goals" name="short_term_goals" placeholder="Enter short term goals" class="form-control form-control-required"></textarea>
                        </div>
                        <div class="col-md-6">
                          <label for="long_term_goals">Long Term Goals</label>
                          <textarea id="long_term_goals" name="long_term_goals" placeholder="Enter long term goals" class="form-control form-control-required"></textarea>
                        </div>
                      </div>


                    </div>
                  </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">

                  <!-- Default checked -->
                  <!-- <div class="col-md-12"> -->
                  <!-- <label for="checkbox">Enable App Access</label> -->
                  <!-- </div> -->
                  <!-- <div class="col-md-12">
                  <label class="switch">
                            <input class="form-control form-control-required" type="checkbox" checked>
                            <span class="slider round"></span>
                          </label>
                          <label>Enable App Access</label>
                        </div>

                  <label class="switch">
                    <input type="checkbox" id="togBtn">
                    <div class="slider round">
                     ADDED HTML -->
                  <!--END
                    </div>
                  </label>
                  <span class="on">ON</span>
                </div> -->

                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="custom-control custom-switch app-access">
                        <!-- <label class="switch"> -->
                        <input type="checkbox" id="app_access_enabled" name="app_access_enabled" data-toggle="toggle" data-size="lg">
                        <!-- <span class="slider"></span>
                      </label> -->
                        <label class="custom-control-label" for="customSwitch1">Enable App Access</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                    <div class="form-check form-check-inline">
                      <button type="submit" class="btn btn-info pull-right" id="saveBtn" value="create">Save</button>
                    </div>
                  </div>
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

<script src="https://unpkg.com/imask"></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

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

      ajax: "{{route('participants.loadrecords')}}",
      'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center',
        'render': function(data, type, full, meta) {
          var radiobtn = "";
          var size = Object.keys(data)[0].length;

          // console.log(size);

          // if (size == 2) {
          //   radiobtn = '<input type="radio" name="participant-selection" value="' + $('<div/>').text(data.id).html() + '">';
          // } else {
          //   radiobtn = '<input type="radio" name="participant-selection" checked value="' + $('<div/>').text(data.id).html() + '">';
          // }
          // return radiobtn;
          return '<input type="radio" name="participant-selection" value="' + $('<div/>').text(data.id).html() + '">';

        }
      }],
      columns: [{
          data: null,
          orderable: false,
          searchable: false,
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
          data: 'ndis_number',
          name: 'ndis_number'
        },
        {
          data: 'dateofbirth',
          name: 'dateofbirth'
        },
        {
          data: 'email',
          name: 'email'
        },
        {
          data: 'status',
          name: 'status'
        }
      ],
      initComplete: function(data) {

        // set first record selected
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);

      },
      drawCallback: function( settings ) {   
        // set first record selected
        var firstrow = $('.data-table tbody tr:eq(0) td').find("input");
        $(firstrow).prop('checked', true);
    }
    });
    $('#addNewParticipant').click(function() {
      $('#saveBtn').val("create-participant");

      $("#app_access_enabled").prop('checked', false);
      $('.btn-light').addClass("off");
      $('.toggle').removeClass("btn-primary");
      $('.toggle').addClass("btn-light off");
      $('#participant_id').val(0);


      $('#ModalForm').trigger("reset");
      $('#modelHeading').html("Add Participant");
      $('#ajaxModel').modal({
    backdrop: 'static',
    keyboard: false
})

      $('#saveBtn').show();
    });

    
    $('#editParticipant').on('click', function() {

      $("#app_access_enabled").prop('checked', false);
      $('.btn-light').addClass("off");
      $('.toggle').removeClass("btn-primary");
      $('.toggle').addClass("btn-light off");

      var participant_id = $("input:radio[name=participant-selection]:checked").val();
      if (participant_id == undefined) {
        // alert("Please select an item.");
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      $.get("{{ route('participants.loadrecords') }}" + '/' + participant_id + '/editrecord', function(data) {
        $('#modelHeading').html("Edit participant");
        $('#saveBtn').val("edit-participant");
        $('#ajaxModel').modal({
    backdrop: 'static',
    keyboard: false
})

        $('#participant_id').val(data.id);
        $('#ndis_number').val(data.ndis_number);
        $('#firstname').val(data.firstname);
        $('#lastname').val(data.lastname);
        $('#address1').val(data.address1);
        $('#address2').val(data.address2);
        $('#state').val(data.state);
        $('#postcode').val(data.postcode);
        $('#dateofbirth').val(data.dateofbirth);
        $('#email').val(data.email);
        $('#homenumber').val(data.homenumber);
        $('#phonenumber').val(data.phonenumber);
        $('#aboutme').val(data.aboutme);
        $('#ndis_plan_start_date').val(data.ndis_plan_start_date);
        $('#ndis_plan_end_date').val(data.ndis_plan_end_date);
        $('#short_term_goals').val(data.short_term_goals);
        $('#long_term_goals').val(data.long_term_goals);


        if (data.app_access_enabled == 0) {

          $("#app_access_enabled").prop('checked', false);
          $('.btn-light').addClass("off");
          $('.toggle').removeClass("btn-primary");
          $('.toggle').addClass("btn-light off");

        } else {
          $("#app_access_enabled").prop('checked', true);
          $('.btn-light').removeClass("off");
          $('.toggle').removeClass("off");

        }
        $('#saveBtn').show();
      })
    });
    $('#ModalForm').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();
      $.ajax({
        data: $(this).serialize(),

        url: "{{ route('participants.saverecord') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');

          $('#saveBtn').prop('disabled', true);
        },
        success: function(data) {
          // if (data.msg) {
          //   confirm("Record already exists!");
          // }

          if (data.msg) {
            OkayModal("Error", data.msg);
            $('#clickmodal').click();
            // $('#saveBtn').html('Save');
            // $('#saveBtn').prop('disabled', false);
          } else {
            // OkayModal("Success", data.msg);
            $('#ModalForm').trigger("reset");
            $('#ajaxModel').modal('hide');
          }
          $('#saveBtn').html('Save');
          $('#saveBtn').prop('disabled', false);
          table.draw();

        },
        error: function(data) {
          console.log('ErrorLog:', data);
          $('#saveBtn').html('Save');
        }
      });
    });

    $('#viewparticipant').on('click', function() {
      var participant_id = $("input:radio[name=participant-selection]:checked").val();


      $("#app_access_enabled").prop('checked', false);
      $('.btn-light').addClass("off");
      $('.toggle').removeClass("btn-primary");
      $('.toggle').addClass("btn-light off");

      if (participant_id == undefined) {
        // alert("Please select an item.");
        OkayModal("Error", "Please select an item.");
        $('#clickmodal').click();
        return false;
      }

      var redirect = window.location.href + '/' + participant_id + '/profile';
      $(this).attr("href", redirect);
    })

    $('#deleteParticipant').on('click', function() {
      var participant_id = $("input:radio[name=participant-selection]:checked").val()
      if (participant_id == undefined) {
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
              url: "{{ route('participants.deleterecord')}}",
              data: {
                id: participant_id
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
      //     url: "{{ route('participants.deleterecord')}}",
      //     data: {
      //       id: participant_id
      //     },
      //     success: function(data) {
      //       if (data.success) {
      //         table.draw();
      //       } else {
      //         alert(data.msg);
      //       }
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