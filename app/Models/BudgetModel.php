<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetModel extends Model
{
    protected $table         = 'budgets';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'user_id', 'category_id', 'name', 'amount',
        'period_type', 'start_date', 'end_date', 'is_active',
    ];

    protected $useTimestamps = true;

    public function getWithSpending(int $userId, string $month): array
    {
        $budgets = $this->where('user_id', $userId)
            ->where('is_active', 1)
            ->findAll();

        foreach ($budgets as &$budget) {
            $spent = db_connect()
                ->table('transactions')
                ->selectSum('amount', 'spent')
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->where("DATE_FORMAT(transaction_date, '%Y-%m')", $month)
                ->where('deleted_at', null);

            if ($budget['category_id']) {
                $spent->where('category_id', $budget['category_id']);
            }

            $row = $spent->get()->getRowArray();
            $budget['spent']      = $row['spent'] ?? 0;
            $budget['remaining']  = $budget['amount'] - $budget['spent'];
            $budget['percentage'] = $budget['amount'] > 0
                ? min(100, round(($budget['spent'] / $budget['amount']) * 100))
                : 0;
        }

        return $budgets;
    }
}
