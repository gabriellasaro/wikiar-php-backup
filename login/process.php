<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

echo 'Processando...';

if(!$_POST['email'] | !$_POST['password']){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?m='.urlencode('Dados incompletos!'));
}
if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST['email'])) {
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?m='.urlencode('E-mail ou senha incorretos!'));
}
if(!preg_match('/^[a-zA-Z0-9_.!@#,$;]{4,100}$/', $_POST['password'])){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/login/?m='.urlencode('E-mail ou senha incorretos!'));
}

require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/session.php');
$login = new WaSession();

if(isset($_GET['r'])){
  if($login->start($_POST['email'], $_POST['password'])==1){
    return header('Location: '.urldecode($_GET['r']));
  }else{
    return header('Location: index.php?m='.urlencode('E-mail ou senha incorretos!'));
  }
}else{
  if($login->start($_POST['email'], $_POST['password'])==1){
    return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr');
  }else{
    return header('Location: index.php?m='.urlencode('E-mail ou senha incorretos!'));
  }
}
