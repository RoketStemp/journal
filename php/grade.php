<?php

require_once "db.php";
session_start();

if (isset($_POST['add'])) {
	mysqli_query($link, "INSERT INTO marks VALUES(NULL, {$_POST['grade']}, '{$_POST['name']}')");

	$get_mark_id = mysqli_query($link, "SELECT * FROM marks ORDER BY mark_id DESC");
	$get = mysqli_fetch_row($get_mark_id);

	mysqli_query($link, "INSERT INTO subject_mark SET mark_id={$get[0]}, student_id='{$_GET['student']}', semester={$_GET['semester']}, subject_id={$_GET['subject']}");
}

if (isset($_POST['accept'])) {
	mysqli_query($link, "UPDATE marks SET mark_name='{$_POST['name']}', mark_subject_grade='{$_POST['grade']}' WHERE mark_id='{$_POST['accept']}'");
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
	$get_student = mysqli_query($link, "SELECT student_full_name FROM student WHERE student_id={$_GET['student']} AND semester={$_GET['semester']}");
	$s = mysqli_fetch_row($get_student);
	$get_subject = mysqli_query($link, "SELECT subject_name FROM subjects WHERE subject_id={$_GET['subject']}");
	$sub = mysqli_fetch_row($get_subject);

	echo "<h1>{$sub[0]}</h1>";
	echo "<h2>{$s[0]}</h2>";
	?>

	<table class="table">
		<thead>
			<tr>
				<th scope="col">№</th>
				<th scope="col">Назва</th>
				<th scope="col">Оцінка</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$get_grades = mysqli_query($link, "SELECT mark_id FROM subject_mark WHERE student_id={$_GET['student']} AND semester={$_GET['semester']} AND subject_id={$_GET['subject']}");
			$c = 1;
			while ($s = mysqli_fetch_array($get_grades)) {
				$get_grade = mysqli_query($link, "SELECT * FROM marks WHERE mark_id={$s[0]}");
				$grade = mysqli_fetch_row($get_grade);
				echo "<form method='POST' action='grade.php?student={$_GET['student']}&subject={$_GET['subject']}&id={$_GET['id']}&semester={$_GET['semester']}&teacher={$_GET['teacher']}'>";
				echo "<tr>";
				echo "<th scope='row'>{$c}</th>";
				echo "<th>{$grade[2]}</th>";
				echo "<th>{$grade[1]}</th>";
				echo "<th><button type='submit' name='change' value='{$s[0]}' class='btn btn-primary'>Відредагувати</button></th>";
				echo "</tr>";
				echo "</form>";
				$c++;
			}
			$get_sum_query = mysqli_query($link, "SELECT `subject_mark`.subject_id, `subject_mark`.student_id, `subject_mark`.semester, SUM(`marks`.`mark_subject_grade`) FROM `subject_mark` LEFT JOIN `marks` ON   `subject_mark`.`mark_id`=`marks`.`mark_id` WHERE `subject_mark`.subject_id={$_GET['subject']} AND `subject_mark`.student_id={$_GET['student']} AND `subject_mark`.semester={$_GET['semester']}");

			$sum = mysqli_fetch_row($get_sum_query);

			echo "<tr>";
			echo "<th>Всього:</th>";
			echo "<th></th>";
			echo "<th>{$sum[3]}</th>";
			echo "<th></th>";
			echo "</tr>";
			?>
		</tbody>
	</table>

	<?php 
	echo "<form method='POST' action='grade.php?student={$_GET['student']}&subject={$_GET['subject']}&id={$_GET['id']}&semester={$_GET['semester']}&teacher={$_GET['teacher']}'>";
	
	if (isset($_POST['change'])) {
		$get_mark = mysqli_query($link, "SELECT * FROM marks WHERE mark_id={$_POST['change']}");
		$mark = mysqli_fetch_row($get_mark);
		echo "<div class='mb-3'>";
		echo "<label class='form-label'>Назва</label>";
		echo "<input type='text' class='form-control' name='name' value='{$mark[2]}'>";
		echo "</div>";
		echo "<div class='mb-3'>";
		echo "<label class='form-label'>Оцінка</label>";
		echo "<input type='text' class='form-control' name='grade' value='{$mark[1]}'>";
		echo "</div>";
		echo "<button type='submit' class='btn btn-primary' name='accept' value='{$mark[0]}'>Change</button>";
	} else{
		echo "<div class='mb-3'>";
		echo "<label class='form-label'>Назва</label>";
		echo "<input type='text' class='form-control' name='name'>";
		echo "</div>";
		echo "<div class='mb-3'>";
		echo "<label class='form-label'>Оцінка</label>";
		echo "<input type='text' class='form-control' name='grade'>";
		echo "</div>";
		echo "<button type='submit' class='btn btn-primary' name='add'>Submit</button>";
	}
	
	echo "</form>";
	?>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>