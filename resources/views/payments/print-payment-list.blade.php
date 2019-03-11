@extends('printer')

<style>
	thead tr th{
		text-align: center;
		font-size: 14px !important;
	}

	tbody tr td{
		font-size: 13px !important;
	}
</style>

@section('main-content')
	<section class="invoice">
	    <div class="row">
	       <div class="col-sm-12 text-center" >
	         @if(!empty(settings('site_logo')))
              	<img src="{!! asset('uploads/site/'.settings('site_logo')) !!}" style="height: 84px; width: 190px;">
              @else
                <h4>{{settings('site_name')}}</h4>
                <p>
                	{{ trans('core.phone') }}:
		          	{{ bangla_digit(settings('phone'))}}
		        </p>
              @endif
	      </div>
	      <!-- /.col -->
	    </div> 
	    <!-- header row ends-->

	    <div class="row" >
	      <div class="col-sm-12 table-responsive" style="margin-left: 20px; ">
	      	<h5 class="text-center">	
				<b>{{trans('core.report_transaction')}}:</b>
			 	{{carbonDate($from, 'y-m-d')}} 
			 	<b>{{trans('core.to')}}</b> 
			 	{{carbonDate($to, 'y-m-d')}} 
			</h5>
			<br />
	        <table class="table table-bordered">
	          	<thead  class="table-header-color">
                    <td class="text-center">{{trans('core.date')}}</td>
    				<td class="text-center">{{trans('core.receipt_no')}}</td>
                    <td class="text-center">{{trans('core.invoice_no')}}</td>
    				<td class="text-center">{{trans('core.name')}}</td>
    				<td class="text-center">{{trans('core.amount')}}</td>
    				<td class="text-center">{{trans('core.note')}}</td>
                </thead>
	          
	          	<tbody>
                    @foreach($printable_payments as $payment)
					<tr>
						<td class="text-center tooltip-button" data-placement="bottom" title="{{ carbonDate($payment->created_at, 'g:i:a') }}">
                                {{carbonDate($payment->created_at, 'y-m-d')}}
                            </td>

    						<td class="text-center">#{{ref($payment->id)}}</td>
                            
                            <td class="text-center">{{$payment->reference_no}}</td>

    						
    						<td class="text-center">{{$payment->client->name}}</td>
    						
    						<td class="text-center">
                                {{settings('currency_code')}}
                                {{twoPlaceDecimal($payment->amount)}}
                            </td>
    						
    						<td class="text-center">
    							@if($payment->note)
    								{{$payment->note}}
    							@else
    								-
    							@endif
    						</td>
					</tr>
					@endforeach
                </tbody>
	        </table>

	        <table style="width: 40%; font-weight: bold;" align="right" class="table table-bordered" >
                <tr >
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_debit')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}} 
                        {{twoPlaceDecimal($total_debit)}}
                    </td>
                </tr>

                <tr >
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_credit')}}</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}} 
                        {{twoPlaceDecimal($total_credit)}}
                    </td>
                </tr>

                @if($total_return > 0)
                <tr >
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_return')}}</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}} 
                        {{twoPlaceDecimal($total_return)}}
                    </td>
                </tr>
                @endif
            </table> 
	      </div>
	      <!-- /.col -->
	    </div>
	    <!-- /.row -->
  	</section>
	
@stop