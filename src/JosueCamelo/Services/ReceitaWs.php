<?php

namespace JosueCamelo\Services;

use http\Exception;
class ReceitaWs
{
    public static function consultar($documento)
    {
        if(empty($documento)){
            throw new \Exception('Nenhuma Documento foi Informado para Pesquisa.');
        }
        
        $headers = array(
            'Content-Type: application/json',
            sprintf('Authorization: Bearer %s', '8154ea80438567b4b56877a297a7877baada70f54063d54c7780a68fa8caa733')
        );
        
        $curl = curl_init("https://www.receitaws.com.br/v1/cnpj/" . str_replace(['.', '-', '/'], '', $documento));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $resposta = curl_exec($curl);
        $result = json_decode($resposta);
        
        if($result->status == 'ERROR'){
            throw new \Exception($result->message);
        }
        
        return $result;
    }
}
