<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__)))."/lib/wa_manager/local_session.php");
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  echo '0';
  exit;
}
require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  echo '0';
  exit;
}

if(isset($_POST['id'])){
  if(is_numeric($_POST['id']) && strlen($_POST['id'])==10){
    require_once(dirname(dirname(dirname(__FILE__))).'/lib/post/post-editor.php');
    $publish = new PostEditor();
    echo $publish->publish($_SESSION['user_number'], $_POST['id']);
    exit;
  }
  echo '0';
  exit;
}
echo '0';
exit;
