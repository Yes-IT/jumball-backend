<section id="sidebar">

			<a href="#"class="brand">

				<!-- <i class='bx bxs-smile'></i> -->

				<!-- <span class="text">INaholic</span> -->

				<img src="{{asset('assets/images/logojb.png')}}" alt="">

			</a>

			<ul class="side-menu">

				<li class="{{Request::is('admin/users') ? 'active':''}}">

					<p>User Management</p>

					<a href="{{route('admin.users')}}">

						<img src="{{asset('assets/images/menu-icons/usermanicon.png')}}" alt="">

                        <span class="text">User Management</span>

					</a>

				</li>

				<li class="{{Request::is('admin/players')?'active':''}}">

					<p>Player Management</p>

					<a href="{{route('admin.players')}}">

						<img src="{{asset('assets/images/menu-icons/playericon.png')}}" alt="">

                        <span class="text">Player Management </span>

					</a>

				</li>

				<li class="{{Request::is('admin/world_cup_management*')?'active':''}}">

					<p>World Cup Management</p>

					<a href="{{route('admin.world_cup_management')}}">

						<img src="{{asset('assets/images/menu-icons/worldcup4.png')}}" alt="">

                        <span class="text">World Cup Management </span>

					</a>

				</li>

				<li class="{{Request::is('admin/statistics')?'active':''}}">

					<p>Statistics Management</p>

					<a href="{{route('admin.statistics')}}">

						<img src="{{asset('assets/images/menu-icons/statsicon.png')}}" alt="">

                        <span class="text">Statistics Management</span>

					</a>

				</li>

				<li class="{{Request::is('admin/jersey_management')?'active':''}}">
					<p>Terms and Conditions</p>
					<a href="{{url('admin/jersey_management')}}">
						<img src="{{asset('assets/images/menu-icons/termsicon.png')}}" alt="">
                        <span class="text">Jersey Management</span>
					</a>
				</li>

				<li class="{{Request::is('admin/sticker_management')?'active':''}}">
					<p>Terms and Conditions</p>
					<a href="{{url('admin/sticker_management')}}">
						<img src="{{asset('assets/images/menu-icons/termsicon.png')}}" alt="">
                        <span class="text">Sticker Management</span>
					</a>
				</li>



				<li class="{{Request::is('admin/term_and_condition')?'active':''}}">
					<p>Terms and Conditions</p>
					<a href="{{route('admin.term_and_condition')}}">
						<img src="{{asset('assets/images/menu-icons/termsicon.png')}}" alt="">
                        <span class="text">Terms and Conditions</span>
					</a>
				</li>

				<li class="{{Request::is('admin/privacy-policy')?'active':''}}">

					<p>Privacy-Policy</p>

					<a href="{{route('admin.privacy-policy')}}">

						<img src="{{asset('assets/images/menu-icons/privacyicon.png')}}" alt="">

                        <span class="text">Privacy-Policy</span>

					</a>

				</li>

				<!-- <li>

					<p>Manage Content</p>

					<a onclick="myFunction()"class="dropbtn">

                        </a>

						<img src="images/menu-icons/manage-content.svg"alt="">Manage Content

				</li>

				<div id="myDropdown"class="dropdown-content show">

                    <a href="privacy-policy.html"> <p>Privacy Policy</p> <img src="images/menu-icons/privacy-policy.svg"alt=""> Privacy Policy</a>

                        <a href="terms-&-conditions.html"> <p>Terms & Conditions</p> <img src="images/menu-icons/terms-conditions.svg"alt=""> Terms & Conditions</a>

					<div class="clr"></div>

				</div> -->

				<li>

					<p>Logout</p>

					<a type="button"class="show-modal"data-toggle="modal"data-target="#myModal">

						<img src="{{asset('assets/images/menu-icons/logicon.png')}}" alt="">

                        <span class="text">Logout</span>

					</a>

				</li>

			</ul>

		</section>