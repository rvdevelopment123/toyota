@extends('app')

@section('contentheader')
	Cash Status Today
@stop

@section('breadcrumb')
	Cash Status Today
@stop

@section('main-content')
	<div class="panel-body">
		<table class="table table-bordered" >
			<tbody style="background-color: #fff;" id="myTable">
				<tr>
					<td>{{trans('core.cash_in_hands')}}</td>
					<td>{{settings('currency_code')}} {{$cash_in_hands}}</td>
				</tr>
				<tr>
					<td>{{trans('core.cash_received_today')}} <b>(+)</b></td>
					<td>{{settings('currency_code')}} {{$total_received}}</td>
				</tr>
				<tr>
					<td>{{trans('core.cash_given_today')}} <b>(-)</b></td>
					<td>{{settings('currency_code')}} {{$total_paid}}</td>
				</tr>
				<tr >
					<td>{{trans('core.todays_expense')}} <b>(-)</b></td>
					<td>{{settings('currency_code')}} {{$total_expense}}</td>
				</tr>
				<tr style="background-color: #F3F8F1; font-weight: bold;">
					<td>{{trans('core.now_in_cash')}}</td>
					<td>{{settings('currency_code')}} {{$remaining_cash}}</td>
				</tr>
			</tbody>
		</table>
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
		
	</script>

@stop