@extends('app')

@section('contentheader')
	{{trans('core.role_index')}}
@stop

@section('breadcrumb')
	{{trans('core.role_index')}}
@stop


@section('main-content')
	
	<div class="panel-heading">
		@if(auth()->user()->can('acl.manage'))
			<a type="button" class="btn btn-success btn-xs " data-toggle="modal" data-target="#myModal">
			   <i class="fa fa-plus"></i> 
			   {{trans('core.add_new_role')}}
			</a>
		@endif
	</div>

	<div class="panel-body">
		<table class="table table-hover table-bordered" >
			<thead class="table-header-color">
				<td class="text-center">{{trans('core.role')}}</td>
				<td class="text-center">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($roles as $role)
					<tr>
						<td class="text-center">{{$role->name}}</td>
						<td class="text-center">
							<!-- <a href="#" class="btn btn-primary btn-xs">
								<i class="fa fa-edit"></i>
								{{trans('core.edit')}}
							</a> -->

							<!-- Set permission for role -->
							@if(auth()->user()->can('acl.set'))
								<a href="{{route('role.permission', $role->id)}}" class="btn btn-info btn-xs">
									<i class="fa fa-user-secret"></i>
									{{trans('core.set_permission')}}
								</a>
							@endif
							<!-- ends -->

						</td>
					</tr>
					<!-- Modal for delete role-->
				@endforeach
			</tbody>
		</table>
		<!-- Table Ends -->
	</div>
	<!-- Modal for create role-->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	{!! Form::open(['method' => 'post', 'class' => 'form-horizontal']) !!}
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">
	        	{{trans('core.add_new_role')}}
	        </h4>
	      </div>
	      <div class="modal-body">
	        <div class="form-group">
            	<div class="col-md-1">
                	<label>{{trans('core.role')}}</label>
                </div>
                <div class="col-sm-11">
                    <input type="text" class="form-control" name="role_name" required>
                </div>
            </div>
	      </div>
	      <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
            	{{trans('core.close')}}
            </button>
            {!! Form::submit('Save', ['class' => 'btn btn-primary', 'data-disable-with' => 'Saving']) !!}
          </div>
        {!! Form::close() !!}
	    </div>
	  </div>
	</div>
	<!--Modal Ends-->
@stop