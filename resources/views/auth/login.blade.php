<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type"content="text/html; charset=utf-8"/>
		<meta name="viewport"content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
		<title>Login</title>
		<link href="{{asset('assets/css/bootstrap.min.css')}}"rel="stylesheet"type="text/css"/>
		<link href="{{asset('assets/css/bootstrap-theme.min.css')}}"rel="stylesheet"type="text/css"/>
		<link rel="stylesheet"href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css">
		<link href="{{asset('assets/css/animation.css')}}"rel="stylesheet"type="text/css">
		<link href="{{asset('font/stylesheet.css')}}"rel="stylesheet"type="text/css"/>
		<link href="{{asset('assets/css/custom.css')}}"rel="stylesheet"type="text/css"/>
		<link href="{{asset('assets/css/style.css')}}"rel="stylesheet"type="text/css"/>
		<link href="{{asset('assets/css/responsive.css')}}"rel="stylesheet"type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

		<!--=================== custom links ================== -->
		<link href="{{asset('dist/toast/toastr.css')}}" rel="stylesheet"type="text/css"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!--=================== custom links ================== -->
		<!-- CSS -->
		<!-- JS -->
		<script src="{{asset('assets/js/jquery.js')}}"type="text/javascript"></script>


	</head>
	<body>
		<div class="login-wrap">
			<div class="container">

				<div class="row justify-content-center">
					<div class="col-lg-9 col-md-10">
						<div class="login-in">
							<div class="login-logo">
								<div class="logo">
									<img src="{{asset('assets/images/jumballbg.png')}}">
									<!-- <h1>INaholic</h1> -->
								</div>
							</div>
							<div class="login-form">
								<form id="admin_login">
									<h1>Login</h1>
									<div>
										<input  type="text"  name="email" class="login-txt emailicon admin_email"   placeholder= "Email" autocomplete="off"/>
									</div>
									<label class="login-txt mb-0 pl-0">
										<input type="password" name="password" class="login-txt passicon admin_password" id="password" placeholder="Password">
										<div class="password-eye"><i class="fas fa-eye-slash" id="eye"></i></div>
										<!-- <input id="password-field" type="password" class="login-txt" name="password" value="" placeholder="Password">
										<span toggle="#password-field" class="fa fa-fw fa-eye password-eye field-icon toggle-password"></span> -->
									</label>
									<label for="remember" class="login-labl">
										<input type="checkbox" class="login-rmb admin_remember" id="remember" name="remember">Remember me</label>
										<!-- <input type="submit" class="login-btn" value="Sign In"> -->
										<!-- <a href="#"  class="show-modal login-btn"data-toggle="modal"data-target="#myModal-2">Login</a> -->
										<button class="show-modal login-btn" type="submit">Login</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Login succesfull -->
	<!-- <div class="modal fade" id="login-success" tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-dialog-edit"role="document">
			<div class="modal-content-front clearfix">
				<button type="button"class="close close-btn-front"data-dismiss="modal"aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>

				<div class="modal-body">
					<img src="images/logicon.png" alt="">
					<div class="verifcation-form-wrap">
						<form>

						<div class="congrtaulation-wrap">

						<h3>Login successful</h3>
						<p class="pt-2">Welcome to VoolayVoo Admin panel!</p>
						</div>

						<div class="centered-btn-wrap text-center w-100">
							<a href="signin-success.html" class="common-btn-front">Ok</a>
						</div>


						</form>
					</div>
				</div>
			</div>
		</div>
	</div> -->

  <!-- Login succesfull -->


    <!-- Login succesfull -->
	<!-- <div class="modal fade" id="login-success" tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-dialog-edit"role="document">
			<div class="modal-content-front clearfix">
				<button type="button"class="close close-btn-front"data-dismiss="modal"aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>

				<div class="modal-body">
					<div class="verifcation-form-wrap">
						<form>

							<div class="login-succ">
								<img src="images/logicon.png" alt="">
								<h1>Login successful</h1>
								<p>Welcome to Jumball Admin Panel!</p>

							</div>

						<div class="centered-btn-wrap text-center w-100">
							<a href="user-management.html" type="submit" class="common-btn-front">Ok</a>
						</div>


						</form>
					</div>
				</div>
			</div>
		</div>
	</div> -->

  <!-- Login succesfull -->

  <!-- <div class="modal fade" id="login-success" tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-edit"role="document">
		<div class="modal-content-front clearfix">
			<button type="button"class="close close-btn-front"data-dismiss="modal"aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>

			<div class="modal-body">
				<div class="verifcation-form-wrap">
					<form>

					<div class="congrtaulation-wrap">
					<img src="images/roam-images/check.png" alt="">
					<h3>Login Successful</h3>
					<p class="pt-2">Welcome to VoolayVoo Admin panel!</p>
					</div>

					<div class="centered-btn-wrap text-center w-100">
						<a href="user-management.html" type="submit" class="common-btn-front">Ok</a>
					</div>


					</form>
				</div>
			</div>
		</div>
	</div>
</div> -->


<div class="modal fade" id="myModal-1"tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-edit"role="document">
		<div class="modal-content login-succ">


				<img src="{{asset('assets/images/logicon.png')}}" alt="">
						<h1>Login successful</h1>
						<p>Welcome to jumball admin panel</p>
						<a href="{{route('home')}}">Ok</a>
				<button type="button"class="close close-btn" data-dismiss="modal"aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>

					<!-- <div class="login-succ">
						<img src="images/logicon.png" alt="">
						<h1>Login successful</h1>
						<p>Welcome to Jumball Admin Panel!</p>
						<a href="user-management.html">Ok</a>
					</div> -->


		</div>
	</div>
</div>


<div class="modal fade" id="myModal-2"tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-edit"role="document">
		<div class="modal-content login-fail">


				<img src="{{asset('assets/images/failed-icon-jb.png')}}" alt="">
						<h1>Login Failed</h1>
						<p>Please enter correct email or password</p>
						<a href="{{route('login')}}">Ok</a>
				<button type="button"class="close close-btn" data-dismiss="modal"aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>

					<!-- <div class="login-succ">
						<img src="images/logicon.png" alt="">
						<h1>Login successful</h1>
						<p>Welcome to Jumball Admin Panel!</p>
						<a href="user-management.html">Ok</a>
					</div> -->


		</div>
	</div>
</div>
<div class="modal fade" id="myModal-3"tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-edit"role="document">
		<div class="modal-content login-fail">


				<img src="{{asset('assets/images/failed-icon-jb.png')}}" alt="">
						<h1>Login Failed</h1>
						<p>Incorrect Password. Please try again</p>
						<a href="{{route('login')}}">Ok</a>
				<button type="button"class="close close-btn" data-dismiss="modal"aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>

					<!-- <div class="login-succ">
						<img src="images/logicon.png" alt="">
						<h1>Login successful</h1>
						<p>Welcome to Jumball Admin Panel!</p>
						<a href="user-management.html">Ok</a>
					</div> -->


		</div>
	</div>
</div>












		<script src="{{asset('assets/js/bootstrap.min.js')}}"type="text/javascript"></script>
		<script src="{{asset('assets/js/custom.js')}}"type="text/javascript"></script>
		<script src="{{asset('assets/js/animation.js')}}"type="text/javascript"></script>

		<script src="{{asset('dist/toast/toastr.js')}}" type="text/javascript"></script>

		<script>
			$('#admin_login').submit(function(e){
				e.preventDefault();
				console.log('hdgdfsghdgf');

				let formData  = new FormData(this);
				$.ajax({
				// cache: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url:'{{route("admin.login")}}',
					type: 'post',
					dataType: 'json',
					data:formData,
					processData: false,
					contentType: false,
					success: function (res) {
						if(res.status ==200)
						{

							$('#myModal-1').modal('show');

							// window.location.href = '{{route("admin.users")}}';

						}
						else if(res.status ==202)
						{
							// alert('gdddddd');
							$('#myModal-3').modal('show');

						}
						else{
							$('#myModal-2').modal('show');
						}

					},
					error: function (result) {
						toastr.error('Something went wrong');
					}
				});
			});




		</script>
        <script>
            if (localStorage.admin_chkbx && localStorage.admin_chkbx != '') {
				$('.admin_remember').attr('checked', 'checked');
				$('.admin_email').val(localStorage.admin_usrname);
				$('.admin_password').val(localStorage.admin_pass);
			} else {
				$('.admin_remember').removeAttr('checked');
				$('.admin_email').val('');
				$('.admin_password').val('');
			}

			$('.admin_remember').click(function() {
				console.log('admin_remember');
				if ($('.admin_remember').is(':checked')) {
					localStorage.admin_usrname = $('.admin_email').val();
					localStorage.admin_pass = $('.admin_password').val();
					localStorage.admin_chkbx = $('.admin_remember').val();
				} else {
					localStorage.admin_usrname = '';
					localStorage.admin_pass = '';
					localStorage.admin_chkbx = '';
				}
			});
        </script>




		</body>
	</html>
