<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // ─── USERS ───────────────────────────────────────────────────────────────
        $users = [
            [
                'id'         => 1,
                'username'   => 'admin',
                'email'      => 'admin@cashflow.com',
                'password'   => password_hash('admin123', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 2,
                'username'   => 'john_doe',
                'email'      => 'john@cashflow.com',
                'password'   => password_hash('user123', PASSWORD_BCRYPT),
                'role'       => 'user',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 3,
                'username'   => 'jane_doe',
                'email'      => 'jane@cashflow.com',
                'password'   => password_hash('user123', PASSWORD_BCRYPT),
                'role'       => 'user',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('users')->insertBatch($users);

        // ─── PROFILES ─────────────────────────────────────────────────────────────
        $profiles = [
            [
                'user_id'               => 1,
                'full_name'             => 'Administrator',
                'phone_number'          => '08100000000',
                'address'               => 'Jl. Sudirman No. 1',
                'city'                  => 'Jakarta',
                'province'              => 'DKI Jakarta',
                'postal_code'           => '10220',
                'gender'                => 'male',
                'date_of_birth'         => '1990-01-01',
                'currency'              => 'IDR',
                'monthly_income_target' => 20000000,
                'monthly_expense_limit' => 15000000,
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
            [
                'user_id'               => 2,
                'full_name'             => 'John Doe',
                'phone_number'          => '08111111111',
                'address'               => 'Jl. Thamrin No. 10',
                'city'                  => 'Bandung',
                'province'              => 'Jawa Barat',
                'postal_code'           => '40111',
                'gender'                => 'male',
                'date_of_birth'         => '1995-06-15',
                'currency'              => 'IDR',
                'monthly_income_target' => 10000000,
                'monthly_expense_limit' => 7000000,
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
            [
                'user_id'               => 3,
                'full_name'             => 'Jane Doe',
                'phone_number'          => '08222222222',
                'address'               => 'Jl. Gatot Subroto No. 5',
                'city'                  => 'Surabaya',
                'province'              => 'Jawa Timur',
                'postal_code'           => '60100',
                'gender'                => 'female',
                'date_of_birth'         => '1998-03-22',
                'currency'              => 'IDR',
                'monthly_income_target' => 8000000,
                'monthly_expense_limit' => 5000000,
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
        ];
        $this->db->table('profiles')->insertBatch($profiles);

        // ─── CATEGORIES ───────────────────────────────────────────────────────────
        $categories = [
            // Income Global
            ['user_id' => null, 'name' => 'Gaji',            'type' => 'income',  'icon' => 'cash-outline',          'color' => '#10b981'],
            ['user_id' => null, 'name' => 'Bonus',           'type' => 'income',  'icon' => 'gift-outline',           'color' => '#34d399'],
            ['user_id' => null, 'name' => 'Investasi',       'type' => 'income',  'icon' => 'trending-up-outline',    'color' => '#6ee7b7'],
            ['user_id' => null, 'name' => 'Freelance',       'type' => 'income',  'icon' => 'laptop-outline',         'color' => '#059669'],
            ['user_id' => null, 'name' => 'Bisnis',          'type' => 'income',  'icon' => 'business-outline',       'color' => '#047857'],
            ['user_id' => null, 'name' => 'Penjualan',       'type' => 'income',  'icon' => 'cart-outline',           'color' => '#065f46'],
            ['user_id' => null, 'name' => 'Lain-lain',       'type' => 'income',  'icon' => 'add-circle-outline',     'color' => '#6b7280'],
            // Expense Global
            ['user_id' => null, 'name' => 'Makan & Minum',  'type' => 'expense', 'icon' => 'restaurant-outline',     'color' => '#ef4444'],
            ['user_id' => null, 'name' => 'Transportasi',   'type' => 'expense', 'icon' => 'car-outline',            'color' => '#f97316'],
            ['user_id' => null, 'name' => 'Belanja',        'type' => 'expense', 'icon' => 'bag-outline',            'color' => '#eab308'],
            ['user_id' => null, 'name' => 'Kesehatan',      'type' => 'expense', 'icon' => 'medkit-outline',         'color' => '#ec4899'],
            ['user_id' => null, 'name' => 'Pendidikan',     'type' => 'expense', 'icon' => 'school-outline',         'color' => '#8b5cf6'],
            ['user_id' => null, 'name' => 'Hiburan',        'type' => 'expense', 'icon' => 'game-controller-outline','color' => '#3b82f6'],
            ['user_id' => null, 'name' => 'Tagihan',        'type' => 'expense', 'icon' => 'receipt-outline',        'color' => '#6366f1'],
            ['user_id' => null, 'name' => 'Rumah',          'type' => 'expense', 'icon' => 'home-outline',           'color' => '#14b8a6'],
            ['user_id' => null, 'name' => 'Asuransi',       'type' => 'expense', 'icon' => 'shield-outline',         'color' => '#0891b2'],
            ['user_id' => null, 'name' => 'Tabungan',       'type' => 'expense', 'icon' => 'save-outline',           'color' => '#7c3aed'],
            ['user_id' => null, 'name' => 'Lain-lain',      'type' => 'expense', 'icon' => 'ellipsis-horizontal-outline', 'color' => '#9ca3af'],
        ];
        foreach ($categories as &$cat) {
            $cat['is_active']  = 1;
            $cat['created_at'] = $now;
            $cat['updated_at'] = $now;
        }
        $this->db->table('categories')->insertBatch($categories);

        // ─── SAMPLE TRANSACTIONS (for user 2 = john_doe) ─────────────────────────
        $transactions = [];
        $catIncome  = [1, 2, 3, 4]; // gaji, bonus, investasi, freelance
        $catExpense = [8, 9, 10, 11, 13, 14]; // makan, transport, belanja, kesehatan, hiburan, tagihan
        $months = ['2026-02', '2026-03', '2026-04'];
        foreach ($months as $m) {
            $transactions[] = ['user_id'=>2,'category_id'=>1,'type'=>'income','amount'=>8500000,'description'=>'Gaji Bulan '.$m,'transaction_date'=>$m.'-01','payment_method'=>'bank_transfer','created_at'=>$now,'updated_at'=>$now];
            $transactions[] = ['user_id'=>2,'category_id'=>8,'type'=>'expense','amount'=>1500000,'description'=>'Makan bulan '.$m,'transaction_date'=>$m.'-05','payment_method'=>'cash','created_at'=>$now,'updated_at'=>$now];
            $transactions[] = ['user_id'=>2,'category_id'=>9,'type'=>'expense','amount'=>500000,'description'=>'Bensin & Grabcar','transaction_date'=>$m.'-10','payment_method'=>'e_wallet','created_at'=>$now,'updated_at'=>$now];
            $transactions[] = ['user_id'=>2,'category_id'=>14,'type'=>'expense','amount'=>700000,'description'=>'Listrik & Air','transaction_date'=>$m.'-15','payment_method'=>'bank_transfer','created_at'=>$now,'updated_at'=>$now];
            $transactions[] = ['user_id'=>2,'category_id'=>4,'type'=>'income','amount'=>2000000,'description'=>'Proyek Freelance','transaction_date'=>$m.'-20','payment_method'=>'bank_transfer','created_at'=>$now,'updated_at'=>$now];
        }
        $this->db->table('transactions')->insertBatch($transactions);

        echo "✅ Seeder selesai! Akun tersedia:\n";
        echo "   Admin  : admin / admin123\n";
        echo "   User 1 : john_doe / user123\n";
        echo "   User 2 : jane_doe / user123\n";
    }
}
