@extends('app')

@section('contentheader')
	{{trans('core.report_product')}}
@stop

@section('breadcrumb')
	{{trans('core.report_product')}}
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
			<b>{{trans('core.report_product')}}:</b>
		 	{{carbonDate($from, 'y-m-d')}} 
		 	<b>{{trans('core.to')}}</b> 
		 	{{carbonDate($to, 'y-m-d')}}
		 	<br>
		 	<b>{{trans('core.warehouse')}}: </b>{{$warehouse_name}}
		 </h4>

		 <br />

		<table class="table table-bordered" width="100%">	
			<thead  class="table-header-color">
				<th class="text-center">{{trans('core.product')}}</th>
				<th class="text-center">{{trans('core.sell')}}</th>
				<th class="text-center">{{trans('core.purchase')}}</th>
				<th class="text-center">{{trans('core.profit')}}</th>
			</thead>

			@foreach($total as $items)
				<tr>
					@foreach($items as $item)
						<td class="text-center">
							{{$item}}								
						</td>
					@endforeach
				</tr>
			@endforeach		
		</table>

		<table style="width: 60%; font-weight: bold;" align="right" class="table table-bordered visible-lg" >
			<tr style="background-color: #F8F9F9;border: 2px solid #ddd;">
				<td width="50%">
					{{trans('core.total_profit')}} *
				</td>
				<td style="text-align: center;font-weight: bold; ">
					{{settings('currency_code')}}
					{{twoPlaceDecimal($total_profit)}}
				</td>
			</tr>
			
		</table>

	</div> <!--Printable area-->

	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('report.index')}}">
	        <i class="fa fa-backward"></i> 
	        {{trans('core.back')}}
	    </a>
	</div>
	
	<div class="pull-right visible-lg">
		<small>
		<b>*Note:</b> {{trans('core.total_profit')}} = {{trans('core.selling_goods_total_price')}} - {{trans('core.selling_goods_total_cost_price')}} 
		{{(trans('core.excluding_tax'))}}
		</small>
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