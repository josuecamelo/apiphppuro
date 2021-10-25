<?php

namespace JosueCamelo\Http\Controllers;

use JosueCamelo\Models\Contribuinte;
use JosueCamelo\Models\Usuario;
use JosueCamelo\Services\ReceitaWs;
class ContribuinteController extends BaseController
{
    private $contribuinteModel;
    
    public function __construct(){
        $this->contribuinteModel = new Contribuinte();
    }
    
    public function index(){
        if (AuthController::checkAuth()) {
            $filtro = $this->montarFiltros($_GET);
            return $this->contribuinteModel->find($filtro);
        }
    }
    
    protected function montarFiltros($data){
        $filtros = "";
        if(isset($data['campos'])){
            $campos = $data['campos'];
            $filtros = " WHERE ";
            
            $chaves = array_keys($campos);
            
            foreach($chaves as $chave){
                $filtros.= "(";
                foreach($campos[$chave] as $key => $campo){
                    if(end($campos[$chave]) == $campos[$chave][count($campos[$chave]) - 1]){
                        $filtros.= " $chave = '$campo' OR ";
                    }
                }
                $filtros = substr($filtros, 0, -3);
                $filtros.= ") AND ";
            }
            $filtros = substr($filtros, 0, -4);
        }
      
        return $filtros;
    }
    
    public function search($documento){
        try {
            $empresa = ReceitaWs::consultar($documento);
            return $empresa;
        }catch (\Exception $e){
            throw $e;
        }
    }
    
    public function store()
    {
        if (AuthController::checkAuth()) {
            $errors = $this->validateData($_POST);
            
            if(!empty($errors)){
                return ['validacao' => $errors];
            }
            
            $insertOk = $this->contribuinteModel->inserir($_POST);
            $id = $this->contribuinteModel->getByDocumento($_POST['documento']);
            
            if($insertOk){
                $this->validateEstado($_POST);
                
                return [
                    'mensagem' => $this->msg,
                    'ultimo_id_inserido' => $id[0]['id']
                ];
            }else{
                return [
                    'mensagem' => 'Erro ao Inserir Contribuinte'
                ];
            }
        }
        
        throw new \Exception('Não autenticado');
    }
    
    public function update($identificador){
        if (AuthController::checkAuth()) {
            $errors = $this->validateData($_POST);
        
            if(!empty($errors)){
                return ['validacao' => $errors];
            }
        
            $updateOk = $this->contribuinteModel->update($identificador, $_POST);
            
            if($updateOk){
                $this->validateEstado($_POST);
            
                return [
                    'mensagem' => $this->msg,
                ];
            }else{
                return [
                    'mensagem' => 'Erro ao Inserir Contribuinte'
                ];
            }
        }
    
        throw new \Exception('Não autenticado');
    }
    
    public function validateData($data)
    {
        $camposVazio = [];
        
        $opcionais = [
            'im',
            'email',
            'end_complemento'
        ];
        
        if (!empty($data['telefone_principal']) || !empty($data['telefone_secundario'])) {
            $opcionais[] = 'telefone_principal';
            $opcionais[] = 'telefone_secundario';
        }
        
        if ($data['tipo_contribuinte'] != 'Contribuinte') {
            $opcionais[] = 'ie';
        }
        
        if ($data['tipo_pessoa'] == 'Física') {
            $opcionais[] = 'nome_fantasia';
        }
        
        foreach ($data as $key => $campos) {
            if (!in_array($key, $opcionais)) {
                if (empty($data[$key])) {
                    $camposVazio[] = [
                        'nome_campo' => $key,
                        'campo' => $this->slugToCamelCase($key, true),
                        'validacao' => 'É Requerido',
                    ];
                }
            }
        }
        
        /*if($this->contribuinteModel->getByDocumento($data['documento'])){
            $camposVazio[] = [
                'CNPJ/CPF já está em uso por outro Contribuinte.',
            ];
        }*/
        
        return $camposVazio;
    }
    
    public function delete($identificador){
        $this->contribuinteModel->remover($identificador);
    }
    
    public function slugToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('', '', ucwords(str_replace('_', ' ', $string)));
        
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }
    
    public function validateEstado($data)
    {
        switch ($data['estado']) {
            case 'Goiás':
                if ($data['tipo_cadastro'] == 'Variante') {
                    $this->msg = 'Mandar equipe de campo';
                }
                break;
            case 'São Paulo':
                if (($data['tipo_cadastro'] == 'Variante') && $data['tipo_pessoa'] == 'Física') {
                    $this->msg = 'Reavaliar em 2 meses';
                }
                break;
            case 'Ceará':
            case 'Tocantins':
                if (($data['tipo_cadastro'] == 'Variante') && $data['tipo_pessoa'] == 'Física' && !empty($data['observacao'])) {
                    $this->msg = 'Possível violação do tratado Celta';
                }
                break;
            case 'Amazonas':
                if (($data['tipo_cadastro'] == 'Variante') && $data['tipo_pessoa'] == 'Física' && !empty($data['observacao'])) {
                    $this->msg = 'Possível violação do tratado Alpha';
                }
                break;
            default:
                break;
        }
    }
}
