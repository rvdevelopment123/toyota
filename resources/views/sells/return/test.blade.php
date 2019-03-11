@extends('app')

@section('title')
	{{trans('core.return')}}
@stop

@section('contentheader')
	{{trans('core.return')}}
@stop

@section('breadcrumb')
	{{trans('core.return')}}
@stop


@section('main-content')
    
    @if(Session::has('message'))
        <?php $message = Session::get('message'); ?>
    @else
        <?php $message = "";  ?>
    @endif	

    <div class="well" style="background-color: rgba(255, 222, 160, 0.25);">
		@if($transaction->total - $transaction->paid <= 0)
			Payment of this sale is fully completed.
		@else
			Payment of this sale is not completed. 
			<b>Paid: {{$transaction->paid}} {{settings('currency_code')}}</b>
			&amp;
			<b>Due: {{$transaction->total - $transaction->paid}} {{settings('currency_code')}}</b>
		@endif
	</div>

    <div class="row"> 
		<div class="col-md-12">
			<table class="table table-bordered" >
				<thead style="background-color: #ddd;">
					<th class="text-center">Product Name</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Unit Price</th>
					<th class="text-center">
						<span >
							<i class="fa fa-trash"></i>
						</span>
					</th>
				</thead>

				<tbody style="background-color: #fff;" id="dsTable">
					<form method="post" action="{{route('return.post',$transaction)}}">
						{{csrf_field()}}
						@foreach($transaction->sells as $sell)
							<tr>
								<td width="30%">
									<p>{{$sell->product->name}}</p>
								</td>

								<input type="hidden" name="sell_id{{$sell->id}}" value="{{$sell->id}}">
								<input type="hidden" name="ref_no" value="{{$transaction->id}}">

								<td width="30%">
									<input type="number" name="quantity{{$sell->id}}" class="form-control" value="{{$sell->quantity}}"  style="text-align:center;">
								</td>

								<td width="30%">
									<p>{{$sell->sub_total / $sell->quantity}} {{ settings('currency_code') }} </p>
									<input type="hidden" name="unit_price{{$sell->id}}" value="{{$sell->sub_total / $sell->quantity}}">
								</td>
      							<td>
      								<button type="button" class="btn btn-danger"  onclick="deleteRow(this)"/>
      									<i class="fa fa-times"></i>
      								</button> 
      							</td>
							</tr>
						@endforeach
					</tbody>
					<span id="demo">
						
					</span>
					<tfoot>	
						<td colspan="4">
							<input type="submit" class="btn btn-success pull-right" value="Update">
						</td>	
								
					</tfoot>
				</form>
				<button onclick="myFunction23()">Try it</button>
			</table>
		</div>
	</div>
@stop

@section('js')
    @parent
    <script>
        function deleteRow(btn) {
			  var row = btn.parentNode.parentNode;
			  row.parentNode.removeChild(row);
			}

		function myFunction23() {
	    	var x = document.getElementById("dsTable").rows.length;
	    	document.getElementById("demo").innerHTML = "<input type='text' name='emon' value='"+x+"'>";
	    }
    </script>

@stop