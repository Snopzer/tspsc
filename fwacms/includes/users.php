<?php
	$UserQuery = $conn->query("SELECT * FROM r_user order by name")or die(mysql_error());
	if(mysqli_num_rows($UserQuery) > 1 ){
	?>
	
	<div class="blo-top1">
		<div class="tech-btm">
			<h4>Featured Users </h4>
			<?php				
				while ($user = $UserQuery->fetch_assoc()) {
					if($user['image']!="" && $user['name']!=""){
					?>
					<div class="blog-grids">
						<div class="blog-grid-left">
							<img src="images/user/<?= $user['image'] ?>" class="img-responsive"/>
						</div>
						<div class="blog-grid-right">
							<h5><?= $user['name'] ?></h5>
						</div>
						<div class="clearfix"> </div>
					</div>
				<?php }} ?>
		</div>
	</div>
<?php } ?>