<!DOCTYPE html>
<html>
<head>
<!-- sections/head.main.blade -->
@include('sections.head')
</head>
<body class="hold-transition login-page" style="background-color: white;">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  @section('content')
  @show
</div>
<!-- ./wrapper -->
@include('sections.scripts')
<script src="js/public-main.js"></script>
</body>
</html>
