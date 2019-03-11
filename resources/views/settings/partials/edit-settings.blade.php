{!! Form::model($setting, ['method' => 'post', 'files' => true]) !!}
	<div class="example-box-wrapper">
		<div class="form-horizontal bordered-row">

			<div class="form-group bg-khaki">
				<h3 class="control-label col-sm-2 title-hero">
		            {{trans('core.general_settings')}}
		        </h3>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
					{{trans('core.shop_name')}}
				</label>
				<div class="col-sm-4 {{ $errors->has('site_name') ? 'has-error' : ''}}"> 
					{!! Form::text('site_name', $setting->site_name, ['class' => 'form-control']) !!}
					<!-- {!! $errors->first('site_name', '<p class="error-message">:message</p>') !!} -->
			    </div>

			    <label class="control-label col-sm-2">
			    	{{trans('core.shop_slogan')}}
			    </label>
				<div class="col-sm-4"> 
					{!! Form::text('slogan', $setting->slogan, ['class' => 'form-control']) !!}
			    </div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
					{{trans('core.phone')}}
				</label>
				<div class="col-sm-4 {{ $errors->has('phone') ? 'has-error' : ''}}"> 
					{!! Form::text('phone', $setting->phone, ['class' => 'form-control']) !!}
			    </div>

			    <label class="control-label col-sm-2">
			    	{{trans('core.email')}}
			    </label>
				<div class="col-sm-4 {{ $errors->has('email') ? 'has-error' : ''}}"> 
					{!! Form::text('email', $setting->email, ['class' => 'form-control']) !!}
			    </div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
					{{trans('core.shop_address')}}
				</label>
				<div class="col-sm-10 {{ $errors->has('address') ? 'has-error' : ''}}"> 
					{!! Form::textarea('address', $setting->address, ['class' => 'form-control', 'rows' => 3]) !!}
			    </div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
					{{trans('core.shop_owner')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::text('owner_name', $setting->owner_name, ['class' => 'form-control']) !!}
			    </div>

			    <label class="control-label col-sm-2">
			    	{{trans('core.currency')}}
			    </label>
				<div class="col-sm-4"> 
					{!! Form::text('currency_code', $setting->currency_code, ['class' => 'form-control']) !!}
			    </div>
			</div>

			<div class="form-group">
                <label class="control-label col-sm-2">
			    	{{trans('core.theme')}}
			    </label>
				<div class="col-sm-4"> 
					{!! Form::select('theme', [ 'bg-primary' => 'Pacific Blue', 'bg-green' => 'Green', 'bg-red' => 'Red', 'bg-blue' => 'Blue', 'bg-warning' => 'Orange', 'bg-purple' => 'Purple', 'bg-black' => 'Black','bg-gradient-1' => 'Moderate Azure', 'bg-gradient-2' => 'Strong Spring Green', 'bg-gradient-3' => 'Magenta-pink', 'bg-gradient-4' => 'Desaturated Cyan', 'bg-gradient-5' => 'Strong Azure', 'bg-gradient-6' => 'Vivid Cyan', 'bg-gradient-7' => 'Deep Cyan', 'bg-gradient-8' => 'Strong Cornflower Blue.', 'bg-gradient-9' => 'Strong Arctic Blue'], null, ['class' => 'form-control']) !!}
			    </div>

			    <label class="control-label col-sm-2">
			    	{{trans('core.dashboard_style')}}
			    </label>
				<div class="col-sm-4">
					{!! Form::select('dashboard_style', [ 'chart-box' => 'Chart Box', 'tile-box' => 'Tile Box'], $setting->dashboard, ['class' => 'form-control']) !!}
			    </div>
            </div>

			<div class="form-group">
                <label class="col-sm-2 control-label">
                	{{trans('core.logo')}}
                </label>
                <div class="col-sm-10">
                    {!! Form::file('image', ['id' => 'file']) !!}
					<br>
					<small>
						Logo size should be (width=190px) x (height=34px).
					</small>
                </div>
            </div>

			<div class="form-group bg-khaki">
				<h3 class="control-label col-sm-2 title-hero">
		            {{trans('core.product_settings')}}
		        </h3>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
					{{trans('core.alert_range')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::number('alert_quantity', $setting->alert_quantity, ['class' => 'form-control']) !!}
			    </div>

			    <label class="control-label col-sm-2">
					{{trans('core.product_tax')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::select('product_tax', ['0' => 'Disable', '1' => 'Enable'],null, ['class' => 'form-control']) !!}
			    </div>
			</div>

			<div class="form-group bg-khaki">
				<h3 class="control-label col-sm-3 title-hero">
					{{trans('core.sell_n_purchase_settings')}}
		        </h3>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
					{{trans('core.invoice_tax')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::select('invoice_tax', ['0' => 'Disable', '1' => 'Enable'],null, ['class' => 'form-control', 'id' => 'invoice_tax',]) !!}
			    </div>

			    <div id="invoice_tax_rate"> 
			    	<!-- @if(settings('invoice_tax') == 0) style="display: none;" @endif -->
				    <label class="control-label col-sm-2">
						{{trans('core.invoice_tax_rate')}}
					</label>

					<div class="col-sm-4">
						<select class="form-control" name="invoice_tax_id">
							@foreach($taxes as $tax)
								<option value="{{$tax->id}}" @if($tax_rate == $tax->rate) selected @endif>
									{{$tax->name}}
								</option>
							@endforeach
						</select>

						<span>Add VAT Rate?</span>
						<a href="{{route('tax.index')}}" style="text-decoration: underline; padding: 10px; color: blue; ">Click Here</a>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2">
				{{trans('core.enable_purchaser')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::select('enable_purchaser', ['0' => 'Disable', '1' => 'Enable'],null, ['class' => 'form-control']) !!}
			    </div>

			    <label class="control-label col-sm-2">
				{{trans('core.enable_customer')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::select('enable_customer', ['0' => 'Disable', '1' => 'Enable'],null, ['class' => 'form-control']) !!}
			    </div>
			</div>

			<div class="form-group bg-khaki" >
				<h3 class="control-label col-sm-2 title-hero">
					{{trans('core.invoice_settings')}}
		        </h3>
			</div>

			<div class="form-group">
			    <label class="control-label col-sm-2">
				{{trans('core.vat_no')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::text('vat_no', $setting->vat_no, ['class' => 'form-control']) !!}
			    </div>
			</div>

			<div class="form-group bg-khaki" >
				<h3 class="control-label col-sm-2 title-hero">
					{{trans('core.pos_settings')}}
		        </h3>
			</div>

			<div class="form-group">
			    <label class="control-label col-sm-2">
				{{trans('core.pos_footer_text')}}
				</label>
				<div class="col-sm-4"> 
					{!! Form::textarea('pos_invoice_footer_text', $setting->pos_invoice_footer_text, ['class' => 'form-control', 'rows' => '2']) !!}
			    </div>
			</div>

			@if(auth()->user()->can('settings.manage'))
		    <div class="bg-default content-box text-center pad20A mrg25T">
                <button class="btn btn-lg btn-primary" type="submit">
                	{{ trans('core.save') }}
                </button>
            </div>
            @endif
		</div>		
	</div>
{!! Form::close() !!}