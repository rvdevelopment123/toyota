@extends('app')

@section('title')
	{{trans('core.agent_list')}}
@stop

@section('contentheader')
	{{trans('core.agent_list')}}
@stop

@section('breadcrumb')
	{{trans('core.agent_list')}}
@stop


@section('main-content')
   

    <div class="panel-heading">
        @if(count(Request::input()))
            <span class="pull-right">   
                <a 
                    class="btn btn-default btn-alt btn-xs" 
                    href="{{ action('AgentController@getIndex') }}"
                >
                    <i class="fa fa-eraser"></i> 
                    {{ trans('core.clear') }}
                </a>

                <a class="btn btn-primary btn-alt btn-xs" id="searchButton">
                    <i class="fa fa-search"></i> 
                    {{ trans('core.modify_search') }}
                </a>
            </span>
        @else
            <a class="btn btn-primary btn-alt btn-xs pull-right" id="searchButton">
                <i class="fa fa-search"></i>
                {{ trans('core.search') }}
            </a>
        @endif

        <input type="button" class="btn btn-alt bg-purple btn-xs" onclick="showSummary()" id="summaryBtn" value="Summary">
    </div>

    <div class="panel-body">
         <div  id="summaryDiv" style="display: none;">
            <table style="width: 100%; font-weight: bold;" class="table table-bordered" >
                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($total)}} 
                        <span class="font-size-9">{{trans('core.excluding_vat_and_tax')}}</span>
                    </td>
                </tr>

                @if($product_tax > 0)
                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_product_tax')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($product_tax)}}
                    </td>
                </tr>
                @endif

                <tr style="background-color: #F8F9F9;border: 2px solid #ddd; ">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_tax')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($invoice_tax)}}
                    </td>
                </tr>

                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.net_total')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($net_total)}}
                    </td>
                </tr>

                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_cost_price')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($total_cost_price)}}
                        <span class="font-size-9">{{trans('core.excluding_vat_and_tax')}}</span>
                    </td>
                </tr>

                <tr style="background-color: #F8F9F9;border: 2px solid #ddd; ">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                       <b>{{trans('core.total_profit')}}</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($profit)}}
                    </td>
                </tr>
            </table> 
        </div>

        <div class="table-responsive" style="min-height: 300px;" id="tableDIv">
        	<table class="table table-bordered table-striped">
                <thead  class="{{settings('theme')}}">
                    <td class="text-center font-white" width="15%">{{trans('core.date')}}</td>
                    <td class="text-center font-white" width="15%">{{trans('core.invoice_no')}}</td>
                    <td class="text-center font-white" width="15%">{{trans('core.agent')}}</td>
                    <td class="text-center font-white" width="15%">{{trans('core.customer')}}</td>
                    <td class="text-center font-white" width="15%">{{trans('core.net_total')}}</td>
                    <td class="text-center font-white" width="15%">{{trans('core.paid')}}</td>
                    <td class="text-center font-white" width="10%">{{trans('core.actions')}}</td>
                </thead>

                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="text-center tooltip-button" data-placement="bottom" title="{{ carbonDate($transaction->created_at, 'g:i:a') }}" width="12%">
                                {{ carbonDate($transaction->created_at, 'y-m-d') }}
                            </td>
                            <td class="text-center" width="20%">
                                @if($transaction->paid >= $transaction->net_total)
                                   <i class="fa fa-check" style="color: green;"></i>
                                @endif
                                {{$transaction->reference_no}}
                            </td>
                            
                            <td class="text-center" width="15%">
                                {{$transaction->agent->first_name." ".$transaction->agent->last_name}}
                            </td>

                            <td class="text-center" width="15%">
                                {{$transaction->client->name}}
                            </td>

                            <td class="text-center">
                                {{settings('currency_code')}} 
                                {{twoPlaceDecimal($transaction->net_total)}} 
                            </td>

                            <td class="text-center">
                                {{settings('currency_code')}} 
                                {{twoPlaceDecimal($transaction->paid)}} 
                            </td>
                            
                            <td class="text-center">
                                <div class="btn-group">
                                  <button type="button" class="btn btn-info btn-alt btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('core.actions')}} <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="{{route('agents.details', $transaction)}}" >
                                        <i class="fa fa-eye" style="color: #269fed;"></i>
                                        {{trans('core.details')}}
                                        </a>
                                    </li>
                                  </ul>
                                </div>
                            </td>
                        </tr>

                        <!--Modal for delete sells-->
                        <div class="modal fade" id="deleteModal{{$transaction->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        {!! Form::open(['route' => ['sell.delete', $transaction], 'method' => 'delete' ]) !!}
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">
                                    Are you sure to delete the sell #<b>{{$transaction->reference_no}}</b>?
                                </h4>
                              </div>
                              <div class="modal-body">
                                <b>{{trans('core.agent_details')}}:</b>
                                <br />
                                <ul style="font-weight: lighter;">
                                    @foreach($transaction->sells as $sells)
                                        <li>{{$sells->product->name}} - {{$sells->quantity}} {{$sells->product->unit}}</li>
                                    @endforeach
                                </ul>
                                <hr>
                                <b>Total: {{$transaction->total}} {{settings('currency_code')}}</b>
                                <br>
                                <b>Paid: {{$transaction->paid}} {{settings('currency_code')}}</b>
                                <br /><br />

                                <div class="alert alert-success alert-red">If you delete this sell, all the transactions of this sale will also be deleted.</div>
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
                                <button type="submit" class="btn btn-danger">{{trans('core.delete')}}</button>
                              </div>
                            </div>
                          </div>
                        {!! Form::close() !!}
                        </div>
                        <!--Modal Div Ends-->
                    @endforeach
                </tbody>
            </table>

            <!--Pagination-->
            <div class="pull-right">
                {{ $transactions->links() }}
            </div>
            <!--Ends-->

             
        </div>
    </div>

    <div class="pull-right" id="tableFooterDIv" style="display: none;">
        <small>
        <b>*Note:</b> Profit Calculation has been done without Vat/Tax
        </small>
    </div>

    <!-- Sell search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.sell') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.invoice_no')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('invoice_no', Request::get('invoice_no'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.customer')}}
                        </label>
                        <div class="col-sm-9">
                            {{--  {!! Form::select('customer', $customers, Request::get('customer'), ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => 'Please select a customer']) !!}  --}}
                            <select name="customer" class="form-control selectpicker" id="customer" data-live-search="true" placeholder="Please select a customer">
                                <option value="">Please select a customer</option>
                                @foreach ($customers as $key => $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->first_name . " " . $customer->last_name . " " . $customer[$key]['user_role'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.agent')}}
                        </label>
                        <div class="col-sm-9">
                            <select name="agent" class="form-control selectpicker" id="agent" data-live-search="true" placeholder="Please select an agent">
                                <option value="">Please select an agent</option>
                                @foreach ($agents as $key => $agent)
                                    @if (!empty($agent->user_role->role))
                                        <option value="{{ $agent->id }}">{{ $agent->first_name . " " . $agent->last_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3" @if(!rtlLocale()) style="text-align: left;" @endif>{{trans('core.type')}}</label>
                        <div class="col-sm-9">
                            {!! Form::select('type', ['0' => 'ALL', 'pos' => 'POS'], Request::get('type'), ['class' => 'form-control selectpicker']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.from')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.to')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                        </div>
                    </div>
                                                             
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
                    {!! Form::submit('Search', ['class' => 'btn btn-primary', 'data-disable-with' => trans('core.searching')]) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- search modal ends -->

@stop

@section('js')
    @parent
    <script>
        $(function() {
            $('#searchButton').click(function(event) {
                event.preventDefault();
                $('#searchModal').modal('show')
            });
        })

        function showSummary() {
            var x = document.getElementById("summaryDiv");
            var y = document.getElementById("tableDIv");
            var z = document.getElementById("tableFooterDIv");
            var elem = document.getElementById("summaryBtn");
            if (elem.value=="Summary") elem.value = "Sales List";
            else elem.value = "Summary";
            if (x.style.display === "none") {
                x.style.display = "block";
                z.style.display = "block";
                y.style.display = "none";
            } else {
                x.style.display = "none";
                z.style.display = "none";
                y.style.display = "block";
            }
        }
    </script>

@stop