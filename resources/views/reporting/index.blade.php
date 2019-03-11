@extends('app')

@section('title')
  {{trans('core.report')}}
@stop

@section('breadcrumb')
  {{trans('core.report')}}
@stop

@section('main-content')

<div class="panel-body">
  <div class="row">

    <!--Product Report-->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt bg-primary">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-cubes"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.product')}} <br>
                  <small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Product Report" data-toggle="modal" data-target="#productModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!--Product Report-->

    <!--Purchase Report-->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt bg-purple">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-bar-chart"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.purchase')}} 
                  <br><small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Purchase Report" data-toggle="modal" data-target="#purchaseModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!--Purchase Report Ends-->

    <!--Sell Report-->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt bg-blue-alt">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-balance-scale"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.sell')}} <br><small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Sales Report" data-toggle="modal" data-target="#sellsModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!--Sell Report Ends-->

    <!-- <div class="col-md-3 hidden">
      <div class="tile-box tile-box-alt bg-green">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-cubes"></i>
              <div class="tile-content">
                <span>
                  Clients <br><small>Report</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Clients Report" data-toggle="modal" data-target="#clientModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div> -->

    <!--Stock Report-->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt bg-warning" >
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-pie-chart"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.stock')}}<br>
                  <small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Stock Report" data-toggle="modal" data-target="#stockModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!--Stock Report Ends-->

    <!-- Category Report -->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt" style="background-color: #ab6666;color: #fff;">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-tag"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.category')}} <br>
                  <small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Category Report" data-toggle="modal" data-target="#categoryModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!-- Category Report Ends -->

    <!-- Subcategory Report -->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt"  style="background-color: #4e7d75;color: #fff;">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-tags"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.subcategory')}} <br>
                  <small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Category Report" data-toggle="modal" data-target="#subcategoryModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!-- Subcategory Report Ends-->

    <!-- Warehouse Report -->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt bg-black" style="margin-top: 5px;">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-industry"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.warehouse')}}
                  <br><small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Warehouse Report" data-toggle="modal" data-target="#warehouseModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!-- Warehouse Report Ends-->

    <!-- Profit Report -->
    <div class="col-md-4">
      <div class="tile-box tile-box-alt" style="margin-top: 5px;background-color: #db3b8a; color: white;">
          <div class="tile-header">
              
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-area-chart"></i>
              <div class="tile-content">
                <span>
                  {{trans('core.profit')}}
                  <br><small>{{trans('core.report')}}</small>
                </span>
              </div>
          </div>
          <a href="#" class="tile-footer tooltip-button" data-placement="bottom" title="View Profit Report" data-toggle="modal" data-target="#profitModal">
              {{trans('core.view_report')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div>
    <!-- Profit Report Ends-->
</div>

<!-- Modal for Purchase-->
  <div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="houseBillModalLabel">
    {!! Form::open(['route'=>'report.purchase']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="houseBillModalLabel">
            {{trans('core.purchase')}} 
            {{trans('core.purchase')}}
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group"> 
            <div class="row">
               {!! Form::label('Branch', 'Branch', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                <select class="form-control selectpicker" name="warehouse_id" data-live-search = true>
                    <option value="all">ALL Branches</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{$warehouse->id}}">
                        {{$warehouse->name}}
                      </option>
                    @endforeach
                  </select>
              </div>
            </div>  
          </div>

          <div class="form-group"> 
            <div class="row">
               {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
              </div>
            </div>  
          </div>

          <div class="form-group">
              <div class="row">
                {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              {{trans('core.close')}}
            </button>
            <button type="submit" class="btn btn-primary">
              {{trans('core.generate_report')}}
            </button>
          </div>
        </div> <!--modal body-->
    </div>
    {!! Form::close() !!}
  </div>
  </div>
  <!-- purchase modal ends here -->

  <!-- Modal for sells-->
  <div class="modal fade" id="sellsModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.sells']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Sells Report</h4>
        </div>
        <div class="modal-body">
          <div class="form-group"> 
            <div class="row">
               {!! Form::label('Branch', 'Branch', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                <select class="form-control selectpicker" name="warehouse_id" data-live-search = true>
                    <option value="all">ALL Branches</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{$warehouse->id}}">
                        {{$warehouse->name}}
                      </option>
                    @endforeach
                  </select>
              </div>
            </div>  
          </div>
          
          <div class="form-group"> 
            <div class="row">
               {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
              </div>
            </div>  
          </div>

           <div class="form-group">
              <div class="row">
                {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!-- sells modal Ends Here -->

  <!-- Product Report -->
  <div class="modal fade" id="productModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.product']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Product Report</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              {!! Form::label('Product', 'Product', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10">   
                <select class="form-control selectpicker" name="product_id" data-live-search = true>
                  <option value="all">ALL PRODUCTS</option>
                  @foreach($products as $product)
                    <option value="{{$product->id}}">
                      {{$product->name}}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="form-group"> 
            <div class="row">
               {!! Form::label('Branch', 'Branch', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                <select class="form-control selectpicker" name="warehouse_id" data-live-search = true>
                    <option value="all">ALL BRANCHES</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{$warehouse->id}}">
                        {{$warehouse->name}}
                      </option>
                    @endforeach
                  </select>
              </div>
            </div>  
          </div>

          <div class="form-group"> 
            <div class="row">
               {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
              </div>
            </div>  
          </div>

          <div class="form-group">
            <div class="row">
              {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10">   
                {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!-- Product Report Ends-->

  <!--Clients Report-->
  <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.client']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Client Report</h4>
        </div>
        <div class="modal-body">
            <div class="form-group"> 
              <div class="row">
                 {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10"> 
                  {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>  
            </div>

           <div class="form-group">
              <div class="row">
                {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                {!! Form::label('Product', 'Product', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  <select class="form-control selectpicker" name="client_id" data-live-search = true>
                    <option value="all">ALL CLIENT</option>
                    @foreach($clients as $client)
                      <option value="{{$client->id}}">
                        {{$client->name}}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!--Clients Report Ends-->

  <!--Stock Report Modal-->
  <div class="modal fade" id="stockModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.stock']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{trans('core.stock_report')}}</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <div class="row">
                {!! Form::label('Product', 'Product', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  <select class="form-control selectpicker" name="product_id" data-live-search = true>
                    <option value="all">ALL PRODUCT</option>
                    @foreach($products as $product)
                      <option value="{{$product->id}}">
                        {{$product->name}}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('core.close')}}</button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!--Stock Report Modal Ends-->

  <!--Category Report-->
  <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.category']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{trans('core.report_category')}}</h4>
        </div>
        <div class="modal-body">
            <div class="form-group"> 
              <div class="row">
                 {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10"> 
                  {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>  
            </div>

           <div class="form-group">
              <div class="row">
                {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                {!! Form::label('Category', 'Category', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  <select class="form-control selectpicker" name="category_id" data-live-search = true>
                    <option value="all">ALL CATEGORIES</option>
                    @foreach($categories as $category)
                      <option value="{{$category->id}}">
                        {{$category->category_name}}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!--Category Report Ends-->

  <!-- Subcategory Report -->
  <div class="modal fade" id="subcategoryModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.subcategory']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Subcategory Report</h4>
        </div>
        <div class="modal-body">
            <div class="form-group"> 
              <div class="row">
                 {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10"> 
                  {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>  
            </div>

           <div class="form-group">
              <div class="row">
                {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                {!! Form::label('sub_category', 'SubCategory', ['class' => 'col-sm-2']) !!} 
                <div class="col-sm-10">   
                  <select class="form-control selectpicker" name="subcategory_id" data-live-search = true>
                    <option value="all">ALL SUB-CATEGORIES</option>
                      @foreach($categories as $category)
                         <optgroup label="{{$category->category_name}}">
                           @foreach($category->subcategories as $subcategory)
                            <option value="{{$subcategory->id}}">
                              {{$subcategory->name}}
                            </option>
                          @endforeach
                        </optgroup>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              {{trans('core.close')}}
            </button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!-- Subcategory Report Ends-->

  <!-- Warehouse Report -->
  <div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.branch']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Branch Report</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              {!! Form::label('Product', 'Product', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10">   
                <select class="form-control selectpicker" name="product_id" data-live-search = true>
                  <option value="all">ALL PRODUCT</option>
                  @foreach($products as $product)
                    <option value="{{$product->id}}">
                      {{$product->name}} 
                      ({{$product->code}})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              {!! Form::label('branch', 'Branch', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10">   
                <select class="form-control selectpicker" name="warehouse_id" data-live-search = true>
                  <option value="all">ALL BRANCHES</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{$warehouse->id}}">
                        {{$warehouse->name}}
                      </option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              {{trans('core.close')}}
            </button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!-- Subcategory Report Ends-->

  <!-- Profit Modal Starts -->
  <div class="modal fade" id="profitModal" tabindex="-1" role="dialog" >
    {!! Form::open(['route'=>'report.profit']) !!}
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Profit Report</h4>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <div class="row">
              {!! Form::label('branch', 'Branch', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10">   
                <select class="form-control selectpicker" name="warehouse_id" data-live-search = true>
                  <option value="all">ALL BRANCHES</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{$warehouse->id}}">
                        {{$warehouse->name}}
                      </option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="form-group"> 
            <div class="row">
               {!! Form::label('From', 'From', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10"> 
                {!! Form::text('from', Request::get('from'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
              </div>
            </div>  
          </div>

          <div class="form-group">
            <div class="row">
              {!! Form::label('To', 'To', ['class' => 'col-sm-2']) !!} 
              <div class="col-sm-10">   
                {!! Form::text('to', Request::get('to'), ['class' => 'form-control dateTime','placeholder'=>"yyyy-mm-dd"]) !!}
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              {{trans('core.close')}}
            </button>
            <button type="submit" class="btn btn-primary">{{trans('core.generate_report')}}</button>
          </div>
        </div>
      </div>
    {!! Form::close() !!}
    </div>
  </div>
  <!-- Profit Report Modal Ends-->
</div>
@stop