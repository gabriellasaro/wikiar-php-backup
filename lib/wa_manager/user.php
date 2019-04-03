<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection2.php");
class WaUser extends connection{

  public function __construct(){
    $this->day = date('Y-m-d');
    $this->userIp = $_SERVER['REMOTE_ADDR'];
  }

  public function info_user($selection, $typo){
    $PDO = parent::conexao();
    $sql = "SELECT $selection FROM wa_profile WHERE user_name='$this->code_user' AND user_status >= '1' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($result->rowCount() == 0){
      $rows = array('user_status'=>'0');
    }else{
      if($typo==1){
        $social = $this->socialnetwork($user);
        if($social['social_status']=='1'){
          $social = array('social'=>$social);
          $rows = array_merge($rows[0], $social);
        }else{
          $social = array('social_status'=>$social['social_status']);
          $social = array('social'=>$social);
          $rows = array_merge($rows[0], $social);
        }
      }else{
        $rows = $rows[0];
      }
    }
    return $rows;
  }

  public function socialnetwork(){
    $PDO = parent::conexao();
    $sql = "SELECT ID_youtube, ID_gplus, ID_facebook, ID_twitter, ID_donate, social_status FROM socialnetwork_ID WHERE CODE_link='$this->code_user' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($result->rowCount() == 1){
      if($rows[0]['social_status'] == '1'){
        if($rows[0]['ID_youtube']==null){
          unset($rows[0]['ID_youtube']);
        }
        if($rows[0]['ID_gplus']==null){
          unset($rows[0]['ID_gplus']);
        }
        if($rows[0]['ID_facebook']==null){
          unset($rows[0]['ID_facebook']);
        }
        if($rows[0]['ID_twitter']==null){
          unset($rows[0]['ID_twitter']);
        }
        if($rows[0]['ID_donate']==null){
          unset($rows[0]['ID_donate']);
        }
        $rows = $rows[0];
      }else {
        $rows = array('social_status'=>$rows[0]['social_status']);
      }
    }else{
      $rows = array('social_status'=>'N');
    }
    return $rows;
  }

}
