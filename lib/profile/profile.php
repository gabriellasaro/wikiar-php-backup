<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__)).'/connection.php');
class WaProfile extends connection{

  public function list_user_publications($codeUser){
    $PDO = parent::conexao();
    $sql = "SELECT wa_linker.code_user, wa_archived_posts.code_post, wa_archived_posts.post_title, wa_linker.post_status, wa_linker.post_date FROM wa_archived_posts INNER JOIN wa_linker ON wa_linker.code_user = '$codeUser' AND wa_linker.code_post=wa_archived_posts.code_post AND wa_linker.last_version=wa_archived_posts.post_version ORDER BY wa_archived_posts.post_modified DESC";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if(count($rows)>=1){
      $rows = array('data'=>$rows, 'status'=>'1');
    }else{
      $rows = array('status'=>'0');
    }
    return $rows;
  }

  public function info_profile($selection, $codeUser, $typeSocial = 0, $typeSearch = 0){
    $PDO = parent::conexao();
    if($typeSearch == 0){
      $sql = "SELECT $selection FROM wa_profile WHERE code_user='$codeUser' LIMIT 1";
    }else{
      $sql = "SELECT $selection FROM wa_profile WHERE username='$codeUser' LIMIT 1";
    }
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($result->rowCount() == 0){
      return array('user_status'=>'N');
    }
    if($typeSocial==1){
      $social = array('social'=>$this->socialnetwork($codeUser));
      return array_merge($rows[0],  array('social'=>$this->socialnetwork($codeUser)));
    }
    return $rows[0];
  }

  public function socialnetwork($codeUser){
    $PDO = parent::conexao();
    $sql = "SELECT ID_youtube, ID_gplus, ID_facebook, ID_twitter, ID_donate, sn_status FROM wa_socialnetwork WHERE code_user='$codeUser' AND sn_status='1' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if(empty($rows)){
      return array('sn_status'=>'0');
    }
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
    return $rows[0];
  }

  public function new_profile($codeUser, $username, $niceName, $birthday, $sex, $language){
    $PDO = parent::conexao();
    $register = date('Y-m-d H:i:s');
    $sql = "INSERT INTO wa_profile(code_user, username, user_nicename, user_birthday, user_language, user_sex, user_register) VALUES(:code_user, :username, :user_nicename, :user_birthday, :user_language, :user_sex, :user_register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $codeUser );
    $stmt->bindParam( ':username', $username );
    $stmt->bindParam( ':user_nicename', $niceName );
    $stmt->bindParam( ':user_birthday', $birthday );
    $stmt->bindParam( ':user_language', $language );
    $stmt->bindParam( ':user_sex', $sex );
    $stmt->bindParam( ':user_register', $register );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }

  // PARTE: My recommendations
  public function my_recommendations($codeUser){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.code_post, wa_posts.post_title FROM wa_post_recommend INNER JOIN wa_posts ON wa_posts.code_post=wa_post_recommend.code_post INNER JOIN wa_linker ON wa_linker.post_status='1' AND wa_linker.code_post=wa_post_recommend.code_post WHERE wa_post_recommend.code_user='$codeUser' AND recommend='1'";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }
}
