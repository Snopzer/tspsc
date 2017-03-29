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
	if($_SESSION['id_user_role']==1){
		$selectPosts = $conn->query("SELECT * FROM r_post order by id_post desc");
	}else{
		$selectPosts = $conn->query("SELECT * FROM r_post where id_user=".$_SESSION['id']." order by id_post desc");
	}
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
	
	if($_SESSION['id_user_role']==1){
		$selectPostList = $conn->query("SELECT * FROM r_post order by id_post desc limit ".$page1.",".ADMIN_PAGE_LIMIT)or die(mysqli_error());
	}else{
		$selectPostList = $conn->query("SELECT * FROM r_post where id_user=".$_SESSION['id']." order by id_post desc limit ".$page1.",".ADMIN_PAGE_LIMIT)or die(mysqli_error());
	}
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
					<span>Posts</span>
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
											<td><h3 id="h3.-bootstrap-heading"> POSTS - [<?php echo $postCount;?>]</h3></td>
											<td class="type-info text-right">
												<a href="posts.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
												<a  href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"> <?php echo EDIT_BUTTON;?></span></span></a>
												<a href="javascript:fnDelete();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
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
									<td class="table-text desktop"><h6>Date Added</h6></td>
									<td class="table-text desktop"><h6>Status</h6></td>
									<td class="table-text"><h6>&nbsp;</h6></td>
								</tr>
								<?php	while ($post = $selectPostList->fetch_assoc()) {	?>
									<tr class="table-row <?php echo ($post["status"]==1)?'warning':'danger'; ?>">
										<td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $post["id_post"] ?>"></td>
										<td class="march"><h6><?php echo  str_replace('\"', '"',str_replace("\'", "'", $post["title"])) ?></h6></td>
										<td class="march desktop"><h6><?php echo  $post["date_added"] ?></h6></td>
										<td class="march desktop"><h6><?php echo ($post["status"]==1)?'Enable':'Disable'; ?></h6></td>
										<td><a href="posts.php?id=<?php echo  $post["id_post"] ?>&action=edit&page=<?php echo  "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
										<a href="post-controller.php?chkdelids=<?php echo  $post["id_post"] ?>&action=delete&page=<?php echo  "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
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
												<li class="active"><a href="posts.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="posts.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
						<span><a href="posts.php">Posts</a></span>
						<i class="fa fa-angle-right"></i>
						<span><?php echo ($_GET['action']=='edit')?'Edit Post':'Add Post';?></span>
					</h2>
				</div>
				<div class="grid-system">
					<div class="horz-grid">
						<?php if($_GET['action'] == "edit"){
							$id = $_GET['id'];
							$page=$_GET['page'];
							if($_SESSION['id_user_role']==1){
								$query = $conn->query("select p.id_user as userId,p.id_category as category , p.*,su.* from r_post p
								LEFT JOIN r_seo_url su ON su.id_post = p.id_post WHERE p.id_post=$id")or die(mysqli_error());
							}else{
								$query = $conn->query("select p.id_user as userId,p.id_category as category , p.*,su.* from r_post p LEFT JOIN r_seo_url su ON su.id_post = p.id_post WHERE p.id_post=$id and p.id_user=".$_SESSION['id']."")or die(mysqli_error());	
							}
							$result = $query->fetch_assoc();
							
						?>
						<form class="form-horizontal" action="post-controller.php" method="post" enctype="multipart/form-data" >
							<input type="hidden" name="action" value="edit"/>
							<input type="hidden" name="id" value="<?php echo $id ?>">
							<input type="hidden" name="page" value='<?php echo "$page"?>'>
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Information</h4>
							</div>
							
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Title</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["title"])) ?>" id="title" name="title" placeholder="title">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Short Description</label>
								<div class="col-sm-8">
									<textarea  name="short_description" class="form-control" rows="6">
										<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["short_description"])) ?>
									</textarea>  
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
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Image</label>
								<div class="col-sm-8">
									<input type="hidden" name="preview_image" value="<?php echo $result["image"];?>">
									<input type="file" name="photo">
									<span id="prev_image_name"><?php echo $result["image"];?></span><br />
									<img style="display:none;" id="prev_image" src='../images/post/<?php echo  $result["image"] ?>' width="50" height="50"> 
									
								</div>
							</div>
							
							<div class="grid-hor">
								<h4 id="grid-example-basic">SEO Related Information</h4>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Seo url</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo  $result["seo_url"] ?>" id="title" name="seo_url" placeholder="seo_url">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Meta Title</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["meta_title"])) ?>" id="meta_title" name="meta_title" placeholder="meta_title">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Meta Keywords</label>
								<div class="col-sm-8">
									<textarea  name="meta_keywords" class="form-control" rows="6"><?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["meta_keywords"])) ?></textarea> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Meta Description</label>
								<div class="col-sm-8">
									<textarea  name="meta_description" class="form-control" rows="6"><?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["meta_description"])) ?></textarea> 
								</div>
							</div>
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Settings</h4>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Category</label>
								<div class="col-sm-8">
									<select name="category" id="selector1" class="form-control selectpicker" >
										<option value="">select category</option>
										<?php
											$row = $conn->query("select * from r_category")or die(mysqli_error());						
											while ($run = $row->fetch_assoc()) {
												if($run['id_category']==$result['category']){?> 
												<option value="<?php echo $run['id_category']?>" selected><?php echo $run['name']?></option>
												<?php }else{ ?>
												<option value="<?php echo $run['id_category']?>"><?php echo $run['name']?></option>
												
												<?php }
											?>
										<?php }?>
									</select>
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
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Source</h4>
					 			</div>
					 		<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Source</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="source" value="<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["source"])) ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Image Surce</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="image_source" value="<?php echo  str_replace('\"', '"',str_replace("\'", "'", $result["image_source"])) ?>">
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
						<form class="form-horizontal" action="post-controller.php" method="post" enctype="multipart/form-data">
							<input type="hidden" name="action" value="add"/>
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Information</h4>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Title</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="title" name="title" placeholder="title">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Short Description</label>
								<div class="col-sm-8">
									<textarea  name="short_description" class="form-control" rows="6"></textarea> 
								</div>
							</div>
							
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Description</label>
								<div class="col-sm-8">
									<textarea  name="description" id="description" class="form-control" rows="6"></textarea> 	
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Image</label>
								<div class="col-sm-8">
									<input type="file" id="photo" name="photo" placeholder="photo">
								</div>
							</div>
							<div class="grid-hor">
								<h4 id="grid-example-basic">SEO Related Information</h4>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Seo url</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="seo_url" placeholder="seo_url">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Meta Title</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="meta_title" placeholder="meta_title">
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label ">Meta Keywords</label>
								<div class="col-sm-8">
									<textarea  name="meta_keywords" class="form-control" rows="6"></textarea> 
								</div>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label ">Meta Description</label>
								<div class="col-sm-8">
									<textarea  name="meta_description" class="form-control" rows="6"></textarea> 
								</div>
							</div>
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Settings</h4>
							</div>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Category</label>
								<div class="col-sm-8">
									<select name="category" id="selector1" class="form-control" >
										<option value="0">Select Category</option>
										<?php  
											$row = $conn->query("select * from r_category ")or die(mysqli_error());
											while ($run = $row->fetch_assoc()) {	?>
											<option value="<?php echo $run['id_category'] ?>"><?php echo $run['name'] ?></option>
										<?php }?>
									</select>
								</div>                      
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
							<div class="grid-hor">
								<h4 id="grid-example-basic">Article Source</h4>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Source</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="source" placeholder="Source Link">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Image Surce</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="image_source" placeholder="Image Source Link">
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
                    window.location.href = "posts.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
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
                    document.frmMain.action = "post-controller.php";
                    document.frmMain.submit()
                }
            }
        }
    </script>