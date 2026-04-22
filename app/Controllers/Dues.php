<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\DuesTypeModel;
use App\Models\DuesPaymentModel;
use App\Models\TransactionModel;
use App\Models\CategoryModel;

class Dues extends BaseController
{
    protected MemberModel      $memberModel;
    protected DuesTypeModel    $duesTypeModel;
    protected DuesPaymentModel $paymentModel;
    protected TransactionModel $transModel;
    protected CategoryModel    $catModel;

    public function __construct()
    {
        $this->memberModel   = new MemberModel();
        $this->duesTypeModel = new DuesTypeModel();
        $this->paymentModel  = new DuesPaymentModel();
        $this->transModel    = new TransactionModel();
        $this->catModel      = new CategoryModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $year   = $this->request->getGet('year') ?? date('Y');
        
        $duesTypes = $this->duesTypeModel->where('user_id', $userId)->findAll();
        
        $data = [
            'title'     => lang('App.dues_payment'),
            'duesTypes' => $duesTypes,
            'year'      => $year
        ];
        return view('dues/index', $data);
    }

    public function type($typeId)
    {
        $userId = session()->get('user_id');
        $type = $this->duesTypeModel->where('user_id', $userId)->find($typeId);
        if (!$type) {
            return redirect()->to('/dues')->with('error', lang('App.error_occurred'));
        }

        $year = $this->request->getGet('year') ?? date('Y');
        $members = $this->memberModel->where('user_id', $userId)->where('is_active', 1)->findAll();

        // Fetch all payments for this dues type in this year for all members of this user
        $memberIds = array_column($members, 'id');
        $paymentMatrix = [];
        if (!empty($memberIds)) {
            $allPayments = $this->paymentModel
                ->where('dues_type_id', $typeId)
                ->where('year', $year)
                ->whereIn('member_id', $memberIds)
                ->findAll();

            foreach ($allPayments as $p) {
                if (!isset($paymentMatrix[$p['member_id']])) {
                    $paymentMatrix[$p['member_id']] = [];
                }
                $paymentMatrix[$p['member_id']][$p['month']] = (float)$p['amount_paid'];
            }
        }

        $data = [
            'title'   => lang('App.dues_payment') . ': ' . $type['name'],
            'type'    => $type,
            'members' => $members,
            'year'    => $year,
            'paymentMatrix' => $paymentMatrix,
            'months' => [
                1 => lang('App.january'), 2 => lang('App.february'), 3 => lang('App.march'), 4 => lang('App.april'), 
                5 => lang('App.may'), 6 => lang('App.june'), 7 => lang('App.july'), 8 => lang('App.august'), 
                9 => lang('App.september'), 10 => lang('App.october'), 11 => lang('App.november'), 12 => lang('App.december')
            ]
        ];
        return view('dues/type', $data);
    }

    public function detail($memberId)
    {
        $userId = session()->get('user_id');
        $member = $this->memberModel->where('user_id', $userId)->find($memberId);
        if (!$member) {
            return redirect()->to('/dues')->with('error', lang('App.error_occurred'));
        }

        $year = $this->request->getGet('year') ?? date('Y');
        $selectedTypeId = $this->request->getGet('type_id');
        
        $payments = $this->paymentModel->getPaymentGrid($memberId, $year);
        
        $duesTypesQuery = $this->duesTypeModel->where('user_id', $userId);
        if ($selectedTypeId) {
            $duesTypesQuery->where('id', $selectedTypeId);
        }
        $duesTypes = $duesTypesQuery->findAll();

        // Organize payments by month and dues_type_id
        $paymentGrid = [];
        foreach ($payments as $p) {
            if (!isset($paymentGrid[$p['month']][$p['dues_type_id']])) {
                $paymentGrid[$p['month']][$p['dues_type_id']] = [
                    'type_name'  => $p['type_name'],
                    'total_paid' => 0,
                    'records'    => []
                ];
            }
            $paymentGrid[$p['month']][$p['dues_type_id']]['total_paid'] += $p['amount_paid'];
            $paymentGrid[$p['month']][$p['dues_type_id']]['records'][] = $p;
        }

        $data = [
            'title'       => lang('App.dues_detail') . ': ' . $member['name'],
            'member'      => $member,
            'year'         => $year,
            'selectedTypeId' => $selectedTypeId,
            'selectedType'   => $selectedTypeId ? ($this->duesTypeModel->find($selectedTypeId)) : null,
            'duesTypes'   => $duesTypes,
            'paymentGrid' => $paymentGrid,
            'months'      => [
                1 => lang('App.january'), 2 => lang('App.february'), 3 => lang('App.march'), 4 => lang('App.april'), 
                5 => lang('App.may'), 6 => lang('App.june'), 7 => lang('App.july'), 8 => lang('App.august'), 
                9 => lang('App.september'), 10 => lang('App.october'), 11 => lang('App.november'), 12 => lang('App.december')
            ]
        ];
        return view('dues/detail', $data);
    }

    public function pay()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'member_id'    => 'required|integer',
            'dues_type_id' => 'required|integer',
            'month'        => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[12]',
            'year'         => 'required|integer',
            'amount'       => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                $errors = array_values($this->validator->getErrors());
                return $this->response->setJSON(['status' => 'error', 'message' => $errors[0]]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $memberId   = $this->request->getPost('member_id');
        $duesTypeId = $this->request->getPost('dues_type_id');
        $month      = $this->request->getPost('month');
        $year       = $this->request->getPost('year');
        $amount     = $this->request->getPost('amount');
        $date       = $this->request->getPost('payment_date') ?? date('Y-m-d');

        $member   = $this->memberModel->find($memberId);
        $duesType = $this->duesTypeModel->find($duesTypeId);

        // 1. Find or create "Iuran" category
        $category = $this->catModel->where('name', 'Iuran')->where('type', 'income')->first();
        if (!$category) {
            $catId = $this->catModel->insert([
                'name'  => 'Iuran',
                'type'  => 'income',
                'icon'  => 'calendar-outline',
                'color' => '#10b981'
            ]);
        } else {
            $catId = $category['id'];
        }

        // 2. Create Transaction (Journal Kas)
        $monthName = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ][$month];
        
        $description = "Iuran {$duesType['name']} - {$member['name']} ({$monthName} {$year})";
        
        $transId = $this->transModel->insert([
            'user_id'          => $userId,
            'category_id'      => $catId,
            'type'             => 'income',
            'amount'           => $amount,
            'description'      => $description,
            'transaction_date' => $date,
            'payment_method'   => 'cash'
        ]);

        // 3. Create Dues Payment record
        $this->paymentModel->save([
            'member_id'      => $memberId,
            'dues_type_id'   => $duesTypeId,
            'transaction_id' => $transId,
            'month'          => $month,
            'year'           => $year,
            'amount_paid'    => $amount,
            'payment_date'   => $date
        ]);

        if ($this->request->isAJAX()) {
            $summary = $this->paymentModel->selectSum('amount_paid', 'total_paid')
                ->where('member_id', $memberId)
                ->where('dues_type_id', $duesTypeId)
                ->where('month', $month)
                ->where('year', $year)
                ->get()->getRowArray();

            return $this->response->setJSON([
                'status' => 'success', 
                'message' => lang('App.save_success'),
                'payment' => [
                    'total_paid' => $summary['total_paid'] ?? 0
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil disimpan');
    }

    public function delete($id)
    {
        $payment = $this->paymentModel->find($id);
        if (!$payment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        // Before delete, get info for dynamic update
        $month = $payment['month'];
        $memberId = $payment['member_id'];
        $duesTypeId = $payment['dues_type_id'];
        $year = $payment['year'];

        // Delete associated transaction if exists
        if ($payment['transaction_id']) {
            $this->transModel->delete($payment['transaction_id']);
        }

        // Delete payment record
        $this->paymentModel->delete($id);

        $summary = $this->paymentModel->selectSum('amount_paid', 'total_paid')
            ->where('member_id', $memberId)
            ->where('dues_type_id', $duesTypeId)
            ->where('month', $month)
            ->where('year', $year)
            ->get()->getRowArray();

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => lang('App.delete_success'),
            'month' => $month,
            'dues_type_id' => $duesTypeId,
            'summary' => [
                'total_paid' => $summary['total_paid'] ?? 0
            ]
        ]);
    }
}
