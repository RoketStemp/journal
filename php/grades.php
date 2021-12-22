<?php

require_once "db.php";
session_start();

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
	<?php $get_subject = mysqli_query($link, "SELECT subject_name FROM subjects WHERE subject_id={$_GET['subject']}"); 
		$r = mysqli_fetch_row($get_subject);
		echo "<h1>{$r[0]}</h1>";
	?>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">№</th>
				<th scope="col">Ім'я</th>
				<th scope="col">Оцінка</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$get_students = mysqli_query($link, "SELECT student_id, student_full_name FROM student WHERE student_group_id={$_GET['id']} AND semester={$_GET['semester']}");
			$c = 1;
			while ($s = mysqli_fetch_array($get_students)) {
				$get_sum_query = mysqli_query($link, "SELECT `subject_mark`.subject_id, `subject_mark`.student_id, `subject_mark`.semester, SUM(`marks`.`mark_subject_grade`) FROM `subject_mark` LEFT JOIN `marks` ON   `subject_mark`.`mark_id`=`marks`.`mark_id` WHERE `subject_mark`.subject_id={$_GET['subject']} AND `subject_mark`.student_id={$s[0]} AND `subject_mark`.semester={$_GET['semester']}");

				$sum = mysqli_fetch_row($get_sum_query);
				echo "<tr>";
				echo "<th scope='row'>{$c}</th>";
				echo "<th>{$s[1]}</th>";
				echo "<th>{$sum[3]}</th>";
				echo "<th><a href='grade.php?student={$s[0]}&subject={$_GET['subject']}&id={$_GET['id']}&semester={$_GET['semester']}&teacher={$_GET['teacher']}' class='btn btn-primary'>Відредагувати</a></th>";
				echo "</tr>";
				$c++;
			}
			?>
		</tbody>
	</table>
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>