<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/list/'));
}
require_once(dirname(dirname(dirname(__FILE__))).'/includes/info-core.php');
require_once(dirname(dirname(dirname(__FILE__))).'/includes/func-mdl.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib/profile/profile.php');
$infoCore = new infoCore();
$profile = new WaProfile();
$profile = $profile->list_user_publications($_SESSION['user_number']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Minhas publicações - <?php echo $infoCore->name;?></title>
<meta name="COPYRIGHT" content="<?php echo $infoCore->min_copyright;?>">
<meta name="language" content="pt-br">
<meta name="theme-color" content="<?php echo $infoCore->themeColor;?>">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/dashboard/css/style.css">
<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.css">
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/dashboard/js/status.js"></script>';
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head><body>
<!-- Simple header with scrollable tabs. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title"><?php echo $infoCore->name;?></span>
    </div>
    <!-- Tabs -->
    <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
      <a href="#scroll-tab-1" class="mdl-layout__tab is-active">Públicas</a>
      <a href="#scroll-tab-2" class="mdl-layout__tab">Privadas</a>
      <a href="#scroll-tab-3" class="mdl-layout__tab">Lixeira</a>
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
    <section class="mdl-layout__tab-panel is-active" id="scroll-tab-1">
      <div class="page-content"><br><br>
        <?php
        if($profile['status']=='0'){
          echo '<p class="msg">Sem publicações!</p><br>
          <center><a href="../new/" class="button"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Criar publicação
          </button></a></center>';
        }else{
          for($i=0;$i<=count($profile['data'])-1;$i++){
            if($profile['data'][$i]['post_status']=='1'){
              echo '<div class="container_post"><a href="../../post/?p='.$profile['data'][$i]['code_post'].'" target="__blank">
              <p>'.$profile['data'][$i]['post_title'].'</p></a>
              <div id="tools"><hr><ul><li><a href="../editor/?p='.$profile['data'][$i]['code_post'].'">Editar</a></li>
              <li><a href="./edit/?p='.$profile['data'][$i]['code_post'].'">Edição rápida</a></li>
              <li id="status" onClick="update_status('.$profile['data'][$i]['code_post'].', \'D\');">Deletar</li></ul></div></div>';
            }
          }
        }
        ?>
        <p id="copy"><?php echo $infoCore->location_created."<br><br>".$infoCore->copyright;?></p>
      </div>
    </section>
    <section class="mdl-layout__tab-panel" id="scroll-tab-2">
      <div class="page-content"><br><br>
        <?php
        if($profile['status']=='0'){
          echo '<p class="msg">Sem publicações!</p><br>
          <center><a href="../new/" class="button"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Criar publicação
          </button></a></center>';
        }else{
          for($i=0;$i<=count($profile['data'])-1;$i++){
            if($profile['data'][$i]['post_status']=='0'){
              echo '<div class="container_post"><p>'.$profile['data'][$i]['post_title'].'</p>
              <div id="tools"><hr><ul><li><a href="../editor/?p='.$profile['data'][$i]['code_post'].'">Editar</a></li>
              <li><a href="./edit/?p='.$profile['data'][$i]['code_post'].'">Edição rápida</a></li>
              <li id="status" onClick="update_status('.$profile['data'][$i]['code_post'].', \'D\');">Deletar</li></ul></div></div>';
            }
          }
        }
        ?>
      </div>
    </section>
    <section class="mdl-layout__tab-panel" id="scroll-tab-3">
      <div class="page-content"><br><br>
        <?php
        if($profile['status']=='0'){
          echo '<p class="msg">Sem publicações!</p><br>
          <center><a href="../new/" class="button"><button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Criar publicação
          </button></a></center>';
        }else{
          for($i=0;$i<=count($profile['data'])-1;$i++){
            if($profile['data'][$i]['post_status']=='D'){
              echo '<div class="container_post"><p>'.$profile['data'][$i]['post_title'].'</p>
              <div id="tools"><hr><ul><li><a href="../editor/?p='.$profile['data'][$i]['code_post'].'">Editar</a></li>
              <li><a href="./edit/?p='.$profile['data'][$i]['code_post'].'">Edição rápida</a></li>
              <li id="status" onClick="update_status('.$profile['data'][$i]['code_post'].', \'0\');">Restaurar</li>
              </ul></div></div>';
            }
          }
        }
        ?>
      </div>
    </section>
  </main>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
