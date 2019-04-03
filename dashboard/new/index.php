<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/new/'));
}
require_once(dirname(dirname(dirname(__FILE__))).'/includes/info-core.php');
$infoCore = new infoCore();
require_once(dirname(dirname(dirname(__FILE__))).'/includes/func-mdl.php');
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title><?php echo "Nova publicação - ".$infoCore->name;?></title>
<meta name="COPYRIGHT" content="Copyright (c) Gabriel Lasaro">
<meta name="language" content="pt-br">
<meta name="theme-color" content="<?php echo $infoCore->themeColor;?>">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/dashboard/css/style.css">
<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.css">
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/dashboard/js/waforms.js"></script>';
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
      <?php echo menu_oculto_mdl(); ?>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
        <br><p id="sub_title">Nova publicação</p>
        <?php
        if(isset($_GET['msg'])){
          echo '<p id="msg" onClick="exitmsg()">'.$_GET['msg'].'</p><br>';
        }
        ?>
        <form method="post" action="process.php">
        <p>Título</p>
        <center><input name="title" type="text" onkeydown="character_limit(200, 'title', 'limit0');" maxlength="200" placeholder="Título da publicação" value="" autocomplete="off"/></center>
        <p id="limit0"></p>
        <p>Subtítulo</p>
        <center><textarea name="subtitle" type="text" onkeydown="character_limit(300, 'subtitle', 'limit1');" maxlength="300" placeholder="Subtítulo (título complementar)"></textarea></center>
        <p id="limit1"></p>
        <p>Imagem de capa</p>
        <!-- <div id="div_insert_img"><a href="#" onClick="open_dialog_msg('container_img'); return false;">Inserir imagem</a></div> -->
        <!-- <div id="container_img">
        <p class="p_simples">Biblioteca de imagens</p> -->
        <center><input name="img" type="text" title="Imagem de capa" placeholder="Capa do post: insira a url da imagem" value="" autocomplete="off"/></center>
        <!-- <br><a href="#" class="button_default" onClick="exit_dialog_msg('container_img'); return false;">OK</a>
        </div><hr> -->
        <hr><div class="container_form">
          <p class="p_simples p_inline">Categoria:</p>
          <select style="margin:10px 5px 10px 10px;" name="category" value=""/>
          <option value="30">Filmes e Desenhos</option>
          <option value="40">Automóveis</option>
          <option value="50">Música</option>
          <option value="60">Animais</option>
          <option value="70">Esportes</option>
          <option value="80">Viagens e eventos</option>
          <option value="90">Jogos</option>
          <option value="11">Pessoas e blogs</option>
          <option value="12">Comédia</option>
          <option value="13">Entretenimento</option>
          <option value="10" selected="selected">Notícias</option>
          <option value="20">Política</option>
          <option value="14">Guias e estilo</option>
          <option value="15">Educação</option>
          <option value="16">Ciências e tecnologia</option>
        </select>

        <p class="p_simples p_inline">Classificação:</p>
        <select name="cla">
          <option value="0" checked>Livre</option>
          <option value="1">Maiores de 18+</option>
        </select>

        <p class="p_simples p_inline">Idioma:</p>
        <select style="margin:10px 5px 10px 10px;" name="lang" value=""/>
          <?php
          echo '<option value="'.$_SESSION['user_lang'].'" selected="selected">'.$infoCore->language_acronyms($_SESSION['user_lang']).'</option>';
          ?>
          <option value="pt-br">Português do Brasil</option>
          <option value="pt">Português</option>
          <option value="en">English</option>
          <option value="fr">Français</option>
          <option value="it">Italiano</option>
          <option value="de">Deutsch</option>
          <option value="es">Español</option>
          <option value="ca">Català</option>
          <option value="id">Bahasa Indonesia</option>
          <option value="ms">Bahasa Melayu</option>
          <option value="ru">Русский</option>
          <option value="ro">Română</option>
          <option value="tr">Türkçe</option>
          <option value="ja">日本語</option>
          <option value="zh">中文</option>
        </select>

        </div>
        <hr>
        <div class="container_form">
        <p class="p_simples p_inline">Direitos autorais:</p>
        <select name="license">
        <option value="BY-SA">Atribuição-CompartilhaIgual CC BY-SA - https://creativecommons.org/licenses/by-sa/4.0</option>
        <option value="BY">Atribuição CC BY - https://creativecommons.org/licenses/by/4.0</option>
        <option value="BY-ND">Atribuição-SemDerivações CC BY-ND - https://creativecommons.org/licenses/by-nd/4.0</option>
        <option value="BY-NC">Atribuição-NãoComercial CC BY-NC - https://creativecommons.org/licenses/by-nc/4.0</option>
        <option value="BY-NC-SA">Atribuição-NãoComercial-CompartilhaIgual CC BY-NC-SA - https://creativecommons.org/licenses/by-nc-sa/4.0</option>
        <option value="BY-NC-ND">Atribuição-SemDerivações-SemDerivados CC BY-NC-ND - https://creativecommons.org/licenses/by-nc-nd/4.0</option>
        <!-- <option value="TDR">Todos os direitos reservados</option> -->
        <option value="DP">Domínio público</option>
        </select>
        </div><hr>

        <div class="container_form">
        <input class="button_default button_input_complement" type="submit" value="PRÓXIMA ETAPA"/>
        </div><br><br>
        </form>
        <p id="copy"><?php echo $infoCore->location_created."<br><br>".$infoCore->copyright;?></p>
      </div>
    </main>
  </div>
<?php
echo $infoCore->analyticstracking;
?>
</body></html>
