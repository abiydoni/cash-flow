<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesForPerformance extends Migration
{
    public function up()
    {
        // Indexes for transactions table
        $this->db->query("ALTER TABLE transactions ADD INDEX idx_tx_user_date (user_id, transaction_date)");
        $this->db->query("ALTER TABLE transactions ADD INDEX idx_tx_type (type)");
        $this->db->query("ALTER TABLE transactions ADD INDEX idx_tx_deleted (deleted_at)");
        $this->db->query("ALTER TABLE transactions ADD INDEX idx_tx_category (category_id)");

        // Indexes for categories table
        $this->db->query("ALTER TABLE categories ADD INDEX idx_cat_active_type (is_active, type)");
        $this->db->query("ALTER TABLE categories ADD INDEX idx_cat_user (user_id)");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE transactions DROP INDEX idx_tx_user_date");
        $this->db->query("ALTER TABLE transactions DROP INDEX idx_tx_type");
        $this->db->query("ALTER TABLE transactions DROP INDEX idx_tx_deleted");
        $this->db->query("ALTER TABLE transactions DROP INDEX idx_tx_category");

        $this->db->query("ALTER TABLE categories DROP INDEX idx_cat_active_type");
        $this->db->query("ALTER TABLE categories DROP INDEX idx_cat_user");
    }
}
