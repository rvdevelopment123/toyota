
<h3 class="text-center">{{ trans('core.transaction_details') }}</h3>

<div class="box-body" >
  <table class="table table-bordered">
    @if($client->provious_due > 0)
    <tr>
      <td class="text-center"> 
        <b>{{trans('core.previous_due')}}</b>             
      </td>

      <td class="text-center" style="width: 40%;">
        {{settings('currency_code')}}
        {{bangla_digit($client->provious_due)}}
      </td>
    </tr>
    @endif

    <tr>
      <td class="text-center">
          @if($client->client_type == 'purchaser')
            <b>{{trans('core.purchasing_goods_total_price')}}</b>
          @else
            <b>
              {{trans('core.selling_goods_total_price')}} (+)
            </b>
          @endif         
      </td>

      <td class="text-center" style="width: 40%;">
        {{settings('currency_code')}}
        {{bangla_digit($net_total)}}
      </td>
    </tr>

    @if($client->client_type != 'purchaser' && $total_return > 0)
      <tr>
        <td class="text-center">
            <b>{{trans('core.total_return')}} (-)</b>        
        </td>
        <td class="text-center">
          {{settings('currency_code')}}
          {{bangla_digit($total_return)}}
        </td>
      </tr>
    @endif


    <tr>
      <td class="text-center">
        @if($client->client_type == 'purchaser')
          <b>{{trans('core.total_given')}}</b>
        @else
          <b>{{trans('core.total_received')}} (-)</b>
        @endif         
      </td>
      <td class="text-center">
        {{settings('currency_code')}}
        {{bangla_digit($total_received)}}
      </td>
    </tr>

    <tr >
      <td class="text-center"> 
        @if($client->client_type == 'purchaser')
      	   <b>{{trans('core.current_get')}}</b>
        @else
          <b>{{trans('core.current_due')}}</b>
        @endif
      </td>
      <td class="text-center">
        {{settings('currency_code')}}
      	{{bangla_digit($total_due)}}
      </td>
    </tr>
  </table>
</div> <!-- box body ends -->