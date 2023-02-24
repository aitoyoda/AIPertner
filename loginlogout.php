<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="description" content="AIPertner">
    <meta name="keywords" content="AI,リマインド,AIpertner,AIPertner">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="css/stylelogin.css">
    <link rel="icon" href="img/aikon/favicon.ico" id="favicon">
    <link rel="apple-touch-icon" sizes="180x180" href="img/aikon/apple-touch-icon-180x180.png">
</head>

<body>
    <header>
        <?php
        // if (isset($_SESSION["login"])) {
        //     session_regenerate_id(TRUE);
        //     header("Location: success.php");
        //     exit();
        // }
        ?>
    </header>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="register.php" method="post">
                <h2><a href="index.html">AIPertner</a></h2>
                <h1>新規登録</h1>
                <input type="text" name="name" required placeholder="名前" />
                <input type="email" name="mail" required placeholder="メールアドレス" />
                <input type="password" name="pass" required placeholder="パスワード" />
                <button>登録</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="login.php" method="post">
                <h2><a href="index.html">AIPertner</a></h2>
                <h1>ログイン</h1>
                <input type="email" name="mail" required placeholder="メールアドレス" />
                <input type="password" name="pass" required placeholder="パスワード" />
                <button type="submit" name="login">ログイン</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>おかえりなさい！</h1>
                    <p>秘書に用事がありますか？</p>
                    <button class="ghost" id="signIn">ログイン</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>ようこそ！</h1>
                    <p>ここがあなたと秘書AIとの始まりの地</p>
                    <button class="ghost" id="signUp">新規登録</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>
</body>


</html>