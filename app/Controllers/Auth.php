<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

class Auth extends BaseController
{
    public function __construct()
    {
        helper('secure_password');
    }

    use ResponseTrait;
    public function login()
    {
        try {
            $username = $this->request->getPost('username');
            $passw = (string) $this->request->getPost('password');

            $clienteModel = new ClienteModel();
            //$where = array('email' => $username, 'password' => $password);
            $where = array('email' => $username);
            $validate_usuario = $clienteModel->where($where)->find();

            if($validate_usuario == null)
                return $this->failNotFound('Usuario o contraseña invalido');

            if(password_verify($passw, $validate_usuario[0]['password'])){
                $jwt = $this->generateJWT($validate_usuario);
                return $this->respondCreated([
                    'status' => 200,
                    'jwt' => $jwt,
                    'message' => 'User Login Success'
                ]);
            } else {
                return $this->failValidationError('Contraseña invalida');
            }


            
            /*
            return $this->respond('usuario encontrado');*/

            //echo password_verify('123456', '$2y$10$YLOT0QkW5mKgQ30FxdOR/.F8Gp1t1ciRa8Ogypk4vxCINYLzspMkC');


        } catch (\Throwable $th) {
            echo json_encode($th);
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    protected function generateJWT($usuario) {
        $key = 'parangacutirimicuaro';
        $payload = [
            'iss' => base_url(),
            'aud' => base_url(),
            "data" => [
                'user_id' => $usuario[0]['id'],
                'name' => $usuario[0]['name']
            ],
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public function readuser() {
        $request = Service('request');
        $key = 'parangacutirimicuaro';
        $headers = $request->getHeaders('Authorization');
        var_dump($headers['Autorization']);
        /*$jwt = $headers->getvalue();
        $userData = JWT::decode($jwt, new key($key, 'HS256'));
        return $this->respond([
            'status' => 1,
            'users' => $userData->data
        ]);*/
    }
}
