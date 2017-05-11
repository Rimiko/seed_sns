<?php
session_start();

//ログイン状態のチェック
//ログインしてると判断できる条件
//１．セッションにidが入っていること
//2.最後の行動から１時間以内であること
if ((isset($_SESSION['login_member_id'])) && ($_SESSION['time'] + 3600>time())){

//ログインしている
//セッションの時間を更新
  $_SESSION['time'] = time();

}else{
//ログインしてない
  header('Location: login.php');
  exit();
}



//dbconnect.phpを読み込む
require('dbconnect.php');

//ログインしている人の情報を取得（名前の表示）

//SQL実行し、ユーザーのデータを取得
  $sql =sprintf('SELECT * FROM `members` WHERE `member_id` = %d', mysqli_real_escape_string($db,$_SESSION['login_member_id']));

  $record = mysqli_query($db,$sql) or die(mysqli_error($db));
  $member = mysqli_fetch_assoc($record);



//データベースに接続する
     // $dsn = 'mysql:dbname=seed_sns;host=localhost';
     // $user = 'root';
     // $password='';
     // $dbh = new PDO($dsn, $user, $password);
     // $dbh->query('SET NAMES utf8');

    // ２．SQL文を実行する
  if (!empty($_POST)) {
     //補足：つぶやきが空っぽではないときだけ、insertする
    if($_POST['tweet'] !== ''){
   //ポスト送信したときのみに動くようにする
    $tweet = htmlspecialchars($_POST['tweet'],ENT_QUOTES,'UTF-8');
    $login_member_id = $_SESSION['login_member_id'];
    $reply_tweet_id = 0;

    // $sql = 'INSERT INTO `tweets`(`tweet_id`, `tweet`, `member_id`, `reply_tweet_id`, `created`, `modified`) VALUES (NULL, "'.$tweet.'", "'.$_SESSION['login_member_id'].'", "%s", now(), now())';
    $sql = sprintf('INSERT INTO `tweets` (`tweet`, `member_id`, `reply_tweet_id`, `created`, `modified`) VALUES ("%s", "%s", "%s", now(), now());',
    mysqli_real_escape_string($db,$tweet),
    mysqli_real_escape_string($db,$login_member_id),
    mysqli_real_escape_string($db,$reply_tweet_id));
    mysqli_query($db,$sql) or die(mysqli_error($db));


    //データの再送信の防止（これをつけると再読み込みでPOST送信が発生しなくなる！）
    header("Location: index.php");
    exit();
  }
}

  //投稿を取得する
  $sql = 'SELECT m.`nick_name`,m.`picture_path`,t.* FROM `members`m,`tweets`t WHERE m.member_id = t.member_id ORDER BY `created` DESC;';
  $tweets = mysqli_query($db,$sql) or die(mysqli_error($db));
  $tweet_array = array();
  while ($tweet = mysqli_fetch_assoc($tweets)) {
  $tweet_array[] = $tweet;
  }
  //返信の場合
  if(isset($_REQUEST['res'])){
    //返信元のデータ（つぶやきとニックネーム）を取得してくる
    $sql = 'SELECT `tweets`.`tweet`,`members`.`nick_name` FROM `tweets` INNER JOIN `members` on `tweets`.`member_id` = `members`.`member_id` WHERE `tweet_id`='.$_REQUEST['res'];
    $reply = mysqli_query($db,$sql) or die(mysqli_error($db));
    $reply_table = mysqli_fetch_assoc($reply);
    //[@ニックネームつぶやき]という文字をセット
    $reply_post = '@' .$reply_table['nick_name']. ' ' .$reply_table['tweet'];
  }



  //   $stmt = $dbh->prepare($sql);
  //   $stmt->execute();
  //  }
  //   $sql = 'SELECT * FROM `tweets` ORDER BY `created` DESC;';
  //   //SQLを実行
  //   $stmt = $dbh->prepare($sql);
  //   $stmt->execute();

  //   $tweet_datas = array();

  //   while (1) {
  //     $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  //   if ($rec == false) {
  //       break;
  //     }
  //    $tweet_datas[] = $rec;
  //    }
  // $dbh = null;
     var_dump($sql);

    // SQLを実行
    // $stmt = $dbh->prepare($sql);
    // $stmt->execute();

    // $sql = 'SELECT * FROM `tweets` ORDER BY `created` DESC;';

    // //SQLを実行
    // $stmt = $dbh->prepare($sql);
    // $stmt->execute();
    // $tweet_datas=array();

    // while (1) {
    //   $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    // if ($rec == false) {
    //     break;
    //   }
    //  $tweet_datas[]=$rec;
    //  }

  // $dbh = null;


  ?>




<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SeedSNS</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-twitter-square"></i> Seed SNS</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">ログアウト</a></li>
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <legend>ようこそ<?php echo $member['nick_name']; ?>さん！</legend>
        <form method="post" action="" class="form-horizontal" role="form">
            <!-- つぶやき -->
            <div class="form-group">
              <label class="col-sm-4 control-label">つぶやき</label>
              <div class="col-sm-8">
                <?php
                if(isset($reply_post)){ ?>
                <textarea name="tweet" cols="50" rows="5" class="form-control" placeholder="例：Hello World!"><?php echo $reply_post; ?></textarea>
                <input type="hidden" name="reply_tweet_id" value="<?php echo $_REQUEST['res'];?>">
                <?php }else{?>
                <textarea name="tweet" cols="50" rows="5" class="form-control" placeholder="例：Hello World!"></textarea>
                <?php } ?>
                </div>
            </div>
          <ul class="paging">
            <input type="submit" class="btn btn-info" value="つぶやく">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <li><a href="index.html" class="btn btn-default">前</a></li>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <li><a href="index.html" class="btn btn-default">次</a></li>
          </ul>
        </form>
      </div>
      <div class="col-md-8 content-margin-top">
        <?php foreach($tweet_array as $tweet_each){ ?>
        <div class="msg">
          <img src="member_picture/<?php echo $tweet_each['picture_path']; ?>" width="48" height="48">
          <p><?php  echo $tweet_each['tweet']; ?>
            <span class="name"> <?php echo $tweet_each['nick_name']; ?> </span>
            [<a href="index.php?res=<?php echo $tweet_each['tweet_id']; ?>">Re</a>]
          </p>
          <p class="day">
            <a href="view.php?tweet_id=<?php echo $tweet_each['tweet_id']; ?>">
              <?php  echo $tweet_each['created']; ?>
            </a>
            [<a href="#" style="color: #00994C;">編集</a>]
            [<a href="#" style="color: #F33;">削除</a>]
          </p>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
