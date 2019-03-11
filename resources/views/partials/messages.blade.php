@if(( isset($errors) && $errors->any()) || Session::has('error') || isset($error) || Session::has('message') || isset($message))
    <div id="messageBar" class="animated fadeInDown">
        @if($errors->any())
            <div class="alert alert-close alert-danger">
                <a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a>
                <div class="bg-red alert-icon">
                    <i class="glyph-icon fa fa-times fa-2x"></i>
                </div>
                <div class="alert-content">
                    <h4 class="alert-title">{{ trans('core.validation_error_title') }}</h4>
                    <p>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </p>
                </div>
            </div>
        @endif

        @if(isset($message) || Session::has('message'))

            <div class="alert alert-close alert-info">
                <a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a>
                <div class="bg-info alert-icon">
                    <i class="fa {{ (isset($icon)) ? $icon : (Session::has('icon') ? Session::get('icon') : 'fa-info-circle') }} fa-2x text-info"></i>
                </div>
                <div class="alert-content">
                    <h4 class="alert-title">{{ trans('core.info') }}</h4>
                    <p>
                        {!! isset($message) ? $message : Session::get('message') !!}
                    </p>
                </div>
            </div>
        @endif
        
    </div>
@endif

@if(isset($success) || Session::has('success'))
    @section('js')
        @parent
        <script>
            $(document).ready(function() {
                swal({
                    title: '',
                    text: {!! json_encode(isset($success) ? $success : Session::get('success')) !!},
                    type: 'success',
                    confirmButtonText: {!! json_encode(trans('core.ok')) !!}
                });
            });
        </script>
    @stop
@endif


@if(isset($quantityerror) || Session::has('quantityerror'))
    @section('js')
        @parent
        <script>
            $(document).ready(function() {
                swal({
                    title: '',
                    text: {!! json_encode(isset($quantityerror) ? $quantityerror : Session::get('quantityerror')) !!},
                    type: 'warning',
                })
                .then(() => {
                  window.location.href = '{{route("sell.index")}}';
                });
            });
        </script>
    @stop
@endif

@if(isset($warning) || Session::has('warning'))
    @section('js')
        @parent
        <script>
            $(document).ready(function() {
                swal({
                    title: '',
                    text: {!! json_encode(isset($warning) ? $warning : Session::get('warning')) !!},
                    type: 'warning',
                    confirmButtonText: {!! json_encode(trans('core.ok')) !!}
                });
            });
        </script>
    @stop
@endif