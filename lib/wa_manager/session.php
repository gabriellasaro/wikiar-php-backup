<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__)).'/connection2.php');
class WaSession extends WaManagerConnection{

  public function __construct(){
    $this->day = date('Y-m-d');
    $this->userIp = $_SERVER['REMOTE_ADDR'];
  }

  public function check_session($code_user, $token){
    $this->code_user = $code_user;
    $PDO = parent::conexao();
    $sql = "SELECT id, closing_date, session_IP FROM wa_sessions WHERE code_user='$this->code_user' AND token='$token' AND session_status='1' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if(count($rows)==1){
      if($rows[0]['session_IP']==$this->userIp){
        if(strtotime($this->day)>=strtotime($rows[0]['closing_date'])){
          $this->update_session_status($rows[0]['id']);
          return 0;
        }
        return 1;
      }
      return 0;
    }
    return 0;
  }

  public function start($email, $pass){
    require('attempts.php');
    $attempts = new WaAttempts($email);
    $resultAttempts = $attempts->search_attempts();
    if($resultAttempts==0){
      // $attempts->update_attempts(0);
      return 0;
    }
    $result = $this->user_data($email);
    if($result['user_status'] != 'D'){
      if($result['user_status'] != 'N'){
        if($this->verify_password($pass, $result['user_password'])==1){
          if($resultAttempts==1){
            $attempts->create_attempts();
            $attempts->update_attempts(1);
          }else{
            $attempts->update_attempts(1);
          }
          // A senha está correta!
          $this->code_user = $result['code_user'];
          $dbresult = $this->create_session();
          if($dbresult['status']=='1'){
            require('local_session.php');
            require_once(dirname(dirname(__FILE__)).'/profile/profile.php');
            $infoProfile = new WaProfile();
            $dataProfile = $infoProfile->info_profile("user_nicename, user_img, user_capa, user_sex", $result['code_user']);
            $start = new WaLocalSession();
            $start->safe_session();
            $_SESSION['user_number'] = $result['code_user'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['full_name'] = $dataProfile['user_nicename'];
            if($dataProfile['user_img']==null){
              $_SESSION['user_img'] = 'http://localhost/WikiAr/img_material_icons/ic_account_circle_black_48px.svg';
            }else{
              $_SESSION['user_img'] = $dataProfile['user_img'];
            }
            $_SESSION['user_capa'] = $dataProfile['user_capa'];
            $_SESSION['user_sex'] = $dataProfile['user_sex'];
            $_SESSION['user_lang'] = $result['user_language'];
            $_SESSION['user_status'] = $result['user_status'];
            $_SESSION['token'] = $dbresult['token'];
            return 1;
          }
        }
      }
    }
    if($resultAttempts==1){
      $attempts->create_attempts();
      $attempts->update_attempts(0);
    }else{
      $attempts->update_attempts(0);
    }
    return 0;
  }

  private function user_data($email){
    $PDO = parent::conexao();

    $sql = "SELECT code_user, username, user_nicename, user_password, user_status, user_language FROM wa_user WHERE user_email='$email' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if(count($rows)==1){
      if($rows[0]['user_status']!='D'){
        return $rows[0];
      }
    }
    return array('user_status'=>'N');
  }

  private function verify_password($pass, $dbpass){
    if(password_verify($pass, $dbpass)){
      return 1;
    }
    return 0;
  }

  private function create_session(){
    $PDO = parent::conexao();

    $register = date('Y-m-d H:i:s');

    $info_return = $this->info_platform();
    $platform = $info_return['platform'];
    $browser = $info_return['browser'];
    $token = $this->generate_token();
    $closing = date('Y-m-d', strtotime('+1 week'));

    $sql = "INSERT INTO wa_sessions(code_user, closing_date, token, platform, browser, session_IP, register) VALUES(:code_user, :closing_date, :token, :platform, :browser, :session_IP, :register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $this->code_user );
    $stmt->bindParam( ':closing_date', $closing );
    $stmt->bindParam( ':token', $token );
    $stmt->bindParam( ':platform', $platform );
    $stmt->bindParam( ':browser', $browser );
    $stmt->bindParam( ':session_IP', $this->userIp );
    $stmt->bindParam( ':register', $register );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      return array('status'=>'0');
      exit;
    }
    return array('status'=>'1', 'token'=>$token);
  }

  public function logout_session($code_user, $token){
    $this->code_user = $code_user;
    $PDO = parent::conexao();
    $sql = "SELECT id FROM wa_sessions WHERE code_user='$this->code_user' AND token='$token' AND session_status='1' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if(count($rows)==1){
      $this->update_session_status($rows[0]['id']);
      return 1;
    }else{
      return 0;
    }
  }

  private function update_session_status($id){
    $PDO = parent::conexao();
    $logoutDate = date('Y-m-d H:i:s');
    $status = '0';
    $sql = "UPDATE wa_sessions SET session_status = :session_status, logout_date = :logout_date WHERE id = :id";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':id', $id );
    $stmt->bindParam( ':session_status', $status );
    $stmt->bindParam( ':logout_date', $logoutDate );
    $result = $stmt->execute();

    if ( ! $result )
    {
        // var_dump( $stmt->errorInfo() );
        exit;
    }
  }

  private function delete_session($id){
    $PDO = parent::conexao();

    $sql = "DELETE FROM wa_sessions WHERE id = :id";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':id', $id );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }

  private function generate_token(){
    $qtd = 60;
    $Caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz0123456789';
    $QuantidadeCaracteres = strlen($Caracteres);
    $QuantidadeCaracteres--;

    $hash=null;
    for($x=1;$x<=$qtd;$x++){
        $Posicao = rand(0,$QuantidadeCaracteres);
        $hash .= substr($Caracteres,$Posicao,1);
    }
    return $hash;
  }

  public function info_platform(){
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $platform = array("platform"=>null,"browser"=>null);

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform['platform'] = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform['platform'] = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform['platform'] = 'windows';
    }

    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $platform['browser'] = "msie";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $platform['browser'] = "firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $platform['browser'] = "chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $platform['browser'] = "safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $platform['browser'] = "opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $platform['browser'] = "netscape";
    }
    return $platform;
  }
}
