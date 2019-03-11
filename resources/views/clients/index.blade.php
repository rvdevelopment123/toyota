@extends('app')

@section('contentheader')
	{{trans('core.customer_list')}}
@stop

@section('breadcrumb')
	{{trans('core.customer_list')}}
@stop

@section('main-content')
	<div class="panel-heading">
		@if(auth()->user()->can('customer.create'))
		<a  href="{{route('client.new')}}" class="btn btn-success btn-alt btn-xs" style="border-radius: 0px !important;" >
			<i class='fa fa-plus'></i> 
			{{trans('core.add_new_customer')}}
		</a>
		@endif

		@if(count(Request::input()))
			<span class="pull-right">	
	            <a class="btn btn-default btn-alt btn-xs font-black" href="{{ action('ClientController@getIndex') }}">
	            	<i class="fa fa-eraser"></i> 
	            	{{ trans('core.clear') }}
	            </a>

	            <a class="btn btn-primary btn-alt btn-xs" id="searchButton">
	            	<i class="fa fa-search"></i> 
	            	{{ trans('core.modify_search') }}
	            </a>
	        </span>
        @else
            <a class="btn btn-primary btn-alt btn-xs pull-right" id="searchButton">
				<i class="fa fa-search"></i>
				{{ trans('core.search') }}
			</a>
        @endif
	</div>

	<div class="panel-body">
		<div class="table-responsive" style="min-height: 150px;">
			<table class="table table-bordered table-striped">
				<thead class="{{settings('theme')}}">
					<td class="text-center font-white"># &nbsp;&nbsp;</td>
					<td class="text-center font-white">{{trans('core.name')}}</td>
					<td class="text-center font-white">{{trans('core.company_name')}}</td>
					<td class="text-center font-white">{{trans('core.total_due')}}</td>
					<td class="text-center font-white">{{trans('core.phone')}}</td>
					<td class="text-center font-white">{{trans('core.actions')}}</td>
				</thead>

				<tbody>
					@foreach($clients as $client)
						<tr>
							<td class="text-center">{{$loop->iteration}}</td>
							<td class="text-center">{{title_case($client->name)}}</td>
							<td class="text-center">{{title_case($client->company_name)}}</td>
							<td class="text-center">
								<?php 
									$due = ($client->transactions->sum('net_total') + $client->payments->where('type', 'return')->sum('amount')) - ($client->payments->where('type', 'credit')->sum('amount')) 
								?>
								@if($due == 0)
									-
								@else
									{{settings('currency_code')}} {{$due}}
								@endif
							</td>
							<td class="text-center">
								@if($client->phone)
									{{$client->phone}}
								@else
									<i class="fa fa-remove"></i>
								@endif
							</td>
							<td class="text-center">
								@if(auth()->user()->can('customer.manage'))
									<a href="{{route('client.edit', $client)}}" class="btn btn-info btn-alt btn-xs">
										<i class="fa fa-edit"></i>
										{{trans('core.edit')}}
									</a>

									@if($client->id != 1)
									<!-- delete trigger modal -->
									<a type="button" class="btn btn-danger btn-alt btn-xs" data-toggle="modal" data-target="#deleteModal{{$client->id}}">
										<i class="fa fa-trash"></i>
									  	{{trans('core.delete')}}
									</a>
									@endif

									<a  href="{{route('client.details', $client->id)}}" type="button" class="btn btn-purple btn-alt btn-xs">
										<i class="fa fa-eye"></i>
										{{trans('core.details')}}
									</a>	
								@endif		
							</td>
						</tr>

						<!-- modal for delete -->
						<div class="modal fade" id="deleteModal{{$client->id}}">
						  <div class="modal-dialog ">
						      {!! Form::open(['route' => ['client.delete', $client], 'method' => 'delete']) !!}
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">
						        	{{$client->name}}
						        </h4>
						      </div>
						      <div class="modal-body" >
						        <h4 >
						        	Are you sure to delete <b>{{$client->name}}</b>?
						        </h4>
						        <br>
						        @if(count($client->sells) == 0 && count($client->purchases) == 0)
						        @else
						        	<h4 style="color: red;">
						        	<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{$client->name}} has too much transactions, so it can't be deleted!</h4>
						        @endif
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
						        <button type="submit" class="btn btn-danger">{{trans('core.delete')}}</button>
						      </div>
						    </div>
						  </div><!-- /.modal-dialog -->
						  {!! Form::close() !!}
						</div>
						<!-- Delete modal Ends Here -->
					@endforeach
				</tbody>
			</table>
		</div>

		<!--Pagination-->
		<div class="pull-right">
			{{ $clients->links() }}
		</div>
		<!--Ends-->
	</div>


	<!-- Client search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.customer') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        {!! Form::label('Name', trans('core.name'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', Request::get('name'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Company Name', trans('core.company_name'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('company_name', Request::get('company_name'), ['class' => 'form-control']) !!}
                        </div>
                    </div>  

                    <div class="form-group">
                        {!! Form::label('Phone No', trans('core.phone'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('phone', Request::get('phone'), ['class' => 'form-control']) !!}
                        </div>
                    </div>  

                    <div class="form-group">
                        {!! Form::label('Address', trans('core.address'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('address', Request::get('address'), ['class' => 'form-control']) !!}
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