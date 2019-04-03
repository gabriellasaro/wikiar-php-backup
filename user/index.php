<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

if(empty($_GET['u'])){
  return header('Location: http://'.$_SERVER['HTTP_HOST']);
}
require_once(dirname(dirname(__FILE__))."/lib/profile/profile-page.php");
$cache = new ProfilePage($_GET['u']);
if($cache->check()!=1){
  echo 'Page error!<br>Usuário indiponível/inexistente!';
  exit;
}
$dataProfile = $cache->show($_GET['u']);

require_once(dirname(dirname(__FILE__)).'/includes/info-core.php');
$InfoCore = new InfoCore();
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title><?php echo $dataProfile['user']['user_nicename'].' - '.$InfoCore->name;?></title>
<meta http-equiv="content-language" content="pt-br" />
<meta name="description" content="<?php echo substr($dataProfile['user']['user_description'], 0, 144).'...';?>">
<link rel="canonical" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
<meta name="author" content="<?php echo $dataProfile['user']['user_nicename'];?>">
<meta name="robots" content="index">
<!-- Google+ / Schema.org -->
<meta itemprop="name" content="<?php echo $dataProfile['user']['user_nicename'].' - '.$InfoCore->name;?>">
<meta itemprop="description" content="<?php echo substr($dataProfile['user']['user_description'], 0, 194).'...';?>">
<meta itemprop="image" content="<?php echo $dataProfile['user']['user_img'];?>">
<!-- Open Graph Facebook -->
<meta property="og:title" content="<?php echo $dataProfile['user']['user_nicename'].' - '.$InfoCore->name;?>">
<meta property="og:description" content="<?php echo substr($dataProfile['user']['user_description'], 0, 194).'...';?>"/>
<meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
<meta property="og:site_name" content="<?php echo $InfoCore->name;?>"/>
<meta property="og:type" content="website">
<meta property="og:image" content="<?php echo $dataProfile['user']['user_img'];?>">
<!-- Twitter -->
<meta name="twitter:title" content="<?php echo $dataProfile['user']['user_nicename'].' - '.$InfoCore->name;?>">
<meta name="twitter:description" content="<?php echo substr($dataProfile['user']['user_description'], 0, 194).'...';?>">
<meta name="twitter:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:image" content="<?php echo $dataProfile['user']['user_img'];?>">
<meta name="theme-color" content="#808080">
<link rel="stylesheet" href="../css/menu/menu.css">
<link rel="stylesheet" href="../css/profile/profile.css">
<meta name="viewport" content="width=device-width">
<script src="../js/classic_bar.js"></script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
</head>
<body>
<div class="container-header">
<?php
if(strlen($dataProfile['user']['user_capa'])>1){
  echo '<style>
.url-img{
  background:url("'.$dataProfile['user']['user_capa'].'");
}
</style>
<div class="img-capa url-img"></div>';
}else{
  echo '<div class="not-img-capa"></div>';
}
?>
<?php
if(strlen($dataProfile['user']['user_img'])>1){
  echo '<div id="img-profile"><img src="'.$dataProfile['user']['user_img'].'" alt="'.$dataProfile['user']['user_nicename'].'" title="'.$dataProfile['user']['user_nicename'].'" /></div>';
}
echo '<div id="container_first"><p id="display_name">'.$dataProfile['user']['user_nicename'].'</p>';
if(strlen($dataProfile['user']['user_address'])>1){
  echo '<p style="font-size:16px;">'.$dataProfile['user']['user_address'].'</p>';
}
echo '</div>';
?>
</div>
<div id="box_centro">
<div class="info">
  <p id="button" onCLick="seguir('<?php echo $dataProfile['user']['code_user'];?>');" title="Seguir">SEGUIR +</p></center>
  <p id="t1" style="text-align:center;" title="Número de seguidores"><?php echo $dataProfile['user']['user_followers'];?></p>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="../js/profile/profile_js.js"></script>
<script>follower_search('<?php echo $dataProfile['user']['code_user']?>');</script>
<div class="info">
  <?php
  if(strlen($dataProfile['user']['user_description'])>1){
    echo '<p id="t1">Descrição:</p><p id="t2">'.$dataProfile['user']['user_description'].'</p><hr>';
  }
  ?>
  <p id="t1">Redes sociais:</p>
  <!-- <img src="../img/socialnetwork/youtube.svg" alt="YouTube" title="YouTube"/> -->
  <a href="https://www.facebook.com/<?php echo $dataProfile['user']['social']['ID_facebook'];?>" target="_blank"><img src="../img/socialnetwork/facebook.svg" alt="Facebook" title="Facebook"/></a>
  <a href="https://twitter.com/<?php echo $dataProfile['user']['social']['ID_twitter'];?>" target="_blank"><img src="../img/socialnetwork/twitter.svg" alt="Twitter" title="Twitter"/></a>
  <a href="<?php echo $dataProfile['user']['social']['ID_gplus'];?>" target="_blank"><img src="../img/socialnetwork/google-plus.svg" alt="Google+" title="Google+"/></a>
</div>
<?php
if(!empty($dataProfile['posts'])){
  foreach ($dataProfile['posts'] as $key => $value) {
    echo '<div class="block_post" title=\''.$value['post_title'].'\'>
    <a href="../post/?p='.$value['code_post'].'" id="link"><div id="container_img"><img src="'.$value['post_img'].'"/></div>
    <div id="more"><p id="title">'.$value['post_title'].'</p></div></a></div>';
  }
}else{
  echo '<br><br><p id="txt">Não foi encontrado nem uma publicação!</p><br><br>';
}
echo '</div><p id="copy">'.$InfoCore->location_created.'<br><br>'.$InfoCore->copyright.'</p>';
echo $InfoCore->analyticstracking;
?>

<div class="wikiar-classic-bar">
<a href="" title="MENU" onclick="menu('../'); return false;"><img src="../img/menu.svg" id="img-menu"></a>
<a href="../"><img src="<?php echo '../'.$InfoCore->img_logo;?>" id="logo" alt="<?php echo $InfoCore->name; ?>"/></a>
</div>
<div id="seta_topoMENU"></div><div id="menu_lateral"><br>
<a href="index.php" id="link">PÁGINA INICIAL</a><hr>
<?php
if($se->session_check()==true){
  echo '<div id="container_profile"><a href="./?u='.$_SESSION['username'].'"><img src="'.$_SESSION['user_img'].'" alt="'.$_SESSION['full_name'].'" title="'.$_SESSION['full_name'].'"/><span title="'.$_SESSION['full_name'].'">';
  if(strlen($_SESSION['full_name']) > 18){
    echo substr($_SESSION['full_name'],0,18)."....";
  }else{
    echo $_SESSION['full_name'];
  }
  echo '</span></a></div><hr><a href="../logout.php" id="link">SAIR</a>';
}else{
  echo '<a href="../login/" id="link">ENTRAR</a><a href="../signup/" id="link">INSCREVA-SE</a>';
}
?>
<hr><a href="../dashbord/" id='link'>PAINEL DE ADMINISTRAÇÃO</a>
<a href="../dashbord/new/" id='link'>NOVA PUBLICAÇÃO</a><hr>
<a href="../about/" id='link'>SOBRE - <?php echo strtoupper($InfoCore->name);?></a>
<br>
</div>

<div id="container_login" onClick="show_container_login(0);">
  <p>Faça login para seguir!</p>
  <p><a href="../login/?r=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" id="button">Logar</a></p>
</div>
</body></html>
