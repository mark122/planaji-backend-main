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
            <h3 class="box-title">Pricing Guide
            </h3>

          </div>
          <div class="box-body">
            <table class="table table-bordered data-table display nowrap table-example1">
              <thead>
                <tr>
                  <th>Support Item Number</th>
                  <th>Support Item Name</th>
                  <th>Registration Group Number</th>
                  <th>Registration Group Name</th>
                  <th>Support Category Number</th>
                  <th>Support Categories Id</th>
                  <th>Unit</th>
                  <th>Quote</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>ACT</th>
                  <th>NSW</th>
                  <th>NT</th>
                  <th>QLD</th>
                  <th>SA</th>
                  <th>TAS</th>
                  <th>VIC</th>
                  <th>WA</th>
                  <th>Remote</th>
                  <th>Very Remote</th>
                  <th>Non Face To Face Support Provision</th>
                  <th>Provider Travel</th>
                  <th>Short Notice Cancellations</th>
                  <th>NDIA Requested Reports</th>
                  <th>Irregular Sil Supports</th>
                  <th>Type</th>
                </tr>
              </thead>
              <tbody>
                {{-- <tr>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                  <td>adas</td>
                </tr> --}}
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->
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

<script>
  $.ajaxSetup({

    headers: {

      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

  });

  const dollarUSLocale = new Intl.NumberFormat('en-US', {
    currency: 'USD',
    minimumFractionDigits: 2
  })


  var table = $('.data-table').DataTable({

    // responsive: true,

    processing: true,

    serverSide: true,
    "scrollX": true,

    ajax: "{{route('pricing.loadrecords')}}",

    columns: [{
        data: 'support_item_number',
        name: 'support_item_number'
      },
      {
        data: 'support_item_name',
        name: 'support_item_name'
      },
      {
        data: 'registration_group_number',
        name: 'registration_group_number'
      },
      {
        data: 'registration_group_name',
        name: 'registration_group_name'
      },
      {
        data: 'support_category_number',
        name: 'support_category_number'
      },
      {
        data: 'support_categories_id',
        name: 'support_categories_id'
      },
      {
        data: 'unit',
        name: 'unit'
      },
      {
        data: 'quote',
        name: 'quote'
      },
      {
        data: 'start_date',
        name: 'start_date'
      },
      {
        data: 'end_date',
        name: 'end_date'
      },
      {
        data: 'ACT',
        render: function(data, type, row) {

          var _act = ''
          if ((row.ACT - Math.floor(row.ACT)) != 0) {
            _act = '$' + row.ACT;
          } 
          else {
            _act = '$' + dollarUSLocale.format(row.ACT);
          }
          return _act;
          
        }
      },
      {
        data: 'NSW',
        render: function(data, type, row) {

          var _nsw = ''
          if ((row.NSW - Math.floor(row.NSW)) != 0) {
            _nsw = '$' + row.NSW;
          } 
          else {
            _nsw = '$' + dollarUSLocale.format(row.NSW);
          }
          return _nsw;

        }
      },
      {
        data: 'NT',
        render: function(data, type, row) {

          var _nt = ''
          if ((row.NT - Math.floor(row.NT)) != 0) {
            _nt = '$' + row.NT;
          } 
          else {
            _nt = '$' + dollarUSLocale.format(row.NT);
          }
          return _nt;

        }
      },
      {
        data: 'QLD',
        render: function(data, type, row) {

          var _qld = ''
          if ((row.QLD - Math.floor(row.QLD)) != 0) {
            _qld = '$' + row.QLD;
          } 
          else {
            _qld = '$' + dollarUSLocale.format(row.QLD);
          }
          return _qld;

        }
      },
      {
        data: 'SA',
        render: function(data, type, row) {

          var _sa = ''
          if ((row.SA - Math.floor(row.SA)) != 0) {
            _sa = '$' + row.SA;
          } 
          else {
            _sa = '$' + dollarUSLocale.format(row.SA);
          }
          return _sa;

        }
      },
      {
        data: 'TAS',
        render: function(data, type, row) {

          var _tas = ''
          if ((row.TAS - Math.floor(row.TAS)) != 0) {
            _tas = '$' + row.TAS;
          } 
          else {
            _tas = '$' + dollarUSLocale.format(row.TAS);
          }
          return _tas;

        }
      },
      {
        data: 'VIC',
        render: function(data, type, row) {

          var _vic = ''
          if ((row.VIC - Math.floor(row.VIC)) != 0) {
            _vic = '$' + row.VIC;
          } 
          else {
            _vic = '$' + dollarUSLocale.format(row.VIC);
          }
          return _vic;

        }
      },
      {
        data: 'WA',
        render: function(data, type, row) {

          var _wa = ''
          if ((row.WA - Math.floor(row.WA)) != 0) {
            _wa = '$' + row.WA;
          } 
          else {
            _wa = '$' + dollarUSLocale.format(row.WA);
          }
          return _wa;

        }
      },
      {
        data: 'remote',
        render: function(data, type, row) {

          var _remote = ''
          if ((row.remote - Math.floor(row.remote)) != 0) {
            _remote = '$' + row.remote;
          } 
          else {
            _remote = '$' + dollarUSLocale.format(row.remote);
          }
          return _remote;

        }
      },
      {
        data: 'very_remote',
        render: function(data, type, row) {

          var _very_remote = ''
          if ((row.very_remote - Math.floor(row.very_remote)) != 0) {
            _very_remote = '$' + row.very_remote;
          } 
          else {
            _very_remote = '$' + dollarUSLocale.format(row.very_remote);
          }
          return _very_remote;

        }
      },
      {
        data: 'non_face_to_face_support_provisionprovider_travel',
        name: 'non_face_to_face_support_provisionprovider_travel'
      },
      {
        data: 'provider_travel',
        name: 'provider_travel'
      },
      {
        data: 'short_notice_cancellations',
        name: 'short_notice_cancellations'
      },
      {
        data: 'NDIA_requested_reports',
        name: 'NDIA_requested_reports'
      },
      {
        data: 'irregular_sil_supports',
        name: 'irregular_sil_supports'
      },
      {
        data: 'type',
        name: 'type'
      }
    ]
  });
</script>

@endsection