<?php

namespace App\Controllers\API;

use App\Models\ClienteModel;
use CodeIgniter\RESTful\ResourceController;

class Clientes extends ResourceController
{

    public function __construct()
    {
        $this->model = $this->setModel(new ClienteModel());
    }

    public function index()
    {
        $clientes = $this->model->findAll();
        return $this->respond($clientes);
    }

    public function create(){
        try {
            $cliente = $this->request->getPost();
            if($this->model->insert($cliente)){
                //return $this->respondCreated($cliente);
                $cliente = $this->model->insertID();
                return $this->respondCreated($cliente);
            } else {
                return $this->failValidationErrors($this->model->validation->listErrors());
            }
        } catch (\Throwable $th) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function update($id = null){
        try {
            if($id == null) 
                return $this->failServerError('No se ha pasado un ID valido');

            $cliente_verificado = $this->model->find($id);
            if($cliente_verificado == null)
                return $this->failNotFound('No se ha encontrado un cliente con el ID : ' . $id);

            $cliente = $this->request->getRawInput();
            if(!empty($cliente)){
                $cliente = array(
                    'name' => $this->request->getVar('name'),
                    'email' => $this->request->getVar('email'),
                );
                if($this->model->update($id, $cliente)){
                    $cliente['id'] = $id;
                    return $this->respond($cliente, 202);
                    //return $this->model->respondUpdated($cliente);
                } else {
                    return $this->failValidationErrors($this->model->validation->listErrors());
                }
            } else {
                return $this->failNotFound('Ha ocurrido un error, intente nuevamente : ' . $id);
            }
            //return $this->respond($cliente_verificado);*/
        } catch (\Throwable $th) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    public function delete($id = null){
        try {
            if($id == null) 
                return $this->failServerError('No se ha pasado un ID valido');
            
            $cliente_verificado = $this->model->find($id);
            if($cliente_verificado == null)
                return $this->failNotFound('No se ha encontrado un cliente con el ID : ' . $id);

            if($this->model->delete($id)){
                return $this->respondDeleted($cliente_verificado);
            } else {
                return $this->failServerError('No se ha podido eliminar el registro');
            }

        } catch (\Throwable $th) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }


}
