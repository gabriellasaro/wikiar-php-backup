<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__)).'/connection.php');
class PostEditor extends connection{
  private $codeUser;
  private $codePost;

  // Etapa um na criação da publicação
  public function new_publication($codeUser, $title, $subtitle, $img, $classification, $category, $language, $license, $commentStatus = '0'){
    require_once(dirname(__FILE__).'/chave.php');
    $chave = new PostKey();
    // criar o Código de identificação da publicação
    if($chave->create_key()==0){
      return array('status'=>'0');
    }
    $this->codePost = $chave->show_key();
    $this->codeUser = $codeUser;
    $this->create_post_statistics(); // Integra o post ao sistema de statisticas
    $this->create_new_version('wa_archived_posts', $title, $subtitle, $img); // Faz backup dos dados

    $date = date('Y-m-d H:i:s');
    $license = $this->license_type($license);
    $PDO = parent::conexao();
    $sql = "INSERT INTO wa_linker(code_post, code_user, post_classification, post_category, post_language, post_license, comment_status, post_date) VALUES(:code_post, :code_user, :post_classification, :post_category, :post_language, :post_license, :comment_status, :post_date)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':code_user', $this->codeUser );
    $stmt->bindParam( ':post_classification', $classification );
    $stmt->bindParam( ':post_category', $category );
    $stmt->bindParam( ':post_language', $language );
    $stmt->bindParam( ':post_license', $license );
    $stmt->bindParam( ':comment_status', $commentStatus );
    $stmt->bindParam( ':post_date', $date );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      return array('status'=>'0');
    }
    return array('status'=>'1', 'code_post'=>$this->codePost);
  }

  private function create_post_statistics(){
    $PDO = parent::conexao();
    $sql = "INSERT INTO wa_post_statistics(code_post) VALUES(:code_post)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );

    $result = $stmt->execute();

    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }
  // Fim da etapa um na criação da publicação

  // Funções de retorno de dados
  public function get_publication_data($codeUser, $codePost, $selected, $allVersions = 0){
    $PDO = parent::conexao();

    if($allVersions==0){
      $sql = "SELECT $selected FROM wa_archived_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_archived_posts.code_post AND wa_linker.last_version=wa_archived_posts.post_version AND wa_linker.code_user='$codeUser' WHERE wa_archived_posts.code_post='$codePost' LIMIT 1";
    }else{
      $sql = "SELECT $selected FROM wa_archived_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_archived_posts.code_post AND wa_linker.code_user='$codeUser' WHERE wa_linker.code_post='$codePost' ORDER BY wa_archived_posts.post_version DESC LIMIT 5";
    }
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if(count($rows)>=1){
        $rows = array('data'=>$rows, 'status'=>'1');
    }else{
      $rows = array('status'=>'0');
    }
    return $rows;
  }
  // Fim das funções de retorno de dados

  // Parte Editor
  public function save_backup($codeUser, $codePost, $content){
    $this->codeUser = $codeUser;
    $this->codePost = $codePost;
    $rows = $this->get_publication_data($codeUser, $codePost, 'wa_linker.public_version, wa_linker.last_version, wa_linker.post_status, wa_archived_posts.post_title, wa_archived_posts.post_subtitle, wa_archived_posts.post_img');
    if($rows['status']=='1'){
      if($rows['data'][0]['last_version']=='0'){
        $this->update_publish_content('wa_archived_posts', $content, 1);
        $this->update_linker_version(1);
        return 1;
      }
      if($rows['data'][0]['public_version']==$rows['data'][0]['last_version']){
        $version = $rows['data'][0]['last_version']+1;
        $this->create_new_version('wa_archived_posts', $rows['data'][0]['post_title'], $rows['data'][0]['post_subtitle'], $rows['data'][0]['post_img'], $content, $version);
        $this->update_linker_version($version);
        return 1;
      }
      $this->update_publish_content('wa_archived_posts', $content, $rows['data'][0]['last_version']);
      $this->update_linker_version($rows['data'][0]['last_version']);
      return 1;
    }
    return 0;
  }
  // Fim parte Editor

  // Função para publicar a última versão.
  public function publish($codeUser, $codePost){
    $this->codeUser = $codeUser;
    $this->codePost = $codePost;

    $resultBackup = $this->get_publication_data($this->codeUser, $this->codePost, 'wa_linker.public_version, wa_linker.last_version, wa_archived_posts.post_title, wa_archived_posts.post_subtitle, wa_archived_posts.post_img, wa_archived_posts.post_content');
    if($resultBackup['status']==1){
      if($resultBackup['data'][0]['last_version']=='0'){
        return 404;
      }
      if($resultBackup['data'][0]['last_version']==$resultBackup['data'][0]['public_version']){
        return 1;
      }
      $version = $resultBackup['data'][0]['last_version'];
      if($resultBackup['data'][0]['public_version']=='0'){
        $this->create_new_version('wa_posts', $resultBackup['data'][0]['post_title'], $resultBackup['data'][0]['post_subtitle'], $resultBackup['data'][0]['post_img'], $resultBackup['data'][0]['post_content'], $version);
        $this->update_public_version($version);
        $this->calculate_reading_time($resultBackup['data'][0]['post_content']);
        return 1;
      }
      $this->full_publication_update('wa_posts', $resultBackup['data'][0]['post_title'], $resultBackup['data'][0]['post_subtitle'], $resultBackup['data'][0]['post_img'], $resultBackup['data'][0]['post_content'], $version);
      $this->update_public_version($version);
      $this->calculate_reading_time($resultBackup['data'][0]['post_content']);
      return 1;
    }
    return 0;
  }
  // Função para calcular o tempo aproximado de leitura.
  private function calculate_reading_time($text){
    $text = explode(' ', strip_tags($text));
    $numberText = count($text);
    $time = ($numberText*0.5)/60;
    $PDO = parent::conexao();
    $sql = "UPDATE wa_linker SET reading_time = :reading_time WHERE code_post = :code_post";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':reading_time', $time );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }

  // Funções de versionamento
  private function create_new_version($tb, $title, $subtitle, $img, $content = '0', $version = 0){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO $tb(code_post, post_title, post_subtitle, post_content, post_img, post_version, post_modified) VALUES(:code_post, :post_title, :post_subtitle, :post_content, :post_img, :post_version, :post_modified)";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':post_title', $title );
    $stmt->bindParam( ':post_subtitle', $subtitle );
    $stmt->bindParam( ':post_content', $content );
    $stmt->bindParam( ':post_img', $img );
    $stmt->bindParam( ':post_version', $version );
    $stmt->bindParam( ':post_modified', $date );

    $result = $stmt->execute();
    if ( ! $result ){
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }
  // Usada para atualizar publicações na versão 0
  private function update_publish_content($tb, $content, $version){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');

    $sql = "UPDATE $tb SET post_content = :post_content, post_version = :post_version, post_modified = :post_modified WHERE code_post = :code_post";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':post_content', $content );
    $stmt->bindParam( ':post_version', $version );
    $stmt->bindParam( ':post_modified', $date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }

  private function full_publication_update($tb, $title, $subtitle, $img, $content, $version){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');

    $sql = "UPDATE $tb SET post_title = :post_title, post_subtitle = :post_subtitle, post_img = :post_img, post_content = :post_content, post_version = :post_version, post_modified = :post_modified WHERE code_post = :code_post";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':post_title', $title );
    $stmt->bindParam( ':post_subtitle', $subtitle );
    $stmt->bindParam( ':post_img', $img );
    $stmt->bindParam( ':post_content', $content );
    $stmt->bindParam( ':post_version', $version );
    $stmt->bindParam( ':post_modified', $date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }
  // A função updade_linker_version é usada para atualizar a versão da publicação, na tabela wa_linker.
  // Obs: atualizar somente o campo last_version.
  private function update_linker_version($version){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');

    $sql = "UPDATE wa_linker SET last_version = :last_version, post_last_change = :post_last_change WHERE code_post = :code_post";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':last_version', $version );
    $stmt->bindParam( ':post_last_change', $date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }
  // A função update_public_version atualiza os campos public_version e post_status na tabela wa_linker.
  private function update_public_version($version, $status = '1'){
    $date = date('Y-m-d H:i:s');
    $PDO = parent::conexao();
    $sql = "UPDATE wa_linker SET public_version = :public_version, post_status = :post_status, post_last_change = :post_last_change WHERE code_post = :code_post";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_post', $this->codePost );
    $stmt->bindParam( ':public_version', $version );
    $stmt->bindParam( ':post_status', $status );
    $stmt->bindParam( ':post_last_change', $date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      exit;
    }
  }
  // Fim da funções de vercionamento

  // Funções de status da publicação
  public function update_post_status($codeUser, $codePost, $status){
    $PDO = parent::conexao();
    $date = date('Y-m-d H:i:s');
    $sql = "UPDATE wa_linker SET post_status = :post_status, post_last_change = :post_last_change WHERE code_post = :code_post AND code_user = :code_user";
    $stmt = $PDO->prepare( $sql );
    $stmt->bindParam( ':code_user', $codeUser );
    $stmt->bindParam( ':code_post', $codePost );
    $stmt->bindParam( ':post_status', $status );
    $stmt->bindParam( ':post_last_change', $date );

    $result = $stmt->execute();

    if ( ! $result )
    {
      // var_dump( $stmt->errorInfo() );
      return 0;
    }
    return 1;
  }
  // FIM - Funções de status de publicações

  // PAGE FAST EDITION

  public function fast_post_update($codeUser, $codePost, $data){
    $this->codeUser = $codeUser;
    $this->codePost = $codePost;
    $rows = $this->get_publication_data($codeUser, $codePost, 'wa_linker.public_version, wa_linker.last_version, wa_linker.post_classification, wa_archived_posts.post_content');
    if($rows['status']=='1'){
      if($rows['data'][0]['last_version']=='0'){
        $this->full_publication_update('wa_archived_posts', $data['title'], $data['subtitle'], $data['img'], $rows['data'][0]['post_content'], 1);
        $this->update_linker_version(1);
        $this->update_post_metadata($data['classification']);
        return 1;
      }
      if($rows['data'][0]['public_version']==$rows['data'][0]['last_version']){
        $version = $rows['data'][0]['last_version']+1;
        $this->create_new_version('wa_archived_posts', $data['title'], $data['subtitle'], $data['img'], $rows['data'][0]['post_content'], $version);
        $this->update_linker_version($version);
        return 1;
      }
      $this->full_publication_update('wa_archived_posts', $data['title'], $data['subtitle'], $data['img'], $rows['data'][0]['post_content'], $rows['data'][0]['last_version']);
      $this->update_linker_version($rows['data'][0]['last_version']);
      return 1;
    }
    return 0;
  }
  // FIM - PAGE FAST EDITION




// Page Edition
  // public function select_data_post_update($code_user, $code_post, $selected){
  //   $PDO = parent::conexao();
  //
  //   $sql = "SELECT $selected FROM wa_archived_posts INNER JOIN wa_linker ON wa_linker.code_post = wa_archived_posts.code_post AND wa_linker.last_edition_version=wa_archived_posts.post_version AND wa_linker.code_user='$code_user' WHERE wa_archived_posts.code_post='$code_post' LIMIT 1";
  //   $result = $PDO->query( $sql );
  //   $rows = $result->fetchAll( PDO::FETCH_ASSOC );
  //   if(count($rows)==1){
  //       $rows = array('data'=>$rows[0], 'status'=>'1');
  //   }else{
  //     $rows = array('status'=>'0');
  //   }
  //   return $rows;
  // }

  // Parte edition dynamic

  // private function select_last_version($select){
  //   $PDO = parent::conexao();
  //   $sql = "SELECT $select FROM wa_archived_posts INNER JOIN wa_linker ON wa_linker.code_post = wa_archived_posts.code_post AND wa_linker.last_edition_version=wa_archived_posts.post_version AND wa_linker.code_user='$this->codeUser' WHERE wa_archived_posts.code_post='$this->codePost' LIMIT 1";
  //   $result = $PDO->query( $sql );
  //   $rows = $result->fetchAll( PDO::FETCH_ASSOC );
  //   if(count($rows)==1){
  //       $rows = array('data'=>$rows[0], 'status'=>'1');
  //   }else{
  //     $rows = array('status'=>'0');
  //   }
  //   return $rows;
  // }

  private function analyze_versions(){
    $PDO = parent::conexao();
    $sql = "SELECT id FROM wa_archived_posts WHERE code_post='$this->codePost'";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );

    $number = count($rows);
    if($number>=10){
      $remover = $number-5;
      for($i=$remover;$i<=$number-1;$i++){
        unset($rows[$i]);
      }
      $this->remove_obsolete_versions($rows);
      $rows = '1';
    }else{
      $rows = '0';
    }
    return $rows;
  }

  private function remove_obsolete_versions($ids){
    $PDO = parent::conexao();
    for($i=0;$i<=count($ids)-1;$i++){
      $id = $ids[$i]['id'];
      $sql = "DELETE FROM wa_archived_posts WHERE id = :id";
      $stmt = $PDO->prepare( $sql );
      $stmt->bindParam( ':id', $id );

      $result = $stmt->execute();

      if ( ! $result )
      {
        var_dump( $stmt->errorInfo() );
        exit;
      }

      echo $stmt->rowCount() . "linhas removidas";
    }
  }

  // Fim da parte edition dynamic
  private function license_type($li){
    switch ($li){
      case 'BY-SA':
       $license = 'CC-BY-SA';
       break;
      case 'BY':
       $license = 'CC-BY';
       break;
      case 'BY-ND':
       $license = 'CC-BY-ND';
       break;
      case 'BY-NC':
       $license = 'CC-BY-NC';
       break;
      case 'BY-NC-SA':
       $license = 'CC-BY-NC-SA';
       break;
      case 'BY-NC-ND':
       $license = 'CC-BY-NC-ND';
       break;
      case 'DP':
       $license = 'DP';
       break;
      case 'TDR':
       $license = 'COPY';
       break;
      default:
       $license = 'BY-SA';
       break;
    }
    return $license;
  }

  public function validate_date($dh){
    if (preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4} ([01][0-9]|2[0-3]):[0-5][0-9]$/", $dh)){
      // já temos um padrão inicial validado acima e a hora está ok.
      // validando a data
      $dh = explode(" ", $dh);
      $data = explode("/", $dh[0]);
      if (checkdate($data[1],$data[0],$data[2])){
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }
}
