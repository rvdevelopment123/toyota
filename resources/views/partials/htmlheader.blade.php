<head>
  <meta charset="UTF-8">
  <title> 
      @section('title')
          {{ settings('site_name') }}
      @show
  </title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <script src="/assets/js-core/modernizr.js"></script>
  <!-- CSS -->
  <link rel="stylesheet" href="{{ elixir('base.css') }}">
  <link rel="stylesheet" href="/assets/css-core/custom.css">
  @if(app()->getLocale() == 'ar')
  <link rel="stylesheet" href="/assets/css-core/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="/assets/css-core/theme-rtl.css">
  @endif
  <link href="{!! asset('img/favicon.ico') !!}" rel="icon" type="image/gif" sizes="16x16">
  <script src="{{ elixir('vendor.js') }}"></script>
  <style type="text/css">
    #header-logo .logo-content-big {
        @if(settings('site_logo'))
          background: url({!! json_encode(asset('uploads/site/'. settings('site_logo')))!!}) left 50% no-repeat;
        @endif
    }

    .logo-content-small {
        background: url({!! json_encode(asset('img/small-logo-white.png'))!!}) left 50% no-repeat;
        left: 10px !important;
        width: 50px !important;
    }

    @media only screen and (max-width: 870px) {
    .logo-content-small {
        left: 75px !important;
      }
    }
  </style>

   <script type="text/javascript">
        $(window).load(function(){
            setTimeout(function() {
                $('#loading').fadeOut( 400, "linear" );
            }, 300);
        });
    </script>

</head>
