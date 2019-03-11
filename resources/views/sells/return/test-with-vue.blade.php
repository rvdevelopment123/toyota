@extends('app')

@section('title')
	{{trans('core.return')}}
@stop

@section('contentheader')
	{{trans('core.return')}}
@stop

@section('breadcrumb')
	{{trans('core.return')}}
@stop

@section('main-content')
	@section('main-content')

	<form method="post" id="app">

		{{ csrf_field() }}
		
		<div class="well" style="background-color: rgba(255, 222, 160, 0.25);">
			@if($transaction->total - $transaction->paid <= 0)
				Payment of this sale is fully completed.
			@else
				Payment of this sale is not completed. 
				<b>Paid: {{$transaction->paid}} {{settings('currency_code')}}</b>
				<b>Due: {{$transaction->total - $transaction->paid}} {{settings('currency_code')}}</b>
			@endif
		</div>
		{{-- <div class="well">@{{ returnsells }}</div> --}}
    	<div class="table-responsive">
    		<table class="table table-bordered" style="background-color: #fff;">
    			<thead>
					<tr style="background-color: #455664; color: #FFF;">
						<td>{{trans('core.product')}}</td>
						<td>{{trans('core.unit_price')}}</td>
						<td>{{trans('core.quantity')}}</td>
						<td>{{trans('core.sub_total')}}</td>
						<td>&nbsp;</td>
					</tr>
				</thead>

				<tbody>
					<tr 
						is="returnsell"
						v-for="returnsell in returnsells" 
						:id="returnsell.id"
						:returnsell="returnsell"
						:add="addInput"
						:remove="removeInput"
					></tr>
				</tbody>

				<tfoot>
					<tr>
						<td colspan="3" style="text-align: right; font-weight: bold;">
							Total Return Amount
						</td>
						<td>
							<input type="text" class="form-control" :value="total" disabled />
						</td>
					</tr>

					<tr>
						<td colspan="4">
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

	<template id="return">
		<tr>
			<td>
				<select class="form-control" @change="setPrice" v-model="returnsell.product_id">
					<option>Select a product</option>
					@foreach($products as $product)
						<option value="{{$product->id}}" data-price="{{$product->mrp}}" data-quantity="{{$product->quantity}}">
							{{$product->name}}
						</option>
					@endforeach
				</select>
			</td>
			<td>
				<input type="text" v-model="returnsell.price" class="form-control" disabled="true">
			</td>
			<td>
				<input type="text" v-model="returnsell.quantity" class="form-control" >
				<p v-if="returnsell.quantity > returnsell.stock" style="color: red;">
					{{trans('core.in_stock')}}: @{{returnsell.stock}}
				</p>
			</td>
			<td>
				<input type="text" v-model="returnsell.subtotal" class="form-control" disabled="true">
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
    <script>

    	axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
		axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    	Vue.component('returnsell', {
    		template: '#return',
    		props: ['id', 'returnsell', 'add', 'remove'],
    		data: function () {
	    		return {}
	    	},
	    	methods: {
	    		setPrice: function (event) {
	    			var selectedPrice = $('option:selected', event.target).data('price')
	    			this.returnsell.price = selectedPrice
	    			this.returnsell.stock = $('option:selected', event.target).data('quantity');
	    		}
	    	},
	    	mounted: function () {
	    		this.$watch('returnsell.price', function (value) {
	    			this.returnsell.subtotal = value * this.returnsell.quantity
	    		})

	    		this.$watch('returnsell.quantity', function (value) {
	    			this.returnsell.subtotal = this.returnsell.price * value
	    		})
	    	}
    	})

    	var app = new Vue({
		    el: '#app',
		    data: {
		    	customer: '',
		    	method: 'cash',
		    	returnsells: [
		    		{ id: 1, price: 0, quantity: 0, subtotal: 0, product_id: 0, stock: 0},
		    	],
		    	submitted: false           
		    },
		    computed: {
		    	total: function () {
		    		var total = 0
		    		for (var i = 0; i < this.returnsells.length; i++) {
		        		total = total + this.returnsells[i].subtotal
		        	}

		        	return total
		    	},
		    },
		    methods:{
		        addInput: function () {
		        	var newInputId = 1
		        	for (var i = 0; i < this.returnsells.length; i++) {
		        		newInputId = this.returnsells[i].id + 1
		        	}
		        	this.returnsells.push({ id: newInputId, price: 0, quantity: 0, subtotal: 0 })
		        },
		        removeInput: function (id) {
		        	console.log('PASSED ID', id)
		           var index = this.returnsells.findIndex(function (returnsell) {
		           		return returnsell.id === id
		           })
		           console.log('INDEX', index)
		           this.returnsells.splice(index, 1)
		        },
		        postForm: function () {
		        	var self = this
		        	/*console.log(this.total)*/
					axios.post('', { returnsells: this.returnsells, customer: this.customer, paid: this.paid, method: this.method, total: this.total })
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