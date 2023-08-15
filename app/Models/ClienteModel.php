<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    =  ['name', 'email'];
    protected $useTimestamps    = false;

    protected $validationRules  = [
        'name' => 'required|alpha_space|min_length[3]|max_length[100]',
        'email' => 'required|max_length[255]|is_valid_cliente'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'El nombre es requerido',
        ],
        'email' => [
            'required' => 'El email es requerido',
            'is_valid_cliente' => 'Ya existe un cliente registrado con ese email',
        ]
    ];

    protected $skipValidation = false;

}
