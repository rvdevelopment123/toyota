<!DOCTYPE html>
<html>

@include('partials.htmlheader')

@section('css')
  <link rel="stylesheet" type="text/css" href="/assets/css-core/slick.css">
  <link rel="stylesheet" type="text/css" href="/assets/css-core/slick-theme.css">
  <link rel="stylesheet" href="/assets/css-core/pos.css">
@show

<body class="add-transition pt-page-rotatePullTop-init fixed-sidebar closed-sidebar">
    <div id="page-wrapper">
        @include('partials.mainheader')
        <div id="page-content-wrapper">
          <div id="page-content"  @if(rtlLocale()) style="margin-right: 0px !important;" @endif>
            <div class="container">
                @yield('main-content')
            </div>
          </div>
          @include('partials.footer')
        </div>
        
    </div>

    @section('js')
        <script>
            window.Laravel = {!! json_encode(['csrfToken' => csrf_token() ]); !!}
        </script>
        @include('partials.scripts')
        <script src="/assets/js-core/jquery-2.2.0.min.js" type="text/javascript"></script>
        <script src="/assets/js-core/slick.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            $(document).on('ready', function() {
              $(".regular").slick({
                dots: true,
                infinite: true,
                slidesToShow: 7,
                slidesToScroll: 3
              });
              $(".center").slick({
                dots: true,
                infinite: true,
                centerMode: true,
                slidesToShow: 3,
                slidesToScroll: 3
              });
              $(".variable").slick({
                dots: true,
                infinite: true,
                variableWidth: true
              });
            });
        </script>
    @show

</body>
</html>