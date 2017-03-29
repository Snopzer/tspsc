<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	include_once('includes/header.php'); 
	include_once('includes/menu.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	$id = (int)$_POST['id'];
	// display all posts
	$selectSites = $conn->query("SELECT * FROM r_site_details order by id desc");
	$siteCount = mysqli_num_rows($selectSites);
	
	$pages = $siteCount / ADMIN_PAGE_LIMIT;
	$pages = ceil($pages);
	
	//pagination
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = $_GET['page'];
	}
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	$selectSiteList = $conn->query("SELECT * FROM r_site_details order by id desc limit ".$page1.",".ADMIN_PAGE_LIMIT)or die(mysqli_error());					
	
?>  

<script src="js/ckeditor/ckeditor.js"></script>
<?php if(!isset($_GET['action'])){?>
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<div class="content-main">	
			<div class="banner">
				<h2>
					<a href="home.php">Home</a>
					<i class="fa fa-angle-right"></i>
					<span>Sites</span>
				</h2>
			</div>
			<div class="grid-system">
				<div class="horz-grid">
					<div class="grid-system">
						<?php if(isset($_GET['message']) && $_GET['message']!=''){ ?>
							<div class="alert alert-<?php echo $_GET['response']?> fade in">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								<?php echo $_GET['message'];?>
							</div>
						<?php } ?>
						<div class="horz-grid">
							<div class="bs-example">
								<table class="table">
									<tbody>
										<tr>
											<td><h3 id="h3.-bootstrap-heading"> Sites - [<?php echo $siteCount;?>]</h3></td>
											<td class="type-info text-right">
												<a href="site.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
												<a href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"> <?php echo EDIT_BUTTON;?></span></span></a>
												<a href="javascript:fnDelete();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<table class="table"> 
								<form name="frmMain" action="search.php?type=search" method="post">
									<tr class="table-row">
										<td class="table-img">&nbsp;</td>
										<td class="march"><h6><input class="form-control" type="text" name="department" id="department"></h6></td>
										<td class="march"><h6><input class="btn btn-default" type="submit"  name="search" value="Search"></h6></td>                                
									</tr>
									
									<tr class="table-row">
										<td class="table-img">
											<input type="checkbox" name="checkall" onClick="Checkall()"/>
										</td>
										<td class="table-text"><h6>Site Name</h6></td>
										<td class="table-text desktop"><h6>Owner Email</h6></td>
										<td class="table-text desktop"><h6>Email From</h6></td>
										<td class="table-text desktop"><h6>Phone</h6></td>
									</tr>
									<?php	while ($site = $selectSiteList->fetch_assoc()) {	?>
										<tr class="table-row">
											<td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $site["id"] ?>"></td>
											<td class="march"><h6><?php echo $site["site_name"] ?></h6></td>
											<td class="march desktop"><h6><?php echo $site["owner_name"] ?></h6></td>
											<td class="march desktop"><h6><?php echo $site["owner_email"] ?></h6></td>
											<td class="march desktop"><h6><?php echo $site["phone"] ?></h6></td>
											<td><a href="site.php?id=<?php echo  $site["id"] ?>&action=edit&page=<?php echo  "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
											<a href="site-controller.php?chkdelids=<?php echo  $site["id"] ?>&action=delete&page=<?php echo  "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
											</td>
										</tr>
									<?php	}	?>
								</table>
								<input name="uid" type="hidden" value="<?php echo $_REQUEST["uid"]; ?>">
								<input type="hidden" name="action"/>
								<input type="hidden" name="id"/>
								<input type="hidden" name="chkdelids"/>
								<input type="hidden" name="page" value="<?php echo "$page"; ?>"/>
							</form>
							<?php	if ($siteCount > ADMIN_PAGE_LIMIT) {	?>
								<div class="horz-grid text-center">
									<ul class="pagination pagination-lg">
										<?php for ($b = 1; $b <= $pages; $b++) { ?>
											<?php if ($b == $page) { ?>
												<li class="active"><a href="site.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="site.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
												<?php
												}
											}
										?>
									</ul>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php  }// show all users ends here?>
	<?php 	if (isset($_GET['action'])) {		?>
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="content-main">
				<div class="banner">
					<h2>
						<a href="home.php">Home</a>
						<i class="fa fa-angle-right"></i>
						<span><a href="site.php">Sites</a></span>
						<i class="fa fa-angle-right"></i>
						<span><?php echo ($_GET['action']=='edit')?'Edit Site':'Add Site';?></span>
					</h2>
				</div>
				<div class="grid-system">
					<div class="horz-grid">
						<?php if($_GET['action'] == "edit"){
							$id = $_GET['id'];
							$page=$_GET['page'];
							$query = $conn->query("select * from r_site_details where id=$id")or die(mysqli_error());
							$result = $query->fetch_assoc();
						?>
						
						<form class="form-horizontal" action="site-controller.php" method="post" enctype="multipart/form-data" >
							
							<div class="grid_3 grid_5">
							<h4 id="grid-example-basic">Site Details:</h4>
								<div class="but_list">
									<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
										<ul id="myTab" class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active"><a href="#general" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">General Details</a></li>
											<li role="presentation" class=""><a href="#socialmedia" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Social Media</a></li>
											<li role="presentation" class=""><a href="#seo" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">SEO</a></li>
											<li role="presentation" class=""><a href="#googledetails" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Google Details</a></li>
										</ul>
										<div id="myTabContent" class="tab-content">
											<div role="tabpanel" class="tab-pane fade active in" id="general" aria-labelledby="general-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Site Name</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["site_name"] ?>" name="site_name" placeholder="Enter Site Name">
													</div>
												</div>
												
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">owner Name</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["owner_name"] ?>" name="owner_name" placeholder="Enter owner Name">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Owner Email</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["owner_email"] ?>" name="owner_email" placeholder="Enter Owner Email">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Admin page Limit</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["admin_page_limit"] ?>" name="admin_page_limit" placeholder="Admin page Limit">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">POST Description Length</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["post_description_length"] ?>" name="post_description_length" placeholder="POST Description Length">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Admin Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["admin_mail"] ?>" name="admin_mail" placeholder="Enter Admin Mail">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">From Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["from_mail"] ?>" name="from_mail" placeholder="Enter FROM Mail">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Reply To Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["reply_to_mail"] ?>" name="reply_to_mail" placeholder="Enter Reply To Mail">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Site Copy Rights</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["site_copy_rights"] ?>" name="site_copy_rights" placeholder="Enter Site Copy Rights">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Phone</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["phone"] ?>" name="phone" placeholder="Enter Phone Number">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Contact Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["contact_mail"] ?>" name="contact_mail" placeholder="Enter YOUTUBE URL">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Contact Phone</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["contact_phone"] ?>" name="contact_phone" placeholder="Contact Phone">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Contact Address</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["contact_address"] ?>" name="contact_address" placeholder="Contact Address">
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="socialmedia" aria-labelledby="socialmedia-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL TWITTER URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_twitter_url"] ?>" name="social_twitter_url" placeholder="Enter TWITTER URL">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL GOOGLE+ URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_googleplus_url"] ?>" name="social_googleplus_url" placeholder="Enter GOOGLE+ URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL LINKEDIN URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_linkedin_url"] ?>" name="social_linkedin_url" placeholder="Enter LINKEDIN URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL BEHANCE URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_behance_url"] ?>" name="social_behance_url" placeholder="Enter BEHANCE URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL VIMIO URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_vimio_url"] ?>" name="social_vimio_url" placeholder="Enter VIMIO URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL FACEBOOK URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_facebook_url"] ?>" name="social_facebook_url" placeholder="Enter FACEBOOK URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL YOUTUBE URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["social_youtube_url"] ?>" name="social_youtube_url" placeholder="Enter YOUTUBE URL">
													</div>
												</div>
											</div>
											
											<div role="tabpanel" class="tab-pane fade" id="seo" aria-labelledby="seo-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Site Title</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" value="<?php echo  $result["site_title"] ?>" name="site_title" placeholder="Enter Site Title">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label ">Site Keywords</label>
													<div class="col-sm-8">
														<textarea  name="site_keywords" id="site_keywords" class="form-control"><?php echo  $result["site_keywords"] ?></textarea> 
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label ">Site Description</label>
													<div class="col-sm-8">
														<textarea  name="site_description" id="site_description" class="form-control"><?php echo  $result["site_description"] ?></textarea> 
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="googledetails" aria-labelledby="googledetails-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label ">Google Analytics</label>
													<div class="col-sm-8">
														<textarea  name="google_analytics" id="google_analytics" class="form-control"><?php echo  $result["google_analytics"] ?></textarea> 
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="action" value="edit"/>
							<input type="hidden" name="id" value="<?php echo $result["id"] ?>">
							<input type="hidden" name="page" value='<?php echo "$page"?>'>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<input type="submit" value="<?php echo UPDATE_BUTTON;?>" class="btn-primary btn">
								</div>
							</div></div>
						</form>
						<?php
							} elseif($_GET['action'] == "add") {
						?>
						<form class="form-horizontal" action="site-controller.php" method="post" enctype="multipart/form-data">
							
							
							<div class="grid_3 grid_5">
								<h3 class="head-top">Site Details</h3>
								<div class="but_list">
									<div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
										<ul id="myTab" class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active"><a href="#general" id="general-tab" role="tab" data-toggle="tab" aria-controls="general" aria-expanded="false">General Details</a></li>
											<li role="presentation" class=""><a href="#socialmedia" role="tab" id="socialmedia-tab" data-toggle="tab" aria-controls="socialmedia" aria-expanded="true">Social Media</a></li>
											<li role="presentation" class=""><a href="#seo" role="tab" id="seo-tab" data-toggle="tab" aria-controls="seo" aria-expanded="true">SEO</a></li>
											<li role="presentation" class=""><a href="#googledetails" role="tab" id="googledetails-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Google Details</a></li>
										</ul>
										<div id="myTabContent" class="tab-content">
											<div role="tabpanel" class="tab-pane fade" id="general" aria-labelledby="general-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Site Name</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="site_name" placeholder="Enter Site Name">
													</div>
													</div>
												
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">owner Name</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="owner_name" placeholder="Enter owner Name">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Owner Email</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="owner_email" placeholder="Enter Owner Email">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Admin page Limit</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="admin_page_limit" placeholder="Admin page Limit">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">POST Description Length</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="post_description_length" placeholder="POST Description Length">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Admin Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="admin_mail" placeholder="Enter Admin Mail">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">From Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control"  name="from_mail" placeholder="Enter FROM Mail">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Reply To Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="reply_to_mail" placeholder="Enter Reply To Mail">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Site Copy Rights</label>
													<div class="col-sm-8">
														<input type="text" class="form-control"  name="site_copy_rights" placeholder="Enter Site Copy Rights">
													</div>
												</div>
												
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Phone</label>
													<div class="col-sm-8">
														<input type="text" class="form-control"  name="phone" placeholder="Enter Phone Number">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Contact Mail</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="contact_mail" placeholder="Enter YOUTUBE URL">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Contact Phone</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="contact_phone" placeholder="Contact Phone">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Contact Address</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="contact_address" placeholder="Contact Address">
													</div>
												</div>
												
											</div>
											<div role="tabpanel" class="tab-pane fade active in" id="socialmedia" aria-labelledby="socialmedia-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL TWITTER URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_twitter_url" placeholder="Enter TWITTER URL">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL GOOGLE+ URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_googleplus_url" placeholder="Enter GOOGLE+ URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL LINKEDIN URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_linkedin_url" placeholder="Enter LINKEDIN URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL BEHANCE URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_behance_url" placeholder="Enter BEHANCE URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL VIMIO URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_vimio_url" placeholder="Enter VIMIO URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL FACEBOOK URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_facebook_url" placeholder="Enter FACEBOOK URL">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">SOCIAL YOUTUBE URL</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="social_youtube_url" placeholder="Enter YOUTUBE URL">
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane fade active in" id="seo" aria-labelledby="seo-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label hor-form">Site Title</label>
													<div class="col-sm-8">
														<input type="text" class="form-control" name="site_title" placeholder="Enter Site Title">
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label ">Site Keywords</label>
													<div class="col-sm-8">
														<textarea  name="site_keywords" id="site_keywords" class="form-control"></textarea> 
													</div>
												</div>
												
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label ">Site Description</label>
													<div class="col-sm-8">
														<textarea  name="site_description" id="site_description" class="form-control"></textarea> 
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane fade" id="googledetails" aria-labelledby="googledetails-tab">
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label ">Google Analytics</label>
													<div class="col-sm-8">
														<textarea  name="google_analytics" id="google_analytics" class="form-control"></textarea> 
													</div>
												</div>
											</div>
											
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="action" value="add"/>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<input type="submit" value="<?php echo SAVE_BUTTON;?>" class="btn-primary btn">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php
			}// end of add
		}// end of action set edit/add
	?>
	
	<script language="JavaScript">	
		function selectAll(source) {
			checkboxes = document.getElementsByName('colors[]');
			for (var i in checkboxes)
			checkboxes[i].checked = source.checked;
		}
	</script>
	<?php include_once('includes/footer.php'); ?>	
	<script language="JavaScript">
		function fnDetails()
		{
			var obj = document.frmMain.elements;
			flag = 0;
			for (var i = 0; i < obj.length; i++)
			{
				if (obj[i].name == "selectcheck" && obj[i].checked)
				{
					flag = 1;
					break;
				}
			}
			if (flag == 0)
			{
				alert("Please make a selection from a list to Edit");
			} else if (flag == 1)
			{
				var checkedvals = "";
				for (var i = 0; i < obj.length; i++) {
					if (obj[i].checked == true) {
						checkedvals = checkedvals + "," + obj[i].value;
					}
				}
				var checkvals = checkedvals.substr(1);
				var arrval = checkvals.split(",");
				if (arrval.length > 1)
				{
					alert("Select Only One checkbox to edit");
				} else
				{
					window.location.href = "site.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
				}
			}
		}
	</script>
	
	
	<script language="JavaScript">
		function Checkall()
		{
			if (document.frmMain.checkall.checked == true)
			{
				var obj = document.frmMain.elements;
				for (var i = 0; i < obj.length; i++)
				{
					if ((obj[i].name == "selectcheck") && (obj[i].checked == false))
					{
						obj[i].checked = true;
					}
				}
			} else if (document.frmMain.checkall.checked == false)
			{
				var obj = document.frmMain.elements;
				for (var i = 0; i < obj.length; i++)
				{
					if ((obj[i].name == "selectcheck") && (obj[i].checked == true))
					{
						obj[i].checked = false;
					}
				}
			}
		}
		function fnDelete()
		{
			var obj = document.frmMain.elements;
			flag = 0;
			for (var i = 0; i < obj.length; i++)
			{
				if (obj[i].name == "selectcheck" && obj[i].checked) {
					flag = 1;
					break;
				}
			}
			if (flag == 0) {
				alert("Select Checkbox to Delete");
				} else if (flag == 1) {
				var i, len, chkdelids, sep;
				chkdelids = "";
				sep = "";
				for (var i = 0; i < document.frmMain.length; i++) {
					if (document.frmMain.elements[i].name == "selectcheck")
					{
						if (document.frmMain.elements[i].checked == true) {
							//alert(document.frmFinal.elements[i].value)
							chkdelids = chkdelids + sep + document.frmMain.elements[i].value;
							sep = ",";
						}
					}
				}
				ConfirmStatus = confirm("Do you want to DELETE selected User Role.?")
				if (ConfirmStatus == true) {
					document.frmMain.chkdelids.value = chkdelids
					document.frmMain.action.value = "delete"
					document.frmMain.action = "site-controller.php";
					document.frmMain.submit()
				}
			}
		}
	</script>												