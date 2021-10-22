<?php

namespace JosueCamelo\Utils;

class Queries
{
    private $con;
    
    public function __construct()
    {
        $db = new DBConnection();
        $this->con = $db->getConnection();
    }
    
    public function queryExec($statement, $params = array())
    {
        $stmt = $this->con->prepare($statement);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        
        return $rows;
    }
}
