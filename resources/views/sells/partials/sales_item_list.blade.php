<h2 style="text-align: center;">
  {{trans('core.sale_items')}}
</h2>

<div class="table-responsive">
  <table class="table table-bordered ">
    <tr class="table-header-color">
      <th class="text-center">#</th>
      <th class="text-center">{{trans('core.product')}}</th>
      <th class="text-center">{{trans('core.quantity')}}</th>
      <th class="text-center">{{ trans('core.unit_price') }}</th>
      @if(settings('product_tax') == 1)
      <th class="text-center">
        {{ trans('core.product_tax') }}
      </th>
      @endif
      <th class="text-center">{{trans('core.sub_total')}}</th>
    </tr>
    
    @foreach($transaction->sells as $sells)
    <tr>
      <td class="text-center">{{$loop->iteration}}</td>
      
      <td class="text-center">
          {{$sells->product->name}}
      </td>
      
      <td class="text-center">{{$sells->quantity}}</td>
      
      <td class="text-center">
        @if($sells->quantity > 0)
          {{ settings('currency_code') }}
          {{ twoPlaceDecimal($sells->sub_total / $sells->quantity) }}
        @else
          0
        @endif
      </td>

      @if(settings('product_tax') == 1)
      <td class="text-center">
        {{ settings('currency_code') }}
        {{ twoPlaceDecimal($sells->product_tax)}}
      </td>
      @endif
      
      <td class="text-center">
        {{settings('currency_code')}}
        {{twoPlaceDecimal($sells->sub_total + $sells->product_tax)}}
      </td>
    </tr>
    @endforeach


    <!--Table footer for total-->
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}">
        <b>{{trans('core.total_quantity')}} :</b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif>
        {{$transaction->sells->sum('quantity')}} 
        @if(!rtlLocale ()) {{trans('core.item')}} @endif
      </td>
    </tr>

    @if($transaction->invoice_tax > 0)
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif  colspan="{{sellDetailsColSpanNumber()}}" >
        <b>
          {{trans('core.total')}} :
        </b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif>
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->total + $transaction->discount + $transaction->total_tax - $transaction->invoice_tax)}}
      </td>
    </tr>
    @endif

    @if($transaction->discount)
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>{{trans('core.discount')}} :</b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif> 
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->discount)}}
      </td>
    </tr>  
    @endif

    <!-- Invoice Tax Section -->
    @if(settings('invoice_tax'))
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>
            {{trans('core.invoice_tax')}}
            ({{orderTax()}} @if($transaction->return != 1) of {{$transaction->total + $transaction->total_tax - $transaction->invoice_tax}} @endif ):
        </b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif>
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->invoice_tax)}}
      </td>
    </tr>
    @endif
    <!--ends-->

    @if($transaction->labor_cost > 0)
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif  colspan="{{sellDetailsColSpanNumber()}}" >
        <b>{{trans('core.labor_cost')}} :</b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif> 
        {{settings('currency_code')}} 
        {{twoPlaceDecimal($transaction->labor_cost)}}
      </td>
    </tr>
    @endif

    <tr style="background-color: #F8FCD4;">
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>{{trans('core.net_total')}} :</b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif>
        {{settings('currency_code')}} 
        {{twoPlaceDecimal($transaction->net_total)}}
      </td>
    </tr>
  </table>
</div>