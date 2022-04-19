<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$stmt = $pdo->query('SELECT * FROM images ORDER BY uploaded_date DESC');
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=headerBar('Gallery')?>
<div class="content">
	<h2>Online Gallery System</h2>
	<p>Welcome to our gallery system! You can upload your images here!</p>
	<a href="upload.php" class="upload-image">Upload Image</a>
	<div class="images">
		<?php foreach ($images as $image): ?>
		<?php if (file_exists($image['filepath'])): ?>
		<a href="#">
			<img src="<?=$image['filepath']?>" alt="<?=$image['description']?>" data-id="<?=$image['id']?>" data-title="<?=$image['title']?>" width="300" height="200">
			<span><?=$image['description']?></span>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
<div class="image-popup"></div>

<script>
let image_popup = document.querySelector('.image-popup');
document.querySelectorAll('.images a').forEach(img_link => {
	img_link.onclick = popUp => {
		popUp.preventDefault();
		let img_meta = img_link.querySelector('img');
		let img = new Image();
		img.onload = () => {
			image_popup.innerHTML = `
				<div class="con">
					<h3>${img_meta.dataset.title}</h3>
					<p>${img_meta.alt}</p>
					<img src="${img.src}" width="${img.width}" height="${img.height}">
					<a href="delete.php?id=${img_meta.dataset.id}" title="Delete Image"><i class="fas fa-trash fa-xs"></i></a>
				</div>
			`;
			image_popup.style.display = 'inline-flex';
		};
		img.src = img_meta.src;
	};
});
image_popup.onclick = popUp => {
	if (popUp.target.className == 'image-popup') {
		image_popup.style.display = "none";
	}
};
</script>

<?=footerBar()?>