<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__)).'/connection.php');

class WaSearch extends connection{

  public function search($q){
    $result = $this->identify_type_search($q);
    if($result['type']=='default'){
      $data = $this->search_post($result['data']);
      $data = array('results'=>$data, 'number_results'=>count($data), 'type'=>$result['type']);
    }elseif($result['type']=='user'){
      $data = $this->search_user_posts($result['data_type'], $result['data']);
      $data = array('results'=>$data, 'number_results'=>count($data), 'type'=>$result['type'], 'data_type'=>$result['data_type']);
    }else{
      $data = $this->search_tag_posts($result['data_type'], $result['data']);
      $data = array('results'=>$data, 'number_results'=>count($data), 'type'=>$result['type'], 'data_type'=>$result['data_type']);
    }
    return $data;
  }

  private function identify_type_search($q){
    $elements = $this->clean_white_space($q);
    switch (substr($elements[0], 0, 1)){
      case '@':
      $type = 'user';
      $data_type = substr($elements[0], 1);
      break;
      case '#':
      $type = 'tag';
      $data_type = substr($elements[0], 1);
      break;
      default:
      $type = 'default';
      break;
    }
    if($type=='user' or $type=='tag'){
      array_shift($elements);
      return array('data'=>$elements, 'type'=>$type, 'data_type'=>$data_type);
    }
    return $info = array('data'=>$elements, 'type'=>$type);
  }

  private function clean_white_space($q){
    $q = trim($q);
    $q = explode(' ', $q);
    for($i=0;$i<=count($q)-1;$i++){
      $q[$i] = trim($q[$i]);
      if(strlen($q[$i])==0){
        unset($q[$i]);
      }
    }
    return $q;
  }

  private function search_post($q){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.code_post, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img, wa_linker.post_last_change FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_posts.code_post AND wa_linker.post_status='1' WHERE wa_posts.post_title LIKE '%" . $q[0] . "%'";
    unset($q[0]);

    for($i = 1; $i<=count($q); $i++) {
      $pb =  $q[$i];
      $busca_option = "OR wa_posts.post_title LIKE '%".$pb."%'";
      $sql .= $busca_option;
    }
    $sql = $sql." ORDER BY wa_linker.post_last_change DESC";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }

  private function search_user_posts($username, $q){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.code_post, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img, wa_linker.post_last_change FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_posts.code_post INNER JOIN wa_profile ON wa_profile.username = '$username' AND wa_linker.code_user = wa_profile.code_user AND wa_linker.post_status='1' WHERE wa_posts.post_title LIKE '%" . $q[0] . "%'";
    unset($q[0]);

    for($i = 1; $i<=count($q); $i++) {
      $pb =  $q[$i];
      $busca_option = "OR wa_posts.post_title LIKE '%".$pb."%'";
      $sql .= $busca_option;
    }
    $sql = $sql." ORDER BY wa_linker.post_last_change DESC";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }

  private function search_tag_posts($tag, $q){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.code_post, wa_tags.code_tag, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img, wa_linker.post_last_change FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_posts.code_post AND wa_linker.post_status='1' INNER JOIN wa_tags ON wa_tags.code_post=wa_linker.code_post AND wa_tags.code_tag = '$tag' WHERE wa_posts.post_title LIKE '%" . $q[0] . "%'";
    unset($q[0]);

    for($i = 1; $i<=count($q); $i++) {
      $pb =  $q[$i];
      $busca_option = "OR wa_posts.post_title LIKE '%".$pb."%'";
      $sql .= $busca_option;
    }
    $sql = $sql." ORDER BY wa_linker.post_last_change DESC";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }

  private function search_code_posts($q){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.code_post, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img, wa_linker.post_last_change FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_posts.code_post AND wa_linker.code_user = '$code_user' AND wa_linker.post_status='1' WHERE wa_posts.post_title LIKE '%" . $q[0] . "%'";
    unset($q[0]);

    for($i = 1; $i<=count($q); $i++) {
      $pb =  $q[$i];
      $busca_option = "OR wa_posts.post_title LIKE '%".$pb."%'";
      $sql .= $busca_option;
    }
    $sql = $sql." ORDER BY wa_linker.post_last_change DESC";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }
}
