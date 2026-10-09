<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		@include('layouts.head')

		<!-- CSS -->
	</head>
	<body>

		<!-- SIDEBAR -->
		@include('layouts.sidebar')
		<!-- SIDEBAR -->

		<!-- CONTENT -->
		<section id="content">
			<!-- NAVBAR -->
			<nav>
				<i class='bx bx-menu'><i class="fal fa-bars"></i></i>
				<div class="influ-search">
					<form>
						<label for="">
							<!-- <input type="text"name=""placeholder="Search user by name, employer, employee id, etc."> -->
							<!-- <button><img src="images/search.png"alt=""></button> -->
						</label>
					</form>
				</div>
				<!-- <div class="invite-user">
					<a type="button"class="show-modal"data-toggle="modal"href="#lost"data-target="#myModal-3">Invite Users
						<i class="fal fa-plus-circle"></i>
					</a>
				</div> -->
				<div class="admin-icon">
					<img src="{{asset('assets/images/adminico.svg')}}"></img>
					Admin
				</div>
			</nav>
			<!-- NAVBAR -->
			<!-- MAIN -->

			@yield('content')
			@show
			<!-- MAIN -->
		</section>
		<!-- CONTENT -->
		<!-- LOGOUT-POPUP -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content clearfix">
					<div class="modal-body">
						<div class="logout-in">
							<img  class="logout-icon" src="{{asset('assets/images/logouticon.png')}}" alt="">

							<p><b>Are you sure you want to logout?</b></p>
							<form class="button-logout">
								<a href="" type="button" class="logout-in-btn" data-dismiss="modal"
									aria-label="Close">Cancel</a>
								<a href="{{route('admin.logout')}}" class="logout-in-btn logout-in-btn-red">Logout</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- LOGOUT-POPUP -->
		<!-- EDIT-POPUP -->
        <!-- EDIT-POPUP -->
	
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content clearfix">
					<div class="modal-body">
						<div class="logout-in">
							<img  class="logout-icon" src="images/logouticon.png" alt="">

							<p>Are you sure you want to logout?</p>
							<form>
								<a href="dashboard-login.html"class="logout-in-btn logout-in-btn-red">Confirm</a>
								<a href=""type="button"class="logout-in-btn"data-dismiss="modal"aria-label="Close">Cancel</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- EDIT-POPUP -->
	
	    <!-- delete popup -- -->

			<div class="modal fade" id="delete-query" tabindex="-1"role="dialog"aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-dialog-edit"role="document">
					<div class="modal-content clearfix">
						<div class="modall-head">
							<button type="button"class="close close-btn-front"data-dismiss="modal"aria-label="Close">
							<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body-2">
							<div class="small-box">
								<form>
									<div class="content-box">
									<img src="images/roam-images/delete-popup.png" alt="">
									<h3 class="heading-delete">Delete!</h3>
									<p class="para-2">Are you sure you want to delete ?</p>
									</div>

									<div>
										<a href="#" type="submit" class="confirm-btn" data-dismiss="modal"aria-label="Close">Confirm</a>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

	    	<!-- delete popup -- -->

		<!-- MORE-FILTERS-POPUP -->
		<!-- JS -->
		@include('layouts.footer')
	</body>
</html>
