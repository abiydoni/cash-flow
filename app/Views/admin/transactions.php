<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php function rupiah($n){return 'Rp '.number_format($n??0,0,',','.');} ?>

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="list-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.all_transactions') ?>
    </h2>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="user_id" class="bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 flex-1 min-w-36">
            <option value=""><?= lang('App.all') ?> User</option>
            <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" <?= $filters['user_id'] == $u['id'] ? 'selected' : '' ?>><?= esc($u['username']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="month" name="month" value="<?= esc($filters['month']) ?>"
            class="bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 flex-1 min-w-32">
        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white text-sm font-semibold px-4 py-2 rounded-xl flex items-center gap-1">
            <ion-icon name="filter-outline"></ion-icon> <?= lang('App.filter') ?>
        </button>
    </form>
</div>

<?php
$totalIn  = array_sum(array_map(fn($t) => $t['type']==='income'  ? $t['amount'] : 0, $transactions));
$totalOut = array_sum(array_sum(array_map(fn($t) => $t['type']==='expense' ? $t['amount'] : 0, $transactions))); // wait, fixed the array_sum error in original mapping
$totalOut = 0; foreach($transactions as $t) if($t['type']==='expense') $totalOut += $t['amount'];
$net      = $totalIn - $totalOut;
$grandTotal = ($openingBalance ?? 0) + $net;
?>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-3 text-center">
        <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1"><?= lang('App.opening_balance') ?></p>
        <p class="text-sm font-bold text-blue-400"><?= rupiah($openingBalance) ?></p>
    </div>
    <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-3 text-center">
        <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1"><?= lang('App.total_in') ?></p>
        <p class="text-sm font-bold text-emerald-400"><?= rupiah($totalIn) ?></p>
    </div>
    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-3 text-center">
        <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1"><?= lang('App.total_out') ?></p>
        <p class="text-sm font-bold text-red-400"><?= rupiah($totalOut) ?></p>
    </div>
    <div class="<?= $grandTotal>=0 ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-red-500/10 border-red-500/30' ?> border rounded-xl p-3 text-center">
        <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1"><?= lang('App.balance') ?></p>
        <p class="text-sm font-bold <?= $grandTotal>=0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= rupiah($grandTotal) ?></p>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
    <div class="divide-y divide-slate-200 dark:divide-slate-700/50">
        <!-- Opening Balance Row -->
        <?php if ($openingBalance != 0): ?>
        <div class="flex items-center gap-3 p-4 bg-slate-50/50 dark:bg-slate-700/20">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-500/20 text-blue-400">
                <ion-icon name="wallet-outline"></ion-icon>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-blue-400 opacity-80 italic tracking-wide"><?= lang('App.opening_balance') ?></p>
                <p class="text-[10px] text-slate-500 dark:text-slate-400"><?= lang('App.before') ?> <?= date('F Y', strtotime($filters['month'] . '-01')) ?></p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-blue-400"><?= rupiah($openingBalance) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (! empty($transactions)): ?>
            <?php foreach ($transactions as $tx): ?>
            <div class="flex items-center gap-3 p-4 hover:bg-slate-700/30 transition-colors">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                    style="background-color:<?= esc($tx['category_color'] ?? '#6366f1') ?>20">
                    <ion-icon name="<?= esc($tx['category_icon'] ?? 'wallet-outline') ?>"
                        style="color:<?= esc($tx['category_color'] ?? '#6366f1') ?>"></ion-icon>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white truncate"><?= esc($tx['description'] ?: $tx['category_name'] ?: lang('App.transaction')) ?></p>
                    <div class="flex items-center flex-wrap gap-x-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1">
                            <ion-icon name="person-outline" class="text-xs"></ion-icon>
                            <?= esc($tx['full_name'] ?? $tx['username']) ?>
                        </span>
                        <span>·</span>
                        <span><?= esc($tx['category_name'] ?? '-') ?></span>
                        <span>·</span>
                        <span><?= date('d M Y', strtotime($tx['transaction_date'])) ?></span>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold <?= $tx['type']==='income' ? 'text-emerald-400' : 'text-red-400' ?>">
                        <?= $tx['type']==='income' ? '+' : '-' ?><?= rupiah($tx['amount']) ?>
                    </p>
                    <span class="text-xs text-slate-400 dark:text-slate-500 capitalize"><?= str_replace('_',' ',$tx['payment_method']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="flex flex-col items-center justify-center py-16 text-slate-400 dark:text-slate-500">
        <ion-icon name="receipt-outline" class="text-5xl mb-3"></ion-icon>
        <p class="text-sm"><?= lang('App.no_transactions_found') ?></p>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
