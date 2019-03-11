@extends('app')

@section('contentheader')
	Create Expense
@stop

@section('breadcrumb')
	<a href="{{route('expense.index')}}">Expense List</a>
	 &nbsp;>&nbsp;
	Create Expense
@stop



@section('main-content')

	<div class="row">
		<div class="col-md-offset-2">
			@if($errors->any)
			<ul>
				@foreach($errors->all() as $error)
					<li style="color:red;">{{$error}}</li>
				@endforeach
			</ul>
			@endif
		</div>
	</div>


	{!! Form::model($expense,['method' => 'post', 'files' => true]) !!}
		<div class="row font17 top20">
			<div class="col-md-offset-2 col-md-6">
				<label> {{trans('core.subcategory_name')}}  </label>
				<span class="required">*</span>
				{!! Form::text('purpose', $expense->purpose, ['class' => 'form-control']) !!}
			</div>

			<div class="col-md-offset-2 col-md-6">
				<label> {{trans('core.subcategory_name')}}  </label>
				<span class="required">*</span>
				{!! Form::text('amount', $expense->amount, ['class' => 'form-control']) !!}
			</div>

			<div class="col-md-offset-2 col-md-6" style="padding-top:10px;">
				{!! Form::submit('Save', ['class' => 'btn btn-success']) !!}
			</div>
		</div>
	{!! Form::close() !!}

@stop