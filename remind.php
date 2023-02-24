<?php

if (isset($_POST["subject"])) {

    //フォームからの値をそれぞれ変数に代入
    $subject = $_POST['subject'];
    $date = $_POST['date'];

    $dsn = "mysql:host=localhost; dbname=aipertner; charset=utf8";
    $username = "root";
    $password = "";

    try {
        $dbh = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        exit('データベースエラー');
    }

    $sql = "INSERT INTO remind(user_id,subject,date) VALUES (:user_id,:subject,:date)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':subject', $subject);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':user_id', 1);
    $stmt->execute();
    unset($pdo);
} else {
    error_log('err');
}

?>

