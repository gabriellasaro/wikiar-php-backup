<?php
// COPYRIGHT 2017 - Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)).'/includes/info-core.php');
$infoCore = new infoCore();
require_once(dirname(dirname(__FILE__)).'/includes/func-mdl.php');
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title>Sobre - <?php echo $infoCore->name;?></title>
<meta name="COPYRIGHT" content="<?php echo $infoCore->min_copyright;?>">
<meta name="language" content="pt-br">
<meta name="theme-color" content="<?php echo $infoCore->themeColor;?>">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/about/style.css">
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
          <a class="mdl-navigation__link" href="../">Página inicial</a>
        </nav>
      </div>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title"><?php echo $infoCore->name;?></span>
      <?php echo menu_oculto_mdl();?>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
<?php
echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/'.$infoCore->img_logo.'"/>
<p id="title">'.$infoCore->name.' - '.$infoCore->subname.'</p><p>'.$infoCore->description.'</p>
<p style="margin:30px 10px 30px 10px;">Versão: '.$infoCore->version.' - '.$infoCore->dateRelease.'</p>
<p id="copy">'.$infoCore->location_created.'<br><br>'.$infoCore->copyright.'</p>';
?>
      </div>
    </main>
  </div>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
