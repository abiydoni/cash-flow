<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
function rupiah($n) { return 'Rp ' . number_format($n ?? 0, 0, ',', '.'); }
$types = ['income' => ['label'=>lang('App.income'),'color'=>'text-emerald-400','bg'=>'bg-emerald-500/10','icon'=>'trending-up-outline'],
          'expense'=> ['label'=>lang('App.expense'),'color'=>'text-red-400','bg'=>'bg-red-500/10','icon'=>'trending-down-outline']];
?>

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h2 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="swap-horizontal-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.transaction') ?>
    </h2>
    <div class="flex gap-2">
        <a href="<?= base_url('transaction/create?type=income') ?>"
            class="flex items-center gap-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/40 text-emerald-300 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
            <ion-icon name="trending-up-outline"></ion-icon> <?= lang('App.income') ?>
        </a>
        <a href="<?= base_url('transaction/create?type=expense') ?>"
            class="flex items-center gap-1.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/40 text-red-300 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
            <ion-icon name="trending-down-outline"></ion-icon> <?= lang('App.expense') ?>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-2 mb-2">
    <form method="GET" action="<?= base_url('transaction') ?>" class="flex flex-wrap gap-2">
        <input type="month" name="month" value="<?= esc($filters['month']) ?>"
            class="bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white text-xs rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 flex-1 min-w-32">

        <select name="type" class="bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white text-xs rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 flex-1 min-w-28">
            <option value=""><?= lang('App.all') ?> <?= lang('App.type') ?></option>
            <option value="income"  <?= $filters['type'] === 'income'  ? 'selected' : '' ?>>📈 <?= lang('App.income') ?></option>
            <option value="expense" <?= $filters['type'] === 'expense' ? 'selected' : '' ?>>📉 <?= lang('App.expense') ?></option>
        </select>

        <select name="category_id" class="bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white text-xs rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 flex-1 min-w-36">
            <option value=""><?= lang('App.all') ?> <?= lang('App.category') ?></option>
            <optgroup label="── <?= lang('App.income') ?>">
                <?php foreach ($incomeCategories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $filters['category_id'] == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </optgroup>
            <optgroup label="── <?= lang('App.expense') ?>">
                <?php foreach ($expenseCategories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $filters['category_id'] == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </optgroup>
        </select>

        <div class="relative flex-1 min-w-32">
            <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
            <input type="text" name="search" value="<?= esc($filters['search']) ?>" placeholder="<?= lang('App.search') ?>..."
                class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 text-xs rounded-xl pl-9 pr-3 py-2 focus:outline-none focus:border-emerald-500">
        </div>

        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-1">
            <ion-icon name="filter-outline"></ion-icon> <?= lang('App.filter') ?>
        </button>
        <a href="<?= base_url('transaction') ?>" class="bg-slate-200 dark:bg-slate-600 hover:bg-slate-500 text-slate-800 dark:text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-1">
            <ion-icon name="refresh-outline"></ion-icon>
        </a>
    </form>
</div>

<!-- Transaction List -->
<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
    <?php if (! empty($transactions)): ?>
        <!-- Summary row -->
        <?php
        $totalIn  = array_sum(array_map(fn($t) => $t['type']==='income' ? $t['amount'] : 0, $transactions));
        $totalOut = array_sum(array_map(fn($t) => $t['type']==='expense' ? $t['amount'] : 0, $transactions));
        $opening  = $openingBalance ?? 0;
        $net      = $totalIn - $totalOut;
        $grandTotal = $opening + $net;
        ?>
        <div class="px-3 py-2 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-slate-500 dark:text-slate-400"><?= lang('App.total_transactions', [count($transactions)]) ?></span>
                <span class="text-emerald-400 font-semibold">+<?= rupiah($totalIn) ?></span>
                <span class="text-red-400 font-semibold">-<?= rupiah($totalOut) ?></span>
            </div>
            <div class="flex items-center justify-between sm:justify-end gap-2 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-slate-700/50">
                <span class="text-[10px] text-slate-500 dark:text-slate-400"><?= lang('App.balance') ?><?= $openingBalance !== null ? ' (Total)' : '' ?>:</span>
                <span class="font-bold <?= $grandTotal >= 0 ? 'text-emerald-300' : 'text-red-300' ?>"><?= rupiah($grandTotal) ?></span>
            </div>
        </div>

        <div class="divide-y divide-slate-200 dark:divide-slate-700/50">
            <!-- Saldo Awal Row -->
            <?php if ($openingBalance !== null): ?>
            <div class="flex items-center gap-2 p-3 bg-blue-500/5 border-b border-slate-200 dark:border-slate-700/50">
                <!-- Icon -->
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-blue-500/10">
                    <ion-icon name="wallet-outline" class="text-blue-400" style="font-size:1.1rem;"></ion-icon>
                </div>
                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-bold text-slate-800 dark:text-white truncate"><?= lang('App.opening_balance') ?></p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400"><?= lang('App.balance') ?> <?= lang('App.before') ?> <?= date('M Y', strtotime($filters['month'])) ?></p>
                </div>
                <!-- Amount -->
                <div class="text-right">
                    <p class="text-[13px] font-bold text-blue-400">
                        <?= rupiah($openingBalance) ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <?php foreach ($transactions as $tx): ?>
            <div class="flex items-center gap-2 p-2 hover:bg-slate-700/30 transition-colors" id="tx-<?= $tx['id'] ?>">
                <!-- Icon -->
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background-color:<?= esc($tx['category_color'] ?? '#6366f1') ?>15">
                    <ion-icon name="<?= esc($tx['category_icon'] ?? 'wallet-outline') ?>"
                        style="color:<?= esc($tx['category_color'] ?? '#6366f1') ?>;font-size:1rem;"></ion-icon>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-slate-800 dark:text-white truncate"><?= esc($tx['description'] ?: $tx['category_name'] ?: 'Transaksi') ?></p>
                    <div class="flex items-center flex-wrap gap-x-2 gap-y-0.5">
                        <span class="text-[10px] text-slate-500 dark:text-slate-400"><?= esc($tx['category_name'] ?? '-') ?></span>
                        <span class="text-slate-600">·</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400"><?= date('d M Y', strtotime($tx['transaction_date'])) ?></span>
                        <span class="text-slate-600">·</span>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 capitalize"><?= str_replace('_', ' ', $tx['payment_method']) ?></span>
                    </div>
                </div>

                <!-- Amount + Actions -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="text-right">
                        <p class="text-xs font-bold <?= $tx['type'] === 'income' ? 'text-emerald-400' : 'text-red-400' ?>">
                            <?= $tx['type'] === 'income' ? '+' : '-' ?><?= rupiah($tx['amount']) ?>
                        </p>
                        <span class="inline-block text-xs px-1.5 py-0.5 rounded-md <?= $tx['type']==='income' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' ?>">
                            <?= $types[$tx['type']]['label'] ?? $tx['type'] ?>
                        </span>
                    </div>
                    <button onclick="deleteTx(<?= $tx['id'] ?>)" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                        <ion-icon name="trash-outline" class="text-sm"></ion-icon>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="flex flex-col items-center justify-center py-16 text-slate-400 dark:text-slate-500">
            <ion-icon name="receipt-outline" class="text-5xl mb-3"></ion-icon>
            <p class="font-medium mb-1"><?= lang('App.no_data') ?></p>
            <p class="text-sm text-slate-600"><?= lang('App.record_first_transaction') ?></p>
            <a href="<?= base_url('transaction/create') ?>" class="mt-4 bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors flex items-center gap-1">
                <ion-icon name="add-outline"></ion-icon> <?= lang('App.add') ?> <?= lang('App.transaction') ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function deleteTx(id) {
    Swal.fire({
        title: '<?= lang('App.confirm_delete_transaction_title') ?>',
        text: '<?= lang('App.confirm_delete_transaction_text') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#475569',
        confirmButtonText: '<?= lang('App.yes_delete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b',
        color: '#f1f5f9',
    }).then(result => {
        if (result.isConfirmed) {
            showLoading();
            fetch(`<?= base_url('transaction/delete/') ?>` + id, { method: 'POST' })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        document.getElementById('tx-' + id)?.remove();
                        Toast.fire({ icon: 'success', title: '<?= lang('App.delete_success') ?>' });
                    } else {
                        Toast.fire({ icon: 'error', title: data.message });
                    }
                })
                .catch(err => {
                    hideLoading();
                    Toast.fire({ icon: 'error', title: '<?= lang('App.error_occurred') ?>' });
                });
        }
    });
}
</script>
<?= $this->endSection() ?>
