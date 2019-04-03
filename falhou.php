<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
include_once("lib/WikiAr_infoCORE.php");
$infoCORE = new WikiAr_infoCORE();
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Falhou - <?php echo $infoCORE->name;?></title>
<meta http-equiv="content-language" content="pt-br" />
<link rel="stylesheet" href="css/menu/style_menu.css"><link rel="stylesheet" href="css/falhou/style.css">
<meta name="viewport" content="width=device-width">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
</head><body>
<img src="<?php echo $infoCORE->img_logo;?>" alt="<?php echo $infoCORE->name;?>" title="<?php echo $infoCORE->name;?>"/><p class="p_simples">FALHOU!</p>
<?php
if(isset($_GET['msg'])){
  echo '<p class="p_simples msg">'.$_GET['msg'].'</p>';
}
echo '<p id="copy">'.$infoCORE->location_created.'<br><br>'.$infoCORE->copyright.'</p>';
echo $infoCORE->analyticstracking;
?>
</body></html>
