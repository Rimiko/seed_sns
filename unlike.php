<?php
session_start();
require('dbconnect.php');

// get送信されたtweet_idを取得
if(isset($_REQUEST['tweet_id'])){
// SQL文作成（likesテーブルのINSERT文）
$sql = sprintf('DELETE FROM `likes` WHERE `member_id`=%d  AND `tweet_id` = %d;',$_SESSION['login_member_id'],
   $_REQUEST['tweet_id']);
//SQL文実行
mysqli_query($db,$sql) or die(mysqli_error($db));

// 一覧のページに戻る（index.php）
header("Location: index.php");
exit();
}

?>