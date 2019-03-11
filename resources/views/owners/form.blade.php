@extends('app')

@section('breadcrumb')
	<a href="{{route('owner.index')}}">{{trans('core.owner')}}</a>
	<li>
		@if($owner->id)
			{{trans('core.editing')}} {{$owner->name}}
		@else
			{{trans('core.add_new_owner')}}
		@endif
	</li>
@stop

@section('main-content')
	<div class="panel-body">

	    <h3 class="title-hero">
			@if($owner->id)
				{{trans('core.editing')}} {{$owner->name}}
			@else
				{{trans('core.add_new_owner')}}
			@endif
	    </h3>
	    <div class="example-box-wrapper">
		
			{!! Form::model($owner,['method' => 'post', 'files' => true, 'class' => 'form-horizontal bordered-row', 'id' => 'ism_form']) !!}

				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.name') }}</label>
					<span class="required" style="color: red; font-weight: bolder;">*</span>
					<div class="col-sm-6">
						{!! Form::text('name', $owner->name, ['class' => 'form-control']) !!}
					</div>
				</div>

			    <div class="bg-default content-box text-center pad20A mrg25T">
                    <input type="submit" class="btn btn-lg btn-primary" id="submitButton" value="{{ trans('core.save') }}" onclick="submitted()">
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