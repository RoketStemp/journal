<?php

require_once "db.php";
session_start();

if ($_SESSION['admin']!=0) {
	$id = mysqli_fetch_row(mysqli_query($link, "SELECT group_curator FROM `group` WHERE group_id='{$_GET['id']}'"));
}

function get_semester_student_info($link, $semester, $id){
		if (isset($semester[0])) {
			$get_current_group_students = mysqli_query($link, "SELECT * FROM `student` WHERE (`student_group_id`='".$_GET['id']."') AND (`semester`='{$semester[0]}') ORDER BY student_full_name ASC") or die(mysqli_error($link));
			$counter = 1;
			while ($row = mysqli_fetch_array($get_current_group_students)) {
				echo "<tr>";
				echo "<th scope='row'>{$counter}</th>";
				echo "<th>{$row['student_full_name']}</th>";
				echo "<th>{$row['student_phone']}</th>";
				echo "<th>{$row['student_address']}</th>";
				if ($_SESSION['admin']==0 || $_SESSION['admin']==$id[0]) {
					echo "<th><a class='btn btn-primary' href='student_grade.php?id={$row['student_id']}&semester={$semester[0]}&group={$_GET['id']}'>Оцінки</a></th>";
					echo "<th><a class='btn btn-primary' href='student.php?id={$row['student_id']}&semester={$semester[0]}'>Детальніше</a></th>";
				}
				echo "</tr>";
				$counter++;
			}
		}
	}

$get_group_query = mysqli_query($link, "SELECT * FROM `group` WHERE `group_id`='".$_GET['id']."'");
$result = mysqli_fetch_row($get_group_query);
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
	<h1><?php echo "{$_GET['id']}"; ?></h1>
<?php 
	if ($_SESSION['admin']==0 || $_SESSION['admin']==$id[0]) {
	echo "<a href='students.php?id={$_GET['id']}' class='btn btn-primary'>Зарегистрировать студента</a>"; 
	echo "<a href='subject.php?id={$_GET['id']}&semester={$_POST['semester'][0]}' class='btn btn-primary'>Выбрать предмет</a>"; 
	echo "<a href='proms.php?id={$_GET['id']}&semester={$_POST['semester'][0]}' class='btn btn-primary'>Заохочення та догани студентів</a>"; 
	echo "<a href='education.php?id={$_GET['id']}&semester={$_POST['semester'][0]}' class='btn btn-primary'>Виховна робота</a>"; 
	echo "<a href='hobby.php?id={$_GET['id']}&semester={$_POST['semester'][0]}' class='btn btn-primary'>Гуртки</a>"; 
	echo "<a href='parents_feedback.php?id={$_GET['id']}&semester={$_POST['semester'][0]}' class='btn btn-primary'>Зв'язок з батьками</a>"; 
	echo "<a href='visits.php?id={$_GET['id']}&semester={$_POST['semester'][0]}' class='btn btn-primary'>Відвідування уроків викладачами</a>"; 
	echo "<a href='notations.php?semester={$_POST['semester'][0]}' class='btn btn-primary my-2'>Нотатки</a>"; 
	echo "<a class='btn btn-primary' href='skips.php?semester={$_POST['semester'][0]}&id={$_GET['id']}'>Пропуски</a>";
}
?>


	<?php echo "<form method='POST' action='?id={$_GET['id']}'>";  ?>
		<button type='submit' name='semester[]' value="1">1 семестр</button>
		<button type='submit' name='semester[]' value="2">2 семестр</button>
		<button type='submit' name='semester[]' value="3">3 семестр</button>
		<button type='submit' name='semester[]' value="4">4 семестр</button>
		<button type='submit' name='semester[]' value="5">5 семестр</button>
		<button type='submit' name='semester[]' value="6">6 семестр</button>
		<button type='submit' name='semester[]' value="7">7 семестр</button>
		<button type='submit' name='semester[]' value="8">8 семестр</button>
	</form>
	<h1 style="text-align: center;">Студенти</h1>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">№</th>
				<th scope="col">Ім'я</th>
				<th scope="col">Номер</th>
				<th scope="col">Адресс</th>
				<th scope="col"></th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
				<?php get_semester_student_info($link, $_POST['semester'], $id); ?>
		</tbody>
	</table>

	<h1 style="text-align: center;">Предмети</h1>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Назва предмету</th>
				<th scope="col">Викладач</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php 
					$get_info = mysqli_query($link, "SELECT * FROM `group_subjects` WHERE `group_id`='{$_GET['id']}' AND semester='{$_POST['semester'][0]}'");
					while ($info = mysqli_fetch_array($get_info)) {

						$get_teacher_query = mysqli_query($link, "SELECT teacher_full_name FROM teachers WHERE teacher_id={$info[2]}");
						$teacher = mysqli_fetch_row($get_teacher_query);
						$get_subject_query = mysqli_query($link, "SELECT subject_name FROM subjects WHERE subject_id={$info[1]}");
						$subject = mysqli_fetch_row($get_subject_query);
						echo "<tr>";
						echo "<th scope='row'>{$subject[0]}</th>";
						echo "<th>{$teacher[0]}</th>";
						if ($_SESSION['admin']==0 || $_SESSION['admin']==$id[0]) {
						echo "<th><a class='btn btn-primary' href='grades.php?id={$_GET['id']}&semester={$_POST['semester'][0]}&subject={$info[1]}&teacher={$info[2]}'>Оцінки</a></th>";
					}
						echo "</tr>";
					}
				?>
			</tr>
		</tbody>
	</table>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>