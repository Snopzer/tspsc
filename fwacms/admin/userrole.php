<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		
		header('location:index.php');
	}
	$userQuery = $conn->query("SELECT * FROM r_user_role order by id_user_role desc")or die(mysqli_error());
	$userCount = mysqli_num_rows($userQuery);
	$pages = $userCount / ADMIN_PAGE_LIMIT;
	$pages = ceil($pages);
	
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = $_GET['page'];
	}
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	
	$userList = $conn->query("SELECT * FROM r_user_role order by id_user_role desc limit $page1,".ADMIN_PAGE_LIMIT)or die(mysqli_error());
?>  
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<?php if (!isset($_GET['action'])) { ?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="content-main">	
            <div class="banner">
                <h2>
                    <a href="home.php">Home</a>
                    <i class="fa fa-angle-right"></i>
                    <span>User Roles</span>
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
                                            <td><h3 id="h3.-bootstrap-heading"> USER ROLES - [<?php echo $userCount; ?>]</h3></td>
                                            <td class="type-info text-right">
                                                <a href="userrole.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
                                                <a  href="javascript:fnDetails();"><span class="btn btn-primary"><i class="fa fa-pencil white" aria-hidden="true"></i> <span class="desktop"> <?php echo EDIT_BUTTON;?></span></span></a>
                                                <a  href="javascript:fnDelete();"><span class="btn btn-danger"><i class="fa fa-remove white" aria-hidden="true"></i> <span class="desktop"><?php echo DELETE_BUTTON;?></span></span></a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
                            <table class="table"> 
                                <form name="frmMain" method="post">
                                    <tr class="table-row">
                                        <td class="table-img">
                                            <input type="checkbox" name="checkall" onClick="Checkall()"/>
										</td>
                                        <td class="table-text"><h6>Role</h6></td>
                                        <td class="table-text"colspan="2"><h6>Status</h6></td>
									</tr>
                                    <?php
										if (mysqli_num_rows($userList) > 0) {
											while ($user = $userList->fetch_assoc()) {
											?>
                                            <tr class="table-row">
                                                <td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $user["id_user_role"] ?>"/></td>
                                                <td class="march"><h6><?php echo $user["role"] ?></h6></td>
                                                <td class="march"><h6><?php echo ($user["status"] == 1) ? 'Enable' : 'Disable'; ?></h6></td>
                                                <td><a href="userrole.php?id=<?php echo $user["id_user_role"] ?>&action=edit&page=<?php echo "$page" ?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
												<a href="userrole-controller.php?chkdelids=<?php echo $user["id_user_role"] ?>&action=delete&page=<?php echo "$page" ?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
                                                </td>
											</tr>
                                            <?php
											}
											} else {
										?>
                                        <tr class="table-row">
                                            <td class="table-img text-center" colspan="4"><?php echo ADMIN_NO_RECORDS_FOUND;?></td>
										</tr>
									<?php } ?>
								</table>
								<input name="uid" type="hidden" value="<?php echo $_REQUEST["uid"]; ?>">
								<input type="hidden" name="action"/>
								<input type="hidden" name="id"/>
								<input type="hidden" name="chkdelids"/>
								<input type="hidden" name="page" value="<?php echo "$page"; ?>"/>
							</form>
                            <?php
								if ($userCount > ADMIN_PAGE_LIMIT) {
								?>
                                <div class="horz-grid text-center">
                                    <ul class="pagination pagination-lg">
										
                                        <?php for ($b = 1; $b <= $pages; $b++) { ?>
                                            <?php if ($b == $page) { ?>
                                                <li class="active"><a href="userrole.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
                                                <li><a href="userrole.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
	<?php }// show all users ends here ?>
    <?php if (isset($_GET['action'])) { ?>
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="content-main">
                <div class="banner">
                    <h2>
                        <a href="home.php">Home</a>
                        <i class="fa fa-angle-right"></i>
                        <span><a href="userrole.php">User Roles</a></span>
                        <i class="fa fa-angle-right"></i>
                        <span><?php echo ($_GET['action'] == 'edit') ? 'Edit User Role' : 'Add User Role'; ?></span>
					</h2>
				</div>
                <div class="grid-system">
                    <div class="horz-grid">
                        <div class="grid-hor">
                            <h4 id="grid-example-basic"><?php echo ($_GET['action'] == 'edit') ? 'Edit User Role' : 'Add User Role'; ?> Details:</h4>
						</div>
                        <?php
							if ($_GET['action'] == "edit") {
								if (isset($_GET['uid'])) {
									$id = $_GET['uid'];
									} else {
									$id = $_GET['id'];
								}
								$page = $_GET['page'];
								$query = $conn->query("select * from r_user_role  where id_user_role=$id")or die(mysql_error());
								$result = $query->fetch_assoc();
							?>
                            <form class="form-horizontal" action="userrole-controller.php" method="post">
                                <input type="hidden" name="action" value="edit"/>
                                <input type="hidden" name="id" value="<?php echo $result["id_user_role"] ?>">
                                <input type="hidden" name="page" value="<?php echo "$page" ?>"/>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Role</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["role"] ?>" id="role" name="role" placeholder="Role">
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Select Status</label>
                                    <div class="col-sm-8">
                                        <select name="status" id="status" class="form-control" >
                                            <option name="enable"  value="1" <?php
												if ($result['status'] == 1) {
													echo "Selected";
												}
											?>>Enable</option>
                                            <option name="disable"  value="0" <?php
												if ($result['status'] == 0) {
													echo "Selected";
												}
											?>>Disable</option>
										</select>
									</div>                      
								</div>
								
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <input type="submit" value="<?php echo UPDATE_BUTTON;?>" class="btn-primary btn">
									</div>
								</div></div>
						</form>
						<?php } elseif ($_GET['action'] == "add") { ?>
						
                        <form class="form-horizontal" action="userrole-controller.php" method="post">
                            <input type="hidden" name="action" value="add"/>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label hor-form">Role</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="" id="role" name="role" placeholder="Enter User Role">
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
                    window.location.href = "userrole.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
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
                    document.frmMain.action = "userrole-controller.php";
                    document.frmMain.submit()
				}
			}
		}
	</script>		
<?php include_once('includes/footer.php'); ?>