<?php

require_once "db.php";
session_start();

if (isset($_POST['add'])) {
	mysqli_query($link,"INSERT INTO educational_work VALUES(NULL, '{$_POST['date']}', '{$_POST['teacher']}', '{$_POST['prom']}', '{$_GET['id']}', '{$_GET['semester']}')") or die(mysqli_error($link));
}
if (isset($_POST['change'])) {
	mysqli_query($link,"UPDATE educational_work SET educational_work_date='{$_POST['date']}', educational_work_teacher_id='{$_POST['teacher']}', educational_work_grade='{$_POST['prom']}'");
}
if (isset($_POST['delete'])) {
	mysqli_query($link,"DELETE FROM educational_work WHERE educational_work_id={$_GET['work_id']}");
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
				<th scope="col">Дата</th>
				<th scope="col">Викладач</th>
				<th scope="col">Оцінка</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
				<?php 
				$get_work = mysqli_query($link, "SELECT * FROM educational_work WHERE group_id={$_GET['id']} AND semester={$_GET['semester']} ORDER BY educational_work_id DESC");

				while ($w = mysqli_fetch_array($get_work)){
					$get_teacher = mysqli_query($link, "SELECT teacher_full_name FROM teachers WHERE teacher_id={$w[2]}");
					$t = mysqli_fetch_row($get_teacher);
					echo "<tr>";
					echo "<th>{$w[0]}</th>";
					echo "<th>{$w[1]}</th>";
					echo "<th>{$t[0]}</th>";
					echo "<th>{$w[3]}</th>";
					echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&work_id={$w[0]}&teacher={$w[2]}'>";
					echo "<th><button name='edit' type='submit' value='$w[0] 'class='btn btn-primary'>Відредагувати</button></th>";
					echo "<th><button name='delete' type='submit' value='$w[0] 'class='btn btn-primary'>Видалити</button></th>";
					echo "</form>";
					echo "</tr>";
				}
				?>
		</tbody>
	</table>

	<?php if (isset($_GET['work_id'])) {
		$get_promotion = mysqli_query($link, "SELECT * FROM educational_work WHERE educational_work_id={$_GET['work_id']}");
		$gp = mysqli_fetch_row($get_promotion);
	} ?>
	<form method="POST" <?php if(isset($_GET['work_id'])) echo "action='?id={$_GET['id']}&semester={$_GET['semester']}&work_id={$_GET['work_id']}'"; else echo "action='?id={$_GET['id']}&semester={$_GET['semester']}'"; ?>>
		<div class="mb-3">
			<label for='stud' class='form-label'>Викладач</label>
			<select name='teacher' id='stud' class='form-select'>
				<?php 
				$get_teachers = mysqli_query($link, "SELECT teacher_id FROM group_subjects WHERE group_id={$_GET['id']} AND semester={$_GET['semester']}");
				while ($teach = mysqli_fetch_array($get_teachers)) {
					$teacher = mysqli_query($link, "SELECT teacher_full_name FROM teachers WHERE teacher_id={$teach[0]}");
					$t = mysqli_fetch_row($teacher);
					if ($teach[0] == $_GET['teacher']) {
						echo "<option value='{$teach[0]}' selected>{$t[0]}</option>";
					}else{echo "<option value='{$teach[0]}'>{$t[0]}</option>";}
				}
				?>
			</select>
		</div>
		<div class="mb-3">
			<label class='form-label'>Дата</label>
			<input type="date" name="date" class='form-control' <?php echo "value='{$gp[1]}'"; ?>>
		</div>
		<div class="mb-3 form-floating">
			<textarea name='prom' class="form-control" id="floatingTextarea2" style="height: 200px"><?php echo "{$gp[3]}"; ?></textarea>
			<label for="floatingTextarea2">Оцінка</label>
		</div>
		<?php if (!isset($_POST['edit'])) {
			echo "<button type='submit' name='add' class='btn btn-primary'>Додати</button>";
		}else{
			echo "<button type='submit' name='change' value='{$_GET['work_id']}' class='btn btn-primary'>Змінити</button>";
		} ?>
		
	</form>
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>