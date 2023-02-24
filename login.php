<?php
session_start();
$mail = $_POST['mail'];
$dsn = "mysql:host=localhost; dbname=aipertner; charset=utf8";
$username = "root";
$password = "";

try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    exit('データベースエラー');
}

$sql = "SELECT * FROM user WHERE mail = :mail";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':mail', $mail);
$stmt->execute();
$member = $stmt->fetch();

//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $member['pass'])) {
    $msg = 'ログインしました。';
    //DBのユーザー情報をセッションに保存
    $_SESSION['mail'] = $member['mail'];
    $_SESSION['pass'] = $member['pass'];
    $_SESSION['name'] = $member['name'];
    $link = '<a href="home.php">ホーム</a>';
} else {
    $msg = 'メールアドレスもしくはパスワードが間違っています。';
    $link = '<a href="loginlogout.php">戻る</a>';
}

unset($pdo);

?>

<h1><?php echo $msg; ?></h1>
<!--メッセージの出力-->
<?php echo $link; ?>
