<?php


include('functions.php');

//今回分析したidを取得（将来的に。今回はダイレクトにセット）
$terms_id = 1;


$pdo = connect_to_db();

//合計出力と明細出力のデータ抽出
$sql = "SELECT terms_table.currency,terms_table.chart_time,terms_table.b_from_ymd,terms_table.b_to_ymd,terms_table.buy_sell,result_detail.ymd_start,result_detail.value_start,result_detail.ymd_end,result_detail.value_end,result_detail.p_a_l FROM result_detail INNER JOIN (SELECT * FROM terms_table WHERE del_f = 0 AND id = $terms_id) AS terms_table ON result_detail.terms_id = terms_table.id ORDER BY result_detail.id";

$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";
$check = 0;
$cnt = 0;
$v_cnt = 0;
$val = 0;

foreach ($result as $record) {

    //分析結果（TOTAL）に表示するデータ処理（上部分）
    if ($check === 0) {
        //通貨ペア
        switch ($record["currency"]) {
            case '1':
                $currency = "EUR / USD";
                break;
            case '9':
                $currency = "GBP / AUD";
                break;
        }
        //時間足
        switch ($record["chart_time"]) {
            case '1':
                $cha_time = "1分足";
            case '4':
                $cha_time = "1時間足";
        }

        //分析期間
        $period = substr($record["b_from_ymd"], 0, 4) . "/" . substr($record["b_from_ymd"], 4, 2) . "/" . substr($record["b_from_ymd"], 6, 2) . " ～ " . substr($record["b_to_ymd"], 0, 4) . "/" . substr($record["b_to_ymd"], 4, 2) . "/" . substr($record["b_to_ymd"], 6, 2);

        //BUY&SELL
        if ($record["chart_time"]) {
            $B_S = "BUY（買い）";
        } else if ($record["buy_sell"] === '1') {
            $B_S = "SELL（売り）";
        }

        $check = 1;
    }

    //分析結果（TOTAL）に表示するデータ処理（下部分）
    //取引回数
    $cnt++;
    //勝率
    if ($record["p_a_l"] > 0) {
        $v_cnt++;
    }
    //損益
    $val += $record["p_a_l"];

    //分析結果（明細）に表示するデータ処理
    $s_time = substr(date('Y/m/d H:i:s', $record["ymd_start"]), 0, 16);
    $e_time = substr(date('Y/m/d H:i:s', $record["ymd_end"]), 0, 16);
    $pl = strval(doubleval($record["p_a_l"]));

    $output .= "
    <tr class='retu'>
        <td>{$s_time}</td>
        <td>{$record["value_start"]}</td>
        <td>{$e_time}</td>
        <td>{$record["value_end"]}</td>
        <td>{$pl}</td>
    </tr>
  ";
}

$per = $v_cnt / $cnt * 100;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>分析結果（データ）</title>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style2.css">
</head>

<body>
    <div class="container-fluid main">
        <ul class="nav nav-tabs nav-pills">
            <li class="nav-item">
                <a href="index.php" class="nav-link">条件入力</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active">分析結果（データ）</a>
            </li>
            <li class="nav-item">
                <a href="index3.php" class="nav-link">分析結果（チャート）</a>
            </li>
            <li class="nav-item">
                <a href="index4.php" class="nav-link">結果履歴</a>
            </li>
            <li class="nav-item">
                <a href="index5.php" class="nav-link">結果履歴（データ）</a>
            </li>
            <li class="nav-item">
                <a href="index6.php" class="nav-link">結果履歴（チャート）</a>
            </li>
        </ul>
        <div class="dark">
            <div>
                <p class="total_p">＜分析結果（合計）＞</p>
                <ul class="total_ul">
                    <li class="total_col">
                        <p>通貨ペア：</p>
                        <p>時間足：</p>
                        <p>分析期間：</p>
                        <p>BUY / SELL：</p>
                        <p>取引回数：</p>
                        <p>勝率：</p>
                        <p>損益：</p>
                        <p>分析条件：</p>
                    </li>
                    <li class="total_de">
                        <p><?= $currency ?></p>
                        <p><?= $cha_time ?></p>
                        <p><?= $period ?></p>
                        <p><?= $B_S ?></p>
                        <p><?= $cnt ?> 回</p>
                        <p><?= $per ?> %</p>
                        <p><?= $val ?> pips</p>
                        <p>あああああああああ</p>
                    </li>
                </ul>
            </div>
            <div class="detail">
                <p class="detail_p">＜分析結果（明細）＞</p>
                <table class="col-10 offset-3 table table-sm table-bordered">
                    <thead class="header">
                        <tr>
                            <th scope="col" colspan="2">新規</th>
                            <th scope="col" colspan="2">決済</th>
                            <th scope="col" rowspan="2" class="row_ver">損益（pips）</th>
                        </tr>
                        <tr>
                            <th scope="col">日時</th>
                            <th scope="col">約定価格</th>
                            <th scope="col">日時</th>
                            <th scope="col">約定価格</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $output ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>
</body>

</html>