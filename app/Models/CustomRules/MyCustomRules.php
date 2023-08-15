<?php 
namespace App\Models\CustomRules;
use App\Models\ClienteModel;

class MyCustomRules{
    public function is_valid_cliente(string $email) : bool{
        $model = new ClienteModel();
        $cliente = $model->where('email', $email)->find();
        return $cliente == null ? true : false;
    }
}