// COPYRIGHT 2017 - Gabriel Lasaro, Todos os direitos reservados.
function save(id){
	if(document.getElementById('container_loading').style.display == 'block'){
		console.log('aguarde...');
		return(false);
	}
	document.getElementById('container_loading').style.display='block';
	$.ajax({
		url: 'process.php',
		type: 'post',
		dataType: 'html',
		data: {
			'id': id,
      'wiki': editor.getValue()
		}
	}).done(function(data){
		document.getElementById('container_loading').style.display='none';
		console.log(data);
		if(data==='1'){
			console.log('Salvo com sucesso!');
		}else if(data==='L'){
			console.log('Falha, login necessário!');
			document.getElementById('container_msg').style.display='block';
			document.getElementById('id_msg_loading').innerHTML='Falha, login necessário!';
		}else if(data==='0'){
			console.log('Falha ao salvar no servidor!');
			document.getElementById('container_msg').style.display='block';
			document.getElementById('id_msg_loading').innerHTML='Falha ao salvar no servidor, tente mais tarde!';
		}else{
			console.log('Falha, campo vazio e/ou alteração no formulário!');
			document.getElementById('container_msg').style.display='block';
			document.getElementById('id_msg_loading').innerHTML='Falha, campo vazio e/ou alteração no formulário!';
		}
	});
}

function publish(id){
	if(document.getElementById('container_loading').style.display == 'block'){
		console.log('aguarde...');
		return(false);
	}
	document.getElementById('container_loading').style.display='block';
	$.ajax({
		url: 'publish.php',
		type: 'post',
		dataType: 'html',
		data: {
			'id': id
		}
	}).done(function(data){
		console.log(data);
		document.getElementById('container_loading').style.display='none';
		if(data==='1'){
			document.getElementById('container_success').style.display='block';
		}else if (data==='404') {
			document.getElementById('container_msg').style.display='block';
			document.getElementById('id_msg_loading').innerHTML='Nada encontrado para publicar!';
		}else{
			document.getElementById('container_msg').style.display='block';
			document.getElementById('id_msg_loading').innerHTML='Erro ao publicar última versão!';
		}

	});
}
