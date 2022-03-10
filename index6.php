<?php

// var_dump($_POST);
// exit();

include('functions.php');

// 入力項目のチェック
if (
    !isset($_POST["chart_id"]) || $_POST["chart_id"] === "" ||
    !isset($_POST["chart_cu"]) || $_POST["chart_cu"] === ""
) {
    header('Location:index4.php');
    exit();
}
$id = intval($_POST['chart_id']);
$currency = $_POST['chart_cu'];


$pdo = connect_to_db();


//ローソク足などの取得とJSに渡す処理
//表示する通貨ペアを指定
if ($currency === "1") {
} else if ($currency === "2") {
} else if ($currency === "9") {
    $sql = "SELECT * FROM gbpaud60 ORDER BY time";
}

$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output = array();
$ma20_output = array();
$mtf1d_output = array();

foreach ($result as $record) {

    array_push($output, array(
        "time" => $record["time"] + 32400,
        "open" => $record["open"],
        "high" => $record["high"],
        "low" => $record["low"],
        "close" => $record["close"]
    ));

    array_push($ma20_output, array(
        "time" => $record["time"] + 32400,
        "value" => $record["ma"]
    ));

    array_push($mtf1d_output, array(
        "time" => $record["time"] + 32400,
        "value" => $record["mtf_ma1"]
    ));
}
$json_output = json_encode($output);
$json_ma20 = json_encode($ma20_output);
$json_mtf1d = json_encode($mtf1d_output);


//マーク情報の取得とJSに渡す
//将来的に分析期間などを取得するため、入力条件データを取得
$sql = "SELECT result_markers.* FROM result_markers INNER JOIN (SELECT * FROM terms_table WHERE del_f = 0 AND id = $id) AS terms_table ON result_markers.terms_id = terms_table.id
 ORDER BY result_markers.id";

$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$bs_markers = array();
$be_markers = array();
$ss_markers = array();
$se_markers = array();

foreach ($result as $m_record) {
    if ($m_record["buy_sell"] === '1') {
        //BUYの場合
        array_push($bs_markers, $m_record["start_no"]);
        array_push($be_markers, $m_record["end_no"]);
    } else if ($m_record["buy_sell"] === '2') {
        //SELLの場合
        array_push($ss_markers, $m_record["start_no"]);
        array_push($se_markers, $m_record["end_no"]);
    }
}
$json_bs = json_encode($bs_markers);
$json_be = json_encode($be_markers);
$json_ss = json_encode($ss_markers);
$json_se = json_encode($se_markers);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャート</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style3.css">
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
                <a href="index4.php" class="nav-link">結果履歴</a>
            </li>
            <li class="nav-item">
                <a href="index5.php" class="nav-link">結果履歴（データ）</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active">結果履歴（チャート）</a>
            </li>
        </ul>
        <div class="main dark">
            <div id="chart"></div>
        </div>
    </div>

    <script>
        let chart = LightweightCharts.createChart($("#chart").get(0), {
            width: 1000,
            height: 600,
            layout: {
                backgroundColor: '#fff',
                textColor: 'rgba(0, 0, 0, 0.77)',
            },
            grid: {
                vertLines: {
                    color: 'rgba(197, 203, 206, 0)',
                },
                horzLines: {
                    color: 'rgba(197, 203, 206, 0)',
                },
            },
            crosshair: {
                mode: LightweightCharts.CrosshairMode.Normal,
            },
            rightPriceScale: {
                borderColor: 'rgba(197, 203, 206, 0.8)',
            },
            timeScale: {
                borderColor: 'rgba(197, 203, 206, 0.8)',
                timeVisible: true, //5分足とか表示したい人はtrueにしてください
                secondsVisible: false, //秒足を使いたい人はtrueに
            },
        });

        //ローソク足に関する設定
        var candleSeries = chart.addCandlestickSeries({
            upColor: '#38b48b',
            downColor: '#d9333f',
            borderDownColor: '#d9333f',
            borderUpColor: '#38b48b',
            wickDownColor: '#d9333f',
            wickUpColor: '#38b48b',
        });

        //チャートの描画はOHLCとタイムスタンプ(秒単位)を指定して行います
        //OHLC+Tのデータは連想配列の形式で指定します
        //例: {time: 1534204800, open: 6035, high: 6213, low: 5968, close: 6193}
        //githubの例だと時間の指定が'2019-04-11'となっていますが、タイムスタンプの方が便利かと思います
        const candleData = <?= $json_output ?>;
        candleSeries.setData(candleData);

        candleSeries.applyOptions({
            priceFormat: {
                type: 'price',
                precision: 5,
                minMove: 0.00001,
            },
        });

        const sma20Line = chart.addLineSeries({
            color: 'rgb(255, 192, 203)',
            lineWidth: 2,
        });
        const ma20_Data = <?= $json_ma20 ?>;
        //20maを表示
        sma20Line.setData(ma20_Data);
        sma20Line.applyOptions({
            priceFormat: {
                type: 'price',
                precision: 5,
                minMove: 0.00001,
            },
        });

        const mtf1dLine = chart.addLineSeries({
            color: 'rgba(255, 0, 0, 0.66)',
            lineWidth: 2,
        });
        const mtf1d_Data = <?= $json_mtf1d ?>;
        //mtf1dを表示
        mtf1dLine.setData(mtf1d_Data);
        mtf1dLine.applyOptions({
            priceFormat: {
                type: 'price',
                precision: 5,
                minMove: 0.00001,
            },
        });


        // $("#chart").append('<div class = "sma-legend" id="d_ma20"></div>');
        $("#chart").append('<div class = "sma-legend"></div>');

        function setLegendText(pricevalue, kinds) {
            let val = 'n/a';
            if (pricevalue !== undefined) {
                val = pricevalue;
            }
            if (kinds === "ma20") {
                $("#d_ma20").html('MA20 <span style="color:rgb(255, 192, 203)">' + val + '</span>');
            } else if (kinds === "mtf1d") {
                $("#d_mtf1d").html('MTF MA 1D <span style="color:rgba(255, 0, 0, 0.66)">' + val + '</span>');
            }
        }

        $(".sma-legend").append('<div id="d_ma20"></div>');
        setLegendText(ma20_Data[ma20_Data.length - 1].value, "ma20");

        $(".sma-legend").append('<div id="d_mtf1d"></div>');
        setLegendText(mtf1d_Data[mtf1d_Data.length - 1].value, "mtf1d");

        chart.subscribeCrosshairMove((param) => {
            setLegendText(param.seriesPrices.get(sma20Line), "ma20");
            setLegendText(param.seriesPrices.get(mtf1dLine), "mtf1d");
        });
        //BUYスタートかSELLスタートかどちらかしかない予定
        //マークがつく場所を指定（SELLの場合）
        const markers_sell_start = <?= $json_ss ?>;
        const markers_buy_end = <?= $json_se ?>;
        const markers_buy_start = <?= $json_bs ?>;
        const markers_sell_end = <?= $json_be ?>;

        let dataForSellStart = [];
        let dataForBuyEnd = [];
        let dataForBuyStart = [];
        let dataForSellEnd = [];

        if (markers_sell_start.length > 0) {
            for (let i = 0; i < markers_sell_start.length; i++) {
                dataForSellStart.push(candleData[candleData.length - markers_sell_start[i]]);
                dataForBuyEnd.push(candleData[candleData.length - markers_buy_end[i]]);
            }
        } else if (markers_buy_start.length > 0) {
            for (let j = 0; j < markers_buy_start.length; j++) {
                dataForBuyStart.push(candleData[candleData.length - markers_buy_start[j]]);
                dataForSellEnd.push(candleData[candleData.length - markers_sell_end[j]]);
            }
        }

        // const dataForSellStart = [candleData[candleData.length - 60], candleData[candleData.length - 44]];
        // const dataForBuyEnd = [candleData[candleData.length - 50], candleData[candleData.length - 31]];

        //マークがつく場所を指定（BUYの場合）
        // const dataForBuyStart = [candleData[candleData.length - 20]];
        // const dataForSellEnd = [candleData[candleData.length - 15]];

        let markers = [];

        if (dataForSellStart.length > 0) {
            for (let i = 0; i < dataForSellStart.length; i++) {
                if (i === 0) {
                    markers.push({
                        time: dataForSellStart[i].time,
                        position: 'aboveBar',
                        color: '#e91e63',
                        shape: 'arrowDown',
                        text: 'Sell@' + (i + 1) + ' Start'
                    });
                    markers.push({
                        time: dataForBuyEnd[i].time,
                        position: 'belowBar',
                        color: '#2196F3',
                        shape: 'arrowUp',
                        text: 'Buy@' + (i + 1) + ' End'
                    });
                } else {
                    markers.push({
                        time: dataForSellStart[i].time,
                        position: 'aboveBar',
                        color: '#e91e63',
                        shape: 'arrowDown',
                        text: 'Sell@' + (i + 1) + ' Start'
                    });
                    markers.push({
                        time: dataForBuyEnd[i].time,
                        position: 'belowBar',
                        color: '#2196F3',
                        shape: 'arrowUp',
                        text: 'Buy@' + (i + 1) + ' End'
                    });
                }
            }
            candleSeries.setMarkers(markers);
        } else if (dataForBuyStart.length > 0) {
            for (let j = 0; j < dataForBuyStart.length; j++) {
                if (j === 0) {
                    markers.push({
                        time: dataForBuyStart[j].time,
                        position: 'belowBar',
                        color: '#2196F3',
                        shape: 'arrowUp',
                        text: 'Buy@' + (j + 1) + ' Start'
                    });
                    markers.push({
                        time: dataForSellEnd[j].time,
                        position: 'aboveBar',
                        color: '#e91e63',
                        shape: 'arrowDown',
                        text: 'Sell@' + (j + 1) + ' End'
                    });
                } else {
                    markers.push({
                        time: dataForBuyStart[j].time,
                        position: 'belowBar',
                        color: '#2196F3',
                        shape: 'arrowUp',
                        text: 'Buy@' + (j + 1) + ' Start'
                    });
                    markers.push({
                        time: dataForSellEnd[j].time,
                        position: 'aboveBar',
                        color: '#e91e63',
                        shape: 'arrowDown',
                        text: 'Sell@' + (j + 1) + ' End'
                    });
                }
            }
            candleSeries.setMarkers(markers);
        } else {
            //結果がない場合
        }
    </script>

</body>

</html>