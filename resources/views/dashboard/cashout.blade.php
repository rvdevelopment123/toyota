@extends('app')

@section('contentheader')
	{{trans('core.total_given_today')}}
@stop

@section('breadcrumb')
	Total Given Today
@stop

@section('main-content')   
	<div class="panel-body">
		<table class="table table-bordered" >
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">{{trans('core.receipt')}}</td>
				<td class="text-center font-white">{{trans('core.name')}}</td>
				<td class="text-center font-white">{{trans('core.note')}}</td>
				<td class="text-center font-white">{{trans('core.method')}}</td>
				<td class="text-center font-white">{{trans('core.date')}}</td>
				<td class="text-center font-white">{{trans('core.amount')}}</td>
				<td class="text-center font-white">{{trans('core.print_receipt')}}</td>
			</thead>

			<tbody>
				@foreach($paid_rows as $paid_row)
					<tr>
						<td class="text-center">#{{ref($paid_row->id)}}</td>
						<td class="text-center">{{$paid_row->client->name}}</td>
						<td class="text-center">{{$paid_row->note}}</td>
						<td class="text-center">
							<span class="btn btn-border btn-alt border-default font-default btn-xs">
								@if($paid_row->method == 'cash') <i class="fa fa-money"></i> @else <i class="fa fa-credit-card"></i> @endif
								{{title_case($paid_row->method)}} 
							</span>
						</td>
						<td class="text-center">{{carbonDate($paid_row->date, 'g i a')}}</td>
						<td class="text-center">{{bangla_digit($paid_row->amount)}} {{settings('currency_code')}}</td>
						<td class="text-center">
							<a href="{{route('payment.voucher', $paid_row)}}" class="btn btn-border btn-alt btn-warning btn-xs">
				        		<i class="fa fa-print"></i> 
				        		{{trans('core.print')}}
				        	</a>
						</td>
					</tr>
				@endforeach

				<tr style="background-color: #F3F8F1; font-weight: bold;">
					<td colspan="5" style="text-align: right;">{{trans('core.total')}}</td>
					<td colspan="2" style="text-align: left;">{{bangla_digit($total_paid_sum)}} {{settings('currency_code')}}</td>
				</tr>
			</tbody>

		</table>
	</div>

	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('transactions.today')}}">
	        <i class="fa fa-backward"></i> {{trans('core.back')}}
	    </a>
	</div>
@stop
