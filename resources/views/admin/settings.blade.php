@extends('admin.layouts.app')

@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-cogs"></i> Settings</a></li>
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
                  <label for="username" class="col-sm-2 control-label">Username:</label>
                  <div class="col-sm-10">
                    <input type="text" id="username" name="username" disabled required="" value="{{$user_data->name}}" placeholder="Enter Username" class="form-control form-control-required"></input>
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label for="email_adress" class="col-sm-2 control-label">Email Address:</label>
                  <div class="col-sm-10">
                    <input type="text" id="email_address" name="email_address" disabled required="" value="{{$user_data->email}}" placeholder="Enter Email Address" class="form-control form-control-required"></input>
                  </div>
                </div>
              
                <div class="col-md-12">
                  <hr>
                  <h5><b>Change Password:</b> </h5>
                  <ul style="list-style-type:square">
                    <li>Must be at least 6 characters in length</li>
                    <li>Must contain at least one lowercase letter</li>
                    <li>Must contain at least one uppercase letter</li>
                    <li>Must contain at least one digit</li>
                    <li>Must contain a special character</li>
                  </ul>
                </div>

                <div class="form-group col-sm-12">
                  <label for="email_adress" class="col-sm-2 control-label">Old password:</label>
                  <div class="col-sm-10">
                    <input type="password" id="old_password" name="old_password"  required="" placeholder="Enter Old Password" class="form-control form-control-required"></input>
                  </div>
                </div>

                <div class="form-group col-sm-12">
                  <label for="email_adress" class="col-sm-2 control-label">New password:</label>
                  <div class="col-sm-10">
                    <input type="password" id="new_password" name="new_password"  required="" placeholder="Enter New Password" class="form-control form-control-required"></input>
                  </div>
                </div>
                <div class="form-group col-sm-12">
                  <label for="email_adress" class="col-sm-2 control-label">Retype new password:</label>
                  <div class="col-sm-10">
                    <input type="password" id="retype_new_password" name="retype_new_password"  required="" placeholder="Enter Retype New Password" class="form-control form-control-required"></input>
                  </div>
                </div>
             </div>
            </div>
            <div class="box-footer">
                <!-- <button type="submit" class="btn btn-default">Cancel</button> -->
                <button type="submit" class="btn btn-info pull-right" id="saveBtn" value="create">Save</button>
              </div>
              <!-- /.box-footer -->
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
        url: "{{ route('settings.save') }}",
        data: $(this).serialize(),
        type: "POST",
        dataType: 'json',
        beforeSend: function(e) {
          
        },
        success: function(data) {
          if(data.has_error){
            OkayModal("Error", data.message);
          }else{
            OkayModal("Success", data.message);
          }

          $('#clickmodal').click();
          
          $('#old_password').val('');
          $('#new_password').val('');
          $('#retype_new_password').val('');

        },
        error: function(data) {
        
        }
      });
    });
  });

  </script>

@endsection