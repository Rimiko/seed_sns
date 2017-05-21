<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <title></title>
</head>
<body>

<!-- 20170519 復習 -->
<?php
$name = 'おはよう';
$greeting = '今日';
$weather = '晴れたね';
$aisatsu = sprintf('%s, %s %%次の試合絶対勝とう %%%s%%',$name,$greeting, $weather);
echo $aisatsu;
echo '<br>';


$names = array('ドラえもん','のびた','ジャイアン','しずか','スネ夫');
for ($i=0; $i<5; $i++){
    if($names[$i]=='しずか'){
    echo 'こんにちは、私' . $names[$i].'<br>';
}else{
    echo 'こんにちは、僕' . $names[$i].'<br>';
}
}

//連想配列
$members = array('nickname' =>'ドラえもん' , 'gender' => 'male');

foreach ($members as $key => $value) {
    echo '  ';
    echo $key;
    echo '→';
    echo $value;
    echo '<br>';
}

$member1 = array('nickname' =>'ドラえもん' , 'gender' => 'male');
$member2 = array('nickname' =>'のびた' , 'gender' => 'male');
$member3 = array('nickname' =>'ジャイアン' , 'gender' => 'male');

$members = array($member1,$member2,$member3);
var_dump($members);
foreach ($members as $member_each) {
    var_dump($member_each);
    echo $member_each['nickname'].'<br>';
    # code...
}

    //宿題
    // 連想配列
    $symphony_no1 = array('composer' => 'Brahms', 'era' => '1876');
    // 二次元配列
    $symmphonies = array($symphony_no1);
    // 三次元配列
    $classic = array($symmphonies);
    // 四次元配列
    $music = array($classic);
    // 五次元配列
    $culture = array($music);

    echo $culture[0][0][0][0]['composer'];


?>
<!-- マーヨンブンタック おはよう -->

</body>
</html>

