<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		
		header('location:index.php');
	}
	$questionQuery = $conn->query("SELECT * FROM r_question")or die(mysqli_error());
	$questionCount = mysqli_num_rows($questionQuery);
	$pages = $questionCount / ADMIN_PAGE_LIMIT;
	$pages = ceil($pages);
	
	$page = false;
	if (array_key_exists('page', $_GET)) {
		$page = (int)$_GET['page'];
	}
	if ($page == "" || $page == 1) {
		$page1 = 0;
		} else {
		$page1 = ($page * ADMIN_PAGE_LIMIT) - ADMIN_PAGE_LIMIT;
	}
	
	$userList = $conn->query("SELECT * FROM r_question order by id_question desc limit $page1,".ADMIN_PAGE_LIMIT);
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
                    <span>Questions</span>
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
                                            <td><h3 id="h3.-bootstrap-heading"> QUESTIONS - [<?php echo $questionCount; ?>]</h></td>
                                            <td class="type-info text-right">
                                                <a href="question.php?action=add"><span class="btn btn-success"><i class="fa fa-plus-square white" aria-hidden="true"></i> <span class="desktop"> <?php echo ADD_BUTTON;?></span></span></a> 
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
                                        <td class="table-text"><h6>Question</h6></td>
                                        <!--<td class="table-text desktop"><h6>Email</h6></td>
                                        <td class="table-text desktop"><h6>Phone</h6></td>
                                        <td class="table-text desktop"><h6>Role</h6></td>-->
                                        <td class="table-text"><h6>Status</h6></td>
									</tr>
                                    <?php
										if (mysqli_num_rows($userList) > 0) {
											while ($user = $userList->fetch_assoc()) {
											?>
											<tr class="table-row <?php echo ($user["status"]==1)?'warning':'danger'; ?>">
                                                <td class="table-img"><input type="checkbox" name="selectcheck" value="<?= $user["id_question"] ?>"/></td>
                                                <td class="march"><h6><?php echo $user["question"] ?></h6></td>
                                                <!--<td class="march desktop"><h6><?php echo $user["email"] ?></h6></td>
                                                <td class="march desktop"><h6><?php echo $user["phone"] ?></h6></td>
                                                <td class="march desktop"><h6><?php echo $user["adminrole"] ?></h6></td>-->
                                                <td class="march"><h6><?php echo ($user["status"] == 1) ? 'Enable' : 'Disable'; ?></h6></td>
                                                <td><a href="question.php?id=<?php echo $user["id_question"] ?>&action=edit&page=<?php echo "$page" ?>"><span class="label label-primary"><i class="fa fa-pencil white" aria-hidden="true"></i></span><a/>
												<a href="question-controller.php?chkdelids=<?php echo $user["id_question"] ?>&action=delete&page=<?php echo "$page" ?>""><span class="label label-info"><i class="fa fa-remove white" aria-hidden="true"></i></span></a>
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
								if ($questionCount > ADMIN_PAGE_LIMIT) {
								?>
                                <div class="horz-grid text-center">
                                    <ul class="pagination pagination-lg">
										
                                        <?php for ($b = 1; $b <= $pages; $b++) { ?>
                                            <?php if ($b == $page) { ?>
                                                <li class="active"><a href="question.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>    
												<?php } else { ?>
                                                <li><a href="question.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
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
                        <span><a href="question.php">Question</a></span>
                        <i class="fa fa-angle-right"></i>
                        <span><?php echo ($_GET['action'] == 'edit') ? 'Edit Question' : 'Add Question'; ?></span>
					</h2>
				</div>
                <div class="grid-system">
                    <div class="horz-grid">
                        <div class="grid-hor">
                            <h4 id="grid-example-basic"><?php echo ($_GET['action'] == 'edit') ? 'Edit Question' : 'Add Question'; ?> Details:</h4>
							
						</div>
                        <?php
							if ($_GET['action'] == "edit") {
								if (isset($_GET['uid'])) {
									$id = $_GET['uid'];
									} else {
									$id = $_GET['id'];
								}
								$page = $_GET['page'];
								$query = $conn->query("select * from r_question where id_question=$id")or die(mysql_error());
								$result =$query->fetch_assoc();
							?>
                            <form class="form-horizontal" action="question-controller.php" method="post">
                                <input type="hidden" name="action" value="edit"/>
                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                <input type="hidden" name="page" value="<?php echo "$page" ?>"/>
								
								
								<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Test</label>
								<div class="col-sm-8">
									<select name="test" id="selector1" class="form-control selectpicker" >
										<option value="">Select Test</option>
										<?php
											$row = $conn->query("select * from r_test")or die(mysqli_error());						
											while ($run = $row->fetch_assoc()) {
												if($run['id_test']==$result['id_test']){?> 
												<option value="<?php echo $run['id_test']?>" selected><?php echo $run['name']?></option>
												<?php }else{ ?>
												<option value="<?php echo $run['id_test']?>"><?php echo $run['name']?></option>
												
												<?php }
											?>
										<?php }?>
									</select>
								</div>                      
							</div>
							
							
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Question</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["question"] ?>" id="question" name="question" placeholder="question">
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 1</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["option_1"] ?>" id="option_1" name="option_1" placeholder="option_1">
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 2</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["option_2"] ?>" id="option_2" name="option_2" placeholder="option_2" >
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 3</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["option_3"] ?>" id="option_3" name="option_3" placeholder="option_3">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 4</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["option_4"] ?>" id="option_4" name="option_4" placeholder="option_4">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Correct Option</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="option_correct" name="option_correct" placeholder="option_correct" value="<?php echo $result["option_correct"] ?>">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Notes</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="<?php echo $result["notes"] ?>" id="notes" name="notes" placeholder="notes">
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
                        <?php if (isset($_GET['email']) && $_GET['email'] == "alreadyexist") { ?>
                            <p style="color: red">Email already exist!</p>
						<?php } ?>
                        <form class="form-horizontal" action="question-controller.php" method="post">
                            <input type="hidden" name="action" value="add"/>
							
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label hor-form">Test</label>
								<div class="col-sm-8">
									<select name="test" id="selector1" class="form-control selectpicker" >
										<option value="">Select Test</option>
										<?php
											$row = $conn->query("select * from r_test")or die(mysqli_error());						
											while ($run = $row->fetch_assoc()) { ?>
												<option value="<?php echo $run['id_test']?>"><?php echo $run['name']?></option>
										<?php }?>
									</select>
								</div>                      
							</div>
							
                            <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Question</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" ; id="question" name="question" placeholder="question">
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 1</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="option_1" name="option_1" placeholder="option_1">
									</div>
								</div>
								
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 2</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="option_2" name="option_2" placeholder="option_2" >
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 3</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="option_3" name="option_3" placeholder="option_3">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Option 4</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="option_4" name="option_4" placeholder="option_4">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Correct Option</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="option_correct" name="option_correct" placeholder="option_correct">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label hor-form">Notes</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control"  id="notes" name="notes" placeholder="notes">
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
                    window.location.href = "question.php?action=edit&page=<? echo "$page"?>&uid=" + arrval[0];
				}
			}
		}
	</script>
    <?php include_once('includes/footer.php'); ?>			
	
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
                ConfirmStatus = confirm("Do you want to DELETE selected User.?")
                if (ConfirmStatus == true) {
                    document.frmMain.chkdelids.value = chkdelids
                    document.frmMain.action.value = "delete"
                    document.frmMain.action = "question-controller.php";
                    document.frmMain.submit()
				}
			}
		}
	</script>
<?php include_once('includes/footer.php'); ?>	