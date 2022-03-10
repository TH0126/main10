<?php

include("functions.php");

// 入力項目のチェック
if (
    !isset($_POST['delete']) || $_POST['delete'] == ''
) {
    header('Location:index4.php');
    exit();
}
$del = [$_POST['delete']];

// DB接続
$pdo = connect_to_db();

// SQL実行
$sql = "UPDATE terms_table SET del_f='1', updated_at=now() WHERE id IN (" . implode(",", $del) . ")";

$stmt = $pdo->prepare($sql);
// $stmt->bindValue(':del', $del, PDO::PARAM_STR);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

header('Location:index4.php');
exit();
