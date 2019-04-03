<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$tags = ' #brasil, #mundo #bill gAte #windows';
echo $tags.'<hr>';

$array_tags = explode("#", $tags);
print_r($array_tags);
$total = count($array_tags)-1;
$list_remove = array(" ", ",");
$list_subs = array("-", "");
for($i=0; $i<=$total; $i++){
  $array_tags[$i] = trim(strtolower($array_tags[$i]));
  if(strlen($array_tags[$i])==0){
    unset($array_tags[$i]);
  }else{
    $array_tags[$i] = str_replace($list_remove, $list_subs, $array_tags[$i]);
  }
}

print_r($array_tags);
