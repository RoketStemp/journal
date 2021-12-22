<?php

require_once "db.php";
session_start();

$get_teacher_query = mysqli_query($link, "SELECT * FROM teachers WHERE teacher_login='".$_SESSION['username']."'");
$result = mysqli_fetch_row($get_teacher_query);

$get_group_query = mysqli_query($link, "SELECT * FROM `group` WHERE `group_curator`={$result[0]}");
$group_result = mysqli_fetch_row($get_group_query);


if (isset($_POST['add_subject'])) {
	mysqli_query($link, "DELETE FROM subject_teacher WHERE teacher_id='{$result[0]}'");

	foreach ($_POST['subjects'] as $value) {
		$get_subjects_query .= "INSERT INTO subject_teacher VALUES(NULL , {$value} , {$result[0]});";
	}
	mysqli_multi_query($link, $get_subjects_query);
	header("Refresh:0");
}
if (isset($_POST['change_teacher_data'])) {
	mysqli_query($link," UPDATE teachers SET teacher_login='".$_POST['username']."', teacher_password='".$_POST['password']."', teacher_full_name='".$_POST['name']."'");
	$_SESSION['username'] = $_POST['username'];
	header("Refresh:0");
}
if (isset($_POST['add_group'])) {
	mysqli_query($link, "INSERT INTO `group` VALUES({$_POST['id']} , {$_POST['spec']} , {$result[0]}, NULL, {$_POST['course']})");
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
	echo "<div>";
	echo "<h1>".$result[1]."</h1>";
	echo "<p>Login: ".$result[2]."</p>";
	echo "<p>Password: ".$result[3]."</p>";
	echo "<p>Groups: <a href='group.php?id={$group_result[0]}'>".$group_result[0]."</a></p>";
	echo "</div>";

	$get_subjects_query = mysqli_query($link, "SELECT * FROM subjects");

	$get_subject_teacher_query = mysqli_query($link, "SELECT subject_id FROM subject_teacher WHERE teacher_id={$result['0']}");
	$active_subjects = array();
	foreach ($get_subject_teacher_query as $value) {
		array_push($active_subjects, $value['subject_id']); 
	}
	
	echo "<form method='POST'>";
	echo "<div class='mb-3'>";
	while ($row = mysqli_fetch_array($get_subjects_query)) {
		echo "<div class='form-check form-switch'>";
		if (in_array($row['subject_id'], $active_subjects)) {
			echo "<input class='form-check-input' type='checkbox' id='flexSwitchCheck' name='subjects[]' value='{$row['subject_id']}' checked>";
		}else{
			echo "<input class='form-check-input' type='checkbox' id='flexSwitchCheck' name='subjects[]' value='{$row['subject_id']}'>";
		}
		
		echo "<label class='form-check-label' for='flexSwitchCheck'>{$row['subject_name']}</label>";
		echo "</div>";
	}
	echo "</div>";
	echo "<button type='submit' class='btn btn-primary' name='add_subject'>Выбрать Предметы</button>";
	echo "<br>";
	echo "<button class='mt-3 btn btn-primary' type='button' data-bs-toggle='collapse' data-bs-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>Изменить данные</button>";
	echo "<div class='collapse' id='collapseExample'>";
	echo "<div class='mb-3'>";
	echo "<label class='form-label'>Full name</label>";
	echo "<input type='text' class='form-control' name='name' value='{$result[1]}'>";
	echo "</div>";
	echo "<div class='mb-3'>";
	echo "<label class='form-label'>login</label>";
	echo "<input type='text' class='form-control' name='username' value='{$result[3]}'>";
	echo "</div>";
	echo "<div class='mb-3'>";
	echo "<label for='exampleInputPassword1' class='form-label'>Password</label>";
	echo "<input type='text' class='form-control' name='password' value='{$result[4]}'>";
	echo "</div>";
	echo "<button type='submit' class='btn btn-primary' name='change_teacher_data'>Submit</button>";
	echo "</div>";
	echo "</form>";
	?>
	<button class='mt-3 btn btn-primary' type='button' data-bs-toggle='collapse' data-bs-target='#addGroupCollapse' aria-expanded='false' aria-controls='addGroupCollapse'>Создать группу</button>
	<div class='collapse' id='addGroupCollapse'>
		<form method='POST'>
			<div class="mb-3">
				<label for="groupId" class="form-label">Номер группы: </label>
				<input type="text" class="form-control" id="groupId" aria-describedby="groupCourse" name='id'>
			</div>
			<div class="mb-3">
				<label for="groupCourse" class="form-label">Курс: </label>
				<input type="text" class="form-control" id="groupCourse" aria-describedby="groupCourse" name='course'>
			</div>
			<div class="mb-3">
				<label for='spec'></label>
				<select id='spec' class="form-select" name='spec'>
					<?php
						$get_spec = mysqli_query($link, "SELECT * FROM group_special");
						while ($row = mysqli_fetch_array($get_spec)) {
							echo "<option value='{$row['special_id']}'>{$row['special_name']}</option>";
						}
					?>
				</select>
			</div>
			<button type='submit' class='btn btn-primary' name='add_group'>Submit</button>
		</form>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>