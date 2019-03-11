@extends('app')

@section('title')
	{{trans('core.print_barcode')}}
@stop

@section('contentheader')
	{{trans('core.print_barcode')}} 
	<b>({{$product->name}})</b>
@stop

@section('breadcrumb')
	{{trans('core.print_barcode')}}
@stop

@section('main-content')
	
	<div id='app'>
		<div class="panel-heading" style="background-color: #c5d5d34d;">
			<form>
			  	<div class="form-group">
			    	<div class="row">
			    		<div class="col-md-6">
				    		<label>Quantity</label>
				     		<input type="number" v-model.number='items' class="form-control">
				     	</div>

				     	<div class="col-md-6">
				     		<label>Print Per Page</label>
					      	<select v-model="ppp" class="form-control">
							  <option disabled value="">Please select one</option>
							  <option value="pp10">Per Page 10</option>
							  <option value="pp24">Per Page 24</option>
							  <option value="pp40">Per Page 40</option>
							  <option value="pp50">Per Page 50</option>
							  <option value="pp60">Per Page 60</option>
							  <option value="pp70">Per Page 70</option>
							</select>
					    </div>
					</div>
			  	</div>

			  	<div class="form-group">
			  		<div class="row">
			    		<div class="col-md-2">
						  	<div class="checkbox-custom checkbox-custom-success">
		                        <input id="checkbox3" type="checkbox" v-model.number='site_name'>
		                        <label for="checkbox3">
		                            Site Name
		                        </label>
	                    	</div>
						</div>

						<div class="col-md-2">
							<div class="checkbox-custom checkbox-custom-success">
					    		<input id="checkbox4" type="checkbox" v-model.number='product_name'>
					    		<label for="checkbox4">
	                            	Product Name
	                        	</label>
						    </div>
						</div>

						<div class="col-md-2">
							<div class="checkbox-custom checkbox-custom-success">
					    		<input id="checkbox1" type="checkbox" v-model.number='product_price'>
					    		<label for="checkbox1">
	                            	Product Price
	                        	</label>
						    </div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<a @click="printDiv('printableArea')" class="btn btn-block btn-xs" style="background-color: #2D5C8A; color: #FFF;"> 
							{{trans('core.print')}} 
						</a>
					</div>
				</div>
			</form>
		</div>

		<div class="panel-body">
			<div class="a4paper" id="printableArea">
				<div class="barcode-single-item" v-for="item of items">
					<div :class="ppp">
						<p v-if="site_name" class="barcode-info-p">
							{{settings('site_name')}}
						</p>
						<p v-if="product_name" class="barcode-info-p">
							{{$product->name}}
						</p>
						<?php echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($product->code, "c128A",1,33,array(1,1,1), true) . '"   />'; ?>
						<br>
						<small style="font-size: 8px !important;"><b>{{$product->code}}</b></small>
						<p v-if="product_price" style="line-height: 12px !important; font-size: 8px !important;">
							<b>MRP: {{settings('currency_code')}} {{$product->mrp}} </b>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('js')
    @parent
	<script src="/assets/js-core/vue.min.js"></script>
    <script src="/assets/js-core/axios.min.js"></script>
    
    <script>
	    
    	axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
		axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    	var app = new Vue({
		  el: '#app',
		  data: {
		    items: 36,
		    ppp:'pp40',
		    site_name: true,
		    product_name: true,
		    product_price: true,
		  },

		  methods:{
		  	printDiv: function (divName) {
	        	var printContents = document.getElementById(divName).innerHTML;
	            var originalContents = document.body.innerHTML;
	            document.body.innerHTML = printContents;
	            window.print();
	            document.body.innerHTML = originalContents;
	            location.reload();
        	}
		  }
		})
    </script>
@stop