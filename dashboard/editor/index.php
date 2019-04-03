<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/editor/?p='.$_GET['p']));
}
require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/new/'));
}
if(empty($_GET['p'])){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/list/');
}
if(!is_numeric($_GET['p']) && strlen($_GET['p'])!=10){
  echo 'Publicação não encontrada!';
  exit;
}

require_once(dirname(dirname(dirname(__FILE__)))."/lib/post/post-editor.php");
$getData = new PostEditor();
$dataPost = $getData->get_publication_data($_SESSION['user_number'], $_GET['p'], "wa_archived_posts.post_title, wa_archived_posts.post_content, wa_archived_posts.post_version");
if($dataPost['status']=='0'){
  echo 'Publicação não encontrada!';
}
// print_r($dataPost);

require_once(dirname(dirname(dirname(__FILE__))).'/includes/info-core.php');
$InfoCore = new InfoCore();

function wiki_process($content){
    if($content != '0'){
      return $content;
    }
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title><?php echo $InfoCore->name;?> Editor Beta</title>
<meta http-equiv="content-language" content="pt-br" />
<meta name="theme-color" content="#808080">
<meta name="viewport" content="width=device-width">
<?php
echo '<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/css/editor/editor.css">
<link rel="stylesheet" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.css">
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/mdl/material.min.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/js/editor/others.js"></script>
<script src="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/templates/js/editor/save.js"></script>';
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<div id="wysihtml-toolbar" class="toolbar">
  <div class="block">
    <a href="../list/"><img src="../../img/voltar_wikiar_white.svg" alt="Lista de postagens" title="Lista de postagens"/></a>
    <img src="../../img_material_icons/editor/ic_backup_white_48px.svg" alt="SALVAR" title="SALVAR (privado)" onClick="save('<?php echo $_GET['p'];?>');"/>
    <img src="../../img_material_icons/ic_more_vert_white_48px.svg" alt="PUBLICAR" title="Publicar" onClick="open_menu('menusave');"/>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <a data-wysihtml-command="bold" title="Negrito (CTRL+B)"><img src="../../img_material_icons/editor/ic_format_bold_white_48px.svg" id="botao-img"/></a>
    <a data-wysihtml-command="italic" title="Itálico (CTRL+I)"><img src="../../img_material_icons/editor/ic_format_italic_white_48px.svg" id="botao-img"/></a>
    <a data-wysihtml-command="underline" title="Sublinhado (CTRL+U)"><img src="../../img_material_icons/editor/ic_format_underlined_white_48px.svg"/></a>
    <!-- <a data-wysihtml-command="superscript" title="sobrescrito">sobrescrito</a> -->
    <!-- <a data-wysihtml-command="subscript" title="subscrito">subscrito</a> -->

    <a data-wysihtml-command="createLink" title="Inserir link"><img src="../../img_material_icons/editor/ic_insert_link_white_48px.svg"/></a>
    <a data-wysihtml-command="removeLink" title="Remove link"><img src="../../img_material_icons/editor/ic_insert_link_white_48px.svg"/></a>
    <a data-wysihtml-command="insertImage" title="Inserir imagem"><img src="../../img_material_icons/ic_insert_photo_white_48px.svg"/></a>
    <img src="../../img_material_icons/editor/ic_text_fields_white_48px.svg" title="Tipos de formatação" onClick="open_menu('menutxt');"/>
    <a data-wysihtml-command="insertBlockQuote" title="Bloco de citação"><img src="../../img_material_icons/editor/ic_format_quote_white_48px.svg"/></a>
    <a data-wysihtml-command="formatCode" data-wysihtml-command-value="language-html" title="Código"><img src="../../img_material_icons/ic_code_white_48px.svg"/></a>

    <a data-wysihtml-command="insertUnorderedList" title="Marcadores circulares"><img src="../../img_material_icons/editor/ic_format_list_bulleted_white_48px.svg"/></a>
    <a data-wysihtml-command="insertOrderedList" title="Lista numérica"><img src="../../img_material_icons/editor/ic_format_list_numbered_white_48px.svg"/></a>
  </div>

  <div class="block">
    <a data-wysihtml-command="alignLeftStyle" title="ALinhar à esquerda"><img src="../../img_material_icons/editor/ic_format_align_left_white_48px.svg"/></a>
    <a data-wysihtml-command="alignRightStyle" title="Alinhar à direita"><img src="../../img_material_icons/editor/ic_format_align_right_white_48px.svg"/></a>
    <a data-wysihtml-command="alignCenterStyle" title="Alinhar ao centro"><img src="../../img_material_icons/editor/ic_format_align_center_white_48px.svg"/></a>

    <a data-wysihtml-command="foreColorStyle" title="Cor da fonte"><img src="../../img_material_icons/editor/ic_format_color_text_white_48px.svg"/></a>
    <div id="container_dialog" class="container_form" data-wysihtml-dialog="foreColorStyle" style="display: none;">
      <span>Cor (RGB):</span>
      <input type="text" data-wysihtml-dialog-field="color" value="rgba(0,0,0,1)" placeholder="rgba(0,0,0,1);"/><br>
      <a data-wysihtml-dialog-action="save" class="button_default">OK</a>&nbsp;<a data-wysihtml-dialog-action="cancel" class="button_default">Cancelar</a>
    </div>

    <a data-wysihtml-command="bgColorStyle" title="Cor de fundo"><img src="../../img_material_icons/editor/ic_format_color_fill_white_48px.svg"/></a>
    <div id="container_dialog" class="container_form" data-wysihtml-dialog="bgColorStyle" style="display: none;">
      <span>Cor (RGB):</span>
      <input type="text" data-wysihtml-dialog-field="color" value="rgba(0,0,0,1)" placeholder="rgba(0,0,0,1);"/><br>
      <a data-wysihtml-dialog-action="save" class="button_default">OK</a>&nbsp;<a data-wysihtml-dialog-action="cancel" class="button_default">Cancel</a>
    </div>

    <a data-wysihtml-command="undo" title="Desfazer"><img src="../../img_material_icons/ic_undo_white_48px.svg"/></a>
    <a data-wysihtml-command="redo" title="Refazer"><img src="../../img_material_icons/ic_redo_white_48px.svg"/></a>

    <a data-wysihtml-action="change_view" title="Mostrar HTML"><img src="../../img_material_icons/editor/ic_inbox_white_48px.svg"/></a>
  </div>

  <div id="container_dialog" class="container_form" data-wysihtml-dialog="createLink" style="display: none;">
    <label>
      Link:
      <input data-wysihtml-dialog-field="href" value="" placeholder="https://">
    </label><br>
    <a data-wysihtml-dialog-action="save" class="button_default">OK</a>&nbsp;<a data-wysihtml-dialog-action="cancel" class="button_default">Cancelar</a>
  </div>
  <div id="container_dialog" class="container_form" data-wysihtml-dialog="insertImage" style="display: none;">
    <label>
      Imagem:
    <input data-wysihtml-dialog-field="src" value="" placeholder="https://">
    </label>
    <label>
      Alinhar:
      <select data-wysihtml-dialog-field="className">
        <option value="">Padrão</option>
        <option value="wysiwyg-float-left">Esquerda</option>
        <option value="wysiwyg-float-right">Direira</option>
      </select>
    </label><br>
    <a data-wysihtml-dialog-action="save" class="button_default">OK</a>&nbsp;<a data-wysihtml-dialog-action="cancel" class="button_default">Cancelar</a>
  </div>
  <div class="submenu dialog-txt" id="menutxt">
    <ul>
      <li data-wysihtml-command="formatBlock" data-wysihtml-command-value="h1" title="Título (H1)" onClick="open_menu('menutxt');">Título (H1)</li>
      <li data-wysihtml-command="formatBlock" data-wysihtml-command-value="h2" title="Título (H2)" onCLick="open_menu('menutxt');">Título (H2)</li>
      <li data-wysihtml-command="formatBlock" data-wysihtml-command-value="h3" title="Título (H3)">Título (H3)</li>
      <li data-wysihtml-command="formatBlock" data-wysihtml-command-value="p" onCLick="open_menu('menutxt');">Texto (p)</li>
      <li data-wysihtml-command="formatBlock" data-wysihtml-command-value="pre" onCLick="open_menu('menutxt');">Texto (pre)</li>
      <li data-wysihtml-command="formatBlock" data-wysihtml-command-blank-value="true" onCLick="open_menu('menutxt');">Texto simples</li>
    </ul>
  </div>
</div> <!-- toolbar -->
<br>

<form>
<textarea id="wysihtml-textarea" placeholder="Enter your text ..." autofocus><?php echo wiki_process($dataPost['data'][0]['post_content']); ?></textarea>
</form>
<div id="wysihtml-div" data-placeholder="Enter your text ..."></div>

<small>Versão modificada.<br>powered by <a href="https://github.com/Voog/wysihtml" target="_blank">wysihtml</a>.</small>
<script src="./dist/wysihtml.js"></script>
<script src="./dist/wysihtml.all-commands.js"></script>
<script src="./dist/wysihtml.table_editing.js"></script>
<script src="./dist/wysihtml.toolbar.js"></script>

<script src="./parser_rules/advanced_and_extended.js"></script>

<script>
var editor = new wysihtml.Editor("wysihtml-textarea", { // id of textarea element
  toolbar:      "wysihtml-toolbar", // id of toolbar element
  parserRules:  wysihtmlParserRules // defined in parser rules set
});
</script>

<div class="submenu" id="menusave">
  <ul>
    <li onClick="publish('<?php echo $_GET['p'];?>'); open_menu('menusave');">Publicar última versão</li>
  </ul>
</div>
<div id="container_success">
<p>Publicado com sucesso!</p>
<center><img src="../../img_material_icons/ic_done_white_64px.svg"/><br><br>
<a href="../../post/?p=<?php echo $_GET['p'];?>">Acessar</a>
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
</body>
</html>
