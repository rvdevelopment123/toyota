@extends('app')

@section('contentheader')
	{{trans('core.user_list')}}
@stop

@section('breadcrumb')
	{{trans('core.user_list')}}
@stop

@section('main-content')

	<div class="panel-heading">
		@if(auth()->user()->can('user.create'))
			<a href="{{route('user.new')}}" class="btn btn-success btn-alt btn-xs" style="border-radius: 0px !important;">
				<i class='fa fa-plus'></i> 
				{{ trans('core.add_new_user') }}
			</a>
		@endif

		@if(count(Request::input()))
			<span class="pull-right">	
	            <a class="btn btn-default btn-alt btn-xs" href="{{ action('UserController@getIndex') }}">
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

		<table class="table table-bordered">
			<thead class="{{settings('theme')}}">
				<td class="text-center font-white">#</td>
				<td class="text-center font-white">{{trans('core.name')}}</td>
				<td class="text-center font-white">{{trans('core.email')}}</td>
				<td class="text-center font-white">{{trans('core.role')}}</td>
				<td class="text-center font-white">{{trans('core.actions')}}</td>
			</thead>

			<tbody>
				@foreach($users as $user)
					<tr>
						<td class="text-center">{{$loop->iteration}}</td>
						<td class="text-center">{{$user->name}}</td>
						<td class="text-center">{{$user->email}}</td>
						<td class="text-center">
							@foreach($user->roles as $role)
								{{ $role->name }}
							@endforeach
						</td>
						<td class="text-center">
							@if(auth()->user()->can('user.manage'))
								<a href="{{route('user.edit', $user)}}" class="btn btn-info btn-alt btn-xs">
									<i class="fa fa-edit"></i>
									{{trans('core.edit')}}
								</a>

								<a type="button" data-toggle="modal" data-target="#userAction{{$user->id}}">
									@if($user->inactive == 1)
										<span class="btn btn-success btn-alt btn-xs">
											{{trans('core.activate')}}
										</span>
									@else
										<span class="btn btn-danger btn-alt btn-xs">
											{{trans('core.deactivate')}}
										</span>
									@endif
								</a>
							@else
								<a href="#" class="btn btn-border btn-alt border-blue btn-link font-blue btn-xs" disabled>
									<i class="fa fa-edit"></i>
									{{trans('core.edit')}}
								</a>
							@endif							
						</td>
					</tr>

					<!-- Activate / Deactivate User -->
					<div class="modal fade" id="userAction{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					  <form method="post" action="{{route('user.status')}}">
					  	{{ csrf_field() }}
					  <input type="hidden" name="user_id" value="{{$user->id}}">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">
					        	{{$user->first_name}} {{$user->last_name}} is currently @if($user->inactive == 1) Inactive @else Active @endif
					        </h4>
					      </div>
					      <div class="modal-body">
					        Do you want to @if($user->inactive == 1) Activate  @else Deactivate @endif this user
					      </div>

					      <div class="modal-footer">
					        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
					        <button type="submit" class="btn btn-danger">Yes</button>
					      </div>
					      </form>
					    </div>
					  </div>
					</div>
				@endforeach
			</tbody>
		</table>

		<!--Pagination-->
		<div class="pull-right">
			{{ $users->links() }}
		</div>
		<!--Ends-->
	</div>

	<!-- User search modal -->
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
                        {!! Form::label('Email', trans('core.email'), ['class' => 'col-sm-3']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('email', Request::get('email'), ['class' => 'form-control']) !!}
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