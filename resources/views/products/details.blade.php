@extends('app')

@section('title')
  {{$product->name}}
@stop

@section('contentheader')
  {{$product->name}} Details
@stop

@section('breadcrumb')
  <a href="{{route('product.index')}}">Products</a>
  <li>{{$product->name}}</li>
@stop

@section('main-content')

    <!-- Main content -->
    <div class="panel-body">

      <div class="row">
        <div class="col-md-3">
          <div class="box box-primary">
            <div class="box-body box-profile">
              @if(!empty($product->image))
                <p>
                    <a href="{{url('uploads/products/' . $product->image)}}">
                        <abbr title="Show Product Image">
                            <img src="{!! asset('uploads/products/'. $product->image)!!}" 
                                class="img-thumbnail img-responsive" alt="" >
                        </abbr>
                    </a>
                </p>
              @else
                <img src="{!! asset('uploads/products/no-product-image.jpg' )!!}" class="img-thumbnail img-responsive" alt="" >
              @endif

              <h3 class="profile-username text-center">{{ $product->name }}</h3>

              <p class="text-muted text-center">
                {{$product->category->category_name}} 
                <i class="fa fa-arrow-right"></i> 
                  @if($product->subcategory)
                    {{$product->subcategory->name}}
                  @endif
                
              </p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>{{trans('core.created')}}:</b> 
                  <span class="pull-right">{{carbonDate($product->created_at, 'y-m-d')}}</span>
                </li>
                <li class="list-group-item">
                  <b>{{trans('core.status')}}:</b> 
                  <span class="pull-right">
                    @if($product->status == 1)
                      <span style="color: green;" >
                        <i class="fa fa-check"></i> Active
                      </span>
                    @else
                      <span style="color: red;"><i class="fa fa-times"></i> Inactive</span>
                    @endif
                  </span>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#details{{$product->id}}" data-toggle="tab">
                  {{trans('core.details')}}
                </a>
              </li>
              <li>
                  <a href="#timeline{{$product->id}}" data-toggle="tab">{{trans('core.purchase_history')}}
                  </a>
              </li>
              <li>
                <a href="#sell{{$product->id}}" data-toggle="tab">
                  {{trans('core.sell_history')}}
                </a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="details{{$product->id}}">
                <form>
                @if($product->details)
                <div class="form-group">
                  <label>{{trans('core.details')}}</label>
                  <input type="text" class="form-control" value="{{$product->details}}" disabled="true">
                </div>
              @endif

              <div class="row">
                <div class="col-md-12">
                  <label>{{trans('core.cost_price')}}</label>
                  <input type="text" class="form-control" value="{{settings('currency_code')}} {{$product->cost_price}}" disabled="true">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                    <label>{{trans('core.mrp')}}</label>
                    <input type="text" class="form-control" value="{{settings('currency_code')}} {{$product->mrp}} " disabled="true">
                </div>

                <div class="col-md-6">
                    <label>{{trans('core.minimum_retails_price')}}</label>
                    <input type="text" class="form-control" value="{{settings('currency_code')}} {{$product->minimum_retail_price}} " disabled="true">
                </div>
              </div>
            </form>
            <hr>

            <h4>{{trans('core.stock')}}</h4>
            <hr>
            <table class="table table-bordered">
              <tr>
                <td class="text-center">{{trans('core.opening_stocks')}} (+)</td>
                <td class="text-center">@if($product->opening_stock == null) 0 @else {{$product->opening_stock}} @endif {{$product->unit}}</td>
              </tr>

              <tr>
                <td class="text-center">{{trans('core.total_purchase')}} (+)</td>
                <td class="text-center">{{$product->purchases->sum('quantity')}} {{$product->unit}}</td>
              </tr>

              <tr>
                <td class="text-center">{{trans('core.total_sell')}} (-)</td>
                <td class="text-center">{{$product->sells->sum('quantity')}} {{$product->unit}}</td>
              </tr>

              <tr class="bg-khaki">
                <td class="text-center"><b>{{trans('core.in_stock')}}</b></td>
                <td class="text-center"><b>@if($product->quantity == null) 0 @else {{$product->quantity}} @endif {{$product->unit}}</b></td>
              </tr>
              
            </table>

            <div class="form-group">
                <label class="tooltip-button" title="Stock Integrity is ok if the summation of opening stocks and purchase items is equal to sales items">
                    {{trans('core.stock_integrity')}}
                </label>
                <input type="text" class="form-control" value="@if(($product->purchases->sum('quantity') + $product->opening_stock) - $product->quantity -  $product->sells->sum('quantity') == 0) Ok  @else Not Ok @endif" disabled="true"> 
            </div>
              </div><!-- /.tab-pane -->

            <div class="tab-pane" id="timeline{{$product->id}}">
              <!-- <div class="input-group col-md-3 pull-right" >
                  <div class="input-group-addon">
                    <i class="fa fa-search"></i>
                  </div>
                <input type="text" class="form-control inline pull-right" id="search_field" placeholder="Search..">
              </div> -->
              <table class="table table-bordered">
                <tr class="bg-khaki">
                    <td class="text-center">{{trans('core.date')}}</td>
                    <td class="text-center">{{trans('core.supplier')}}</td>
                    <td class="text-center">{{trans('core.quantity')}}</td>
                </tr>

                <tbody style="background-color: #fff;" id="myTable">
                  @foreach($product->purchases as $purchase)
                    <tr>
                      <td class="text-center">{{carbonDate($purchase->created_at, 'h:i:s')}}</td>
                      <td class="text-center">{{$purchase->client->name}}</td>
                      <td class="text-center">{{$purchase->quantity}}</td>
                    </tr>
                  @endforeach
                </tbody>

              </table>
            </div><!-- /.tab-pane -->

              <div class="tab-pane" id="sell{{$product->id}}">
                @if($product->sells->count() == 0)
                    No sell 
                @else
                  <table class="table table-bordered">
                    <tr class="bg-khaki">
                        <td class="text-center">{{trans('core.date')}}</td>
                        <td class="text-center">{{trans('core.customer')}}</td>
                        <td class="text-center">{{trans('core.quantity')}}</td>
                    </tr>

                      @foreach($product->sells as $sell)
                        <tr>
                          <td class="text-center">{{carbonDate($sell->created_at, 'h:i:s')}}</td>
                          <td class="text-center">{{$sell->client->name}}</td>
                          <td class="text-center">{{$sell->quantity}}</td>
                        </tr>
                      @endforeach
                  </table>
                @endif
              </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
          </div><!-- /.nav-tabs-custom -->
        </div><!-- /.col -->
      </div>
      <!-- /.row -->

    </div>

    <div class="panel-footer">  
        <span style="padding: 10px;">
        
        </span> 
      <a class="btn btn-border btn-alt border-primary font-black btn-xs pull-right" href="{{route('product.index')}}">
            <i class="fa fa-backward"></i> {{trans('core.back')}}
        </a>
    </div>
@stop


@section('js')
    @parent
    <!-- <script>
        $('#search_field').on('keyup', function() {
          var value = $(this).val();
          var patt = new RegExp(value, "i");

          $('#myTable').find('tr').each(function() {
            if (!($(this).find('td').text().search(patt) >= 0)) {
              $(this).not('.myHead').hide();
            }
            if (($(this).find('td').text().search(patt) >= 0)) {
              $(this).show();
            }
          });

        });

    </script> -->
@stop