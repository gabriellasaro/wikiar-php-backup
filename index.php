<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(__FILE__).'/includes/info-core.php');
require_once('lib/home/home.php');
require_once(dirname(__FILE__).'/lib/wa_manager/local_session.php');

$home = new HomePage();
$se = new WaLocalSession();
$infoCore = new InfoCore();

$home->check_cache();
$dataHome = $home->home_json();
$se->safe_session();
?>
<!--
L           AAAAAAAAAAAA   SSSSSSSSSSS AAAAAAAAAAA RRRRRRRRRRRR OOOOOOOOOOOO
L           A          A  SS           A         A R          R O          O
L           A          A SS            A         A R          R O          O
L           A          A  SS           A         A R          R O          O
L           A          A   SSSS        A         A R          R O          O
L           AAAAAAAAAAAA       SSSS    AAAAAAAAAAA RRRRRRRRRRRR O          O
L           A          A           SS  A         A R  RR        O          O
L           A          A            SS A         A R     RR     O          O
L           A          A           SS  A         A R       RR   O          O
LLLLLLLLLLL A          A SSSSSSSSSSS   A         A R         RR OOOOOOOOOOOO
COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
-->
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title><?php echo $infoCore->name.", ".$infoCore->subname;?></title>
<meta http-equiv="content-language" content="pt-br" />
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>">
<meta property="og:image" itemprop="image" content="<?php echo $infoCore->img_logo;?>">
<meta property="og:site_name" content="<?php echo $infoCore->name." - ".$infoCore->subname;?>">
<meta property="og:description" content="<?php echo $infoCore->description; ?>">
<meta property="og:locale" content="pt-br">
<meta name="Robots" content="INDEX,FOLLOW">
<meta name="RESOURCE-TYPE" content="DOCUMENT">
<meta name="DISTRIBUTION" content="GLOBAL">
<meta name="COPYRIGHT" content="<?php echo $infoCore->min_copyright;?>">
<meta name="RATING" content="GENERAL">
<meta name="theme-color" content="<?php echo $infoCore->themeColor;?>">
<!-- <link rel="shortcut icon" href=""> -->
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/menu/menu.css">
<link rel="stylesheet" href="css/home/style.css" media="screen">
<link rel="stylesheet" href="css/home/style2.css" media="(max-width: 1000px)">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body onLoad="notify(); ba();">
<div class="wikiar-home-bar">
<a href="" title="MENU" onclick="menu('0'); return false;"><img src="./img/menu.svg" id="img-menu"></a>
<a href="./"><img src="./<?php echo $infoCore->img_logo;?>" id="logo" alt="<?php echo $infoCore->name; ?>"/></a></div>
<div class="box_home">
<p style="margin-top:110px;">Populares</p>
<?php
if(is_array($dataHome)){
  foreach ($dataHome as $key => $value) {
    if($key==0){
      echo '<div class="top" title="'.$value['post_title'].'"><a href="post/?p='.$value['code_post'].'">
      <div id="left"><img src="'.$value['post_img'].'"/></div><div id="right">
      <div id="more"><p id="title">'.$value['post_title'].'</p>
      </div></a><div id="profile">
      <a href="user/?u='.urlencode($value['username']).'">';
      if(strlen($value['user_img']) > 1){
        echo '<img src="'.$value['user_img'].'" alt="'.$value['user_nicename'].'" title="'.$value['user_nicename'].'"/>';
      }
      echo '<p>Gabriel Lasaro</p></a>
      </div></div></div>';
    }else{
      echo '<div class="container-post" title="'.$value['post_title'].'"><a href="post/?p='.$value['code_post'].'">
      <img src="'.$value['post_img'].'"/>
      <div id="more"><p id="title">'.$value['post_title'].'</p></div></a>
      <div class="div_profile"><a href="user/?u='.urlencode($value['username']).'">';
      if(strlen($value['user_img']) > 1){
        echo '<img src="'.$value['user_img'].'" alt="'.$value['user_nicename'].'" title="'.$value['user_nicename'].'"/>';
      }
      echo '<p>Gabriel Lasaro</p></a></div></div>';
    }
  }
}else{
  echo '<p class="p_msg p_erro">Não foi encontrado nem uma publicação!</p>';
}
?>
</div>
<p id="copy" style="background:none;margin-bottom:0px;"><span style="color:#1E90FF;">Página atualizada a cada 10 minutos</span></p><hr id="update_hr"><p id="copy" style="background:none;"><?php echo $infoCore->location_created;?><br><br><?php echo $infoCore->copyright;?></p>
<?php
echo '
<div id="container_category">
<ul>';
$dataTags = $home->home_json('top_tags');
$numberTags = count($dataTags);
if($numberTags>=1){
  for($i=0;$i<=$numberTags-1;$i++){
    echo '<li><a href="tag/?t='.$dataTags[$i]['code_tag'].'">'.$dataTags[$i]['tag_name'].'</a></li>';
  }
}
echo '</ul>
</div>';
?>
<div id="seta_topoMENU"></div><div id="menu_lateral"><br>
<a href="./" id='link'>PÁGINA INICIAL</a><hr>
<?php
if($se->session_check()==true){
  echo '<div id="container_profile"><a href="user/?u='.urlencode($_SESSION['username']).'"><img src="'.$_SESSION['user_img'].'" alt="'.$_SESSION['full_name'].'" title="'.$_SESSION['full_name'].'"/><span title="'.$_SESSION['full_name'].'">';
  if(strlen($_SESSION['full_name']) > 18){
    echo substr($_SESSION['full_name'],0,18)."....";
  }else{
    echo $_SESSION['full_name'];
  }
  echo '</span></a></div><hr><a href="logout.php" id="link">SAIR</a>';
}else{
  echo '<a href="login/" id="link">ENTRAR</a><a href="signup/" id="link">INSCREVA-SE</a>';
}
?>
<hr><a href="./dashboard/" id='link'>PAINEL</a>
<a href="./dashboard/new/" id='link'>NOVA PUBLICAÇÃO</a><hr>
<a href="./about/" id='link'>SOBRE - <?php echo strtoupper($infoCore->name);?></a>
<br></div>
<script src="js/classic_bar.js"></script>
<script src="js/notification.js"></script>
<script>
var a1 = window.pageYOffset;
var a2 = a1+20;

function ba(){
if(window.pageYOffset >= a2){
  document.getElementById('container_category').style.top="0px";
}else {
  document.getElementById('container_category').style.top="80px";
}
setTimeout("ba()",600);
}
</script>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
