@extends('app')

@section('title')
	{{trans('core.invoice_tax_rate')}}
@stop

@section('contentheader')
	{{trans('core.invoice_tax_rate')}}
@stop

@section('breadcrumb')
	{{trans('core.invoice_tax_rate')}}
@stop

@section('main-content')

<div class="panel-heading">
	@if(auth()->user()->can('tax.actions'))
    <a id="addButton" class="btn btn-success btn-alt btn-xs" style="border-radius: 0px !important;">
  		<i class='fa fa-plus'></i> 
  		{{trans('core.add_tax_rate')}}
  	</a>
  @endif
</div>

<div class="panel-body" style="min-height: 600px !important;">
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
		<thead class="table-header-color">
			<td class="text-center">#</td>
			<td class="text-center">{{trans('core.name')}}</td>
			<td class="text-center">{{trans('core.type')}}</td>
			<td class="text-center">{{trans('core.rate')}}</td>
			@if(auth()->user()->can('tax.actions'))
        <td class="text-center">{{trans('core.actions')}}</td>
      @endif
		</thead>

		<tbody>
			@foreach($taxes as $tax)
				<tr>
					<td class="text-center">{{$loop->iteration}}</td>
					<td class="text-center">{{$tax->name}}</td>
					<td class="text-center">
						@if($tax->type == 1)
							{{trans('core.percentage')}}
						@else
							{{trans('core.fixed')}}
						@endif
					</td>
					<td class="text-center">{{$tax->rate}}</td>
					
          @if(auth()->user()->can('tax.actions'))
            <!--Tax Edit button trigger-->
            <td class="text-center">
  						<a href="#" 
  							data-id="{{$tax->id}}" 
  							data-name="{{$tax->name}}"
  							data-rate="{{$tax->rate}}"
  							data-type="{{$tax->type}}"
  							class="btn btn-info btn-alt btn-xs btn-edit">
  							<i class="fa fa-edit"></i>
                {{trans('core.edit')}}
  						</a>

              <!--Tax Delete button trigger-->
              <a href="#" data-id="{{$tax->id}}" data-name="{{$tax->name}}"  class="btn btn-danger btn-alt btn-xs btn-delete">
                 <i class="fa fa-trash"></i>
                 {{trans('core.delete')}}
              </a>
  					</td>
          @endif
				</tr>
			@endforeach
		</tbody>
	</table>
	
	<!--Pagination-->
	<div class="pull-right">
		{{ $taxes->links() }}
	</div>
	<!--Ends-->
</div>

<!--Create Taxrate Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['class' => '']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> {{trans('core.add_tax_rate')}}</h4>
            </div>

            <div class="modal-body">                  
                <div class="form-group">
				    <label>{{trans('core.name')}}</label>
				    <input type="text" name="name" class="form-control" required>
				</div>

				<div class="form-group">
				    <label>{{trans('core.type')}}</label>
				    {!! Form::select('type', ['1' => 'Percentage', '2' => 'Fixed'], null, ['class' => 'form-control']) !!}
				</div> 

				<div class="form-group">
				    <label>{{trans('core.rate')}}</label>
				    <input type="text" name="rate" class="form-control"  onkeypress='return event.charCode <= 57 && event.charCode != 32' required>
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
<!-- Create Taxrate modal ends -->

<!-- Delete Modal Starts -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  {!! Form::open(['route'=>'tax.delete','method'=>'POST']) !!}
  {!! Form::hidden('id',null,['id'=>'deleteTaxInput']) !!}
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            Delete <span id="deleteTaxName"></span>
        </h4>
      </div>
      <div class="modal-body">
        <h3>Are you sure you want to delete this tax rate?</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</div>
<!-- Modal Ends -->


<!-- Edit Modal Starts -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  {!! Form::open(['route'=>'tax.edit','method'=>'POST']) !!}
  {!! Form::hidden('id',null,['id'=>'editTaxInput']) !!}
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
            Edit <span id="editTaxName"></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
		    <label>{{trans('core.name')}}</label>
		    <input type="text" name="name" class="form-control" id="editName" required>
		</div>

		<div class="form-group">
		    <label>{{trans('core.type')}}</label>
		    {!! Form::select('type', ['1' => 'Percentage', '2' => 'Fixed'], null, ['class' => 'form-control', 'id' => 'editType']) !!}
		</div> 

		<div class="form-group">
		    <label>{{trans('core.rate')}}</label>
		    <input type="text" name="rate" class="form-control"  onkeypress='return event.charCode <= 57 && event.charCode != 32' id="editRate" required>
		</div>   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-info">Update</button>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</div>
<!-- Modal Ends -->   

@stop

@section('js')
	@parent
	<script>
		$(function() {
            $('#addButton').click(function(event) {
                event.preventDefault();
                $('#addModal').modal('show')
            });
        })

        $(document).ready(function(){
            $('.btn-delete').on('click',function(e){
                e.preventDefault();
                $('#deleteModal').modal();
                $('#deleteTaxInput').val($(this).attr('data-id'));
                var taxName = ($(this).attr('data-name'));
                document.getElementById("deleteTaxName").innerHTML = taxName;
            })
        });

        $(document).ready(function(){
            $('.btn-edit').on('click',function(e){
                e.preventDefault();
                $('#editModal').modal();
                $('#editTaxInput').val($(this).attr('data-id'));
                $('#editName').val($(this).attr('data-name'));
                $('#editType').val($(this).attr('data-type'));
                $('#editRate').val($(this).attr('data-rate'));
                var taxNameEdit = ($(this).attr('data-name'));
                document.getElementById("editTaxName").innerHTML = taxNameEdit;
            })
        });
	</script>

@stop