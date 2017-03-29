<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		
		header('location:index.php');
	}
	$select = $conn->query("SELECT * FROM r_message order by id_message desc")or die(mysqli_error());
	$messageCount = $select->num_rows;
	
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = $_GET['page'];
	}
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	$select = $conn->query("SELECT * FROM r_message order by id_message desc limit $page1,".ADMIN_PAGE_LIMIT)or die(mysqli_error());
?>   
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/menu.php'); ?>
<script src="js/ckeditor/ckeditor.js"></script>
<?php if (!isset($_GET['action'])) { ?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="content-main">	
            <div class="banner">
                <h2>
                    <a href="home.php">Home</a>
                    <i class="fa fa-angle-right"></i>
                    <span>Message</span>
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
                                            <td><h3 id="h3.-bootstrap-heading"> Message - [<?php echo $messageCount;?>]</h3></td>
                                            <td class="type-info text-right">
                                                <a href="message.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
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
                                        <td class="march"><h6><input class="form-control" type="text" name="department" id="department" placeholder="Search Name"></h6></td>
                                        <td class="march"><h6><input class="btn btn-default" type="submit"  name="search" value="Search"></h6></td>                                
									</tr>
									
									<tr class="table-row">
										<td class="table-img">
											<input type="checkbox" name="checkall" onClick="Checkall()"/>
										</td>
										<td class="table-text"><h6>Name</h6></td>
										<td class="table-text desktop"><h6>Subject</h6></td>
										<td class="table-text desktop"><h6>Email</h6></td>
										<!--<td class="table-text"><h6>Message</h6></td>-->
									</tr>
									<?php
										while ($row = $select->fetch_assoc()) {
										?>
                                        <tr class="table-row">
                                            <td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $row["id_message"] ?>"></td>
                                            <td class="march"><h6><?php echo $row["name"] ?></h6></td>
                                            <td class="march desktop"><h6><?php echo $row["subject"] ?></h6></td>
                                            <td class="march desktop"><h6><?php echo $row["email"] ?></h6></td>
                                            <!--<td class="march"><h6><?php echo $row["message"] ?></h6></td>-->
                                            <td><a href="message.php?id=<?php echo $row["id_message"] ?>&action=edit&page=<?php echo "$page"?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
											<a href="message-controller.php?chkdelids=<?php echo $row["id_message"] ?>&action=delete&page=<?php echo "$page"?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
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
								$res1 = $conn->query("SELECT * FROM r_message");
								$count = mysqli_num_rows($res1);
								$a = $count / ADMIN_PAGE_LIMIT;
								$a = ceil($a);
								if ($count > ADMIN_PAGE_LIMIT) {
								?>
								<div class="horz-grid text-center">
									<ul class="pagination pagination-lg">
										
										<?php for ($b = 1; $b <= $a; $b++) { ?>
											<?php if ($b == $page) { ?>
												<li class="active"><a href="message.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
												<li><a href="message.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
												<?php
												}
											}
										?>
									</ul>
								</div>
							<?php }?>
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
                        <span><a href="message.php">Message</a></span>
                        <i class="fa fa-angle-right"></i>
                        <span><?php echo ($_GET['action'] == 'edit') ? 'Edit message' : 'Add message'; ?></span>
					</h2>
				</div>
                <div class="grid-system">
                    <div class="horz-grid">
                        <div class="grid-hor">
                            <h4 id="grid-example-basic">Message Details:</h4>
							
						</div>
                        <?php
							if ($_GET['action'] == "edit") {
								$id = $_GET['id'];
								$page = $_GET['page'];
								$query = $conn->query("select * from r_message where id_message=$id")or die(mysqli_error());
								$result = $query->fetch_assoc();
							?>
                            <form class="form-horizontal" action="message-controller.php" method="post">
                                <input type="hidden" name="action" value="edit"/>
                                <input type="hidden" name="id" value="<?php echo $result["id_message"] ?>">
                                <input type="hidden" name="page" value='<?php echo "$page"?>'>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["name"] ?>" id="name" name="name" placeholder="name">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Subject</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["subject"] ?>" id="subject" name="subject" placeholder="subject">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Email</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["email"] ?>" id="email" name="email" placeholder="phone">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Message</label>
                                    <div class="col-sm-8">
										<textarea id="message" name="message" class="form-control" rows="6"><?php echo $result["message"] ?></textarea>
									</div>
								</div>
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <input type="submit" value="Save" class="btn-primary btn">
									</div>
								</div></div>
								
						</form>
                        <?php
							} elseif ($_GET['action'] == "add") {
						?>
                        <form class="form-horizontal" action="message-controller.php" method="post">
                            <input type="hidden" name="action" value="add"/>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label hor-form">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name">
								</div>
							</div>							
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label hor-form">Subject</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="subject">
								</div>
							</div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label hor-form">Email</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control"  id="email" name="email" placeholder="email">
								</div>
							</div>
							
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label hor-form">Message</label>
                                <div class="col-sm-8">
									<textarea id="message" name="message"  class="form-control" rows="6"></textarea>
								</div>
							</div>
                            <!--<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Date Created</label>
								<div class="col-sm-8">
								<input type="text" class="form-control" value="" id="date_created" name="date_created" placeholder="Date created">
								</div>
							</div>-->
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <input type="submit" value="Save" class="btn-primary btn">
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
        function selectAll(source) {
            checkboxes = document.getElementsByName('colors[]');
            for (var i in checkboxes)
			checkboxes[i].checked = source.checked;
		}
	</script>
	<?php include_once('includes/footer.php'); ?>	
	<script language="JavaScript">
		/* editor script */
		var editor=CKEDITOR.replace('message');
		
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
                    window.location.href = "message.php?action=edit&page=<? echo "$page"?>&id=" + arrval[0];
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
                    document.frmMain.action = "message-controller.php";
                    document.frmMain.submit()
				}
			}
		}
	</script>		