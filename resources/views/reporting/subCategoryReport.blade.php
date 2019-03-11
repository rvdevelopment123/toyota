@extends('app')

@section('contentheader')
	{{trans('core.report_subcategory')}}
@stop

@section('breadcrumb')
	<a href="{{route('report.index')}}">{{trans('core.report')}}</a>
	<li>{{trans('core.report_subcategory')}}</li>
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
			<b>{{trans('core.report_subcategory')}}:</b>
		 	{{carbonDate($from, 'y-m-d')}} 
		 	<b>{{trans('core.to')}}</b> 
		 	{{carbonDate($to, 'y-m-d')}} 
		 </h4>

		<table class="table table-bordered" width="100%">	
			<thead class="table-header-color">
				<th class="text-center">{{trans('core.subcategory_name')}}</th>
				<th class="text-center">{{trans('core.total_sells')}}</th>
				<th class="text-center">{{trans('core.profit')}}</th>
			</thead>

			@foreach($data as $items)
				<tr>
					@foreach($items as $item)
						<td>
							{{$item}}								
						</td>
					@endforeach
				</tr>
			@endforeach	
		</table>

		<table style="width: 40%; font-weight: bold;" align="right" class="table table-bordered visible-lg" >
			<tr style="background-color: #F8F9F9;border: 1px solid #ddd; ">
				<td><b>{{trans('core.total_profit')}} :</b></td>
				<td style="text-align: left; ">
					{{settings('currency_code')}}
					{{$total_profit}}
				</td>
			</tr>
		</table>
	</div> <!-- printable area ends -->

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