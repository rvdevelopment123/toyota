@extends('app')

@section('title')
	Alert Products
@stop

@section('contentheader')
    {{trans('core.alert_title')}}
    {{bangla_digit($products->count())}}
    {{trans('core.entity')}}
    {{trans('core.product')}}
@stop

@section('breadcrumb')
	Alert Products
@stop

@section('main-content')
	<div class="panel-body">
		<table class="table table-bordered">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">{{trans('core.name')}}</td>
				<td class="text-center font-white">{{trans('core.quantity')}}</td>
				<td class="text-center font-white">{{trans('core.actions')}}</td>
			</thead>

			<tbody style="background-color: #fff;" id="myTable">
				@foreach($products as $product)
					<tr>
						<td>{{$product->name}}</td>						
						<td align="center">
							@if($product->quantity)
								{{$product->quantity}}
							@else
								0
							@endif
						</td>						
						
						<td>
							<a href="{{route('product.details', $product->id)}}" class="btn btn-info btn-xs">
							 	{{trans('core.details')}}
							</a>
						</td>
					</tr>						
				@endforeach
			</tbody>
		</table>
	</div>
@stop

@section('js')
    @parent
    <script>
        
    </script>
@stop