<?php


include('functions.php');
$pdo = connect_to_db();

$sql = "SELECT * FROM terms_table WHERE del_f = '0'";

$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = "";
$cnt = "";
foreach ($result as $record) {

    $cnt = "{$record["id"]}_{$record["currency"]}";

    $output .= "
    <tr class='retu'>
        <td>
            <div class='form-check'>
                <input class='form-check-input position-static' type='checkbox' value='" . $cnt . "'>
            </div>
        </td>
        <td>{$record["id"]}</td>
        <td>{$record["currency"]}</td>
        <td>{$record["chart_time"]}</td>
        <td>{$record["b_from_ymd"]}</td>
        <td>{$record["rikaku"]}</td>
        <td>{$record["songiri"]}</td>
        <td>{$record["trend"]}</td>
        <td>{$record["trend_ma1"]}</td>
        <td>{$record["nehaba"]}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
  ";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果履歴</title>
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.0.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style4.css">
</head>

<body>
    <div class="container-fluid">
        <ul class="nav nav-tabs nav-pills">
            <li class="nav-item">
                <a href="index.php" class="nav-link">条件入力</a>
            </li>
            <li class="nav-item">
                <a href="index2.php" class="nav-link">分析結果（データ）</a>
            </li>
            <li class="nav-item">
                <a href="index3.php" class="nav-link">分析結果（チャート）</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active">結果履歴</a>
            </li>
            <li class="nav-item">
                <a href="index5.php" class="nav-link">結果履歴（データ）</a>
            </li>
            <li class="nav-item">
                <a href="index6.php" class="nav-link">結果履歴（チャート）</a>
            </li>
        </ul>
        <div class="dark">
            <div class="row offset-1">
                <p class="w_col">結果履歴一覧</p>
                <form action="index6.php" method="POST" name="set_c">
                    <input type="hidden" id="chart_id" name="chart_id" value="">
                    <input type="hidden" id="chart_cu" name="chart_cu" value="">
                    <button type="submit" class="btn formbtn" id="btn_cha">Chart</button>
                </form>
                <form action="data_delete.php" method="POST" name="set">
                    <input type="hidden" id="delete" name="delete" value="">
                    <button type="submit" class="btn formbtn" id="btn_del">Delete</button>
                </form>
            </div>
            <table class="col-10 offset-1 table table-bordered">
                <thead class="header">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">No.</th>
                        <th scope="col">通貨ペア</th>
                        <th scope="col">時間足</th>
                        <th scope="col">分析期間</th>
                        <th scope="col">利確(pips)</th>
                        <th scope="col">損切(pips)</th>
                        <th scope="col">トレンド</th>
                        <th scope="col">トレンド判断基準</th>
                        <th scope="col">トレンドの強さ</th>
                        <th scope="col">注文するタイミング</th>
                        <th scope="col">BUY/SELL</th>
                        <th scope="col">勝率</th>
                        <th scope="col">取引回数</th>
                        <th scope="col">損益(pips)</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $output ?>
                </tbody>
            </table>
        </div>
        <!-- モーダルウィンドウ群 -->
        <div class="modal-container">
            <div class="modal-body">
                <p id="message"></p>
                <div class="modal-close">OK</div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {});

        //HTMLの読み込みが終わった後、処理開始
        $(window).on('load', function() {

            $("#btn_cha").on("click", function() {
                if ($('[class="form-check-input position-static"]:checked').length > 1) {
                    $("#message").text("チャート結果を表示する場合は履歴の中から１つだけ選択してください。");
                    $(".modal-container").toggleClass("active");
                    return false;

                } else if ($('[class="form-check-input position-static"]:checked').length === 1) {
                    //1件のみなのでダイレクトにセット
                    const ind = $('[class="form-check-input position-static"]:checked').val().indexOf("_");
                    const str_id = $('[class="form-check-input position-static"]:checked').val().slice(0, ind);
                    const str_cu = $('[class="form-check-input position-static"]:checked').val().slice(ind + 1);

                    $("#chart_id").val(str_id);
                    $("#chart_cu").val(str_cu);
                }
            });

            //閉じるボタンをクリックしたらモーダルを閉じる
            $(".modal-close").on("click", function() {
                $(".modal-container").removeClass("active");
            });
            //モーダルの外側をクリックしたらモーダルを閉じる
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".modal-body").length) {
                    $(".modal-container").removeClass("active");
                }
            });

            $("#btn_del").on("click", function() {
                let arr = [];
                $('[class="form-check-input position-static"]:checked').each(function() {
                    //idだけを抽出
                    const ind = $(this).val().indexOf("_");
                    const str = $(this).val().slice(0, ind);

                    // チェックされている値を配列に格納
                    arr.push(str);
                });
                $("#delete").val(arr);
            });

        });
    </script>
</body>

</html>