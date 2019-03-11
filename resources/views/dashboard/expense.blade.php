@extends('app')

@section('contentheader')
	{{trans('core.todays_expense')}}
@stop

@section('breadcrumb')
	{{trans('core.todays_expense')}}
@stop


@section('main-content')
	<div class="panel-body">
		<table class="table table-bordered" >
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">{{trans('core.purpose')}}</td>
                <td class="text-center font-white">{{trans('core.amount')}}</td>
			</thead>

			<tbody style="background-color: #fff;" id="myTable">
				@foreach($expense_rows as $expense_row)
					<tr>
                        <td class="text-center">{{$expense_row->purpose}}</td>
                        <td class="text-center">{{bangla_digit($expense_row->amount)}}</td>
                    </tr>
				@endforeach

				<tr>
					<td  style="text-align: right;">Total</td>
					<td  style="text-align: center;">{{bangla_digit($total_expense_sum)}} {{settings('currency_code')}}</td>
				</tr>
				
			</tbody>

		</table>	
	</div>

	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('expense.index')}}">
	        <i class="fa fa-backward"></i> {{trans('core.back')}}
	    </a>
	</div>
@stop

@section('js')
	@parent
	<script>
		
	</script>

@stop