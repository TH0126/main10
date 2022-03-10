<?php

// var_dump($_POST);
// exit();

session_start();
include('functions.php');


// データ受け取り
$email = $_POST["email"];
$password = $_POST["password"];

// DB接続
$pdo = connect_to_db();

$sql = "SELECT * FROM user_master WHERE email=:email AND password=:password AND del_f='0'";

// SQL実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

// ユーザ有無で条件分岐
$val = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$val) {
    echo "<p>ログイン情報に誤りがあります</p>";
    echo "<a href=todo_login.php>ログイン</a>";
    exit();
} else {
    $_SESSION = array();
    $_SESSION['session_id'] = session_id();
    $_SESSION['power'] = $val['power'];
    $_SESSION['user_id'] = $val['user_id'];
    header("Location:index.php");
    exit();
}
