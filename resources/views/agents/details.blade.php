@extends('app')

@section('title')
	{{trans('core.agent_details')}}
@stop

@section('contentheader')
	{{trans('core.agent_details')}}
@stop

@section('breadcrumb')
	{{trans('core.agent_details')}}
@stop

@section('main-content')

  <div class="panel-body">
    @if ($sell->product->category->category_name == 'Cars' || $sell->product->category->category_name == 'Car')
      <div class="row">
        <div class="col-sm-12">
          <h3>
            <span>{{ $transaction->client->name }}</span>, 
            <span><b>{{ $transaction->agent->first_name . " " . $transaction->agent->last_name }}</b></span>
          </h3>
          <span>ID: {{ $transaction->client->national_id }}</span>
        </div>
        <div class="col-sm-6 col-xs-12">
          <div style="padding: 10% 0%;">
            <dl class="dl-horizontal">
              <dt>{{ trans('core.national_id') }}:</dt>
              <dd>{{ $transaction->client->national_id }}</dd>
              <dt>{{ trans('core.address') }}:</dt>
              <dd>{{ $transaction->client->address }}</dd>
              <dt>{{ trans('core.phone_number') }}:</dt>
              <dd>{{ $transaction->client->phone }}</dd>
            </dl>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12">
          <div style="padding: 10% 0%;">
            <dl class="dl-horizontal">
              <dt>{{ trans('core.sponsor_name') }}:</dt>
              <dd>{{ $transaction->agent->sponsor_name }}</dd>
              <dt>{{ trans('core.national_id') }}:</dt>
              <dd>{{ $transaction->agent->national_id }}</dd>
              <dt>{{ trans('core.address') }}:</dt>
              <dd>{{ $transaction->agent->address }}</dd>
              <dt>{{ trans('core.phone_number') }}:</dt>
              <dd>{{ $transaction->agent->phone }}</dd>
            </dl>
          </div>
        </div>
      </div>
      <hr style="border-top: 1px solid black;">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <h2><b>Car Information</b></h2>
          <div style="padding: 10% 0%;">
            <dl class="dl-horizontal">
              <dt>{{ trans('core.car_model') }}:</dt>
              <dd>{{ $sell->product->model }}</dd>
              <dt>{{ trans('core.year') }}:</dt>
              <dd>{{ $sell->product->year }}</dd>
              <dt>{{ trans('core.vin') }}:</dt>
              <dd>{{ $sell->product->vin }}</dd>
            </dl>
          </div>
        </div>
        @if ($payment->method == 'installment')
          <div class="col-sm-6 col-xs-12">
            <h2><b>Installment Details</b></h2>
            <div style="padding: 10% 0%;">
              <dl class="dl-horizontal">
                <dt>{{ trans('core.upfront_payment') }}:</dt>
                <dd>{{ $payment->upfront_payment }}</dd>
                <dt>{{ trans('core.monthly_payment') }}:</dt>
                <dd>{{ $payment->monthly_payment }}</dd>
                <dt>{{ trans('core.last_payment') }}:</dt>
                <dd>{{ $payment->last_payment }}</dd>
                <dt>{{ trans('core.total_installment') }}:</dt>
                <dd>{{ $payment->total_installment }}</dd>
              </dl>
            </div>
          </div>
        @endif
      </div>
      @if ($payment->method == 'installment')
        <div class="row">
          <div class="col-sm-2">
            <h3><b>Payment Due</b></h3>
            <br>
            <div class="panel-layout">
              <div class="panel-box">
                <div class="panel-content bg-gray">
                  <h4><b>{{ $payment->monthly_payment }}</b></h4>
                  <h6 class="text-center">
                    SAR
                  </h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <h3><b>Remaining</b></h3>
            <br>
            <div class="panel-layout">
              <div class="panel-box">
                <div class="panel-content bg-gray">
                  <h4><b>{{ $payment->total_installment - $payment->monthly_payment }}</b></h4>
                  <h6 class="text-center">
                    SAR
                  </h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <h3><b>Received</b></h3>
            <br>
            <div class="panel-layout">
              <div class="panel-box">
                <div class="panel-content bg-gray">
                  <h4><b>{{ $payment->monthly_payment }}</b></h4>
                  <h6 class="text-center">
                    SAR
                  </h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-2">
            <h3><b>Installment Plane</b></h3>
            <br>
            <div class="panel-layout">
              <div class="panel-box">
                <div class="panel-content bg-gray">
                  <h4><b>3</b>/24</h4>
                  <h6 class="text-center">
                    Months
                  </h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
    @else
      {{-- OLD LAYOUT --}}
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="panel-layout">
              <div class="panel-box">
                  <div class="panel-content bg-primary">
                      <div class="image-content font-white">
                          <div class="center-vertical">
                              <div class="meta-box center-content">
                                  <h4 class="meta-subheading">
                                    {{$transaction->client->name}}
                                  </h4>
                                  <h3 class="meta-heading">
                                    {{$transaction->client->company_name}}
                                  </h3>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="panel-content bg-white">
                      <ul class="list-group list-group-separator mrg0A row list-group-icons">
                          <li class="col-md-12 list-group-item">
                            {{trans('core.phone')}}: 
                            {{$transaction->client->phone}}
                          </li>
                          <li class="col-md-12 list-group-item">
                            {{trans('core.email')}}:
                            {{$transaction->client->email}}
                          </li>
                          <li class="col-md-12 list-group-item">
                            {{trans('core.address')}}:
                            {{$transaction->client->address}}
                          </li>
                      </ul>
                  </div>
              </div>
            </div>
        </div><!-- /.col -->

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="panel-layout">
              <div class="panel-box">
                  <div class="panel-content bg-blue-alt">
                      <div class="image-content font-white">
                          <div class="center-vertical">
                              <div class="meta-box center-content">
                                  <h4 class="meta-subheading">
                                    <i class="fa fa-file-text"></i>
                                  </h4>
                                  <h3 class="meta-heading">
                                      {{trans('core.invoice_info')}}
                                  </h3>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="panel-content bg-white" style="min-height: 190px;">
                      <ul class="list-group list-group-separator mrg0A row list-group-icons">
                          <li class="col-md-12 list-group-item">
                            {{trans('core.invoice_no')}}: 
                            {{$transaction->reference_no}}
                            @if($transaction->pos == 1)
                              (POS)
                            @endif
                          </li>

                          <li class="col-md-12 list-group-item">
                            {{trans('core.date')}}: 
                            {{carbonDate($transaction->created_at, 'y-m-d')}}
                          </li>
                          <li class="col-md-12 list-group-item">
                            {{trans('core.time')}}: 
                            {{carbonDate($transaction->created_at, 'time')}}
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
        </div> <!-- /.col -->

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="panel-layout">
              <div class="panel-box">
                  <div class="panel-content bg-purple">
                      <div class="image-content font-white">
                          <div class="center-vertical">
                              <div class="meta-box center-content">
                                  <h4 class="meta-subheading">
                                    <i class="fa fa-money"></i>
                                  </h4>
                                  <h3 class="meta-heading">
                                    {{trans('core.payment')}}
                                  </h3>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="panel-content bg-white" style="min-height: 190px;">
                      <ul class="list-group list-group-separator mrg0A row list-group-icons">
                          <!-- <li class="col-md-12 list-group-item">
                            {{trans('core.total')}} :
                            {{settings('currency_code')}}
                            {{bangla_digit($transaction->net_total + $transaction->discount)}}
                          </li>

                          @if($transaction->discount)
                            <li class="col-md-12 list-group-item">
                                {{trans('core.discount')}}:
                                {{settings('currency_code')}}
                                {{bangla_digit($transaction->discount)}}
                            </li>
                          @endif -->

                          <li class="col-md-12 list-group-item">
                            {{trans('core.net_total')}} :
                            {{settings('currency_code')}}
                            {{twoPlaceDecimal($transaction->net_total)}}
                          </li>

                          <li class="col-md-12 list-group-item">
                            {{trans('core.paid')}} :
                            {{settings('currency_code')}}
                            {{twoPlaceDecimal($transaction->paid)}} 
                            @if($transaction->return == 1)
                                <small>(This sell has return item)</small>
                            @endif
                          </li>

                          @if($transaction->return == 1 || ($transaction->net_total - $transaction->paid) > 0 )
                            <li class="col-md-12 list-group-item">
                                @if($transaction->net_total - $transaction->paid > 0)
                                  {{trans('core.due')}}:
                                  {{settings('currency_code')}}
                                  {{twoPlaceDecimal(($transaction->net_total) - $transaction->paid)}}
                                @else
                                  {{trans('core.total_return')}}:
                                  {{settings('currency_code')}}
                                  {{$transaction->payments->where('type', 'return')->sum('amount')}}
                                @endif
                                
                            </li>
                          @endif
                      </ul>
                  </div>
              </div>
          </div>
        </div> <!-- /.col --> 
      </div>


      <div class="row">
        <div class="col-md-12">
          @if($transaction->return == 1)
            <div class="well" style="background-color: rgba(255, 222, 160, 0.25);">
              This sell has return item 
              <a href="#return" data-toggle="tab" class="btn btn-primary btn-xs">
                {{trans('core.view_details')}}
              </a>
            </div>
          @endif
        </div>
      </div>

      <!-- tab section -->
      <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#items" data-toggle="tab">
                      {{ trans('core.sale_items') }}
                    </a>
                  </li>
                  <li>
                    <a href="#payments" data-toggle="tab">
                      {{ trans('core.payment_history') }}
                    </a>
                  </li>

                  @if(auth()->user()->can('sell.manage'))
                  <li>
                    <a href="#make-payment" data-toggle="tab">
                      {{ trans('core.make_payment') }}
                    </a>
                  </li>
                  @endif
              </ul>

              <div class="tab-content">
                <!--sell items table-->
                <div class="active tab-pane animated fadeIn" id="items" style="padding-bottom: 50px;">
                  @include('sells.partials.sales_item_list')
                </div>
                <!--sell items table-->

                <!-- Payment list table -->
                <div class="tab-pane animated fadeIn" id="payments">
                  @include('sells.partials.payment-history')       
                </div>
                <!-- Payment list table ends -->

                <!--Make payment form-->
                <div class="tab-pane animated fadeIn" id="make-payment">
                  @include('sells.partials.make-payment-form')
                </div>
                <!--Make payment div ends-->

                <!--return starts-->
                <div class="tab-pane animated fadeIn" id="return">
                  @include('sells.partials.return_item_list')
                </div>
                <!--return ends-->

              </div> <!--  tab-content -->
            </div> <!-- nav-tabs-custom -->
        </div> <!-- col -->
      </div> <!--row-->
    @endif
  </div> <!--panel body-->

  <div class="panel-footer">
      <a class="btn btn-border btn-alt border-black font-black btn-xs" href="{{ route('agents.index') }}">
        <i class="fa fa-backward"></i>
        {{ trans('core.back') }}
      </a>

      {{--  <a class="btn btn-alt btn-warning btn-xs" target="_BLINK" href="{{route('sell.invoice', $transaction)}}">
        <i class="fa fa-print"></i>
        {{trans('core.print_invoice')}}
      </a>
     
      <a class="btn btn-alt btn-purple btn-xs" target="_BLINK" href="{{route('pos.invoice', $transaction)}}">
        <i class="fa fa-print"></i>
        {{trans('core.print_pos_invoice')}}
      </a>  --}}
  </div>

@stop

@section('js')
  @parent
  <script>
    function validateForm() {   
        var x = parseFloat(document.forms["myForm"]["amount"].value);
        var y = parseFloat("{{$transaction->net_total - $transaction->paid}}");
        if (x > y) {
            document.getElementById("message").innerHTML = "Paid amount ({{settings('currency_code')}} "+ x + " ) can't be greater than Due Amount ({{settings('currency_code')}} " + y + " )";
            return false;
        }
    }

    $(function() {
      $('.number').on('input', function() {
        match = (/(\d{0,100})[^.]*((?:\.\d{0,2})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
        this.value = match[1] + match[2];
      });
    });
</script>
@stop