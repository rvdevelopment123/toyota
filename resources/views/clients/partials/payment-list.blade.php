<h3 class="text-center">{{trans('core.payment_history')}}</h3>
 
<div class="box-body" >
  <table class="table table-bordered" id="example">
    <tr class="table-header-color">
      <th class="text-center">{{trans('core.date')}}</th>
      <th class="text-center">{{trans('core.method')}}</th>
      <th class="text-center">{{trans('core.note')}}</th>
      <th class="text-center">
        @if($client->client_type == 'purchaser')
          {{trans('core.given_amount')}}
        @else
          {{trans('core.received_amount')}}
        @endif
      </th>
      <th class="text-center">{{trans('core.voucher')}}</th>
    </tr>

    <tbody id="myTable">
      @foreach($payment_lists as $payment)
      <tr>
        <td>{{carbonDate($payment->created_at, 'g:i:a')}}</td>
        <td>{{title_case($payment->method)}}</td>
        <td>{{$payment->note}}</td>
        <td>
          {{settings('currency_code')}}
          {{bangla_digit($payment->amount)}}
        </td>
        
        <td>
        	<a target="_BLINK" href="{{route('payment.voucher', $payment)}}" class="btn btn-alt btn-warning btn-xs">
        		<i class="fa fa-print"></i> 
        		{{trans('core.print')}}
        	</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  
  <a class="btn btn-default btn-xs pull-right" href="{{route('client.payment.list', $client)}}"> 
    {{trans('core.view_all')}} <i class="fa fa-arrow-circle-right"></i>
  </a>
</div> <!-- box body ends -->


