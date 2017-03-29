<div class="blo-top">
    
</div>
<!-- display alexa rank-->
<div class="blo-top">
    <div class="tech-btm">
        <h5>Subscribe To Our Newsletter</h5>
        <p>Signup and Get Connected with us</p>
		<span id="showMessage"></span>
		<div id="SubscibeNow">
			<form id="SubscriptionFrom">
				<div class="name">
					<input type="text" name="email" id="email" placeholder="Email" required="">
				</div>	
				<div class="button">
					<button id="Subscribe" class="btn btn-info">Subscribe Now</button>
				</div>
			</form>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>


<script type="text/javascript" language="JavaScript">
	$("#Subscribe").click(function()
	{
		$("#showMessage").html('');
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $("#email").val();
		if((email=='') || (!regex.test(email)))
		{
			$("#email").css({"border-style": "solid", "border-color": "red"});
			$("#showMessage").html('Please Enter Valid Email');
			$("#email").focus();
			return false;
			}else{
			$("#email").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		$.ajax({
			url: "controller.php",
			method: "POST",
			data: { subscribeData : $("#SubscriptionFrom").serialize(), 'action':'newsletter'},
			dataType: "json",
			success: function (response) {
				if(response["success"]==true)
				{
					$("#showMessage").html('<div class="alert alert-success"><strong>Success!</strong> '+response['message']+'</div>');
					
					}else{
					$("#showMessage").html('<div class="alert alert-warning"><strong>warning!</strong> '+response['message']+'</div>');
				}
			},
			error: function (request, status, error) {
				$("#showMessage").html('<div class="alert alert-warning"><strong>warning!</strong> Please Try After Sometime!</div>');
			}
		});
		return false;
	});
</script>