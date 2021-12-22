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

	<?php 
	$get_student = mysqli_query($link, "SELECT student_full_name FROM student WHERE student_id={$_GET['id']} AND semester={$_GET['semester']}");
	$s = mysqli_fetch_row($get_student);

	echo "<h2>{$s[0]}</h2>";
	?>

	<h1 style="text-align: center;">Предмети</h1>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Назва предмету</th>
				<th scope="col">Викладач</th>
				<th scope="col">Оцінка</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php 
					
				$get_subjects = mysqli_query($link, "SELECT subject_id, teacher_id FROM `group_subjects` WHERE `group_id`='{$_GET['group']}' AND semester='{$_GET['semester']}'");
				while ($subs = mysqli_fetch_array($get_subjects)) {
					$get_subject_name = mysqli_query($link, "SELECT subject_name FROM `subjects` WHERE `subject_id`='{$subs[0]}'");
					$s_name = mysqli_fetch_row($get_subject_name);
					$get_teacher_name = mysqli_query($link, "SELECT teacher_full_name FROM `teachers` WHERE `teacher_id`='{$subs[1]}'");
					$t_name = mysqli_fetch_row($get_teacher_name);
					$get_sum_query = mysqli_query($link, "SELECT `subject_mark`.subject_id, `subject_mark`.student_id, `subject_mark`.semester, SUM(`marks`.`mark_subject_grade`) FROM `subject_mark` LEFT JOIN `marks` ON   `subject_mark`.`mark_id`=`marks`.`mark_id` WHERE `subject_mark`.subject_id={$subs[0]} AND `subject_mark`.student_id={$_GET['id']} AND `subject_mark`.semester={$_GET['semester']}");
					$sum = mysqli_fetch_row($get_sum_query);
					echo "<tr>";
					echo "<th>{$s_name[0]}</th>";
					echo "<th>{$t_name[0]}</th>";
					echo "<th>{$sum[3]}</th>";
					echo "<th><a href='grade.php?student={$_GET['id']}&subject={$subs[0]}&id={$_GET['group']}&semester={$_GET['semester']}&teacher={$subs[1]}' class='btn btn-primary'>Відредагувати</a></th>";
					echo "</tr>";
				}
					
				?>
			</tr>
		</tbody>
	</table>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>