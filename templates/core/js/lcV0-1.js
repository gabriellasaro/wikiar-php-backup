// COPYRIGHT 2016 - Gabriel Lasaro, Todos os direitos reservados.
function exitmsg(){
  document.getElementById('msg-error').innerHTML='';
  moveRelogio();
}
function tempo(){
  momentoAtual = new Date()
  segundo = momentoAtual.getSeconds()
  setTimeout("tempo()",3000);
}
var a = 1
var b = 300
var e = 10
function moveRelogio(){
  a++
  c = b - a
  d = e - a
  document.getElementById('msg-error').style.width=c+"px";
  document.getElementById('msg-error').style.padding=d+"px";

  setTimeout("moveRelogio()",1)
  if (document.getElementById('msg-error').style.width <= "0px") {
    document.getElementById('msg-error').style.display='none';
  }
}
function senhav(){
var campo1 = document.getElementsByName('senha')[0].value;
var campo2 = document.getElementsByName('senhaV')[0].value;
if(campo1 != campo2){
  document.getElementById('statussenha').style.display='block';
  document.getElementById('statussenha').style.color='#FF0000';
  document.getElementById('statussenha').innerHTML="Senhas diferentes!";
}else {
  document.getElementById('statussenha').style.color='#4169E1';
  document.getElementById('statussenha').innerHTML="Senhas iguais!";
  if(caracsenha(campo1, campo2) === "1"){
    document.getElementById('statussenha').innerHTML="Senhas iguais!";
  }else {
    document.getElementById('statussenha').innerHTML="Senhas iguais! - <span style='color:#FF0000'>Deve ter pelo menos 8 caracteres!</span>";
  }
}
setTimeout("senhav()",1000);

}
function caracsenha(campo1, campo2){
ccarac1 = campo1.length;
ccarac2 = campo2.length;
if(ccarac1 === ccarac2){
  if(ccarac1 >= 8){
    return "1";
  }else {
    return "0";
  }
}else{
  return "0";
}
}
