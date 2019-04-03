// COPYRIGHT 2017 Gabriel Lasaro, Todos os direitos reservados.
function update_status(id, status){
		if(document.getElementById('status').title == 'Processando...'){
			return(false);
		}
		document.getElementById('status').title="Processando...";
		$.ajax({
			url: 'update_status.php',
			type: 'post',
			dataType: 'html',
			data: {
				'id': id,
				'status': status
			}
		}).done(function(data){
			document.getElementById('status').title="Concluido!";
      if(data==='1'){
				if(status=="D"){
				  alert('Post deletado!');
				}else{
					alert('Publicação recuperada!');
				}
				location.reload();
      }else{
        console.log('Erro!');
				if(status=="D"){
					alert('Erro ao deletar!');
				}else{
					alert('Erro ao recuperar!');
				}
      }
		});
}
