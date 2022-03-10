<?php


include('functions.php');
$pdo = connect_to_db();


//ローソク足などの取得とJSに渡す処理(1時間足)
$sql = "SELECT * FROM gbpaud60 ORDER BY time";

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
$ma75_output = array();
$ma200_output = array();
$mtf1d_output = array();
$mtf1w_output = array();

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

    array_push($ma75_output, array(
        "time" => $record["time"] + 32400,
        "value" => $record["ma_1"]
    ));

    array_push($ma200_output, array(
        "time" => $record["time"] + 32400,
        "value" => $record["ma_2"]
    ));

    array_push($mtf1d_output, array(
        "time" => $record["time"] + 32400,
        "value" => $record["mtf_ma1"]
    ));

    array_push($mtf1w_output, array(
        "time" => $record["time"] + 32400,
        "value" => $record["mrf_ma"]
    ));
}
$json_output = json_encode($output);
$json_ma20 = json_encode($ma20_output);
$json_ma75 = json_encode($ma75_output);
$json_ma200 = json_encode($ma200_output);
$json_mtf1d = json_encode($mtf1d_output);
$json_mtf1w = json_encode($mtf1w_output);

//ローソク足などの取得とJSに渡す処理(1時間足)
$sql = "SELECT * FROM gbpaud240 ORDER BY time";

$stmt = $pdo->prepare($sql);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$output4 = array();
$ma20_output4 = array();
$ma75_output4 = array();
$ma200_output4 = array();
$mtf1d_output4 = array();
$mtf1w_output4 = array();

foreach ($result as $record4) {

    array_push($output4, array(
        "time" => $record4["time"] + 32400,
        "open" => $record4["open"],
        "high" => $record4["high"],
        "low" => $record4["low"],
        "close" => $record4["close"]
    ));

    array_push($ma20_output4, array(
        "time" => $record4["time"] + 32400,
        "value" => $record4["ma"]
    ));

    array_push($ma75_output4, array(
        "time" => $record4["time"] + 32400,
        "value" => $record4["ma_1"]
    ));

    array_push($ma200_output4, array(
        "time" => $record4["time"] + 32400,
        "value" => $record4["ma_2"]
    ));

    array_push($mtf1d_output4, array(
        "time" => $record4["time"] + 32400,
        "value" => $record4["mtf_ma1"]
    ));

    array_push($mtf1w_output4, array(
        "time" => $record4["time"] + 32400,
        "value" => $record4["mrf_ma"]
    ));
}
$json4_output = json_encode($output4);
$json4_ma20 = json_encode($ma20_output4);
$json4_ma75 = json_encode($ma75_output4);
$json4_ma200 = json_encode($ma200_output4);
$json4_mtf1d = json_encode($mtf1d_output4);
$json4_mtf1w = json_encode($mtf1w_output4);




//マーク情報の取得とJSに渡す
$sql = "SELECT * FROM result_markers WHERE terms_id = 1 ORDER BY id";

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
                <a href="#" class="nav-link active">分析結果（チャート）</a>
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
        <div class="main dark">
            <div id="chart"></div>
            <div id="function">
                <div class="currency">GBPAUD</div>
                <div id="min1" class="chart_time2">1分</div>
                <div id="min5" class="chart_time2">5分</div>
                <div id="min30" class="chart_time2">30分</div>
                <div id="hour1" class="chart_time act">1時間</div>
                <div id="hour4" class="chart_time">4時間</div>
                <div id="day" class="chart_time3">日</div>
                <div id="week" class="chart_time3">週</div>
                <div class="technical">
                    <img src="./img/tec.png" alt="">
                </div>

            </div>
        </div>
        <!-- モーダルウィンドウ群 -->
        <div class="modal-container">
            <div class="modal-body">
                <div class="modal-close">×</div>
                <div class="modal-content">
                    <div class="row">
                        <button type="button" id="inge" class="btn btn-outline-info part1">インジケーター</button>
                        <button type="button" id="stra" class="btn btn-outline-info part1">ストラテジー</button>
                    </div>
                    <hr>
                    <div id="inge_de">
                        <div id="s_ma20">● SMA（単純移動平均線）20期間</div>
                        <div id="s_ma75">● SMA（単純移動平均線）75期間</div>
                        <div id="s_ma200">● SMA（単純移動平均線）200期間</div>
                        <div id="s_mtf1d">● MTF MA（マルチタイムフレーム移動平均線）1日</div>
                        <div id="s_mtf1w">● MTF MA（マルチタイムフレーム移動平均線）1週</div>
                        <div id="s_pivot">● Pivot（ピボットポイント）</div>
                    </div>
                    <div id="stra_de">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let candleSeries = null;
        let sma20Line = null;
        let sma75Line = null;
        let sma200Line = null;
        let mtf1dLine = null;
        let mtf1wLine = null;

        let cha_time = "1hour";

        $(document).ready(function() {});

        $(window).on('load', function() {

            $(".technical").on("click", function() {
                $(".modal-container").toggleClass("active");
                return false;
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

            //対象のインジケーターを選択した場合にチャートに表示される
            $(document).on("click", "#s_ma20", function() {
                sma20Line.applyOptions({
                    visible: true,
                });
                $("#d_ma20").show();
            });
            $(document).on("click", "#s_ma75", function() {
                sma75Line.applyOptions({
                    visible: true,
                });
                $("#d_ma75").show();
            });
            $(document).on("click", "#s_ma200", function() {
                sma200Line.applyOptions({
                    visible: true,
                });
                $("#d_ma200").show();
            });
            $(document).on("click", "#s_mtf1d", function() {
                mtf1dLine.applyOptions({
                    visible: true,
                });
                $("#d_mtf1d").show();
            });
            $(document).on("click", "#s_mtf1w", function() {
                mtf1wLine.applyOptions({
                    visible: true,
                });
                $("#d_mtf1w").show();
            });

            //×ボタンを押したときに対象のインジケーターを非表示に
            $(document).on("click", "#re_ma20", function() {
                sma20Line.applyOptions({
                    visible: false,
                });
                $("#d_ma20").hide();
            });
            $(document).on("click", "#re_ma75", function() {
                sma75Line.applyOptions({
                    visible: false,
                });
                $("#d_ma75").hide();
            });
            $(document).on("click", "#re_ma200", function() {
                sma200Line.applyOptions({
                    visible: false,
                });
                $("#d_ma200").hide();
            });
            $(document).on("click", "#re_mtf1d", function() {
                mtf1dLine.applyOptions({
                    visible: false,
                });
                $("#d_mtf1d").hide();
            });
            $(document).on("click", "#re_mtf1w", function() {
                mtf1wLine.applyOptions({
                    visible: false,
                });
                $("#d_mtf1w").hide();
            });

            let chart = LightweightCharts.createChart($("#chart").get(0), {
                width: 1150,
                height: 620,
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
            candleSeries = chart.addCandlestickSeries({
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

            sma20Line = chart.addLineSeries({
                color: 'rgb(255, 192, 203)',
                lineWidth: 2,
            });
            let ma20_Data = <?= $json_ma20 ?>;
            //20maラインを表示
            sma20Line.setData(ma20_Data);
            sma20Line.applyOptions({
                priceFormat: {
                    type: 'price',
                    precision: 5,
                    minMove: 0.00001,
                },
            });

            sma75Line = chart.addLineSeries({
                color: 'rgb(144, 0, 231)',
                lineWidth: 2,
            });
            let ma75_Data = <?= $json_ma75 ?>;
            //75maラインを表示
            sma75Line.setData(ma75_Data);
            sma75Line.applyOptions({
                priceFormat: {
                    type: 'price',
                    precision: 5,
                    minMove: 0.00001,
                },
            });

            sma200Line = chart.addLineSeries({
                color: 'rgb(255, 0, 166)',
                lineWidth: 2,
            });
            let ma200_Data = <?= $json_ma200 ?>;
            //200maラインを表示
            sma200Line.setData(ma200_Data);
            sma200Line.applyOptions({
                priceFormat: {
                    type: 'price',
                    precision: 5,
                    minMove: 0.00001,
                },
            });

            mtf1dLine = chart.addLineSeries({
                color: 'rgba(255, 0, 0, 0.66)',
                lineWidth: 2,
            });
            let mtf1d_Data = <?= $json_mtf1d ?>;
            //mtf1dラインを表示
            mtf1dLine.setData(mtf1d_Data);
            mtf1dLine.applyOptions({
                priceFormat: {
                    type: 'price',
                    precision: 5,
                    minMove: 0.00001,
                },
            });

            mtf1wLine = chart.addLineSeries({
                color: '#95949a',
                lineWidth: 2,
            });
            let mtf1w_Data = <?= $json_mtf1w ?>;
            //mtf1wラインを表示
            mtf1wLine.setData(mtf1w_Data);
            mtf1wLine.applyOptions({
                priceFormat: {
                    type: 'price',
                    precision: 5,
                    minMove: 0.00001,
                },
            });


            //左上の各種ラインの値を表示
            // $("#chart").append('<div class = "sma-legend" id="d_ma20"></div>');
            $("#chart").append('<div class = "sma-legend"></div>');

            function setLegendText(pricevalue, kinds) {
                let val = 'n/a';
                if (pricevalue !== undefined) {
                    val = pricevalue;
                }
                if (kinds === "ma20") {
                    $("#d_ma20").html('MA20 <span style="color:rgb(255, 192, 203)">' + val + '　</span><div id="re_ma20"><img src="./img/remove.png"></div>');
                } else if (kinds === "ma75") {
                    $("#d_ma75").html('MA75 <span style="color:rgb(144, 0, 231)">' + val + '　</span><div id="re_ma75"><img src="./img/remove.png"></div>');
                } else if (kinds === "ma200") {
                    $("#d_ma200").html('MA200 <span style="color:rgb(255, 0, 166)">' + val + '　</span><div id="re_ma200"><img src="./img/remove.png"></div>');
                } else if (kinds === "mtf1d") {
                    $("#d_mtf1d").html('MTF MA 1D <span style="color:rgba(255, 0, 0, 0.66)">' + val + '　</span><div id="re_mtf1d"><img src="./img/remove.png"></div>');
                } else if (kinds === "mtf1w") {
                    $("#d_mtf1w").html('MTF MA 1W <span style="color:#95949a">' + val + '　</span><div id="re_mtf1w"><img src="./img/remove.png"></div>');
                }
            }

            $(".sma-legend").append('<div id="d_ma20"></div>');
            setLegendText(ma20_Data[ma20_Data.length - 1].value, "ma20");

            $(".sma-legend").append('<div id="d_ma75"></div>');
            setLegendText(ma75_Data[ma75_Data.length - 1].value, "ma75");

            $(".sma-legend").append('<div id="d_ma200"></div>');
            setLegendText(ma200_Data[ma200_Data.length - 1].value, "ma200");

            $(".sma-legend").append('<div id="d_mtf1d"></div>');
            setLegendText(mtf1d_Data[mtf1d_Data.length - 1].value, "mtf1d");

            $(".sma-legend").append('<div id="d_mtf1w"></div>');
            setLegendText(mtf1w_Data[mtf1w_Data.length - 1].value, "mtf1w");

            chart.subscribeCrosshairMove((param) => {
                setLegendText(param.seriesPrices.get(sma20Line), "ma20");
                setLegendText(param.seriesPrices.get(sma75Line), "ma75");
                setLegendText(param.seriesPrices.get(sma200Line), "ma200");
                setLegendText(param.seriesPrices.get(mtf1dLine), "mtf1d");
                setLegendText(param.seriesPrices.get(mtf1wLine), "mtf1w");
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

            //時間足をクリックしたときの処理（時間足に合わせてチャートが変わる）
            //1時間足を選択した場合
            $(document).on("click", "#hour1", function() {

                //時間足のボタンを青色表示切替え
                switch (cha_time) {
                    case "1min":
                        $("#min1").toggleClass("act")
                        break;
                    case "5min":
                        $("#min5").toggleClass("act")
                        break;
                    case "30min":
                        $("#min30").toggleClass("act")
                        break;
                    case "1hour":
                        $("#hour1").toggleClass("act")
                        break;
                    case "4hour":
                        $("#hour4").toggleClass("act")
                        break;
                    case "day":
                        $("#day").toggleClass("act")
                        break;
                    case "week":
                        $("#week").toggleClass("act")
                        break;
                }
                $("#hour1").toggleClass("act")
                cha_time = "1hour";

                //ローソク足をリセット
                chart.removeSeries(candleSeries);
                candleSeries = null;

                //各インジケーターリセット
                chart.removeSeries(sma20Line);
                chart.removeSeries(sma75Line);
                chart.removeSeries(sma200Line);
                chart.removeSeries(mtf1dLine);
                chart.removeSeries(mtf1wLine);
                // sma20Line = null;

                //ローソク足に関する設定
                candleSeries = chart.addCandlestickSeries({
                    upColor: '#38b48b',
                    downColor: '#d9333f',
                    borderDownColor: '#d9333f',
                    borderUpColor: '#38b48b',
                    wickDownColor: '#d9333f',
                    wickUpColor: '#38b48b',
                });


                //ローソク足を表示
                candleSeries.setData(candleData);

                candleSeries.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                sma20Line = chart.addLineSeries({
                    color: 'rgb(255, 192, 203)',
                    lineWidth: 2,
                });
                ma20_Data = <?= $json_ma20 ?>;
                //20maを表示
                sma20Line.setData(ma20_Data);
                sma20Line.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                sma75Line = chart.addLineSeries({
                    color: 'rgb(144, 0, 231)',
                    lineWidth: 2,
                });
                ma75_Data = <?= $json_ma75 ?>;
                //75maを表示
                sma75Line.setData(ma75_Data);
                sma75Line.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                sma200Line = chart.addLineSeries({
                    color: 'rgb(255, 0, 166)',
                    lineWidth: 2,
                });
                ma200_Data = <?= $json_ma200 ?>;
                //200maを表示
                sma200Line.setData(ma200_Data);
                sma200Line.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                mtf1dLine = chart.addLineSeries({
                    color: 'rgba(255, 0, 0, 0.66)',
                    lineWidth: 2,
                });
                mtf1d_Data = <?= $json_mtf1d ?>;
                //mtf1dを表示
                mtf1dLine.setData(mtf1d_Data);
                mtf1dLine.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                mtf1wLine = chart.addLineSeries({
                    color: '#95949a',
                    lineWidth: 2,
                });
                mtf1w_Data = <?= $json_mtf1w ?>;
                //mtf1wを表示
                mtf1wLine.setData(mtf1w_Data);
                mtf1wLine.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                //左上の機能を作り直し
                $(".sma-legend").empty();

                $(".sma-legend").append('<div id="d_ma20"></div>');
                setLegendText(ma20_Data[ma20_Data.length - 1].value, "ma20");

                $(".sma-legend").append('<div id="d_ma75"></div>');
                setLegendText(ma75_Data[ma75_Data.length - 1].value, "ma75");

                $(".sma-legend").append('<div id="d_ma200"></div>');
                setLegendText(ma200_Data[ma200_Data.length - 1].value, "ma200");

                $(".sma-legend").append('<div id="d_mtf1d"></div>');
                setLegendText(mtf1d_Data[mtf1d_Data.length - 1].value, "mtf1d");

                $(".sma-legend").append('<div id="d_mtf1w"></div>');
                setLegendText(mtf1w_Data[mtf1w_Data.length - 1].value, "mtf1w");

            });


            //4時間足を選択した場合
            $(document).on("click", "#hour4", function() {

                //時間足のボタンを青色表示切替え
                switch (cha_time) {
                    case "1min":
                        $("#min1").toggleClass("act")
                        break;
                    case "5min":
                        $("#min5").toggleClass("act")
                        break;
                    case "30min":
                        $("#min30").toggleClass("act")
                        break;
                    case "1hour":
                        $("#hour1").toggleClass("act")
                        break;
                    case "4hour":
                        $("#hour4").toggleClass("act")
                        break;
                    case "day":
                        $("#day").toggleClass("act")
                        break;
                    case "week":
                        $("#week").toggleClass("act")
                        break;
                }
                $("#hour4").toggleClass("act")
                cha_time = "4hour";

                //ローソク足をリセット
                chart.removeSeries(candleSeries);
                candleSeries = null;

                //各インジケーターリセット
                chart.removeSeries(sma20Line);
                chart.removeSeries(sma75Line);
                chart.removeSeries(sma200Line);
                chart.removeSeries(mtf1dLine);
                chart.removeSeries(mtf1wLine);
                // sma20Line = null;

                //ローソク足に関する設定
                candleSeries = chart.addCandlestickSeries({
                    upColor: '#38b48b',
                    downColor: '#d9333f',
                    borderDownColor: '#d9333f',
                    borderUpColor: '#38b48b',
                    wickDownColor: '#d9333f',
                    wickUpColor: '#38b48b',
                });

                //ローソク足を表示
                const candleData4 = <?= $json4_output ?>;
                candleSeries.setData(candleData4);

                candleSeries.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                sma20Line = chart.addLineSeries({
                    color: 'rgb(255, 192, 203)',
                    lineWidth: 2,
                });
                ma20_Data = <?= $json4_ma20 ?>;
                //20maを表示
                sma20Line.setData(ma20_Data);
                sma20Line.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                sma75Line = chart.addLineSeries({
                    color: 'rgb(144, 0, 231)',
                    lineWidth: 2,
                });
                ma75_Data = <?= $json4_ma75 ?>;
                //75maを表示
                sma75Line.setData(ma75_Data);
                sma75Line.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                sma200Line = chart.addLineSeries({
                    color: 'rgb(255, 0, 166)',
                    lineWidth: 2,
                });
                ma200_Data = <?= $json4_ma200 ?>;
                //200maを表示
                sma200Line.setData(ma200_Data);
                sma200Line.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                mtf1dLine = chart.addLineSeries({
                    color: 'rgba(255, 0, 0, 0.66)',
                    lineWidth: 2,
                });
                mtf1d_Data = <?= $json4_mtf1d ?>;
                //mtf1dを表示
                mtf1dLine.setData(mtf1d_Data);
                mtf1dLine.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                mtf1wLine = chart.addLineSeries({
                    color: '#95949a',
                    lineWidth: 2,
                });
                mtf1w_Data = <?= $json4_mtf1w ?>;
                //mtf1wを表示
                mtf1wLine.setData(mtf1w_Data);
                mtf1wLine.applyOptions({
                    priceFormat: {
                        type: 'price',
                        precision: 5,
                        minMove: 0.00001,
                    },
                });

                //左上の機能を作り直し
                $(".sma-legend").empty();

                $(".sma-legend").append('<div id="d_ma20"></div>');
                setLegendText(ma20_Data[ma20_Data.length - 1].value, "ma20");

                $(".sma-legend").append('<div id="d_ma75"></div>');
                setLegendText(ma75_Data[ma75_Data.length - 1].value, "ma75");

                $(".sma-legend").append('<div id="d_ma200"></div>');
                setLegendText(ma200_Data[ma200_Data.length - 1].value, "ma200");

                $(".sma-legend").append('<div id="d_mtf1d"></div>');
                setLegendText(mtf1d_Data[mtf1d_Data.length - 1].value, "mtf1d");

                $(".sma-legend").append('<div id="d_mtf1w"></div>');
                setLegendText(mtf1w_Data[mtf1w_Data.length - 1].value, "mtf1w");

            });

        });
    </script>

</body>

</html>