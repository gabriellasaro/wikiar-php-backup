<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection2.php");
class WaSignUp extends WaManagerConnection{
  private $accountErrors = null;
  private $username = null;
  public function create_account($username, $niceName, $email, $password, $birthday, $sex, $language = 'pt-br'){
    if(strlen($niceName)>120 or strlen($username)>120){
      $this->accountErrors = array('error'=>'1', 'type'=>'server');
      return 0;
    }
    if($this->search_email($email)==1){
      $this->accountErrors = array('error'=>'1', 'type'=>'email');
      return 0;
    }
    if($this->search_username($username)==1){
      $this->accountErrors = array('error'=>'1', 'type'=>'user');
      return 0;
    }
    $codeUser = $this->create_code();
    if($codeUser==null){
      $this->accountErrors = array('error'=>'1', 'type'=>'server');
      return 0;
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $register = date('Y-m-d H:i:s');

    $PDO = parent::conexao();
    $sql = "INSERT INTO wa_user(code_user, username, user_nicename, user_email, user_password, user_birthday, user_language, user_register) VALUES(:code_user, :username, :user_nicename, :user_email, :user_password, :user_birthday, :user_language, :user_register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $codeUser );
    $stmt->bindParam( ':username', $username );
    $stmt->bindParam( ':user_nicename', $niceName );
    $stmt->bindParam( ':user_email', $email );
    $stmt->bindParam( ':user_password', $password );
    $stmt->bindParam( ':user_birthday', $birthday );
    $stmt->bindParam( ':user_language', $language );
    $stmt->bindParam( ':user_register', $register );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      $this->accountErrors = array('error'=>'1', 'type'=>'server');
      return 0;
    }
    require_once(dirname(dirname(__FILE__)).'/profile/profile.php');
    $newProfile = new WaProfile();
    $newProfile->new_profile($codeUser, $username, $niceName, $birthday, $sex, $language);
    $this->accountErrors = array('error'=>'0');
    $this->username = $username;
    return 0;
  }

  public function get_errors(){
    return $this->accountErrors;
  }

  public function get_username(){
    return $this->username;
  }

  private function create_code(){
    $codeUser = mt_rand(100000000000, 999999999999);
    if($this->search_code($codeUser)==0){
      return $codeUser;
    }
    return null;
  }

  public function search_username($user){
    $PDO = parent::conexao();
    $sql = "SELECT username FROM wa_user WHERE username='$user' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($result->rowCount() == 1){
      return 1;
    }
    return 0;
  }

  private function search_code($codeUser){
    $PDO = parent::conexao();
    $sql = "SELECT code_user FROM wa_user WHERE code_user='$codeUser' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($result->rowCount() == 1){
      return 1;
    }
    return 0;
  }

  private function search_email($email){
    $PDO = parent::conexao();
    $sql = "SELECT user_email FROM wa_user WHERE user_email='$email' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($result->rowCount() == 1){
      return 1;
    }
    return 0;
  }

}
