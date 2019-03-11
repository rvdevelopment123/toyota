@extends('app')

@section('contentheader')
	@if($subcategory->id)
		{{trans('core.editing')}} <b>{{$subcategory->name}}</b>
	@else
		{{trans('core.add_new_subcategory')}}
	@endif
@stop

@section('breadcrumb')
	<a href="{{route('subcategory.index')}}">{{trans('core.subcategory_index')}}</a>
	<li>
		@if($subcategory->id)
			{{trans('core.editing')}} {{$subcategory->name}}
		@else
			{{trans('core.add_new_subcategory')}}
		@endif
	</li>
	
@stop


@section('main-content')

	<div class="panel-body">

	    <h3 class="title-hero">
			@if($subcategory->id)
				{{trans('core.editing')}} {{$subcategory->name}}
			@else
				{{trans('core.add_new_subcategory')}}
			@endif
	    </h3>

		{!! Form::model($subcategory,['method' => 'post', 'files' => true, 'class' => 'form-horizontal bordered-row', 'id' => 'ism_form']) !!}
			<div class="form-group">		
				<label class="col-sm-3 control-label"> 
					{{trans('core.subcategory_name')}}  
					<span class="required">*</span>
				</label>
				<div class="col-sm-6"> 
					{!! Form::text('name', $subcategory->name, ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" title="{{trans('core.category_info')}}">
					{{trans('core.category_name')}}
					<span class="required">*</span>
				</label>
				
				<div class="col-sm-6"> 
					{!! Form::select('category_id', $category, null, ['class' => 'form-control selectpicker', 'title' => 'Please select a category', 'data-live-search' => 'true']) !!}
				</div>
			</div>

			<div class="bg-default content-box text-center pad20A mrg25T">
            	<input type="submit" class="btn btn-lg btn-primary" id="submitButton" value="{{ trans('core.save') }}" onclick="submitted()">
        	</div>

		{!! Form::close() !!}
	</div>
@stop