<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.

ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return '2';
}
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}


if(isset($_POST['user_followed'], $_POST['type']) && $_POST['type']==1){
  require_once(dirname(dirname(__FILE__)).'/lib/profile/follow.php');
  $follow = new WaFollow($_SESSION['user_number'], $_POST['user_followed']);
  echo $follow->follow_user();
}else{
  require_once(dirname(dirname(__FILE__)).'/lib/profile/follow.php');
  $unfollow = new WaFollow($_SESSION['user_number'], $_POST['user_followed']);
  echo $unfollow->unfollow();
}
