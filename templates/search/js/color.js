// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
function numbers(){
  return Math.floor(Math.random() * 256);
}
function color(){
  cor = "rgb("+numbers()+", "+numbers()+", "+numbers()+")";
  document.body.style.backgroundColor = cor;
  document.body.style.transitionDuration = '1s';
  setTimeout("color()",4000);
}
window.load = color();
