<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\CategoryModel;
use App\Models\BudgetModel;
use App\Models\UserModel;
use App\Models\ProfileModel;

class Dashboard extends BaseController
{
    protected TransactionModel $transModel;
    protected CategoryModel    $catModel;
    protected BudgetModel      $budgetModel;

    public function __construct()
    {
        $this->transModel  = new TransactionModel();
        $this->catModel    = new CategoryModel();
        $this->budgetModel = new BudgetModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $role   = session()->get('role');
        $month  = $this->request->getGet('month') ?? date('Y-m');

        $summary   = $this->transModel->getMonthlySummary($userId, $month);
        $balance   = $summary['total_income'] - $summary['total_expense'];
        $openingBalance = $this->transModel->getOpeningBalance($userId, $month);
        
        $recentTx  = $this->transModel->getWithCategory($userId, ['month' => $month]);
        $recentTx  = array_slice($recentTx, 0, 10);
        $chartData = $this->transModel->getLast7DaysChart($userId);
        $byCategory = $this->transModel->getExpenseByCategory($userId, $month);
        $budgets   = $this->budgetModel->getWithSpending($userId, $month);

        // Admin extras
        $adminStats = null;
        if ($role === 'admin') {
            $db = db_connect();
            $adminStats = [
                'total_users'        => $db->table('users')->where('deleted_at', null)->countAllResults(),
                'total_transactions' => $db->table('transactions')->where('deleted_at', null)->countAllResults(),
                'total_income'       => $db->table('transactions')->where('type', 'income')->where('deleted_at', null)->selectSum('amount')->get()->getRowArray()['amount'] ?? 0,
                'total_expense'      => $db->table('transactions')->where('type', 'expense')->where('deleted_at', null)->selectSum('amount')->get()->getRowArray()['amount'] ?? 0,
            ];
        }

        return view('dashboard/index', [
            'month'          => $month,
            'summary'        => $summary,
            'balance'        => $balance,
            'openingBalance' => $openingBalance,
            'recentTx'       => $recentTx,
            'chartData'      => $chartData,
            'byCategory'     => $byCategory,
            'budgets'        => $budgets,
            'adminStats'     => $adminStats,
        ]);
    }
}
