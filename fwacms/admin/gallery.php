<?php
	session_start();
	include_once('../config.php');
	include_once('../parameter.php');
	if (!isset($_SESSION['id'])) {
		header('location:index.php');
	}?>  
	<?php include_once('includes/header.php'); ?>
	<?php include_once('includes/menu.php'); ?>
	
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<div class="content-main">
			<div class="banner">
				<h2>
					<a href="home.php">Home</a>
					<i class="fa fa-angle-right"></i>
					<span><a href="gallery.php">Gallery</a></span><i class="fa fa-angle-right"></i>
					<span><a href="gallery.php?action=add"><button type="button" class="btn btn-sm btn-info">Add New Image</button></a></span>
				</h2>
			</div>
			
			
			<?php if(!isset($_GET['action'])){?>
			<div class="grid-system">
					<div class="horz-grid">
				<div class="gallery">
				<?php
					$row = $conn->query("select * from r_image order by id_image")or die(mysqli_error());
					while ($result = $row->fetch_assoc()) {
					?> 
					<div class="col-md">
						<div class="gallery-img">
							<img class="img-responsive fancybox" src="../images/gallery/<?= $result['image'] ?>" alt="<?= $result['image'] ?>" />
							<!--<span class="zoom-icon"> </span> -->
						</div>
						<a href="gallery-controller.php?id=<?= $result["id_image"] ?>&action=delete"><span class="label label-info">Delete</span></a>
						
						<div class="text-gallery">
							<h6><?= $result['image_name'] ?></h6>
						</div>
					</div>
					<?php  }//if is notset  ends here ?>
				</div>			
				</div>			
				</div>			
				<?php }//if is notset  ends here ?>


				
			<?php if (isset($_GET['action'])) {//if isset starts here ?>
			<?php        if($_GET['action'] == "add") { ?>
				<div class="grid-system">
					<div class="horz-grid">
						<form action="gallery-controller.php?action=add" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="exampleInputFile">Image Name</label>
								<input type="text" id="image_name" class="form-control" name="image_name">
							</div>
							
							<div class="form-group">
								<label for="exampleInputFile">Select Image</label>
								<input type="file" id="photo" name="photo">
							</div>
							
							<button type="submit" class="btn btn-default" id="submit">Upload Image</button>
						</form>
						
					</div>
				</div>
			<?php }} ?>
		</div> 
	</div>
	
	<?php include 'includes/footer.php'; ?>
	<script>document.getElementById('closeButton').addEventListener('click', function(e) {
		e.preventDefault();
		this.parentNode.style.display = 'none';
	}, false);</script>  
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/fancybox/1.3.4/jquery.fancybox-1.3.4.pack.min.js"></script>
	<script type="text/javascript">
		$(function($){
			var addToAll = false;
			var gallery = true;
			var titlePosition = 'inside';
			$(addToAll ? 'img' : 'img.fancybox').each(function(){
				var $this = $(this);
				var title = $this.attr('title');
				var src = $this.attr('data-big') || $this.attr('src');
				var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
				$this.wrap(a);
			});
			if (gallery)
			$('a.fancybox').attr('rel', 'fancyboxgallery');
			$('a.fancybox').fancybox({
				titlePosition: titlePosition
			});
		});
		$.noConflict();
	</script>
	
																																	