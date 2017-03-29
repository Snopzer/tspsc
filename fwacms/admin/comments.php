<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		
		header('location:index.php');
	}
	
	$res1 = $conn->query("SELECT * FROM r_comment");
	$commentCount = $res1->num_rows;
	
	$a = $commentCount / ADMIN_PAGE_LIMIT;
	$a = ceil($a);
	
	
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = $_GET['page'];
	}
	//  $page = $_GET["page"];
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	$commentQuery = $conn->query("SELECT * FROM r_comment order by id_comment desc limit $page1,".ADMIN_PAGE_LIMIT)or die(mysqli_error());
?>  
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<?php if (!isset($_GET['action']) & !isset($_GET['type'])) { ?>
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<div class="content-main">	
			<div class="banner">
				<h2>
					<a href="home.php">Home</a>
					<i class="fa fa-angle-right"></i>
					<span>Comments</span>
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
											<td><h3 id="h3.-bootstrap-heading"> comments - [<?php echo $commentCount;	?>]</h3>
											</td>
											<td class="type-info text-right">
												<a href="comments.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"><?php echo ADD_BUTTON;?></span></span></a> 
												<a href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"><?php echo EDIT_BUTTON;?></span></span></a>
												<a href="javascript:fnDelete();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<form name="frmMain" >
								<table class="table"> 
									<tr class="table-row">
										<td class="table-img">
											<input type="checkbox" name="checkall" onClick="Checkall()"/>
										</td>
										<td class="table-text"><h6>Name</h6></td>
										<td class="table-text desktop"><h6>Email</h6></td>
										<td class="table-text desktop"><h6>Website</h6></td>
										<!--<td class="table-text desktop"><h6>Subject</h6></td>-->
										<td class="march"> Action </td>
									</tr>
									<?php
										
										while ($row = $commentQuery->fetch_assoc()) {
										?>
										<tr class="table-row">
											<td class="table-img"><input type="checkbox" name="selectcheck" value="<?php echo $row["id_comment"] ?>"></td>
											<td class="march"><h6><?php echo $row["name"] ?></h6></td>
											<td class="march desktop"><h6><?php echo $row["email"] ?></h6></td>
											<td class="march desktop"><h6><?php echo $row["website"] ?></h6></td>
											<!--<td class="march desktop"><h6><?php echo $row["subject"] ?></h6></td>-->
											<td><a href="comments.php?id=<?php echo $row["id_comment"] ?>&action=edit&page=<?php echo "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
											<a href="comments-controller.php?chkdelids=<?php echo $row["id_comment"] ?>&action=delete&page=<?php echo "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
											</td>
										</tr>
										<?php
										}
									?>
								</table>
								<input name="uid" type="hidden" value="<?php echo $_REQUEST["uid"]; ?>">
								<input type="hidden" name="action"/>
								<input type="hidden" name="id"/>
								<input type="hidden" name="chkdelids"/>
								<input type="hidden" name="page" value="<?php echo "$page"; ?>"/>
							</form>
							<?php
								
								if ($commentCount >  ADMIN_PAGE_LIMIT) {
								?>
								<div class="horz-grid text-center">
									<ul class="pagination pagination-lg">
										
										<?php for ($b = 1; $b <= $a; $b++) { ?>
											<?php if ($b == $page) { ?>
												<li class="active"><a href="comments.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="comments.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
	<?php }// show all users ends here?>
	<?php
		if (isset($_GET['action'])) {
		?>
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="content-main">
				<div class="banner">
					<h2>
						<a href="home.php">Home</a>
						<i class="fa fa-angle-right"></i>
						<span><a href="comments.php">Comments</a></span>
						<i class="fa fa-angle-right"></i>
						<span><?php echo ($_GET['action'] == 'edit') ? 'Edit Comment' : 'Add Comment'; ?></span>
					</h2>
				</div>
				<div class="grid-system">
					<div class="horz-grid">
						<div class="grid-hor">
							<h4 id="grid-example-basic">Comment Details:</h4>
							
						</div>
						<?php
							if ($_GET['action'] == "edit") {
								$id = $_GET['id'];
								$page = $_GET['page'];
								$query = $conn->query("select * from r_comment where id_comment=$id")or die(mysqli_error());
								$result = $query->fetch_assoc();
							?>
							<form class="form-horizontal" action="comments-controller.php" method="post">
								<input type="hidden" name="action" value="edit"/>
								<input type="hidden" name="id" value="<?php echo $result["id_comment"] ?>">
								<input type="hidden" name="page" value='<? echo "$page"?>'>
								
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="name" id="name" value="<?php echo $result["name"] ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Email</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="email" id="email" value="<?php echo $result["email"] ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Website</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="website" id="website" value="<?php echo $result["website"] ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Subject</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="subject" id="subject" value="<?php echo $result["subject"] ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Message</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="message" id="message" value="<?php echo $result["message"] ?>">
									</div>
								</div>	
								
								<div class="row">
									<div class="col-sm-8 col-sm-offset-2">
										<input type="submit" value="<?php echo EDIT_BUTTON;?>" class="btn-primary btn">
										<!--<button class="btn btn-default" type="reset">Reset</button>-->
									</div>
								</div>	
							</form>
							<?php
								} elseif ($_GET['action'] == "add") {
							?>
							<form class="form-horizontal" action="comments-controller.php" method="post">
								<input type="hidden" name="action" value="add"/>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="name" id="name" value="">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Email</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="email" id="email" value="">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Website</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="website" id="website">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Subject</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="subject" id="subject" value="">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Message</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="message" id="message" value="">
									</div>
								</div>	
								<div class="row">
									<div class="col-sm-8 col-sm-offset-2"><br>
										<input type="submit" value="<?php echo ADD_BUTTON;?>" class="btn-primary btn">
										<!--<button class="btn btn-default" type="reset">Reset</button>-->
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
		<?php
			if (isset($_GET['type']) && $_GET['type'] == "search") {
				$search = $_POST['search'];
				//echo "$search";
				//exit;
				$query = $conn->query("SELECT * FROM `r_comment` WHERE CONCAT( `message`) LIKE '%" . $search . "%' ")or die(mysqli_error());
			?>
			
			
			<div id="page-wrapper" class="gray-bg dashbard-1">
				<div class="content-main"> 
					<div class="banner">
						<h2>
							<a href="home.php">Home</a>
							<i class="fa fa-angle-right"></i>
							<span>Countries</span>
						</h2>
					</div>
					<div class="grid-system">
						<div class="horz-grid">
							<div class="grid-system">
								<div class="horz-grid">
									<div class="bs-example">
										<table class="table">
											<tbody>
												<tr>
													<td><h1 id="h1.-bootstrap-heading"> Countries - [<?php
														$select = $conn->query("SELECT * FROM `r_comment` WHERE CONCAT( `message`) LIKE '%" . $search . "%' ")or die(mysqli_error());
														$count = $select->num_rows;
														echo "$count";
													?>]</h1></td>
													<td class="type-info text-right">
														<a href="category.php?action=add"><span class="btn btn-success">Add New</span></a> 
														<a><span class="btn btn-primary">Edit</span></a>
														<a><span class="btn btn-danger">Delete</span></a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<form action="comments.php?type=search" method="post">
										<table class="table"> 
											
											<tr class="table-row">
												<td class="table-img">&nbsp;</td>
												<td class="march"><h6><input class="form-control" type="text" name="search" id="message"></h6></td>
												<td class="march"><h6><input class="btn btn-default" type="submit"></h6></td>                                
											</tr>
											
											<tr class="table-row">
												<td class="table-img">
													<input type="checkbox" id="selectall" onClick="selectAll(this)" >
												</td>
												<td class="table-text"><h6>Messages</h6></td>
												<td class="table-text"><h6>Action</h6></td>
												<!--<td class="table-text"><h6>Status</h6></td>-->
											</tr>
											<?php
												$page = false;
												if (array_key_exists('page', $_GET)) {
													$page = $_GET['page'];
												}
												//  $page = $_GET["page"];
												if ($page == "" || $page == 1) {
													$page1 = 0;
													} else {
													$page1 = ($page * 5) - 5;
												}
												$query = $conn->query("SELECT * FROM `r_comment` WHERE CONCAT( `message`) LIKE '%" . $search . "%' ")or die(mysqli_error());
												if ($query) {
													
													if (($query->num_rows) > 0) {
														while ($row = $query->fetch_assoc()) {
														?>
														<tr class="table-row">
															<td class="table-img"><input type="checkbox" name="colors[]"></td>
															<td class="march"><h6><?php echo $row["message"] ?></h6></td>
															<td><a href="comments.php?id=<?php echo $row["id_comment"] ?>&action=edit&page=<?php echo "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
															<a href="comments-controller.php?id=<?php echo $row["id_comment"] ?>&action=delete&page=<?php echo "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
															</td>
														</tr>
														<?php }
														} else {
													?>
													<tr class="table-row">
														<td class="march"></td>
														<td class="march"><h3>NO RESULTS MATCHING</h3></td>
														
														<?php
														}
													}
												?>
											</table>
										</form>
										<?php
											$res1 = $conn->query("SELECT * FROM `r_comment` WHERE CONCAT( `message`) LIKE '%" . $search . "%'");
											$count = $res1->num_rows;
											//echo "$count";
											$a = $count / 5;
											$a = ceil($a);
											if ($count > 5) {
											?>
											<div class="horz-grid text-center">
												<ul class="pagination pagination-lg">
													
													<?php for ($b = 1; $b <= $a; $b++) { ?>
														<?php if ($b == $page) { ?>
															<li class="active"><a href="comments.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
															<?php } else { ?>
															<li><a href="comments.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
					
					
					
				<?php } ?>
				
				
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
								window.location.href = "comments.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
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
								document.frmMain.action = "comments-controller.php";
								document.frmMain.submit()
							}
						}
					}
				</script>	