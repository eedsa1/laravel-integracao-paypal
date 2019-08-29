<!DOCTYPE html>

@php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

@endphp
<html>
<head>
	<title>Integração com o PayPal</title>

	<link rel="stylesheet" type="text/css" href="https://www.w3schools.com/w3css/4/w3.css">

</head>
<body>

	<div class="w3-container">

		@if ($message = Session::get('success'))
		<div class="w3-panel w3-green w3-display-container">
			<span onclick="this.parentElement.style.display='none'" class="w3-button w3-green w3-large w3-display-topright">
				&times;
			</span>
			<p>{!! $message !!}</p>
		</div>
		<?php Session::forget('success'); ?>
		@endif

		@if ($message = Session::get('error'))
		<div class="w3-panel w3-red w3-display-container">
			<span onclick="this.parentElement.style.display='none'" class="w3-button w3-red w3-large w3-display-topright">
				&times;
			</span>
			<p>{!! $message !!}</p>
		</div>
		<?php Session::forget('error'); ?>
		@endif

		<form class="w3-container w3-display-middle w3-card-4 w3-padding-16" method="POST" id="payment-form" action="{!! URL::to('paypal') !!}">
			{{ csrf_field() }}
			
			<div class="w3-container w3-teal w3-padding-16">Pague com o PayPal</div>
			<h2 class="w3-text-blue">Form de pagamento</h2>
			<p>Form de demonstração - Integrando paypal no laravel</p>
			<label class="w3-text-blue"><b>Enter Amount</b></label>
			<input class="w3-input w3-border" id="amount" type="text" name="amount"/>
			<br/>
			<button class="w3-btn w3-blue">Pague com o Paypal</button>
			
		</form>

	</div>
</body>
</html>