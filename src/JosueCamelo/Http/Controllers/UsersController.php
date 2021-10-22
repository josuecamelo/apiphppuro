<?php
	namespace JosueCamelo\Http\Controllers;

	class UsersController {
		public function index()
		{
			if (AuthController::checkAuth()) {
			
			}
			
			throw new \Exception('Não autenticado');
		}
	}
