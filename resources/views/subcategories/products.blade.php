@extends('app')

@section('contentheader')
	{{trans('core.product')}}
@stop

@section('breadcrumb')
	{{trans('core.product')}}
@stop

@section('main-content')
	<div class="panel-body">
		@if($products->count() > 0)
			<table class="table table-bordered table-striped table-hover">
				<thead class="table-header-color">
					<td># &nbsp;&nbsp;</td>
					<td>{{trans('core.name')}}</td>
					<td>{{trans('core.in_stock')}}</td>
				</thead>

				<tbody >
					@foreach($products as $product)
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$product->name}}</td>						
							<td align="center">
								@if($product->quantity)
									{{$product->quantity}}
								@else
									0
								@endif
							</td>						
						</tr>
					@endforeach
				</tbody>
			</table>
		@else

		<h2>No Product</h2>

		@endif

		<!--Pagination-->
		<div class="pull-right">
			{{ $products->links() }}
		</div>
		<!--Ends-->
	</div>
@stop