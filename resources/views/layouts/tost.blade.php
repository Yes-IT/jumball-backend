
<!-- <div class="alert alert-danger" role="alert">
  This is a primary alert—check it out!
</div>


<div class="alert alert-success" role="alert">
  This is a primary alert—check it out!
</div> -->
@if($errors->any())
  @foreach ($errors->all() as $error)
    <script>
        $(document).ready(function() {
          toastr.error('{{$error}}');
        });
    </script>
  @endforeach
@endif


@if ($message = Session::get('success'))
  <script>
      $(document).ready(function() {
            toastr.success("{{$message}}");
      });
  </script>
@endif



<div class="statusMsg"></div>