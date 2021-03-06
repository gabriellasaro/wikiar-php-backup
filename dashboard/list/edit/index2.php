<?php
// COPYRIGHT 2016-2017 - Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

if(!is_numeric($_GET['p']) && strlen($_GET['p'])!=10){
  return header('Location: ../../list/');
}

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/list/edit/?p='.$_GET['p']));
}

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/post/post-editor.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/tags/tags.php');
$tags = new WaTags();
$getData = new PostEditor();
$dataPost = $getData->get_publication_data($_SESSION['user_number'], $_GET['p'], "wa_archived_posts.post_title, wa_archived_posts.post_subtitle, wa_archived_posts.post_img, wa_linker.post_category, wa_linker.post_classification, wa_linker.post_language, wa_linker.post_license, wa_linker.post_status, wa_linker.post_date, wa_linker.post_last_change");
if($dataPost['status']=='0'){
  echo 'Publicação não encontrada!';
  exit;
}
// print_r($dataPost);

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/includes/info-core.php');
$InfoCore = new InfoCore();
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/includes/func-mdl.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edição rápida - <?php echo $InfoCore->name;?></title>
<meta name="COPYRIGHT" content="<?php echo $InfoCore->min_copyright;?>">
<meta name="language" content="pt-br">
<meta name="theme-color" content="#808080">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/css/dashbord/style.css">
<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.css">
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/js/dashbord/update_dynamic.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/js/editor/others.js"></script>';
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
        <span class="mdl-layout-title"><?php echo $InfoCore->name;?></span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        <!-- Navigation. We hide it in small screens. -->
        <nav class="mdl-navigation mdl-layout--large-screen-only">
          <a class="mdl-navigation__link" href="../../../">Página inicial</a>
        </nav>
      </div>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title"><?php echo $InfoCore->name;?></span>
      <div class="container_profile">
        <a href="../../../user/?u=<?php echo urlencode($_SESSION['username']);?>">
        <center><img src="<?php echo $_SESSION['user_img']; ?>"/></center>
        <p><?php echo $_SESSION['username'];?></p>
        </a>
      </div>
      <?php echo menu_oculto_mdl();?>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
        <br><p id="sub_title">Edição rápida</p>
        <?php
        if(isset($_GET['msg'])){
          echo '<p id="msg" onClick="exitmsg()">'.$_GET['msg'].'</p><br>';
        }
        ?>
        <form>
        <p>Título</p>
        <center><input name="title" type="text" maxlength="200" placeholder="Título da publicação" value="<?php echo $dataPost['data'][0]['post_title']; ?>" autocomplete="off"/></center>
        <p>Subtítulo</p>
        <center><textarea name="subtitle" type="text" maxlength="300" placeholder="Subtítulo (título complementar)"><?php echo $dataPost['data'][0]['post_subtitle']; ?></textarea></center>
        <p>Imagem de capa</p>
        <center><input name="capa" type="text" title="Imagem de capa" placeholder="Capa do post: insira a url da imagem" value="<?php echo $dataPost['data'][0]['post_img']; ?>" autocomplete="off"/></center>
        <br><div class="container_form">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" onClick="update_dynamic('<?php echo $_GET['p']; ?>'); return(false);">Atualizar</button>
        </div><hr>
        <div class="container_form">
        <p class="p_simples p_inline">Idioma: <?php echo $InfoCore->language_acronyms($dataPost['data'][0]['post_language']);?></p><br>
        <?php
        if($dataPost['data'][0]['post_status']=='1'){
          echo '<p class="p_simples p_inline">Status: Público</p><br>';
        }else{
          echo '<p class="p_simples p_inline">Status: Privado</p><br>';
        }
        ?>
      </div><br>
      <hr><p>Tags</p>
      <br>
<?php
foreach ($tags->show_tags_post($_GET['p'], 1) as $key => $value) {
  echo '<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-'.$key.'">
  <input type="checkbox" id="checkbox-'.$key.'" class="mdl-checkbox__input" checked>
  <span class="mdl-checkbox__label">'.$value['tag_name'].'</span>
  </label>';
}
?>

          <!-- <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-2">
          <input type="checkbox" id="checkbox-2" class="mdl-checkbox__input">
          <span class="mdl-checkbox__label">Checkbox</span>
          </label> -->
        </div><br>
        <hr>
        <div class="container_form">
        <p class="p_simples p_inline">Licensa</p><ul>
        <?php
        switch ($dataPost['data'][0]['post_language']){
          case 'BY-SA':
           $license = '<a href="https://creativecommons.org/licenses/by-sa/4.0" target="__blank">Atribuição-CompartilhaIgual CC BY-SA</a>';
           break;
          case 'BY':
           $license = '<a href="https://creativecommons.org/licenses/by/4.0" target="__blank">Atribuição CC BY</a>';
           break;
          case 'BY-ND':
           $license = '<a href="https://creativecommons.org/licenses/by-nd/4.0" target="__blank">Atribuição-SemDerivações CC BY-ND</a>';
           break;
          case 'BY-NC':
           $license = '<a href="https://creativecommons.org/licenses/by-nc/4.0" target="__blank">Atribuição-NãoComercial CC BY-NC</a>';
           break;
          case 'BY-NC-SA':
           $license = '<a href="https://creativecommons.org/licenses/by-nc-sa/4.0" target="__blank">Atribuição-NãoComercial-CompartilhaIgual CC BY-NC-SA</a>';
           break;
          case 'BY-NC-ND':
           $license = '<a href="https://creativecommons.org/licenses/by-nc-nd/4.0" target="__blank">Atribuição-SemDerivações-SemDerivados CC BY-NC-ND</a>';
           break;
          case 'DP':
           $license = 'Domínio público';
           break;
          case 'TDR':
           $license = 'Todos os direitos reservados';
           break;
          default:
           $license = '<a href="https://creativecommons.org/licenses/by-sa/4.0" target="__blank">Atribuição-CompartilhaIgual CC BY-SA</a>';
           break;
        }
        echo '<li>'.$license.'</li>';
        ?>
        </ul></div>
      </form>
        <p id="copy"><?php echo $InfoCore->location_created."<br><br>".$InfoCore->copyright;?></p>
        <div id="container_success">
        <p>Atualizado com sucesso!</p>
        <center><img src="../../../img_material_icons/ic_done_white_64px.svg"/><br><br>
        <a href="../../list/">Lista</a>
        <a href="../../../post/?p=<?php echo $_GET['p'];?>">Acessar post</a>
        <a href="#" onClick="exit_dialog_msg('container_success'); return false;">Editar</a></center>
        </div>
        <div id="container_loading">
        <p>Salvando...</p>
        <!-- MDL Progress Bar with Indeterminate Progress -->
        <div id="p2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
        </div>
        <div id="container_msg" onClick="exit_dialog_msg('container_msg');" title="Clique na mensagem para ocultar!">
        <p id="id_msg_loading">Salvo</p>
        </div>
      </div>
    </main>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<?php
echo $InfoCore->analyticstracking;
?>
</body></html>
