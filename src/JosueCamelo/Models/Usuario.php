<?php

namespace JosueCamelo\Models;

class Usuario extends BaseModel
{
    private $table = 'usuarios';
    
    public function getAll(){
        $res = $this->getQueryBuilder()->queryExec("SELECT * FROM usuarios", []);
        print_r($res);
    }
    
    public function autenticar($email, $senha){
        $senha = base64_encode($senha);
        $res = $this->getQueryBuilder()->queryExec("SELECT * FROM $this->table where email = '$email' and senha='$senha'");
        
        return $res;
    }
}
