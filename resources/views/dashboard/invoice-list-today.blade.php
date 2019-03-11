@extends('app')

@section('contentheader')
	{{trans('core.invoice_list')}} ({{trans('core.today')}})
@stop

@section('breadcrumb')
	{{trans('core.invoice_list')}}
@stop

@section('main-content')
    <div class="panel-heading" >
        <span style="padding: 10px;">
        
        </span>

        @if(count(Request::input()))
        <span class="pull-right">   
            <a class="btn btn-alt btn-default btn-xs" href="{{ action('HomeController@todayInvoice') }}">
                <i class="fa fa-eraser"></i> 
                {{ trans('core.clear') }}
            </a>

            <a class="btn btn-alt btn-primary btn-xs" id="searchButton">
                <i class="fa fa-search"></i> 
                {{ trans('core.modify_search') }}
            </a>
        </span>
        @else
            <a class="btn btn-alt btn-primary btn-xs pull-right" id="searchButton" style="border-radius: 0px !important;" >
                <i class="fa fa-search"></i>
                {{ trans('core.search') }}
            </a>
        @endif
    </div>

	<div class="panel-body">
		<table class="table table-bordered table-striped">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">{{trans('core.date')}}</td>
				<td class="text-center font-white">{{trans('core.invoice_no')}}</td>
                <td class="text-center font-white">{{trans('core.client')}}</td>
                <td class="text-center font-white">{{trans('core.total_amount')}}</td>
                <td class="text-center font-white">{{trans('core.paid')}}</td>
                <td class="text-center font-white">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($invoices as $invoice)
					<tr>
						<td class="text-center">
                            {{ carbonDate($invoice->created_at, 'time') }}
                        </td>

                        <td class="text-center">#{{$invoice->reference_no}}</td>

                        <td class="text-center">
                        	<a 
                                href="{{route('client.details', $invoice->client)}}" 
                                title="{{trans('core.client_details')}}"
                                style="color: green; text-decoration: underline;" 
                            >
                        		{{$invoice->client->name}}
                        	</a>
                        </td>
                        
                        <td class="text-center">
                        	{{settings('currency_code')}} 
                        	{{bangla_digit($invoice->net_total)}} 
                        </td>

                        <td class="text-center">
                            {{settings('currency_code')}}
                        	{{bangla_digit($invoice->paid)}}
                        </td>
                        
                        <td class="text-center">   
                        	<a target="_BLINK" href="{{route('sell.invoice', $invoice)}}" class="btn btn-alt btn-warning btn-xs">
                        		<i class="fa fa-print"></i>
                        		{{trans('core.invoice')}}
                        	</a>
                        	<a href="{{route('sells.details', $invoice)}}" class="btn btn-alt btn-purple btn-xs">
                        		{{trans('core.details')}}
                        	</a>		                           
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

    	<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('home')}}">
            <i class="fa fa-backward"></i> {{trans('core.back')}}
        </a>
    </div>

    <!-- Sell search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.sell') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        {!! Form::label('Invoice No', trans('core.invoice_no'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('invoice_no', Request::get('invoice_no'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Customer', trans('core.customer'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('customer', $customers, Request::get('customer'), ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => 'Please select a customer']) !!}
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