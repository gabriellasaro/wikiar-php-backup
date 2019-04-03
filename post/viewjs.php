<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.

// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__))."/lib/data_statistics/post-views.php");
$view = new PostViews($_POST['id']);

require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()==true){
  require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/session.php');
  $checkSession = new WaSession();
  if($checkSession->check_session($_SESSION['user_number'], $_SESSION['token'])==1){
    return $view->authenticated_view_counter($_SESSION['user_number']);
  }
  return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr/logout.php');
}
return $view->basic_counter_views();
