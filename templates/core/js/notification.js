// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
var notify = function() {
  if(!window.Notification) {
    console.log('Este browser não suporta Web Notifications!');
    return;
  }

  if (Notification.permission === 'default') {
    Notification.requestPermission(function() {
      console.log('Usuário não falou se quer ou não notificações. Logo, o requestPermission pede a permissão pra ele.');
    });
  } else if (Notification.permission === 'granted') {
    console.log('Usuário deu permissão');
  } else if (Notification.permission === 'denied') {
    console.log('Usuário não deu permissão');
  }

};
function notifica(msg){
	var notification = new Notification('WikiAr', {
	 body: msg,
	 tag: 'string única que previne notificações duplicadas',
	});
	notification.onshow = function() {
	 console.log('onshow: evento quando a notificação é exibida')
	},
	notification.onclick = function() {
	 console.log('onclick: evento quando a notificação é clicada')
	},
	notification.onclose = function() {
	 console.log('onclose: evento quando a notificação é fechada')
	},
	notification.onerror = function() {
	 console.log('onerror: evento quando a notificação não pode ser exibida. É disparado quando a permissão é defualt ou denied')
	}
}
