<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");
class WaTags extends connection{

  public function __construct(){
    $this->date = date('Y-m-d H:i:s');
  }

  public function show_tags_post($code_post, $format = 0){
    $PDO = parent::conexao();
    $sql = "SELECT tag_name FROM wa_tags WHERE code_post='$code_post'";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if($format==0){
      $a = null;
      for($i=0; $i<=count($rows)-1; $i++){
        $a .= '#'.$rows[$i]['tag_name'].' ';
      }
      unset($rows);
      return trim($a);
    }
    return $rows;
  }

  public function new_tag($code_post, $tags){
    $tags = $this->format_tags($tags);
    if(empty($tags)){
      return 0;
    }
    for($i=0; $i<=count($tags['code'])-1; $i++){
      $PDO = parent::conexao();
      // $date = date('Y-m-d H:i:s');
      $sql = "INSERT INTO wa_tags(code_post, code_tag, tag_name, register) VALUES(:code_post, :code_tag, :tag_name, :register)";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_post', $code_post );
      $stmt->bindParam( ':code_tag', $tags['code'][$i] );
      $stmt->bindParam( ':tag_name', $tags['name'][$i] );
      $stmt->bindParam( ':register', $this->date );

      $result = $stmt->execute();

      if ( ! $result ){
        // var_dump( $stmt->errorInfo() );
        return 0;
      }
      $this->tag_counter($tags['code'][$i], $tags['name'][$i]);
    }
    return 1;
  }

  private function format_tags($tags){
    $tags = explode("#", $tags);
    array_shift($tags);
    for($i=0; $i<=count($tags)-1; $i++){
      $tags[$i] = str_replace(',', '', trim($tags[$i]));
    }
    $listRemove = array(" ", ",", "á", "Á", "ã", "é", "ç", "ó", "à");
    $listSubs = array("-", "", "a", "a", "a", "e", "c", "o", "a");
    if(count($tags)==1){
      $codeTag = str_replace($listRemove, $listSubs, strtolower($tags[0]));
      $tag = array('code'=>array($codeTag), 'name'=>array($tags[0]));
      return $tag;
    }
    $arrayTags = array();
    for($i=0; $i<=count($tags)-1; $i++){
      $codeTag = str_replace($listRemove, $listSubs, strtolower($tags[$i]));
      $tag = array('code'=>$codeTag, 'name'=>$tags[$i]);
      $arrayTags = array_merge_recursive($arrayTags, $tag);
    }
    return $arrayTags;
  }

  // parte top tags
  public function list_top_tags(){
    $PDO = parent::conexao();
    $month = date('Y-m');
    $sql = "SELECT code_tag, tag_name, used FROM wa_tt WHERE tag_month='$month' ORDER BY used DESC LIMIT 5";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    $arquivojson = json_encode($rows);
    $caminho = dirname(dirname(dirname(__FILE__)))."/cache/home/top_tags.json";
    file_put_contents($caminho, $arquivojson);
  }

  private function tag_counter($tag, $name){
    $month = date('Y-m');
    $PDO = parent::conexao();
    $sql = "SELECT id, used FROM wa_tt WHERE code_tag='$tag' AND tag_month='$month' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    if(empty($rows)){
      $sql = "INSERT INTO wa_tt(code_tag, tag_name, tag_month, register, date_last_changes) VALUES(:code_tag, :tag_name, :tag_month, :register, :date_last_changes)";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_tag', $tag );
      $stmt->bindParam( ':tag_name', $name );
      $stmt->bindParam( ':tag_month', $month );
      $stmt->bindParam( ':register', $this->date );
      $stmt->bindParam( ':date_last_changes', $this->date );

      $result = $stmt->execute();

      if ( ! $result ){
        // var_dump( $stmt->errorInfo() );
        return 0;
      }
      return 1;
    }
    $number = $rows[0]['used']+1;
    $sql = "UPDATE wa_tt SET used = :used, date_last_changes = :date_last_changes WHERE id = :id";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':id', $rows[0]['id'] );
    $stmt->bindParam( ':used', $number );
    $stmt->bindParam( ':date_last_changes', $this->date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }

}
