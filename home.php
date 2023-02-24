<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="AIPertner">
    <meta name="keywords" content="AI,リマインド,AIpertner,AIPertner">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ホーム画面</title>
    <link rel="stylesheet" href="css/stylehome.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="icon" href="img/aikon/favicon.ico" id="favicon">
    <link rel="apple-touch-icon" sizes="180x180" href="img/aikon/apple-touch-icon-180x180.png">
</head>

<header>
    <div class="headers">
        <div class="loginmg">
            <?php
            session_start();
            error_reporting(0);
            $name = $_SESSION['name'];
            if (isset($_SESSION['mail'])) { //ログイン時
                $msg = 'こんにちは' . htmlspecialchars($name, \ENT_QUOTES, 'UTF-8') . 'さん';
                $link = '<a href="logout.php">ログアウト</a>';
            } else { //ログイン時
                $msg = 'ログインしていません';
                $link = '<a href="loginlogout.php">ログイン</a>';
            }
            ?>
            <h2><?php echo $msg; ?></h2>
            <?php echo $link; ?>
        </div>
        <div class="systemname">
            <a href="home.php">
                <h1>AIPertner</h1>
            </a>
        </div>

        <div class="logo">
            <a href="home.html"><img src="img/logo_transparent.png"></a>
        </div>
    </div>
</header>

<script type="text/javascript">
    console.log("送信ボタンを押して下さい");
    var sock = new WebSocket('ws://127.0.0.1:8000');

    sock.addEventListener('open', function(e) { // 接続
        console.log('Socket 接続成功');
    });

    sock.addEventListener('message', function(e) { // サーバーからデータを受け取る
        Push.create(e.data);
        //document.body.style.backgroundColor = "blue"
    });

    document.addEventListener('DOMContentLoaded', function(e) {
        document.getElementById('sample').addEventListener('click', function(e) {
            console.log("送信されました");
            sock.send(`${document.getElementById("num").value}`); // WebSocketでサーバーに文字列を送信
        });
    });
</script>

</head>

<body>


    <input type="number" name="num" id="num" value="15">
    <input type="button" id="sample" value="送信">

    <br>

    <input type="button" id="push" onclick="return push()" value="クリックすると通知">
    <script>
        function push() {
            Push.create('通知');
        }
    </script>

    <br>

    <p></p>

    <script src="https://riversun.github.io/chatux/chatux.min.js"></script>
    <script>
        const chatux = new ChatUx();

        //ChatUXの初期化パラメータ
        const initParam = {
            renderMode: 'pc',
            api: {
                //echo chat server
                endpoint: 'http://localhost:8080/chat',
                method: 'GET',
                dataType: 'json',
                errorResponse: {
                    output: [
                        //ネットワークエラー発生時のエラーメッセージ
                        {
                            type: 'text',
                            value: 'ネットワークエラーが発生しました'
                        }
                    ]
                }
            },
            bot: {
                botPhoto: 'img/3364.png',
                humanPhoto: null,
                widget: {
                    sendLabel: '送信',
                    placeHolder: '入力してください。'
                }
            },
            window: {
                title: '秘書',
                infoUrl: 'home.php',
                size: {
                    width: 900, //ウィンドウの幅
                    height: 500, //ウィンドウの高さ
                    minWidth: 300, //ウィンドウの最小幅
                    minHeight: 300, //ウィンドウの最小高さ
                    titleHeight: 60 //ウィンドウのタイトルバー高さ
                },
                appearance: {
                    //ウィンドウのボーダースタイル
                    border: {
                        shadow: '2px 2px 20px  rgba(0, 0, 0, 0.5)', //影
                        width: 0, //ボーダーの幅
                        radius: 6 //ウィンドウの角丸半径
                    },
                    //ウィンドウのタイトルバーのスタイル
                    titleBar: {
                        fontSize: 14,
                        color: 'white',
                        background: '#191970',
                        leftMargin: 40,
                        height: 40,
                        buttonWidth: 36,
                        buttonHeight: 16,
                        buttonColor: 'white',
                        buttons: [
                            //閉じるボタン
                            {
                                //閉じるボタンのアイコン(fontawesome)
                                fa: 'fas fa-times',
                                name: 'hideButton',
                                visible: true //true:表示する
                            }
                        ],
                        buttonsOnLeft: [
                            //左上のinforUrlボタン
                            {
                                fa: 'fas fa-comment-alt', //specify font awesome icon
                                name: 'info',
                                visible: true //true:表示する
                            }
                        ],
                    },
                }
            },
            methods: {
                onServerResponse: (response) => {
                    //A callback that occurs when there is a response from the chat server.
                    // You can handle server responses before reflecting them in the chat UI.
                    if (response.output && response.output[0]) {
                        console.log('#onServerResponse response=' + JSON.stringify(response));
                        console.log(response.output[0].remind);

                    }
                    return response;
                }
            }

        };
        chatux.init(initParam);
        chatux.start(true);
    </script>

    <?php
    $dsn = "mysql:host=localhost; dbname=aipertner; charset=utf8";
    $username = "root";
    $password = "";
    try {
        $dbh = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }
    try {
        $sql = "SELECT remind FROM subject.date";
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
    } catch (PDOException $Exception) {
        die('接続エラー：' . $Exception->getMessage());
    }
    ?>

    <table>
        <tbody>
            <tr>
                <th>件名</th>
                <th>日時</th>
            </tr>
            <?php
            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <th><?= htmlspecialchars($row['subject']) ?></th>
                    <th><?= htmlspecialchars($row['date']) ?></th>
                </tr>
            <?php
            }
            $pdo = null;
            ?>
        </tbody>
    </table>

    <footer>
        <button><a href="loginlogout.php">戻る</a></button>
    </footer>

</body>

</html>