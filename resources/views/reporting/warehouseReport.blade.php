@extends('app')

@section('contentheader')
	{{trans('core.report_warehouse')}}
@stop

@section('breadcrumb')
	<a href="{{route('report.index')}}">{{trans('core.report')}}</a>
	<li>{{trans('core.report_warehouse')}}</li>
@stop

@section('main-content')              
	<div class="panel-heading">
		<a href="#" class="btn btn-border btn-alt border-orange font-orange btn-xs" onclick="printDiv('printableArea')" >
			<i class="fa fa-print"></i>
			{{trans('core.print')}}
		</a>
	</div>

	<div id="printableArea" class="panel-body">
		<h4 class="text-center">	
			<b>{{trans('core.warehouse')}}:</b> {{$branch_name}}, <b>{{trans('core.product')}}: </b> {{$product_name}}
		</h4>

		<br>
	 
		<table class="table table-bordered" width="100%">	
			<thead class="table-header-color">
				<th class="text-center">{{trans('core.product_name')}}</th>
				<th class="text-center">{{trans('core.quantity')}}</th>
			</thead>

			@foreach($stock as $items)
				<tr>
					@foreach($items as $item)
						<td class="text-center">
							{{$item}}								
						</td>
					@endforeach
				</tr>
			@endforeach	

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