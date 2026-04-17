<?php

namespace App\Controllers;

use App\Models\BudgetModel;
use App\Models\CategoryModel;
use App\Models\DuesPaymentModel;
use App\Models\DuesTypeModel;
use App\Models\MemberModel;
use App\Models\TransactionModel;
use App\Models\SettingModel;

class Settings extends BaseController
{
    public function index()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', lang('App.access_denied'));
        }

        $settingModel = new SettingModel();
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
        $tables = $db->listTables();
        
        $output = "-- CashFlow Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $query = $db->query("SHOW CREATE TABLE `$table`")->getRowArray();
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            $output .= $query['Create Table'] . ";\n\n";

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

        $settingModel = new SettingModel();
        
        $settingModel->setVal('wa_gateway_url', $this->request->getPost('wa_gateway_url') ?? '');
        $settingModel->setVal('wa_api_key', $this->request->getPost('wa_api_key') ?? '');
        $settingModel->setVal('notif_enabled', $this->request->getPost('notif_enabled') ? '1' : '0');
        $settingModel->setVal('app_name', $this->request->getPost('app_name') ?? 'CashFlow');

        return redirect()->to('/admin/settings')->with('success', lang('App.settings_updated'));
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
        
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        $db->transStart();
        $db->table('transactions')->where('id >', 0)->delete();
        $db->table('dues_payments')->where('id >', 0)->delete();
        $db->table('budgets')->where('id >', 0)->delete();
        $db->table('dues_types')->where('id >', 0)->delete();
        $db->table('members')->where('id >', 0)->delete();
        $db->table('categories')->where('user_id IS NOT NULL', null, false)->delete();
        $db->transComplete();
        
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        if ($db->transStatus() === false) {
            return redirect()->to('/admin/settings')->with('error', lang('App.reset_failed'));
        }

        return redirect()->to('/admin/settings')->with('success', lang('App.reset_success'));
    }
}
