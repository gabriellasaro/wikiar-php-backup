<?php
// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");

class TagsPage extends connection{

  private function search_post_tag(){
    $PDO = parent::conexao();
    $sql = "SELECT code_post, tag_name FROM wa_tags WHERE code_tag='$this->tag' AND tag_status='1' ORDER BY register DESC LIMIT 40";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }

  private function create_cache(){
    require_once(dirname(dirname(__FILE__)).'/profile/profile.php');
    $perfil = new WaProfile();
    $dataTags = $this->search_post_tag();
    if(empty($dataTags)){
      return 0;
    }
    $preCache = array(array('tag_name'=>$dataTags[0]['tag_name']));
    $PDO = parent::conexao();
    foreach ($dataTags as $key => $value) {
      $id = $dataTags[$key]['code_post'];
      $sql = "SELECT wa_linker.code_user, wa_posts.code_post, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img FROM wa_posts INNER JOIN wa_linker ON wa_linker.code_post=wa_posts.code_post WHERE wa_posts.code_post=$id AND wa_linker.post_status='1' LIMIT 1";
      $result = $PDO->query( $sql );
      $rows = $result->fetchAll( PDO::FETCH_ASSOC );
      if(count($rows)==1){
        $profile = $perfil->info_profile('username, user_nicename, user_img, user_status', $rows[0]['code_user']);
        if($profile['user_status'] == '1'){
          unset($profile['user_status'], $rows[0]['code_user']);
          $rows[0] = array_merge($rows[0], array('user'=>$profile));
          $preCache = array_merge($preCache, $rows);
        }
      }
    }
    if(empty($preCache[1])){
      return 0;
    }
    $arquivojson = json_encode($preCache);
    $caminho = dirname(dirname(dirname(__FILE__))).'/cache/cache_tags/'.$this->tag.'.json';
    file_put_contents($caminho, $arquivojson);
    return 1;
  }

  public function check_cache($tag){
    $this->tag = $tag;
    // Configurações
    $validadeEmSegundos = 600;
    $arquivoCache = dirname(dirname(dirname(__FILE__))).'/cache/cache_tags/'.$this->tag.'.json';
    // Verifica se o arquivo cache existe e se ainda é válido
    if (file_exists($arquivoCache) && (filemtime($arquivoCache) > time() - $validadeEmSegundos)) {
      return 1;
    }
    // Cria o cache
    if($this->create_cache()==1){
      return 1;
    }
    return 0;
  }

  public function tag_json(){
    $file_conteudo = file_get_contents(dirname(dirname(dirname(__FILE__))).'/cache/cache_tags/'.$this->tag.'.json');
    $dados = json_decode($file_conteudo, true);
    return $dados;
  }

}
