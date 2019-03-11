@extends('app')

@section('contentheader')
	{{trans('core.subcategory')}}
@stop

@section('breadcrumb')
	{{trans('core.subcategory_index')}}
@stop

@section('main-content')

	<div class="panel-heading">
		@if(auth()->user()->can('category.create'))
			<a href="{{route('subcategory.new')}}" class="btn btn-success btn-alt btn-xs" style="border-radius: 0px !important;">
				<i class='fa fa-plus'></i> 
				{{trans('core.add_new_subcategory')}}
			</a>
		@endif

		@if(count(Request::input()))
			<span class="pull-right">	
	            <a class="btn btn-alt btn-default font-black btn-xs" href="{{ action('SubcategoryController@getIndex') }}">
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
		<div class="table-responsive" style="min-height: 300px;">
			<table class="table table-bordered table-striped">
				<thead class="{{settings('theme')}}">
					<td class="text-center font-white">#</td>
					<td class="text-center font-white">{{trans('core.name')}}</td>
					<td class="text-center font-white">{{trans('core.category')}}</td>
					<td class="text-center font-white">{{trans('core.actions')}}</td>
				</thead>

				<tbody>
					@foreach($subcategories as $subcategory)
						<tr>
							<td class="text-center">{{$loop->iteration}}</td>
							<td class="text-center">{{$subcategory->name}}</td>
							<td class="text-center">{{$subcategory->category->category_name}}</td>
							<td class="text-center">
								@if(auth()->user()->can('category.manage'))
									<a href="{{route('subcategory.edit',$subcategory)}}" class="btn btn-info btn-alt btn-xs">
										<i class="fa fa-edit"></i>
										{{trans('core.edit')}}
									</a>
									<!-- Delete modal trigger -->
									<a type="button" class="btn btn-danger btn-alt btn-xs" data-toggle="modal" data-target="#deleteModal{{$subcategory->id}}">
										<i class="fa fa-trash"></i>
									  {{trans('core.delete')}}
									</a>
								@endif

								<a href="{{route('subcategory.products', $subcategory)}}" class="btn btn-purple btn-alt btn-xs">
									<i class="fa fa-cube"></i>
									{{trans('core.product')}}
								</a>
							</td>
						</tr>

						<!-- Modal -->
						<div class="modal fade" id="deleteModal{{$subcategory->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							{!! Form::open(['route' => ['subcategory.delete', $subcategory], 'method' => 'delete' ]) !!}
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">
						        	<span style="color:red;">
						        		{{$subcategory->name}} has total {{count($subcategory->products)}} product(s)
						        	</span>
						        </h4>
						      </div>
						      <div class="modal-body">
						        <h4>
						        	{{trans('core.delete_alert')}} <b>{{$subcategory->name}}</b> {{trans('core.subcategory')}}?
						        </h4>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">		{{trans('core.close')}}
						        </button>
						        <button type="submit" class="btn btn-danger">
						        	{{trans('core.delete')}}
						        </button>
						      </div>
						    </div>
						  </div>
						  {!! Form::close() !!}
						</div>
					@endforeach
				</tbody>
			</table>
		</div>

		<!--Pagination-->
		<div class="pull-right">
			{{ $subcategories->links() }}
		</div>
		<!--Ends-->
	</div>

	<!-- Subcategory search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.subcategory') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        {!! Form::label('Name', trans('core.name'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', Request::get('name'), ['class' => 'form-control']) !!}
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