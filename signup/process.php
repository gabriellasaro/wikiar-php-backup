<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

// preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$username);

if (!isset($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password'], $_POST['birthday'])) {
  return header('Location: index.php?m='.urlencode('Preencha os campos!'));
}
if(strlen($_POST['username'])>120 or strlen($_POST['username'])<4){
  return header('Location: index.php?m='.urlencode('Preencha os campo!'));
}

if(isset($_POST['g-recaptcha-response'])){
  // echo 'Processando...';
  $secret = '6Lf_VhYUAAAAAH1iip0hrFTqMwv5b6D4mjVLOaXW';
  require('recaptcha/src/autoload.php');
  $recaptcha = new \ReCaptcha\ReCaptcha($secret);
  $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
  if ($resp->isSuccess()) {
    // verified!
    // Limpa e valida os dados passados em

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // Email inválido
      return header('Location: index.php?m='.urlencode('O endereço de email digitado não é válido!'));
    }

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if (strlen($password) > 20 or strlen($password)<=4) {
      return header('Location: index.php?m='.urlencode('A senha digitada não é válida!'));
    }

    switch ($_POST['options']) {
      case '1':
        $sex = 'M';
        break;
      case '2':
        $sex = 'F';
        break;
      default:
        $sex = 'M';
        break;
    }

    require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/signup.php');
    $newAccount = new WaSignUp();
    $newAccount->create_account($username, $name, $email, $password, $_POST['birthday'], $sex);
    if($newAccount->get_errors()['error'] == '1'){
      if($newAccount->get_errors()['type']=='email'){
        return header('Location: index.php?m='.urlencode('Não foi possivel criar sua conta!<br>E-mail indisponível!'));
      }elseif($newAccount->get_errors()['type']=='user'){
        return header('Location: index.php?m='.urlencode('Não foi possivel criar sua conta!<br>Username indisponível!'));
      }
      return header('Location: index.php?m='.urlencode('Não foi possivel criar sua conta no momento!<br>Tente mais tarde!'));
    }
    // require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/email.php');
    require_once(dirname(dirname(__FILE__)).'/lib/wa_manager/session.php');
    // $enviar_email = new WaEmail();
    $login = new WaSession();
    // $enviar_email->enviar_email_html($email, $name, $username, 'Confirmação de e-mail', 1);
    if($login->start($email, $password)==1){
      return header('Location: http://'.$_SERVER['HTTP_HOST'].'/WikiAr');
    }
    return header('Location: http://'.$_SERVER['HTTP_HOST'].'//login/?m='.urlencode('Conta criada com sucesso!<br>Erro ao logar!<br>E-mail ou senha incorretos!'));
  }
  // $errors = $resp->getErrorCodes();
  return header('Location: index.php?m='.urlencode('Recaptcha incorreta!'));
}
return header('Location: index.php?m='.urlencode('Recaptcha incorreta!'));
