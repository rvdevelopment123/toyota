@extends('app')

@section('contentheader')
	{{trans('core.return_sale')}}
@stop

@section('breadcrumb')
	<a href="{{route('sell.index')}}">{{trans('core.sell_list')}}</a>
	 &nbsp;>&nbsp;
	{{trans('core.return_sale')}}
@stop


@section('main-content')

	<div class="row">
		<div class="col-md-offset-2">
			@if($errors->any)
			<ul>
				@foreach($errors->all() as $error)
					<li style="color:red;">{{$error}}</li>
				@endforeach
			</ul>
			@endif
		</div>
	</div>

	<div class="panel-body">
		<div class="alert alert-warning animated headShake" style="background-color: rgba(255, 222, 160, 0.25); margin-bottom: 10px;">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
			@if($transaction->total - $transaction->paid <= 0)
				Payment of this sale is fully completed.
			@else
				Payment of this sale is not completed. 
				</br>
				<b>Paid:</b> 
				{{settings('currency_code')}} {{$transaction->paid}} 
				</br>
				<b>Due:</b> 
				{{settings('currency_code')}} {{$transaction->net_total - $transaction->paid}}
			@endif
		</div>
		<table class="table table-bordered">
			<thead style="background-color: #ddd;">
				<th class="text-center">{{trans('core.product_name')}}</th>
				<th class="text-center">{{trans('core.quantity')}}</th>
				<th class="text-center">{{trans('core.return_quantity')}}</th>
				<th class="text-center">{{trans('core.unit_price')}}</th>
			</thead>

			<tbody style="background-color: #fff;">
			<form method="post" >
					{{csrf_field()}}
					@foreach($transaction->sells as $sell)
						<tr>
							<td class="text-center" width="30%">
								<p>{{$sell->product->name}}</p>
							</td>

							<td class="text-center" width="30%">
								<p>{{$sell->quantity}}</p>
							</td>

							<input type="hidden" name="sell_{{$sell->id}}" value="{{$sell->id}}">
							<input type="hidden" name="transaction_id" value="{{$transaction->id}}">

							<td width="15%" class="text-center">
								<input type="text" name="quantity_{{$sell->id}}" class="form-control" value="0"  style="text-align:center;" onkeypress='return event.charCode <= 57 && event.charCode != 32 && event.charCode != 46' @if($sell->quantity == 0) disabled="true" @endif >
							</td>

							
							<td width="25%" class="text-center">
								@if($sell->quantity != 0)
									<p>
										{{ settings('currency_code') }}
										{{$sell->sub_total / $sell->quantity}} 
									</p>
									<input type="hidden" name="unit_price_{{$sell->id}}" value="{{$sell->sub_total / $sell->quantity}}">
								@else
									<p>
										{{$sell->product->mrp}}
										{{ settings('currency_code') }} 
									</p>
								@endif
							</td>							
						</tr>
					@endforeach
				</tbody>

				<tfoot>	
					<td colspan="4">
						<input type="submit" class="btn btn-warning pull-right" value="Return">
					</td>
				</tfoot>
			</form>		
		</table>
	</div>
@stop

@section('js')
	@parent
	<script>
		
	</script>

@stop