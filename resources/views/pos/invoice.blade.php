@extends('layouts.pos')

@section('title')
	@parent :: POS
@stop

@section('css')
	<link rel="stylesheet" href="/assets/css-core/pos-invoice.css">
@stop

@section('main-content')
	<div id="printableArea">
		<div id="invoice-POS">
		    <div id="top" style="border-bottom: 1px solid #EEE; margin-bottom: 8px;">
			    <center>
			      <!-- <div class="logo" style="background: url({{asset('uploads/site/'.settings('site_logo')) }}) no-repeat">
			      </div> -->
			      <div class="info"> 
			        <h2>
			        	<b>{{settings('site_name')}}</b>
			        </h2>
			        <p> 
			            {{trans('core.address')}}: 
			            {{settings('address')}}
			            <br>
			            {{trans('core.email')}}: 
			            {{settings('email')}}
			            <br>
			            {{trans('core.phone')}}: 
			            {{settings('phone')}}
			            <br><br>
			        </p>
			      </div><!--End Info-->
			    </center>

			    <div class="row ref">
			    	<div class="col-md-12">
			    		{{trans('core.invoice_no')}}:
			    		{{$transaction->reference_no}}
			    	</div>
			    	<div class="col-md-12">
			    		{{trans('core.date')}}:
			    		{{carbonDate($transaction->created_at, '')}}
			    	</div>
			    	<div class="col-md-12">
			    		{{trans('core.biller')}}:
			    		{{Auth::user()->name}}
			    	</div>
			    </div>
			</div>
			<!--End InvoiceTop-->
		      
	    
	    	<div id="bot">
				<div id="table">
					<table class="table table-bordered">
						<tr class="tabletitle" >
							<td>
								<span class="table-header">Item</span>
							</td>
							<td>
								<span class="table-header">Qty</span>
							</td>
							<td>
								<span class="table-header">Price</span>
							</td>
							<td>
								<span class="table-header">Sub Total</span>
							</td>
						</tr>

						@foreach($transaction->sells as $sell)
						<tr class="service">
							<td class="tableitem">
								<p class="itemtext">{{$sell->product->name}}</p>
							</td>
							<td class="tableitem">
								<p class="itemtext">{{$sell->quantity}}</p>
							</td>
							<td class="tableitem">
								<p class="itemtext">
									@if($sell->quantity > 0)
										{{$sell->sub_total / $sell->quantity}}
									@else
										0
									@endif
								</p>
							</td>
							<td class="tableitem">
								<p class="itemtext">
									<!-- {{settings('currency_code')}} -->
									{{$sell->sub_total}}
								</p>
							</td>
						</tr>
						@endforeach

						<tr class="tabletitle">
							<td class="Rate text-right" colspan="3">
								<span class="table-footer">{{trans('core.total')}}: &nbsp;&nbsp;</span class="table-footer">
							</td>
							<td class="payment">
								<span class="table-footer">
									<!-- {{settings('currency_code')}} -->
									{{$transaction->total + $transaction->discount}}
								</span class="table-footer">
							</td>
						</tr>

						<tr class="tabletitle">
							<td class="Rate text-right" colspan="3">
								<span class="table-footer">{{trans('core.discount')}}: &nbsp;&nbsp;</span class="table-footer">
							</td>
							<td class="payment">
								<span class="table-footer">
									<!-- {{settings('currency_code')}} -->
									{{$transaction->discount}}
								</span class="table-footer">
							</td>
						</tr>

						@if($transaction->total_tax > 0)
						<tr class="tabletitle">
							<td class="Rate text-right" colspan="3">
								<span class="table-footer">{{trans('core.vat')}}: &nbsp;&nbsp;</span class="table-footer">
							</td>
							<td class="payment">
								<span class="table-footer">
									<!-- {{settings('currency_code')}} -->
									{{$transaction->net_total}}
								</span class="table-footer">
							</td>
						</tr>
						@endif

						<tr class="tabletitle">
							<td class="Rate text-right" colspan="3">
								<span class="table-footer">{{trans('core.net_total')}}: &nbsp;&nbsp;</span class="table-footer">
							</td>
							<td class="payment">
								<span class="table-footer">
									<!-- {{settings('currency_code')}} -->
									{{$transaction->net_total}}
								</span class="table-footer">
							</td>
						</tr>

						<tr class="tabletitle">
							<td class="Rate text-right" colspan="3">
								<span class="table-footer">{{trans('core.paid')}}: &nbsp;&nbsp;</span class="table-footer">
							</td>
							<td class="payment">
								<span class="table-footer">
									<!-- {{settings('currency_code')}} -->
									{{$transaction->paid + $transaction->change_amount}}
								</span class="table-footer">
							</td>
						</tr>

						@if($transaction->change_amount > 0)
						<tr class="tabletitle">
							<td class="Rate text-right" colspan="3">
								<span class="table-footer">{{trans('core.change_amount')}}: &nbsp;&nbsp;</span class="table-footer">
							</td>
							<td class="payment">
								<span class="table-footer">
									<!-- {{settings('currency_code')}} -->
									{{$transaction->change_amount}}
								</span class="table-footer">
							</td>
						</tr>
						@endif

					</table>
				</div><!--End Table-->

				<div id="legalcopy" style="padding-bottom: 10px;">
					<span class="table-footer">
						<strong>Thank you for shopping!</strong>  
						<br>
						 {{settings('pos_invoice_footer_text')}}
					</span>
				</div>

			</div><!--End InvoiceBot-->

			<div style="text-align: center;  font-size: 10px; color: black;">
				A Software By Intelle Hub Inc.
				<br>
				+880 1674871091, info@intelle-hub.com
			</div>
	  	</div><!--End Invoice-->
  	</div> <!--Printable Div Ends-->

  	<div class="invoice-pos-footer">
  		<div class="row">
  			<div class="col-md-6">
  				<a class="btn btn-success btn-block" onclick="printDiv('printableArea')" >
  					{{trans('core.print')}}
  					<i class="fa fa-print"></i>
  				</a>
  			</div>

  			<div class="col-md-6">
  				<a class="btn btn-danger btn-block" href="{{route('sell.pos')}}">
  					<i class="fa fa-backward"></i>
  					{{trans('core.back')}}
  				</a>
  			</div>
  		</div>
  	</div>
@stop


@section('js')
	@parent
	<script>
		function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
	</script>


@stop