<h3 style="text-align: center;">{{trans('core.return_items')}}</h3>

@if($transaction->returnSales->count() != 0)
  <h5 style="text-align: left;">
    <b>{{trans('core.biller')}}:</b> 
    {{$transaction->returnSales->first()->user->name}}
    <br>
    <b>{{trans('core.date')}}:</b> 
    {{carbonDate($transaction->returnSales->first()->created_at, 'g:i:a')}}
  </h5>
@endif

<table class="table table-bordered ">
  <thead class="table-header-color">
    <th class="text-center">#</th>
    <th class="text-center">{{trans('core.product')}}</th>
    <th class="text-center">{{trans('core.quantity')}}</th>
    <th class="text-center">{{trans('core.total')}}</th>
    <th class="text-center">{{trans('core.vat')}}</th>
    <th class="text-center">{{trans('core.sub_total')}}</th>
  </thead>

  @foreach($transaction->returnSales as $return)
    <tr>
      <td class="text-center">{{$loop->iteration}}</td>
      <td class="text-center">{{$return->sells->product->name}}</td>
      <td class="text-center">{{$return->return_units}}</td>
      <td class="text-center">{{($return->return_amount - $return->return_vat)}}</td>
      <td class="text-center">{{$return->return_vat}}</td>
      <td class="text-center">
        {{settings('currency_code')}}
        {{$return->return_amount}}
      </td>
    </tr>
  @endforeach
</table>