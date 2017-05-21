<?php
session_start();
// セッション変数の中身を上書きして空にする。
$_SESSION = array();
if (ini_get("session.use_cookies")){
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time(), - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

}

//COOKIE情報も削除
session_destroy();
setcookie('email', '', time() - 3600);
setcookie('password', '', time() - 3600);

header("Locaion: index.php");
exit();



?>