@extends('printer')

<style>
	thead tr th{
		text-align: center;
		font-size: 14px !important;
	}

	tbody tr td{
		font-size: 13px !important;
	}
</style>

@section('main-content')
	<section class="invoice">
	    <div class="row">
	      <div class="col-sm-4" style="margin-left: 20px; padding: 20px; ">
	          @if(!empty(settings('site_logo')))
              	<img src="{!! asset('uploads/site/'.settings('site_logo')) !!}" style="height: 84px; width: 190px;">
              @else
                <h4>{{settings('site_name')}}</h4>
                <p>
                	{{ trans('core.phone') }}:
		          	{{ bangla_digit(settings('phone'))}}
		        </p>
		        <p>
		          	{{ bangla_digit(settings('address'))}}
		        </p>
              @endif
	      </div>

	       	<div class="col-sm-3">
		        <h3 class="" style="text-align: center;  padding: 20px; font-weight: bolder;">
		           	{{trans('core.invoice')}}
		          	<br>
			        
			        @if(settings('vat_no'))
			          	<small style="font-size: 10px;">
			          		{{trans('core.tin')}}: {{settings('vat_no')}}
			          	</small>
			        @endif
		        </h3>
		        
		        <p class="" style="text-align: center; ">
		          <b>
		          	<small>
		          		{{trans('core.name')}}: {{$transaction->client->name}}
		          	</small>
		          </b>
		        </p>
		        <p class="" style="text-align: center; ">
		          <b>
		          	<small>
			          	@if($transaction->client->phone)
			          		{{trans('core.phone')}}: 
			          		{{$transaction->client->phone}}
			          	@endif
			          	
			          	@if($transaction->client->email)
			          		,{{trans('core.email')}}: 
			          		{{$transaction->client->email}}
			          	@endif
		          	</small>
		      	  </b>
		        </p>
	      	</div>

	       <div class="col-sm-4" style="margin-left: 20px; padding: 20px;">
	          <table class="table table-bordered">
	          	 <tr>
	          	 	<td>
	          	 		{{trans('core.invoice_no')}}
	          	 	</td>
	          	 	<td>{{$transaction->reference_no}}</td>
	          	 </tr>
	          
	          	 <tr>
	          	 	<td>
	          	 		{{trans('core.date')}}
	          	 	</td>
	          	 	<td>
	          	 		{{carbonDate($transaction->sells->first()->created_at, 'y-m-d')}}
	          	 	</td>
	          	 </tr>
	          </table>
	      </div>
	      <!-- /.col -->
	    </div> 
	    <!-- header row ends-->

	    <div class="row" >
	      <div class="col-sm-12 table-responsive" style="margin-left: 20px; ">
	        <table class="table table-bordered">
	          <thead style="background-color: #FFF !important;color: black !important;">
	          <tr>
	          	<th width="5%">{{ trans('core.si_no') }}</th>
	            <th width="15%">{{ trans('core.name') }}</th>
	            <th width="15%">{{ trans('core.quantity') }}</th>
	            <th width="15%">{{ trans('core.unit_price') }}</th>
	            <th width="15%">{{ trans('core.amount') }}</th>
		        @if(settings('product_tax'))
		        <th class="text-center" width="15%">
		          {{ trans('core.product_tax') }}
		        </th>
		        @endif
	            <th width="20%">{{ trans('core.sub_total') }}</th>
	          </tr>
	          </thead>
	          
	          <tbody>
	          	@foreach($transaction->sells as $sells)
		          <tr>
		          	<td>{{$loop->iteration}}</td>

		            <td class="text-center">
		            	{{$sells->product->name}}
		            </td>
		            
		            <td class="text-center">
		            	{{ bangla_digit($sells->quantity) }} 
		            	{{ $sells->product->unit }}
		            </td>
		            
		            <td class="text-center">
		            @if($sells->quantity > 0)
		            	{{ twoPlaceDecimal($sells->sub_total / $sells->quantity) }}
		            @else
		            	0
		            @endif
		            </td>
		            
		            <td class="text-center">
			          {{ twoPlaceDecimal($sells->sub_total)}}
			        </td>

			        @if(settings('product_tax'))
			        <td class="text-center">
			          {{ twoPlaceDecimal($sells->product_tax)}}
			        </td>
			        @endif

		            <td class="text-center">
		            	{{ twoPlaceDecimal($sells->sub_total + $sells->product_tax)}}
		            </td>
		          </tr>
	          	@endforeach

          		<tr>
          			<td>&nbsp;</td>
          			<td></td>
          			<td></td>
          			@if(settings('product_tax'))
          			<td></td>
          			@endif
          			<td></td>
          			<td></td>
          			<td></td>
          		</tr>

          		<tr style="background-color: #F8FCD4;">
          			<td></td>
          			<td><b>{{trans('core.total')}}</b></td>
          			<td><b>{{$total_quanity}}</b></td>
          			<td></td>

          			<td>
          				<b>{{twoPlaceDecimal($transaction->total + $transaction->discount)}}</b>
          			</td>
          			
          			@if(settings('product_tax'))
          				<td>
          					<b>{{twoPlaceDecimal($transaction->total_tax - $transaction->invoice_tax)}}</b>
          				</td>
          			@endif
          			
          			<td>
          				<b>{{twoPlaceDecimal($transaction->total + $transaction->discount + ($transaction->total_tax - $transaction->invoice_tax))}}</b>
          			</td>
          		</tr>

          		@if($transaction->discount > 0)
          		<tr>
          			<td colspan="{{sellDetailsColSpanNumber() + 1}}" style="text-align: right">
          				<b>{{trans('core.discount')}}:</b>
          			</td>
          			<td>
          				{{twoPlaceDecimal($transaction->discount)}}
          			</td>
          		</tr>
          		@endif

          		@if($transaction->invoice_tax > 0)
          		<tr>
          			<td colspan="{{sellDetailsColSpanNumber() + 1}}" style="text-align: right">
          				<b>
          					{{trans('core.invoice_tax')}}
          					({{orderTax()}} 
          					@if($transaction->return != 1) of {{$transaction->total + $transaction->total_tax - $transaction->invoice_tax}}) @endif
          				</b>
          			</td>
          			<td>
          				{{twoPlaceDecimal($transaction->invoice_tax)}}
          			</td>
          		</tr>
          		@endif

          		<tr>
          			<td colspan="{{sellDetailsColSpanNumber() + 1}}" style="text-align: right">
          				<b>{{trans('core.net_total')}}:</b>
          			</td>
          			<td>
          				{{twoPlaceDecimal($transaction->net_total)}}
          			</td>
          		</tr>

          		<tr>
          			<td colspan="{{sellDetailsColSpanNumber() + 1}}" style="text-align: right">
          				<b>{{trans('core.paid')}}:</b>
          			</td>
          			<td>
          				{{twoPlaceDecimal($transaction->paid)}}
          			</td>
          		</tr>

          		@if($transaction->net_total - $transaction->paid != 0)
          		<tr>
          			<td colspan="{{sellDetailsColSpanNumber() + 1}}" style="text-align: right">
          				<b>{{trans('core.due')}}:</b>
          			</td>
          			<td>
          				{{twoPlaceDecimal($transaction->net_total - $transaction->paid)}}
          			</td>
          		</tr>
          		@endif

	          </tbody>
	        </table>
	      </div>
	      <!-- /.col -->
	    </div>
	    <!-- /.row -->

	    <div class="row">
	    	<div class="col-md-12" style="margin-left: 20px;">
		    	<span class="amount-in-words">
			    	{{trans('core.amount')}} (In Words)
			        <br>
			        <b>{{settings('currency_code')}} {{numberFormatter($transaction->net_total)}}</b>
			    	<br>
			    	<br>
			    </span>
	    	</div>
	    </div>

	    <div class="row">
	    	<div class="col-sm-5" style="margin-left: 20px;">
	          	<span class="declaration_header">
	          		{{trans('core.declaration')}}
	          	</span>
	          	<br>
	          		{{trans('core.declaration_text')}}
		    	<br>
		    	<br><br>
		    	<span class="customer-signature">
		    		{{trans('core.customer_seal')}}:
		    	</span>
		    	<br><br><br>
	        </div>

	        <div class="col-sm-offset-2 col-sm-4 pull-right" >
	          	<span>&nbsp;</span>
	    		<br><br><br>
	    		for <b>{{settings('site_name')}}</b>
	    		<br><br>
	    		{{trans('core.authorized_signature')}}
	        </div>
	    </div>
  	</section>
	
@stop