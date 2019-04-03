<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return 0;
}
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}
if(isset($_POST['id'], $_POST['title'], $_POST['subtitle'], $_POST['capa'])){

  if(strlen($_POST['id'])!=10){
    return 0;
  }
  if(strlen($_POST['subtitle'])>300){
    return 0;
  }
  if(strlen($_POST['title'])>200){
    return 0;
  }

  if(is_numeric($_POST['id'])){
    require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/post/post-editor.php');
    $editor = new PostEditor();
    $data = array(
      'title'=>$_POST['title'],
      'subtitle'=>$_POST['subtitle'],
      'img'=>$_POST['capa']
    );
    echo $editor->fast_post_update($_SESSION['user_number'], $_POST['id'], $data);
    exit;
  }
  return 0;
}
return 0;
