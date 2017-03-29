<?php 
	include_once('config.php');
	
	if (!file_exists('config.php')) {
		header('Location:install.php');
	}
	include_once('parameter.php');
	
	$metaArray = array(
    "title"  => SITE_TITLE,
    "meta-keywords" => SITE_KEYWORDS,
    "meta-description" => SITE_DESCRIPTION,
	);
	include_once('includes/header.php'); 
?>
<?php 
	
	$postQuery = $conn->query("SELECT ru.name as user,rc.name,rp.id_post, seo.seo_url as seo_url, rp.id_category,rp.title,rp.description,rp.short_description,rp.image,rp.date_updated,rp.favourites,rp.views  from r_post rp
	LEFT JOIN r_category rc ON rp.id_category=rc.id_category
	LEFT JOIN r_user ru ON rp.id_user=ru.id_user
	LEFT JOIN r_seo_url seo ON rp.id_post=seo.id_post
	where rp.status=1 order by id_post desc")or die(mysql_error());
?> 
<div class="technology">
	<div class="banner">
		<div class="container">
			<h2><?php echo SITE_NAME;?></h2> 
			<p><?php echo SITE_DESCRIPTION;?></p>
		</div>
	</div>
	
    <div class="container">
        <div class="col-md-9 technology-left">
            <div class="tech-no">
                <?php while ($postData = $postQuery->fetch_assoc()) { ?>
					
					<div class="tc-ch">		
						<?php if(!empty($postData['image'])){?>
							<div class="tch-img">
								<a href="<?php echo SITEURL?><?php echo $postData['seo_url']?>"><img src="images/post/<?php echo $postData['image'] ?>" class="img-responsive" alt="<?php echo $postData['name']; ?>"/></a>
							</div>
							<a class="blog blue"><?php echo $postData['name']; ?></a>
						<?php } ?>
						<h3> <a href="<?php echo SITEURL?><?php echo $postData['seo_url']?>"><?php echo  str_replace('\"', '"',str_replace("\'", "'", $postData["title"])) ?></a></h3>
						<p><?php echo substr(str_replace('\"', '"',str_replace("\'", "'", $postData['short_description'])),0,POST_DESCRIPTION_LENGTH); ?>....<a href="<?php echo SITEURL?><?php echo $postData['seo_url']?>">Read More.</a></p>
						<div class="blog-poast-info">
							<ul>
								<li><i class="glyphicon glyphicon-user"> </i><a class="admin" href="#"><?php echo $postData['user']; ?> </a></li>
								<li><i class="glyphicon glyphicon-calendar"> </i><?php echo $postData['date_updated']; ?></li>
								<li><i class="glyphicon glyphicon-comment"> </i><a class="p-blog">
									<?php 
										
										$commentCountQuery = $conn->query("SELECT count(id_post) as count from r_comment where id_post=".$postData['id_post']);
										$commentCount = $commentCountQuery->fetch_assoc();
										if($commentCount > 0){
											echo $commentCount['count'];
										}else{
											echo '0';
										}
										 ?> comments</a></li>
								<li><i class="glyphicon glyphicon-heart"> </i><a class="admin"><?php echo $postData['favourites']; ?> favourites </a></li>
								<li><i class="glyphicon glyphicon-eye-open"> </i><?php echo $postData['views']; ?> views</li>
							</ul>
						</div>
					</div>
				<?php	}	?>
			</div>
		</div>
        <div class="col-md-3 technology-right">
            <?php include_once('includes/subscribe.php'); ?>
            <?php include_once('includes/users.php'); ?>
		</div>
        <div class="clearfix"></div>
	</div>
</div>
<?php include_once('includes/footer.php'); ?>