<?php

require_once "db.php";
session_start();

function get_skips_student_info($link, $month){
	if (isset($month)) {
		if (isset($_POST['filter_student'])) {
			$get_current_group_students = mysqli_query($link, "SELECT skips.skip_id, student.student_id, student.student_full_name, skips.total_skips, skips.resonable_skips, skips.month, skips.week FROM `skips` INNER JOIN student ON skips.student_id=student.student_id WHERE (student.`semester`='".$_GET['semester']."') AND (`month`='{$month}') AND (student.student_full_name LIKE '{$_POST['filter_student']}%') ORDER BY student.student_full_name ASC") or die(mysqli_error($link));;
			$get_sum = mysqli_query($link, "SELECT SUM(`total_skips`), SUM(`resonable_skips`), student.student_full_name FROM skips INNER JOIN student ON skips.student_id=student.student_id WHERE (student.student_full_name LIKE '{$_POST['filter_student']}%') AND (student.semester={$_GET['semester']}) AND (month={$month}) GROUP BY student.student_full_name");
			$sum = mysqli_fetch_row($get_sum);
			echo "<tr>";
			echo "<th scope='row'>Всього: </th>";
			echo "<th></th>";
			echo "<th>{$sum[0]}</th>";
			echo "<th>{$sum[1]}</th>";
			echo "</tr>";
		}else if ($_POST['filter_week']) {
			$get_current_group_students = mysqli_query($link, "SELECT skips.skip_id, student.student_id, student.student_full_name, skips.total_skips, skips.resonable_skips, skips.month, skips.week FROM `skips` INNER JOIN student ON skips.student_id=student.student_id WHERE (student.`semester`='".$_GET['semester']."') AND (`month`='{$month}') AND (`week`='{$_POST['filter_week']}') ORDER BY student.student_full_name ASC") or die(mysqli_error($link));
			$get_sum = mysqli_query($link, "SELECT SUM(`total_skips`), SUM(`resonable_skips`), week FROM skips INNER JOIN student ON skips.student_id=student.student_id WHERE (week='{$_POST['filter_week']}') AND (student.semester={$_GET['semester']}) AND (month={$month}) GROUP BY week");
			$sum = mysqli_fetch_row($get_sum);
			echo "<tr>";
			echo "<th scope='row'>Всього: </th>";
			echo "<th></th>";
			echo "<th>{$sum[0]}</th>";
			echo "<th>{$sum[1]}</th>";
			echo "</tr>";
		}else{
			$get_current_group_students = mysqli_query($link, "SELECT skips.skip_id, student.student_id, student.student_full_name, skips.total_skips, skips.resonable_skips, skips.month, skips.week FROM `skips` INNER JOIN student ON skips.student_id=student.student_id WHERE (student.`semester`='".$_GET['semester']."') AND (`month`='{$month}') ORDER BY student.student_full_name ASC") or die(mysqli_error($link));
		}
		$counter = 1;
		while ($row = mysqli_fetch_array($get_current_group_students)) {
			echo "<tr>";
			echo "<th scope='row'>{$counter}</th>";
			if (isset($_GET['month'])) {
				echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&month={$_GET['month']}'>";
			}else{
				echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&month={$_POST['month']}'>";
			}
			echo "<th><button type='submit' name='filter_student' class='btn' value={$row['student_full_name']}>{$row['student_full_name']}</button></th>";
			echo "<th>{$row['total_skips']}</th>";
			echo "<th>{$row['resonable_skips']}</th>";
			echo "<th><button type='submit' name='filter_week' class='btn' value={$row['week']}>{$row['week']}</button></th>";
			echo "<th><button type='submit' name='edit' class='btn btn-primary' value={$row['skip_id']}>Відредагувати</button></th>";
			echo "<th><button type='submit' name='delete' class='btn btn-primary' value={$row['skip_id']}>Видалити</button></th>";
			echo "</tr>";
			echo "</form>";
			$counter++;
		}
	}
}

if (isset($_POST['add'])) {
	mysqli_query($link,"INSERT INTO skips VALUES(NULL, '{$_POST['student']}', '{$_POST['total']}', '{$_POST['reason']}', '{$_GET['month']}','{$_POST['week']}')");
}

if (isset($_POST['change'])) {
	mysqli_query($link,"UPDATE skips SET student_id='{$_POST['student']}', total_skips={$_POST['total']}, resonable_skips='{$_POST['reason']}', week='{$_POST['week']}' WHERE skip_id={$_POST['change']}")or die(mysqli_error($link));
}

if (isset($_POST['delete'])) {
	mysqli_query($link,"DELETE FROM skips WHERE skip_id={$_POST['delete']}");
}

if (isset($_POST['edit'])) {
	$get_skip = mysqli_query($link,"SELECT * FROM skips WHERE skip_id={$_POST['edit']}");
	$skip=mysqli_fetch_row($get_skip);
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

	<?php echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}'>";  ?>
	<button type='submit' name='month' value="1">Вересень</button>
	<button type='submit' name='month' value="2">Жовтень</button>
	<button type='submit' name='month' value="3">Листопад</button>
	<button type='submit' name='month' value="4">Грудень</button>
	<button type='submit' name='month' value="5">Січень</button>
	<button type='submit' name='month' value="6">Лютий</button>
	<button type='submit' name='month' value="7">Березень</button>
	<button type='submit' name='month' value="8">Квітень</button>
	<button type='submit' name='month' value="9">Травень</button>
	<button type='submit' name='month' value="10">Червень</button>
</form>

<h1 style="text-align: center;">Пропуски</h1>
<table class="table">
	<thead>
		<tr>
			<th scope="col">№</th>
			<th scope="col">Ім'я</th>
			<th scope="col">Загальна кількість пропусків</th>
			<th scope="col">Пропуски з поважної причини</th>
			<th scope="col">Тиждень</th>
			<th scope="col"></th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
		<?php if (isset($_GET['month'])) get_skips_student_info($link, $_GET['month']); else get_skips_student_info($link, $_POST['month']); ?>
	</tbody>
</table>
<form method="POST" <?php echo "action='?id={$_GET['id']}&semester={$_GET['semester']}&month={$_POST['month']}'"; ?>>	
	<div class="mb-3">
		<label for='graduate'>Student</label>
		<select id='graduate' class="form-select" name='student'>
			<?php
			$get_spec = mysqli_query($link, "SELECT student_id, student_full_name FROM student WHERE student_group_id={$_GET['id']} AND semester={$_GET['semester']}");
			while ($row = mysqli_fetch_array($get_spec)) {
				if ($skip[1] == $row['student_id']) {
					echo "<option value='{$row['student_id']}' selected>{$row['student_full_name']}</option>";
				}else{
					echo "<option value='{$row['student_id']}'>{$row['student_full_name']}</option>";
				}
			}
			?>
		</select>
	</div>	
	<div class="mb-3">
		<label class='form-label'>Загальна кількість пропусків</label>
		<input type='text' class='form-control' name='total' <?php echo "value='{$skip[2]}'";?>/>
	</div>	
	<div class="mb-3">
		<label class='form-label'>Пропуски з поважної причини</label>
		<input type='text' class='form-control' name='reason' <?php echo "value='{$skip[3]}'";?>/>
	</div>	
	<div class="mb-3">
		<label class='form-label'>Тиждень</label>
		<input type='text' class='form-control' name='week' <?php echo "value='{$skip[5]}'";?>/>
	</div>
	<?php 
	if (isset($_POST['edit'])) {
		echo "<button class='btn btn-primary' type='submit' name='change' value='{$_POST['edit']}'>Change</button>";
	} else{
		echo "<button class='btn btn-primary' type='submit' name='add'>Add</button>";
	}
	?>

</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>