<?php
class WaLocalSession{

  public function safe_session() {
    $session_name = 'sec_session_id';   // Estabeleça um nome personalizado para a sessão
    $secure = false;
    // Isso impede que o JavaScript possa acessar a identificação da sessão.
    $httponly = true;
    // Assim você força a sessão a usar apenas cookies.
   if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../../falhou.php?msg=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Obtém params de cookies atualizados.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Estabelece o nome fornecido acima como o nome da sessão.
    session_name($session_name);
    session_start();            // Inicia a sessão PHP
    session_regenerate_id();    // Recupera a sessão e deleta a anterior.
  }

  public function logout(){
    $this->safe_session();
    require('session.php');
    $logount_db = new WaSession();
    $logount_db->logout_session($_SESSION['user_number'], $_SESSION['token']);
    // Desfaz todos os valores da sessão
    $_SESSION = array();

    // obtém os parâmetros da sessão
    $params = session_get_cookie_params();

    // Deleta o cookie em uso.
    setcookie(session_name(),
        '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]);

    // Destrói a sessão
    session_destroy();
  }

  public function session_check() {
    // Verifica se todas as variáveis das sessões foram definidas
    if (isset($_SESSION['user_number'], $_SESSION['username'], $_SESSION['full_name'], $_SESSION['user_img'], $_SESSION['user_sex'], $_SESSION['user_lang'], $_SESSION['user_status'], $_SESSION['token'])) {
      return true;
    }else {
      // Não foi logado
      return false;
    }
  }

}
