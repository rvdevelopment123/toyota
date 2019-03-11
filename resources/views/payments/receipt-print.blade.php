@extends('printer')

<style>
  thead tr th{
    text-align: center;
  }
</style>

@section('main-content')
  <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h4 class="page-header" >
            @if(!empty(settings('site_logo')))
              <img src="{!! asset('uploads/site/'.settings('site_logo')) !!}" style="height: 100px; width: auto;">
            @else
                <p>{{settings('site_name')}}</p>
            @endif
            <small>
              {{ trans('core.phone') }}:
              {{ bangla_digit(settings('phone'))}}
              <br>
              {{ trans('core.email') }}: 
              {{ bangla_digit(settings('email'))}}
            </small>
          </h4>
        </div>
        <!-- /.col -->
      </div>

      <!-- info row -->
      <div class="row invoice-info" >
        <div class="col-sm-4 invoice-col">
          @if($payment->type == 'debit')
            <b>{{ trans('core.received_from') }}: </b>
          @else
            <b>{{ trans('core.received_by') }}: </b>
          @endif 
          <br>
          {{$payment->client->name}}<br>
          {{$payment->client->company_name}}
        </div>
        
        <div class="col-sm-4 invoice-col">
          <b>{{ trans('core.address') }}:</b> 
          <address>
             {{$payment->client->address}}
          </address>
        </div>
        
        <div class="col-sm-3 invoice-col" style="margin-left: 20px; text-align: left;">
          <b>{{trans('core.receipt_no')}}</b> #{{ref($payment->id)}}<br>
          <b>{{trans('core.invoice_no')}}</b> #{{$payment->reference_no}}<br>
          <b>{{ trans('core.date') }}: </b>{{carbonDate($payment->created_at, 'y-m-d')}}
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <br>
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>
                  {{trans('core.note')}} 
              </th>

              <th style="text-align: center;">
                @if($payment->type == 'credit')
                  {{ trans('core.received_amount') }}
                @else
                  {{ trans('core.amount') }}
                @endif
              </th>
            </tr>
            </thead>

            <tbody>
              <td style="height: 100px;">
                {{ $payment->note }} 
              </td> 

              <td style="height: 100px;">
                {{settings('currency_code')}}
                {{ twoPlaceDecimal($payment->amount) }} 
              </td>       
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12" style="margin-left: 20px;">
          <span class="amount-in-words">
            {{trans('core.amount')}} (In Words)
              <br>
              <b>{{settings('currency_code')}} {{numberFormatter($payment->amount)}}</b>
            <br>
            <br>
          </span>
        </div>
      </div>

      <br><br>
      <div class="row">
        <div class="col-sm-5" style="margin-left: 20px;">
              
          
        </div>

        <div class="col-sm-offset-4 col-sm-4 pull-right" >
            <span>&nbsp;</span>
        <br><br>
        {{trans('core.authorized_signature_receipt')}}
        </div>
      </div>

    </section>
  
@stop