@extends('app')

@section('contentheader')
	{{trans('core.supplier_list')}}
@stop

@section('breadcrumb')
	{{trans('core.supplier_list')}}
@stop

@section('main-content')
	<div class="panel-heading">
		@if(auth()->user()->can('supplier.create'))
			<a href="{{route('purchaser.new')}}" class="btn btn-success btn-alt btn-xs" >
				<i class='fa fa-plus'></i> 
				{{trans('core.add_new_supplier')}}
			</a>
		@endif

		@if(count(Request::input()))
			<span class="pull-right">	
	            <a class="btn btn-default btn-alt btn-xs" href="{{ action('PurchaserController@getIndex') }}">
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
			<table class="table table-bordered">
				<thead class="{{settings('theme')}}">
					<td class="text-center font-white"># &nbsp;&nbsp;</td>
					<td class="text-center font-white">{{trans('core.name')}}</td>
					<td class="text-center font-white">{{trans('core.company_name')}}</td>
					<td class="text-center font-white">{{trans('core.phone')}}</td>
					<td class="text-center font-white">{{trans('core.address')}}</td>
					<td class="text-center font-white">{{trans('core.actions')}}</td>
				</thead>

				<tbody>
					@foreach($purchasers as $purchaser)
						<tr>
							<td class="text-center">{{$loop->iteration}}</td>
							<td class="text-center">{{title_case($purchaser->name)}}</td>
							<td class="text-center">{{title_case($purchaser->company_name)}}</td>
							
							<td class="text-center">
								@if($purchaser->phone)
									{{$purchaser->phone}}
								@else
									<i class="fa fa-remove"></i>
								@endif								
							</td>

							<td class="text-center">
								@if($purchaser->address)
									{{$purchaser->address}}
								@else
									<i class="fa fa-remove"></i>
								@endif							
							</td>

							<td class="text-center">
								@if(auth()->user()->can('supplier.manage'))
								<a href="{{route('client.edit', $purchaser)}}" class="btn btn-info btn-alt btn-xs" >
									<i class="fa fa-edit"></i>
									{{trans('core.edit')}}
								</a>

								@if($purchaser->id != 2)
								<!-- delete trigger modal -->
								<a type="button" data-toggle="modal" data-target="#deleteModal{{$purchaser->id}}" class="btn btn-danger btn-alt btn-xs" >
								  <i class="fa fa-trash"></i>
								  {{trans('core.delete')}}
								</a>
								@endif

								<!-- Client Details modal -->
								<a type="button" href="{{route('client.details', $purchaser->id)}}" class="btn btn-purple btn-alt btn-xs">
									<i class="fa fa-eye"></i>
									{{trans('core.details')}}
								</a>
								@endif			
							</td>
						</tr>

						<!-- modal for delete -->
						<div class="modal fade" id="deleteModal{{$purchaser->id}}">
						  <div class="modal-dialog ">
						      {!! Form::open(['route' => ['client.delete', $purchaser], 'method' => 'delete']) !!}
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						        <h4 class="modal-title" id="myModalLabel">
						        	{{$purchaser->name}}
						        </h4>
						      </div>
						      <div class="modal-body" style="color:black;">
						        <h4 >
						        	Are you sure to delete <b>{{$purchaser->name}}</b>?
						        </h4>
						        <br>
						        @if(count($purchaser->sells) == 0 && count($purchaser->purchases) == 0)
						        @else
						        	<h4 style="color: red;">
						        	<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{$purchaser->name}} has too much transactions, so it can't be deleted!</h4>
						        @endif
						      </div>
						     <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
						        <button type="submit" class="btn btn-danger">{{trans('core.delete')}}</button>
						      </div>
						    </div><!-- /.modal-content -->
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
			{{ $purchasers->links() }}
		</div>
		<!--Ends-->
	</div>


	<!-- Purchaser search modal -->
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
