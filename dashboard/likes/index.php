<?php
// COPYRIGHT 2016-2017 - Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/likes/'));
}
require_once(dirname(dirname(dirname(__FILE__))).'/includes/info-core.php');
require_once(dirname(dirname(dirname(__FILE__))).'/includes/func-mdl.php');
$infoCore = new infoCore();
require_once(dirname(dirname(dirname(__FILE__)))."/lib/profile/profile.php");
$profile = new WaProfile();
$recommend = $profile->my_recommendations($_SESSION['user_number']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Painel - <?php echo $infoCore->name;?></title>
<meta name="COPYRIGHT" content="<?php echo $infoCore->min_copyright;?>">
<meta name="language" content="pt-br">
<meta name="theme-color" content="<?php echo $infoCore->themeColor;?>">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/dashboard/css/style.css">
<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.css">
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.js"></script>';
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
  <!-- Always shows a header, even in smaller screens. -->
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title"><?php echo $infoCore->name;?></span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        <!-- Navigation. We hide it in small screens. -->
        <nav class="mdl-navigation mdl-layout--large-screen-only">
          <a class="mdl-navigation__link" href="../../">Página inicial</a>
        </nav>
      </div>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title"><?php echo $infoCore->name;?></span>
      <div class="container_profile">
        <a href="../../user/?u=<?php echo urlencode($_SESSION['username']);?>">
        <center><img src="<?php echo $_SESSION['user_img']; ?>"/></center>
        <p><?php echo $_SESSION['username'];?></p>
        </a>
      </div>
<?php echo menu_oculto_mdl();?>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
          <br><p id="sub_title">Publicações que recomendo</p>
        <?php
        if(count($recommend)==0){
          echo '<p class="msg">Sem publicações recomendadas!</p><br>
          <center><a href="../new/" class="button"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Criar publicação
          </button></a></center>';
        }else{
          foreach ($recommend as $value) {
            echo '<div class="container_post"><a href="../../post/?p='.$value['code_post'].'" target="__blank">
            <p>'.$value['post_title'].'</p></a></div>';
          }
        }
        ?>
        <p id="copy"><?php echo $infoCore->location_created."<br><br>".$infoCore->copyright;?></p>
      </div>
    </main>
  </div>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
