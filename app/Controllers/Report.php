<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\DuesPaymentModel;
use App\Models\MemberModel;

class Report extends BaseController
{
    protected TransactionModel $transModel;
    protected DuesPaymentModel $paymentModel;
    protected MemberModel      $memberModel;

    public function __construct()
    {
        $this->transModel   = new TransactionModel();
        $this->paymentModel = new DuesPaymentModel();
        $this->memberModel  = new MemberModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $year   = $this->request->getGet('year') ?? date('Y');

        // General Stats
        $totalIncome = $this->getSum('income', $year, $userId);
        $totalExpense = $this->getSum('expense', $year, $userId);
        
        $duesCollected = $this->paymentModel
            ->selectSum('amount_paid')
            ->where('year', $year)
            ->whereIn('member_id', function($builder) use ($userId) {
                return $builder->select('id')->from('members')->where('user_id', $userId);
            })
            ->first();

        $activeMembers = $this->memberModel->where('user_id', $userId)->countAllResults();

        // Monthly Trends
        $trends = $this->getMonthlyTrends($year, $userId);

        $data = [
            'title'         => lang('App.reports'),
            'year'          => $year,
            'totalIncome'   => $totalIncome,
            'totalExpense'  => $totalExpense,
            'duesCollected' => $duesCollected['amount_paid'] ?? 0,
            'activeMembers' => $activeMembers,
            'trends'        => $trends
        ];

        return view('report/index', $data);
    }

    public function viewMonth($year, $month)
    {
        $userId = session()->get('user_id');
        
        // Get all transactions for this month
        $transactions = $this->transModel
            ->select('transactions.*, categories.name as category_name, categories.icon as category_icon, categories.color as category_color')
            ->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.user_id', $userId)
            ->where('YEAR(transaction_date)', $year)
            ->where('MONTH(transaction_date)', $month)
            ->orderBy('transaction_date', 'ASC')
            ->findAll();

        // Get dues summary for this month
        $dues = $this->paymentModel
            ->select('dues_payments.*, members.name as member_name, dues_types.name as dues_name')
            ->join('members', 'members.id = dues_payments.member_id')
            ->join('dues_types', 'dues_types.id = dues_payments.dues_type_id')
            ->where('dues_payments.year', $year)
            ->where('dues_payments.month', $month)
            ->whereIn('dues_payments.member_id', function($builder) use ($userId) {
                return $builder->select('id')->from('members')->where('user_id', $userId);
            })
            ->findAll();

        $monthName = [
            1=>lang('App.january'), 2=>lang('App.february'), 3=>lang('App.march'), 4=>lang('App.april'),
            5=>lang('App.may'), 6=>lang('App.june'), 7=>lang('App.july'), 8=>lang('App.august'),
            9=>lang('App.september'), 10=>lang('App.october'), 11=>lang('App.november'), 12=>lang('App.december')
        ][$month];

        $data = [
            'title'        => lang('App.reports') . ' ' . $monthName . ' ' . $year,
            'year'         => $year,
            'month'        => $month,
            'monthName'    => $monthName,
            'transactions' => $transactions,
            'dues'         => $dues
        ];

        return view('report/month', $data);
    }

    private function getSum($type, $year, $userId)
    {
        $res = $this->transModel
            ->selectSum('amount')
            ->where('type', $type)
            ->where('user_id', $userId)
            ->where("YEAR(transaction_date)", $year)
            ->first();
        return $res['amount'] ?? 0;
    }

    private function getMonthlyTrends($year, $userId)
    {
        $inc = $this->transModel
            ->select("MONTH(transaction_date) as month, SUM(amount) as total")
            ->where('type', 'income')
            ->where('user_id', $userId)
            ->where("YEAR(transaction_date)", $year)
            ->groupBy('month')
            ->findAll();

        $exp = $this->transModel
            ->select("MONTH(transaction_date) as month, SUM(amount) as total")
            ->where('type', 'expense')
            ->where('user_id', $userId)
            ->where("YEAR(transaction_date)", $year)
            ->groupBy('month')
            ->findAll();

        $data = [];
        for ($i=1; $i<=12; $i++) {
            $m = str_pad($i, 2, '0', STR_PAD_LEFT);
            $data[$i] = [
                'income'  => 0,
                'expense' => 0
            ];
            foreach($inc as $r) if((int)$r['month'] == $i) $data[$i]['income'] = (float)$r['total'];
            foreach($exp as $r) if((int)$r['month'] == $i) $data[$i]['expense'] = (float)$r['total'];
        }
        return $data;
    }
}
