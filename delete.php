<?php
session_start();
require('dbconnect.php');

if(isset($_REQUEST['tweet_id'])){
    //返信元のデータ（つぶやきとニックネーム）を取得してくる
    //・投稿者
// ・投稿者のプロフィール写真
// ・投稿したつぶやき
// ・投稿した日時
    // $sql = 'SELECT `tweets`.`tweet`,`members`.`nick_name`,`members`.`picture_path`,`tweets`.`created` FROM `tweets` INNER JOIN `members` on `tweets`.`member_id` = `members`.`member_id` WHERE `tweet_id`='.$_REQUEST['tweet_id'];

//delete_flag を１に更新
    $sql = 'UPDATE `tweets` SET `delete_flag` = 1 WHERE `tweet_id` = '.$_REQUEST['tweet_id'];

    $detail = mysqli_query($db,$sql) or die(mysqli_error($db));

      header("Location: index.php");
      exit();
  }


?>