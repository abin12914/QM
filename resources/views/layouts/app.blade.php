<!DOCTYPE html>
<html>
<head>
<!-- sections/head.main.blade -->
@include('sections.head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    @include('sections.header')
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  @include('sections.leftsidebar')

  <!-- Content Wrapper. Contains page content -->
  @section('content')
  @show
  <!-- /.content-wrapper -->
  @include('sections.footer')
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@include('sections.scripts')
</body>
</html>
