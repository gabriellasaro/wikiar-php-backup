<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");
class PostViews extends connection{

  public function __construct($id){
    $this->id = $id;
  }

  public function basic_counter_views(){
    $PDO = parent::conexao();
    $value = $this->search_accesses('accesses');
    if($value['status']=='1'){
      $value = $value['data']['accesses']+1;

      $sql = "UPDATE wa_post_statistics SET accesses = :accesses WHERE code_post = :code_post";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_post', $this->id );
      $stmt->bindParam( ':accesses', $value );

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

  public function authenticated_view_counter($codeUser){
    $this->codeUser = $codeUser;
    $this->date = date('Y-m-d H:i:s');
    $value = $this->search_accesses();
    if($value['status']=='1'){
      require_once(dirname(dirname(__FILE__)).'/profile/profile.php');
      $perfil = new WaProfile();
      $profile = $perfil->info_profile('user_sex, user_status', $codeUser);
      if($profile['user_status'] == 'N'){
        return 0;
      }
      $dataURP = $this->search_user_read();
      if($dataURP['status']=='0'){
        $this->new_user_read();
      }else{
        $this->update_user_read($dataURP['id'], $dataURP['accesses']);
      }
      $PDO = parent::conexao();
      if($profile['user_sex']=='M'){
        $value = $value['data']['access_male']+1;
        $sql = "UPDATE wa_post_statistics SET access_male = :access_male WHERE code_post = :code_post";
        $stmt = $PDO->prepare( $sql );
        $stmt->bindParam( ':code_post', $this->id );
        $stmt->bindParam( ':access_male', $value );

        $result = $stmt->execute();

        if ( ! $result )
        {
          // var_dump( $stmt->errorInfo() );
          return 0;
        }
        return 1;
      }
      $value = $value['data']['access_female']+1;
      $sql = "UPDATE wa_post_statistics SET access_female = :access_female WHERE code_post = :code_post";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_post', $this->id );
      $stmt->bindParam( ':access_female', $value );

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

  public function search_accesses($select = 'accesses, access_male, access_female'){
    $PDO = parent::conexao();
    $sql = "SELECT $select FROM wa_post_statistics WHERE code_post='$this->id' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if($result->rowCount() == 1){
      return array('data'=>$rows[0], 'status'=>'1');
    }
    return array('status'=>'0');
  }
  // User-read publications
  private function new_user_read(){
    $PDO = parent::conexao();
    $sql = "INSERT INTO wa_urp(code_user, code_post, last_viewed, register) VALUES(:code_user, :code_post, :last_viewed, :register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $this->codeUser );
    $stmt->bindParam( ':code_post', $this->id );
    $stmt->bindParam( ':last_viewed', $this->date );
    $stmt->bindParam( ':register', $this->date );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }

  private function update_user_read($urpId, $accesses){
    $accesses += 1;
    $PDO = parent::conexao();
    $sql = "UPDATE wa_urp SET accesses = :accesses, last_viewed = :last_viewed WHERE id = :id";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':id', $urpId );
    $stmt->bindParam( ':accesses', $accesses );
    $stmt->bindParam( ':last_viewed', $this->date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }

  private function search_user_read(){
    $PDO = parent::conexao();
    $sql = "SELECT id, accesses FROM wa_urp WHERE code_user='$this->codeUser' AND code_post='$this->id' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if($result->rowCount() == 1){
      $rows[0]['status'] = '1';
      return $rows[0];
    }
    return array('status'=>'0');
  }
}
