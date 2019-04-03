// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
function send_preview(id){
	$.ajax({
		url: 'viewjs.php',
		type: 'post',
		dataType: 'html',
		data: {
			'id': id
		}
	}).done(function(data){
	});
}
function vcounter(id){
		setTimeout("send_preview("+id+")", 10000);
}
