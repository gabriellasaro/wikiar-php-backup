<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");

class ProfilePage extends connection{

  public function __construct($username){
    $this->username = $username;
  }

  public function search_user_posts($codeUser){
    $PDO = parent::conexao();
    $sql = "SELECT wa_posts.code_post, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post = wa_posts.code_post WHERE wa_linker.code_user = '$codeUser' AND wa_linker.post_status = '1' ORDER BY wa_posts.post_modified DESC";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return array('posts'=>$rows);
  }

  private function create_cache(){
    require_once(dirname(dirname(__FILE__)).'/profile/profile.php');
    $perfil = new WaProfile();
    $profile = $perfil->info_profile('code_user, user_nicename, user_img, user_capa, user_description, user_address, user_followers, user_sex, user_language, user_status, verified_user', $this->username, 1, 1);
    if($profile['user_status'] == '1'){
      $data = $this->search_user_posts($profile['code_user']);
      unset($profile['user_status']);
      $rows = array_merge($data, array('user'=>$profile));
    }else{
      return $profile['user_status'];
    }
    $arquivojson = json_encode($rows);
    $caminho = dirname(dirname(dirname(__FILE__))).'/cache/cache_profile/'.$this->username.'.json';
    file_put_contents($caminho, $arquivojson);
    return 1;
  }

  public function check(){
    $arquivo = dirname(dirname(dirname(__FILE__)))."/cache/cache_profile/$this->username.json";
    if (file_exists($arquivo) && (filemtime($arquivo) > time() - 600)) {
      return 1;
    }
    return $this->create_cache();
  }

  public function show(){
    $arquivoCache = dirname(dirname(dirname(__FILE__)))."/cache/cache_profile/$this->username.json";
    $conteudo = file_get_contents($arquivoCache);
    $dados = json_decode($conteudo, true);
    return $dados;
  }
}
