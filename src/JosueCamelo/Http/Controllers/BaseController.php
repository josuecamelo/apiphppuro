<?php

namespace JosueCamelo\Http\Controllers;

class BaseController
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        
        return $response;
    }
    
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        
        return json_encode($response, $code);
    }
}
