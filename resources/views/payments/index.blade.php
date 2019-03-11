@extends('app')

@section('title')
	Payment
@stop

@section('contentheader')
	Payment
@stop

<style>
  tr th{
    text-align: center;
  }
</style>

@section('breadcrumb')
	payment
@stop

@section('main-content')
    <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
                <p>Reference No: {{$payments->first()->reference_no}}</p>
            </div>

            <div class="box-body" >
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Paid Amount</th>
                  <th>Date</th>
                </tr>

                <?php 
                  $number = 0;
                ?>
                @foreach($payments as $payment)
                <tr>
                  <td>
                    <?php $number = $number + 1;  ?>
                    {{$number}}
                  </td>
                  <td>{{$payment->amount}}</td>
                  <td>{{carbonDate($payment->created_at, 'y-m-d')}}</td>
                </tr>
                @endforeach
              </table>
            </div>
          </div><!-- /.box -->
        </div> <!-- /.col -->
       
        <div class="col-md-6">
          	<div class="box">
	            <div class="box-header with-border">
                <table class="table table-bordered">
                  <thead>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                  </thead>

                  <tbody>
                    <tr>
                      <td>{{$payments->first()->transaction->total}} {{settings('currency_code')}}</td>
                      <td>{{$payments->first()->transaction->paid}} {{settings('currency_code')}}</td>
                      <td>{{$payments->first()->transaction->total - $payments->first()->transaction->paid}} {{settings('currency_code')}}</td>
                    </tr>
                  </tbody>
                </table>
	            </div>

	            <div class="box-body" >
	            	@if($payments->first()->transaction->total - $payments->first()->transaction->paid > 0)
		              	<form method="post" name="myForm" onsubmit="return validateForm()">
		              		{{csrf_field()}}
		              		<div class="form-group">
							   <label>Amount</label>
							   <input type="hidden" name="reference_no" value="{{$payments->first()->reference_no}}">
							   <input type="number" class="form-control" name="amount" required>
							   <p id="message" style="color: red;"></p>
							</div>

							<button type="submit" class="btn btn-success">Submit</button>
		              	</form>
		            @else
		            	<marquee>
		            		<h4 style="color: green;">All Payment Clear</h4>
		            	</marquee>
		            @endif
	            </div>
          </div>
        </div>
        
    </div>
@stop

@section('js')
    @parent
    <script>
		function validateForm() {		
		    var x = parseInt(document.forms["myForm"]["amount"].value);
		    var y = parseInt("{{$payments->first()->transaction->total - $payments->first()->transaction->paid}}");
		    if (x > y) {
            swal(
              'Oops...',
              'Paid Amount Is Greater Than Total Due',
              'error'
            )
		        document.getElementById("message").innerHTML = "Paid amount ("+ x + " Tk ) can't be greater than Due Amount (" + y + "Tk )";
		        return false;
		    }
		}
</script>
@stop