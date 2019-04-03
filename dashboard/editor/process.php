<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// require_once(dirname(dirname(dirname(__FILE__)))."/lib/tags/tags.php");
// $tag = new WaTags();
// $tag->new_tag($statusEditor['code_post'], $_POST['tags']);

require_once(dirname(dirname(dirname(__FILE__)))."/lib/wa_manager/local_session.php");
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  echo 'L';
  exit;
}
require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  echo 'L';
  exit;
}

if(isset($_POST['id'])){
  if(is_numeric($_POST['id']) && strlen($_POST['id'])==10){
    require_once(dirname(dirname(dirname(__FILE__))).'/lib/post/post-editor.php');
    $postEditor = new PostEditor();
    if(empty($_POST['wiki'])){
      echo 'F';
      exit;
    }
    if($postEditor->save_backup($_SESSION['user_number'], $_POST['id'], $_POST['wiki']) == 1){
      echo '1';
      exit;
    }
    echo '0';
    exit;
  }
  echo 'F';
  exit;
}
echo 'F';
exit;
