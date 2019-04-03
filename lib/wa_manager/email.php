<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__)).'/connection2.php');
class WaEmail extends WaManagerConnection{
  private $code_user;
  private $email;
  private $token = null;

  private function key_generator($qtd){
    $qtd = $qtd ?? 60;
    $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz0123456789';
    $QuantidadeCaracteres = strlen($Caracteres);
    $QuantidadeCaracteres--;

    $Hash=null;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $Hash .= substr($Caracteres,$Posicao,1);
    }
    $this->token = $Hash;
  }

  private function save_key(){
    $this->key_generator(50);
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO wa_email(code_user, user_email, token, email_register) VALUES(:code_user, :user_email, :token, :email_register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $this->code_user );
    $stmt->bindParam( ':user_email', $this->email );
    $stmt->bindParam( ':token', $this->token );
    $stmt->bindParam( ':email_register', $date );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }

  public function validate_email_verification($email, $token){
    $this->email = $email;
    $this->token = $token;
    $PDO = parent::conexao();
    $sql = "SELECT code_user FROM wa_email WHERE user_email='$this->email' AND token='$this->token' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    $number = count($rows);
    if($number==0){
      $rows = array('status'=>'N');
    }elseif($number==1){
      $rows = array('status'=>'1', 'result'=>$rows[0]['code_user']);
    }else{
      $rows = array('status'=>'0');
    }
    return $rows;
  }
  
  public function enviar_email_html($para, $nome, $user, $assunto, $type){
    $this->email = $para;
    $this->code_user = $user;
    $this->save_key();
    $link = 'http://wikiar.news/confirm_email/?t='.$this->token;
    $msg = $this->html_generator($type, $link);
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $headers .= 'To: '.$nome.' <'.$para.'>' . "\r\n";
    $headers .= 'From: WikiAr <contact@wikiar.news>' . "\r\n";
    // $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
    // $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

    mail($para, $assunto, $msg, $headers);
  }

  private function html_generator($type, $link){
    if($type=='1'){
      $msg = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>E-mail - WikiAr</title><meta name="language" content="pt-br">
      <link rel="stylesheet" href="../css/menu/style_menu.css"><link rel="stylesheet" href="../css/email/style.css">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
      </head><body><div id="WikiAr_barra"><p id=\'title_barra\'><a href="http://wikiar.news" target="__blank" id="title_barra_link">WikiAr</a></p></div>
      <div class="container_msg"><h2>Confirmar conta <span style="color:#2E8B57;">WikiAr</span></h2>
      <p>Recebemos uma solicitação de confirmação do e-mail: '.$this->email.'.<br>Se você é o responsável pela solicitação, segue o link de confirmação:<br><br><a href="'.$link.'">'.$link.'</a><br><br>Caso você não seja o responsável ignorar mensagem ou entrar em contato: suporte@wikiar.news</p></div>
      <p id="copy">FEITO COM <span style="color:red;font-size:24px;" title="AMOR">&#10084;</span> EM COLATINA<br><br>© COPYRIGHT 2017 WikiAr, Todos os direitos reservados.</p></body></html>';
    }
    return $msg;
  }

}
