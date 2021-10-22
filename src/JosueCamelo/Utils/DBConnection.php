<?php

namespace JosueCamelo\Utils;

class DBConnection
{
    private $dbHostname = "localhost";
    private $dbName = "testeapiphppuro";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $con;
    
    public function __construct() {
        try {
            $this->con = new \PDO("mysql:host=$this->dbHostname;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
            $this->con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            echo "<h1>Ops, algo deu errado: " . $e->getMessage()."</h1>";
            echo "<pre>";
            echo print_r($e);
        }
        
    }
    
    public function getConnection() {
        return $this->con;
    }
}
