// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.

function altura_textarea(){
    var a1 = window.innerHeight;
    var a2 = a1-163;
    document.getElementById('textarea').style.height=a2+"px";
    setTimeout("altura_textarea()",1000);
}

function open_menu(id){
  var menuValue = document.getElementById(id).style.display;
  if(menuValue!='block'){
    document.getElementById(id).style.display='block';
  }else{
    document.getElementById(id).style.display='none';
  }
}

function agendarP(){
  var div_agendar = document.getElementById("div_agendar").style.display;
  if(div_agendar != "block"){
    document.getElementById("div_agendar").style.display="block";
    agendaDate = new Date();
    document.getElementsByName("dia")[0].value = agendaDate.getDate();
    document.getElementsByName("mes")[0].value = agendaDate.getMonth();
    document.getElementsByName("ano")[0].value = agendaDate.getFullYear();
    document.getElementsByName("hora")[0].value = agendaDate.getHours();
    document.getElementsByName("minuto")[0].value = agendaDate.getMinutes();
  }else{
    document.getElementById("div_agendar").style.display="none";
  }
}

function format_date(){
  mes = parseInt(document.getElementsByName("mes")[0].value) + 1;
  date = document.getElementsByName("ano")[0].value+'-'+mes+'-'+document.getElementsByName("dia")[0].value;
  time = document.getElementsByName("hora")[0].value+':'+document.getElementsByName("minuto")[0].value+':00';
  full_date = date+' '+time;
  document.getElementsByName('date_published')[0].value=full_date;
}

// Fechar janela de dialog_msg
function exit_dialog_msg(element){
  document.getElementById(element).style.display="none";
}
function open_dialog_msg(element){
  document.getElementById(element).style.display="block";
}
function exit_dialog_login(){
  document.getElementById("dialog_login").style.display="none";
  conta = document.getElementsByName("login")[0].value;
  document.getElementById("name_conta").innerHTML="Publicar na conta: "+conta;
}
