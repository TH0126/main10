<?php

$output = "";
session_start();

// var_dump($_POST);
// exit();
if (!empty($_POST)) {
    //ブラウザの更新ボタンを押したときに前回のPOST情報で処理が走らず画面が更新されるようにトークン比較
    if ($_POST["chkno"] !== $_SESSION["chkno"]) {
        header("Location:login.php");
        exit();
    }

    if (
        !isset($_POST["email"]) || $_POST["email"] === "" ||
        !isset($_POST["password"]) || $_POST["password"] === ""
    ) {
        $output = "Valid email required";
    } else {
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
        }

        // ユーザ有無で条件分岐
        $val = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$val) {
            $output = "Valid email or password required";
        } else {
            $_SESSION = array();
            $_SESSION['session_id'] = session_id();
            $_SESSION['power'] = $val['power'];
            $_SESSION['user_id'] = $val['user_id'];
            header("Location:index.php");
            exit();
        }
    }
}
//2重処理にならないようにトークン設定
$_SESSION["chkno"] = $chkno = strval(mt_rand());

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="body"></div>
    <div class="grad"></div>
    <div class="header">
        <div>Trading<span>Analysis</span></div>
    </div>
    <br>
    <form action="" method="POST">
        <div class="login">
            <input type="text" placeholder="e-mail" name="email"><br>
            <input type="password" placeholder="password" name="password"><br>
            <p class="red"><?= $output ?></p>
            <div>
                <a id="p_color" href="">Forgot your password?</a>
            </div>
            <input id="btn_in" type="submit" value="Login">
            <div class="center">
                <span id="b_color">or</span>
                <button type="button" onclick="clickTextChange()" id="c_color">Create Account</button>
            </div>
        </div>
        <!-- 2重処理にならないようにトークン設定 -->
        <input name="chkno" type="hidden" value="<?= $chkno ?>">
    </form>

    <script>
        $(document).ready(function() {});

        //HTMLの読み込みが終わった後、処理開始
        $(window).on('load', function() {


        });

        function clickTextChange() {
            if ($("#c_color").text() === "Create Account") {
                $("#c_color").text("Login to your account")
                $("#btn_in").val("Create Account")
            } else if ($("#c_color").text() === "Login to your account") {
                $("#c_color").text("Create Account")
                $("#btn_in").val("Login")
            }
        };
    </script>

</body>

</html>