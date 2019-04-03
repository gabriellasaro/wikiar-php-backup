// COPYRIGHT 2017 - Gabriel Lasaro, Todos os direitos reservados.
function menu(pasta){
  var menu = document.getElementById('menu_lateral').style.display;
  var seta_menu = document.getElementById('seta_topoMENU').style.display;
  if(menu === "block"){
    document.getElementById('menu_lateral').style.display='none';
    if(pasta === "0"){
      document.getElementById('img-menu').src="img/menu.svg";
    }else{
    document.getElementById('img-menu').src=pasta+"img/menu.svg";
    }
    document.getElementById('seta_topoMENU').style.display='none';
  }else{
    document.getElementById('menu_lateral').style.display='block';
    if(pasta === "0"){
      document.getElementById('img-menu').src="img/voltar.svg";
    }else{
    document.getElementById('img-menu').src=pasta+"img/voltar.svg";
    }
    document.getElementById('seta_topoMENU').style.display='block';
  }
}
