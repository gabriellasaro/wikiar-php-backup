<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
require_once(dirname(__FILE__).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->logout();
header('Location: index.php');
