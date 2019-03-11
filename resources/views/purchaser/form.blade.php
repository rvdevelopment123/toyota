@extends('app')

@section('contentheader')
	@if($purchaser->id)
		Editing <b>{{$purchaser->name}}</b>
	@else	
		{{trans('core.add_new_supplier')}}
	@endif
@stop

@section('breadcrumb')
	<a href="{{route('purchaser.index')}}">{{trans('core.supplier_list')}}</a>
	<li>{{trans('core.add_new_supplier')}}</li>
@stop

@section('main-content')
	
	<div class="panel-body" >
		<h3 class="title-hero">
			@if($purchaser->id)
				Editing <b>{{$purchaser->name}}</b>
			@else	
				{{trans('core.add_new_supplier')}}
			@endif
		</h3>

		{!! Form::model($purchaser,['method' => 'post', 'files' => true, 'class' => 'form-horizontal bordered-row', 'id' => 'ism_form']) !!}

			<div class="form-group">
				<label class="col-sm-3 control-label"> {{trans('core.first_name')}} <span class="required">*</span></label>
				<div class="col-sm-6"> 
					{!! Form::text('first_name', $purchaser->first_name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"> {{trans('core.last_name')}}</label>
				<div class="col-sm-6"> 
					{!! Form::text('last_name', $purchaser->last_name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"> {{trans('core.company_name')}}</label>
				<div class="col-sm-6"> 
					{!! Form::text('company_name', $purchaser->company_name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"> {{trans('core.email')}} </label>
				<div class="col-sm-6"> 
					{!! Form::text('email', $purchaser->email, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"> {{trans('core.phone')}} <span class="required">*</span></label>
				<div class="col-sm-6"> 
					{!! Form::text('phone', $purchaser->phone, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"> {{trans('core.address')}} <span class="required">*</span></label>
				<div class="col-sm-6"> 
					{!! Form::textarea('address', $purchaser->address, ['class' => 'form-control', 'rows'=>3]) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">
					{{ trans('core.account_no') }}
				</label>
				<div class="col-sm-6">
					{!! Form::text('account_no', $purchaser->account_no, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">
					{{ trans('core.previous_due') }}
				</label>
				<div class="col-sm-6">
					{!! Form::text('previous_due', $purchaser->provious_due, ['class' => 'form-control', 'onkeypress'=> 'return event.charCode <= 57 && event.charCode != 32']) !!}
				</div>
			</div>

		    <div class="bg-default content-box text-center pad20A mrg25T">
		        <input type="submit" class="btn btn-lg btn-primary" id="submitButton" value="{{ trans('core.save') }}" onclick="submitted()">
		    </div>

		{!! Form::close() !!}

	</div> 

@stop