<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo SITE_NAME;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="<?php echo SITE_KEYWORDS;?>" />
        <meta name="keywords" content="<?php echo SITE_DESCRIPTION;?>" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
        <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
        <!-- Custom Theme files -->
        <link href="css/style.css" rel='stylesheet' type='text/css' />
        <link href="css/font-awesome.css" rel="stylesheet"> 
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		
        <!-- Mainly scripts -->
        <script src="js/jquery.metisMenu.js"></script>
        <script src="js/jquery.slimscroll.min.js"></script>
        <!-- Custom and plugin javascript -->
        <link href="css/custom.css" rel="stylesheet">
        <script src="js/custom.js"></script>
        <script src="js/screenfull.js"></script>
        <script>
			$(function () {
				$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);
				
				if (!screenfull.enabled) {
					return false;
				}
				
				$('#toggle').click(function () {
					screenfull.toggle($('#container')[0]);
				});
				
				
				
			});
			function fnDelete()
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
					alert("Select Checkbox to Delete");
				} else if (flag == 1)
				{
					var i, len, chkdelids, sep;
					chkdelids = "";
					sep = "";
					for (var i = 0; i < document.frmMain.length; i++)
					{
						if (document.frmMain.elements[i].name == "selectcheck")
						{
							if (document.frmMain.elements[i].checked == true)
							{
								//alert(document.frmFinal.elements[i].value)
								chkdelids = chkdelids + sep + document.frmMain.elements[i].value;
								sep = ",";
							}
						}
					}
					ConfirmStatus = confirm("Do you want to DELETE selected records?")
					
					if (ConfirmStatus == true) {
						
						document.frmMain.chkdelids.value = chkdelids
						document.frmMain.tType.value = "Del"
						document.frmMain.action = "deletedetails.php";
						document.frmMain.submit()
					}
					
				}
			}
		</script>
	</head>
    <body>
        <div id="wrapper">
				