@if(licensed())
<div id="page-sidebar" class="@if(settings('theme')){{settings('theme')}} @else bg-gradient-1 @endif font-inverse">
  <div class="scroll-sidebar">
        
    <ul id="sidebar-menu">
          
        <li @if(currentRoute()=="home") class="no-menu active" @else class="no-menu" @endif>
            <a href="{{ route('home') }}">
                <i class='fa fa-th'></i> 
                <span>{{ trans('core.home')}}</span>
            </a>
        </li>

        <li @if(currentRoute()=="category.index") class="no-menu active" @else class="no-menu" @endif>
            <a href="{{route('category.index')}}">
                <i class='fa fa-tag'></i>
                <span>{{ trans('core.category')}}</span>
            </a>
        </li>

        <li @if(currentRoute()=="subcategory.index") class="no-menu active" @else class="no-menu" @endif>
            <a href="{{route('subcategory.index')}}"> 
                <i class='fa fa-tags'></i>
                <span>{{ trans('core.subcategory')}}</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class='fa fa-cubes'></i> 
                <span>{{ trans('core.product')}}</span>
            </a>

            <div class="sidebar-submenu">
                <ul>
                    <li>
                        <a href="{{route('product.index')}}">
                            <i class=''></i> 
                            <span>{{ trans('core.product_list')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('product.new')}}">
                            <i class=''></i> 
                            <span>{{ trans('core.add_new_product')}} </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('product.print_barcode')}}">
                            <i class=''></i> 
                            <span>{{ trans('core.print_barcode')}} </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li>
            <a href="#"><i class='fa fa-users'></i> <span>People</span></a>

            <div class="sidebar-submenu">
                <ul>
                    <li>
                        <a href="{{route('client.index')}}">
                            <i class=''></i> 
                            <span>{{ trans('core.customer')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('purchaser.index')}}">
                            <i class=''></i> 
                            <span>{{ trans('core.supplier')}} </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('user.index')}}">
                            <i class=''></i> 
                            <span>{{ trans('core.user')}} </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li >
            <a href="#"><i class="fa fa-ship"></i> <span>{{ trans('core.purchase')}}</span></a>
            <div class="sidebar-submenu">
                <ul>
                    <li>
                        <a href="{{route('purchase.index')}}">
                            <span>{{ trans('core.purchase_list')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('purchase.item')}}">
                            <span>{{ trans('core.add_new_purchase')}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li>
            <a href="#"><i class="fa fa-shopping-cart"></i> <span>{{ trans('core.sell')}}</span></a>
            <div class="sidebar-submenu">
                <ul>
                    <li>
                        <a href="{{route('sell.index')}}">
                            <span>{{ trans('core.sell_list')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('sell.form')}}">
                            <span>{{ trans('core.add_new_sell')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('sell.pos')}}">
                            <span>{{ trans('core.pos_screen')}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
          
        <li class="no-menu">
            <a href="{{route('payment.list')}}">
               <i class="fa fa-money"></i>                  
                <span>{{trans('core.transaction')}}</span>
            </a>
        </li>

        <li class="no-menu">
            <a href="{{route('expense.index')}}">
               <i class="fa fa-usd"></i>                  
                <span>{{trans('core.expense')}}</span>
            </a>
        </li>

        <li>
            <a href="#"><i class="fa fa-cog"></i><span> {{ trans('core.settings')}}</span></a>
            <div class="sidebar-submenu">
                <ul>
                    <li>
                        <a href="{{route('settings.index')}}">
                            <span>{{ trans('core.general_settings')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('role.index')}}">
                            <span>{{ trans('core.role')}}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('tax.index')}}">
                            <span>{{ trans('core.tax')}}</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('settings.backup')}}">
                            <span>{{ trans('core.backup')}}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li> 

        <li class="no-menu">
            <a href="{{route('warehouse.index')}}">
               <i class="fa fa-industry"></i>                  
                <span>{{ trans('core.warehouse')}}</span>
            </a>
        </li>

        @if(auth()->user()->can('report.view'))
        <li class="no-menu">
            <a href="{{route('report.index')}}">
               <i class="fa fa-pie-chart"></i>                  
                <span>{{ trans('core.report')}}</span>
            </a>
        </li>
        @endif
    </ul><!-- #sidebar-menu -->
  </div>
</div>
@endif
