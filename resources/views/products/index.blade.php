@extends('app')

@section('title')
	{{trans('core.product_index')}}
@stop

@section('contentheader')
	{{trans('core.product_index')}}
@stop

@section('breadcrumb')
	{{trans('core.product_index')}}
@stop

@section('main-content')
	<div class="panel-heading">
		@if(auth()->user()->can('product.create')) 
			<a href="{{route('product.new')}}" class="btn btn-success btn-alt btn-xs" style="border-radius: 0px !important;">
				<i class='fa fa-plus'></i> 
				{{trans('core.add_new_product')}}
			</a>
		@endif


		@if(count(Request::input()))
		<span class="pull-right">	
            <a class="btn btn-alt btn-default font-black btn-xs" href="{{ action('ProductController@getIndex') }}">
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
					<td class="text-center font-white"># &nbsp;&nbsp;</td>
					<td class="text-center font-white">{{trans('core.name')}}</td>
					<td class="text-center font-white">{{trans('core.in_stock')}}</td>
					<td class="text-center font-white">{{trans('core.actions')}}</td>
				</thead>

				<tbody >
					@foreach($products as $product)
						<tr>
							<td class="text-center">{{$loop->iteration}}</td>
							<td class="text-center">
								{{$product->name}}
								({{$product->code}})
							</td>						
							<td class="text-center">
								@if($product->quantity)
									{{$product->quantity}} {{$product->unit}}
								@else
									0
								@endif
							</td>

							<td class="text-center">
                                <div class="btn-group">
                                  <button type="button" class="btn btn-info btn-alt btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('core.actions')}} <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu pull-right">
                                    @if(auth()->user()->can('product.manage'))
                                    <li>
                                        <a href="{{route('product.edit', $product)}}" title="Edit" >
											<i class="fa fa-edit" style="color: #069996;"></i>
											{{trans('core.edit')}}
										</a>
                                    </li>

                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#priceUpdate{{$product->id}}">
                                        <i class="fa fa-usd" style="color: #0ad629;"></i>
							    		{{trans('core.update_price')}}
							    		</a>
                                    </li>

                                    <!-- product delete modal trigger -->
                                    <li>
										<a data-toggle="modal" data-target="#deleteModal{{$product->id}}">
											<i class="fa fa-trash" style="color: #edb426;"></i>
										 	{{trans('core.delete')}}
										</a>
                                    </li>
                                    @endif

                                    @if(auth()->user()->can('product.view'))
                                    <li>
                                        <!-- product details button -->
										<a href="{{route('product.details', $product)}}">
											<i class="fa fa-eye" style="color: #269fed;"></i>
										 	{{trans('core.details')}}
										</a>
                                    </li>
                                    @endif

                                    <!-- Print barcode of a product -->
                                    <li>
										<a href="{{route('single.print_barcode', $product)}}">
											<i class="fa fa-barcode" style="color: purple;"></i>
										 	{{trans('core.print_barcode')}}
										</a>
                                    </li>
                                  </ul>
                                </div>
                            </td>
						</tr>

						<!-- Price Update Modal -->
						<div class="modal fade" id="priceUpdate{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">Update {{$product->name}} Price</h4>
						      </div>
						      <form action="{{route('product.price.update')}}" method="post">
						      <div class="modal-body">
						      	{{csrf_field()}}
						        <div class="form-group">
								    <label>{{trans('core.update_cost_price')}}</label>
								    <input type="text" name="cost_price" class="form-control number" value="{{$product->cost_price}}" id="cost_price" onkeypress='return event.charCode <= 57 && event.charCode != 32'>
								</div>

								<div class="form-group">
								    <label>{{trans('core.update_mrp')}}</label>
								    <input type="text" name="mrp" class="form-control number" value="{{$product->mrp}}">
								    <span id="pinky"></span>
								</div>

								<input type="hidden" name="product_id" value="{{$product->id}}">
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
						        <button type="submit" class="btn btn-primary">{{trans('core.update')}}</button>
						      </div>
						      </form>
						    </div>
						  </div>
						</div>
						<!-- Price Update Modal Ends-->
						
						<!-- modal for delete product -->
						<div class="modal fade" id="deleteModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							{!! Form::open(['route'=> ['product.delete', $product], 'method'=>'delete']) !!}
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">
						        	{{$product->name}}
						        </h4>
						      </div>
						      <div class="modal-body" >
						        <h4 >
						        	{{trans('core.delete_alert')}} <b>{{$product->name}}</b>?
						        </h4>
						        <br>
						        @if(count($product->sells) == 0 && count($product->purchases) == 0)
						        @else
						        	<h4 style="color: red;">
						        	<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>This product has too much transactions, so it can't be deleted!</h4>
						        @endif
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
						        <button type="submit" class="btn btn-danger">{{trans('core.delete')}}</button>
						      </div>
						    </div>
						  </div>
						  {!! Form::close() !!}
						</div>
						<!-- delete modal ends here -->
					@endforeach
				</tbody>
			</table>
		</div>
		<!--Pagination-->
		<div class="pull-right">
			{{ $products->links() }}
		</div>
		<!--Ends-->
	</div>

	<!-- Product search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.product') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        {!! Form::label('Name', trans('core.name'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('name', Request::get('name'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Product Code', trans('core.product_code'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('code', Request::get('code'), ['class' => 'form-control']) !!}
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

        $(function() {
          $('.number').on('input', function() {
            match = (/(\d{0,100})[^.]*((?:\.\d{0,5})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
            this.value = match[1] + match[2];
          });
        });
	</script>

	

@stop