<nav class="navbar-default navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
		</button>
        
        <h1> <a class="navbar-brand" href="home.php"><?php echo SITE_NAME;?></a></h1>         
	</div>
    <div class=" border-bottom">
        <div class="full-left">
            <div class="clearfix"> </div>
		</div>
        <div class="drop-men" >
            <ul class=" nav_1">
				<li class="dropdown at-drop">
					<a target="_blank" href="<?php echo SITEURL;?>">
						
						<button class="btn btn-primary" type="button">
							<span class="fa fa-tv" aria-hidden="true"></span> View Site<!--<span class="badge">4</span>-->
						</button>
					</a>
				</li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret"><?= $_SESSION['name'] ?><i class="caret"></i></span><img src="../images/user/<?= $_SESSION['image'] ?>" style="width:100px; height: 61px;"></a>
                    <ul class="dropdown-menu " role="menu">
                        <li><a href="profile.php"><i class="fa fa-edit"></i>Edit Profile</a></li>
                        <li><a href="controller.php?type=logout"><i class="fa fa-sign-in"></i>Logout</a></li>    
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
		<div class="clearfix">
		</div>
		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav" id="side-menu">
					<li class="active"> 
						<a class="hvr-bounce-to-right" href="home.php"><i class="fa fa-dashboard nav_icon"></i> <span class="nav-label">Dashboard</span></a>
					</li>
					<?php //echo  "Role".$_SESSION['id_user_role'];
					//exit;?>
					<?php if($_SESSION['id_user_role']==1){?>
						<li class="">
							<a class=" hvr-bounce-to-right" href="#"><i class="fa fa-users nav_icon"></i> <span class="nav-label">User</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse" aria-expanded="false">
								<li><a href="userrole.php"	class=" hvr-bounce-to-right"><i class="fa fa-sitemap nav_icon"></i>	<span class="nav-label">User Role</span> </a></li>
								<li><a href="users.php" class=" hvr-bounce-to-right"><i class="fa fa-user nav_icon"></i>	<span class="nav-label">Users </span></a></li>
							</ul>
						</li>		
					<?php } ?>
					<li class="">
						<a class=" hvr-bounce-to-right" href="#"><i class="fa fa-indent nav_icon"></i> <span class="nav-label">Catalog</span><span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse" aria-expanded="false">
							<li><a href="category.php" 	class="hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i>	<span class="nav-label">Category</span></a></li>
							<li><a href="posts.php"	class="hvr-bounce-to-right"><i class="fa fa-file-text-o nav_icon"></i>	<span class="nav-label">Posts</span> </a></li>	
							<li><a href="comments.php" 	class="hvr-bounce-to-right"><i class="fa fa-comments nav_icon"></i> 		<span class="nav-label">Comments</span> </a></li>
						</ul>
					</li>					
					<li class="">
						<a class=" hvr-bounce-to-right" href="#"><i class="fa fa-file-text-o nav_icon"></i> <span class="nav-label">CMS</span><span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse" aria-expanded="false">
							<li><a href="pages.php" 	class=" hvr-bounce-to-right"><i class="fa fa-newspaper-o nav_icon"></i>	<span class="nav-label">Pages</span> </a></li>
							<li><a href="gallery.php" 	class=" hvr-bounce-to-right"><i class="fa fa-picture-o nav_icon"></i> 	<span class="nav-label">Gallery</span> </a></li>
							<li><a href="message.php" 	class=" hvr-bounce-to-right"><i class="fa fa-inbox nav_icon"></i> 		<span class="nav-label">Message</span> </a></li>
							<li><a href="subscriber.php" 	class="hvr-bounce-to-right"><i class="fa fa-user-plus nav_icon"></i><span class="nav-label">Subscribers</span></a></li>
						</ul>
					</li>
					<?php if($_SESSION['id_user_role']==1){?>
						<li class="">
							<a class=" hvr-bounce-to-right" href="#"><i class="fa fa-cog fa-spin nav_icon"></i> <span class="nav-label">Settings</span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse" aria-expanded="false">
								
								<li><a href="country.php" 	class=" hvr-bounce-to-right"><i class="fa fa-map-marker nav_icon"></i> <span class="nav-label">Countries</span> </a></li>
								<li><a href="site.php" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon"></i> 	<span class="nav-label">Site details</span> </a></li>
								
							</ul>
						</li>
					<?php } ?>
					
					<li class="">
						<a class=" hvr-bounce-to-right" href="#"><i class="fa fa-cog fa-spin nav_icon"></i> <span class="nav-label">TSPSC</span><span class="fa arrow"></span></a>
						<ul class="nav nav-second-level collapse" aria-expanded="false">
							<li>
								<a href="syllabus.php" 	class=" hvr-bounce-to-right"><i class="fa fa-picture-o nav_icon"></i> 	<span class="nav-label">Syllabus</span> </a>
							</li>
							<li>
								<a href="notification.php" 	class="hvr-bounce-to-right"><i class="fa fa-list nav_icon"></i> 		<span class="nav-label">Notification</span> </a>
							</li>
							<li>
								<a href="question.php" 	class="hvr-bounce-to-right"><i class="fa fa-list nav_icon"></i> 		<span class="nav-label">Questions</span> </a>
							</li>
							<li>
								<a href="subject.php" 	class=" hvr-bounce-to-right"><i class="fa fa-file-text-o nav_icon"></i> 	<span class="nav-label">Subject</span> </a>
							</li>
							
							<li>
								<a href="test.php" 	class=" hvr-bounce-to-right"><i class="fa fa-picture-o nav_icon"></i> 	<span class="nav-label">Question Papers</span> </a>
							</li>
							<li>
								<a href="currentaffairs.php" 	class=" hvr-bounce-to-right"><i class="fa fa-picture-o nav_icon"></i> 	<span class="nav-label">Current Affairs</span> </a>
							</li>
							<li>
								<a href="test.php" 	class=" hvr-bounce-to-right"><i class="fa fa-picture-o nav_icon"></i> 	<span class="nav-label">Test</span> </a>
							</li>
							
						</ul>
					</li>
					
					
					
					
				</ul>
			</div>
		</div>
	</nav>												