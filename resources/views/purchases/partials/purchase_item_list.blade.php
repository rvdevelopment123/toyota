<h3 style="text-align: center;">{{trans('core.purchase_items')}}</h3>
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


    @foreach($transaction->purchases as $purchases)
    <tr>
      <td class="text-center">{{$loop->iteration}}</td>
      <td class="text-center">{{$purchases->product->name}}</td>
      <td class="text-center">
        {{$purchases->quantity}} {{$purchases->product->unit}}
      </td>
      <td class="text-center">
        {{ settings('currency_code') }}
        {{ twoPlaceDecimal($purchases->sub_total / $purchases->quantity) }}
      </td>
      
      @if(settings('product_tax'))
      <td class="text-center">
        {{ settings('currency_code') }}
        {{ twoPlaceDecimal($purchases->product_tax)}}
      </td>
      @endif
     
      <td class="text-center">
        {{settings('currency_code')}}
        {{twoPlaceDecimal($purchases->sub_total + $purchases->product_tax)}} 
      </td>
    </tr>
    @endforeach


    <!--Table footer section for total-->
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>{{trans('core.total_quantity')}} :</b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif>
        {{$transaction->purchases->sum('quantity')}} 
        @if(!rtlLocale ()) {{trans('core.item')}} @endif
      </td>
    </tr>

    @if($transaction->invoice_tax > 0)
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>
          {{trans('core.total')}} :
        </b>
      </td>
      <td @if(rtlLocale ()) class="text-right" @endif>
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->total + $transaction->discount + $transaction->total_tax - $transaction->invoice_tax)}}
      </td>
    </tr>
    @endif

    @if($transaction->discount)
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}">
        <b>{{trans('core.discount')}} :</b>
      </td>
      <td @if(rtlLocale ()) class=" bold text-right" @endif> 
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->discount)}}
      </td>
    </tr>  
    @endif

    <!-- invoice tax -->
    @if(settings('invoice_tax'))
    <tr>
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>
          {{trans('core.invoice_tax')}} 
          ({{orderTax()}} of {{$transaction->total + $transaction->total_tax - $transaction->invoice_tax}}) :
        </b>
      </td>
      <td @if(rtlLocale ()) class="text-right" @endif>
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->invoice_tax)}}
      </td>
    </tr>
    @endif
    <!-- Ends -->

    <tr style="background-color: #F8FCD4;">
      <td @if(!rtlLocale ()) class="text-right" @endif colspan="{{sellDetailsColSpanNumber()}}" >
        <b>{{trans('core.net_total')}} :</b>
      </td>
      <td @if(rtlLocale ()) class="text-right" @endif>
        {{settings('currency_code')}}
        {{twoPlaceDecimal($transaction->net_total)}}
      </td>
    </tr>
  </table>
</div>