<?php
error_reporting( ~E_NOTICE );
require_once 'dbcon.php';

if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
{
	$id = $_GET['edit_id'];
	$stmt_edit = $DB_con->prepare('SELECT username, description, userprofile FROM users WHERE userid =:uid');
	$stmt_edit->execute(array(':uid'=>$id));
	$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
	extract($edit_row);
}
else
{
	header("Location: index.php");
}
if(isset($_POST['btn_save_updates']))
{
	$username = $_POST['user_name'];
	$description = $_POST['description'];		
	$imgFile = $_FILES['user_image']['name'];
	$tmp_dir = $_FILES['user_image']['tmp_name'];
	$imgSize = $_FILES['user_image']['size'];
	if($imgFile)
	{
		$upload_dir = 'uploads/';
		$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION));
		$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
		$userprofile = rand(1000,1000000).".".$imgExt;
		if(in_array($imgExt, $valid_extensions))
		{			
			if($imgSize < 5000000)
			{
				unlink($upload_dir.$edit_row['userprofile']);
				move_uploaded_file($tmp_dir,$upload_dir.$userprofile);
			}
			else
			{
				$errMSG = "Sorry, Your File Is Too Large To Upload. It Should Be Less Than 5MB.";
			}
		}
		else
		{
			$errMSG = "Sorry, only JPG, JPEG, PNG & GIF Extension Files Are Allowed.";		
		}	
	}
	else
	{
		$userprofile = $edit_row['userprofile'];
	}
	if(!isset($errMSG))
	{
		$stmt = $DB_con->prepare('UPDATE users SET username=:uname, description=:udes, userprofile=:upic WHERE userid=:uid');
		$stmt->bindParam(':uname',$username);
		$stmt->bindParam(':udes',$description);
		$stmt->bindParam(':upic',$userprofile);
		$stmt->bindParam(':uid',$id);
		
		if($stmt->execute()){
			?>
			<script>
				alert('Successfully Updated...');
				window.location.href='index.php';
			</script>
			<?php
		}
		else{
			$errMSG = "Sorry User Could Not Be Updated!";
		}
	}			
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add, Edit, Delete, User Profile</title>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="jquery-1.11.3-jquery.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="https://www.tutorialswb.com/">tutorialsWB</a>
				<ul class="nav navbar-nav">
					<li class="active"><a href="index.php">Home</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<div>
			<h1 class="h2">&nbsp; Update Profile<a class="btn btn-success" href="index.php" style="margin-left: 850px"><span class="glyphicon glyphicon-home"></span>&nbsp; Back</a></h1><hr>
		</div>
		<form method="post" enctype="multipart/form-data" class="form-horizontal" style="margin: 0 300px 0 300px;border: solid 1px;border-radius:4px">
			<?php
			if(isset($errMSG)){
				?>
				<div class="alert alert-danger">
					<span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
				</div>
				<?php
			}
			?>
			<table class="table table-responsive">
				<tr>
					<td><label class="control-label">Username</label></td>
					<td><input class="form-control" type="text" name="user_name" value="<?php echo $username; ?>" required /></td>
				</tr>
				<tr>
					<td><label class="control-label">Description</label></td>
					<td><input class="form-control" type="text" name="description" value="<?php echo $description; ?>" required /></td>
				</tr>
				<tr>
					<td><label class="control-label">Profile Picture</label></td>
					<td>
						<p><img src="uploads/<?php echo $userprofile; ?>" height="150" width="150" /></p>
						<input class="input-group" type="file" name="user_image" accept="image/*" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<button type="submit" name="btn_save_updates" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-save"></span>&nbsp; Save</button>
						<a class="btn btn-warning" href="index.php"> <span class="glyphicon glyphicon-remove"></span>&nbsp; Cancel</a>
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>