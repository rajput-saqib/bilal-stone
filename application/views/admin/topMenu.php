<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?=ADMIN_URL?>" style="width: auto;">
				<img alt="admin panel" src="<?=ADMIN_IMAGE_PATH?>logo20.png" />
				<span><?=$loginData['company_name']?></span>
			</a>

			<!-- user dropdown starts -->
			<div class="btn-group pull-right" >
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-user"></i><span class="hidden-phone"> &nbsp; <?=$loginData['username']?> &nbsp; </span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="<?=ADMIN_URL?>profile">Profile</a></li>
					<li class="divider"></li>
					<li><a href="<?=base_url()?>admin/index/logout">Logout</a></li>
				</ul>
			</div>
			<!-- user dropdown ends -->
		</div>
	</div>
</div>