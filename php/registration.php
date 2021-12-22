<?php

require_once "db.php";
session_start();

function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}

if(isset($_POST['add'])) {
	$err = [];
	
	if(strlen($_POST['username']) < 3 or strlen($_POST['username'] > 30))
	{
		$err[] = "Username connot be less then 3 symbols and more then 30";
	}
	$query = mysqli_query($link, "SELECT id FROM teachers WHERE teacher_login='".mysqli_real_escape_string($link, $_POST['username'])."'");
	if(mysqli_num_rows($query) > 0){
		$err[] = "User Exist!!!!";
	}
	if (count($err) == 0) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	mysqli_query($link," INSERT INTO teachers SET teacher_login='".$username."', teacher_password='".$password."', teacher_full_name='".$_POST['name']."'");
	redirect("/php/login.php"); exit();
	} else {
		foreach ($err as $error) {
			echo "<br>".$error."<br>";
		}
	}	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>

	<form method="POST">
		<div class="mb-3">
			<label class="form-label">Full name</label>
			<input type="text" class="form-control" name='name'>
		</div>
		<div class="mb-3">
			<label class="form-label">login</label>
			<input type="text" class="form-control" name='username'>
		</div>
		<div class="mb-3">
			<label for="exampleInputPassword1" class="form-label">Password</label>
			<input type="password" class="form-control" name='password'>
		</div>
		<button type="submit" class="btn btn-primary" name='add'>Submit</button>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	</form>
</body>
</html>
