	<nav class="navbar">
				<div class="navbar-content">

          <div class="logo-mini-wrapper">
            <img  src="https://speechpublications.com/public/images/logo/Loggo3.png" class="logo-mini logo-mini-light w-full" style="width:100%!important" alt="logo">
            <img src="https://speechpublications.com/public/images/logo/Loggo3.png" class="logo-mini logo-mini-dark w-full" alt="logo" style="width:100%!important">
          </div>

					<form class="search-form">
						<div class="input-group">
              
							<!--<input type="text" class="form-control" id="navbarForm" placeholder="Search here...">-->
						</div>
					</form>

					<ul class="navbar-nav">
          
				
					
					
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img class="w-30px h-30px ms-1 rounded-circle" src="https://cdn-icons-png.flaticon.com/128/149/149071.png" alt="profile">
							</a>
							<div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
								<div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
									<div class="mb-3">
										<img class="w-80px h-80px rounded-circle" src="https://cdn-icons-png.flaticon.com/128/149/149071.png" alt="">
									</div>
									<div class="text-center">
										<p class="fs-16px fw-bolder">{{Auth()->user()->name}}</p>
										<p class="fs-12px text-secondary">{{Auth()->user()->email}}</p>
									</div>
								</div>
                <ul class="list-unstyled p-1">
                  <li>
                     <form id="logoutForm" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="dropdown-item py-2 text-body ms-0" style="background:none; border:none;">
            <i class="me-2 icon-md" data-lucide="log-out"></i>
            <span>Log Out</span>
        </button>
    </form>
                  </li>
                </ul>
							</div>
						</li>
					</ul>

          <a href="#" class="sidebar-toggler">
            <i data-lucide="menu"></i>
          </a>

				</div>
			</nav>