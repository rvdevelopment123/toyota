@extends('app')

@section('htmlheader_title')
    Invalid License
@endsection

@section('contentheader_title')
    402: Payment Required
@endsection

@section('contentheader_description')
@endsection

@section('main-content')
<div class="panel-body">
    <div class="error-page">
        <h2 class="headline text-yellow"> 402: Invalid License</h2><br />
        <div class="error-content">
            <h3><i class="fa fa-warning font-red"></i> Payment Required/Invalid License.</h3>
            <p>
                Sorry, we could not verify your license with the purchase code you entered .<br />
                If you think this was by mistake, please contact Intelle Hub Support team.<br />
                Meanwhile, you may want to retry with a different/correct purchase code at <br />
                <hr />
                <a class="btn btn-success btn-md" href='{{ route('verify-purchase') }}'>Verify Purchase</a>.
            </p>
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
</div>
@endsection