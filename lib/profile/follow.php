<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");
class WaFollow extends connection{

  public function __construct($codeUser, $userFollowed){
    $this->codeUser = $codeUser;
    $this->userFollowed = $userFollowed;
    $this->date = date('Y-m-d H:i:s');
  }

  public function follow_user(){
    $resultCheck = $this->check_user();
    if($resultCheck==1){
      return 1;
    }
    if($resultCheck==0){
      $PDO = parent::conexao();
      $sql = "INSERT INTO wa_users_followers(code_user, user_followed, register, last_change) VALUES(:code_user, :user_followed, :register, :last_change)";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_user', $this->codeUser );
      $stmt->bindParam( ':user_followed', $this->userFollowed );
      $stmt->bindParam( ':register', $this->date );
      $stmt->bindParam( ':last_change', $this->date );

      $result = $stmt->execute();

      if ( ! $result ){
        // var_dump( $stmt->errorInfo() );
        return 0;
      }
      if($this->update_follower_list(1)==1){
        return 1;
      }
      return 0;
    }
    if($this->update_status('1')==1){
      if($this->update_follower_list(1)==1){
        return 1;
      }
      return 0;
    }
    return 0;
  }

  public function unfollow(){
    if($this->update_status('0')==1){
      if($this->update_follower_list(0)==1){
        return 1;
      }
      return 0;
    }
    return 0;
  }

  public function check_user(){
    $PDO = parent::conexao();
    $sql = "SELECT user_followed, status FROM wa_users_followers WHERE code_user='$this->codeUser' AND user_followed='$this->userFollowed' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if(empty($rows)){
      return 0;
    }
    if($rows[0]['status']==1){
      return 1;
    }
    return 2;
  }
  // Number 0 para quem nunca seguiu
  // Number 1 para quem já está seguindo
  // Number 2 para quem deixou de seguir

  private function update_status($status){
    $PDO = parent::conexao();
    $sql = "UPDATE wa_users_followers SET status = :status, last_change = :last_change WHERE user_followed = :user_followed AND code_user = :code_user";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $this->codeUser );
    $stmt->bindParam( ':user_followed', $this->userFollowed );
    $stmt->bindParam( ':status', $status );
    $stmt->bindParam( ':last_change', $this->date );

    $result = $stmt->execute();
    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }

  private function update_follower_list($typeFunc){
    $PDO = parent::conexao();

    $sql = "SELECT user_followers FROM wa_profile WHERE code_user='$this->codeUser' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if(!empty($rows)){
      if($typeFunc==1){
        $newNumber = $rows[0]['user_followers']+1;
      }else{
        if($rows[0]['user_followers']==0){
          $newNumber = 0;
        }else{
          $newNumber = $rows[0]['user_followers']-1;
        }
      }
      $sql = "UPDATE wa_profile SET user_followers = :user_followers WHERE code_user = :code_user";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_user', $this->codeUser );
      $stmt->bindParam( ':user_followers', $newNumber );

      $result = $stmt->execute();

      if ( ! $result )
      {
        // var_dump( $stmt->errorInfo() );
        return 0;
      }
      return 1;
    }
    return 0;
  }

}
