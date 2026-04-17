<?php

namespace App\Controllers;

use App\Models\BudgetModel;
use App\Models\CategoryModel;
use App\Models\DuesPaymentModel;
use App\Models\DuesTypeModel;
use App\Models\MemberModel;
use App\Models\TransactionModel;

class Settings extends BaseController
{
    public function index()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', lang('App.access_denied'));
        }

        $settingModel = new \App\Models\SettingModel();
        return view('settings/index', [
            'title' => lang('App.settings'),
            'settings' => $settingModel->getAllSettings()
        ]);
    }

    public function backup()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', lang('App.access_denied'));
        }

        $db = \Config\Database::connect();
        $dbname = $db->getDatabase();
        $tables = $db->listTables();
        
        $output = "-- CashFlow Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Structure
            $query = $db->query("SHOW CREATE TABLE `$table`")->getRowArray();
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            $output .= $query['Create Table'] . ";\n\n";

            // Data
            $rows = $db->table($table)->get()->getResultArray();
            foreach ($rows as $row) {
                $keys = array_keys($row);
                $vals = array_map(function($v) use ($db) {
                    if ($v === null) return 'NULL';
                    return $db->escape($v);
                }, array_values($row));
                
                $output .= "INSERT INTO `$table` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $vals) . ");\n";
            }
            $output .= "\n";
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $filename = 'backup_' . date('Ymd_His') . '.sql';
        
        return $this->response
            ->setHeader('Content-Type', 'application/sql')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($output);
    }

    public function updateNotifications()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', lang('App.access_denied'));
        }

        $settingModel = new \App\Models\SettingModel();
        
        $settingModel->setVal('wa_gateway_url', $this->request->getPost('wa_gateway_url') ?? '');
        $settingModel->setVal('wa_api_key', $this->request->getPost('wa_api_key') ?? '');
        $settingModel->setVal('notif_enabled', $this->request->getPost('notif_enabled') ? '1' : '0');
        $settingModel->setVal('app_name', $this->request->getPost('app_name') ?? 'CashFlow');

        return redirect()->to('/settings')->with('success', lang('App.settings_updated'));
    }

    public function resetData()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', lang('App.access_denied'));
        }

        $confirmation = $this->request->getPost('confirmation');
        if ($confirmation !== 'RESET') {
            return redirect()->back()->with('error', lang('App.reset_confirm_failed'));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Reset Transactions
        $db->table('transactions')->truncate();

        // 2. Reset Dues Payments
        $db->table('dues_payments')->truncate();

        // 3. Reset Budgets
        $db->table('budgets')->truncate();

        // 4. Reset Members
        // TRUNCATE is better but if there are foreign keys we might need DELETE
        try {
            $db->table('members')->truncate();
        } catch (\Exception $e) {
            $db->table('members')->where('id >', 0)->delete();
        }

        // 5. Reset Categories (Custom only)
        // Keep categories where user_id is NULL (System categories)
        $db->table('categories')->where('user_id IS NOT NULL', null, false)->delete();

        // 6. Reset Dues Types? 
        // Usually these are custom setup, so reset them too
        try {
            $db->table('dues_types')->truncate();
        } catch (\Exception $e) {
            $db->table('dues_types')->where('id >', 0)->delete();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', lang('App.reset_failed'));
        }

        return redirect()->to('/settings')->with('success', lang('App.reset_success'));
    }
}
