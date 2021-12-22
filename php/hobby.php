<?php

require_once "db.php";
session_start();

if (isset($_POST['add'])) {
	mysqli_query($link,"INSERT INTO hobby_group VALUES(NULL, '{$_POST['prom']}', '{$_POST['teacher']}')");
}
if (isset($_POST['change'])) {
	mysqli_query($link,"UPDATE hobby_group SET hobby_group_occupation='{$_POST['prom']}', hobby_work_student_id='{$_POST['teacher']}' WHERE hobby_group_id={$_POST['change']}");
}
if (isset($_POST['delete'])) {
	mysqli_query($link,"DELETE FROM hobby_group WHERE hobby_group_id={$_GET['hobby']}");
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

	<table class="table">
		<thead>
			<tr>
				<th scope="col">№</th>
				<th scope="col">Студент</th>
				<th scope="col">Назва гуртка, секції і яку роботу виконав</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
				<?php 
				$get_work = mysqli_query($link, "SELECT student.student_full_name, hobby_group.hobby_group_id, hobby_group.hobby_group_occupation FROM hobby_group INNER JOIN student ON hobby_group.hobby_work_student_id=student.student_id WHERE student.student_group_id={$_GET['id']} AND student.semester={$_GET['semester']} ORDER BY hobby_group.hobby_group_id DESC");

				while ($w = mysqli_fetch_array($get_work)){
					echo "<tr>";
					echo "<th>{$w[1]}</th>";
					echo "<th>{$w[0]}</th>";
					echo "<th>{$w[2]}</th>";
					echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&hobby={$w[1]}&teacher={$w[0]}'>";
					echo "<th><button name='edit' type='submit' value='$w[1] 'class='btn btn-primary'>Відредагувати</button></th>";
					echo "<th><button name='delete' type='submit' value='$w[1] 'class='btn btn-primary'>Видалити</button></th>";
					echo "</form>";
					echo "</tr>";
				}
				?>
		</tbody>
	</table>

	<?php if (isset($_GET['hobby'])) {
		$get_promotion = mysqli_query($link, "SELECT * FROM hobby_group WHERE hobby_group_id={$_GET['hobby']}");
		$gp = mysqli_fetch_row($get_promotion);
	} ?>
	<form method="POST" <?php if(isset($_GET['hobby'])) echo "action='?id={$_GET['id']}&semester={$_GET['semester']}&hobby={$_GET['hobby']}'"; else echo "action='?id={$_GET['id']}&semester={$_GET['semester']}'"; ?>>
		<div class="mb-3">
			<label for='stud' class='form-label'>Студент</label>
			<select name='teacher' id='stud' class='form-select'>
				<?php 
				$get_teachers = mysqli_query($link, "SELECT student_id, student_full_name FROM student WHERE student_group_id={$_GET['id']} AND semester={$_GET['semester']}");
				while ($teach = mysqli_fetch_array($get_teachers)) {
					if ($teach[0] == $_GET['teacher']) {
						echo "<option value='{$teach[0]}' selected>{$teach[1]}</option>";
					}else{echo "<option value='{$teach[0]}'>{$teach[1]}</option>";}
				}
				?>
			</select>
		</div>
		<div class="mb-3 form-floating">
			<textarea name='prom' class="form-control" id="floatingTextarea2" style="height: 200px"><?php echo "{$gp[1]}"; ?></textarea>
			<label for="floatingTextarea2">Назва гуртка, секції і яку роботу виконав</label>
		</div>
		<?php if (!isset($_POST['edit'])) {
			echo "<button type='submit' name='add' class='btn btn-primary'>Додати</button>";
		}else{
			echo "<button type='submit' name='change' value='{$_GET['hobby']}' class='btn btn-primary'>Змінити</button>";
		} ?>
		
	</form>
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>