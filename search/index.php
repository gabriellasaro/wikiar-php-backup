<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

require_once(dirname(dirname(__FILE__)).'/includes/info-core.php');
$InfoCore = new InfoCore();
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
?>
<!--
L           AAAAAAAAAAAA   SSSSSSSSSSS AAAAAAAAAAA RRRRRRRRRRRR OOOOOOOOOOOO
L           A          A  SS           A         A R          R O          O
L           A          A SS            A         A R          R O          O
L           A          A  SS           A         A R          R O          O
L           A          A   SSSS        A         A R          R O          O
L           AAAAAAAAAAAA       SSSS    AAAAAAAAAAA RRRRRRRRRRRR O          O
L           A          A           SS  A         A R  RR        O          O
L           A          A            SS A         A R     RR     O          O
L           A          A           SS  A         A R       RR   O          O
LLLLLLLLLLL A          A SSSSSSSSSSS   A         A R         RR OOOOOOOOOOOO
COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
-->
<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title>Search - <?php echo $InfoCore->name.", ".$InfoCore->subname;?></title>
<meta http-equiv="content-language" content="pt-br" />
<meta property="og:type" content="website">
<meta property="og:url" content="http://www.wikiar.news/">
<meta property="og:image" itemprop="image" content="<?php echo $InfoCore->img_logo;?>">
<meta property="og:site_name" content="<?php echo $InfoCore->name." - ".$InfoCore->subname;?>">
<meta property="og:description" content="<?php echo $InfoCore->description; ?>">
<meta name="COPYRIGHT" content="<?php echo $InfoCore->min_copyright;?>">
<meta name="theme-color" content="#808080">
<link rel="stylesheet" href="../css/menu/menu.css">
<link rel="stylesheet" href="../css/search/style.css">
<meta name="viewport" content="width=device-width">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
</head>
<body>
<a href="../" class="menu-link">Página inicial</a>
<img src="../<?php echo $InfoCore->img_logo;?>" title="WikiAr" alt="WikiAr"/>
<form method="get" action="./results/" class="form-search">
<input type="search" name="q" placeholder="Buscar..." value="" autofocus/>
<br><center><input type="submit" name="submit" value="Buscar" id="button"/></center>
</form>
<p id="copy" style="background:none;margin-bottom:0px;"><?php echo $InfoCore->location_created;?><br><br><?php echo $InfoCore->copyright;?></p>
<?php
echo $InfoCore->analyticstracking;
?>
<script src="../js/search/color.js"></script>
</body></html>
