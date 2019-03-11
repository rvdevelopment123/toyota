<!--Modal for new customer-->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" ref="customerModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('core.add_new_customer')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       	<form class="">
       		{!! csrf_field() !!}
		   	<div class="row">
			    <div class="col-md-6">
				    <label class="control-label">
				    	{{ trans('core.first_name') }}
				    	<span class="required">*</span>
				    </label>
				    <input type="text" v-model="addCustomer.first_name" class="form-control">
				</div>

				<div class="col-md-6">
					<label class="control-label">
				    	{{ trans('core.last_name') }}
				    	<span class="required">*</span>
				    </label>
			    
			    	<input type="text" v-model="addCustomer.last_name" class="form-control">
			    </div>
		  	</div>

		  	<div class="row">
			  	<div class="col-md-6">
				    <label class="control-label">
				    	{{ trans('core.email') }}
				    </label>
			    	<input type="text" v-model="addCustomer.email" class="form-control">
			    </div>

			    <div class="col-md-6">
				    <label class="control-label">
				    	{{ trans('core.phone') }}
				    	<span class="required">*</span>
				    </label>
				    <input type="text" v-model="addCustomer.phone" class="form-control">
			    </div>
		  	</div>

		  <div class="row">
		  	<div class="col-md-12">
			    <label class="control-label">
			    	{{ trans('core.address') }}
			    	<span class="required">*</span>
			    </label>
			    <textarea v-model="addCustomer.address" class="form-control" rows="2"> </textarea>
			</div>
		  </div>

		  <div class="row">
		  	<div class="col-md-12">
			    <label class="control-label">
			    	{{ trans('core.company_name') }}
			    </label>
		    	<input type="text" v-model="addCustomer.company_name" class="form-control">
		    </div>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" ref="customerModalClose">Close</button>
        <button @click.prevent="postNewCustomer" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--Ends-->