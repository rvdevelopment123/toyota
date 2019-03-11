@extends('app')

@section('title')
	{{trans('core.stock_report')}}
@stop

@section('contentheader')
	{{trans('core.stock_report')}} Of {{$product_name}}
@stop

@section('breadcrumb')
	<a href="{{route('report.index')}}">{{trans('core.report')}}</a>
	<li>{{trans('core.stock_report')}}</li>
@stop

@section('main-content')
	<div id="chart1" style="min-width: 550px; height: 400px; margin: 0 auto"></div>
	
	<div class="panel-footer">
		<span style="padding: 10px;">
        
        </span>

		<a class="btn btn-border btn-alt border-black font-black btn-xs pull-right" href="{{route('report.index')}}">
	        <i class="fa fa-backward"></i> 
	        {{trans('core.back')}}
	    </a>
	</div>
	
@stop

@section('js')
	@parent
	<script src="{{ asset('vendors/highchart/highcharts.js') }}" type="text/javascript"></script>
	<script src="{{ asset('vendors/highchart/exporting.js') }}" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function () {
			function currencyFormate(x) {
				var parts = x.toString().split(".");
				return  parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",")+(parts[1] ? "." + ((parts[1].length == 1) ? parts[1]+'0' : parts[1]) : ".00");	 
			}
			
		    Highcharts.setOptions({
		        chart: {
		            style:{
		                    fontFamily:'Arial, Helvetica, sans-serif', 
		                    fontSize: '20em',
		                    color:'#f00'
		                }
		        }
		    });

	        $('#chart1').highcharts({
	            chart: {
	                type: 'pie'
	            },
	            colors: [
	               '#ED5565',
	               '#A0D468', 
	               '#5D9CEC', 
	               '#FFCE54',  
	               '#48CFAD', 
	               '#AC92EC',
	               '#AAB2BD', 
	               '#D770AD', 
	               '#c42525', 
	               '#a6c96a'
	            ],
	            title: {
	                text: '{{trans('core.stock_value')}} ({{$product_name}})',
	                style: {
	                  color: '#555'
	                }
	            },
	            legend: {
	                layout: 'horizontal',
	                align: 'center',
	                verticalAlign: 'bottom',
	                borderWidth: 0,
	                backgroundColor: '#FFFFFF'
	            },
	            xAxis: {
	                categories: [
	                    '2006',
	                    '2007',
	                    '2008',
	                    '2009',
	                    '2010',
	                    '2011'
	                ]
	            },
	            yAxis: {
	                title: {
	                    text: ''
	                }
	            },
	            tooltip: {
					shared: true,
					backgroundColor: '#fff',
					headerFormat: '<span style="font-size:15px background-color: red;">{point.key}</span><br>',
	                pointFormat: '<span style="color:black;padding:0;text-align:right;">{{settings('currency_code')}}<b>{point.y}</b> ({point.percentage:.2f}%)</span>',
	                footerFormat: '',
	                useHTML: true,
					valueDecimals: 2,
					style: {
						fontSize: '15px',
						padding: '10px',
						color: '#000000'
					}
	            },
	            credits: {
	                enabled: false
	            },
	           plotOptions: {
	            	pie: {
		                dataLabels: {
		                    enabled: true,
							formatter: function() {
		                        return '<h3 style="margin:-15px 0 0 0;"><b>'+this.point.name+'</b>:<br>{{settings('currency_code')}} <b> ' + currencyFormate(this.y) +'</b></h3>';
		                    },
							useHTML: true
		                }
	            	},
	            series: {
	                	groupPadding: .15
	            	}
	            },
	            series: [{
	            type: 'pie',
	            name: '{{trans('core.stock_report')}}',
	            data: [
	                ['{{trans('core.stock_by_cost')}}', {{$stock[0]}}],
	                ['{{trans('core.stock_by_price')}}',{{$stock[1]}}],
	                ['{{trans('core.profit_estimate')}}',{{$stock[2]}}],
	            ]
	            }]
	        });
    	});
	</script>

@stop