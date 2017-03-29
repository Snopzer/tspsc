<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		
		header('location:index.php');
	}
	$select = $conn->query("SELECT * FROM r_page order by id_page desc")or die(mysqli_error());
	$count = $select->num_rows;
?>  
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<script src="js/ckeditor/ckeditor.js"></script>
<?php if(!isset($_GET['action'])){?>
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<div class="content-main">	
			<div class="banner">
				<h2>
					<a href="home.php">Home</a>
					<i class="fa fa-angle-right"></i>
					<span>Pages</span>
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
											<td><h3 id="h3.-bootstrap-heading"> Pages - [<?php echo $count;?>]</h3></td>
											<td class="type-info text-right">
												<a href="pages.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
												<a href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"> <?php echo EDIT_BUTTON;?></span></span></a>
												<a href="javascript:fnDelete();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<form name="frmMain" method="post">
							<table  class="table">         		
								<tr class="table-row">
									<td class="table-img">
										<input type="checkbox" name="checkall" onClick="Checkall()"/>
									</td>
									<td class="table-text"><h6>Title</h6></td>
									<td class="table-text"><h6>Page Heading</h6></td>
									<td class="march"> Action </td>
								</tr>	
								<?php
									$title = filter_input(INPUT_POST, 'title');
									$meta_description = filter_input(INPUT_POST, 'meta_description');
									$meta_keywords = filter_input(INPUT_POST, 'meta_keywords');
									$page_heading = filter_input(INPUT_POST, 'page_heading');
									$page_description = filter_input(INPUT_POST, 'page_description');
									
									$page = false;
									if (array_key_exists('page', $_GET)) {
										$page = $_GET['page'];
									}
									// $page = $_GET["page"];
									if ($page == "" || $page == 1) {
										$page1 = 0;
										} else {
										$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
									}
									$select = $conn->query("SELECT * FROM r_page order by id_page desc limit $page1,".ADMIN_PAGE_LIMIT)or die(mysqli_error());
									if ($select) {
										while ($row = $select->fetch_assoc()) {
											?>
										<tr class="table-row">
											<td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $row["id_page"] ?>"></td>
											<td class="march"><h6><?php echo $row["title"] ?></h6></td>
											<td class="march"><h6><?php echo $row["page_heading"] ?></h6></td>
											
											<td>
												<a href="pages.php?id=<?php echo $row["id_page"] ?>&action=edit&page=<?php echo "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
													<a href="pages-controller.php?chkdelids=<?php echo $row["id_page"] ?>&action=delete&page=<?php echo "$page"?>"><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
												</td>
											</tr>
											<?php
											}
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
									$res1 = $conn->query("SELECT * FROM r_page");
									$count = $res1->num_rows;
									//echo "$count";
									$a = $count / ADMIN_PAGE_LIMIT;
									$a = ceil($a);
									 if ($count > ADMIN_PAGE_LIMIT) {
								?>
								<div class="horz-grid text-center">
									<ul class="pagination pagination-lg">
										
										<?php for ($b = 1; $b <= $a; $b++) { ?>
											<?php if ($b == $page) { ?>
												<li class="active"><a href="metaform.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="metaform.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
		<?php
			if (isset($_GET['action'])) {
			?>
			<div id="page-wrapper" class="gray-bg dashbard-1">
				<div class="content-main">
					<div class="banner">
						<h2>
							<a href="home.php">Home</a>
							<i class="fa fa-angle-right"></i>
							<span><a href="pages.php">Pages</a></span>
							<i class="fa fa-angle-right"></i>
							<span><?php echo ($_GET['action']=='edit')?'Edit page':'Add page';?></span>
						</h2>
					</div>
					<div class="grid-system">
						<div class="horz-grid">
							<div class="grid-hor">
								<h4 id="grid-example-basic">Page Details</h4>
								
							</div>
							<?php if($_GET['action'] == "edit"){
								$id = $_GET['id'];
								$page=$_GET['page'];
								$query = $conn->query("select * from r_page pg
								LEFT JOIN r_seo_url seo ON seo.id_page=pg.id_page where pg.id_page=$id")or die(mysqli_error());
								$result = $query->fetch_assoc();
							?>
							<form class="form-horizontal" action="pages-controller.php?id=<?php echo $id; ?>&action=update" method="post">
								<input type="hidden" name="action" value="edit"/>
								<input type="hidden" name="id" value="<?php echo $id; ?>">
								<input type="hidden" name="page" value='<? echo "$page"?>'>
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Title</label>
									<div class="col-sm-8">
										<input type="text" placeholder="Title" name="title" id="title" value="<?php echo $result["title"] ?>" class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="txtarea1">Meta description</label>
									<div class="col-sm-8"><textarea class="form-control1" rows="4" cols="50" id="meta_description"  name="meta_description"  style="width: 688px; height: 201px;"><?php echo $result["meta_description"] ?></textarea></div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Meta Keywords</label>
									<div class="col-sm-8">
										<input type="text" placeholder="Meta Keywords" name="meta_keywords" id="meta_keywords" value="<?php echo $result["meta_keywords"] ?>" class="form-control">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Page Heading</label>
									<div class="col-sm-8">
										<input type="text" placeholder="page_heading" name="page_heading" id="page_heading" value="<?php echo $result["page_heading"] ?>" class="form-control">
									</div>
								</div>
								
								
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Seo url</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" value="<?php echo  $result["seo_url"] ?>" id="title" name="seo_url" placeholder="seo_url">
								</div>
							</div>
							
                                
                                <div class="form-group">
									<label class="col-sm-2 control-label" for="txtarea1">Page Description</label>
									<div class="col-sm-8"><textarea class="form-control1" rows="4" cols="50" id="page_description" name="page_description"  style="width: 688px; height: 201px;"><?php echo stripslashes($result["page_description"]); ?></textarea></div>
								</div>
                                
                                
								<div class="row">
									<div class="col-sm-8 col-sm-offset-2">
										<input type="submit" value="<?php echo  UPDATE_BUTTON;?>" class="btn-primary btn">
									</div>
								</div>
							</form>
							<?php
								} elseif($_GET['action'] == "add") {
							?>
							
							<form class="form-horizontal" action="pages-controller.php" method="post">
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Title</label>
									<div class="col-sm-5">
										<input type="hidden" name="action" value="add"/>
										<input type="text" placeholder="Title" name="title" id="title" value="" class="form-control">
									</div>
								</div>
								
								
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="txtarea1">Meta description</label>
									<div class="col-sm-8"><textarea class="form-control1" rows="4" cols="50" id="meta_description"  name="meta_description"  style="width: 688px; height: 201px;"></textarea></div>
								</div>
								
								
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Meta Keywords</label>
									<div class="col-sm-5">
										<input type="text" placeholder="Meta Keywords" name="meta_keywords" id="meta_keywords" value="" class="form-control">
									</div>
								</div>
								
                                
								<div class="form-group">
									<label class="col-sm-2 control-label hor-form" for="inputEmail3">Page Heading</label>
									<div class="col-sm-5">
										<input type="text" placeholder="page heading" name="page_heading" id="page_heading" value="" class="form-control">
									</div>
								</div>
								
								
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Seo url</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="title" name="seo_url" placeholder="seo_url">
								</div>
							</div>
                                
                                <div class="form-group">
									<label class="col-sm-2 control-label" for="txtarea1">Page Description</label>
									<div class="col-sm-8"><textarea class="form-control1" rows="4" cols="50" id="page_description" name="page_description"  style="width: 688px; height: 201px;"></textarea></div>
								</div>
                                
                                
								
								<div class="row">
									<div class="col-sm-8 col-sm-offset-2">
										<input type="submit" value="<?php echo  SAVE_BUTTON;?>" class="btn-primary btn">
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
			
			
			<?php include_once('includes/footer.php'); ?>	
			<script language="JavaScript">
			/* editor script */
		var editor=CKEDITOR.replace('meta_description');
		var editor=CKEDITOR.replace('page_description');
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
                    window.location.href = "pages.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
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
                    document.frmMain.action = "pages-controller.php";
                    document.frmMain.submit()
                }
            }
        }
    </script>	

			
				

