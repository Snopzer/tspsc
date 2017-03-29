<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	$row = $conn->query("select * from r_user where id_user=" . $_SESSION['id'])or die(mysqli_error());
	$result = $row->fetch_assoc()or die(mysqli_error());
?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="content-main">
            <div class="banner">
                <h2>
                    <a href="home.php">Home</a>
                    <i class="fa fa-angle-right"></i>
                    <a href="profile.php">Profile</a>
                    <i class="fa fa-angle-right"></i>
                    <span>Edit Profile</span>
				</h2>
			</div>
            <div class=" profile">
                <div class="profile-bottom">
                    <h3><i class="fa fa-user"></i>Edit Profile</h3>
                    <div class="profile-bottom-top">
                        <div class="col-md-4 profile-bottom-img">
                            <img src="../images/user/<?php echo $result['image'] ?>" alt="<?php echo $result['name'] ?>" style="width:200px; height: 200px;">
						</div>
                        <div class="col-md-8 profile-text">
							
                            <form action="profile-controller.php" method="post" enctype="multipart/form-data">
                                <table><tr><td>Name</td><td>:</td><td><input type="text" class="form-control" name="name" id="name" value="<?php echo $result['name'] ?>"></td></tr>	
                                    <tr><td>Department</td>  
                                        <td>:</td><input type="hidden" name="id" value="<?php echo $result['id_user'] ?>">  
									<td><input type="text"  class="form-control" name="department" id="department" value="<?php echo $result['department'] ?>"></td></tr>                         
                                    <tr>
                                        <td>Email</td>
                                        <td> :</td>
                                        <td><input type="text" class="form-control" name="email" id="email" value="<?php echo $result['email'] ?>"></td>
									</tr>
                                    <tr>
                                        <td>Skills</td>
                                        <td> :</td>
                                        <td><input type="text" class="form-control" name="skills" id="skills" value="<?php echo $result['skills'] ?>"></td>
									</tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td> :</td>
                                        <td><input type="text" class="form-control" name="phone" id="phone" value="<?php echo $result['phone'] ?>"></td>
									</tr>
                                    <tr>
                                        <td>Country </td>
                                        <td>:</td>
                                        <td><div class="form-group">
											<div class="col-sm-14">
												<select name="country" id="selector1" class="form-control" >
													<option>select country</option>
													<?php $row = $conn->query("select * from r_country order by name asc")or die(mysqli_error());
														while ($run = $row->fetch_assoc()) {
															if($run['id_country']==$result['id_country']){ ?>
															<option value="<?php echo $run['id_country'] ?>" selected><?php echo $run['name'] ?></option>
															<?php }else{ ?>
															<option value="<?php echo $run['id_country'] ?>"><?php echo $run['name'] ?></option>
														<?php } ?>
														
													<?php } ?>
												</select>
											</div>
										</div></td>
									</tr>
                                    <tr>
                                        <td>Image </td>
                                        <td>:</td>
                                        <td>
										<input type="file" name="photo" placeholder="<?php echo $result["image"] ?>"></td>
										<input type="hidden" name="preview_image" value="<?php echo $result["image"] ?>">
									</tr>
									
								</table>
								
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="profile-bottom-bottom">
							<div class="profile-btn">
								<p><button type="submit" class="btn bg-red">Save changes</button></p>
								<div class="clearfix"></div>
							</div>
							<div class="clearfix"></div>
						</div>									
					</form>
				</div>
			</div>		
		<?php }// edit profile ends here  ?>
        <?php if (!isset($_GET['action'])) { ?>
            <div id="page-wrapper" class="gray-bg dashbard-1">
                <div class="content-main">
                    <!--banner-->	
                    <div class="banner">
                        <h2>
                            <a href="home.php">Home</a>
                            <i class="fa fa-angle-right"></i>
                            <span>Profile</span>
						</h2>
					</div>
                    <div class=" profile">
						
                        <div class="profile-bottom">
                            <h3><i class="fa fa-user"></i>Profile</h3>
                            <div class="profile-bottom-top">
                                <div class="col-md-4 profile-bottom-img">
									<img src="../images/user/<?php echo $result['image'] ?>" alt="<?php echo $result['name'] ?>" style="width:200px; height: 200px;">
									<input type="hidden" name="preview_image" value="<?php echo $result["image"] ?>">
								</div>
								<div class="col-md-8 profile-text">
									<table>
										<?php if ($result['name'] != "") { ?><h6><?php echo $result['name'] ?></h6><?php } ?> 
										<?php if ($result['department'] != "") { ?>
											<tr><td>Department</td>  
												<td>:</td>  
												<td><?php echo $result['department'] ?></td>
											</tr><?php } ?>                      
											<?php if ($result['email'] != "") { ?>
												<tr>
													<td>Email</td>
													<td> :</td>
													<td><a href="@gmail.com"><?php echo $result['email'] ?></a></td>
												</tr><?php } ?>
												<?php if ($result['skills'] != "") { ?>
													<tr>
														<td>Skills</td>
														<td> :</td>
														<td><?php echo $result['skills'] ?></td>
													</tr><?php } ?>
													<?php if ($result['phone'] != "") { ?>
														<tr>
															<td>Phone</td>
															<td> :</td>
															<td><?php echo $result['phone'] ?></td>
														</tr><?php } ?> 
														<?php if ($result['country'] != "") { ?>
															<tr>
																<td>Country </td>
																<td>:</td>
																<td><?php echo $result['country'] ?></td>
															</tr><?php } ?>
									</table>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="profile-bottom-bottom">
								<div class="profile-btn">
									<p><a href="profile.php?action=edit" class="pro">
										<?php if(!empty($result['name'])){ ?>
											<button type="submit" class="btn bg-red">Update Profile</button>
											<?PHP }else { ?>
											<button type="submit" class="btn bg-red glyphicon">Complete Profile</button>
										<?php } ?>
										
										
									</p></a>
								</div>
							</div>	
						</div>
					</div>
				<?php } // profile page ends here?>
			<?php include_once('includes/footer.php'); ?>										