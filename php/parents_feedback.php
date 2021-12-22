<?php

require_once "db.php";
session_start();

if (isset($_POST['add'])) {
	mysqli_query($link,"INSERT INTO parents_talk VALUES(NULL, '{$_POST['date']}', '{$_POST['prom']}', {$_GET['id']}, {$_GET['semester']})");
}
if (isset($_POST['change'])) {
	mysqli_query($link,"UPDATE parents_talk SET parents_talk_date='{$_POST['date']}', description='{$_POST['prom']}' WHERE parents_talk_id={$_POST['change']}");
}
if (isset($_POST['delete'])) {
	mysqli_query($link,"DELETE FROM parents_talk WHERE parents_talk_id={$_GET['talk']}");
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
				<th scope="col">Кого відвідано, кому написано листа і короткий зміст питання</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
				<?php 
				$get_work = mysqli_query($link, "SELECT * FROM parents_talk WHERE group_id={$_GET['id']} AND semester={$_GET['semester']}");

				while ($w = mysqli_fetch_array($get_work)){
					echo "<tr>";
					echo "<th>{$w[0]}</th>";
					echo "<th>{$w[1]}</th>";
					echo "<th>{$w[2]}</th>";
					echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&talk={$w[0]}'>";
					echo "<th><button name='edit' type='submit' value='$w[0] 'class='btn btn-primary'>Відредагувати</button></th>";
					echo "<th><button name='delete' type='submit' value='$w[0] 'class='btn btn-primary'>Видалити</button></th>";
					echo "</form>";
					echo "</tr>";
				}
				?>
		</tbody>
	</table>

	<?php if (isset($_GET['talk'])) {
		$get_promotion = mysqli_query($link, "SELECT * FROM parents_talk WHERE parents_talk_id={$_GET['talk']}");
		$gp = mysqli_fetch_row($get_promotion);
	} ?>
	<form method="POST" <?php if(isset($_GET['hobby'])) echo "action='?id={$_GET['id']}&semester={$_GET['semester']}&talk={$_GET['talk']}'"; else echo "action='?id={$_GET['id']}&semester={$_GET['semester']}'"; ?>>
		<div class="mb-3">
			<label class='form-label'>Дата</label>
			<input type="date" name="date" class='form-control' <?php echo "value='{$gp[1]}'"; ?>>
		</div>
		<div class="mb-3 form-floating">
			<textarea name='prom' class="form-control" id="floatingTextarea2" style="height: 200px"><?php echo "{$gp[2]}"; ?></textarea>
			<label for="floatingTextarea2">Кого відвідано, кому написано листа і короткий зміст питання</label>
		</div>
		<?php if (!isset($_POST['edit'])) {
			echo "<button type='submit' name='add' class='btn btn-primary'>Додати</button>";
		}else{
			echo "<button type='submit' name='change' value='{$_GET['talk']}' class='btn btn-primary'>Змінити</button>";
		} ?>
		
	</form>
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>