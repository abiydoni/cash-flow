<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\CategoryModel;

class Transaction extends BaseController
{
    protected TransactionModel $model;
    protected CategoryModel    $catModel;
    protected \App\Models\DuesPaymentModel $paymentModel;

    public function __construct()
    {
        $this->model        = new TransactionModel();
        $this->catModel     = new CategoryModel();
        $this->paymentModel = new \App\Models\DuesPaymentModel();
    }

    public function index()
    {
        $userId  = session()->get('user_id');
        $filters = [
            'type'        => $this->request->getGet('type'),
            'month'       => $this->request->getGet('month') ?? date('Y-m'),
            'category_id' => $this->request->getGet('category_id'),
            'search'      => $this->request->getGet('search'),
        ];

        $perPage      = 20;
        $transactions = $this->model->getWithCategory($userId, $filters, $perPage);
        $pager        = $this->model->pager;

        // Get total summary for filtered transactions (not just current page)
        $summary = $this->model->select("
                COALESCE(SUM(CASE WHEN transactions.type='income' THEN transactions.amount ELSE 0 END), 0) AS total_income,
                COALESCE(SUM(CASE WHEN transactions.type='expense' THEN transactions.amount ELSE 0 END), 0) AS total_expense,
                COUNT(*) AS total_count"
            )->join('categories', 'categories.id = transactions.category_id', 'left')
            ->where('transactions.user_id', $userId);

        if (!empty($filters['type'])) $summary->where('transactions.type', $filters['type']);
        if (!empty($filters['month'])) $summary->like('transactions.transaction_date', $filters['month'], 'after');
        if (!empty($filters['category_id'])) $summary->where('transactions.category_id', $filters['category_id']);
        if (!empty($filters['search'])) {
            $summary->groupStart()
                ->like('transactions.description', $filters['search'])
                ->orLike('categories.name', $filters['search'])
            ->groupEnd();
        }
        $totals = $summary->get()->getRowArray() ?? [
            'total_income' => 0,
            'total_expense' => 0,
            'total_count' => 0
        ];
        
        // Calculate Opening Balance if month filter is set
        $openingBalance = null;
        if (!empty($filters['month'])) {
            $openingBalance = $this->model->getOpeningBalance($userId, $filters['month'], $filters);
        }

        $incomeCategories  = $this->catModel->getForUser($userId, 'income');
        $expenseCategories = $this->catModel->getForUser($userId, 'expense');

        $grandTotalBalance = $this->model->getGrandTotalBalance($userId);
        
        return view('transaction/index', [
            'transactions'      => $transactions,
            'pager'             => $pager,
            'filters'           => $filters,
            'incomeCategories'  => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'openingBalance'    => $openingBalance,
            'totals'            => $totals,
            'grandTotalBalance' => $grandTotalBalance,
        ]);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $type   = $this->request->getGet('type') ?? 'expense';

        $incomeCategories  = $this->catModel->getForUser($userId, 'income');
        $expenseCategories = $this->catModel->getForUser($userId, 'expense');

        return view('transaction/form', [
            'mode'              => 'create',
            'type'              => $type,
            'transaction'       => null,
            'incomeCategories'  => $incomeCategories,
            'expenseCategories' => $expenseCategories,
        ]);
    }

    public function store()
    {
        $userId = session()->get('user_id');

        $rules = [
            'type'             => 'required|in_list[income,expense]',
            'amount'           => 'required|numeric|greater_than[0]',
            'transaction_date' => 'required|valid_date[Y-m-d]',
            'category_id'      => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id'          => $userId,
            'category_id'      => $this->request->getPost('category_id'),
            'type'             => $this->request->getPost('type'),
            'amount'           => $this->request->getPost('amount'),
            'description'      => $this->request->getPost('description'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'payment_method'   => $this->request->getPost('payment_method') ?? 'cash',
            'note'             => $this->request->getPost('note'),
            'reference_no'     => $this->request->getPost('reference_no'),
            'is_recurring'     => $this->request->getPost('is_recurring') ? 1 : 0,
            'recurring_type'   => $this->request->getPost('recurring_type'),
        ];

        if ($this->model->insert($data)) {
            if ($this->request->isAJAX()) {
                $savedTx = $this->model->getWithCategory($userId, ['id' => $this->model->getInsertID()]);
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => lang('App.save_success'),
                    'transaction' => $savedTx[0] ?? null
                ]);
            }
            return redirect()->to('/transaction')
                ->with('success', lang('App.save_success'));
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.error')]);
        }

        return redirect()->back()->withInput()->with('error', lang('App.error'));
    }

    public function edit(int $id)
    {
        $userId      = session()->get('user_id');
        $role        = session()->get('role');
        $transaction = $this->model->find($id);

        if (! $transaction || ($transaction['user_id'] != $userId && $role !== 'admin')) {
            return redirect()->to('/transaction')->with('error', lang('App.not_found'));
        }

        $incomeCategories  = $this->catModel->getForUser($userId, 'income');
        $expenseCategories = $this->catModel->getForUser($userId, 'expense');

        return view('transaction/form', [
            'mode'              => 'edit',
            'type'              => $transaction['type'],
            'transaction'       => $transaction,
            'incomeCategories'  => $incomeCategories,
            'expenseCategories' => $expenseCategories,
        ]);
    }

    public function update(int $id)
    {
        $userId      = session()->get('user_id');
        $role        = session()->get('role');
        $transaction = $this->model->find($id);

        if (! $transaction || ($transaction['user_id'] != $userId && $role !== 'admin')) {
            return redirect()->to('/transaction')->with('error', lang('App.not_found'));
        }

        $rules = [
            'type'             => 'required|in_list[income,expense]',
            'amount'           => 'required|numeric|greater_than[0]',
            'transaction_date' => 'required|valid_date[Y-m-d]',
            'category_id'      => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => implode('<br>', $this->validator->getErrors())]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'category_id'      => $this->request->getPost('category_id'),
            'type'             => $this->request->getPost('type'),
            'amount'           => $this->request->getPost('amount'),
            'description'      => $this->request->getPost('description'),
            'transaction_date' => $this->request->getPost('transaction_date'),
            'payment_method'   => $this->request->getPost('payment_method') ?? 'cash',
            'note'             => $this->request->getPost('note'),
            'reference_no'     => $this->request->getPost('reference_no'),
        ];

        $this->model->update($id, $data);

        // Sync with Dues Payment if linked
        $this->paymentModel->where('transaction_id', $id)->set([
            'amount_paid' => $data['amount'],
            'payment_date' => $data['transaction_date']
        ])->update();

        if ($this->request->isAJAX()) {
            $updatedTx = $this->model->getWithCategory($userId, ['id' => $id]);
            return $this->response->setJSON([
                'status' => 'success', 
                'message' => lang('App.update_success'),
                'transaction' => $updatedTx[0] ?? null
            ]);
        }
        return redirect()->to('/transaction')->with('success', lang('App.update_success'));
    }

    public function delete(int $id)
    {
        $userId      = session()->get('user_id');
        $role        = session()->get('role');
        $transaction = $this->model->find($id);

        if (! $transaction || ($transaction['user_id'] != $userId && $role !== 'admin')) {
            return $this->response->setJSON(['status' => 'error', 'message' => lang('App.not_found')]);
        }

        $this->model->delete($id);
        
        // Also delete linked Dues Payment
        $this->paymentModel->where('transaction_id', $id)->delete();

        return $this->response->setJSON(['status' => 'success', 'message' => lang('App.delete_success')]);
    }

    // ─── AJAX: Summary untuk bulan tertentu ───────────────────────────────────
    public function summary()
    {
        $userId = session()->get('user_id');
        $month  = $this->request->getGet('month') ?? date('Y-m');
        $data   = $this->model->getMonthlySummary($userId, $month);
        $data['balance'] = $data['total_income'] - $data['total_expense'];
        return $this->response->setJSON($data);
    }
}
