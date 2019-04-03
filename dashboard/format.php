<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$texto = ":p>>style(color:#ff0000;)>>No meio de todas as gigantes que estiveram no :l>>url>>http://www.tecmundo.com.br/mwc-2017/>>Mobile World Congress 2017<<, uma das companhias que mais gerou burburinho nem era conhecida há poucos anos. A HMD Global, nova detentora da marca Nokia, levou muita gente ao estande em Barcelona para a apresentação de produtos com o :l>>url>>http://www.tecmundo.com.br/nokia-3/>>Nokia 3<< (acompanhado do já conhecido :l>>url>>http://www.tecmundo.com.br/nokia-6/>>Nokia 6<<) e do relançamento do :l>>url>>http://www.tecmundo.com.br/mwc-2017/114601-nokia-3310-realmente-volta-dessa-vez-estilo.htm>>Nokia 3310<<.
:p>>Mas e o Brasil, como fica? Afinal, o público ainda é apaixonado pela empresa, mesmo depois de tanto tempo longe — desde os feature phones até a família Lumia. Em entrevista exclusiva ao TecMundo durante a feira, o chefe de marketing da marca, Pekka Rantala falou que \"com certeza\" estão olhando para o Brasil no que se refere a futuros lançamentos.";

function p_simples($txt){
  $pre_txt = explode(":p>>", $txt);
  $final_txt = "";
  for($i=0; $i<=count($pre_txt); $i++){
    if(strlen($pre_txt[$i]) == 0){
      unset($pre_txt[$i]);
    }elseif(substr($pre_txt[$i], 0, 6)=='style('){
      $string2 = substr($pre_txt[$i], 6);

      $txt = explode(')', $string2);

      $txt_style = $txt[0];
      unset($txt[0]);
      $txt = substr(implode($txt), 2);

      $final_txt .='<p style="'.$txt_style.'">'.$txt.'</p>';

    }else{
      $final_txt .= "<p>".trim($pre_txt[$i])."</p>";
    }
  }
  return $final_txt;
}

function link_simples($txt){
  $pre_txt = explode(":l>>", $txt);

  for($i=0; $i<=count($pre_txt)-1; $i++){
    if(strlen($pre_txt[$i]) == 0){
      unset($pre_txt[$i]);
    }elseif(substr($pre_txt[$i], 0, 5)=='url>>'){
      $string = substr($pre_txt[$i], 5);

      $txt_part1 = explode('>>', $string);
      $txt_part1[0] = '<a href="'.$txt_part1[0].'">';

      $txt_part2 = explode('<<', $txt_part1[1]);
      $txt_part2[0] = $txt_part2[0].'</a>';

      unset($txt_part1[1]);

      $pre_txt[$i] = $txt_part1[0].$txt_part2[0].$txt_part2[1];
    }
  }
  $pre_txt = implode($pre_txt);
  return $pre_txt;
}
$a = p_simples($texto);
// p_simples($texto);
echo link_simples($a);
