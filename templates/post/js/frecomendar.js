// COPYRIGHT 2016-2017 Gabriel Lasaro, Todos os direitos reservados.
function frecomendar(id){
		if(document.getElementById('img_r').title == 'Enviando...'){
			return(false);
		}

		document.getElementById('img_r').title="Enviando...";
		document.getElementById('img_r').style.cursor="progress";

		$.ajax({
			url: 'frecomendar.php',
			type: 'post',
			dataType: 'html',
			data: {
				'id': id
			}
		}).done(function(data){
      if(data==='1'){
			  document.getElementById('img_r').src="../img_material_icons/ic_star_white_48px.svg";
			  document.getElementById('img_r').title="DEIXAR DE RECOMENDAR";
        document.getElementById('img_r').style.cursor="pointer";
      }else if(data==='0'){
        document.getElementById('img_r').src="../img_material_icons/ic_star_border_white_48px.svg";
			  document.getElementById('img_r').title="RECOMENDAR";
			  document.getElementById('img_r').style.cursor="pointer";
      }else{
        document.getElementById('img_r').title="RECOMENDAR";
        document.getElementById('img_r').style.cursor="pointer";
        console.log('É necessário fazer login!');
				document.getElementsByClassName("container-login")[0].style.display="block";
      }
		});
}
function closeClass(){
	document.getElementsByClassName("container-login")[0].style.display="none";
}

function sR(id){
		$.ajax({
			url: 'searchR.php',
			type: 'post',
			dataType: 'html',
			data: {
				'id': id
			}
		}).done(function(data){
			if(data==='1'){
        document.getElementById('img_r').src="../img_material_icons/ic_star_white_48px.svg";
			  document.getElementById('img_r').title="DEIXAR DE RECOMENDAR";
			}
		});
}
