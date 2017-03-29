<?php
	include_once('config.php');
	include_once('parameter.php');
	
	
	$seo_url = $_GET['seo_url'];
	
	$checkSEORequest = $conn->query("select * from r_seo_url where seo_url='".$seo_url."'");	
	$checkSEO = $checkSEORequest->fetch_assoc () or die(mysql_error());	
	
	if($checkSEO['id_post'] >=1 )	{
		$showPostDiv = true;		
		$select = $conn->query("SELECT ru.name as username,rc.name,rp.id_post,rp.source,rp.image_source,rp.meta_title,rp.meta_keywords,rp.meta_description,rp.id_category,rp.title,rp.description,rp.short_description,rp.image,rp.date_updated,rp.favourites,rp.views
		FROM r_post rp 
		LEFT JOIN r_category rc ON rp.id_category = rc.id_category
		LEFT JOIN r_user ru ON rp.id_user=ru.id_user
		WHERE rp.id_post='".$checkSEO['id_post']."' order by id_post DESC")or die(mysql_error()); 		
		$result = $select->fetch_assoc();	
		
		$id=$result['id_post'];		
		$viewcheck=$conn->query("UPDATE `r_post` SET `views` = `views` + 1 WHERE `id_post` = '$id'");		
		$metaArray = array(		
		"title"  => $result['meta_title'],		
		"meta-keywords" => $result['meta_keywords'],		
		"meta-description" => $result['meta_description']);	
	}
	
	if($checkSEO['id_page'] >=1 )	{
		$showpageDiv = true;	
		
		
		$pageQuery = $conn->query("select * from r_page page,r_seo_url seo where page.id_page=seo.id_page and page.id_page=".$checkSEO['id_page'])or die(mysql_error());
		$result = $pageQuery->fetch_assoc();
		
		$metaArray = array(		
		"title"  => $result['title'],		
		"meta-keywords" => $result['meta_keywords'],		
		"meta-description" => $result['meta_description']);	
	}
	
	if($checkSEO['id_user'] >=1 )	{
		$showUserDiv = true;	
		
		
		$pageQuery = $conn->query("select * from r_page page,r_seo_url seo where page.id_page=seo.id_page and page.id_page=".$checkSEO['id_user'])or die(mysql_error());
		$result = $pageQuery->fetch_assoc();
		
		$metaArray = array(		
		"title"  => $result['title'],		
		"meta-keywords" => $result['meta_keywords'],		
		"meta-description" => $result['meta_description']);	
	}
	
	if($checkSEO['id_category'] >=1 )	{		
		$showCategoryDiv = true;
		$categoryQuery = $conn->query("SELECT * FROM r_category cat, r_seo_url seo WHERE cat.id_category = seo.id_category AND cat.id_category =".$checkSEO['id_category'])or die(mysql_error());
		$result = $categoryQuery->fetch_assoc();
		
		$metaArray = array(		
		"title"  => $result['meta_title'],		
		"meta-keywords" => $result['meta_keywords'],		
		"meta-description" => $result['meta_description']);	
	}
	include_once('includes/header.php');
	
?>

<div class="technology-1">
    <div class="container">
        <div class="col-md-9 technology-left">
            <div class="business">
				<?php if($showCategoryDiv==true){ ?>
					<!--category details start -->
					<div class=" blog-grid2">
						<?php if(!empty($result['image'])) { ?>
							<img src="images/category/<?php echo $result['image'] ?>" class="img-responsive" alt="<?php echo $result['name'] ?>" title="<?php echo $result['name'] ?>">
						<?php }?>
						<div class="blog-text">
							<h5><?php echo str_replace('\"', '"',str_replace("\'", "'", $result["name"])); ?></h5>
							<p><?php echo str_replace('\"', '"',str_replace("\'", "'", $result["description"])); ?></p>	
						</div>
					</div>
					<!--category details end -->
					
					<div class="comment-top">
						<h2><?php echo $result['name'] ?> Topic's</h2><?php
							$postQuery = $conn->query("SELECT rp.*,seo.seo_url as seourl FROM  `r_post` rp LEFT JOIN r_seo_url seo ON rp.id_post = seo.id_post WHERE rp.id_category =".$result['id_category']." and rp.status=1 ORDER BY rp.id_post")or die(mysql_error()); 		
							while ($post = $postQuery->fetch_assoc()) {
							?>
							<?php if($post['image']!=''){ ?>
								<div class="media-left">
									<a href="#">
										<img class="article-short-image" width="100" height="100" src="images/post/<?php echo $post['image']?>">
									</a>
								</div>
								<?php } ?>
                            <div class="media-body">
                                <a href="<?php echo SITEURL?><?php echo $post['seourl']?>"><h4 class="media-heading"><?php echo str_replace('\"', '"',str_replace("\'", "'",  $post['title'])); ?></h4></a>
								<p><?php echo substr(str_replace('\"', '"',str_replace("\'", "'", $post["short_description"])),0,POST_DESCRIPTION_LENGTH); ?>....<a href="<?php echo SITEURL?><?php echo $post['seourl']?>">Read More.</a></p>
							</div><br /> 
                            <?php
							}
						?>
					</div>
					
				<?php } ?>
				<?php if($showpageDiv==true){ ?>
					<div class=" blog-grid2">
						<div class="blog-text">
							<h5><?php echo $result['page_heading'] ?></h5>
						</div>
						<?php if(!empty($result['image'])) { ?>
							<img src="admin/images/<?php echo $result['image'] ?>" class="img-responsive" alt="<?php echo $result['page_heading'] ?>" title="<?php echo $result['page_heading'] ?>">
						<?php }?>
						
						
						<div class="blog-text">
							<p><?php echo str_replace('\"', '"',str_replace("\'", "'", $result["page_description"])); ?></p>
						</div>
					</div>
				<?php } ?>
				
				<?php if($showUserDiv == true) {?>
					
				<?php  } ?>
				<?php if($showPostDiv == true) {?>
					<div class=" blog-grid2">
						<div class="blog-text">
							<h5><?php echo str_replace('\"', '"',str_replace("\'", "'", $result["title"])); ?></h5>
						</div>
						<?php if(!empty($result['image'])) { ?>
							<img src="images/post/<?php echo $result['image'] ?>" class="img-responsive" alt="<?php echo $result['title'] ?>" title="<?php echo $result['title'] ?>">
						<?php }?>
						<div class="blog-poast-info">
							<ul>
								<li><i class="glyphicon glyphicon-user"> </i><a class="admin"><?php echo $result['username'] ?> </a></li>
								<li><i class="glyphicon glyphicon-calendar"> </i><?php echo $result['date_updated'] ?></li>
								<li><i class="glyphicon glyphicon-comment"> </i><a class="p-blog"><?php echo $result['name'] ?></a></li>
								<!--<li><i class="glyphicon glyphicon-heart"> </i><a class="admin"><?php echo$result['favourites'] ?> favourites </a></li>-->
								<li><i class="glyphicon glyphicon-eye-open"> </i><?php echo $result['views'] ?> views</li>
							</ul>
						</div>
						<div class="blog-text">
							<h5><?php echo str_replace('\"', '"',str_replace("\'", "'", $result["title"])); ?></h5>
							<p><?php echo str_replace('\"', '"',str_replace("\'", "'", $result["description"])); ?></p>	
							<?php if($result['source']!=''){ ?>
								<h6>Source :</h6> <p><a href="<?php echo $result['source'];?>"><?php echo $result['source'];?></a></p>		
							<?php }?>
							
							<?php if($result['image_source']!=''){ ?>
							<h6>Image Source :</h6> <a href="<?php echo $result['image_source'];?>"><?php echo $result['image_source'];?></a></p>
						<?php }?>
						
					</div>
					
				</div>
			<?php } ?>
			<?php if($showPostDiv == true) {?>
				
				<div class="comment">
					<h3>Leave a Comment</h3>
					<span id="showMessage"></span>
					<div class=" comment-bottom">
						<form id="commentForm"> 
							<input type="text" id="name" name="name" placeholder="Name">
							<input type="hidden" name="id" value="<?php echo $result['id_post']?>" >
							<input type="text" id="Commentemail" name="email" placeholder="Email">
							<input type="text" id="Commentwebsite" name="website" placeholder="Website">
							<!--<input type="text" id="subject" name="subject" placeholder="Subject">-->
							<textarea placeholder="Message" id="message" name="message" required=""></textarea>
							<button id="SubmitComment" class="btn btn-info">Submit</button>
						</form>
					</div>
				</div>
				
				<div class="comment-top">
					<h2>Comment</h2>
					<div id="commentsdiv">
						<?php
							$commentQuery = $conn->query("select * from r_comment where id_post=$id order by id_comment desc");
							while ($comment = $commentQuery->fetch_assoc()) {
								if ($comment['name'] != '' & $comment['message'] != '') {
								?>
								
								<div class="media-left">
									<a href="#">
										<img src="images/si.png" alt="">
									</a>
								</div>
								<div class="media-body">
									<h4 class="media-heading">
										<?php if(!empty($comment['website'])){?>
											<a href="<?php echo $comment['website'] ?>"><?php echo $comment['name'] ?> Says :</a>
											<?php }else{ ?>
											<a><?php echo $comment['name'] ?></a>
										<?php } ?>
									</h4>
									<p><?php echo str_replace('\"', '"',str_replace("\'", "'", $comment["message"])); ?></p>
								</div><br />
								
								<?php
								}
							}
						?>
					</div>
				</div>
				
			<?php } ?>
		</div>
	</div>
	
	<div class="col-md-3 technology-right-1">
		<?php include_once('includes/subscribe.php'); ?>
		<?php include_once('includes/users.php'); ?>
	</div>
	<div class="clearfix"></div>
</div>
</div>

<script type="text/javascript" language="JavaScript">
	$("#SubmitComment").click(function()
	{
		$("#showMessage").html('');
		if($("#name").val()=='')
		{
			$("#showMessage").html('Please Enter Your Name');
			$("#name").css({"border-style": "solid", "border-color": "red"}).focus();
			return false;
		}
		else
		{
			$("#name").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var Mail = $("#Commentemail").val();
		if((Mail=='') || (!regex.test(Mail)))
		{
			$("#Commentemail").css({"border-style": "solid", "border-color": "red"}).focus();
			$("#showMessage").html('Please Enter Valid Email');
			return false;
			}else{
			$("#Commentemail").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		
		
		/*if($("#Commentwebsite").val()=='')
			{
			$("#Commentwebsite").css({"border-style": "solid", "border-color": "red"}).focus();
			$("#showMessage").html('Please Enter subject');
			return false;
			}else{
			$("#Commentwebsite").css({"border-style": "solid","border-color": "#E9E9E9"});
		}*/
		if($("#subject").val()=='')
		{
			$("#subject").css({"border-style": "solid", "border-color": "red"}).focus();
			$("#showMessage").html('Please Enter subject');
			return false;
			}else{
			$("#subject").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		
		if($("#message").val()=='')
		{
			$("#message").css({"border-style": "solid", "border-color": "red"}).focus();
			$("#showMessage").html('Please Enter Your Message');
			return false;
			}else{
			$("#message").css({"border-style": "solid","border-color": "#E9E9E9"});
		}
		$.ajax({
			url: "controller.php",
			method: "POST",
			data: { CommentData : $("#commentForm").serialize(), 'type':'save'},
			dataType: "json",
			success: function (response) {
				if(response["success"]==true)
				{
					$("#commentsdiv").prepend('<div class="media-left"><img src="images/si.png" alt=""></div><div class="media-body"><a href="'+$("#Commentwebsite").val()+'"><h4 class="media-heading">'+$("#name").val()+'  Says :</h4></a><p>'+$("#message").val()+'</p></div><br />');
					$('#commentForm').find("input[type=text], textarea").val("");
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
<?php include_once ('includes/footer.php'); ?>
