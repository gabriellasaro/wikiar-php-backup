<?php
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()!=true){
  return 0;
}
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/session.php');
$checkSession = new WaSession();
if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==0){
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}
if(!empty($_POST['page_user'])){
    require_once(dirname(dirname(__FILE__))."/lib/profile/follow.php");
    $follower = new WaFollow($_SESSION['user_number'], $_POST['page_user']);
    echo $follower->check_user();
}
