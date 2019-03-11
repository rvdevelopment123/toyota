@extends('app')

@section('title')
	{{trans('core.transaction_list')}}
@stop

@section('contentheader')
	{{trans('core.transaction_list')}} of {{$client->first_name}} {{$client->last_name}}
@stop

@section('breadcrumb')
	{{trans('core.transaction_list')}}
@stop


@section('main-content')
    <div class="panel-heading" >
		<?php
	        $url = \URL::full();
	        $searchParam = "?";
	        $parameterExists = strpos($url,$searchParam);
	    ?>
		    @if($parameterExists !== false)
		        <a target="_blank" href="{{ \URL::full() }}&print=true" class="btn btn-alt btn-warning btn-xs"><i class="fa fa-print"></i> {!! trans('core.print') !!}</a>
		    @else
		        <a target="_blank" href="{{ \URL::full() }}?print=true" class="btn btn-alt btn-warning btn-xs"><i class="fa fa-print"></i> {!! trans('core.print') !!}</a>
		@endif
        
        @if(count(Request::input()))
            <span class="pull-right">   
                <a 
                    class="btn btn-alt btn-default btn-xs" 
                    href="{{route('client.payment.list', $client)}}"
                >
                    <i class="fa fa-eraser"></i> 
                    {{ trans('core.clear') }}
                </a>

                <a class="btn btn-alt btn-primary btn-xs" id="searchButton">
                    <i class="fa fa-search"></i> 
                    {{ trans('core.modify_search') }}
                </a>
            </span>
        @else
            <a class="btn btn-alt btn-primary btn-xs pull-right" id="searchButton">
                <i class="fa fa-search"></i>
                {{ trans('core.search') }}
            </a>
        @endif
    </div>

    <div class="panel-body">
    	<div id="printableArea">
	        <div class="table-responsive" style="min-height: 300px;">
	        	<table class="table table-bordered table-striped">
	                <thead  class="{{settings('theme')}}">
	                    <td class="text-center font-white">#</td>
						<td class="text-center font-white">{{trans('core.receipt_no')}}&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class="text-center font-white">
							@if($client->client_type == 'purchaser')
								{{trans('core.supplier')}}
							@else
								{{trans('core.customer')}}
							@endif
						</td>
						<td class="text-center font-white">{{trans('core.amount')}}</td>
						<td class="text-center font-white">{{trans('core.note')}}</td>
						<td class="text-center font-white">{{trans('core.date')}}</td>
						<td class="text-center font-white">{{trans('core.print_receipt')}}</td>
	                </thead>

	                <tbody>
	                    @foreach($payments as $payment)
						<tr>
							<td class="text-center">{{$loop->iteration}}</td>
							<td class="text-center">#{{ref($payment->id)}}</td>
							<td class="text-center">{{$payment->client->name}}</td>
							<td class="text-center">{{$payment->amount}}</td>
							<td class="text-center">{{$payment->note}}</td>
							<td class="text-center">{{carbonDate($payment->created_at, 'g i a')}}</td>
							<td class="text-center">
								<a target="_BLINK" href="{{route('payment.voucher', $payment)}}" class="btn border-orange font-orange btn-alt btn-xs">
					        		<i class="fa fa-print"></i> 
					        		{{trans('core.print')}}
					        	</a>
							</td>
						</tr>

					@endforeach
	                </tbody>
	            </table>

	            <!--Pagination-->
	            <div class="pull-right">
	                {{ $payments->links() }}
	            </div>
	            <!--Ends-->

	            <table style="width: 40%; font-weight: bold;" align="right" class="table table-bordered" >
	                <tr style="background-color: #F8F9F9;border: 2px solid #ddd; ">
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

	                <tr style="background-color: #F8F9F9;border: 2px solid #ddd; ">
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
	    </div>
    </div>

    <div class="panel-footer">
      <span style="padding: 10px;">
        
      </span>

      <a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('client.details', $client)}}">
        <i class="fa fa-backward"></i> {{trans('core.back')}}
      </a>
  	</div>

    <!-- Sell search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal', 'method' => 'get']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.transaction') }}</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.from')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.to')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                        </div>
                    </div>
                                                             
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
                    {!! Form::submit('Search', ['class' => 'btn btn-primary', 'data-disable-with' => trans('core.searching')]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- search modal ends -->

@stop

@section('js')
    @parent
    <script>
        $(function() {
            $('#searchButton').click(function(event) {
                event.preventDefault();
                $('#searchModal').modal('show')
            });
        })
    </script>

@stop