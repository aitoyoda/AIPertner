<?php
session_start();
$_SESSION = array(); //セッションの中身をすべて削除

if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');//クッキー
}

session_destroy(); //セッションを破壊
?>

<p>ログアウトしました。</p>
<a href="login.php">ログインへ</a>