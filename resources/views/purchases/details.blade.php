@extends('app')

@section('title')
  {{trans('core.purchase_details')}}
@stop

@section('contentheader')
  {{trans('core.purchase_details')}}
@stop

@section('breadcrumb')
  {{trans('core.purchase_details')}}
@stop

@section('main-content')
    <div class="panel-body">
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="panel-layout">
              <div class="panel-box">
                  <div class="panel-content bg-primary">
                      <div class="image-content font-white">
                          <div class="center-vertical">
                              <div class="meta-box center-content">
                                  <h4 class="meta-subheading">
                                    {{$transaction->client->company_name}}
                                  </h4>
                                  <h3 class="meta-heading">
                                    {{$transaction->client->name}}
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
                                      {{trans('core.bill_info')}}
                                  </h3>
                              </div>
                          </div>
                      </div>
                  </div>


                  <div class="panel-content bg-white" style="min-height: 190px;">
                      <ul class="list-group list-group-separator mrg0A row list-group-icons">
                          <li class="col-md-12 list-group-item">
                            {{trans('core.ref_no')}}: 
                            {{$transaction->reference_no}}
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
                            {{trans('core.paid')}}:
                            {{settings('currency_code')}}
                            {{twoPlaceDecimal($transaction->paid)}}
                          </li>
                          
                          <li class="col-md-12 list-group-item">
                              {{trans('core.due')}}: 
                              {{settings('currency_code')}}
                              {{twoPlaceDecimal($transaction->net_total - $transaction->paid)}}
                          </li>
                      </ul>
                  </div>
              </div>
          </div>
        </div> <!-- /.col --> 
      </div>

      <!-- tab section -->
      <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#items" data-toggle="tab">
                      {{ trans('core.purchase_items') }}
                    </a>
                  </li>
                  <li>
                    <a href="#payments" data-toggle="tab">
                      {{ trans('core.payment_history') }}
                    </a>
                  </li>
                  
                  @if(auth()->user()->can('purchase.manage'))
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
                 @include('purchases.partials.purchase_item_list')
                </div>
                <!--sell items table-->

                <!-- Payment list table -->
                <div class="tab-pane animated fadeIn" id="payments">
                  @include('purchases.partials.payment-history')      
                </div>
                <!-- Payment list table ends -->

                <!--Make payment form-->
                <div class="tab-pane animated fadeIn" id="make-payment">
                  @include('purchases.partials.make-payment-form')
                </div>
                <!--Make payment div ends-->
              </div> <!--  tab-content -->
            </div> <!-- nav-tabs-custom -->

        </div> <!-- col -->
      </div> <!--row-->
  </div> <!--panel body-->

  <div class="panel-footer">
    <a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('purchase.index')}}">
      <i class="fa fa-backward"></i> {{trans('core.back')}}
    </a>

    <a class="btn btn-alt btn-warning btn-xs" target="_BLINK" href="{{route('purchase.invoice', $transaction)}}">
      <i class="fa fa-print"></i>
      {{trans('core.print_bill')}}
    </a>
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