<?php
// COPYRIGHT 2016-2017 - Gabriel Lasaro, Todos os direitos reservados.

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/settings/'));
}
include_once(dirname(dirname(dirname(__FILE__))).'/lib/profile/profile.php');
$profile = new WaProfile();
$profile_data = $profile->info_profile('user_nicename, user_img, user_description, user_birthday, user_address, user_status, verified_user, user_language, user_sex', $_SESSION['user_number'], 1);
include_once(dirname(dirname(dirname(__FILE__))).'/includes/info-core.php');
$infoCore = new infoCore();
require_once(dirname(dirname(dirname(__FILE__))).'/includes/func-mdl.php');
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
      <div class="page-content"><br>
        <p id="sub_title">Configurações</p><br>
        <form>
        <p>Imagem de perfil:</p>
        <center><input name="p_img" type="file" maxlength="200" placeholder="Imagem de perfil..." value="" autocomplete="off"/></center>
        <hr><p>Nome completo:</p>
        <center><input name="name" type="text" maxlength="200" placeholder="Nome completo..." value="<?php echo $profile_data['user_nicename'];?>" autocomplete="off"/></center>
        <p>Descrição:</p>
        <center><textarea name="description" type="text" maxlength="300" placeholder="Descrição..."><?php echo $profile_data['user_description'];?></textarea></center>
        <div class="container_form">
        <p class="p_simples p_inline">Idioma:</p>
        <select style="margin:10px 5px 10px 10px;" name="language" value=""/>
          <?php
          echo '<option value="'.$_SESSION['language'].'" selected="selected">'.$infoCore->language_acronyms($profile_data['user_language']).'</option>';
          ?>
          <option value="pt-br" selected="selected">Português do Brasil</option>
          <option value="pt">Português</option>
          <option value="en">English</option>
          <option value="fr">Français</option>
          <option value="it">Italiano</option>
          <option value="de">Deutsch</option>
          <option value="es">Español</option>
          <option value="ca">Català</option>
          <option value="vi">Tiếng Việt</option>
          <option value="id">Bahasa Indonesia</option>
          <option value="ms">Bahasa Melayu</option>
          <option value="ru">Русский</option>
          <option value="ro">Română</option>
          <option value="tr">Türkçe</option>
          <option value="ja">日本語</option>
          <option value="zh">中文</option>
          <option value="ar">العربية</option>
        </select>
      </div><hr>
      <?php if($profile_data['user_status']=='1'){echo '<p style="color:#4169E1;">Perfil verificado!</p>';}else{echo '<p style="color:#B22222;">Perfil não verificado!</p>';}?>
       <hr><p>Redes Sociais:</p>
         <?php
         if($profile_data['social']['sn_status']=='1'){
           echo '<p>YouTube:</p><center><input name="name" type="text" maxlength="200" placeholder="Username YouTube" value="'.$profile_data['social']['ID_youtube'].'" autocomplete="off"/></center>
           <p>Google+:</p><center><input name="name" type="text" maxlength="200" placeholder="Username Google+" value="'.$profile_data['social']['ID_gplus'].'" autocomplete="off"/></center>
           <p>Facebook:</p><center><input name="name" type="text" maxlength="200" placeholder="Username Facebook" value="'.$profile_data['social']['ID_facebook'].'" autocomplete="off"/></center>
           <p>Twitter:</p><center><input name="name" type="text" maxlength="200" placeholder="Username Twitter" value="'.$profile_data['social']['ID_twitter'].'" autocomplete="off"/></center>
           <p>URL Doar:</p><center><input name="name" type="text" maxlength="200" placeholder="Endereço de site de doações... (URL)" value="'.$profile_data['social']['ID_donate'].'" autocomplete="off"/></center>';
         }else{
           echo '<p>YouTube:</p><center><input name="name" type="text" maxlength="200" placeholder="Username YouTube" value="" autocomplete="off"/></center>
           <p>Google+:</p><center><input name="name" type="text" maxlength="200" placeholder="Username Google+" value="" autocomplete="off"/></center>
           <p>Facebook:</p><center><input name="name" type="text" maxlength="200" placeholder="Username Facebook" value="" autocomplete="off"/></center>
           <p>Twitter:</p><center><input name="name" type="text" maxlength="200" placeholder="Username Twitter" value="" autocomplete="off"/></center>
           <p>URL Doar:</p><center><input name="name" type="text" maxlength="200" placeholder="Endereço de site de doações... (URL)" value="" autocomplete="off"/></center>';
         }
         ?>
       </form>
       <p id="copy"><?php echo $infoCore->location_created."<br><br>".$infoCore->copyright;?></p>
      </div>
    </main>
  </div>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
