@extends('app')

@section('contentheader')
	{{trans('core.bill_list')}} ({{trans('core.today')}})
@stop

@section('breadcrumb')
	{{trans('core.bill_list')}}
@stop

@section('main-content')
	<div class="panel-heading" >
        <span style="padding: 10px;">
        
        </span>

        @if(count(Request::input()))
        <span class="pull-right">   
            <a class="btn btn-alt btn-default btn-xs" href="{{ action('HomeController@todaysBill') }}">
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
				<td class="text-center font-white">{{trans('core.time')}}</td>
				<td class="text-center font-white">{{trans('core.bill_no')}}</td>
	            <td class="text-center font-white">{{trans('core.supplier')}}</td>
	            <td class="text-center font-white">{{trans('core.net_total')}}</td>
	            <td class="text-center font-white">{{trans('core.paid')}}</td>
	            <td class="text-center font-white">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($bills as $bill)
					<tr>
						<td class="text-center">
                            {{ carbonDate($bill->created_at, 'time') }}
                        </td>
	                    <td class="text-center">#{{$bill->reference_no}}</td>
	                    <td class="text-center">
	                    	<a 
                                href="{{route('client.details', $bill->client)}}" 
                                title="{{trans('core.client_details')}}"
                                style="color: green; text-decoration: underline;" 
                            >
	                    		{{$bill->client->name}}
	                    	</a>
	                    </td>
	                    
	                    <td class="text-center">
	                    	{{settings('currency_code')}} 
	                    	{{bangla_digit($bill->net_total)}} 
	                    </td>

	                    <td class="text-center">
                            {{settings('currency_code')}}
	                    	{{bangla_digit($bill->paid)}}
	                    </td>

	                    <td class="text-center">   
	                    	<a target="_BLINK" href="{{route('purchase.invoice', $bill)}}" class="btn btn-warning btn-alt btn-xs">
	                    		<i class="fa fa-print"></i>
	                    		{{trans('core.bill')}}
	                    	</a>
	                    	<a href="{{route('purchase.details', $bill)}}" class="btn btn-alt btn-purple btn-xs">
	                    		{{trans('core.details')}}
	                    	</a>		                           
	                    </td>
	                </tr>
				@endforeach
			</tbody>
		</table>
		<!--Pagination-->
        <div class="pull-right">
            {{ $bills->links() }}
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

    <!-- Today Bills search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.purchase') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        {!! Form::label('Bill No', trans('core.bill_no'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('bill_no', Request::get('bill_no'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Supplier', trans('core.supplier'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::select('supplier', $suppliers, Request::get('supplier'), ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => 'Please select a supplier']) !!}
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