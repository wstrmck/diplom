<?php
include("db_connect.php");
$login = $_POST['login'];
$password = $_POST['password'];
$md5_password = md5($password);
$sql = "SELECT * FROM users WHERE login = '$login' AND password = '$md5_password'";
$query = mysqli_query($db, $sql);

if (mysqli_num_rows($query) != 0) {
    $_SESSION['user'] = ['nick' => $login];
    header("Location: index.php");
} else {
    echo "Ошибка: Данный логин или пароль неправильны.";
}
