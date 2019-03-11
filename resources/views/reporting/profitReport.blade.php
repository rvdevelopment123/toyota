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
			<b>{{trans('core.report_profit')}}:</b>
		 	{{carbonDate($from, 'y-m-d')}} 
		 	<b>{{trans('core.to')}}</b> 
		 	{{carbonDate($to, 'y-m-d')}} 
		 </h4>

		 <h4 class="text-center">	
			<b>{{trans('core.warehouse')}}:</b> {{$branch_name}}
		</h4>

		<br>

		 <table class="table table-bordered">
		 	<tr>
		 		<td class="text-center">{{trans('core.total_sells')}}</td>
		 		<td class="text-center">
		 			{{settings('currency_code')}}
		 			{{$total_selling_price}}
		 		</td>
		 	</tr>

		 	<tr>
		 		<td class="text-center">{{trans('core.cost_of_sales')}}</td>
		 		<td class="text-center">
		 			{{settings('currency_code')}}
		 			{{$total_cost_price}}
		 		</td>
		 	</tr>


		 	<tr>
		 		<td class="text-center">{{trans('core.gross_profit')}}</td>
		 		<td class="text-center">
		 			{{settings('currency_code')}}
		 			{{$gross_profit}}
		 		</td>
		 	</tr>

		 	<tr>
		 		<td class="text-center">Total Expense</td>
		 		<td class="text-center">
		 			(-) {{settings('currency_code')}}
		 			{{$total_expense}}
		 		</td>
		 	</tr>

		 	<tr>
		 		<td class="text-center">{{trans('core.net_profit_before_tax')}}</td>
		 		<td class="text-center">
		 			{{settings('currency_code')}}
		 			{{$net_profit}}
		 		</td>
		 	</tr>

		 	<tr>
		 		<td class="text-center">Total Tax</td>
		 		<td class="text-center">
		 			(-) {{settings('currency_code')}}
		 			{{$total_tax}}
		 		</td>
		 	</tr>

		 	<tr style="background-color: #f7f7f7;"> 
		 		<td class="text-center"><b>{{trans('core.net_profit_after_tax')}}</b></td>
		 		<td class="text-center">
		 			<b>
			 			{{settings('currency_code')}}
			 			{{$net_profit_after_tax}}
		 			</b>
		 		</td>
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