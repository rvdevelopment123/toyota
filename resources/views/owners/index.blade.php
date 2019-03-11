@extends('app')

@section('contentheader')
	{{trans('core.owner')}}
@stop

@section('breadcrumb')
	{{trans('core.owner')}}
@stop

@section('main-content')

<div class="panel-heading">
	<!-- @if(auth()->user()->can('category.create')) -->
		<a href="{{route('owner.new')}}" class="btn btn-success btn-xs">
			<i class='fa fa-plus'></i> 
			{{trans('core.add_new_owner')}}
		</a>
	<!-- @endif -->
</div>

<div class="panel-body">
	<div class="table-responsive">
		<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			<thead class="table-header-color">
				<td class="text-center">#</td>
				<td class="text-center">{{trans('core.name')}}</td>
				<td class="text-center">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($owners as $owner)
					<tr>
						<td class="text-center">{{$loop->iteration}}</td>
						<td class="text-center">{{$owner->name}}</td>

						<td class="text-center">
							<!-- @if(auth()->user()->can('category.manage')) -->
								<a href="{{route('owner.edit',$owner)}}" class="btn btn-primary btn-xs">
									<i class="fa fa-edit"></i>
									{{trans('core.edit')}}
								</a>
								<!-- Delete modal trigger -->
								<a type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal{{$owner->id}}">
									<i class="fa fa-trash"></i>
								 	{{trans('core.delete')}}
								</a>
							<!-- @endif -->
						</td>
					</tr>

					<!-- Modal -->
					<div class="modal fade" id="deleteModal{{$owner->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						{!! Form::open(['route' => ['owner.delete', $owner], 'method' => 'delete' ]) !!}
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">
					  			{{$owner->name}}
					        </h4>
					      </div>
					      <div class="modal-body">
					        <h4>
					        	Are you sure to delete this <b>{{$owner->name}}</b>?
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
		{{ $owners->links() }}
	</div>
	<!--Ends-->
</div>


@stop

@section('js')
	@parent
	<script>
		
	</script>

@stop