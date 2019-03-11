@extends('layouts.pos')

@section('title')
	@parent
@stop

@section('main-content')
	<div class="panel panel-default" id="app">
		<div class="panel-body">
			<div class="row">
				<form method="post">
					<div class="col-md-4" style="border: 2px solid #ddd;">
						<div class="row pad5A">
							<div class="col-md-10 pad5A">
								<select class="form-control" v-model="customer"  data-live-search="true">
						      		<option v-for="customerData in customers" :value="customerData.id">
						      			@{{customerData.first_name + ' ' + customerData.last_name}}
						      		</option>
							    </select>
							</div>
							<div class="col-md-2 pad5A">
								<a class="btn btn-default btn-block zero-border-radius" data-toggle="modal" data-target="#customerModal" >
									<i class="fa fa-plus"></i>
								</a>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control" v-model="barcode" @keyup.prevent="getProductByBarcode"  placeholder="Scan your barcode" />
							</div>
						</div>

						<div style="min-height: 380px; overflow-y: scroll; ">
							<table class="table table-bordered">
								<tr class=" {{settings('theme')}} pos-table-header" >
									<td width="30%" class="text-center">{{trans('core.product')}}</td>
									<td width="10%" class="text-center">{{trans('core.quantity')}}</td>
									<td width="25%" class="text-center">{{trans('core.unit_price')}}</td>
									<td width="25%" class="text-center">{{trans('core.sub_total')}}</td>
									<td width="10%" class="text-center">
										<i class="fa fa-trash"></i>
									</td>
								</tr>
								
								<tr v-for="product in selectedProducts" :key="product.uuid">
									<td class="text-center">
										@{{ product.name }}
									</td>
									<td class="text-center">
										<input type="text" v-model='product.sell_quantity' class="form-control text-center" onkeypress='return event.charCode <= 57 && event.charCode != 32' @keyup.prevent="addQuantity(product)">
									</td>
									<td class="text-center">@{{ product.mrp }}</td>
									<td class="text-center">
										@{{ product.mrp *  product.sell_quantity}}
									</td>
									<td @click.prevent="removeFromSelected(product)" class="text-center" style="cursor: pointer;">
										<i class="fa fa-times"></i>
									</td>
								</tr>
							</table>
						</div>


						<!--POS Table footer-->
						<div class="col-md-12 {{settings('theme')}}" style="margin-bottom: 10px;">
							<div class="row pos-footer">
								<div class="col-md-12 padLpadR0">
									<table class="pos-table">
										<tr>
											<td width="25%" height="25px">
												{{trans('core.total_item')}}:
											</td>
											<td width="25%" height="25px" align="right">
												<div>@{{totalQuantity}}</div>
											</td>
											<td width="25%" height="25px" align="right">
												{{trans('core.total')}}:
											</td>
											<td width="25%" height="25px" align="right">
												<div >@{{subTotal}}</div>
											</td>
										</tr>
									</table>
								</div> <!--Col Ends-->
							</div> <!--Row Ends-->
					
							<div class="row pos-footer">
								<div class="col-md-12 padLpadR0">
									<table class="pos-table">
										<tr>
											<td width="30%" height="25px">
												{{trans('core.discount')}} : 
											</td>
											<td width="20%" height="25px">
												<input type="text" v-model='discount' class="pos-discount-input"/>
											</td>
											<td width="25%" height="25px" align="right">
												{{trans('core.amount')}}:
											</td>
											<td width="25%" height="25px" align="right">
												<div id="">@{{discountAmount}} </div>
											</td>
										</tr>
									</table>
								</div> <!--Col Ends-->
							</div> <!--Row Ends-->

							<div class="row pos-footer">
								<div class="col-md-12 padLpadR0">
									<table class="pos-table">
										<tr>
											<td width="75%" height="25px" align="right">
												{{trans('core.vat')}}:
											</td>
											<td width="50%" height="25px" align="right">
												<div >@{{invoiceTax}}</div>
											</td>
										</tr>
									</table>
								</div> <!--Col Ends-->
							</div> <!--Row Ends-->
					
							<div class="row pos-total">
								<div class="col-md-12 padLpadR0">
									<table class="pos-table">
										<tr>
											<td width="50%" height="30px">
												{{trans('core.total_payable')}}:
											</td>
											<td width="50%" height="30px" align="right">
												<div id="total_payable">@{{netTotal}}</div>
											</td>
										</tr>
									</table>
								</div> <!--Col Ends-->
							</div> <!--Row Ends-->

							<div class="row">
								<div class="col-md-12 pad10A ">
									<div class="pull-right">
										<button type="button" class="btn btn-danger">
											{{trans('core.cancel')}}
										</button>
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#paymentModal">
										  {{trans('core.payment')}}
										</button>
									</div>
								</div> <!--Col Ends-->
							</div> <!--Row Ends-->
						</div> <!--Col Ends-->
					</div> <!-- col-md-4 Ends -->
				</form>

				<div class="col-md-8">
					<div class="panel panel-default" style="border: 1px solid #ddd;">
						<div class="panel-body">
							<div class="row" style="margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12" style="padding-left: 15px; padding-right: 15px; padding-top: 10px;">
									<input type="text" class="form-control" style="border: 1px solid #3a3a3a; color: #010101;" placeholder="Search" v-model="search" @keyup.prevent="getProductBySearch"/>
								</div>
							</div>

							<div class="row" style="margin-left: 0px; margin-right: 0px;">
								<div class="col-md-12 pos-cat-div">
									<div class="regular slider" style="width: 100%">
										<a 
											data-toggle="tab" 
											href="#all" 
											class="pos-single-cat {{settings('theme')}} active"
											@click="loadProducts('all')"
											
										>
											Frequent
										</a>

										@foreach($categories as $category)
											<a 
												data-toggle="tab" 
												href="#tab{{$category->id}}" 
												class="pos-single-cat {{settings('theme')}}"
												@click="loadProducts({{ $category->id }})"
												
											>
												{{$category->category_name}}
											</a>
										@endforeach
									</div>
								</div> <!-- col-ends -->
							</div> <!-- row ends -->

							<!--Show the products-->
							<div style="min-height: 535px;" v-if="loading" >
								<center>
									<div id="loader">  
									  <div class="a"></div>
									  <div class="b"></div>  
									  <div class="c"></div>
									  <div class="d"></div>
									</div>
									
									<!-- <div class='loader loader1'>
									  <div>
									    <div>
									      <div>
									        <div>
									          <div>
									            <div></div>
									          </div>
									        </div>
									      </div>
									    </div>
									  </div>
									</div> -->

								</center>
							</div>

						  	<div v-else class="" style="min-height: 535px;">
						  		<div role="allTab" class="tab-pane active" id="all">
						    		<div class="col-md-12" >
										<div class="col-md-2 pos-product-col" v-for="product in products" :key="product.id"
											@click.prevent="addToSelected(product)" style="min-height: 170px; max-height: 170px; background-color: #FFF;">
											<center>
												<img v-if="product.image" :src="'/uploads/products/' + product.image" class="img-responsive img-rounded" :alt="product.name">
												<img v-else src="{{asset('uploads/products/8NKeIGlWVSCE.png')}}" :alt="product.name" class="img-responsive img-rounded">

												<p style="min-height: 60px;">@{{product.name}}</p>
												<small v-if="product.quantity > 0">
													<b>In Stock: @{{product.quantity}}</b>
												</small>
												<small v-else>Out Of Stock</small>
											</center>
										</div>
									</div>
						    	</div>

						    	<!-- @{{selectedProducts}} -->

							    @foreach($categories as $category)
							  		<div role="tabpanel" class="tab-pane" id="tab{{$category->id}}">
							    		<div class="col-md-12" >
											<div class="col-md-2 pos-product-col" v-for="product in products" :key="product.id" @click.prevent="addToSelected(product)" style="min-height: 170px; background-color: #FFF;">
												<center>
													<img v-if="product.image" :src="'/uploads/products/' + product.image" :alt="product.name" class="img-responsive img-rounded">
													<img v-else src="{{asset('uploads/products/8NKeIGlWVSCE.png')}}" :alt="product.name" class="img-responsive img-rounded">

													<p>@{{product.name}}</p>
													<small v-if="product.quantity >= 0">
														Stock: @{{product.quantity}}
													</small>
													<small v-else>Out Of Stock</small>
												</center>
											</div>
										</div>
							    	</div>
							    @endforeach
						  	</div>
						</div> <!-- panel-body Ends-->
					</div> <!-- panel Ends-->
				</div> <!--Col-md-8 Ends-->
			</div> <!-- row ends -->
		</div> <!-- panel-body ends -->

	<!--Create customer modal body-->
	@include('pos.partials.customer_form')
	<!--Ends-->

	<!--Payment Modal Starts-->
	@include('pos.partials.pos-payment')
	<!--Payment Modal Ends-->

	</div> <!-- panel ends -->


@stop

@section('js')
    @parent
    <script src="/assets/js-core/lodash.js"></script>
	<script src="/assets/js-core/vue.js"></script>
    <script src="/assets/js-core/axios.min.js"></script>
    <script>

    	axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
		axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    	var app = new Vue({
		    el: '#app',
		    data: {
		    	customers: [],
		    	customer: '1',
		    	addCustomer: {
		          	first_name: '',
		          	last_name: '',
		          	email: '',
		          	phone: '',
		          	address: '',
		          	company_name: '',
		          	client_type: 'retailer'
		        },
		        products: [],
		        selectedProducts: {},
		        barcode: '',
		        discount: 0,
		        enableInvoiceTax: {{ settings('invoice_tax') ?: 0 }},
		        invoice_tax_rate: {{ settings('invoice_tax_rate') ?: 0 }},
		    	invoice_tax_type: {{ settings('invoice_tax_type') ?: 2 }},
		    	paid: 0,
		    	paying_method: 'cash',
		    	reference_no: '',
		    	search: '',
		    	loading: false,
		    },
		    computed: {
		    	totalQuantity: function () {
		    		return _.reduce(this.selectedProducts, function(result, product) {
					  return result + parseInt(product.sell_quantity)
					}, 0)
		    	},

		    	subTotal: function () {
		    		return _.reduce(this.selectedProducts, function(result, product) {
					  return result + parseInt(product.mrp * product.sell_quantity)
					}, 0)
		    	},
		    	discountAmount: function () {
		    		var discountAmount = this.discount
		        	var isPercentage = (this.discount.toString().indexOf('%') !== -1) ? true : false
		        	if(isPercentage) {
		        		var amount = discountAmount.replace("%", "");
		        		discountAmount = this.subTotal * (1 * amount / 100)
		        	}

		        	return discountAmount
		        	
		    	},

		    	invoiceTax: function () {
		    		var invoice_tax_amount = 0
		    		if(this.enableInvoiceTax == 1){
		    			
			    		//check if tax type is percentage(1) or fixed (2)
			    		if(this.invoice_tax_type == 1){
			    			invoice_tax_amount = (this.invoice_tax_rate * (this.subTotal - this.discountAmount)) / 100
			    		}else{
			    			invoice_tax_amount = this.invoice_tax_rate
			    		}
		    		}
		    		return invoice_tax_amount
		    	},

		    	netTotal: function () {
		    		return (this.subTotal + this.invoiceTax) - this.discountAmount
		    	},

		    },
		    methods:{
		    	addQuantity: function (product) {
		    		var quantityToAdd = parseInt(product.sell_quantity)
		    		this.addToSelected(product, quantityToAdd, true)
		    	},
		    	resetClient: function () {
		    		this.addCustomer = {
			          	first_name: '',
			          	last_name: '',
			          	email: '',
			          	phone: '',
			          	address: '',
			          	company_name: '',
			          	client_type: 'retailer'	    			
		    		}
		    	},
		    	postNewCustomer: function () {
		    		var self = this
		    		axios.post('/api/v1/customer/save', this.addCustomer)
		    			.then(function (response) {
		    				console.log(JSON.stringify(response))
		    				self.loadClients()
		    				self.resetClient()
		    				$(self.$refs.customerModalClose).trigger('click')
		    			})
		    			.catch (function (response) {
		    				console.log(JSON.stringify(response))
		    			})
		    	},
		    	loadClients: function () {
		    		var self = this
			        axios.get('/api/client').then(function (response) {
			        	/*console.log(JSON.stringify(response))*/
			          self.customers = response.data
			        }, function (response) {
			          console.log(response)
			        })
		    	},
		    	loadProducts: function (data) {
		    		var self = this
		    		self.loading = true;
		    		self.products = []
		    		var getUrl = (data === 'all') ? 
		    					'/api/v1/products' : 
		    					'/api/v1/category/' + data + '/products'
		    		axios.get(getUrl)
		    			.then(function (response) {
		    				self.products = response.data.data
		    				self.loading = false
		    			})
		    			.catch(function (response) {
		    				console.log(JSON.stringify(response))
		    			})
		    	},

		    	getProductBySearch: function () {
		    		var self = this
		    		self.loading = true;
		    		self.products = []
		    		var searchUrl = '/api/v1/product-by-search/' + self.search
		    		axios.get(searchUrl)
		    			.then(function (response) {
		    				self.products = response.data.data
		    				self.loading = false
		    			})
		    			.catch(function (response) {
		    				console.log(JSON.stringify(response))
		    		})
		    	},

		    	getProductByBarcode: _.debounce(function () {
					var self = this
					axios.get('/api/v1/product-by-barcode/' + self.barcode)
						.then(function (response) {
							if (response.data.found === true) {
								self.addToSelected(response.data.product)
								self.barcode = ''
							}
						})
						.catch(function (response) {
							console.log(JSON.stringify(response))
						})
			    }, 300),

			    addToSelected: function (product, quantityToAdd = 1, fresh = false) {
			    	var exists = this.selectedProducts[product.id]
			    	if (fresh) {
			    		product.sell_quantity = 0
			    	}
			    	if (exists !== undefined) {
			    		product.sell_quantity = parseInt(this.selectedProducts[product.id].sell_quantity) + quantityToAdd
			    		product.uuid = _.uniqueId('product_')
			    		this.selectedProducts = _.omit(this.selectedProducts, product.id)
			    		this.$set(this.selectedProducts, product.id, product)
			    	} else {
			    		product['sell_quantity'] = 1
			    		product['uuid'] = _.uniqueId('product_')
			    		this.$set(this.selectedProducts, product.id, product)
			    	}
			    },
			    removeFromSelected: function (product) {
			    	this.selectedProducts = _.omit(this.selectedProducts, product.id)
			    },

			    postSell: function () {
		    		var self = this
		    		if(self.totalQuantity <= 0){
		        		 swal("Sorry", "Please Select Product Before Payment ", "warning");
		        		return false;
		        	}

		        	if(self.paid < self.netTotal){
		        		 swal("Sorry", "Paid amount can\'t be less than Net Total " + self.netTotal, "warning");
		        		return false;
		        	}

		    		axios.post('/admin/pos/sell/save', {customer: this.customer, sells: this.selectedProducts, paid: this.paid, method: this.paying_method, discount_amount: this.discountAmount, invoice_tax: this.invoiceTax, })
		    			.then(function (response) {
		    				swal('success', 'success', 'success')
		    				var transactionId = response.data.id
		    				//self.selectedProducts = {}
		    				window.location.href = 'pos/sell/invoice/' + transactionId;
		    				console.log(JSON.stringify(response))
		    			})
		    			.catch (function (response) {
		    				alert('error')
		    				console.log(JSON.stringify(response))
		    			})
		    	},

		    },

		    mounted: function () {
      			this.loadClients()
      			this.loadProducts('all')
		    }
		});
    </script>
@stop