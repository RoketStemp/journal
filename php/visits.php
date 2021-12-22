<?php

require_once "db.php";
session_start();

if (isset($_POST['add'])) {
    mysqli_query($link,"INSERT INTO teacher_visit VALUES(NULL, '{$_POST['date']}', '".explode(",",$_POST['teacher'])[0]."', '".explode(",",$_POST['teacher'])[1]."', '{$_POST['prom']}',{$_GET['id']}, {$_GET['semester']})") or die (mysqli_error($link));
}
if (isset($_POST['change'])) {
    mysqli_query($link,"UPDATE teacher_visit SET teacher_visit_date='{$_POST['date']}', teacher_visit_desc='{$_POST['prom']}', teacher_id='".explode(",",$_POST['teacher'])[0]."', subject_id='".explode(",",$_POST['teacher'])[1]."' WHERE teacher_visit_id={$_POST['change']}");
}
if (isset($_POST['delete'])) {
    mysqli_query($link,"DELETE FROM teacher_visit WHERE teacher_visit_id={$_GET['visit']}");
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
                <th scope="col">Дисципліна і прізвище викладача</th>
                <th scope="col">Висновки та зауваження</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
                <?php 
                $get_work = mysqli_query($link, "SELECT teacher_visit.teacher_visit_id, teacher_visit.teacher_visit_date, teachers.teacher_full_name, subjects.subject_name, teacher_visit.teacher_visit_desc FROM teacher_visit INNER JOIN teachers ON teacher_visit.teacher_id=teachers.teacher_id INNER JOIN subjects ON teacher_visit.subject_id=subjects.subject_id WHERE teacher_visit.group_id={$_GET['id']} AND teacher_visit.semester={$_GET['semester']} ORDER BY teacher_visit.teacher_visit_id DESC");

                while ($w = mysqli_fetch_array($get_work)){
                    echo "<tr>";
                    echo "<th>{$w[0]}</th>";
                    echo "<th>{$w[1]}</th>";
                    echo "<th>{$w[2]} - {$w[3]}</th>";
                    echo "<th>{$w[4]}</th>";
                    echo "<form method='POST' action='?id={$_GET['id']}&semester={$_GET['semester']}&visit={$w[0]}'>";
                    echo "<th><button name='edit' type='submit' value='$w[0] 'class='btn btn-primary'>Відредагувати</button></th>";
                    echo "<th><button name='delete' type='submit' value='$w[0] 'class='btn btn-primary'>Видалити</button></th>";
                    echo "</form>";
                    echo "</tr>";
                }
                ?>
        </tbody>
    </table>

    <?php if (isset($_GET['visit'])) {
        $get_promotion = mysqli_query($link, "SELECT * FROM teacher_visit WHERE teacher_visit_id={$_GET['visit']}");
        $gp = mysqli_fetch_row($get_promotion);
    } ?>
    <form method="POST" <?php if(isset($_GET['visit'])) echo "action='?id={$_GET['id']}&semester={$_GET['semester']}&visit={$_GET['visit']}'"; else echo "action='?id={$_GET['id']}&semester={$_GET['semester']}'"; ?>>
        <div class="mb-3">
            <label class='form-label'>Дата</label>
            <input type="date" name="date" class='form-control' <?php echo "value='{$gp[1]}'"; ?>>
        </div>
        <div class="mb-3">
            <label for='stud' class='form-label'>Викладач</label>
            <select name='teacher' id='stud' class='form-select'>
                <?php 
                $get_teachers = mysqli_query($link, "SELECT group_subjects.teacher_id, group_subjects.subject_id, teachers.teacher_full_name, subjects.subject_name FROM group_subjects INNER JOIN teachers ON group_subjects.teacher_id=teachers.teacher_id INNER JOIN subjects ON group_subjects.subject_id=subjects.subject_id WHERE group_subjects.group_id={$_GET['id']} AND group_subjects.semester={$_GET['semester']}");
                while ($teach = mysqli_fetch_array($get_teachers)) {
                    echo "<option value='{$teach[0]},{$teach[0]}'>{$teach[2]} - {$teach[3]}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3 form-floating">
            <textarea name='prom' class="form-control" id="floatingTextarea2" style="height: 200px"><?php echo "{$gp[4]}"; ?></textarea>
            <label for="floatingTextarea2">Висновки та зауваження</label>
        </div>
        <?php if (!isset($_POST['edit'])) {
            echo "<button type='submit' name='add' class='btn btn-primary'>Додати</button>";
        }else{
            echo "<button type='submit' name='change' value='{$_GET['visit']}' class='btn btn-primary'>Змінити</button>";
        } ?>
        
    </form>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>