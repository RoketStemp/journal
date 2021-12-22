<?php

require_once "db.php";
session_start();

$get_group_query = mysqli_query($link, "SELECT * FROM `group` WHERE `group_id`='".$_GET['id']."'");
$result = mysqli_fetch_row($get_group_query);

if (isset($_GET['student'])) {
	$get_student_query = mysqli_query($link, "SELECT * FROM `student` WHERE `student_id`='".$_GET['student']."'");
	$student_result = mysqli_fetch_row($get_student_query);
}


if (isset($_POST['add'])) {
	for ($i=1; $i <= 8; $i++) { 
		$student_query .= "INSERT INTO `student` (`student_id`, `student_full_name`, `student_phone`, `student_address`, `student_stud_form`, `student_is_contract`, `student_mail`, `student_sex`, `student_nation`, `student_birth`, `student_graduate`, `student_grant`, `student_parents`, `semester`, `student_group_id`) VALUES (NULL, '{$_POST['fullname']}', '{$_POST['phone']}', '{$_POST['address']}', '{$_POST['form']}', '{$_POST['contract']}', '{$_POST['email']}', '{$_POST['sex']}', '{$_POST['nation']}', '{$_POST['date']}', '{$_POST['graduate']}', '{$_POST['grant']}', NULL, '{$i}', '{$_POST['group']}');";
	}
	mysqli_multi_query($link, $student_query);
	header("Location: group.php?id={$_GET['id']}");
}
if (isset($_POST['change'])) {
	mysqli_query($link, "UPDATE `student` SET `student_full_name`='{$_POST['fullname']}',`student_phone`='{$_POST['phone']}',`student_address`='{$_POST['address']}',`student_stud_form`={$_POST['form']},`student_is_contract`={$_POST['contract']},`student_mail`='{$_POST['email']}',`student_sex`={$_POST['sex']},`student_nation`={$_POST['nation']},`student_birth`='{$_POST['date']}',`student_graduate`={$_POST['graduate']},`student_grant`={$_POST['grant']},`student_group_id`={$_POST['group']} WHERE student_id={$_GET['student_name']}");
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
	<form method="POST" <?php echo "action='?student_name={$student_result[0]}'"; ?>>
		<div class="mb-3">
			<label class='form-label'>Group</label>
			<?php if (isset($_GET['student'])) {
				echo"<input type='text' class='form-control' name='group' value='{$student_result[14]}'/>";
			} else{
				echo"<input type='text' class='form-control' name='group' value='{$_GET['id']}'/>";
			}
			?>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Student full name</label>
			<input type='text' class='form-control' name='fullname' <?php echo "value='{$student_result[1]}'";?>/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Student phone number</label>
			<input type='text' class='form-control' name='phone' <?php echo "value='{$student_result[2]}'";?>/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Student address</label>
			<input type='text' class='form-control' name='address' <?php echo "value='{$student_result[3]}'";?>/>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Student birth date</label>
			<input type='date' class='form-control' name='date'/ <?php echo "value='{$student_result[9]}'";?>>
		</div>	
		<div class="mb-3">
			<label class='form-label'>Email</label>
			<input type='email' class='form-control' name='email' <?php echo "value='{$student_result[6]}'";?>/>
		</div>
		<div class="mb-3">
			<label for='form'>Форма навчання</label>
			<select id='form' class="form-select" name='form'>
			<?php
				$get_spec = mysqli_query($link, "SELECT * FROM student_studying_form");
				while ($row = mysqli_fetch_array($get_spec)) {
					echo "<option value='{$row['student_stud_form_id']}'>{$row['studying_form']}</option>";
				}
			?>
			</select>
		</div>	
		<div class="mb-3">
			<label for='contract'>Контракт/Бюджет</label>
			<select id='contract' class="form-select" name='contract'>
			<?php
				$get_spec = mysqli_query($link, "SELECT * FROM student_contract");
				while ($row = mysqli_fetch_array($get_spec)) {
					echo "<option value='{$row['student_is_contract_id']}'>{$row['field_name']}</option>";
				}
			?>
			</select>
		</div>	
		<div class="mb-3">
			<label for='sex'>Стать</label>
			<select id='sex' class="form-select" name='sex'>
			<?php
				$get_spec = mysqli_query($link, "SELECT * FROM student_sex_choose");
				while ($row = mysqli_fetch_array($get_spec)) {
					echo "<option value='{$row['student_sex_id']}'>{$row['sex_name']}</option>";
				}
			?>
			</select>
		</div>	
		<div class="mb-3">
			<label for='nation'>Nationality</label>
			<select id='nation' class="form-select" name='nation'>
			<?php
				$get_spec = mysqli_query($link, "SELECT * FROM student_nation_choose");
				while ($row = mysqli_fetch_array($get_spec)) {
					echo "<option value='{$row['student_nation_id']}'>{$row['nation_name']}</option>";
				}
			?>
			</select>
		</div>	
		<div class="mb-3">
			<label for='graduate'>Graduate</label>
			<select id='graduate' class="form-select" name='graduate'>
			<?php
				$get_spec = mysqli_query($link, "SELECT * FROM student_graduate_choose");
				while ($row = mysqli_fetch_array($get_spec)) {
					echo "<option value='{$row['student_graduate_id']}'>{$row['graduate_name']}</option>";
				}
			?>
			</select>
		</div>	
		<div class="mb-3">
			<label for='grant'>Стипендія</label>
			<select id='grant' class="form-select" name='grant'>
				<option value='0'>Так</option>
				<option value='1'>Ні</option>
			</select>
		</div>	
		<?php 
		if (isset($_GET['student'])) {
			echo "<button class='btn btn-primary' type='submit' name='change'>Change</button>";
		} else{
			echo "<button class='btn btn-primary' type='submit' name='add'>Add</button>";
		}
		?>
		
	</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>