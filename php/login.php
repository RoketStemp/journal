<?php 
	session_start();
	require_once 'db.php';

function redirect($url)
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';

    echo $string;
}

if (isset($_POST['add'])) {
	$user = $_POST['username'];
	$password = $_POST['password'];

	if ($_POST['username']=='admin' && $_POST['password']='admin') {
		$_SESSION['admin'] = 0;
		$_SESSION['username'] = '';
		redirect("/php/groups.php"); exit();
	}

	$query = "SELECT * FROM teachers WHERE teacher_login='".$user."'AND teacher_password=".$password."";

	$result = mysqli_query($link, $query) or die (mysqli_error($link));
	$u = mysqli_fetch_row($result);



	if ($u[0] != NULL) {
		$_SESSION['username'] = $user;
		$_SESSION['admin'] = $u[0];
	}



	if (isset($_SESSION['username'])) {
		redirect("/php/teacher.php"); exit();
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
			<label class="form-label">login</label>
			<input type='text' class="form-control" name='username'>
		</div>
		<div class="mb-3">
			<label class="form-label">Password</label>
			<input type="password" class="form-control" name='password'>
		</div>
		<button type="submit" class="btn btn-primary" name='add'>Submit</button>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
	</form>
</body>
</html>
