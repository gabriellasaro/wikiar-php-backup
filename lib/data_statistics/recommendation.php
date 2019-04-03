<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");
class Recommend extends connection{

  public function __construct($codePost, $codeUser = null){
    $this->codePost = $codePost;
    $this->codeUser = $codeUser;
  }

  public function start(){
    $search = $this->search_list();
    if($search['status']=='N'){
      $this->insert_recommend();
      $this->count_recommend(1);
      return 1;
    }
    if($search['status']=='0'){
      $this->update_recommend($search['id'], '1', $search['number_changes']);
      $this->count_recommend(1);
      return 1;
    }
    $this->update_recommend($search['id'], '0', $search['number_changes']);
    $this->count_recommend();
    return 0;
  }

  public function search_list(){
    $PDO = parent::conexao();
    $sql = "SELECT id, code_post, recommend, number_changes FROM wa_post_recommend WHERE code_user='$this->codeUser' AND code_post='$this->codePost' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if(empty($rows)){
      return array('status'=>'N');
    }
    return array('id'=>$rows[0]['id'], 'status'=>$rows[0]['recommend'], 'number_changes'=>$rows[0]['number_changes']);
  }

  private function update_recommend($id, $status, $changes){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');
    $changes +=1;
    $sql = "UPDATE wa_post_recommend SET recommend = :recommend, last_activity = :last_activity, number_changes = :number_changes WHERE id = :id";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':id', $id );
    $stmt->bindParam( ':recommend', $status );
    $stmt->bindParam( ':last_activity', $date );
    $stmt->bindParam( ':number_changes', $changes );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }

  private function insert_recommend(){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO wa_post_recommend(code_user, code_post, register) VALUES(:code_user, :code_post, :register)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $this->codeUser );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':register', $date );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }

  // Parte tabela wa_post_statistics
  private function count_recommend($operador = 0){
    $data = $this->search_tb_info();
    if($data != null){
      if($operador==1){
        $number = $data+1;
      }else{
        $number = $data-1;
      }
      $PDO = parent::conexao();
      $sql = "UPDATE wa_post_statistics SET recommend = :recommend WHERE code_post = :code_post";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':code_post', $this->codePost );
      $stmt->bindParam( ':recommend', $number );

      $result = $stmt->execute();

      if ( ! $result )
      {
        // var_dump( $stmt->errorInfo() );
        exit;
      }
    }
  }

  public function search_tb_info(){
    $PDO = parent::conexao();
    $sql = "SELECT recommend FROM wa_post_statistics WHERE code_post='$this->codePost' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if($result->rowCount() == 1){
      return $rows[0]['recommend'];
    }
    return 'null';
  }
}
