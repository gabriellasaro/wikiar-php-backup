<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
require_once(dirname(dirname(__FILE__)).'/includes/info-core.php');
require_once(dirname(dirname(__FILE__)).'/lib/tags/tags-page.php');
$infoCore = new InfoCore();
$tag = new TagsPage();
if($tag->check_cache($_GET['t'])==0){
  echo 'Page error!<br>Nada encontrado!';
  exit;
}
$dados = $tag->tag_json();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $dados[0]['tag_name']. ' - '. $infoCore->name;?></title>
<meta http-equiv="content-language" content="pt-br" />
<meta property="og:type" content="website">
<meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'];?>">
<meta property="og:image" itemprop="image" content="<?php echo $infoCore->img_logo;?>">
<meta property="og:site_name" content="<?php echo $infoCore->name." - ".$infoCore->subname;?>">
<meta property="og:description" content="<?php echo $infoCore->description; ?>">
<meta property="og:locale" content="pt-br">
<meta name="Robots" content="INDEX,FOLLOW">
<meta name="RESOURCE-TYPE" content="DOCUMENT">
<meta name="DISTRIBUTION" content="GLOBAL">
<meta name="COPYRIGHT" content="<?php echo $infoCore->min_copyright;?>">
<meta name="language" content="pt-br">
<meta name="RATING" content="GENERAL">
<meta name="theme-color" content="#808080">
<link rel="stylesheet" href="../css/menu/menu.css">
<link rel="stylesheet" href="../css/tag/style.css" media="screen">
<link rel="stylesheet" href="../css/tag/style2.css" media="(max-width: 1000px)">
<meta name="viewport" content="width=device-width">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
<script src="../js/classic_bar.js"></script>
</head>
<body>
<div id="subbarra">
<p><?php echo $dados[0]['tag_name'];?></p>
</div>
<?php
if(is_array($dados)){
  unset($dados[0]);
  echo '<div id="container_home">';
  foreach ($dados as $value) {
    echo '<div class="container-post" title="'.$value['post_title'].'"><a href="../post/?p='.$value['code_post'].'">
    <img src="'.$value['post_img'].'"/>
    <div id="more"><p id="title">'.$value['post_title'].'</p></div></a>
    <div class="div_profile"><a href="../profile/?user='.urlencode($value['user']['username']).'">';
    if(strlen($value['user']['user_img']) > 1){
      echo '<img src="'.$value['user']['user_img'].'" alt="'.$value['user']['user_nicename'].'" title="'.$value['user']['user_nicename'].'"/>';
    }
    echo '<p>'.$value['user']['user_nicename'].'</p></a></div></div>';
  }
}else{
  echo '<br><br><p>Não foi encontrado nem uma publicação!</p><br><br>';
}
echo '</div>';
?>


<p id="copy" style="background:none;margin-bottom:0px;"><span style="color:#1E90FF;">Página atualizada a cada 10 minutos</span></p><hr id="update_hr"><p id="copy" style="background:none;"><?php echo $infoCore->location_created;?><br><br><?php echo $infoCore->copyright;?></p>

<div class="wikiar-classic-bar">
<a href="" title="MENU" onclick="menu('../'); return false;"><img src="../img/menu.svg" id="img-menu"></a>
<a href="../"><img src="<?php echo '../'.$infoCore->img_logo;?>" id="logo" alt="<?php echo $infoCore->name; ?>"/></a>
</div>

<div id="seta_topoMENU" style="border-bottom: 10px solid #A9A9A9;"></div><div id="menu_lateral"><br>
<a href="../index.php" id='link'>PÁGINA INICIAL</a><hr>
<?php
if($se->session_check()==true){
  echo '<div id="container_profile"><a href="../profile/?user='.$_SESSION['username'].'"><img src="'.$_SESSION['profile_img'].'" alt="'.$_SESSION['full_name'].'" title="'.$_SESSION['full_name'].'"/><span title="'.$_SESSION['full_name'].'">';
  if(strlen($_SESSION['full_name']) > 18){
    echo substr($_SESSION['full_name'],0,18)."....";
  }else{
    echo $_SESSION['full_name'];
  }
  echo '</span></a></div><hr><a href="logout.php" id="link">SAIR</a>';
}else{
  echo '<a href="../login/" id="link">ENTRAR</a><a href="singup/" id="link">INSCREVA-SE</a>';
}
?>
<hr><a href="../panel/" id='link'>PAINEL DE ADMINISTRAÇÃO</a>
<a href="../editor/new/" id='link'>CRIAR POST</a><hr>
<a href="../sobre/" id='link'>SOBRE - <?php echo strtoupper($infoCore->name);?></a>
<br>
</div>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
