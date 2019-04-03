private function logbusca($q, $resultado){
  include("connection2.php");
  include_once("functionBasicas.class.php");

  $data = date('Y-m-d H:i:s');

  $ip = new functionBasicas();
  $ip->IPuser();

  $sql = "INSERT INTO log_busca(busca, IP_user, data, resultado) VALUES(:busca, :IP_user, :data, :resultado)";
  $stmt = $PDO->prepare( $sql );
  $stmt->bindParam( ':busca', $q );
  $stmt->bindParam( ':IP_user', $ip->IP_user );
  $stmt->bindParam( ':data', $data );
  $stmt->bindParam( ':resultado', $resultado );

  $result = $stmt->execute();

  if ( ! $result )
  {
      var_dump( $stmt->errorInfo() );
      exit;
  }
  // echo "log com sucesso!";
}
