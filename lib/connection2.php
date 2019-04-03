<?php
class WaManagerConnection{
  protected static $PDO;

  public function __construct(){
    $hostname = 'localhost';
    $username = 'root';
    $password = 'gabriel';
    $database = 'wa_manager';
    try {
        self::$PDO = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        // echo 'Conexao efetuada com sucesso!';
    }
    catch(PDOException $e){
      // echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
      echo 'Erro ao conectar com o banco de dados!';
      exit;
    }
  }

  public static function conexao()
   {
       # Garante uma única instância. Se não existe uma conexão, criamos uma nova.
       if (!self::$PDO)
       {
           new WaManagerConnection();
       }
       # Retorna a conexão.
       return self::$PDO;
   }

}
