<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection2.php");
class WaAttempts extends WaManagerConnection{

  public function __construct($user){
    $this->code_user = $user;
    $this->day = date('Y-m-d');
    $this->userIp = $_SERVER['REMOTE_ADDR'];
  }

  private function select_data_attempts($select){
    $PDO = parent::conexao();
    $sql = "SELECT $select FROM wa_attempts WHERE code_user='$this->code_user' AND day='$this->day' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }

  public function search_attempts(){
    $result = $this->select_data_attempts('failed');
    if(count($result)==1){
      if($result[0]['failed']>21){
        return 0;
      }
      return 2;
    }
    return 1;
  }

  public function create_attempts(){
    $PDO = parent::conexao();
    $register = date('Y-m-d H:i:s');
    $sql = "INSERT INTO wa_attempts(code_user, day, IP, register) VALUES(:code_user, :day, :IP, :register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $this->code_user );
    $stmt->bindParam( ':day', $this->day );
    $stmt->bindParam( ':IP', $this->userIp );
    $stmt->bindParam( ':register', $register );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      return 0;
      exit;
    }
    return 1;
  }

  public function update_attempts($status){
    $PDO = parent::conexao();
    $last_change = date('Y-m-d H:i:s');

    if($status==1){
      $number = $this->select_data_attempts('sucess');
      if(!empty($number)){
        $attempts = $number[0]['sucess']+1;
      }
      $sql = "UPDATE wa_attempts set sucess = :sucess, last_change = :last_change WHERE code_user = :code_user AND day = :day";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_user', $this->code_user );
      $stmt->bindParam( ':day', $this->day );
      $stmt->bindParam( ':sucess', $attempts );
      $stmt->bindParam( ':last_change', $last_change );
    }else{
      $number = $this->select_data_attempts('failed');
      if(!empty($number)){
        $attempts = $number[0]['failed']+1;
      }
      $sql = "UPDATE wa_attempts set failed = :failed, last_change = :last_change WHERE code_user = :code_user AND day = :day";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_user', $this->code_user );
      $stmt->bindParam( ':day', $this->day );
      $stmt->bindParam( ':failed', $attempts );
      $stmt->bindParam( ':last_change', $last_change );
    }

    $result = $stmt->execute();

    if ( ! $result )
    {
        // var_dump( $stmt->errorInfo() );
        exit;
    }
  }
}
