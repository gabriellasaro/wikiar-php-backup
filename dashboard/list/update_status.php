<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return 0;
}
require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}
if(is_numeric($_POST['id'])){
  require_once(dirname(dirname(dirname(__FILE__)))."/lib/post/post_editor.php");
  $update = new PostEditor();
  if($_POST['status']=='D'){
    echo $update->update_post_status($_SESSION['user_number'], $_POST['id'], 'D');
    exit;
  }else{
    echo $update->update_post_status($_SESSION['user_number'], $_POST['id'], '0');
    exit;
  }
}
return 0;
