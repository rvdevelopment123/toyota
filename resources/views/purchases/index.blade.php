@extends('app')

@section('title')
	{{trans('core.purchase_list')}}
@stop

@section('contentheader')
	{{trans('core.purchase_list')}}
@stop

@section('breadcrumb')
	{{trans('core.purchase_list')}}
@stop


@section('main-content')
    <div class="panel-heading" >
        @if (auth()->user()->can('purchase.create'))
            <a href="{{route('purchase.item')}}" class="btn btn-success btn-alt btn-xs" style="border-radius: 0px !important;" >
                <i class="fa fa-plus"></i> 
                {{trans('core.add_new_purchase')}}
            </a>
        @endif

        @if(count(Request::input()))
            <span class="pull-right">   
                <a class="btn btn-default btn-alt btn-xs" href="{{ action('PurchaseController@getIndex') }}">
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
            <table style="width: 100%;" class="table table-bordered">
                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.net_total')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($transactions->sum('net_total'))}}
                    </td>
                </tr>

                <tr style="background-color: #F8F9F9;border: 2px solid #ddd; ">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.total_tax')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($transactions->sum('total_tax'))}}
                    </td>
                </tr>

                <tr style="background-color: #F8F9F9; border: 1px solid #ddd;">
                    <td @if(!rtlLocale()) style="text-align: right;" @endif>
                        <b>{{trans('core.net_total')}} :</b>
                    </td>
                    <td @if(rtlLocale()) style="text-align: right;" @endif>
                        {{settings('currency_code')}}
                        {{twoPlaceDecimal($transactions->sum('net_total'))}}
                    </td>
                </tr>
            </table>
        </div>

        <div class="table-responsive" style="min-height: 250px;" id="tableDIv">
        	<table class="table table-bordered table-striped">
                <thead class="{{settings('theme')}}">
                    <td class="text-center font-white">{{trans('core.date')}}</td>
                    <td class="text-center font-white">{{trans('core.bill_no')}}</td>
                    <td class="text-center font-white">{{trans('core.supplier')}}</td>
                    <td class="text-center font-white">{{trans('core.net_total')}}</td>
                    <td class="text-center font-white">{{trans('core.paid')}}</td>
                    <td class="text-center font-white">{{trans('core.actions')}}</td>
                </thead>

                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="text-center tooltip-button" data-placement="bottom" title="{{ carbonDate($transaction->created_at, 'g:i:a') }}">
                                {{ carbonDate($transaction->created_at, 'y-m-d') }}
                            </td>
                            
                            <td class="text-center">
                                @if($transaction->paid >= $transaction->net_total)
                                   <i class="fa fa-check" style="color: green;"></i>
                                @endif
                                {{$transaction->reference_no}}
                            </td>

                            <td class="text-center">
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
                                    {{trans('core.actions')}} 
                                    <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="{{route('purchase.details', $transaction)}}">
                                            <i class="fa fa-eye" style="color: #269fed;"></i>
                                            {{trans('core.details')}}
                                        </a> 
                                    </li>
                                    <li>
                                        <a target="_BLINK" href="{{route('purchase.invoice', $transaction)}}">
                                            <i class="fa fa-print" style="color: #edb426;"></i>
                                            {{trans('core.bill')}}
                                        </a>
                                    </li>
                                    
                                    <li>
                                        @if(auth()->user()->can('purchase.manage'))
                                            <!--purchase delete button trigger-->
                                            <a href="#" data-id="{{$transaction->id}}" data-name="{{$transaction->reference_no}}" class="btn-delete">
                                               <i class="fa fa-trash" style="color: red;"></i>
                                               {{trans('core.delete')}}
                                            </a>
                                            <!--delete button trigger ends--> 
                                        @endif 
                                    </li>

                                    <!-- <li>
                                        <a href="">
                                            <i class="fa fa-usd" style="color: #89108f;"></i>
                                            {{trans('core.payment')}}
                                        </a>
                                    </li> -->
                                  </ul>
                                </div>
                            </td>
                        </tr>
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

    <!-- Purchases search modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['class' => 'form-horizontal']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> {{ trans('core.search').' '.trans('core.purchase') }}</h4>
                </div>

                <div class="modal-body">                  
                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.bill_no')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::text('bill_no', Request::get('bill_no'), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    @if(settings('enable_purchaser'))
                    <div class="form-group">
                        <label class="col-sm-3" @if(rtlLocale()) style="text-align: left;" @endif>
                            {{trans('core.supplier')}}
                        </label>
                        <div class="col-sm-9">
                            {!! Form::select('supplier', $suppliers, Request::get('supplier'), ['class' => 'form-control selectpicker', 'data-live-search' => 'true', 'placeholder' => 'Please select a supplier']) !!}
                        </div>
                    </div>
                    @endif

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

     <!-- Delete Modal Starts -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
      {!! Form::open(['route'=>'purchase.delete','method'=>'POST']) !!}
      {!! Form::hidden('id',null,['id'=>'deleteExpenseInput']) !!}
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
                Delete Purchase 
                <span id="deleteExpenseName" ></span>
            </h4>
          </div>
          <div class="modal-body">
            <h3>Are you sure you want to delete this purchase?</h3>
            <br>
            <h4 style="color: red;">
                Note: If you delete this purchase, all the transactions of this purchase will also be deleted &amp; product will also adjusted.
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
    <!-- Modal Ends -->
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

        $(document).ready(function(){
            $('.btn-delete').on('click',function(e){
                e.preventDefault();
                $('#deleteModal').modal();
                $('#deleteExpenseInput').val($(this).attr('data-id'));
                $('#deleteExpenseName').val($(this).attr('data-name'));
            })
        });

        function showSummary() {
            var x = document.getElementById("summaryDiv");
            var y = document.getElementById("tableDIv");
            var elem = document.getElementById("summaryBtn");
            if (elem.value=="Summary") elem.value = "Purchase List";
            else elem.value = "Summary";
            if (x.style.display === "none") {
                x.style.display = "block";
                y.style.display = "none";
            } else {
                x.style.display = "none";
                y.style.display = "block";
            }
        }
    </script>

@stop