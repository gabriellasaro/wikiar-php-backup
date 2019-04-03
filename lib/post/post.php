<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");

class PostPage extends connection{

  public function __construct($codePost){
    $this->codePost = $codePost;
  }

  private function create_cache(){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.*, wa_linker.code_user, wa_linker.reading_time, wa_linker.post_language, wa_linker.post_date, wa_linker.comment_status, wa_linker.post_classification FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post = wa_posts.code_post AND wa_linker.public_version=wa_posts.post_version WHERE wa_posts.code_post='$this->codePost' AND wa_linker.post_status='1' LIMIT 1";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    if(empty($rows)){
      return 0;
    }
    require_once(dirname(dirname(__FILE__)).'/profile/profile.php');
    $perfil = new WaProfile();
    $profile = $perfil->info_profile('username, user_nicename, user_img, user_description, user_status', $rows[0]['code_user'], 1);
    if($profile['user_status'] == '1'){
      unset($profile['user_status'], $rows[0]['code_user']);
      require_once(dirname(dirname(__FILE__)).'/data_statistics/post-views.php');
      require_once(dirname(dirname(__FILE__)).'/data_statistics/recommendation.php');
      $accesses = new PostViews($rows[0]['code_post']);
      $recommend = new Recommend($rows[0]['code_post']);
      $recommend = $recommend->search_tb_info();
      if($recommend == null){
        $rows[0]['post_recommend'] = 0;
      }else{
        $rows[0]['post_recommend'] = $recommend;
      }
      $accesses = $accesses->search_accesses();
      if($accesses['status']=='0'){
        $rows[0]['post_accesses'] = 0;
      }else{
        $rows[0]['post_accesses'] = $accesses['data']['accesses']+$accesses['data']['access_male']+$accesses['data']['access_female'];
      }
      $rows = array('post'=>$rows[0], 'user'=>$profile, 'suggestions'=>null);
      $arquivojson = json_encode($rows);
      $caminho = dirname(dirname(dirname(__FILE__))).'/cache/cache_post/'.$this->codePost.'.json';
      file_put_contents($caminho, $arquivojson);
      return 1;
    }
    return 0;
  }

  public function check(){
    // Configurações
    $validadeSegundos = 0;
    $arquivoCache = dirname(dirname(dirname(__FILE__))).'/cache/cache_post/'.$this->codePost.'.json';

    // Verifica se o arquivo cache existe e se ainda é válido
    if (file_exists($arquivoCache) && (filemtime($arquivoCache) > time() - $validadeSegundos)) {
      return 1;
    }
    return $this->create_cache();
  }

  public function show(){
    $arquivoCache = dirname(dirname(dirname(__FILE__))).'/cache/cache_post/'.$this->codePost.'.json';
    $conteudo = file_get_contents($arquivoCache);
    $dados = json_decode($conteudo, true);
    return $dados;
  }

}
