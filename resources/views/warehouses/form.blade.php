@extends('app')

@section('breadcrumb')
	<a href="{{route('warehouse.index')}}">{{trans('core.warehouse')}}</a>
	<li>
		@if($warehouse->id)
			{{trans('core.editing')}} {{$warehouse->name}}
		@else
			{{trans('core.add_new_warehouse')}}
		@endif
	</li>
@stop

@section('main-content')
	<div class="panel-body">

	    <h3 class="title-hero">
			@if($warehouse->id)
				{{trans('core.editing')}} {{$warehouse->name}}
			@else
				{{trans('core.add_new_warehouse')}}
			@endif
	    </h3>
	    <div class="example-box-wrapper">
		
			{!! Form::model($warehouse,['method' => 'post', 'files' => true, 'class' => 'form-horizontal bordered-row', 'id' => 'ism_form']) !!}

				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.name') }}</label>
					<span class="required" style="color: red; font-weight: bolder;">*</span>
					<div class="col-sm-6">
						{!! Form::text('name', $warehouse->name, ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.address') }}</label>
					<div class="col-sm-6">
						{!! Form::text('address', $warehouse->address, ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.phone') }}</label>
					<div class="col-sm-6">
						{!! Form::text('phone', $warehouse->phone, ['class' => 'form-control']) !!}
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.in-charge-name') }}</label>
					<div class="col-sm-6">
						{!! Form::text('in_charge_name', $warehouse->in_charge_name, ['class' => 'form-control']) !!}
					</div>
				</div>

			    <div class="bg-default content-box text-center pad20A mrg25T">
                    <input class="btn btn-lg btn-primary" type="submit" id="submitButton" value="{{ trans('core.save') }}" onclick="submitted()">
                </div>
			{!! Form::close() !!}


		</div>
	</div>

@stop

@section('js')
	@parent
	<script>
		
	</script>

@stop