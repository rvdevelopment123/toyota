<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title> 
    Verify Purchase
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

    <img src="/assets/image-resources/blurred-bg/blurred-bg-4.jpg" class="login-img wow fadeIn" alt="">

    <div class="center-vertical">
        <div class="center-content row">

            <div class="col-md-3 center-margin">
                <center>
                  <img src="/img/intelle_stock_white.png" class="wow fadeIn">
                  <h3 class="font-white wow fadeIn">Intelle Stock Manager</h3>
                </center>
                <br>
                <form role="form" method="POST" action="{{ route('verify-purchase-post') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="content-box wow bounceInDown modal-content">
                        <h3 class="content-box-header content-box-header-alt bg-default">
                            <span class="icon-separator">
                                <i class="glyph-icon icon-cog"></i>
                            </span>
                            <span class="header-wrapper">
                                <small>Verify Your Purchase.</small>
                            </span>
                        </h3>
                        <div class="content-box-wrapper">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Purchase Code" name="code">
                                    <span class="input-group-addon bg-blue">
                                        <i class="glyph-icon fa fa-cog"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <button class="btn btn-success btn-block">Verify</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>
</html>
