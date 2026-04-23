<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'user_id', 'category_id', 'type', 'amount', 'description',
        'transaction_date', 'reference_no', 'payment_method',
        'attachment', 'note', 'is_recurring', 'recurring_type',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'type'             => 'required|in_list[income,expense,transfer]',
        'amount'           => 'required|numeric|greater_than[0]',
        'transaction_date' => 'required|valid_date',
    ];

    public function getWithCategory(int $userId, array $filters = [], int $perPage = null)
    {
        $this->select('transactions.*, 
                categories.name AS category_name, categories.icon AS category_icon, categories.color AS category_color,
                dues_payments.id AS dues_payment_id')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->join('dues_payments', 'dues_payments.transaction_id = transactions.id', 'left')
            ->where('transactions.user_id', $userId);

        if (!empty($filters['type'])) {
            $this->where('transactions.type', $filters['type']);
        }
        if (!empty($filters['month'])) {
            $this->where("DATE_FORMAT(transactions.transaction_date, '%Y-%m')", $filters['month']);
        }
        if (!empty($filters['category_id'])) {
            $this->where('transactions.category_id', $filters['category_id']);
        }
        if (!empty($filters['search'])) {
            $this->groupStart()
                ->like('transactions.description', $filters['search'])
                ->orLike('categories.name', $filters['search'])
            ->groupEnd();
        }

        $this->orderBy('transactions.transaction_date', 'DESC')
            ->orderBy('transactions.created_at', 'DESC');

        if ($perPage) {
            return $this->paginate($perPage);
        }

        return $this->findAll();
    }

    public function getMonthlySummary(int $userId, string $month): array
    {
        $result = $this->select("
            COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END), 0) AS total_income,
            COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END), 0) AS total_expense,
            COUNT(*) AS total_transactions"
        )
        ->where('user_id', $userId)
        ->where("DATE_FORMAT(transaction_date, '%Y-%m')", $month)
        ->get()->getRowArray();

        return $result ?? ['total_income' => 0, 'total_expense' => 0, 'total_transactions' => 0];
    }

    public function getExpenseByCategory(int $userId, string $month): array
    {
        return $this->select('categories.name, categories.icon, categories.color,
                SUM(transactions.amount) AS total')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense')
            ->where("DATE_FORMAT(transactions.transaction_date, '%Y-%m')", $month)
            ->groupBy('transactions.category_id')
            ->orderBy('total', 'DESC')
            ->findAll();
    }

    public function getLast7DaysChart(int $userId): array
    {
        $rows = $this->select("
            DATE(transaction_date) as day,
            SUM(CASE WHEN type='income' THEN amount ELSE 0 END) AS income,
            SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) AS expense"
        )
        ->where('user_id', $userId)
        ->where('transaction_date >=', date('Y-m-d', strtotime('-6 days')))
        ->groupBy('day')
        ->orderBy('day', 'ASC')
        ->findAll();

        // Fill missing days
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));
            $chart[$day] = ['income' => 0, 'expense' => 0];
        }
        foreach ($rows as $r) {
            $chart[$r['day']] = ['income' => $r['income'], 'expense' => $r['expense']];
        }
        return $chart;
    }

    public function getAllForAdmin(array $filters = []): array
    {
        $builder = $this->select('transactions.*, users.username,
                profiles.full_name,
                categories.name AS category_name, categories.icon AS category_icon, categories.color AS category_color')
            ->join('users', 'users.id = transactions.user_id', 'left')
            ->join('profiles', 'profiles.user_id = transactions.user_id', 'left')
            ->join('categories', 'categories.id = transactions.category_id', 'left');

        if (!empty($filters['user_id'])) {
            $builder->where('transactions.user_id', $filters['user_id']);
        }
        if (!empty($filters['month'])) {
            $builder->where("DATE_FORMAT(transactions.transaction_date, '%Y-%m')", $filters['month']);
        }

        return $builder->orderBy('transactions.transaction_date', 'DESC')->findAll();
    }

    /**
     * Get Opening Balance (Saldo Awal) before a specific month.
     */
    public function getOpeningBalance(?int $userId, string $month, array $filters = []): float
    {
        $builder = $this->select("
                COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END), 0) AS opening_balance"
            )
            ->where("DATE_FORMAT(transaction_date, '%Y-%m') <", $month);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        if (!empty($filters['category_id'])) {
            $builder->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['type'])) {
            $builder->where('type', $filters['type']);
        }
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('description', $filters['search'])
            ->groupEnd();
        }

        $result = $builder->get()->getRowArray();
        return (float) ($result['opening_balance'] ?? 0);
    }

    /**
     * Get Grand Total Balance (Actual Current Balance).
     */
    public function getGrandTotalBalance(int $userId): float
    {
        $result = $this->select("
                COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END), 0) AS total_balance"
            )
            ->where('user_id', $userId)
            ->get()->getRowArray();
            
        return (float) ($result['total_balance'] ?? 0);
    }
}
