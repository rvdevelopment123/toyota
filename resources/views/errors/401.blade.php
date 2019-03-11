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
        <h2 class="headline text-yellow"> 401</h2><br />
        <div class="error-content">
            <h3><i class="fa fa-warning font-red"></i> Not Authorized.</h3>
            <p>
                Sorry, you are not authorized to access this particular feature.<br />
                Meanwhile, you may <a href='{{ url('/home') }}'>return to dashboard</a>.
            </p>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
</div>
@endsection