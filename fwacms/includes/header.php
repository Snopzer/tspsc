<!DOCTYPE HTML>
<html>
    <head>
        <title><?php echo $metaArray['title'];?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="icon" href="images/favicon.png" type="image/x-icon"/>
		<link rel="shortcut icon" href="images/favicon.png"  type="image/x-icon"/>
        <meta name="keywords" content="<?php echo $metaArray['meta-keywords'];?>" />
        <meta name="description" content="<?php echo $metaArray['meta-description'];?>" />
        <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
        <link href="css/style.css" rel='stylesheet' type='text/css' />	
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		<?php echo GOOGLE_ANALYTICS; ?>
		<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700' rel='stylesheet' type='text/css'>
        <link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	</head>
    <div class="header">
        <div class="header-top">
            <div class="container">
                <div class="logo">
                    <a href="index.php"><h1><?php echo SITE_NAME;?></h1></a>
				</div>
                <div class="social">
                    <ul>
						<?php if(SOCIAL_FACEBOOK_URL!=''){?>
							<li><a href="<?php echo SOCIAL_FACEBOOK_URL?>" target="_blank" class="facebook"> </a></li>
						<?php }?>
						<?php if(SOCIAL_TWITTER_URL!=''){?>
							<li><a href="<?php echo SOCIAL_TWITTER_URL?>" class="facebook twitter"  target="_blank"> </a></li>
						<?php } ?>
						<?php if(SOCIAL_GOOGLEPLUS_URL!=''){?>
							<li><a href="<?php echo SOCIAL_GOOGLEPLUS_URL?>" class="facebook chrome"  target="_blank"> </a></li>
						<?php }?>
						<?php if(SOCIAL_LINKEDIN_URL!=''){?>
							<li><a href="<?php echo SOCIAL_LINKEDIN_URL?>" class="facebook in"  target="_blank"> </a></li>
						<?php } ?>
						<?php if(SOCIAL_BEHANCE_URL!=''){?>
							<li><a href="<?php echo SOCIAL_BEHANCE_URL?>" class="facebook beh"  target="_blank"> </a></li>
						<?php } ?>
						<?php if(SOCIAL_VIMIO_URL!=''){?>
							<li><a href="<?php echo SOCIAL_VIMIO_URL?>" class="facebook vem"  target="_blank"> </a></li>
						<?php } ?>
						<?php if(SOCIAL_YOUTUBE_URL!=''){?>
							<li><a href="<?php echo SOCIAL_YOUTUBE_URL?>" class="facebook yout" target="_blank"> </a></li>
						<?php } ?>
					</ul>
				</div>
                <div class="clearfix"></div>
			</div>
		</div>
        <div class="head-bottom">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse">					
                    <ul class="nav navbar-nav">						
                        <li class="active"><a href="index.php">Home</a></li>		
						<?php
						$categoriesQuery = $conn->query("SELECT seo.seo_url as seo_url, cat.* FROM r_category cat,r_seo_url seo where cat.id_category=seo.id_category and cat.status=1 order by cat.sort_order asc")or die(mysql_error());
							while ($category = $categoriesQuery->fetch_assoc()) {								
							?>							
                            <li><a href="<?php echo SITEURL;?><?php echo $category['seo_url']; ?>"><?php echo $category['name']; ?></a></li>							
						<?php } ?>
						<?php							
							$PagesQuery = $conn->query("SELECT seo.seo_url as seo_url, pg.* FROM r_page pg,r_seo_url seo where pg.id_page=seo.id_page order by pg.id_page desc")or die(mysql_error());							
							if(mysqli_num_rows($PagesQuery)>0){
							?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo SITE_NAME; ?><span class="caret"></span></a>
								<ul class="dropdown-menu">
									<?php
										while ($page = $PagesQuery->fetch_assoc()) {
										?>							
										<li><a href="<?php echo  SITEURL;?><?php echo $page['seo_url']; ?>"><?php echo $page['title']; ?></a></li>							
									<?php } ?>	
								</ul>
							</li>
						<?php } ?>
                        <li><a href="contact.php">Contact</a></li>						
					</ul>					
				</div>	
			</div>
		</nav>
	</div>
</div>		