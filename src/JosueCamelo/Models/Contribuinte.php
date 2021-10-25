<?php

namespace JosueCamelo\Models;

class Contribuinte extends BaseModel
{
    private $table = 'contribuintes';
    
    private $fillabled = [
        'tipo_pessoa',  'tipo_contribuinte',  'tipo_cadastro',  'documento',  'estado',
        'ie', 'im',  'razao_social',  'nome_fantasia',  'telefone_principal',  'telefone_secundario',  'email',  'end_cep',
        'end_logradouro',  'end_numero',  'end_complemento',  'end_bairro',  'end_cidade',  'end_pais',  'observacoes', 'end_cidade',  'end_pais'
    ];
    
    public function getAll(){
        $res = $this->getQueryBuilder()->queryExec("SELECT * FROM $this->table", []);
        print_r($res);
    }
    
    public function inserir($data){
        if($data['tipo_pessoa'] == 'Física'){
            $data['nome_fantasia'] = '';
        }
        
        
        $sql = "INSERT INTO `contribuintes` (`tipo_pessoa`, `tipo_contribuite`, `tipo_cadastro`, `documento`, `estado`, `ie`, `im`, ";
        $sql.= "`razao_social`, `nome_fantasia`, `telefone_principal`, `telefone_secundario`, `email`, `end_cep`, `end_logradouro`, ";
        $sql.= "`end_numero`, `end_complemento`, `end_bairro`, `end_cidade`, `end_pais`, `observacoes`)";
        $sql.= "VALUES ('".$data['tipo_pessoa']."', '".$data['tipo_contribuinte']."', '".$data['tipo_cadastro']."', ";
        $sql.= "'".$data['documento']."', '".$data['estado']."', '".$data['ie']."', '".$data['im']."', '".$data['razao_social']."', '".$data['nome_fantasia']."', ";
        $sql.= "'".$data['telefone_principal']."', '".$data['telefone_secundario']."', '".$data['email']."', '".$data['end_cep']."', ";
        $sql.=" '".$data['end_logradouro']."', '".$data['end_numero']."', '".$data['end_complemento']."', '".$data['end_bairro']."', '".$data['end_cidade']."', '".$data['end_pais']."', '".$data['observacoes']."')";
        
        try {
            $this->getQueryBuilder()->getConection()->beginTransaction();
            $this->getQueryBuilder()->queryExec($sql, []);
            $this->getQueryBuilder()->getConection()->commit();
        }catch (Exception $e){
            $this->getQueryBuilder()->getConection()->rollback();
            throw $e;
        }
        
        return TRUE;
    }
    
    public function update($identificador, $data){
        $campoWhere = 'id';
        $busca = 0;
    
        if($data['tipo_pessoa'] == 'Física'){
            $data['nome_fantasia'] = '';
        }
        
        $sql = "UPDATE $this->table SET ";
        
        foreach($data as $campo => $valorCampo){
            $sql.= " $campo = '". $valorCampo."', ";
        }
        $sql = substr($sql, 0, -2);
    
        if(count($this->getId($identificador)) == 1){
          $busca++;
        }
        
        if(count($this->getByDocumento($identificador)) == 1){
            $busca++;
            $campoWhere = 'documento';
        }
        
        if($busca == 0){
            throw new \Exception('Não foi possível localizar nenhum contribuinte com o identificador passado');
        }else{
            $sql .= " WHERE $campoWhere = '$identificador'";
    
            try {
                $this->getQueryBuilder()->getConection()->beginTransaction();
                $this->getQueryBuilder()->queryExec($sql, []);
                $this->getQueryBuilder()->getConection()->commit();
            }catch (Exception $e){
                $this->getQueryBuilder()->getConection()->rollback();
                throw $e;
            }
        }
        
        return TRUE;
    }
    
    public function remover($identificador){
        $campoWhere = 'id';
        $busca = 0;
        
        $sql = "DELETE FROM $this->table ";
        
        if(count($this->getId($identificador)) == 1){
            $busca++;
        }
    
        if(count($this->getByDocumento($identificador)) == 1){
            $busca++;
            $campoWhere = 'documento';
        }
    
        if($busca == 0){
            throw new \Exception('Não foi possível localizar nenhum contribuinte com o identificador passado');
        }else{
            $sql .= " WHERE $campoWhere = '$identificador'";
        
            try {
                $this->getQueryBuilder()->getConection()->beginTransaction();
                $this->getQueryBuilder()->queryExec($sql, []);
                $this->getQueryBuilder()->getConection()->commit();
            }catch (Exception $e){
                $this->getQueryBuilder()->getConection()->rollback();
                throw $e;
            }
        }
    
        return TRUE;
    }
    
    public function getByDocumento($documento){
        $res = $this->getQueryBuilder()->queryExec("SELECT * FROM $this->table where documento='$documento'", []);
        return $res;
    }
    
    public function getId($documento){
        $res = $this->getQueryBuilder()->queryExec("SELECT * FROM $this->table where id=$documento", []);
        return $res;
    }
}
