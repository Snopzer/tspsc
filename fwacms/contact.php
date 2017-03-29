<?php 	
	$metaArray = array(
    "title"  => 'Contact us',
    "meta-keywords" => 'contact techdefeat, ask your questions, need support at your work.?',
    "meta-description" => 'Share your Work, we give you support at your technical problems, contact us for more details',
	);
	include_once('config.php');
	include_once('parameter.php');
	
	include_once('includes/header.php'); 
	?>
<div class="banner1">
</div>
<div class="technology-1">
    <div class="container">
        <div class="col-md-9 technology-left">
            <div class="business">
                <div id="contact" class="contact">
                    <h3>Contact</h3>		
                    <div class="contact-grids">
                        <div class="contact-icons">
                            <div class="contact-grid">
                                <div class="contact-fig">
                                    <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
                                </div>
                                <p><?php echo CONTACT_PHONE;?></p>
                            </div>
                            <div class="contact-grid">
                                <div class="contact-fig1">
                                    <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                                </div>
                                <p><?php echo CONTACT_ADDRESS;?></p>
                            </div>
                            <div class="contact-grid">
                                <div class="contact-fig2">
                                    <span class="glyphicon glyphicon-envelope2" aria-hidden="true"></span>
                                </div>
                                <p><a href="mailto:<?php echo CONTACT_MAIL;?>"><?php echo CONTACT_MAIL;?></a></p>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
						<span id="showMessage"></span>
                        <form id="ContactForm">
                            <input type="text" name="subject" id="subject" placeholder="Subject">
                            <input type="text" name="name" id="name" placeholder="Enter Your Name">
                            <input type="text" name="email" id="email" placeholder="Enter A valid Email Address">
                            <textarea name='message' id="message" placeholder=" Type Your Message..."></textarea>
                            <button type="button" class="btn btn-success" id="Contact">Submit</button>
                        </form>
                    </div>	
                </div>
            </div>
        </div>
        <!-- technology-right -->
        <div class="col-md-3 technology-right-1">
            <div class="blo-top">
                <div class="tech-btm">
                    <img src="images/banner1.jpg" class="img-responsive" alt=""/>
                </div>
            </div>            
            <?php include_once('includes/subscribe.php'); ?>
            <?php include_once('includes/users.php'); ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
$("#Contact").click(function ()
    {
        if ($("#subject").val() == '')
        {
            $("#subject").css({"border-style": "solid", "border-color": "red"});
            $("#subject").focus();
            return false;
        }else{
         $("#subject").css({"border-style": "solid", "border-color": "green"});   
        }
        if ($("#name").val() == '')
        {
            $("#name").css({"border-style": "solid", "border-color": "red"});
            $("#name").focus();
            return false;
        }else{
            $("#name").css({"border-style": "solid", "border-color": "green"});
        }
        var email = $("#email").val();
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if ((email == '') || (!regex.test(email)))
        {
            $("#email").css({"border-style": "solid", "border-color": "red"});
            $("#email").focus();
            return false;
        }else{
            $("#email").css({"border-style": "solid", "border-color": "green"});
        }     
        if ($("#message").val() == '')
        {
            $("#message").css({"border-style": "solid", "border-color": "red"});
            $("#message").focus();
            return false;
        }
        else{
            $("#message").css({"border-style": "solid", "border-color": "green"}); 
        }
		$.ajax({
			url: "controller.php",
			method: "POST",
			data: { contactData : $("#ContactForm").serialize(), 'action':'contact'},
			dataType: "json",
			success: function (response) {
					console.log(response);
					$("#showMessage").html('<div class="alert alert-success"><strong>Success! </strong>'+response['message']+'</div>');
					$('#ContactForm').each(function(){
					this.reset();
					});
			},
			error: function (request, status, error) {
				$("#showMessage").html('<div class="alert alert-warning"><strong>OOPS!</strong> Server is Busy Please Try After Sometime</div>');
			}
		});
		return false;
	});
</script>
<?php include_once('includes/footer.php'); ?>