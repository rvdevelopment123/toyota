@extends('app')

@section('breadcrumb')
	<a href="{{route('category.index')}}">{{trans('core.category_index')}}</a>
	<li>
		@if($category->id)
			{{trans('core.editing')}} {{$category->category_name}}
		@else
			{{trans('core.add_new_category')}}
		@endif
	</li>
@stop

@section('main-content')
	<div class="panel-body">
	    <h3 class="title-hero">
			@if($category->id)
				{{trans('core.editing')}} 
				{{$category->category_name}}
			@else
				{{trans('core.add_new_category')}}
			@endif
	    </h3>
	    <div class="example-box-wrapper">
			{!! Form::model($category,['method' => 'post', 'files' => true, 'class' => 'form-horizontal bordered-row', 'id' => 'ism_form']) !!}

				<div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.category_name') }}</label>
					<span class="required">*</span>
					<div class="col-sm-6">
						{!! Form::text('name', $category->category_name, ['class' => 'form-control']) !!}
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
	
@stop