@extends('admin.layouts.app')

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/af-2.3.7/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.4/date-1.1.1/fc-4.0.0/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.2/sp-1.4.0/sl-1.3.3/datatables.min.css" />


@endsection

@section('content')
<!-- <style>
  .control-label:after {
    content:" *";
    color: red;
  }
</style> -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-xs-12">
        <div class="box box-custom">
          <div class="box-header box-header-custom">
            <h3 class="box-title">Users
            </h3>

            <!-- <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addUserAccount"> Add Service Provider</a> -->
          </div>

          <div class="box-body">
            <div class="crud-buttons">
              <!-- <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="viewServiceProvider"> View</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="deleteUserAccount"> Delete</a>
              <a class="btn btn-success ml-5 " style=" margin-right:5px;" href="javascript:void(0)" id="editUserAccount"> Edit</a> -->

              <a class="btn btn-success ml-5 " style="float:right" href="javascript:void(0)" id="addUserAccount"> Add User Account</a>
              <br>
            </div>
            <table class="table table-bordered data-table display nowrap table-example1">
              <thead>
                <tr>
                  <th class="select-provider-header" style="text-align: center;">Select</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Generated password</th>
                  <th>Password Changed</th>
                  <th>Subscription Type</th>
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
                <form class="form-horizontal" id="providerForm" name="providerForm">
                  <div class="box-body">
                    <div class="form-group">
                      <input type="hidden" name="user_id" id="user_id">
                      <input type="hidden" name="planmanager_subscriptions_id" id="planmanager_subscriptions_id" value={{Auth::user()->plan_manager_subscription_id}}>
                      <label for="firstname" class="col-sm-3 control-label">Account name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control form-control-required" id="name" name="name" placeholder="Enter Account Name" value="" maxlength="50" required="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="lastname" class="col-sm-3 control-label">Account Email</label>
                      <div class="col-sm-9">
                        <input type="email" id="email" name="email" required="" placeholder="Enter Account Email" class="form-control form-control-required" maxlength="50"></input>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="subscriptionlist" class="col-sm-3 control-label">Subscription</label>
                      <div class="col-sm-9">
                        <select class="form-control select" name="subscription_id" id="subscription_id" style="width:100%" required>
                          <option value='' selected>Select Subscription Type</option>
                          @foreach ($subscriptions as $subscription)
                          <option value="{{ $subscription->id }}">{{ $subscription->type }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>


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

      ajax: "{{route('users.loadrecords')}}",
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
          data: 'name',
          name: 'name'
        },
        {
          data: 'email',
          name: 'email'
        },
        {
          data: 'generated_password',
          name: 'generated_password'
        },
        {
          data: 'changed_password',
          name: 'changed_password',
          'render': function(data, type, row) {
            if (row.changed_password == 1) {
              return 'YES';
            } else {
              return 'NO';
            }
          }
        },
        {
          data: 'subscriptiontype',
          name: 'subscriptiontype'
        }
      ]

    });

    $('#addUserAccount').click(function() {
      // $('#saveBtn').val("create-serviceprovider");
      // $('#user_id').val('');
      // $('#providerForm').trigger("reset");
      $('#modelHeading').html("Add User Account");
      $('#ajaxModel').modal('show');

      // $('#typename').prop('disabled', false);
      // $('#firstname').prop('disabled', false);
      // $('#lastname').prop('disabled', false);
      // $('#mobile').prop('disabled', false);
      // $('#address1').prop('disabled', false);
      // $('#address2').prop('disabled', false);
      // $('#state').prop('disabled', false);
      // $('#postcode').prop('disabled', false);
      // $('#email').prop('disabled', false);
      // $('#abn').prop('disabled', false);

      $('#saveBtn').show();
    });


    $('#editUserAccount').on('click', function() {
      var user_id = $("input:radio[name=service-provider-selection]:checked").val()

      if (user_id == undefined) {
        alert("Please select an item.");
        return false;
      }

      $.get("{{ route('serviceproviders.loadrecords') }}" + '/' + user_id + '/editrecord', function(data) {
        $('#modelHeading').html("Edit Service Provider");
        $('#saveBtn').val("edit-serviceprovider");
        $('#ajaxModel').modal('show');
        $('#user_id').val(data.id);
        $('#typename').val(data.typename);
        $('#firstname').val(data.firstname);
        $('#lastname').val(data.lastname);
        $('#mobile').val(data.mobile);
        $('#address1').val(data.address1);
        $('#address2').val(data.address2);
        $('#state').val(data.state);
        $('#postcode').val(data.postcode);
        $('#email').val(data.email);
        $('#abn').val(data.abn);
        $('#typename').val(data.provider_type_id).change();

        $('#typename').prop('disabled', false);
        $('#firstname').prop('disabled', false);
        $('#lastname').prop('disabled', false);
        $('#mobile').prop('disabled', false);
        $('#address1').prop('disabled', false);
        $('#address2').prop('disabled', false);
        $('#state').prop('disabled', false);
        $('#postcode').prop('disabled', false);
        $('#email').prop('disabled', false);
        $('#abn').prop('disabled', false);

        $('#saveBtn').show();
      })
    });


    $('#viewServiceProvider').click(function() {

      var user_id = $("input:radio[name=service-provider-selection]:checked").val()



      if (user_id == undefined) {
        alert("Please select an item.");
        return false;
      }

      $.get("{{ route('serviceproviders.loadrecords') }}" + '/' + user_id + '/editrecord', function(data) {
        $('#modelHeading').html("View Service Provider");
        $('#saveBtn').val("edit-serviceprovider");
        $('#ajaxModel').modal('show');
        $('#user_id').val(data.id);
        $('#typename').val(data.typename);
        $('#firstname').val(data.firstname);
        $('#lastname').val(data.lastname);
        $('#mobile').val(data.mobile);
        $('#address1').val(data.address1);
        $('#address2').val(data.address2);
        $('#state').val(data.state);
        $('#postcode').val(data.postcode);
        $('#email').val(data.email);
        $('#abn').val(data.abn);

        $('#typename').prop('disabled', true);
        $('#firstname').prop('disabled', true);
        $('#lastname').prop('disabled', true);
        $('#mobile').prop('disabled', true);
        $('#address1').prop('disabled', true);
        $('#address2').prop('disabled', true);
        $('#state').prop('disabled', true);
        $('#postcode').prop('disabled', true);
        $('#email').prop('disabled', true);
        $('#abn').prop('disabled', true);

        $('#saveBtn').hide();
      })

    });

    $('#providerForm').submit(function(e) {
      e.preventDefault();
      let data = $('form').serializeArray();

      $.ajax({
        data: $(this).serialize(),

        url: "{{ route('users.saverecord') }}",
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          $(this).html('Sending..');
          // $('#saveBtn').prop('disabled', true);
        },
        success: function(data) {
          if (data.msg) {
            // isConfirmed = confirm("Record already exists!");
            // $('#ajaxModel').modal('hide');
            OkayModal("Error", data.msg);
            // if (!isConfirmed) {
            $('#clickmodal').click();
            table.draw();

            // }
          } else {
            $('#providerForm').trigger("reset");
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

    $('#deleteUserAccount').on('click', function() {

      var user_id = $("input:radio[name=service-provider-selection]:checked").val()

      if (user_id == undefined) {
        alert("Please select an item.");
        return false;
      }


      var isConfirmed = confirm("Are you sure you want to delete this item?");

      if (isConfirmed) {
        $.ajax({
          type: "POST",
          url: "{{ route('serviceproviders.deleterecord')}}",
          data: {
            id: user_id
          },
          success: function(data) {
            table.draw();
          },
          error: function(data) {
            console.log('Error:', data);
          }
        });
      }
    });
  });
</script>

@endsection