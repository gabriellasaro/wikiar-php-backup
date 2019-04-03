<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return 'L';
}
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}
if(is_numeric($_POST['id'])){
  require_once(dirname(dirname(__FILE__))."/lib/data_statistics/recommendation.php");
  $user_recommend = new Recommend($_POST['id'], $_SESSION['user_number']);
  echo $user_recommend->search_list()['status'];
}
return 0;
