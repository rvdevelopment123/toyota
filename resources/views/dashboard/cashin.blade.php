@extends('app')

@section('contentheader')
	{{trans('core.total_received_today')}}
@stop

@section('breadcrumb')
	Total Received Today
@stop

@section('main-content')
	<div class="panel-body">
		<table class="table table-bordered">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">{{trans('core.receipt_no')}}</td>
				<td class="text-center font-white">{{trans('core.name')}}</td>
				<td class="text-center font-white">{{trans('core.note')}}</td>
				<td class="text-center font-white">{{trans('core.method')}}</td>
				<td class="text-center font-white">{{trans('core.date')}}</td>
				<td class="text-center font-white">{{trans('core.amount')}}</td>
				<td class="text-center font-white">{{trans('core.print_receipt')}}</td>
			</thead>

			<tbody>
				@foreach($received_cash as $received_cash)
					<tr>
						<td class="text-center">#{{ref($received_cash->id)}}</td>
						<td class="text-center">{{$received_cash->client->name}}</td>
						<td class="text-center">{{$received_cash->note}}</td>
						<td class="text-center">
							<span class="btn btn-border btn-alt border-default font-default btn-xs">
								@if($received_cash->method == 'cash') <i class="fa fa-money"></i> @else <i class="fa fa-credit-card"></i> @endif
								{{title_case($received_cash->method)}}
							</span>
						</td>
						<td class="text-center">{{carbonDate($received_cash->created_at, 'g i a')}}</td>
						<td class="text-center">{{bangla_digit($received_cash->amount)}} {{settings('currency_code')}}</td>
						<td class="text-center">
							<a target="_BLINK" href="{{route('payment.voucher', $received_cash)}}" class="btn btn-alt btn-warning btn-xs">
				        		<i class="fa fa-print"></i> 
				        		{{trans('core.print')}}
				        	</a>
						</td>
					</tr>
				@endforeach

				<tr style="background-color: #F3F8F1; font-weight: bold;">
					<td colspan="5" style="text-align: right;">
						{{trans('core.total')}}
					</td>
					<td colspan="2" style="text-align: left;">
						{{bangla_digit($total_received_cash)}} 
						{{settings('currency_code')}}
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('transactions.today')}}">
	        <i class="fa fa-backward"></i> 
	        {{trans('core.back')}}
	    </a>
	</div>
@stop