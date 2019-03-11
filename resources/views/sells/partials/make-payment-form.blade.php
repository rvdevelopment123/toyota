@if($transaction->net_total - $transaction->paid > 0)
    <h3 style="text-align: center;">{{trans('core.make_payment')}}</h3>
    <form method="post" name="myForm" onsubmit="return validateForm()" action="{{route('payment.post')}}">

      {{csrf_field()}}
      <div class="form-group">
         <input type="hidden" name="client_id" value="{{$transaction->client_id}}">
         <input type="hidden" name="type" value="credit">
         <input type="hidden" name="reference_no" value="{{$transaction->reference_no}}">
         <input type="hidden" name="invoice_payment" value="1">

         <label>{{trans('core.amount')}}</label>
         <input type="text" class="form-control number" name="amount" required>
         <p id="message" style="color: red;"></p>
         
         <label>{{trans('core.payment_method')}}</label>
         <select class="form-control" name="method">
            <option value="cash">{{trans('core.cash')}}</option>
            <option value="cheque">{{trans('core.cheque')}}</option>
            <option value="others">{{trans('core.others')}}</option>
         </select>

         <label>{{trans('core.note')}}</label>
         <textarea name="note" class="form-control"></textarea>
         
      </div>

      <button type="submit" class="btn btn-success">
        {{trans('core.submit')}}
      </button>
    </form>
@else
  <h1 class="no-due">No Due</h1>
@endif