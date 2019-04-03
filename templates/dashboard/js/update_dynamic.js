// COPYRIGHT 2017 - Gabriel Lasaro, Todos os direitos reservados.
function update_dynamic(id){
	if(document.getElementById('loading-msg').style.display == 'block'){
		console.log('aguarde...');
		return(false);
	}
	document.getElementById('loading-msg').style.display='block';
	$.ajax({
		url: 'process.php',
		type: 'post',
		dataType: 'html',
		data: {
			'id': id,
			'title': document.getElementsByName('title')[0].value,
			'subtitle': document.getElementsByName('subtitle')[0].value,
			'capa': document.getElementsByName('capa')[0].value,
			'cla': document.getElementsByName('cla')[0].value,
			'lang': $(".language").val(),
			'status': document.getElementsByName('status')[0].value
		}
	}).done(function(data){
		console.log(data);
		document.getElementById('loading-msg').style.display='none';
		if(data==='1' || data==='2'){
			document.getElementById('success-msg').style.display='block';
		}else{
			document.getElementById('danger-msg').style.display='block';
			document.getElementById('p-danger').innerHTML='Erro ao atualizar!';
		}

	});
}
