<div class="box-body" >
	@if($total_due > 0)
      	<form method="post" name="myForm" onsubmit="return validateForm()" action="{{route('payment.post')}}">

      		{{csrf_field()}}
      		<div class="form-group">
			   <label>
			   		{{trans('core.amount')}}
			   </label>
			   <input type="hidden" name="client_id" value="{{$client->id}}">

			   <input type="text" class="form-control" name="amount" required>
			   <p id="message" style="color: red;"></p>
			   
			   <label>{{trans('core.payment_method')}}</label>
			   <select class="form-control" name="method">
			   		<option value="cash">{{trans('core.cash')}}</option>
			   		<option value="cheque">{{trans('core.cheque')}}</option>
			   		<option value="others">{{trans('core.others')}}</option>
			   </select>

			   <label>{{trans('core.note')}}</label>
			   <textarea name="note" class="form-control"></textarea>

			   @if($client->client_type == 'purchaser')
			   		<input type="hidden" name="type" value="debit">
			   @else
			   		<input type="hidden" name="type" value="credit">
			   @endif
			</div>

			<button type="submit" class="btn btn-success">
				{{trans('core.submit')}}
			</button>
      	</form>
    @else	
	
		<h1 class="no-due">{{trans('core.no_due')}}</h1>
			     
    @endif
</div> <!-- box body -->
	