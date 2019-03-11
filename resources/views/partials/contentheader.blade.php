<!-- Content Header (Page header) -->
<div id="page-title">
    <h2>
        @section('contentheader') 
            {{ settings('site_name') }}
            <small style=" font-size: 12px; letter-spacing: 2px;" class="hidden-xs">
                <b>{{Auth::user()->warehouse->name}}</b>
            </small>
        @show
    </h2>
    <p>
        @section('contentheader_description') 
            Ultimate tool to manage inventory and stock. 
        @show
    </p>
    <ol class="breadcrumb">
        <li>
            <a href="{{route('home')}}">
                <i class="fa fa-dashboard"></i> 
                {{trans('core.dashboard')}}
            </a>
        </li>
        <li class="active">
        	@section('breadcrumb')
        		
        	@show
        </li>
    </ol>
</div>