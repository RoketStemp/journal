<?php

require_once "db.php";
session_start();

$test_query = mysqli_query($link, "SELECT student_parents from student WHERE student_id={$_GET['id']} AND semester={$_GET['semester']}");
$test_result = mysqli_fetch_row($test_query);

$parents = 2;
if ($test_result[0] != NULL) {
	$get_group_query = mysqli_query($link, "SELECT `student_id` AS 'Id',`student_full_name` AS 'Name',`student_phone` AS 'Phone', `student_address` AS 'Address',`student_studying_form`.`studying_form` AS 'Form',`student_contract`.`field_name` AS 'Contract',`student_mail` AS 'Mail',`student_nation_choose`.`nation_name` AS 'Nation',`student_sex_choose`.`sex_name` AS 'Sex',`student_birth` AS 'Birth',`student_graduate_choose`.`graduate_name` AS 'Grad',`student_grant` AS 'Grant',`parents`.`father_full_name` AS 'Father',`parents`.`father_number` AS 'Father Number',`parents`.`father_job` AS 'Father Job', `parents`.`mother_full_name` AS 'Mother', `parents`.`mother_phone` AS 'Mother Phone', `parents`.`mother_job` AS 'Mother Job', `parents`.`parents_address` AS 'Parents Address', `semester` AS 'Semester', `student_group_id` AS 'Group' FROM `student` INNER JOIN `student_studying_form` ON `student`.`student_stud_form`=`student_studying_form`.`student_stud_form_id` INNER JOIN `student_contract` ON `student`.`student_is_contract`=`student_contract`.`student_is_contract_id` INNER JOIN `student_nation_choose` ON `student`.`student_nation`=`student_nation_choose`.`student_nation_id` INNER JOIN `student_sex_choose` ON `student`.`student_sex`=`student_sex_choose`.`student_sex_id` INNER JOIN `student_graduate_choose` ON `student`.`student_graduate`=`student_graduate_choose`.`student_graduate_id` INNER JOIN `parents` ON `student`.`student_parents`=`parents`.`parents_id` WHERE `student_id`='".$_GET['id']."' AND semester='{$_GET['semester']}'");
	$result = mysqli_fetch_row($get_group_query);
	$parents = 1;
}else{
	$get_group_query = mysqli_query($link, "SELECT `student_id` AS 'Id',`student_full_name` AS 'Name',`student_phone` AS 'Phone', `student_address` AS 'Address',`student_studying_form`.`studying_form` AS 'Form',`student_contract`.`field_name` AS 'Contract',`student_mail` AS 'Mail',`student_nation_choose`.`nation_name` AS 'Nation',`student_sex_choose`.`sex_name` AS 'Sex',`student_birth` AS 'Birth',`student_graduate_choose`.`graduate_name` AS 'Grad',`student_grant` AS 'Grant', `semester` AS 'Semester', `student_group_id` AS 'Group' FROM `student` INNER JOIN `student_studying_form` ON `student`.`student_stud_form`=`student_studying_form`.`student_stud_form_id` INNER JOIN `student_contract` ON `student`.`student_is_contract`=`student_contract`.`student_is_contract_id` INNER JOIN `student_nation_choose` ON `student`.`student_nation`=`student_nation_choose`.`student_nation_id` INNER JOIN `student_sex_choose` ON `student`.`student_sex`=`student_sex_choose`.`student_sex_id` INNER JOIN `student_graduate_choose` ON `student`.`student_graduate`=`student_graduate_choose`.`student_graduate_id` WHERE `student_id`='".$_GET['id']."' AND semester='{$_GET['semester']}'");
	$result = mysqli_fetch_row($get_group_query);
	$parents = 0;
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
	<ul class="list-group">
		<li class="list-group-item">Повне ім'я: <?php echo $result[1]; ?></li>
		<li class="list-group-item">Номер телефону: <?php echo $result[2]; ?></li>
		<li class="list-group-item">Адреса: <?php echo $result[3]; ?></li>
		<li class="list-group-item">Форма навчанная: <?php echo $result[4]; ?></li>
		<li class="list-group-item">Контракт/Бюджет: <?php echo $result[5]; ?></li>
		<li class="list-group-item">Пошта: <?php echo $result[6]; ?></li>
		<li class="list-group-item">Стать: <?php echo $result[7]; ?></li>
		<li class="list-group-item">Національність: <?php echo $result[8]; ?></li>
		<li class="list-group-item">Дата народження: <?php echo $result[9]; ?></li>
		<li class="list-group-item">Освіта: <?php echo $result[10]; ?></li>
		<li class="list-group-item">Стипендія: <?php if($result[11]==0)echo 'Ні'; else echo "Так"; ?></li>
		<li class="list-group-item">Группа: <?php echo $result[20]; ?></li>
		<?php 
		if ($parents ==1) {
			echo "Батьки:";
			echo "<ul class='list-group'> ";
			echo "<li class='list-group-item'>Батько: {$result[12]}</li>";
			echo "<li class='list-group-item'>Номер телефона: {$result[13]} </li>";
			echo "<li class='list-group-item'>Місце роботи: {$result[14]} </li>";
			echo "<li class='list-group-item'>Мати: {$result[15]}</li>";
			echo "<li class='list-group-item'>Номер телефона: {$result[16]} </li>";
			echo "<li class='list-group-item'>Місце роботи: {$result[17]} </li>";
			echo "<li class='list-group-item'>Адреса батьків: {$result[18]} </li>";
			echo "</ul>";
		}else{
		echo "<a href='parents.php?student={$result[1]}' class='btn btn-primary'>Додати інформацію про батьків</a>";
		}
		?>
		<a <?php echo "href='students.php?student={$result[0]}'"; ?> class='btn btn-primary'>Відредагувати</a>

	</ul>
		

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>