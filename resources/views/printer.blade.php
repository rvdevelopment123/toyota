<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <!-- Bootstrap -->
    <link href="{{asset('assets/css-core/bootstrap.css')}}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{asset('assets/css-core/printer.css')}}" rel="stylesheet">
    @if(app()->getLocale() == 'ar')
      <!-- <link rel="stylesheet" href="/assets/css-core/bootstrap.rtl.min.css">
      <link rel="stylesheet" href="/assets/css-core/theme-rtl.css"> -->
    @endif 
    <style type="text/css" media="print">
    @page 
    {
        size:  auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    html
    {
        background-color: #FFFFFF; 
        margin: 20px;  /* this affects the margin on the html before sending to printer */
    }

    body
    {
        border: solid 1px #FFF ;
        margin: 5mm 5mm 5mm 5mm; /* margin you want for the content */
    }
    </style>  

    <!--[if lt IE 9]>
    <script src="/js/ie8-responsive-file-warning.js"></script>
    <![endif]-->
     
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{{ asset('/assets/js-core/jquery.min.js') }}"></script>
</head>

<body class="skin-blue sidebar-mini">
<div class="">

    <!-- Content Wrapper. Contains page content -->
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('main-content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
</div><!-- ./wrapper -->

<script type="text/javascript">
    $(document).ready(function(){
        window.print();
        window.close();
    })
</script>
</body>
</html>