<footer>
<div class="foot-nav">
    <div class="container">
        <ul>
            <li><a href="index.php">Home</a></li>
			<?php							
				$PagesQuery = $conn->query("SELECT seo.seo_url as seo_url, pg.* FROM r_page pg,r_seo_url seo where pg.id_page=seo.id_page order by pg.id_page desc")or die(mysql_error());							
				if(mysqli_num_rows($PagesQuery)>0){
				?>
				<?php
					while ($page = $PagesQuery->fetch_assoc()) {
					?>							
					<li><a href="<?php echo  SITEURL;?><?php echo $page['seo_url']; ?>"><?php echo $page['title']; ?></a></li>
				<?php } ?>	
			<?php } ?>
            <li><a href="contact.php">Contact</a></li>
            <div class="clearfix"></div>
		</ul>
	</div>
</div>
<!-- footer-bottom -->
<div class="copyright">
    <div class="container">
        <p>&copy; <?php echo SITE_COPY_RIGHTS;?> </p>
	</div>
</div>
</footer>
</body>
</html>
