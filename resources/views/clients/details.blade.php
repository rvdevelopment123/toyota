@extends('app')

@section('contentheader')
  {{trans('core.details')}}: {{$client->name}} 
@stop

@section('breadcrumb')
    @if($client->client_type == 'purchaser')
      <a href="{{route('purchaser.index')}}"> 
        {{trans('core.supplier_list')}}
      </a>
    @else
      <a href="{{route('client.index')}}"> 
        {{trans('core.customer_list')}}
      </a>
    @endif 
    &nbsp;&nbsp;>&nbsp;
    {{trans('core.details')}}: {{$client->name}} 
@stop

@section('main-content')

  <div class="panel-body">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12" >

        <div class="panel-layout">
            <div class="panel-box">
                <div class="panel-content bg-primary">
                    <div class="image-content font-white">
                        <div class="center-vertical">
                            <div class="meta-box center-content">
                                <h3 class="meta-heading">{{$client->company_name}}</h3>
                                <h4 class="meta-subheading">{{$client->name}}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-content bg-white">
                    <ul class="list-group list-group-separator mrg0A row list-group-icons">
                        <li class="col-md-12 list-group-item">
                          {{trans('core.phone')}} : {{$client->phone}}
                        </li>

                        @if($client->email)
                        <li class="col-md-12 list-group-item">
                          {{trans('core.email')}}: {{$client->email}}
                        </li>
                        @endif

                        @if($client->address)
                        <li class="col-md-12 list-group-item">
                          {{trans('core.address')}}: {{$client->address}}
                        </li>
                        @endif

                        @if($client->account_no)
                        <li class="col-md-12 list-group-item"> 
                            {{trans('core.account_no')}}: {{$client->account_no}}
                        </li>
                        @endif
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
                                <h3 class="meta-heading">
                                  @if($client->client_type == 'purchaser')
                                    {{trans('core.total_purchase')}}
                                  @else
                                     {{trans('core.total_sell')}}
                                  @endif

                                </h3>
                                <h4 class="meta-subheading">
                                   &nbsp;
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="panel-content bg-white" style="min-height: 190px;">
                    <ul class="list-group list-group-separator mrg0A row list-group-icons">
                       <li class="col-md-12 list-group-item">
                          <h3>
                            @if($client->client_type == 'purchaser')
                                {{bangla_digit($client->purchases->sum('quantity'))}}
                                {{trans('core.entity')}} {{trans('core.product')}}
                            @else
                                {{bangla_digit($client->sells->sum('quantity'))}}
                                {{trans('core.product')}}
                            @endif
                          </h3>
                        </li>

                        <li class="col-md-12 list-group-item">
                            <!-- <a href="{{route('client.payment.list', $client)}}" >
                                <i class="glyph-icon font-red fa-arrow-circle-right"></i>
                                {{trans('core.details')}}
                            </a> -->
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
                                <h3 class="meta-heading">
                                  {{trans('core.total_invoice')}}
                                </h3>
                                <h4 class="meta-subheading">
                                  &nbsp;
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-content bg-white" style="min-height: 190px;">
                    <ul class="list-group list-group-separator mrg0A row list-group-icons">
                        <li class="col-md-12 list-group-item">
                            {{bangla_digit($total_invoice)}}
                        </li>

                        <li class="col-md-12 list-group-item">
                            <a href="{{route('client.invoices', $client)}}" >
                                <i class="glyph-icon font-red fa-arrow-circle-right"></i>
                                {{trans('core.details')}}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
      </div> <!-- /.col -->     
    </div>
    <!-- ./row -->

    <!--second row-->
    <div class="row">
      <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                  <a href="#transaction_details" data-toggle="tab">
                    {{ trans('core.transaction_details') }}
                  </a>
                </li>

                <li>
                  <a href="#payments" data-toggle="tab">
                    {{ trans('core.payment_history') }}
                  </a>
                </li>

                <li>
                  <a href="#make-payment" data-toggle="tab">
                    {{ trans('core.make_payment') }}
                  </a>
                </li>
            </ul>
            <!--nav tab ends-->

            <div class="tab-content">
              <!--transaction history-->
              <div class="active tab-pane animated fadeIn" id="transaction_details" style="padding-bottom: 50px;">
                @include('clients.partials.transaction-history')
              </div>
              <!--transaction history ends-->

              <!-- Payment list table -->
              <div class="tab-pane animated fadeIn" id="payments">
                @include('clients.partials.payment-list')       
              </div>
              <!-- Payment list table ends -->

              <!--Make payment form-->
              <div class="tab-pane animated fadeIn" id="make-payment">
                @include('clients.partials.make-payment')
              </div>
              <!--Make payment div ends-->
            </div> <!--  tab-content -->
          </div> <!-- nav-tabs-custom -->
      </div> <!-- col -->
    </div> 
    <!-- ./second row ends--> 
  </div>

  <div class="panel-footer">
    <span style="padding: 10px;">
        
    </span>
     
    <a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" @if($client->client_type == 'purchaser') 
          href="{{route('purchaser.index')}}" 
        @else 
          href="{{route('client.index')}}" 
        @endif>
      <i class="fa fa-backward"></i> {{trans('core.back')}}
    </a>
  </div>
@stop

@section('js')
  @parent
  <script>
    function validateForm() {   
        var x = parseFloat(document.forms["myForm"]["amount"].value);
        var y = parseFloat("{{$total_due}}");
        if (x > y) {
            document.getElementById("message").innerHTML = "Paid amount ({{settings('currency_code')}} "+ x + " ) can't be greater than Due Amount ({{settings('currency_code')}} " + y + " )";
            return false;
        }
    }

    $(function() {
      $('input').on('input', function() {
        match = (/(\d{0,100})[^.]*((?:\.\d{0,2})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
        this.value = match[1] + match[2];
      });
    });
</script>

@stop