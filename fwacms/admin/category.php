<?php
	ob_start();
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}
	
	
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = $_GET['page'];
	}
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	$categoryResult = $conn->query("SELECT * FROM r_category where deleted=0 order by id_category desc limit $page1,".ADMIN_PAGE_LIMIT)or die(mysqli_error());
	
	$categoryQuery = $conn->query("SELECT * FROM r_category where deleted=0 order by id_category desc") or die(mysqli_error());
	$categoryCount = mysqli_num_rows($categoryQuery);
	$a = $categoryCount / ADMIN_PAGE_LIMIT;
	$a = ceil($a);							
?>  
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<script src="js/ckeditor/ckeditor.js"></script>
<?php if (!isset($_GET['action']) & !isset($_GET['type'])) { ?>
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<div class="content-main">	
			<div class="banner">
				<h2>
					<a href="home.php">Home</a>
					<i class="fa fa-angle-right"></i>
					<span>Category</span>
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
											<td><h3 id="h3.-bootstrap-heading"> CATEGORIES - [<?php echo $categoryCount; ?>]</h3></td>
											<td class="type-info text-right">
												<a href="category.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
												<a  href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"> <?php echo EDIT_BUTTON;?></span></span></a>
												<a href="javascript:fnDelete();"><span class="btn btn-danger" id="delete"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
												<a href="category-controller.php?action=undo"><span class="btn btn-primary"><i class="fa fa-undo" aria-hidden="true"></i> <?php echo UNDO_BUTTON;?></span></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<form name="frmMain" method="post">
								<table class="table"> 
									<tr class="table-row">
										<td class="table-img">
											<input type="checkbox" name="checkall" onClick="Checkall()"/>
										</td>
										<td class="table-text"><h6>Name</h6></td>
										<td class="table-text desktop"><h6>Sort Order</h6></td>
										<td class="table-text desktop"><h6>Status</h6></td>
									</tr>
									<?php
										if ($categoryCount > 0) {
											while ($categoryData = $categoryResult->fetch_assoc()) {
											?>
											<tr class="table-row">
												<td class="table-img"><input type="checkbox" name="selectcheck" value="<?php echo $categoryData['id_category']?>"></td>
												<td class="march"><h6><?php echo $categoryData["name"] ?></h6></td>
												<td class="march desktop"><h6><?php echo $categoryData["sort_order"] ?></h6></td>
												<td class="march desktop"><h6><?php echo ($categoryData["status"]==1)?'Enable':'Disable'; ?></h6></td>
												<td><a href="category.php?id=<?php echo $categoryData["id_category"] ?>&action=edit&page=<?php echo "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
												<a href="category-controller.php?chkdelids=<?php echo $categoryData["id_category"] ?>&action=delete&page=<?php echo "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
												</td>
											</tr>
											<?php
											}
											} else {
										?>
										<tr class="table-row">
											<td class="table-img text-center" colspan="4"><?php echo ADMIN_NO_RECORDS_FOUND;?></td>
										</tr>
										<?php }
									?>
								</table>
								<input type="hidden" name="action"/>
								<input type="hidden" name="id"/>
								<input type="hidden" name="chkdelids"/>
								<input type="hidden" name="page" value="<?php echo "$page"; ?>"/>
							</form>
							<?php if ($categoryCount > ADMIN_PAGE_LIMIT) {	?>
								<div class="horz-grid text-center">
									<ul class="pagination pagination-lg">
										<?php for ($b = 1; $b <= $a; $b++) { ?>
											<?php if ($b == $page) { ?>
												<li class="active"><a href="category.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="category.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
	<?php
		if (isset($_GET['action'])) {
		?>
		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="content-main">
				<div class="banner">
					<h2>
						<a href="home.php">Home</a>
						<i class="fa fa-angle-right"></i>
						<span><a href="category.php">Category</a></span>
						<i class="fa fa-angle-right"></i>
						<span><?php echo ($_GET['action'] == 'edit') ? 'Edit Category' : 'Add Category'; ?></span>
					</h2>
				</div>
				<div class="grid-system">
					<div class="horz-grid">
						<div class="grid-hor">
							<h4 id="grid-example-basic">Category Details:</h4>
							
						</div>
						<?php
							if ($_GET['action'] == "edit") {
								$id = $_GET['id'];
								$page = $_GET['page'];
								
								/*$query = $conn->query("select c.*,su.* from r_category c, r_seo_url su where  c.id_category = su.id_category  and c.id_category=$id")or die(mysql_error());*/
								$query = $conn->query("select c.*,su.* from r_category c
								LEFT JOIN r_seo_url su ON c.id_category = su.id_category  where c.id_category=$id")or die(mysql_error());
								$result = $query->fetch_assoc();
							?>
							<form class="form-horizontal" action="category-controller.php" method="post" enctype="multipart/form-data">
								<input type="hidden" name="action" value="edit"/>
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input type="hidden" name="page" value='<?php echo "$page"?>'>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label hor-form">Name</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" value="<?php echo $result["name"] ?>" id="name" name="name">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label hor-form">Description</label>
									<div class="col-sm-8">
										<textarea class="form-control" id="description"  name="description"><?php echo $result["description"] ?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label hor-form">SEO URL</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" value="<?php echo $result["seo_url"] ?>" id="seo_url" name="seo_url" >
									</div>
								</div>
								
								
								
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label hor-form">Image</label>
									<div class="col-sm-8">
										<input type="hidden" name="preview_image" value="<?php echo $result["image"];?>">
										<input type="file" name="photo">
										<span id="prev_image_name"><?php echo $result["image"];?></span><br />
										<img style="display:none;" id="prev_image" src='../images/category/<?php echo  $result["image"] ?>' width="50" height="50">
										
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label hor-form">Meta Title</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" value="<?php echo  $result["meta_title"] ?>" id="meta_title" name="meta_title" >
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label ">Meta Keywords</label>
									<div class="col-sm-8">
										<textarea  name="meta_keywords" class="form-control"><?php echo  $result["meta_keywords"] ?></textarea> 
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label ">Meta Description</label>
									<div class="col-sm-8">
										<textarea  name="meta_description" id="metadescription" class="form-control"><?php echo  $result["meta_description"] ?></textarea> 
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label ">Sort Order</label>
									<div class="col-sm-8">
										<input type="text"  class="form-control" id="sort_order" name="sort_order" value="<?php echo $result["sort_order"] ?>">
									</div>
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
										<!--<button class="btn btn-default" type="reset">Reset</button>-->
									</div>
								</div></div>
						</form>
						<?php
							} elseif ($_GET['action'] == "add") {
						?>
						<form class="form-horizontal" action="category-controller.php" method="post" enctype="multipart/form-data">
							<input type="hidden" name="action" value="add"/>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="" id="name" name="name" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Description</label>
								<div class="col-sm-8">
									<textarea  class="form-control" id="description" name="description" ></textarea>
								</div>
							</div>
							
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">SEO URL</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="" id="seo_url" name="seo_url" >
								</div>
							</div>
							
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Image</label>
								<div class="col-sm-8">
									<input type="file" name="photo">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Meta Title</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="meta_title">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label ">Meta Keywords</label>
								<div class="col-sm-8">
									<textarea  name="meta_keywords" class="form-control"></textarea> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label ">Meta Description</label>
								<div class="col-sm-8">
									<textarea id='metadescription' name="meta_description" class="form-control"></textarea> 
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label ">Sort Order</label>
								<div class="col-sm-8">
									<input type="number"  min="1" class="form-control" id="sort_order" name="sort_order" placeholder="Sort Order">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Status</label>
								<div class="col-sm-8">
									<select name="status" id="status" class="form-control1" >
										<option name="enable"  value="1">Enable</option>
										<option name="disable"  value="0">Disable</option>
									</select>
								</div>                      
							</div>
							
							
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<input type="submit" value="<?php echo SAVE_BUTTON;?>" class="btn-primary btn">
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
	
	<script language="JavaScript">
		/* editor script */
		var editor=CKEDITOR.replace('description');
		//var editor=CKEDITOR.replace('metadescription');
		/* editor script */
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
					window.location.href = "category.php?action=edit&page=<?php echo "$page"?>&id=" + arrval[0];
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
					document.frmMain.action = "category-controller.php";
					document.frmMain.submit()
				}
			}
		}
	</script>	
	
<?php include_once('includes/footer.php'); ?>							