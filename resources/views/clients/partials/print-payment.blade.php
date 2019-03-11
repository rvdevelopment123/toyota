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
                    <td></td>
					<td>{{trans('core.receipt_no')}}&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>
						@if($client->client_type == 'purchaser')
							{{trans('core.supplier')}}
						@else
							{{trans('core.customer')}}
						@endif
					</td>
					<td>{{trans('core.amount')}}</td>
					<td>{{trans('core.note')}}</td>
					<td>{{trans('core.date')}}</td>
                </thead>
	          
	          	<tbody>
                    @foreach($printable_payments as $payment)
					<tr>
						<td>{{$loop->iteration}}</td>
						<td>#{{ref($payment->id)}}</td>
						<td>{{$payment->client->name}}</td>
						<td>{{$payment->amount}}</td>
						<td>{{$payment->note}}</td>
						<td>{{carbonDate($payment->created_at, 'g i a')}}</td>
					</tr>
					@endforeach
                </tbody>
	        </table>

	        <table style="width: 50%; font-weight: bold;" align="right" class="table table-bordered" >
                <tr style="background-color: #F8F9F9;border: 1px solid #ddd; ">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        @if($client->client_type == 'purchaser')
				            <b>{{trans('core.purchasing_goods_total_price')}}</b>
				        @else
				            <b>
				              {{trans('core.selling_goods_total_price')}} (+)
				            </b>
				       	@endif  
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
        				{{bangla_digit($net_total)}}
                    </td>
                </tr>

                @if($client->client_type != 'purchaser' && $total_return > 0)
                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_return')}} (+)</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
          				{{bangla_digit($total_return)}}
                    </td>
                </tr>
                @endif

                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        @if($client->client_type == 'purchaser')
				          <b>{{trans('core.total_given')}}</b>
				        @else
				          <b>{{trans('core.total_received')}} (-)</b>
				        @endif   
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
        				{{bangla_digit($total_received)}}
                    </td>
                </tr>

                <tr style="background-color: #F8F9F9;border: 1px solid #ddd; ">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                       	@if($client->client_type == 'purchaser')
				      	   <b>{{trans('core.current_get')}}</b>
				        @else
				          <b>{{trans('core.current_due')}}</b>
				        @endif
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
      					{{bangla_digit($total_due)}}
                    </td>
                </tr>
	        </table> 
	      </div>
	      <!-- /.col -->
	    </div>
	    <!-- /.row -->
  	</section>
	
@stop