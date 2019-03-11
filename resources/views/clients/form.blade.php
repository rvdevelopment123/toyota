@extends('app')

@section('contentheader')
	@if($client->id)
		Editing <b>{{$client->name}}</b>
	@else	
		{{trans('core.add_new_customer')}}
	@endif
@stop

@section('breadcrumb')
	<a href="{{route('client.index')}}">
		{{trans('core.customer_list')}}
	</a>
	<li>{{trans('core.add_new_customer')}}</li>
@stop

@section('main-content')

	<div class="panel-body">

	    <h3 class="title-hero">
			@if($client->id)
				Editing <b>{{$client->name}}</b>
			@else	
				{{trans('core.add_new_customer')}}
			@endif
	    </h3>

	    <div class="example-box-wrapper">
			{!! Form::open(['route' => 'client.save' ,'method' => 'post', 'files' => true, 'class' => 'form-horizontal bordered-row', 'id' => 'ism_form']) !!}

			{!! Form::hidden('id', $client->id) !!}
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('core.first_name') }}<span class="required">*</span></label>
				<div class="col-sm-6">
					{!! Form::text('first_name', $client->first_name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('core.last_name') }}</label>
				<div class="col-sm-6">
					{!! Form::text('last_name', $client->last_name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('core.email') }}</label>
				<div class="col-sm-6">
					{!! Form::text('email', $client->email, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('core.phone') }}<span class="required">*</span></label>
				<div class="col-sm-6">
					{!! Form::text('phone', $client->phone, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('core.address') }}<span class="required">*</span></label>
				<div class="col-sm-6">
					{!! Form::textarea('address', $client->address, ['class' => 'form-control', 'rows'=>3]) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('core.company_name') }}</label>
				<div class="col-sm-6">
					{!! Form::text('company_name', $client->company_name, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">
					{{ trans('core.account_no') }}
				</label>
				<div class="col-sm-6">
					{!! Form::text('account_no', $client->account_no, ['class' => 'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">
					{{ trans('core.previous_due') }}
				</label>
				<div class="col-sm-6">
					{!! Form::text('previous_due', $client->provious_due, ['class' => 'form-control', 'onkeypress'=> 'return event.charCode <= 57 && event.charCode != 32']) !!}
				</div>
			</div>
			

			@if($client->client_type != 'purchaser')
				<!-- <div class="form-group">
					<label class="col-sm-3 control-label">{{ trans('core.customer_type') }}</label>
					<div class="col-sm-6">
						{!! Form::select('client_type', ['retailer' => 'Retailer', 'wholesaler' => 'Wholesaler'],null, ['class' => 'form-control']) !!}
					</div>
				</div> -->
				<input type="hidden" name="client_type" value="customer">
			@endif
		</div>

	    <div class="bg-default content-box text-center pad20A mrg25T">
            <input type="submit" class="btn btn-lg btn-primary" id="submitButton" value="{{ trans('core.save') }}" onclick="submitted()">
        </div>

	{!! Form::close() !!}
	</div>

@stop