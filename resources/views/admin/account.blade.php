@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Account
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-user-circle"></i> Account</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <section class="col-lg-12 connectedSortable">


        <!-- Chat box -->
        <div class="box box-danger">
          <div class="box-body">
            <div class="row">
              <form class="form-horizontal" id="ModalForm" name="ModalForm">



                <div class="form-group col-sm-12">
                  <label for="subscription_id" class="col-sm-2 control-label">Subscription Plan ID:</label>
                  <div class="col-sm-10">
                    <input type="text" id="subscription_id" name="subscription_id" disabled required="" value="{{$accountData->subscription_no}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="start_date" class="col-sm-2 control-label">Subscription Start Date:</label>
                  <div class="col-sm-10">
                    <input type="text" id="start_date" name="start_date" disabled required="" value="{{$accountData->start_date}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="end_date" class="col-sm-2 control-label">Subscription End Date:</label>
                  <div class="col-sm-10">
                    <input type="text" id="end_date" name="end_date" disabled required="" value="{{$accountData->end_date}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="plantype" class="col-sm-2 control-label">Plan Type:</label>
                  <div class="col-sm-10">
                    <input type="text" id="plantype" name="plantype" disabled required="" value="{{$accountData->type}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="no_of_users" class="col-sm-2 control-label">Number of Operations Users allowed:</label>
                  <div class="col-sm-10">
                    <input type="text" id="no_of_users" name="no_of_users" disabled required="" value="{{$accountData->no_of_users}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="no_of_service_providers" class="col-sm-2 control-label">Number of Service Providers allowed:</label>
                  <div class="col-sm-10">
                    <input type="text" id="no_of_service_providers" name="no_of_service_providers" disabled required="" value="{{$accountData->no_of_service_providers}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="no_of_support_coordinators" class="col-sm-2 control-label">Number of Support Coordinators allowed:</label>
                  <div class="col-sm-10">
                    <input type="text" id="no_of_support_coordinators" name="no_of_support_coordinators" disabled required="" value="{{$accountData->no_of_support_coordinators}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="no_of_participants" class="col-sm-2 control-label">Number of Participants allowed:</label>
                  <div class="col-sm-10">
                    <input type="text" id="no_of_participants" name="no_of_participants" disabled required="" value="{{$accountData->no_of_participants}}" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="qbname" class="col-sm-2 control-label">QB Name:</label>
                  <div class="col-sm-10">
                    <input type="text" id="qbname" name="qbname" required="" value="{{$accountData->qbname}}" class="form-control form-control-required"></input>
                  </div>
                </div>

            </div>
          </div>
          <div class="box-footer">
            <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
            <button type="submit" class="btn btn-info pull-right" id="saveBtn" value="create">Save</button>
          </div>
          </form>
          <!-- /.box (chat box) -->

      </section>
      <!-- /.Left col -->
    </div>
    <!-- /.row (main row) -->

  </section>
  <!-- /.content -->
</div>
@endsection

@section('js')


<script>
  $(function() {

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('#ModalForm').submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('account.save') }}",
        data: $(this).serialize(),
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {

        },
        success: function(data) {
          if (data.has_error) {
            OkayModal("Error", data.message);
          } else {
            OkayModal("Success", data.message);
          }

          $('#clickmodal').click();

        },
        error: function(data) {

        }
      });
    });

  });
</script>

@endsection