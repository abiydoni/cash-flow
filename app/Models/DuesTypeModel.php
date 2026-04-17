<?php

namespace App\Models;

use CodeIgniter\Model;

class DuesTypeModel extends Model
{
    protected $table            = 'dues_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'name', 'amount'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'name'   => 'required|min_length[3]|max_length[100]',
        'amount' => 'required|numeric|greater_than_equal_to[0]',
    ];
}
