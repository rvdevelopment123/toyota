<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title> 
    {{settings('site_name')}}::Login
  </title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <script src="/assets/js-core/modernizr.js"></script>
  <!-- CSS -->
  <link rel="stylesheet" href="{{ elixir('base.css') }}">
  <script src="/assets/js-core/wow.js"></script>

  <script type="text/javascript">
      wow = new WOW({
          animateClass: 'animated',
          offset: 100
      });
      wow.init();
  </script>

  <style type="text/css">

      html,body {
          height: 100%;
          background: #fff;
          overflow: hidden;
      }

  </style>

</head>
<body>

  <img src="/assets/image-resources/blurred-bg/blurred-bg-10.jpg" class="login-img wow fadeIn" alt="">

  <div class="center-vertical">
      <div class="center-content row">
          <div class="col-md-4 col-sm-5 col-xs-11 col-lg-3 center-margin ">
              <center>
                <img src="/img/intelle_stock_white.png" class="wow fadeIn">
                <!-- <h3 class="font-white wow fadeIn">Intelle Stock Manager</h3> -->
              </center><br />
          
              <div class="content-box wow bounceInDown modal-content pad20A clearfix row">
                  <div class="col-md-3">
                    @if(empty($user->image))
                      <img src="{{asset('img/default.png')}}" class="img-bordered border-gray radius-all-4 img-full" alt="User Image"/>
                    @else
                      <img src="{!! asset('uploads/profiles/'. $user->image)!!}" class="img-bordered border-gray radius-all-4 img-full" alt="User Image"/>
                    @endif
                  </div>
                  <div class="col-md-9">
                      <div class="meta-box text-left">
                          <h3 class="meta-heading font-size-16">{{ $user->name }}</h3>
                          <h4 class="meta-subheading font-size-13 font-gray">{{ settings('site_name') }}</h4>
                          <div class="divider"></div>
                          {!! Form::open([ 'route' => 'unlock','method' => 'post', 'class' => 'form-inline pad10T']) !!}
                              <div class="form-group">
                                  <div class="input-group">
                                      <input type="hidden" name="email" value="{{ $user->email }}">
                                      <input type="password" placeholder="Password" class="form-control" name="password">
                                      <span class="input-group-btn">
                                          <button class="btn btn-primary" type="submit">
                                            <i class="glyph-icon icon-unlock-alt"></i>
                                          </button>
                                      </span>
                                  </div>
                              </div>
                          {{ Form::close() }}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

</body>
</html>
