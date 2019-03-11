@extends('app')

@section('contentheader')
	{{trans('core.total_transactions_today')}}
@stop

@section('breadcrumb')
	{{trans('core.total_transactions_today')}}
@stop

@section('main-content')
	<div class="panel-heading">
		<a href="{{route('cashin.today')}}" class="btn btn-border btn-alt border-green font-green btn-xs tooltip-button" title="Click here to sell all credited transaction of today">
			{{trans('core.credit')}}
		</a>
		<a href="{{route('cashout.today')}}" class="btn btn-border btn-alt border-red btn-link font-red btn-xs tooltip-button" title="Click here to sell all debited transaction of today">
			{{trans('core.debit')}}
		</a>
	</div>

	<div class="panel-body">
		<table class="table table-bordered">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">{{trans('core.time')}}</td>
				<td class="text-center font-white">{{trans('core.receipt_no')}}</td>
				<td class="text-center font-white">{{trans('core.name')}}</td>
				<td class="text-center font-white">{{trans('core.note')}}</td>
				<td class="text-center font-white">{{trans('core.method')}}</td>
				<td class="text-center font-white">{{trans('core.type')}}</td>
				<td class="text-center font-white">{{trans('core.amount')}}</td>
				<td class="text-center font-white">{{trans('core.print_receipt')}}</td>
			</thead>

			<tbody style="background-color: #fff;" id="myTable">
				@foreach($transaction_lists as $transaction_list)
					<tr>
						<td class="text-center">{{carbonDate($transaction_list->created_at, 'time')}}</td>
						<td class="text-center">#{{ref($transaction_list->id)}}</td>
						<td class="text-center">{{$transaction_list->client->name}}</td>
						<td class="text-center">{{$transaction_list->note}}</td>
						<td class="text-center">
							{{title_case($transaction_list->method)}}
						</td>
						<td class="text-center">
							@if($transaction_list->type != 'credit')
								<span class="font-red">{{trans('core.debit')}}</span>
							@else
								<span class="font-green">{{trans('core.credit')}}</span>
							@endif
						</td>
						<td class="text-center">
							{{bangla_digit($transaction_list->amount)}} 
							{{settings('currency_code')}}
						</td>
						<td class="text-center">
							<a target="_BLINK" href="{{route('payment.voucher', $transaction_list)}}" class="btn btn-alt btn-warning btn-xs">
				        		<i class="fa fa-print"></i> 
				        		{{trans('core.print')}}
				        	</a>
						</td>
					</tr>
				@endforeach

				<tr style="background-color: #F8FCD4;">
					<td colspan="6" style="text-align: right;">
						<b>{{trans('core.total')}}</b>
					</td>
					<td colspan="2" style="text-align: left;">
						<b>{{bangla_digit($transaction_amount)}} 
						{{settings('currency_code')}}</b>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('home')}}">
	        <i class="fa fa-backward"></i> {{trans('core.back')}}
	    </a>
	</div>
@stop