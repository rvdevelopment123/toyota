@extends('app')

@section('title')
	Settings ::
	@parent
@stop

@section('contentheader')
	{{trans('core.general_settings')}}
@stop

@section('breadcrumb')
	{{trans('core.general_settings')}}
@stop


@section('main-content')
	<div class="panel-body" style="min-height: 1020px;">
		<div class="nav-tabs-custom">
	        <ul class="nav nav-tabs">
	            <!-- <li class="active">
	            	<a href="#details" data-toggle="tab">
	            		{{trans('core.general_settings')}}
	            	</a>
	            </li> -->
	            <li class="active {{settings('theme')}} font-white" >
	            	<a href="#editSettings" data-toggle="tab" class="{{settings('theme')}} font-white no-border">
	            		{{trans('core.general_settings')}}
	            	</a>
	            </li>
	        </ul>

	        <div class="tab-content">
	            <!-- <div class="active tab-pane" id="details">
	            	@include('settings.partials.details')
	            </div> -->

	            <!--Tab For Edit Settings-->
	            <div class="active tab-pane animated fadeIn" id="editSettings" >
	            	@include('settings.partials.edit-settings')
	            </div>
	            <!--Ends-->
	        </div><!--Tab Content Ends-->
	    </div> <!--nav-tabs-custom-->
	</div>
	
@stop


@section('js')
	@parent
	<script type="text/javascript">
        /*$(document).ready(function(){
            $('#invoice_tax').on('change',function(){
            	$('#invoice_tax_rate').hide();
            	var invoiceTax = $(this).val();
            	if(invoiceTax == 1){
                    $('#invoice_tax_rate').show();
                }
            });
        });*/
		/*ends*/
		var _URL = window.URL || window.webkitURL;
		$("#file").change(function(e) {
		    var file, img;
		    if ((file = this.files[0])) {
		        img = new Image();
		        img.onload = function() {
		        	if(this.width > 190 || this.height > 34){
		        		swal({
						  title: 'Invalid Image Size',
						  type: 'warning',
						  html:
						    '<small>Logo size should be (width=<b>190px</b>) x (height=<b>34px</b>) <br>Size of Logo you are tryining to Upload is '+this.width+'px x '+this.height+'px</small>',
						  showCloseButton: true,
						})
		        	}
		        };
		        img.onerror = function() {
		            alert( "not a valid file: " + file.type);
		        };
		        img.src = _URL.createObjectURL(file);
		    }
		});
    </script>

@stop