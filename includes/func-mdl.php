<?php
function menu_oculto_mdl(){
  return '<nav class="mdl-navigation">
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/">WikiAr</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/">Dashboard</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/new/">Nova publicação</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/list/">Lista de publicações</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/statistics/">Estatísticas</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/likes/">Minhas remendações</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/galery">Galeria</a>
          <a class="mdl-navigation__link" href="http://'.$_SERVER['HTTP_HOST'].'/WikiAr/dashboard/settings/">Configurações</a>
        </nav>';
}
