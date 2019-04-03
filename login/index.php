<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/local_session.php');
$se = new WaLocalSession();
$se->safe_session();
if($se->session_check()==true){
  header('Location: ../index.php');
  exit;
}
require_once(dirname(dirname(__FILE__)).'/includes/info-core.php');
$InfoCore = new InfoCore();
require_once(dirname(dirname(__FILE__)).'/includes/func-mdl.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo "Login - ".$InfoCore->name;?></title>
<meta name="COPYRIGHT" content="<?php echo $InfoCore->min_copyright;?>">
<meta name="language" content="pt-br">
<meta name="theme-color" content="#808080">
<link rel="stylesheet" href="../templates/css/core/menu.css">
<link rel="stylesheet" href="../templates/css/login/style.css" media="screen">
<link rel="stylesheet" href="../templates/css/login/style2.css" media="(max-width: 600px)">

<link rel="stylesheet" href="../templates/mdl/material.min.css">
<script src="../templates/mdl/material.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<meta name="viewport" content="width=device-width">
<script src="../js/lcV0-1.js"></script>
<script src="../js/classic_bar.js"></script>
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro|Raleway' rel='stylesheet' type='text/css'>
</head>
<body>
  <!-- Always shows a header, even in smaller screens. -->
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title"><?php echo $InfoCore->name;?></span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        <!-- Navigation. We hide it in small screens. -->
        <nav class="mdl-navigation mdl-layout--large-screen-only">
          <a class="mdl-navigation__link" href="../">Página inicial</a>
        </nav>
      </div>
    </header>
    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title"><?php echo $InfoCore->name;?></span>
      <?php echo menu_oculto_mdl();?>
    </div>
    <main class="mdl-layout__content">
      <div class="page-content">
        <br><br>
        <p id="title" style="margin-top:100px;">Fazer login no <span style="color:#303030;">WikiAr</span></p>
        <form method="post" action="process.php<?php if(isset($_GET['r'])){echo '?r='.urlencode($_GET['r']);}?>"><center>
        <input name="email" type="text" maxlength="120" placeholder="e-mail" value="<?php if(!empty($_GET['user'])){echo $_GET['user'];}?>" autofocus="on"/>
        <input name="password" type="password" placeholder="Senha" value=""/><br>
        <input type="submit" name="Submit" value="Entrar"/></center><br>
        </form>
        <p id="copy"><?php echo $InfoCore->location_created."<br><br>".$InfoCore->copyright;?></p>

      </div>
    </main>
  </div>
<?php
echo $InfoCore->analyticstracking;
?>
</body></html>
