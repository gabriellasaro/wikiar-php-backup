<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__)).'/connection.php');
class PostKey extends connection {
  private $key = null;

  public function create_key(){
    $key = rand(1000000000, 9999999999);

    if ($this->search_key($key) == 0) {
      $this->key = $key;
      $this->insert_key();
      return 1;
    }
    return 0;
  }
  public function show_key(){
    return $this->key;
  }

  private function search_key($key){
    $PDO = parent::conexao();
    $sql = "SELECT code_post FROM post_key WHERE code_post=$key LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $result->rowCount();
  }

  private function insert_key(){
    $PDO = parent::conexao();
    $register = date('Y-m-d H:i:s');
    $sql = "INSERT INTO post_key(code_post, register) VALUES(:code_post, :register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->key );
    $stmt->bindParam( ':register', $register );
    $result = $stmt->execute();

    if ( ! $result )
    {
      var_dump( $stmt->errorInfo() );
      exit;
    }
  }
}
