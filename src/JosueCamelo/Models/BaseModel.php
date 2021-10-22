<?php

namespace JosueCamelo\Models;

use JosueCamelo\Utils\Queries;
class BaseModel
{
    private $queryBuilder;
    
    public function __construct()
    {
        $this->queryBuilder = new Queries();
    }
    
    public function getQueryBuilder(){
        return $this->queryBuilder;
    }
}
