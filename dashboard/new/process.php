<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?r='.urlencode('http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashbord/new/'));
}
require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}

if(empty($_POST['title'])){
  return header("Location: index.php?msg=".urlencode('Preencha os campos!'));
}
if(empty($_POST['subtitle'])){
  return header("Location: index.php?msg=".urlencode('Preencha os campos!'));
}
if(strlen($_POST['subtitle'])>300){
  return header('Location: index.php?msg='.urlencode('Você ultrapassou o limite do campo subtítulo! (300 caracteres)'));
}
if(strlen($_POST['title'])>200){
  return header('Location: index.php?msg='.urlencode('Você ultrapassou o limite do campo título! (120 caracteres)'));
}
require_once(dirname(dirname(dirname(__FILE__))).'/lib/post/post-editor.php');
$editor = new PostEditor();
$resultEditor = $editor->new_publication($_SESSION['user_number'], $_POST['title'], $_POST['subtitle'], $_POST['img'], $_POST['cla'], $_POST['category'], $_POST['lang'], $_POST['license']);
if($resultEditor['status']=='1'){
  return header('Location: ../editor/?p='.$resultEditor['code_post']);
}
return header("Location: index.php?msg=".urlencode('Erro ao criar post!'));
