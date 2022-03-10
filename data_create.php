<?php

include("functions.php");
// var_dump($_POST);
// exit();
// POSTデータ確認
if (
    !isset($_POST["currency"]) || $_POST["currency"] === "" ||
    !isset($_POST["chart_time"]) || $_POST["chart_time"] === "" ||
    !isset($_POST["b_from_ymd"]) || $_POST["b_from_ymd"] === "" ||
    !isset($_POST["rikaku_val"]) || $_POST["rikaku_val"] === "" ||
    !isset($_POST["songiri_val"]) || $_POST["songiri_val"] === ""
) {
    exit("ParamError");
}

$currency = $_POST["currency"];
$chart_time = $_POST["chart_time"];
$b_from_ymd = $_POST["b_from_ymd"];
$rikaku = $_POST["rikaku_val"];
$songiri = $_POST["songiri_val"];

if (isset($_POST["trend"])) {
    $trend = $_POST["trend"];
}
if (isset($_POST["trend_ma"])) {
    $trend_ma = $_POST["trend_ma"];
}
if (isset($_POST["trend_mtf"])) {
    $trend_mtf = $_POST["trend_mtf"];
}
if (isset($_POST["nehaba"])) {
    $nehaba = $_POST["nehaba"];
}
if (isset($_POST["vola_from"])) {
    $vola_from = $_POST["vola_from"];
}
if (isset($_POST["vola_to"])) {
    $vola_to = $_POST["vola_to"];
}
if (isset($_POST["pips_from"])) {
    $pips_from = $_POST["pips_from"];
}
if (isset($_POST["pips_to"])) {
    $pips_to = $_POST["pips_to"];
}
if (isset($_POST["period"])) {
    $period = $_POST["period"];
}
if (isset($_POST["wave"])) {
    $wave = $_POST["wave"];
}
if (isset($_POST["hour_from"])) {
    $hour_from = $_POST["hour_from"];
}
if (isset($_POST["hour_to"])) {
    $hour_to = $_POST["hour_to"];
}

// DB接続
$pdo = connect_to_db();

// SQL作成&実行
$sql = "INSERT INTO terms_table (id," .
    "currency, " .
    "chart_time, " .
    "b_from_ymd, " .
    "rikaku, " .
    "songiri, " .
    "wait, " .
    "trend, " .
    "trend_ma1, " .
    "trend_mtf1, " .
    "nehaba, " .
    "vola_from, " .
    "vola_to, " .
    "pips_from, " .
    "pips_to, " .
    "period, " .
    "wave, " .
    "hour_from, " .
    "hour_to, " .
    "created_at, " .
    "updated_at, " .
    "del_f)" .
    " VALUES (NULL, " .
    ":currency, " .
    ":chart_time, " .
    ":b_from_ymd, " .
    ":rikaku, " .
    ":songiri, " .
    "'0', " .
    ":trend, " .
    ":trend_ma, " .
    ":trend_mtf, " .
    ":nehaba, " .
    ":vola_from, " .
    ":vola_to, " .
    ":pips_from, " .
    ":pips_to, " .
    ":period, " .
    ":wave, " .
    ":hour_from, " .
    ":hour_to, " .
    "now(), " .
    "now(), " .
    "'0')";

$stmt = $pdo->prepare($sql);

// バインド変数を設定
$stmt->bindValue(':currency', $currency, PDO::PARAM_STR);
$stmt->bindValue(':chart_time', $chart_time, PDO::PARAM_STR);
$stmt->bindValue(':b_from_ymd', $b_from_ymd, PDO::PARAM_STR);
$stmt->bindValue(':rikaku', $rikaku, PDO::PARAM_STR);
$stmt->bindValue(':songiri', $songiri, PDO::PARAM_STR);
if (isset($_POST["trend"])) {
    $stmt->bindValue(':trend', $trend, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':trend', "", PDO::PARAM_STR);
}
if (isset($_POST["trend_ma"])) {
    $stmt->bindValue(':trend_ma', $trend_ma, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':trend_ma', 0, PDO::PARAM_STR);
}
if (isset($_POST["trend_mtf"])) {
    $stmt->bindValue(':trend_mtf', $trend_mtf, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':trend_mtf', 0, PDO::PARAM_STR);
}
if (isset($_POST["nehaba"])) {
    $stmt->bindValue(':nehaba', $nehaba, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':nehaba', "", PDO::PARAM_STR);
}
if (isset($_POST["vola_from"])) {
    $stmt->bindValue(':vola_from', $vola_from, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':vola_from', 0, PDO::PARAM_STR);
}
if (isset($_POST["vola_to"])) {
    $stmt->bindValue(':vola_to', $vola_to, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':vola_to', 0, PDO::PARAM_STR);
}
if (isset($_POST["pips_from"])) {
    $stmt->bindValue(':pips_from', $pips_from, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':pips_from', 0, PDO::PARAM_STR);
}
if (isset($_POST["pips_to"])) {
    $stmt->bindValue(':pips_to', $pips_to, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':pips_to', 0, PDO::PARAM_STR);
}
if (isset($_POST["period"])) {
    $stmt->bindValue(':period', $period, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':period', "", PDO::PARAM_STR);
}
if (isset($_POST["wave"])) {
    $stmt->bindValue(':wave', $wave, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':wave', 0, PDO::PARAM_STR);
}
if (isset($_POST["hour_from"])) {
    $stmt->bindValue(':hour_from', $hours_from, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':hour_from', 0, PDO::PARAM_STR);
}
if (isset($_POST["hour_to"])) {
    $stmt->bindValue(':hour_to', $hour_to, PDO::PARAM_STR);
} else {
    $stmt->bindValue(':hour_to', 0, PDO::PARAM_STR);
}

// SQL実行（実行に失敗すると `sql error ...` が出力される）
try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

header("Location:index.php");
exit();
