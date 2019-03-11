@extends('app')

@section('title')
    401
@endsection

@section('contentheader')
    401 Unauthorized
@endsection

@section('$contentheader_description')
@endsection

@section('main-content')
<div class="panel-body">
    <div class="error-page">
        <h2 class="headline text-red"> 401</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Unauthorized</h3>
            <p>
                The request has not been applied because it lacks valid authentication credentials for the target resource, you may <a href='{{ url('/home') }}'>return to dashboard</a>
            </p>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
</div>
@endsection