<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	$id = (int)$_POST['id'];
	// display all posts
	$selectPosts = $conn->query("SELECT * FROM r_syllabus order by id_syllabus desc");
	
	$postCount = mysqli_num_rows($selectPosts);
	
	$pages = $postCount / ADMIN_PAGE_LIMIT;
	$pages = ceil($pages);
	
	//pagination
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = (int)$_GET['page'];
	}
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	
	$selectPostList = $conn->query("SELECT * FROM r_syllabus order by id_syllabus desc limit ".$page1.",".ADMIN_PAGE_LIMIT)or die(mysqli_error());
?>  
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<!-- editor script -->
<script src="js/ckeditor/ckeditor.js"></script>
<!-- editor script -->
<?php if(!isset($_GET['action'])){?>
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<div class="content-main">	
			<div class="banner">
				<h2>
					<a href="home.php">Home</a>
					<i class="fa fa-angle-right"></i>
					<span>Syllabus</span>
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
											<td><h3 id="h3.-bootstrap-heading"> SYLLABUSES - [<?php echo $postCount;?>]</h3></td>
											<td class="type-info text-right">
												<a href="syllabus.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
												<a  href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"> <?php echo EDIT_BUTTON;?></span></span></a>
												<a href="javascript:fnDelete();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
												<a href="javascript:export();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop">EXPORT</span></span></a>
												<!--<a><span class="btn btn-warning ">Enable</span></a>-->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<form name="frmMain" method="post">
								<table class="table table-hover"> 
									<tr class="table-row">
										<td class="table-img">
											<input type="checkbox" name="checkall" onClick="Checkall()"/>
										</td>
										<td class="table-text"><h6>Title</h6></td>
										<td class="table-text desktop"><h6>Description</h6></td>
										<td class="table-text desktop"><h6>pdf</h6></td>
										<td class="table-text desktop"><h6>Status</h6></td>
										<td class="table-text"><h6>&nbsp;</h6></td>
									</tr>
									<?php	while ($post = $selectPostList->fetch_assoc()) {	?>
										<tr class="table-row <?php echo ($post["status"]==1)?'warning':'danger'; ?>">
											<td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $post["id_syllabus"] ?>"></td>
											<td class="march"><h6><?php echo  str_replace('\"', '"',str_replace("\'", "'", $post["name"])) ?></h6></td>
											<td class="march desktop"><h6><?php echo  $post["description"] ?></h6></td>
											<td class="march desktop"><a href='../syllabus/<?php echo  $post["pdf"] ?>' target="_blank" ><h6><?php echo  $post["pdf"] ?></h6></a></td>
											<td class="march desktop"><h6><?php echo ($post["status"]==1)?'Enable':'Disable'; ?></h6></td>
											<td><a href="syllabus.php?id=<?php echo  $post["id_syllabus"] ?>&action=edit&page=<?php echo  "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
											<a href="syllabus-controller.php?chkdelids=<?php echo  $post["id_syllabus"] ?>&action=delete&page=<?php echo  "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
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
							<?php	if ($postCount > ADMIN_PAGE_LIMIT) {	?>
								<div class="horz-grid text-center">
									<ul class="pagination pagination-lg">
										<?php for ($b = 1; $b <= $pages; $b++) { ?>
											<?php if ($b == $page) { ?>
												<li class="active"><a href="syllabus.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="syllabus.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
						<span><a href="syllabus.php">Syllabus</a></span>
						<i class="fa fa-angle-right"></i>
						<span><?php echo ($_GET['action']=='edit')?'Edit Syllabus':'Add Syllabus';?></span>
					</h2>
				</div>
				<div class="grid-system">
					<div class="horz-grid">
						<?php if($_GET['action'] == "edit"){
							$id = $_GET['id'];
							$page=$_GET['page'];
							
							$query = $conn->query("select * from r_syllabus  WHERE id_syllabus=$id")or die(mysqli_error());
							
							$result = $query->fetch_assoc();
							
						?>
						<form class="form-horizontal" action="syllabus-controller.php" method="post" enctype="multipart/form-data" >
							<input type="hidden" name="action" value="edit"/>
							<input type="hidden" name="id" value="<?php echo $id ?>">
							<input type="hidden" name="page" value='<?php echo "$page"?>'>
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Information</h4>
							</div>
							
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Title</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["name"])) ?>" id="name" name="name" placeholder="name">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form ">Description</label>
								<div class="col-sm-8">
									<textarea name="description" id="description" class="form-control" rows="6">
										<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["description"])) ?>
									</textarea> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">PDF File</label>
								<div class="col-sm-8">
									<input type="hidden" name="preview_image" value="<?php echo $result["pdf"];?>">
									<input type="file" name="pdf">
									<span id="prev_image_name">	<a href='../syllabus/<?php echo  $result["pdf"] ?>' target="_blank" ><?php echo $result["pdf"];?></a></span><br /> 	
								</div>
							</div>
							
							
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Settings</h4>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Select Status</label>
								<div class="col-sm-8">
									<select name="status" id="status" class="form-control selectpicker"  >
										<option name="enable"  value="1" <?php if($result['status']==1){ echo "Selected";}?>>Enable</option>
										<option name="disable"  value="0" <?php if($result['status']==0){ echo "Selected";}?>>Disable</option>
									</select>
								</div>                      
							</div>
							
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<input type="submit" value="<?php echo UPDATE_BUTTON;?>" class="btn-primary btn">
								</div>
							</div></div>
						</form>
						
						<?php
							} elseif($_GET['action'] == "add") {
						?>
						<form class="form-horizontal" action="syllabus-controller.php" method="post" enctype="multipart/form-data">
							<input type="hidden" name="action" value="add"/>
							<div class="grid-hor">
								<h4 id="grid-example-basic">Syllabus Information</h4>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Syllabus Title (Name)</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="name" name="name" placeholder="title">
								</div>
							</div>
							
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Description</label>
								<div class="col-sm-8">
									<textarea  name="description" id="description" class="form-control" rows="6"></textarea> 	
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">PDF File</label>
								<div class="col-sm-8">
									<input type="file" id="pdf" name="pdf" placeholder="pdf">
								</div>
							</div>
							
							
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Settings</h4>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Status</label>
								<div class="col-sm-8">
									<select name="status" id="status" class="form-control" >
										<option name="enable"  value="1">Enable</option>
										<option name="disable"  value="0">Disable</option>
									</select>
								</div>                      
							</div>
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
		$('#prev_image_name').mouseover(function() {	$('#prev_image').show();	});
		$('#prev_image_name').mouseout(function() {	$('#prev_image').hide();	});
		
		/* editor script */
		var editor=CKEDITOR.replace('description');
		/* editor script */
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
					window.location.href = "syllabus.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
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
					document.frmMain.action = "syllabus-controller.php";
					document.frmMain.submit()
				}
			}
		}
	</script>				