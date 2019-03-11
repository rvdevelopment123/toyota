@extends('app')

@section('contentheader')
	Today's Profit Details
@stop

@section('breadcrumb')
	Today's Profit Details
@stop

@section('main-content')

	<div class="panel-heading">
		<a href="#" class="btn btn-alt btn-warning btn-xs" onclick="printDiv('printableArea')">
			<i class="fa fa-print"></i>
			{{trans('core.print')}}
		</a>
	</div>

	<div class="panel-body">
		<div id="printableArea">
			<table class="table table-bordered" >
				<tbody style="background-color: #fff;" id="myTable">
					<tr>
						<td>{{trans('core.total_sells')}}</td>
						<td>{{settings('currency_code')}} {{bangla_digit($SellItemMrp)}}</td>
					</tr>
					<tr>
						<td>{{trans('core.cost_of_sales')}}</td>
						<td>
							{{settings('currency_code')}} 
							{{bangla_digit($SellItemCost)}}
						</td>
					</tr>
					<tr>
						<td>
							{{trans('core.product_revenue')}}
							( {{trans('core.total_sells')}} - {{trans('core.cost_of_sales')}} )
						</td>
						<td>
							{{settings('currency_code')}} 
							{{bangla_digit($todaysProfit)}}
						</td>
					</tr>

					<tr>
						<td>
							{{trans('core.todays_expense')}}
						</td>
						<td>
							(-) {{settings('currency_code')}} 
							{{bangla_digit($todaysExpense)}}
						</td>
					</tr>

					<tr>
						<td>
							<b>{{trans('core.net_profit_before_tax')}}</b>
						</td>
						<td>
							<b>{{settings('currency_code')}} 
							{{bangla_digit($todaysProfit - $todaysExpense)}}</b>
						</td>
					</tr>

					<tr>
				 		<td>Total Tax</td>
				 		<td>
				 			(-) {{settings('currency_code')}}
				 			{{$SellItemTax}}
				 		</td>
				 	</tr>

				 	<tr style="background-color: #f7f7f7;"> 
				 		<td><b>{{trans('core.net_profit_after_tax')}}</b></td>
				 		<td>
				 			<b>
					 			{{settings('currency_code')}}
					 			{{bangla_digit($todaysProfit - $todaysExpense - $SellItemTax)}}
				 			</b>
				 		</td>
				 	</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="panel-footer">	
        <span style="padding: 10px;">
        
        </span>	
		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('home')}}">
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