<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
require_once(dirname(dirname(__FILE__))."/connection.php");

class HomePage extends connection{

  public function check_cache(){
    // Configurações
    $validadeEmSegundos = 600;
    $arquivoCache = dirname(dirname(dirname(__FILE__))).'/cache/home/home.json';

    // Verifica se o arquivo cache existe e se ainda é válido
    if (file_exists($arquivoCache) && (filemtime($arquivoCache) > time() - $validadeEmSegundos)) {
      return 1;
    }
    // Cria o cache
    require_once(dirname(dirname(__FILE__)).'/tags/tags.php');
    $tags = new WaTags();
    $tags->list_top_tags();
    return $this->create_cache();
  }

  public function home_json($file = 'home'){
    $arquivoCache = dirname(dirname(dirname(__FILE__))).'/cache/home/'.$file.'.json';
    $conteudo = file_get_contents($arquivoCache);
    $dados = json_decode($conteudo, true);
    return $dados;
  }

  private function create_cache(){
    $this->highlights();
    $arquivojson = json_encode($this->data);
    $caminho = dirname(dirname(dirname(__FILE__)))."/cache/home/home.json";
    file_put_contents($caminho, $arquivojson);
    return 1;
  }

  public function Highlights($quantReturn = 25, $category = 'default'){
    $this->quantReturn = $quantReturn;
    $this->data = $this->search_posts();
    $this->calculate_points();
    $this->reorder_list();
    return $this->data;
  }

  private function reorder_list(){
    for ( $i = 0; $i < count($this->data); $i++){
      for ($j = 0; $j < count($this->data); $j++){
        if($this->data[$i]['points'] > $this->data[$j]['points']){
          $codePost = $this->data[$i]['code_post'];
          $postTitle = $this->data[$i]['post_title'];
          $postSubtitle = $this->data[$i]['post_subtitle'];
          $postImg = $this->data[$i]['post_img'];
          $points = $this->data[$i]['points'];

          $this->data[$i]['code_post'] = $this->data[$j]['code_post'];
          $this->data[$i]['post_title'] = $this->data[$j]['post_title'];
          $this->data[$i]['post_subtitle'] = $this->data[$j]['post_subtitle'];
          $this->data[$i]['post_img'] = $this->data[$j]['post_img'];
          $this->data[$i]['points'] = $this->data[$j]['points'];

          $this->data[$j]['code_post'] = $codePost;
          $this->data[$j]['post_title'] = $postTitle;
          $this->data[$j]['post_subtitle'] = $postSubtitle;
          $this->data[$j]['post_img'] = $postImg;
          $this->data[$j]['points'] = $points;
        }
      }
    }
  }

  private function calculate_points(){
    for($i=0;$i<=count($this->data)-1;$i++){
      $pointsRecommend = $this->data[$i]['recommend']*10;
      $media = ($this->data[$i]['recommend']**3)+$this->data[$i]['accesses']+$this->data[$i]['access_male']+$this->data[$i]['access_female'];
      $this->data[$i]['points'] = $media/3;
      unset($this->data[$i]['recommend'], $this->data[$i]['accesses'], $this->data[$i]['access_male'], $this->data[$i]['access_female']);
    }
  }

  private function search_posts(){
    $PDO = parent::conexao();
    $sql = "SELECT wa_linker.code_post, wa_posts.post_title, wa_posts.post_subtitle, wa_posts.post_img, wa_profile.username, wa_profile.user_nicename, wa_profile.user_img, wa_post_statistics.accesses, wa_post_statistics.access_male, wa_post_statistics.access_female, wa_post_statistics.recommend FROM wa_post_statistics INNER JOIN wa_linker ON wa_post_statistics.code_post=wa_linker.code_post AND wa_linker.post_status='1' INNER JOIN wa_posts ON wa_linker.code_post=wa_posts.code_post INNER JOIN wa_profile ON wa_profile.code_user=wa_linker.code_user WHERE wa_post_statistics.recommend >= 1 ORDER BY wa_linker.post_last_change DESC LIMIT $this->quantReturn";
    $result = $PDO->query( $sql );
    $rows = $result->fetchAll( PDO::FETCH_ASSOC );
    return $rows;
  }
}
