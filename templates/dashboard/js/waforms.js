// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
function character_limit(limit, field, field1){
  numberC = document.getElementsByName(field)[0].value.length;
  resto = limit-numberC;
  document.getElementById(field1).innerHTML="Caracteres restantes: "+resto;
}
