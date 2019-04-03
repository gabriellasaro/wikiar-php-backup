<?php
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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo "Inscreva-se - ".$InfoCore->name;?></title>
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
<script src='https://www.google.com/recaptcha/api.js'></script>
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
        <p id="title" style="margin-top:120px;">Abrir uma conta no <span style="color:#303030;">WikiAr</span></p>
        <form method="post" action="process.php"><center>
        <?php
        if(isset($_GET['m'])){
          echo '<p id="msg-error" onClick="exitmsg()">'.$_GET['m'].'</p><br>';
        }
        ?>
        <input name="name" type="text" maxlength="120" placeholder="Nome:" value="" autofocus="on"/>
        <input name="email" type="text" maxlength="120" placeholder="E-mail:" value=""/>
        <input name="password" type="password" maxlength="20" placeholder="Senha:" value=""/>
        <input name="username" type="text" placeholder="Username:" value=""/>
        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
        <input type="radio" id="option-1" class="mdl-radio__button" name="options" value="1" checked>
        <span class="mdl-radio__label">Masculino</span>
        </label>
        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
        <input type="radio" id="option-2" class="mdl-radio__button" name="options" value="2">
        <span class="mdl-radio__label">Feminino</span>
        </label>
        <input name="birthday" type="date" value=""/>
        <div class="g-recaptcha" data-sitekey="6Lf_VhYUAAAAABezv5IHd69SluzY7rETmEdHqXoR"></div>
        <br><input type="submit" name="Submit" value="Inscreva-se"/></center><br>
        </form><br></div>
        <p id="copy"><?php echo $InfoCore->location_created."<br><br>".$InfoCore->copyright;?></p>
      </div>
    </main>
  </div>
<?php
echo $InfoCore->analyticstracking;
?>
</body>
</html>
