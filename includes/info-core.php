<?php
// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
class InfoCore{
  public $name = 'WikiAr';
  public $subname = 'artigos rápidos';
  public $description = 'Um jeito novo e facil de criar e compartilhar com seus amigos!';
  public $version = '0.1';
  public $dateRelease = '02/09/2017';
  public $img_logo = 'img/WikiAr_logo.svg';
  public $created_by = 'Gabriel Lasaro';
  public $themeColor = '#808080';
  public $location_created = 'FEITO COM <span style="color:red;font-size:24px;" title="AMOR">&#10084;</span> EM COLATINA';
  public $copyright = '© COPYRIGHT 2017 WikiAr, Todos os direitos reservados.';
  public $min_copyright = 'Copyright (c) WikiAr';
  public $analyticstracking = '';
  public function language_acronyms($language){
    $list = array(
      'pt-br'=>'Português do Brasil',
      'pt'=>'Português',
      'en'=>'English',
      'fr'=>'Français',
      'it'=>'Italiano',
      'de'=>'Deutsch',
      'es'=>'Español',
      'ca'=>'Català',
      'vi'=>'Tiếng Việt',
      'id'=>'Bahasa Indonesia',
      'ms'=>'Bahasa Melayu',
      'ru'=>'Русский',
      'ro'=>'Română',
      'tr'=>'Türkçe',
      'ja'=>'日本語',
      'zh'=>'中文',
      'ar'=>'العربية'
    );
    $a = 0;
    $i = 0;
    $list_keys = array_keys($list);
    while($a == 0){
      if($list_keys[$i]==$language){
        $a = 1;
        $pre = $list_keys[$i];
        $language = $list[$pre];
      }
      if($i>=count($list)){
        $a = 1;
      }
      $i++;
    }
    return $language;
  }
}
