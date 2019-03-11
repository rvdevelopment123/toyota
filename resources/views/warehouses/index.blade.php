@extends('app')

@section('contentheader')
	{{trans('core.warehouse')}}
@stop

@section('breadcrumb')
	{{trans('core.warehouse')}}
@stop

@section('main-content')

<div class="panel-heading">
	@if(auth()->user()->can('branch.create'))
		<a href="{{route('warehouse.new')}}" class="btn btn-success btn-alt btn-xs">
			<i class='fa fa-plus'></i> 
			{{trans('core.add_new_warehouse')}}
		</a>
	@endif
</div>

<div class="panel-body">
	<div class="table-responsive">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">#</td>
				<td class="text-center font-white">{{trans('core.name')}}</td>
				<td class="text-center font-white">{{trans('core.address')}}</td>
				<td class="text-center font-white">{{trans('core.phone')}}</td>
				<td class="text-center font-white">{{trans('core.in-charge-name')}}</td>
				<td class="text-center font-white">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($warehouses as $warehouse)
					<tr>
						<td class="text-center">{{$loop->iteration}}</td>
						<td class="text-center">{{$warehouse->name}}</td>
						<td class="text-center">
							@if($warehouse->address)
								{{$warehouse->address}}
							@else
								-
							@endif
						</td>
						<td class="text-center">
							@if($warehouse->phone)
								{{$warehouse->phone}}
							@else
								-
							@endif
						</td>

						<td class="text-center">
							@if($warehouse->in_charge_name)
								{{$warehouse->in_charge_name}}
							@else
								-
							@endif
						</td>

						<td class="text-center">
							@if(auth()->user()->can('branch.create'))
								<a href="{{route('warehouse.edit',$warehouse)}}" class="btn btn-info btn-alt btn-xs">
									<i class="fa fa-edit"></i>
									{{trans('core.edit')}}
								</a>
								<!-- Delete modal trigger -->
								<a type="button" class="btn btn-danger btn-alt btn-xs" data-toggle="modal" data-target="#deleteModal{{$warehouse->id}}">
									<i class="fa fa-trash"></i>
								 	{{trans('core.delete')}}
								</a>
							@endif
						</td>
					</tr>

					<!-- Modal -->
					<div class="modal fade" id="deleteModal{{$warehouse->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						{!! Form::open(['route' => ['warehouse.delete', $warehouse], 'method' => 'delete' ]) !!}
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">
					  			{{$warehouse->name}}
					        </h4>
					      </div>
					      <div class="modal-body">
					        <h4>
					        	Are you sure to delete this <b>{{$warehouse->name}}</b>?
					        </h4>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
		{{ $warehouses->links() }}
	</div>
	<!--Ends-->
</div>


@stop

@section('js')
	@parent
	<script>
		
	</script>

@stop