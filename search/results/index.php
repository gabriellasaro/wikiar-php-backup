<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
if(empty($_GET['q'])){
  return header('Location: ../');
}
require_once(dirname(dirname(dirname(__FILE__))).'/includes/info-core.php');
$InfoCore = new InfoCore();
require_once(dirname(dirname(dirname(__FILE__))).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();

$inicio = microtime( true );
require_once(dirname(dirname(dirname(__FILE__))).'/lib/search/wa_search.php');
$search = new WaSearch();
$results = $search->search($_GET['q']);
$fim = microtime( true );
$time = $fim - $inicio;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Search - <?php echo $InfoCore->name.", ".$InfoCore->subname;?></title>
<meta http-equiv="content-language" content="pt-br" />
<meta name="COPYRIGHT" content="<?php echo $InfoCore->min_copyright;?>">
<meta name="theme-color" content="#808080">
<link rel="stylesheet" href="../../css/menu/menu.css">
<link rel="stylesheet" href="../../css/search/style.css">
<meta name="viewport" content="width=device-width">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
</head>
<body>
<div class="search-bar">
  <a href="../../"><img src="../../<?php echo $InfoCore->img_logo;?>" title="WikiAr" alt="WikiAr"/></a>
  <form method="get" action="">
  <input type="search" name="q" placeholder="Buscar..." value="" autofocus/>
  <input type="submit" name="submit" value="Buscar" id="button"/>
  </form>
</div><br><br><br><br><br>
<?php
echo '<p>Resultados obtidos: ('.$results['number_results'].') em: '.$time.' segundos.</p>';
if($results['type']=='default'){
  echo '<p>Tipo de busca: (default)</p>';
}else{
  echo '<p>Tipo de busca: ('.$results['type'].') em: ('.$results['data_type'].')</p>';
}
print_r($results['results']);
?>
<p id="copy" style="background:none;margin-bottom:0px;"><?php echo $InfoCore->location_created;?><br><br><?php echo $InfoCore->copyright;?></p>
<?php
echo $InfoCore->analyticstracking;
?>
</body></html>
