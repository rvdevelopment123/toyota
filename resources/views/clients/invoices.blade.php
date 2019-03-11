@extends('app')

@section('contentheader')
	@if($client->client_type == 'purchaser')
		{{trans('core.bill_list')}} - 
	@else
		{{trans('core.invoice_list')}} -
	@endif
	{{$client->name}}
@stop

@section('breadcrumb')
	{{trans('core.invoice_list')}}
@stop

@section('main-content')
    <div class="panel-heading" >
        
    </div>

	<div class="panel-body">
		<table class="table table-bordered table-striped">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">#</td>
				<td class="text-center font-white">
					@if($client->client_type == 'purchaser')
						{{trans('core.bill_no')}}
					@else
						{{trans('core.invoice_no')}}
					@endif
				</td>
	            <td class="text-center font-white">
	            	@if($client->client_type == 'purchaser')
						{{trans('core.supplier')}}
					@else
						{{trans('core.customer')}}
					@endif
	            </td>
	            <td class="text-center font-white">{{trans('core.total_amount')}}</td>
	            <td class="text-center font-white">{{trans('core.paid')}}</td>
	            <td class="text-center font-white">{{trans('core.date')}}</td>
	            <td class="text-center font-white">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($invoices as $invoice)
	                <tr>
	                	<td>{{$loop->iteration}}</td>
	                    <td>#{{$invoice->reference_no}}</td>
	                    <td>{{$invoice->client->name}}</td>
	                    <td>
	                    	{{settings('currency_code')}}
	                    	{{$invoice->net_total}}
	                    </td>
	                    <td>
	                    	{{settings('currency_code')}}
	                    	{{$invoice->paid}}
	                    </td>
	                    <td>
	                    	{{ carbonDate($invoice->created_at, 'y-m-d') }}
	                    </td>
	                    
	                    <td>
	                    	@if($client->client_type == 'purchaser')
	                            <a href="{{route('purchase.details', $invoice)}}" class="btn btn-alt btn-purple btn-xs">
	                            	{{trans('core.details')}}
	                            </a>
	                            <a target="_BLINK" href="{{route('purchase.invoice', $invoice)}}" class="btn btn-alt btn-warning btn-xs">
	                             	<i class="fa fa-print"></i>
	                             	{{trans('core.bill')}}
	                            </a>
	                        @else
	                        	<a href="{{route('sells.details', $invoice)}}" class="btn btn-alt btn-purple btn-xs">
	                        		{{trans('core.details')}}
	                        	</a>
	                            <a target="_BLINK" href="{{route('sell.invoice', $invoice)}}" class="btn btn-warning btn-alt btn-xs">
	                             	<i class="fa fa-print"></i>
	                             	{{trans('core.invoice')}}
	                            </a>
	                        @endif   
	                    </td>
	                </tr>
	            @endforeach
			</tbody>
		</table>
        <!--Pagination-->
        <div class="pull-right">
            {{ $invoices->links() }}
        </div>
        <!--Ends-->
	</div>
	
	<div class="panel-footer">
	    <span style="padding: 10px;">
	        
	    </span>
		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('client.details', $client)}}">
	        <i class="fa fa-backward"></i> 
	        {{trans('core.back')}}
	    </a> 
	</div>		
@stop

@section('js')
    @parent

@stop