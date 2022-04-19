<?php
include 'functions.php';
$msg = '';
if (isset($_FILES['image'], $_POST['title'], $_POST['description'])) {
    $uuid = uniqid();
    $filename= $_FILES["image"]['name'];
    $tempname =$_FILES["image"]["tmp_name"];
    $folder = "images/".$filename;
    $filetype = strtolower(pathinfo($folder,PATHINFO_EXTENSION));
    $unique_name ="images/{$uuid}.{$filetype}";
    move_uploaded_file($tempname, $folder);
    
	if (!empty($_FILES['image']['tmp_name'])) {
			rename($folder,$unique_name);
			$pdo = pdo_connect_mysql();
			$stmt = $pdo->prepare('INSERT INTO images (title, description, filepath, uploaded_date) VALUES (?, ?, ?, CURRENT_TIMESTAMP)');
	        $stmt->execute([ $_POST['title'], $_POST['description'], $unique_name ]);
            move_uploaded_file($tempname,$folder);
            $msg = 'Image uploaded successfully! You will be redirected in 3 seconds';
            sleep(3);
            header('location: index.php');
	} else {
		$msg = 'Please upload an image!';
	}
}
?>

<?=headerBar('Upload Image')?>

<div class="content upload">
	<h2>Upload Image</h2>
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<label for="image">Choose file</label>
		<input type="file" name="image" accept="image/*" id="image">
		<label for="title">Title</label>
		<input type="text" name="title" id="title" required>
		<label for="description">Description</label>
		<textarea name="description" id="description" required></textarea>
	    <input type="submit" value="Upload Image" name="submit">
	</form>
	<p><?=$msg?></p>
</div>

<?=footerBar()?>