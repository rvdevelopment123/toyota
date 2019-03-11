<!DOCTYPE html>
<html>

@include('partials.htmlheader')
<body class="add-transition pt-page-rotatePullTop-init">

    @section('loader')
        <div id="loading" >
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    @show

    <div id="page-wrapper">
        @include('partials.mainheader')
        @include('partials.sidebar')
        <div id="page-content-wrapper" >
          <div id="page-content" style="min-height: 600px;">
            <div class="container">
                @include('partials.contentheader')
                @include('partials.messages')
                <div class="panel">
                  @yield('main-content')
                </div>
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
    @show

</body>
</html>