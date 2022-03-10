<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>条件入力</title>
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-1.8.0.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <div class="container-fluid">
        <ul class="nav nav-tabs nav-pills">
            <li class="nav-item">
                <a href="#" class="nav-link active">条件入力</a>
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
                <a href="index6.php" class="nav-link">結果履歴（チャート）</a>
            </li>
        </ul>
        <form action="data_create.php" method="POST" name="set">
            <div class="row">
                <nav class="col-3 d-block bg-dark text-white sidebar">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <p>分析条件①（通貨、時間足などを決めよう）：</p>
                                <div class="form-group down_mv">
                                    <label>通貨ペア：</label>
                                    <select class="col-6 custom-select custom-select-sm" name="currency">
                                        <option value="">--通貨ペア選択--</option>
                                        <option value="1">EUR/USD</option>
                                        <option value="2">USD/JPY</option>
                                        <option value="3">EUR/JPY</option>
                                        <option value="4">GBP/JPY</option>
                                        <option value="5">GBP/USD</option>
                                        <option value="6">AUD/JPY</option>
                                        <option value="7">AUD/USD</option>
                                        <option value="8">EUR/AUD</option>
                                        <option value="9">GBP/AUD</option>
                                    </select>
                                </div>
                                <div class="form-group down_mv">
                                    <label>時間足：　</label>
                                    <select class="col-6 custom-select custom-select-sm" name="chart_time">
                                        <option value="">--時間足選択--</option>
                                        <option value="1">1分足</option>
                                        <option value="2">5分足</option>
                                        <option value="3">30分足</option>
                                        <option value="4">1時間足</option>
                                        <option value="5">4時間足</option>
                                        <option value="6">1日足</option>
                                        <option value="7">週足</option>
                                        <option value="8">月足</option>
                                    </select>
                                </div>
                                <p>分析期間：</p>
                                <div class="row down_mv">
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="b_from_ymd">
                                        </div>
                                    </div>
                                    <label>〜</label>
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="b_to_ymd">
                                        </div>
                                    </div>
                                </div>
                                <p>利確設定：</p>
                                <div class="down_mv">
                                    <div class="left_text">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="rikaku_val">
                                            <div class="input-group-append">
                                                <span class="input-group-text">pips</span>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="l_center">で利確する</label>
                                </div>
                                <p>損切り設定：</p>
                                <div class="down_mv">
                                    <label class="l2_center">ローソク足の確定を</label>
                                    <button type="button" class="btn-sm btn-secondary wait_b">待って</button>
                                    <button type="button" class="btn-sm btn-outline-secondary wait_b">待たずに</button>
                                </div>
                                <div class="down_mv">
                                    <div class="left_text">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="songiri_val">
                                            <div class="input-group-append">
                                                <span class="input-group-text">pips</span>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="l_center">で損切りする</label>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="form-group">
                                    <label>分析条件②（トレンドを選ぼう）：</label>
                                    <select class="col-6 custom-select custom-select-sm" name="trend">
                                        <option value="">--トレンド選択--</option>
                                        <option value="1">すべて</option>
                                        <option value="2">上昇トレンド</option>
                                        <option value="3">下落トレンド</option>
                                        <option value="4">レンジ</option>
                                    </select>
                                </div>
                                <label class="offset-1">トレンドを判断する指標：ＭＡ</label>
                                <div class="row offset-1">
                                    <div class="col-4 c-right0">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="trend_ma">
                                            <div class="input-group-append">
                                                <span class="input-group-text">MA</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="plus_btn shadow">＋</div>
                                    </div>
                                </div>
                                <label class="offset-1">トレンドを判断する指標：ＭＴＦ ＭＡ</label>
                                <div class="row offset-1">
                                    <div class="col-4 c-right0">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="trend_mtf">
                                            <div class="input-group-append">
                                                <span class="input-group-text">MTF</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="plus_btn shadow">＋</div>
                                    </div>
                                </div>
                                <hr>
                                <p>分析条件③（トレンドの強さを決めよう）：</p>
                                <div class="form-group">
                                    <label>値幅を決める：</label>
                                    <select class="col-9 custom-select custom-select-sm" name="nehaba">
                                        <option value="">--値幅の要素単位--</option>
                                        <option value="1">おまかせ</option>
                                        <option value="2">平均ボラティリティとの差</option>
                                        <option value="3">pips</option>
                                    </select>
                                </div>
                                <label class="offset-1">ボラティリティ差：</label>
                                <div class="row offset-1">
                                    <div class="col-4">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="vola_from">
                                            <div class="input-group-append">
                                                <span class="input-group-text">％</span>
                                            </div>
                                        </div>
                                    </div>
                                    <label>〜</label>
                                    <div class="col-4">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="vola_to">
                                            <div class="input-group-append">
                                                <span class="input-group-text">％</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="offset-1">pip数：</label>
                                <div class="row offset-1">
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="pips_from">
                                            <div class="input-group-append">
                                                <span class="input-group-text">pips</span>
                                            </div>
                                        </div>
                                    </div>
                                    <label>〜</label>
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="pips_to">
                                            <div class="input-group-append">
                                                <span class="input-group-text">pips</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>期間を決める：</label>
                                    <select class="col-9 custom-select custom-select-sm" name="period">
                                        <option value="">--期間の要素単位--</option>
                                        <option value="1">おまかせ</option>
                                        <option value="2">波形の波数</option>
                                        <option value="3">時間（hour）</option>
                                    </select>
                                </div>
                                <label class="offset-1">何波後：</label>
                                <div class="row offset-1">
                                    <div class="col-4">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="wave">
                                            <div class="input-group-append">
                                                <span class="input-group-text">波後</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="offset-1">時間（hour）：</label>
                                <div class="row offset-1">
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="hour_from">
                                            <div class="input-group-append">
                                                <span class="input-group-text">時間</span>
                                            </div>
                                        </div>
                                    </div>
                                    <label>〜</label>
                                    <div class="col-5">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" name="hour_to">
                                            <div class="input-group-append">
                                                <span class="input-group-text">時間</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <p id="add1">分析条件④（注文するタイミングを決めよう）：</p>
                                <button type="button" id="part1_null" class="btn btn-outline-info part_1d">テクニカル、ローソク足の勢いなど</button>
                                <p id="add2">で</p>
                                <button type="button" id="part2_null" class="btn btn-outline-secondary part_2d">ライン、ローソク足など</button>
                                <p id="add3">が</p>
                                <button type="button" id="part3_null" class="btn btn-outline-success part_3d">陽線、下ヒゲ、テクニカルの状態など</button>
                                <p id="add4">の状態のとき</p>
                                <button type="button" id="part4_null" class="btn btn-outline-warning part_4d">超えた、クロスした、当たったなど</button>
                                <p>したら</p>
                                <div class="row offset-1">
                                    <button type="submit" class="btn btn-primary">買う（BUY）</button>
                                    <button type="submit" class="btn btn-danger">売る（SELL）</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 必要に応じてページネーション -->
                    <div class="swiper-pagination"></div>
                    <!-- 必要に応じてナビボタン -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </nav>
                <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container col-6 c-right0 c-left0">
                    <div id="tradingview_aeb1e"></div>
                    <div class="tradingview-widget-copyright">
                        <!-- TradingView提供の
                    <a href="https://jp.tradingview.com/symbols/GBPAUD/?exchange=OANDA" rel="noopener" target="_blank">
                        <span class="blue-text">GBPAUDチャート</span>
                    </a> -->
                    </div>
                </div>
                <!-- TradingView Widget END -->
                <!-- モーダルウィンドウ群 -->
                <div class="modal-container">
                    <div class="modal-body">
                        <div class="modal-close">×</div>
                        <div class="modal-content">
                            <div class="row">
                                <button type="button" id="def1_0" class="btn btn-outline-info part1">設定しない</button>
                                <button type="button" id="def1_1" class="btn btn-outline-info part1">ボリンジャーバンド</button>
                                <button type="button" id="val1_1" class="btn btn-outline-info part1">ＭＡ</button>
                                <button type="button" id="val1_2" class="btn btn-outline-info part1">ＭＴＦ　ＭＡ</button>
                                <button type="button" id="def1_2" class="btn btn-outline-info part1">ピボット</button>
                                <button type="button" id="def1_3" class="btn btn-outline-info part1">ＭＡＣＤ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script>
        new TradingView.widget({
            "width": 1135,
            "height": 705,
            "symbol": "OANDA:GBPAUD",
            "timezone": "Asia/Tokyo",
            "theme": "light",
            "style": "1",
            "locale": "ja",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "range": "12M",
            "allow_symbol_change": true,
            "watchlist": [
                "OANDA:GBPUSD",
                "OANDA:GBPJPY",
                "OANDA:USDJPY",
                "OANDA:EURUSD"
            ],
            "details": true,
            "studies": [
                "MASimple@tv-basicstudies"
            ],
            "container_id": "tradingview_aeb1e"
        });
    </script>
    <script>
        const swiper = new Swiper(".swiper", {
            // ページネーションが必要なら追加
            pagination: {
                el: ".swiper-pagination"
            },
            // ナビボタンが必要なら追加
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }
        });
    </script>

    <script>
        $(document).ready(function() {});

        //HTMLの読み込みが終わった後、処理開始
        $(window).on('load', function() {
            $(".swiper-slide").on("click", ".btn-outline-info", function() {
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
            $(".wait_b").on("click", function() {
                if ($(this).text() === "待って") {
                    $(this).toggleClass("btn-secondary");
                    $(this).toggleClass("btn-outline-secondary");
                    $(this).next().toggleClass("btn-secondary");
                    $(this).next().toggleClass("btn-outline-secondary");
                } else {
                    $(this).toggleClass("btn-secondary");
                    $(this).toggleClass("btn-outline-secondary");
                    $(this).prev().toggleClass("btn-secondary");
                    $(this).prev().toggleClass("btn-outline-secondary");
                }
            });
            $(".part1").on("click", function() {
                if ($(this).attr("id") === "def1_0") {
                    if ($("#add1").next().prop("tagName") === "BUTTON") {
                        $('.part_1d').text("テクニカル、ローソク足の勢いなど");
                        $('.part_1d').attr("id", "part1_null");
                    } else if ($("#add1").next().prop("tagName") === "DIV") {
                        $("#add1").next().remove();
                        $("#add1").after('<button type="button" id="part1_null" class="btn btn-outline-info part_1d">テクニカル、ローソク足の勢いなど</button>');
                    }
                } else if ($(this).attr("id") === "val1_1") {
                    $("#add1").next().remove();
                    $("#add1").after(
                        '<div class="row">' +
                        '<div class="col-3 c-right0">' +
                        '<div class="input-group">' +
                        '<input type="text" class="form-control">' +
                        '</div>' +
                        '</div>' +
                        '<div>' +
                        '<button type="button" id="part1_ma" class="btn btn-outline-info part_1d">ＭＡ</button>' +
                        '</div>' +
                        '</div>'
                    );
                } else if ($(this).attr("id") === "def1_1") {
                    if ($("#add1").next().prop("tagName") === "BUTTON") {
                        $('.part_1d').text($(this).text());
                        $('.part_1d').attr("id", "part1_bband");
                    } else if ($("#add1").next().prop("tagName") === "DIV") {
                        $("#add1").next().remove();
                        $("#add1").after('<button type="button" id="part1_bband" class="btn btn-outline-info part_1d">ボリンジャーバンド</button>');
                    }
                }
            });
        });
    </script>

</body>

</html>