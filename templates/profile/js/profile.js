// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
function seguir(user){
		if(document.getElementById('button').title == 'Enviando...'){
			return(false);
		}
    if(document.getElementById('button').title == 'Seguindo'){
			unfollow(user);
			return(false);
		}

    document.getElementById('button').innerHTML="Enviando...";
		document.getElementById('button').title="Enviando...";

		$.ajax({
			url: 'seguir-dados.php',
			type: 'post',
			dataType: 'html',
			data: {
				'user_followed': user,
				'type': 1
			}
		}).done(function(data){
			if(data==='1'){
				document.getElementById('button').innerHTML="Seguindo";
			  document.getElementById('button').title="Seguindo";
			}else if(data==='0'){
				document.getElementById('button').innerHTML='SEGUIR +';
				document.getElementById('button').title='SEGUIR +';
			}else{
				document.getElementById('button').innerHTML='SEGUIR +';
				document.getElementById('button').title='SEGUIR +';
				console.log('Erro ao logar!');
				show_container_login(1);
			}

		});

}

function follower_search(page_user){
		$.ajax({
			url: 'search-follower.php',
			type: 'post',
			dataType: 'html',
			data: {
				'page_user': page_user
			}
		}).done(function(data){
			if(data==='1'){
				document.getElementById('button').innerHTML = 'Seguindo';
			  document.getElementById('button').title = 'Seguindo';
			}
		});

}

function unfollow(user){
	if(document.getElementById('button').title == 'Enviando...'){
		return(false);
	}
	if(document.getElementById('button').title == 'SEGUIR +'){
		seguir();
		return(false);
	}

	document.getElementById('button').innerHTML="Enviando...";
	document.getElementById('button').title="Enviando...";

	$.ajax({
		url: 'seguir-dados.php',
		type: 'post',
		dataType: 'html',
		data: {
			'user_followed': user,
			'type': 0
		}
	}).done(function(data){
		if(data==='1'){
			document.getElementById('button').innerHTML="SEGUIR +";
			document.getElementById('button').title="SEGUIR +";
		}else if(data==='0'){
			document.getElementById('button').innerHTML='Seguindo';
			document.getElementById('button').title='Seguindo';
		}else{
			document.getElementById('button').innerHTML='Seguindo';
			document.getElementById('button').title='Seguindo';
			console.log('Erro ao logar!');
			show_container_login(1);
		}

	});
}

function show_container_login(show_t){
	if(show_t===1){
		document.getElementById('container_login').style.display='block';
	}else{
		document.getElementById('container_login').style.display='none';
	}
}
