<?php

require_once "db.php";
session_start();

$get_students = mysqli_query($link, "SELECT student_id, student_parents from student WHERE student_full_name='{$_GET['student']}'");



if (isset($_POST['add'])) {
	mysqli_query($link, "INSERT INTO parents VALUES (NULL, '{$_POST['fname']}', '{$_POST['fnum']}', '{$_POST['fjob']}', '{$_POST['mname']}', '{$_POST['mnum']}', '{$_POST['mjob']}', '{$_POST['address']}')");
	$get_parents_id = mysqli_query($link, "SELECT parents_id FROM parents WHERE parents_address='{$_POST['address']}'");
	$parents_result = mysqli_fetch_row($get_parents_id);
	while ($row = mysqli_fetch_array($get_students)) {
		mysqli_query($link, "UPDATE student SET student_parents={$parents_result[0]} WHERE student_id={$row[0]}");
	}
	header("Location: groups.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
	<nav>
		<a href="groups.php">All groups</a>
		<a href="registration.php">Registration</a>
		<a href="login.php">Login</a>
		<?php if ($_SESSION['admin']!=0) {
			echo "<a href='teacher.php'>Teacher</a>";
		} ?>
		<a href=""></a>
	</nav>
	<form method="POST">
		<div class="mb-3">
			<label class='form-label'>Батько: </label>
			<input type='text' class='form-control' name='fname'/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Номер телефона:</label>
			<input type='text' class='form-control' name='fnum'/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Місце роботи:</label>
			<input type='text' class='form-control' name='fjob'/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Мати:</label>
			<input type='text' class='form-control' name='mname'/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Номер телефона:</label>
			<input type='text' class='form-control' name='mnum'/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Місце роботи:</label>
			<input type='text' class='form-control' name='mjob'/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Адреса батьків:</label>
			<input type='text' class='form-control' name='address'/>
		</div>	
		<button class="btn btn-primary" type="submit" name='add'>Add</button>
	</form>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>