@extends('app')

@section('contentheader')
	{{trans('core.report_purchase')}}
@stop

@section('breadcrumb')
	{{trans('core.report_purchase')}}
@stop

@section('main-content')
	<div class="panel-heading">
		<a href="#" class="btn btn-border btn-alt border-orange font-orange btn-xs " onclick="printDiv('printableArea')" >
			<i class="fa fa-print"></i>
			{{trans('core.print')}}
		</a>
	</div>	

	<div id="printableArea" class="panel-body">
		<h4 class="text-center">	
			<b>{{trans('core.report_purchase')}}:</b>
		 	{{carbonDate($from, 'y-m-d')}} 
		 	<b>{{trans('core.to')}}</b> 
		 	{{carbonDate($to, 'y-m-d')}} 
		 </h4><br />

		<table class="table table-bordered" width="100%">	
			<thead class="table-header-color">
				<th class="text-center">#</th>
				<th class="text-center">{{trans('core.ref_no')}}</th>
				<th class="text-center">{{trans('core.supplier')}}</th>
				<th class="text-center">{{trans('core.product')}}</th>
				<th class="text-center">{{trans('core.total')}}</th>
				<th class="text-center">{{trans('core.warehouse')}}</th>
				<th class="text-center">{{trans('core.date')}}</th>
			</thead>

			<?php $total_quantity = 0; ?>
			@foreach($transactions as $transaction)
				<tr>
					<td class="text-center">{{$loop->iteration}}</td>
					<td class="text-center">{{$transaction->reference_no}}</td>
					<td class="text-center">{{$transaction->client->name}}</td>
					<td class="text-center">								
						<ol>
							@foreach($transaction->purchases as $purchases)
								<li>
								 	<?php 
								  		$total_quantity = $total_quantity + $purchases->quantity;
								  	?>
									{{$purchases->product->name}}
									(	
									  {{$purchases->quantity}} 
									  {{$purchases->product->unit}}
									)
								</li>
							@endforeach
						</ol>
					</td>
					<td class="text-center">
						{{settings('currency_code')}}
						{{$transaction->net_total}}
					</td>
					<td class="text-center">
						{{$transaction->warehouse->name}}
					</td>
					<td class="text-center">
						{{carbonDate($transaction->created_at, 'y-m-d')}}
					</td>
				</tr>
			@endforeach			
		</table>

		<table style="width: 50%; font-weight: bold;" align="right" class="table table-bordered visible-lg">
			<tr style="background-color: #F8F9F9;border: 1px solid #ddd;">
				<td style="text-align: right;">
					<b>{{trans('core.total_amount')}} : </b>
				</td>
				<td>
					{{settings('currency_code')}}
					{{twoPlaceDecimal($transactions->sum('net_total'))}}
				</td>
			</tr>

			<tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
				<td style="text-align: right;">
					<b> {{trans('core.total_tax')}} : </b>
				</td>
				<td>
					{{settings('currency_code')}}
					{{twoPlaceDecimal($transactions->sum('total_tax'))}}
				</td>
			</tr>

			<tr style="background-color: #F8F9F9;border: 1px solid #ddd;">
				<td style="text-align: right;">
					<b> {{trans('core.total_quantity')}} : </b>
				</td>
				<td>{{$total_quantity}}</td>
			</tr>

			
		</table>
	</div> <!-- Printable div ends -->

	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('report.index')}}">
	        <i class="fa fa-backward"></i> 
	        {{trans('core.back')}}
	    </a>
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