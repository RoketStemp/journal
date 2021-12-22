<?php

require_once "db.php";
session_start();

if (isset($_POST['add'])) {
	mysqli_query($link, "INSERT INTO promotions_and_reprimands VALUES(NULL, '{$_POST['date']}', '{$_POST['order']}', '{$_POST['prom']}')");
	$get_prom_id = mysqli_query($link, "SELECT prom_id FROM promotions_and_reprimands ORDER BY prom_id DESC");
	$prom = mysqli_fetch_row($get_prom_id);
	mysqli_query($link, "INSERT INTO prom_student VALUES({$prom[0]},{$_POST['student']})");

}

if (isset($_POST['change'])) {
	mysqli_query($link, "UPDATE `promotions_and_reprimands` SET `prom_date`='{$_POST['date']}',`prom_order_id`={$_POST['order']},`prom_descriprion`='{$_POST['prom']}' WHERE prom_id={$_POST['change']}");
	mysqli_query($link, "UPDATE prom_student SET student_id={$_POST['student']} WHERE prom_id={$_POST['change']}");
}

if (isset($_POST['delete'])) {
	mysqli_query($link, "DELETE FROM promotions_and_reprimands WHERE prom_id={$_POST['delete']}");
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
				<th scope="col">Дата</th>
				<th scope="col">Номер наказу</th>
				<th scope="col">Опис</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
				<?php 
				$get_s = mysqli_query($link, "SELECT prom_student.prom_id, student.student_full_name FROM prom_student INNER JOIN student ON prom_student.student_id=student.student_id WHERE student.student_group_id={$_GET['id']} AND student.semester={$_GET['semester']} ORDER BY prom_student.prom_id DESC");

				while ($s = mysqli_fetch_array($get_s)){
					$get_promo = mysqli_query($link, "SELECT * FROM promotions_and_reprimands WHERE prom_id={$s[0]}");
					$p = mysqli_fetch_row($get_promo);
					echo "<tr>";
					echo "<th>{$p[0]}</th>";
					echo "<th>{$s[1]}</th>";
					echo "<th>{$p[1]}</th>";
					echo "<th>{$p[2]}</th>";
					echo "<th>{$p[3]}</th>";
					echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&prom_id={$p[0]}&student={$s[0]}'>";
					echo "<th><button name='edit' type='submit' value='$p[0] 'class='btn btn-primary'>Відредагувати</button></th>";
					echo "<th><button name='delete' type='submit' value='$p[0] 'class='btn btn-primary'>Видалити</button></th>";
					echo "</form>";
					echo "</tr>";
				}
				?>
		</tbody>
	</table>

	<?php if (isset($_GET['prom_id'])) {
		$get_promotion = mysqli_query($link, "SELECT * FROM promotions_and_reprimands WHERE prom_id={$_GET['prom_id']}");
		$gp = mysqli_fetch_row($get_promotion);
	} ?>
	<form method="POST" <?php if(isset($_GET['prom_id'])) echo "action='?id={$_GET['id']}&semester={$_GET['semester']}&prom_id={$_GET['prom_id']}'"; else echo "action='?id={$_GET['id']}&semester={$_GET['semester']}'"; ?>>
		<div class="mb-3">
			<label for='stud' class='form-label'>Студент</label>
			<select name='student' id='stud' class='form-select'>
				<?php 
				$get_students = mysqli_query($link, "SELECT student_id, student_full_name FROM `student` WHERE (`student_group_id`='".$_GET['id']."') AND (`semester`='{$_GET['semester']}') ORDER BY student_full_name ASC");
				while ($student = mysqli_fetch_array($get_students)) {
					if ($student[0] == $_GET['student']) {
						echo "<option value='{$student[0]}' selected>{$student[1]}</option>";
					}else{echo "<option value='{$student[0]}'>{$student[1]}</option>";}
				}
				?>
			</select>
		</div>
		<div class="mb-3">
			<label class='form-label'>Дата</label>
			<input type="date" name="date" class='form-control' <?php echo "value='{$gp[1]}'"; ?>>
		</div>
		<div class="mb-3">
			<label class='form-label'>Номер наказу</label>
			<input type="text" name="order" class='form-control' <?php echo "value='{$gp[2]}'"; ?>>
		</div>
		<div class="mb-3 form-floating">
			<textarea name='prom' class="form-control" id="floatingTextarea2" style="height: 200px"><?php echo "{$gp[3]}"; ?></textarea>
			<label for="floatingTextarea2">Заохочення/догана</label>
		</div>
		<?php if (!isset($_POST['edit'])) {
			echo "<button type='submit' name='add' class='btn btn-primary'>Додати</button>";
		}else{
			echo "<button type='submit' name='change' value='{$_GET['prom_id']}' class='btn btn-primary'>Змінити</button>";
		} ?>
		
	</form>
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>