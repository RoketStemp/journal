<?php

require_once "db.php";
session_start();

$get_user = mysqli_query($link, "SELECT teacher_id FROM teachers WHERE teacher_login='{$_SESSION['username']}'");
$user = mysqli_fetch_row($get_user);

if (isset($_POST['notations'])) {
    mysqli_query($link, "DELETE FROM curator_notations WHERE teacher_id={$user[0]} AND semester={$_GET['semester']}");
    mysqli_query($link, "INSERT INTO curator_notations VALUES(NULL, '{$_POST['notations']}', '{$user[0]}', '{$_GET['semester']}')");
}

$get_notations = mysqli_query($link, "SELECT curator_notations_desc FROM curator_notations WHERE teacher_id='{$user[0]}' AND semester={$_GET['semester']}");
$notations = mysqli_fetch_row($get_notations);
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

   <form method="POST" <?php echo "action='?semester={$_GET['semester']}'"; ?>>
        <div class="mb-3 form-floating">
            <textarea name='notations' class="form-control" id="floatingTextarea2" style="height: 600px"><?php echo "{$notations[0]}"; ?></textarea>
            <label for="floatingTextarea2"style='font-size: 20px;'>Нотатки</label>
        </div>
        <button type='submit' name='change' class='btn btn-primary'>Відредагувати</button>
   </form>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>