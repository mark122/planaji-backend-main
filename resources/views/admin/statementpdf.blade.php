<!DOCTYPE html>
<html>

<head>
	<title>NDIS Statement ({{$data['start_date'].' - '.$data['end_date']}})</title>
</head>
<style>
	body {
		font-family: 'Poppins' !important;
	}

	table {
		font-family: 'Poppins' !important;
		border-collapse: collapse;
		width: 100%;
	}

	td,
	th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
	}

	tr:nth-child(even) {
		background-color: #dddddd;
	}
	footer {
        position: fixed; 
        bottom: -10px; 
        left: 0px; 
        right: 0px;
        height: 50px; 
    }
</style>

<body>
	<div style="display: flex; justify-content: space-between;">
		<div style="width:50%">
			<span class="logo-lg">
			@if($data['plan_manager_email']=="pinky+mbs@zithera.com")
			<img style="width: 30%" src="{{ public_path('assets/images/enterpriselogin/logo.jpeg') }}" class="img-rectangle" alt="logo">
			@else
				<img style="width: 30%" src="{{ isset($planmanager[0]->custom_logo) ? public_path($planmanager[0]->custom_logo) : public_path('assets/images/logo/logo_planaji.png') }}" class="img-rectangle" alt="logo">
			@endif
				
			</span>
			{{-- <h1 style="color: darkblue;">company logo</h1> --}}
		</div>
		<div style="width:50%; margin-left:370px;">
			<h2 style="color: darkblue;">Your Plan Management Statement</h2>
			<span style="display: block"><b>Client: </b> {{$data['name']}}</span>
			<span style="display: block"><b>NDIS Number: </b> {{$data['ndis_number']}} </span>
			<span style="display: block"><b>Statement Period: </b> {{$data['start_date'].' - '.$data['end_date']}} </span>
			<span style="display: block"><b>NDIS Start Date: </b> {{$data['ndis_start_date']}} </span>
			<span style="display: block"><b>NDIS End Date :</b> {{$data['ndis_end_date']}} </span>
		</div>
	</div>
	<div style="font-size: 18px">
		<span style="display: block">Dear {{$data['name']}},</span>
		<br><span style="display: block">This is your Statement for the period {{$data['start_date'].' - '.$data['end_date']}}</span>
		<span style="display: block">If you have any questions please don't hesitate to call or email on the details below.</span>
		<br><span style="display: block">Regards, </span>
		<br><span style="display: block">{{$data['plan_manager_name']}} </span>
		<span style="display: block">{{$data['plan_manager_email']}}</span>
		<span style="display: block">{{$data['plan_manager_number']}}</span>
	</div>
	<br>
	<h2 style="color: darkblue;">Category Budget Summary</h2>
	<div>
		<div style="width:100%;">
			<table class="table table-striped" style="width:100%;">
				<thead>
					<tr style="background-color: #00aeef !important; color: white !important; text_align: left;">
						<th style="padding: 6px 10px;">Support Item</th>
						<th style="padding: 6px 10px;">Total Allocated Budget</th>
						<th style="padding: 6px 10px;">Opening Budget</th>
						<th style="padding: 6px 10px;">Used Budget</th>
						<th style="padding: 6px 10px;">Remaining Budget</th>
						<th style="padding: 6px 10px;">Overall Usage %</th>
					</tr>
				</thead>
				<tbody>
					@if(isset($core_support_total_budget[0]->total_budget))
					<tr>
						<td style="padding: 6px 10px;">Core Support</td>
						<td style="padding: 6px 10px;" class = "currency">${{number_format($core_support_total_budget[0]->total_budget, 2, '.', ',') }}</td>
						<td style="padding: 6px 10px;">${{number_format($data['core_opening'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['core_used_budget'], 2, '.', ',') }}</td>
						<td style="padding: 6px 10px;">${{number_format($data['core_opening'] - $data['core_used_budget'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">{{$data['core_used']}}%</td>
					</tr>
					@endif
					@if(isset($capital_total_budget[0]->total_budget))
					<tr>
						<td style="padding: 6px 10px;">Capital Support</td>
						<td style="padding: 6px 10px;">${{number_format($capital_total_budget[0]->total_budget, 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['capital_opening'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['capital_used_budget'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['capital_opening'] - $data['capital_used_budget'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">{{$data['capital_used']}}%</td>
					</tr>
					@endif
					@if(isset($capacity_building_total_budget[0]->total_budget))
					<tr>
						<td style="padding: 6px 10px;">Capacity Building</td>
						<td style="padding: 6px 10px;">${{number_format($capacity_building_total_budget[0]->total_budget, 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['capacity_opening'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['capacity_used_budget'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">${{number_format($data['capacity_opening'] - $data['capacity_used_budget'], 2, '.', ',')}}</td>
						<td style="padding: 6px 10px;">{{$data['capacity_used']}}%</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	<div>
		<h2 style="color: darkblue;" class="box-title" id="box-core-header">
			Paid Invoices Summary</h2>
		<div>
			<table class="table table-striped">
				<thead>
					<tr style="background-color: #00aeef  !important; color: white !important; text_align: left;">
						<th style="padding: 6px 10px;">Invoice Date</th>
						<th style="padding: 6px 10px;">Invoice Number</th>
						<th style="padding: 6px 10px;">Service Provider</th>
						<th style="padding: 6px 10px;">Support Item Number</th>
						<th style="padding: 6px 10px;">Description</th>
						<th style="padding: 6px 10px;">Claim Type</th>
						<th style="padding: 6px 10px;">Amount</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($invoice_details as $invoices)
					@foreach ($invoices as $invoice)
					@if($invoice->invoice_date >= $data['start_date'] && $invoice->invoice_date <= $data['end_date']) <tr>
						<td style="padding: 6px 10px;">{{$invoice->invoice_date}}</td>
						<td style="padding: 6px 10px;">{{$invoice->invoice_number}}</td>
						<td style="padding: 6px 10px;">{{$invoice->service_provider_first_name}} {{$invoice->service_provider_last_name}}</td>
						<td style="padding: 6px 10px;">{{$invoice->support_item_number}}</td>
						<td style="padding: 6px 10px;">{{$invoice->support_item_name}}</td>
						<td style="padding: 6px 10px;">{{$invoice->claimtypecode}}</td>
						<td style="padding: 6px 10px;">${{number_format($invoice->amount, 2, '.', ',')}}</td>
						</tr>
						@endif
						@endforeach
						@endforeach
				</tbody>
			</table>
		</div>
	</div>
	@if($data['plan_manager_email']=="pinky+mbs@zithera.com")
	<footer>
		<span class="logo-md-3">
			<img style="width:20%;margin-left:565px;margin-top:25px;" src="{{ public_path('assets/images/logo/logo_planaji.png') }}" class="img-rectangle" alt="logo">
		</span>
	</footer>
	@endif	
</body>

</html>
