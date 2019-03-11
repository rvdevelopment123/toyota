@extends('printer')

<style>
	td{	
		font-size: 13px;
	}
</style>

@section('main-content')
	
	<center>
	    <h4>
	    <strong>&nbsp;&nbsp;<?php echo settings('site_name'); ?> </strong></h4>
	    <small>&nbsp;<?php echo settings('address'); ?> </small><br>
	    <small>
	    		Phone:<?php echo settings('phone'); ?>, &nbsp; 
	    		Email:<?php echo settings('email'); ?>
	    </small>
	</center>

	<table class="table table-bordered " width="100%" style="font-size:10px;">
		<tr>
			<td><b>Name</b></td>
			<td><b>Quantity</b></td>
			<td><b>Stock Cost Value</b></td>
			<td><b>Stock Sell Value</b></td>
		</tr>

		<tbody>
			@foreach($products as $product)
				<tr>
					<td>{{$product->name}}</td>
					
					<td>
						@if($product->quantity > 0)
							{{$product->quantity}} {{$product->unit}}
						@else
						 0
						@endif
					</td>
					
					<td>
						{{$product->quantity * $product->cost_price}}
						{{settings('currency_code')}}
					</td>
					
					<td>
						{{$product->quantity * $product->mrp}}
						{{settings('currency_code')}}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@stop