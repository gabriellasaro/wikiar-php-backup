<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

if(empty($_GET['p'])){
  return header('Location: http://'.$_SERVER['HTTP_HOST']);
}
if(is_numeric($_GET['p']) and strlen($_GET['p'])==10){
  require_once(dirname(dirname(__FILE__)).'/lib/post/post.php');
  $post = new PostPage($_GET['p']);
  if($post->check() != 1){
    echo 'Page error!<br>Publicação indisponível/não encontrada!';
    exit;
  }
}else{
  echo 'Page error!<br>Problema no endereço!';
  exit;
}
$dataPost = $post->show();
function WikiAr_data($register){
  $data = explode("-", $register);
  switch ($data[1]) {
    case '01':
      $mes = "Janeiro";
      break;
    case '02':
      $mes = "Fevereiro";
      break;
    case '03':
      $mes = "Março";
      break;
    case '04':
      $mes = "Abril";
      break;
    case '05':
      $mes = "Maio";
      break;
    case '06':
      $mes = "Junho";
      break;
    case '07':
      $mes = "Julho";
      break;
    case '08':
      $mes = "Agosto";
      break;
    case '09':
      $mes = "Setembro";
      break;
    case '10':
      $mes = "Outubro";
      break;
    case '11':
      $mes = "Novembro";
      break;
    case '12':
      $mes = "Dezembro";
      break;
  }
  return substr($data[2], 0, 2).' de '.$mes.' de '.$data[0].' as '.substr($data[2], 2, 3).' horas e '.substr($data[2], -5, 2).' minutos (UTC)';
}
$subtitulo = strip_tags($dataPost['post']['post_subtitle']);

require_once(dirname(dirname(__FILE__)).'/includes/info-core.php');
$infoCore = new infoCore();
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title><?php echo $dataPost['post']['post_title']." - ".$infoCore->name;?></title>
<link rel="canonical" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/post/?p='.$_GET['p'];?>">
<meta name="description" content="<?php echo $subtitulo; ?>">
<meta name="Robots" content="INDEX,FOLLOW">
<meta name="RESOURCE-TYPE" content="DOCUMENT">
<meta name="DISTRIBUTION" content="GLOBAL">
<meta name="AUTHOR" content="<?php echo $dataPost['user']['user_nicename'];?>">
<meta name="COPYRIGHT" content="<?php echo $infoCore->min_copyright;?>">
<meta name="language" content="<?php echo $dataPost['post']['post_language']; ?>">
<meta name="RATING" content="GENERAL">
<meta property="og:title" content="<?php echo $dataPost['post']['post_title']; ?>">
<meta property="og:type" content="article">
<meta property="og:url" content="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/post/?p='.$_GET['p'];?>">
<meta property="og:image" itemprop="image" content="<?php echo $dataPost['post']['post_img'];?>">
<meta property="og:site_name" content="<?php echo $infoCore->name." - ".$infoCore->subname;?>">
<meta property="og:description" content="<?php echo $subtitulo; ?>">
<meta property="og:locale" content="<?php echo $dataPost['post']['post_language']; ?>">
<meta property="og:image" itemprop="image" content="<?php echo $dataPost['post']['post_img'];?>">
<meta name="theme-color" content="<?php echo $infoCore->themeColor;?>">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/core/css/menu.css">
<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/post/css/style.css">
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/core/js/classic_bar.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/post/js/views.js"></script>';
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head><body>
<!-- MENU PRINCIPAL -->
<div class="wikiar-classic-bar" style="position:relative;">
<a href="" title="MENU" onclick="menu('../'); return false;"><img src="../img/menu.svg" id="img-menu"></a>
<a href="../"><img src="<?php echo '../'.$infoCore->img_logo;?>" id="logo" alt="<?php echo $infoCore->name; ?>"/></a>
</div>
<!-- DESENVOLVIMENTO DO POST -->
<?php
// ÁREA DE TÍTULO
echo '<div class="container-title">
<h1>'.$dataPost['post']['post_title'].'</h1>
<h3>'.$dataPost['post']['post_subtitle'].'</h3>';
if(strlen($dataPost['post']['post_img']) >= 1){
  echo "<img src='".$dataPost['post']['post_img']."'/>";
}
echo '</div>';
// FIM ÁREA DE TÍTULO
// CONTAINER PROFILE
echo '<div class="container-profile">
<div class="avatar">
<a href="../user/?u='.urlencode($dataPost['user']['username']).'">
<img src="'.$dataPost['user']['user_img'].'" title="'.$dataPost['user']['user_nicename'].'"/></a></div>
<div class="name">
<p><a href="../user/?u='.urlencode($dataPost['user']['username']).'">Gabriel Lasaro</a></p>
<p class="sub">'.WikiAr_data($dataPost['post']['post_modified']).'</p>
<p class="sub read">'.$dataPost['post']['reading_time'].' min. de leitura</p>
</div>
</div>';

// CONTAINER TOOLBAR
echo '<div class="container-toolbar">
<div class="block" title="Acessos">
<span>'.$dataPost['post']['post_accesses'].'</span>
<img src="../img_material_icons/ic_visibility_white_48px.svg"/>
</div>
<div class="block" title="Recomendações">
<span>'.$dataPost['post']['post_recommend'].'</span>
<img src="../img_material_icons/ic_star_border_white_48px.svg" style="cursor:pointer;" id="img_r" title="RECOMENDAR" onClick="frecomendar(\''.$_GET['p'].'\');"/>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/post/js/frecomendar.js"></script>

</div>';
// <div class="block"><a href="#" target="__blank" title="COMPARTILHAR"><img src="../img_material_icons/ic_share_white_48px.svg"/></a></div>
// FIM CONTAINER TOOLBAR
// CONTAINER WIKI
echo '<div class="container-wiki"><span class="first-letter">'.substr(strip_tags($dataPost['post']['post_content']), 0, 1).'</span>'.$dataPost['post']['post_content'].'</div>';
// FIM CONTAINER WIKI

// COPYRIGHT
echo '<p id="copy">'.$infoCore->location_created.'<br><br>'.$infoCore->copyright.'</p>';
?>
<div class="container-login"><p class="close" onClick="closeClass();">X</p>
<img src="../img/WikiAr_logo.svg" alt="WikiAr">
<p class="title"><?php echo $infoCore->description;?></p>
<a class="button" href="http://<?php echo $_SERVER['HTTP_HOST'];?>/WikiAr/login/">Entrar</a></div>

<div id="seta_topoMENU"></div><div id="menu_lateral"><br>
<a href="index.php" id="link">PÁGINA INICIAL</a><hr>
<?php
if($se->session_check()==true){
  echo '<div id="container_profile"><a href="../user/?u='.$_SESSION['username'].'"><img src="'.$_SESSION['user_img'].'" alt="'.$_SESSION['full_name'].'" title="'.$_SESSION['full_name'].'"/><span title="'.$_SESSION['full_name'].'">';
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
<a href="../about/" id='link'>SOBRE - <?php echo strtoupper($infoCore->name);?></a>
<br></div>
<script>
sR('<?php echo $_GET['p'];?>');
vcounter('<?php echo $_GET['p'];?>');
</script>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
