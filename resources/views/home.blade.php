@extends('app')

@section('htmlheader_title')
    Home
@endsection

@section('loader')
<div id="loading" style="top: -250px !important;">
  <div class="spinner">
      <div class="bounce1"></div>
      <div class="bounce2"></div>
      <div class="bounce3"></div>
  </div>
</div>
@stop

@section('main-content')

<div class="panel-body">
  
  <!--homepage design 1 (Chart Boxes)-->
  @if(settings('dashboard') == 'chart-box')
  <div class="row">
    <div class="col-md-4" >
        <div class="dashboard-box dashboard-box-chart bg-white content-box">
            <div class="content-wrapper">
                <div class="header" style="font-size: 20px !important;">
                    {{settings('currency_code')}}
                    {{bangla_digit($todays_stats['total_selling_price'])}}
                    <small style="font-size: 12px !important;">
                      ({{trans('core.today')}})
                    </small>
                    <span>Sales graph<b> of last 7 Days</b></span>
                </div>
                <div class="bs-label bg-primary">Sale</div>
                <div class="center-div sparkline-big-alt">{{ $lastSevenDaySells}}</div>
                <div class="row list-grade">
                  @foreach($daynames as $dayname)
                    <div class="col-md-2" 
                      @if($dayname == Carbon\Carbon::now()->format('D'))
                        style="font-weight: bolder; color: blue;"  
                      @endif
                    >
                        {{$dayname}}
                    </div>
                  @endforeach
                    
                </div>
            </div>
            <div class="button-pane" style="background-color: #00ACA8;">
                <div class="size-md float-left">
                    <a href="{{route('invoice.today')}}" style="color: white;">
                        View today's invoices
                    </a>
                </div>
                <a class="btn btn-default float-right tooltip-button"  href="{{route('invoice.today')}}" class="tooltip-button" data-placement="bottom" title="View Invoices">
                    <i class="glyph-icon icon-caret-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
      <div class="dashboard-box dashboard-box-chart bg-white content-box">
          <div class="content-wrapper">
              <div class="header" style="font-size: 20px !important;">
                  {{settings('currency_code')}}
                  {{bangla_digit($todays_stats['total_purchasing_price'])}}
                  <small style="font-size: 12px !important;">
                    ({{trans('core.today')}})
                  </small>
                  <span>Purchase graph<b> of last 7 Days</b></span>
              </div>
              <div class="bs-label bg-purple">Purchase</div>
              <div class="center-div sparkline-big-alt">{{$lastSevenDayPurchases}}</div>
              <div class="row list-grade">
                  @foreach($daynames as $dayname)
                    <div class="col-md-2" 
                      @if($dayname == Carbon\Carbon::now()->format('D'))
                        style="font-weight: bolder; color: blue;"  
                      @endif
                    >
                      {{$dayname}}
                    </div>
                  @endforeach
              </div>
          </div>
          <div class="button-pane" style="background-color: #984DFF;">
              <div class="size-md float-left">
                  <a href="{{route('bill.today')}}" style="color: #FFF;">
                      View today's bills
                  </a>
              </div>
              <a href="{{route('bill.today')}}" class="btn btn-default float-right tooltip-button" data-placement="top" title="" data-original-title="View Bills">
                  <i class="glyph-icon icon-caret-right"></i>
              </a>
          </div>
      </div>
    </div>

    <div class="col-md-4">
        <div class="dashboard-box dashboard-box-chart bg-white content-box">
            <div class="content-wrapper">
                <div class="header" style="font-size: 20px !important;">
                    {{settings('currency_code')}}
                    {{bangla_digit($todays_stats['total_transactions_today'])}}
                    <small style="font-size: 12px !important;">
                      ({{trans('core.today')}})
                    </small>
                    <span>Transaction graph <b> of last 7 Days </b></span>
                </div>
                <div class="bs-label bg-green">Transaction</div>
                <div class="center-div sparkline-big-alt">{{$lastSevenDayTransactions}}</div>
                <div class="row list-grade">
                    @foreach($daynames as $dayname)
                      <div class="col-md-2" 
                        @if($dayname == Carbon\Carbon::now()->format('D'))
                          style="font-weight: bolder; color: blue;"  
                        @endif
                      >
                        {{$dayname}}
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="button-pane" style="background-color: #2ECC71;">
                <div class="size-md float-left">
                    <a href="{{route('transactions.today')}}" style="color: white;" >
                        View today's transactions
                    </a>
                </div>
                <a href="{{route('transactions.today')}}" class="btn btn-default float-right tooltip-button" data-placement="bottom" title="View Transaction">
                    <i class="glyph-icon icon-caret-right"></i>
                </a>
            </div>
        </div>
    </div>
  </div>
  @endif
  <!-- Home design 1 ends -->

  <!-- Home design 2 (Tile box)-->
  @if(settings('dashboard') == 'tile-box')
  <div class="row ">
    
    <!--Total invoices today-->
    <div class="col-md-4 col-sm-6 col-xs-12 animated headShake">

      <div class="tile-box tile-box-alt {{settings('theme')}} font-white">
          <div class="tile-header">
              {{trans('core.today_sells')}}
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-shopping-cart"></i>
              <div class="tile-content">
                <span>
                  {{bangla_digit($todays_stats['total_selling_quantity'])}}
                  {{trans('core.product')}}
                  <small>
                  (
                    {{settings('currency_code')}}
                    {{bangla_digit($todays_stats['total_selling_price'])}}
                  )
                  </small>
                </span>
              </div>
          </div>
          <a href="{{route('invoice.today')}}" class="tile-footer tooltip-button" data-placement="bottom" title="View Invoices">
              View Invoices
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>

    </div> <!-- /.col -->
    <!--Ends-->


    <!--Total bills for today-->
    <div class="col-md-4 col-sm-6 col-xs-12 animated headShake">

      <div class="tile-box tile-box-alt bg-blue font-white">
          <div class="tile-header">
              {{trans('core.today_purchases')}}
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-ship"></i>
              <div class="tile-content">
                <span>
                  {{bangla_digit($todays_stats['total_purchasing_quantity'])}}
                  {{trans('core.product')}}
                  <small>
                  (
                    {{settings('currency_code')}}
                    {{bangla_digit($todays_stats['total_purchasing_price'])}}
                  )</small>
                </span>
              </div>
          </div>
          <a href="{{route('bill.today')}}" class="tile-footer tooltip-button" data-placement="bottom" title="View Bills">
              View Bills
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>

    </div> <!-- /.col -->
    <!--Total bill for today ends-->


    <!--Total cash received today-->
    <div class="col-md-4 col-sm-6 col-xs-12 animated headShake">
    
      <div class="tile-box tile-box-alt {{settings('theme')}} font-white">
          <div class="tile-header">
              {{trans('core.total_transactions_today')}}
          </div>
          <div class="tile-content-wrapper">
              <i class="glyph-icon fa fa-money"></i>
              <div class="tile-content">
                <span>
                  {!! settings('currency_code')!!} 
                  @if($todays_stats['total_transactions_today'] != null)
                    {{bangla_digit($todays_stats['total_transactions_today'])}}
                  @else
                    {{bangla_digit(0)}}
                  @endif
                </span>
                <small>&nbsp;</small>
              </div>
          </div>
          <a href="{{route('transactions.today')}}" class="tile-footer tooltip-button" data-placement="bottom" title="{{trans('core.view_details')}}">
              {{trans('core.view_details')}}
              <i class="glyph-icon icon-arrow-right"></i>
          </a>
      </div>
    </div> <!-- /.col -->

    <!--Ends-->
  </div>
  @endif

  <!-- Home design 2 ends -->

  <hr />


  <!-- Chart -->
  <div class="row">
    <div class="col-md-6 col-xs-12" >
        <div class="dashboard-box dashboard-box-chart bg-white content-box">
            <div class="content-wrapper">
                <div class="header">Sell Vs Purchase</div>
                <canvas id="sellsvspurchase"></canvas>
            </div>
        </div>
    </div>


    <div class="col-md-6 col-xs-12" >
      <div class="dashboard-box dashboard-box-chart bg-white content-box">
          <div class="content-wrapper">
            <div class="header">Stock Value</div>
            <canvas id="stockChart" ></canvas>
          </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 col-xs-12">
      <div class="dashboard-box dashboard-box-chart bg-white content-box">
          <div class="content-wrapper">
            <div class="header">Hot Products</div>
            <canvas id="productChart"></canvas>
          </div>
      </div>
    </div>
    
    <div class="col-md-6 col-xs-12">
      <div class="dashboard-box dashboard-box-chart bg-white content-box">
          <div class="content-wrapper">
            <div class="header">Profit</div>
            @if(auth()->user()->can('profit.graph'))
            <canvas id="profit"></canvas>
            @endif
          </div>
      </div>
    </div>

  </div>
  <!-- CHART ENDS--> 

  </div>

@endsection


@section('js')
  @parent
  <script type="text/javascript" src="/assets/js-core/sparklines.js"></script>
  <script type="text/javascript" src="/assets/js-core/sparklines-demo.js"></script>
  <script src="/assets/js-core/Chart.min.js"></script>
  <script src="/assets/js-core/chartjs-tooltip-show.js"></script>
  <script>
    
  /*Hot Products*/
    var ctx = document.getElementById("productChart");
    var obj = <?php echo json_encode($top_product_name); ?>;
    var obj2 = <?php echo json_encode($selling_quantity); ?>;
    var productChart = new Chart(ctx, {
        type: 'bar',
        data: {         
            labels: obj,
            datasets: [{
                label: 'Total Sell',
                data: obj2,
                backgroundColor: [
                    'rgba(46, 204, 113, 0.4)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    
                ],
                borderColor: [
                    'rgba(46, 204, 113, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            /*showAllTooltips: true,*/
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
            tooltips: {
              enabled: true,
                callbacks: {
                    label: function(tooltipItems, data) { 
                        return tooltipItems.yLabel + '';
                    }
                }
            }
        }
    });
    /* Hot Products ends */


    /*Pie chart for stock*/
    var ctx2 = document.getElementById("stockChart");
    var stock = <?php echo json_encode($stock); ?>;
    var stockChart = new Chart(ctx2, {
        type: 'pie',
        options: {
          /*showAllTooltips: true,*/
        },
        data: {         
            labels: [
                "By cost",
                "By price",
                "Profit Estimate",
            ],
            datasets: [{
                data: stock,
                backgroundColor: [
                    "#AF7AC5",
                    "#5499C7",
                    '#52BE80'
                ],
                hoverBackgroundColor: [
                    "#6C3483",
                    "#1A5276",
                    "#196F3D"
                ]
            }]
        },
    });
    /*stock pie chart ends*/


    // Sell vs Purchase Chart
    var ctx3 = document.getElementById("sellsvspurchase");
    var months = <?php echo json_encode(array_reverse($months)); ?>;
    var sells = <?php echo json_encode(array_reverse($sells)); ?>;
    var purchases = <?php echo json_encode(array_reverse($purchases)); ?>;
    var chart = new Chart(ctx3, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: ["Sells"],
          backgroundColor: "#039A93",
          data: sells
        }, {
          label: ["Purchases"],
          backgroundColor: "#58D68D",
          data: purchases
        }]
      },

      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        },
        tooltips: {
          enabled: true,
            callbacks: {
                label: function(tooltipItems, data) { 
                    return '{{settings('currency_code')}} ' + tooltipItems.yLabel;
                }
            }
        },

        legend:{
          enabled:true
        },
      }
    });

    //ends

    //profit graph chart
        var profits = <?php echo json_encode(array_reverse($last_six_months_profit)); ?>;
        

        var config = {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: "Profit",
                    data: profits,
                    lineTension: 0,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                hover: {
                    mode: 'label'
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Last 6 Month Profit\'s Graph'
                }
            }
        };

        $.each(config.data.datasets, function(i, dataset) {
            var background = "#4BC0C0";
            dataset.borderColor = background;
            dataset.backgroundColor = background;
            dataset.pointBorderColor = "green";
            dataset.pointBackgroundColor = "#4BC0C0";
            dataset.pointBorderWidth = 1;
        });

        window.onload = function() {
            var ctx = document.getElementById("profit").getContext("2d");
            window.myLine = new Chart(ctx, config);
        };

    //ends

  </script>


@stop
