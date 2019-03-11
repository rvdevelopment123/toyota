@extends('app')

@section('htmlheader_title')
    Page not found
@endsection

@section('contentheader_title')
    404 Error Page
@endsection

@section('contentheader_description')
@endsection

@section('main-content')
<div class="panel-body">
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2><br />
        <div class="error-content">
            <h3><i class="fa fa-warning font-red"></i> Not Found!</h3>
            <p>
                Sorry, we could not find the page you were looking for.<br />
                Meanwhile, you may <a href='{{ url('/home') }}'>return to dashboard</a>.
            </p>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
</div>
@endsection