@extends('app')

@section('title')
	POS
@stop

@section('contentheader')
	POS
@stop

@section('breadcrumb')
	POS
@stop

@section('main-content')

<div class="panel-body">

	<form method="post" id="app"> 
		{{ csrf_field() }}
		<div class="form-horizontal" style="margin-top: 20px;">
			<div class="form-group">
		    <label class="col-md-offset-2 col-md-2  control-label">
		    	{{trans('core.product')}}:
		    </label>
		    <div class="col-md-5">
		      <input type="text" name="product_id" @keyup.prevent="selectProduct">
		    </div>
		  </div>
		</div>

    	<div>
    		<table class="table table-bordered">
    			<thead class="table-header-color">
					<tr>
						<td class="text-center" style="width: 25%;">
							{{trans('core.product')}}
						</td>
						<td class="text-center"  style="width: 15%;">
							{{trans('core.unit_price')}}
						</td>
						<td class="text-center"  style="width: 15%;">
							{{trans('core.quantity')}}
						</td>
						<td class="text-center" v-if="enableProductTax == 1" style="width: 15%;">
							{{trans('core.product_tax')}}
						</td>
						<td class="text-center" style="width: 20%;">
							{{trans('core.sub_total')}}
						</td>
						<td style="width: 5%;">&nbsp;</td>
					</tr>
				</thead>

				<tbody>
					<tr 
						is="sell"
						v-for="sell in sells" 
						:id="sell.id"
						:sell="sell"
						:enable_product_tax="{{ settings('product_tax') }}"
						:add="addInput"
						:remove="removeInput"
					></tr>
				</tbody>

				<tfoot>
					<tr>
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.total')}} 
							<span v-if="enableProductTax == 1">
								{{trans('core.excluding_tax')}}
							</span>
						</td>
						<td colspan="2">
							<input type="text" class="form-control text-center" :value="total" disabled="true" />
						</td>
					</tr>
					
					<!--Product Tax Section-->
					<tr v-if="enableProductTax == 1">
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.total_tax')}}
						</td>
						<td colspan="2">
							<input type="text" class="form-control text-center" :value="total_product_tax" disabled="true"/>
						</td>
					</tr>
					<!--Product tax section ends-->

					<tr>
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.labor_cost')}}
						</td>
						<td colspan="2">
							<input type="text" class="form-control text-center" v-model="shipping_cost" />
						</td>
					</tr>

					<tr>
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.discount')}}
						</td>
						<td colspan="2">
							<input type="text" class="form-control text-center" v-model="discount" />
						</td>
					</tr>

					<tr v-if="discount !== 0">
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.discount_type')}}
						</td>
						<td colspan="2">
							<select v-model="discountType" class="form-control">
								<option value="cash">{{trans('core.fixed')}}</option>
								<option value="percentage">{{trans('core.percentage')}}</option>
							</select>
						</td>
					</tr>

					<!--Invoice Tax Section-->
					<tr v-if="enableInvoiceTax !== 0">
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.tax')}}
						</td>
						<td colspan="2">
							<input type="text" name="" class="form-control text-center"  :value="invoice_tax" disabled="true">
						</td>
					</tr>
					<!--Ends-->

					<tr>
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>		
							{{trans('core.net_total')}}
						</td>
						<td colspan="2">
							<input type="text" class="form-control text-center" :value="netTotal" disabled />
						</td>
					</tr>

					<tr>
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>{{trans('core.payment_method')}}</td>
						<td colspan="2">
							<select v-model="method" class="form-control selectpicker">
							  <option value="cash">{{trans('core.cash')}}</option>
							  <option value="cheque">{{trans('core.cheque')}}</option>
							  <option value="others">{{trans('core.others')}}</option>
							</select>
						</td>
					</tr>

					<tr>
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.paid')}}
						</td>
						<td colspan="2">
							<input type="text" class="form-control text-center" v-model="paid" />
						</td>
					</tr>

					<tr v-if="netTotal - paid > 0">
						<td colspan="3" @if(!rtlLocale()) style="text-align: right; font-weight: bold;" @endif>
							{{trans('core.due')}} &nbsp;&nbsp;
						</td>
						<td colspan="2">
							<input type="text" v-model="due" class="form-control text-center" disabled="true">
						</td> 
					</tr>

					<tr>
						<td colspan="5">
							<button type="submit" @click.prevent="postForm" :disabled="submitted" class="btn btn-success pull-right"> 
								<i class="fa fa-spinner fa-pulse fa-fw" v-if="submitted"></i> 
								{{trans('core.submit')}} 
							</button>
						</td>
					</tr>
				</tfoot>
    		</table>  	 
		</div>
	</form>
</div>


<template id="sell">
	<tr>
		<td>
			@{{ sell.name }}
		</td>
		<td>
			<input type="text" v-model="sell.price" class="form-control text-center" disabled="true">
			<p v-if="sell.min_price > 0" style="font-size: 10px; font-weight: bold; text-align: center;">
				{{trans('core.min_retail_price')}}: 
				{{ settings('currency_code') }} @{{sell.min_price}} 
			</p>
		</td>
		<td>
			<input type="text" v-model="sell.quantity" class="form-control text-center">
			<span v-if="sell.product_id != 0">
				<p v-if="sell.quantity > sell.stock" style="color: red; font-size: 10px; font-weight: bold; text-align: center;">
					<span v-if="sell.stock > 0">{{trans('core.in_stock')}}: @{{sell.stock}}</span>
					<span v-else-if="sell.stock == 0" >{{trans('core.out_of_stock')}}</span>
				</p>
			</span>
		</td>
		<td v-if="enable_product_tax ==1">
			<input type="text" v-model="sell.product_tax" class="form-control text-center" disabled="true"> 
		</td>
		<td>
			<input type="text" v-model="sell.subtotal" class="form-control text-center" disabled="true">
		</td>
		<td>
			<button @click.prevent="remove(id)" class="btn btn-danger" v-if="id != 1">
				<i class="fa fa-times"></i>
			</button>
			<button @click.prevent="add()" class="btn btn-success" v-else >
				<i class="fa fa-plus"></i>
			</button>
		</td>
	</tr>
</template>
@stop

@section('js')
    @parent
	<script src="/assets/js-core/vue.min.js"></script>
    <script src="/assets/js-core/axios.min.js"></script>
    
    <script>
    	$(document).ready(function () {
    		$('.selectPickerLive').selectpicker();
    	})
	    
    	axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
		axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    	Vue.component('sell', {
    		template: '#sell',
    		props: ['id', 'sell', 'add', 'remove','enable_product_tax'],
    		data: function () {
	    		return {}
	    	},
	    	methods: {
	    		setPrice: function (event) {
	    			var selectedPrice = $('option:selected', event.target).data('price')
	    			this.sell.price = selectedPrice
	    			this.sell.cost_price = $('option:selected', event.target).data('cost_price');
	    			this.sell.stock = $('option:selected', event.target).data('quantity');
	    			this.sell.unit_tax_rate = $('option:selected', event.target).data('taxrate')
	    			this.sell.tax_type = $('option:selected', event.target).data('taxtype')
	    			this.sell.min_price = $('option:selected', event.target).data('minprice')
	    		}
	    	},
	    	mounted: function () {
	    		this.$watch('sell.price', function (value) {
	    			if(this.enable_product_tax === 1){
		    			var unitTax = this.sell.unit_tax_rate
		    			if(this.sell.tax_type == 1){
		    				this.sell.product_tax = (unitTax * value * this.sell.quantity) / 100
		    			}else{
		    				this.sell.product_tax = unitTax * this.sell.quantity
		    			}
		    		}
	    			this.sell.subtotal = (value * this.sell.quantity) + this.sell.product_tax
	    		})

	    		this.$watch('sell.quantity', function (value) {
	    			if(this.enable_product_tax === 1){
	    				var unitTax = this.sell.unit_tax_rate
		    			if(this.sell.tax_type == 1){
		    				this.sell.product_tax = (unitTax * this.sell.price * value) / 100
		    			}else{
		    				this.sell.product_tax = unitTax * this.sell.quantity
		    			}
	    			}
	    			this.sell.subtotal = (this.sell.price * value) + this.sell.product_tax
	    		})
	    	}
    	})

    	var app = new Vue({
		    el: '#app',
		    data: {
		    	customer: 1,
		    	paid: 0,
		    	method: 'cash',
		    	shipping_cost: 0,
		    	products: {!! json_encode($products) !!},
		    	sells: [
		    	],
		    	discount: 0,
		    	discountType: 'cash',
		    	submitted: false,
		    	enableProductTax: {{ settings('product_tax') ?: 0 }},
		    	enableInvoiceTax: {{ settings('invoice_tax') ?: 0 }},
		    	invoice_tax_rate: {{ settings('invoice_tax_rate') ?: 0 }},
		    	invoice_tax_type: {{ settings('invoice_tax_type') ?: 2 }},

		    },
		    computed: {
		    	// summation of product_mrp & product_tax
		    	subTotal: function () {
		    		var total = 0
		    		for (var i = 0; i < this.sells.length; i++) {
		        		total = total + this.sells[i].subtotal
		        	}
		        	return total
		    	},
		    	
		    	discountAmount: function () {
		    		var discountAmount = this.discount
		        	if (this.discountType === 'percentage') {
		        		discountAmount = this.subTotal * (1 * this.discount / 100)
		        	}
		        	return discountAmount
		    	},

		    	total: function () {
		        	return this.subTotal 
		    	},

		    	netTotal: function () {
		    		return this.total + this.invoice_tax + parseInt(this.shipping_cost) - this.discountAmount
		    	},

		    	due: function () {
		    		return this.netTotal - this.paid
		    	},

		    	total_product_tax: function () {
		    		var totalProductTax = 0
		    		for (var i = 0; i < this.sells.length; i++) {
		        		totalProductTax = totalProductTax + (this.sells[i].product_tax)
		        	}
		        	return totalProductTax
		    	},

		    	//calculate total invoice tax
		    	invoice_tax: function () {
		    		var invoice_tax_amount = 0
		    		if(this.enableInvoiceTax == 1){
		    			
			    		//check if tax type is percentage(1) or fixed (2)
			    		if(this.invoice_tax_type == 1){
			    			invoice_tax_amount = (this.invoice_tax_rate * (this.total - this.discountAmount)) / 100
			    		}else{
			    			invoice_tax_amount = this.invoice_tax_rate
			    		}
		    		}
		    		return invoice_tax_amount
		    	},
		    },
		    methods:{
		        addInput: function () {
		        	var newInputId = 1
		        	for (var i = 0; i < this.sells.length; i++) {
		        		newInputId = this.sells[i].id + 1
		        	}
		        	this.sells.push({ id: newInputId, price: 0, quantity: 1, subtotal: 0, product_tax: 0 })
		        	this.$nextTick(function () {
		        		$('.selectPickerLive').selectpicker()
		        	})
		        },
		        selectProduct: function (event) {
		        	var value = event.target.value
		        	// console.log('PRODUCTS', JSON.stringify(this.products))
		        	var product = this.products.find(function (data) {
		        		// console.log('data')
		        		// console.log(value)
		        		// console.log(JSON.stringify(data))
		        		return parseInt(data.id) === parseInt(value)
		        	})
		        	console.log(JSON.stringify(product))
		        	if (product !== undefined) {
						this.sells.push(
			    		{ 
			    			id: product.id,
			    			name: product.name,
			    			price: product.mrp, 
			    			cost: product.cost_price, 
			    			quantity: 1, 
			    			subtotal: 0,
			    			unit_tax_rate: 0,
			    			tax_type: 0,
			    			product_tax: 0,
			    			product_id: 0, 
			    			stock: 1,
			    			min_price: 0,
			    		})
		        	}

		        },
		        removeInput: function (id) {
		           console.log('PASSED ID', id)
		           var index = this.sells.findIndex(function (sell) {
		           		return sell.id === id
		           })
		           console.log('INDEX', index)
		           this.sells.splice(index, 1)
		        },
		        postForm: function () {
		        	if(this.paid > this.netTotal){
		        		 swal("Sorry", "Paid amount (" + this.paid + ") cant\'be greater than total amount (" + this.netTotal + ")", "error");
		        		return false;
		        	}
		        	var self = this
					axios.post('/admin/sell/new', { sells: this.sells, customer: this.customer, paid: this.paid, method: this.method, discountType: this.discountType, discount: this.discount, total: this.total, shipping_cost: this.shipping_cost })
					  .then(function (response) {
					    console.log(JSON.stringify(response.data));
					    self.submitted = true
					    window.location.href = '{{route("sell.index")}}';
					  })
					  .catch(function (error) {
					  	console.log(JSON.stringify(error))
					    swal('Something went wrong..', error.response.data.message, 'error')
					  });
		        }
		    }
		});
    </script>
@stop