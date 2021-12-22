<?php

require_once "db.php";
session_start();

$check = mysqli_query($link, "SELECT * FROM group_subjects WHERE group_id={$_GET['id']} AND semester={$_GET['semester']}");
$check_result = array();
foreach ($check as $value) {
	array_push($check_result, "{$value['teacher_id']},{$value['subject_id']}");
}

if (isset($_POST['select'])) {
	mysqli_query($link, "DELETE FROM group_subjects WHERE group_id={$_GET['id']} AND semester={$_GET['semester']}");
	foreach ($_POST['subjects'] as $value) {
		$subject = explode(",",$value)[0];
		$teacher = explode(",",$value)[1];

		mysqli_query($link,"INSERT INTO group_subjects VALUES({$_GET['id']},{$subject},{$teacher},{$_GET['semester']})");
	}
	header("Refresh:0");
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

	<?php 
	$get_all_teachers = mysqli_query($link, "SELECT * FROM teachers");
	$subject_counter=0;
	$teacher_counter=0;
	echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}'>";

	while ($row = mysqli_fetch_array($get_all_teachers)) {
		echo "<div class='mb-3'>";
		$get_teacher_subjects = mysqli_query($link, "SELECT * FROM subject_teacher WHERE teacher_id={$row[0]}");

		echo "<p>{$row[1]}</p>";
		while ($subs = mysqli_fetch_array($get_teacher_subjects)) {
			$get_subs = mysqli_query($link, "SELECT * FROM subjects WHERE subject_id={$subs[1]}");
			$subs = mysqli_fetch_row($get_subs);

			if (in_array("{$row[0]},{$subs[0]}", $check_result)) {
				echo "<input class='form-check-input' id='subs' type='checkbox' name='subjects[]' value='{$subs[0]},{$row[0]}' checked>{$subs[1]}<br>";
			}else{
				echo "<input class='form-check-input' id='subs' type='checkbox' name='subjects[]' value='{$subs[0]},{$row[0]}'>{$subs[1]}<br>";
			}
			$subject_counter++;
		}
		echo "</div>";
	$teacher_counter++;
	}
	echo "<button type='submit' class='btn btn-primary' name='select'>Select</button>";
	echo "</form>";
	?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>