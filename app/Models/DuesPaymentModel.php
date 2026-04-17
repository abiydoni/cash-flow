<?php

namespace App\Models;

use CodeIgniter\Model;

class DuesPaymentModel extends Model
{
    protected $table            = 'dues_payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'member_id', 'dues_type_id', 'transaction_id', 
        'month', 'year', 'amount_paid', 'payment_date'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPaymentGrid($memberId, $year)
    {
        return $this->select('dues_payments.*, dues_types.name as type_name')
                    ->join('dues_types', 'dues_types.id = dues_payments.dues_type_id')
                    ->where('member_id', $memberId)
                    ->where('year', $year)
                    ->findAll();
    }

    public function getPaymentsByMember($memberId)
    {
        return $this->select('dues_payments.*, dues_types.name as dues_name, transactions.description as journal_desc')
                    ->join('dues_types', 'dues_types.id = dues_payments.dues_type_id')
                    ->join('transactions', 'transactions.id = dues_payments.transaction_id', 'left')
                    ->where('member_id', $memberId)
                    ->orderBy('year', 'DESC')
                    ->orderBy('month', 'DESC')
                    ->findAll();
    }
}
