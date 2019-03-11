<h3 style="text-align: center;">{{trans('core.payment_history')}}</h3>
  <div class="table-responsive">
    <table class="table table-bordered ">
      <tr class="table-header-color">
        <th class="text-center">{{trans('core.date')}}</th>
        <th class="text-center">{{trans('core.method')}}</th>
        <th class="text-center">{{trans('core.note')}}</th>
        <th class="text-center">{{trans('core.amount')}}</th>
        <th class="text-center">{{trans('core.print')}}</th>
      </tr>

      @foreach($payments as $payment)
        <tr>
          <td class="text-center">{{carbonDate($payment->created_at, 'g:i:a')}}</td>
          <td class="text-center">{{title_case($payment->method)}}</td>
          <td class="text-center">{{$payment->note}}</td>
          <td class="text-center">
             {{settings('currency_code')}}
             {{twoPlaceDecimal($payment->amount)}}
          </td>
          <td class="text-center">
            <a target="_BLINK" href="{{route('payment.voucher', $payment)}}" class="btn btn-border btn-alt border-orange btn-link font-orange btn-xs">
              <i class="fa fa-print"></i> 
              {{trans('core.print')}}
            </a>
          </td>
        </tr>
      @endforeach
      <tr style="background-color: #F8FCD4;" class="text-center">
        <td colspan="3" @if(!rtlLocale ()) class="text-right" @else class="text-left" @endif">
          <b>{{trans('core.total')}} :</b>
        </td>
        <td colspan="2" @if(!rtlLocale ()) class="text-left bold" @else class="text-right bold" @endif"> 
          {{settings('currency_code')}}
          {{twoPlaceDecimal($payments->sum('amount'))}}
        </td>
      </tr>
    </table>
  </div>