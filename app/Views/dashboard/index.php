<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$currentMonth = $month ?? date('Y-m');
function rupiah($n) {
    return 'Rp ' . number_format($n ?? 0, 0, ',', '.');
}
?>

<!-- Month Picker -->
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white"><?= lang('App.dashboard') ?></h2>
    <div class="flex items-center gap-2">
        <input type="month" id="monthPicker" value="<?= $currentMonth ?>"
            class="bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500"
            onchange="window.location='/dashboard?month='+this.value">
    </div>
</div>

<?php if (session('role') === 'admin' && $adminStats): ?>
<!-- Admin Global Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="bg-gradient-to-br from-blue-500/20 to-indigo-600/20 border border-blue-500/30 rounded-2xl p-4 transition-all hover:shadow-md">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <ion-icon name="people-outline" class="text-blue-400"></ion-icon>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><?= lang('App.total_user') ?></span>
        </div>
        <p class="text-base font-bold text-slate-800 dark:text-white"><?= $adminStats['total_users'] ?></p>
    </div>
    <div class="bg-gradient-to-br from-violet-500/20 to-purple-600/20 border border-violet-500/30 rounded-2xl p-4 transition-all hover:shadow-md">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-violet-500/20 rounded-lg flex items-center justify-center">
                <ion-icon name="swap-horizontal-outline" class="text-violet-400"></ion-icon>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><?= lang('App.all_transactions') ?></span>
        </div>
        <p class="text-base font-bold text-slate-800 dark:text-white"><?= number_format($adminStats['total_transactions']) ?></p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500/20 to-green-600/20 border border-emerald-500/30 rounded-2xl p-4 transition-all hover:shadow-md">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                <ion-icon name="trending-up-outline" class="text-emerald-400"></ion-icon>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><?= lang('App.income') ?></span>
        </div>
        <p class="text-sm font-bold text-emerald-400 truncate"><?= rupiah($adminStats['total_income']) ?></p>
    </div>
    <div class="bg-gradient-to-br from-red-500/20 to-rose-600/20 border border-red-500/30 rounded-2xl p-4 transition-all hover:shadow-md">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center">
                <ion-icon name="trending-down-outline" class="text-red-400"></ion-icon>
            </div>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><?= lang('App.expense') ?></span>
        </div>
        <p class="text-sm font-bold text-red-400 truncate"><?= rupiah($adminStats['total_expense']) ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <!-- Opening Balance -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-slate-500 dark:text-slate-400 text-[13px] font-medium"><?= lang('App.opening_balance') ?></p>
            <div class="w-9 h-9 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <ion-icon name="wallet-outline" class="text-blue-400"></ion-icon>
            </div>
        </div>
        <p class="text-lg font-bold text-blue-400 break-all"><?= rupiah($openingBalance) ?></p>
        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1"><?= lang('App.before') ?> <?= date('F Y', strtotime($currentMonth . '-01')) ?></p>
    </div>

    <!-- Income -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-slate-500 dark:text-slate-400 text-[13px] font-medium"><?= lang('App.income') ?></p>
            <div class="w-9 h-9 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                <ion-icon name="trending-up-outline" class="text-emerald-400"></ion-icon>
            </div>
        </div>
        <p class="text-lg font-bold text-emerald-400 break-all"><?= rupiah($summary['total_income']) ?></p>
        <div class="mt-2 h-1 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-400 rounded-full" style="width: <?= $summary['total_income'] > 0 ? 100 : 0 ?>%"></div>
        </div>
    </div>

    <!-- Expense -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4">
        <div class="flex items-center justify-between mb-2">
            <p class="text-slate-500 dark:text-slate-400 text-[13px] font-medium"><?= lang('App.expense') ?></p>
            <div class="w-9 h-9 bg-red-500/20 rounded-xl flex items-center justify-center">
                <ion-icon name="trending-down-outline" class="text-red-400"></ion-icon>
            </div>
        </div>
        <p class="text-lg font-bold text-red-400 break-all"><?= rupiah($summary['total_expense']) ?></p>
        <?php
        $expRatio = $summary['total_income'] > 0
            ? min(100, round(($summary['total_expense'] / $summary['total_income']) * 100))
            : 0; ?>
        <div class="mt-2 h-1 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full <?= $expRatio > 80 ? 'bg-red-500' : 'bg-orange-400' ?> rounded-full" style="width: <?= $expRatio ?>%"></div>
        </div>
    </div>

    <!-- Total Balance -->
    <div class="<?= $grandTotalBalance >= 0 ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-red-500 to-rose-600' ?> rounded-2xl p-4 shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <p class="text-white/80 text-[13px] font-medium"><?= lang('App.balance') ?> (Total)</p>
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                <ion-icon name="cash-outline" class="text-white"></ion-icon>
            </div>
        </div>
        <p class="text-lg font-bold text-white break-all"><?= rupiah($grandTotalBalance) ?></p>
        <p class="text-white/60 text-[10px] mt-1"><?= lang('App.status') ?>: <?= $grandTotalBalance >= 0 ? lang('App.active') : lang('App.inactive') ?></p>
    </div>
</div>

<!-- Chart + Category Breakdown -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
    <!-- 7-Day Chart -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-4">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
            <ion-icon name="analytics-outline" class="text-emerald-400"></ion-icon>
            <?= lang('App.last_7_days') ?>
        </h3>
        <div class="h-56 mt-2 relative w-full">
            <canvas id="weekChart"></canvas>
        </div>
    </div>

    <!-- Expense by Category -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <ion-icon name="pie-chart-outline" class="text-emerald-400"></ion-icon>
            <?= lang('App.expense_by_category') ?>
        </h3>
        <?php if (! empty($byCategory)): ?>
            <div class="space-y-2.5">
                <?php $totalExp = array_sum(array_column($byCategory, 'total')); ?>
                <?php foreach (array_slice($byCategory, 0, 5) as $cat): ?>
                <?php $pct = $totalExp > 0 ? round(($cat['total']/$totalExp)*100) : 0; ?>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background-color:<?= esc($cat['color'] ?? '#6366f1') ?>20">
                                <ion-icon name="<?= esc($cat['icon'] ?? 'wallet-outline') ?>" style="color:<?= esc($cat['color'] ?? '#6366f1') ?>;font-size:0.75rem;"></ion-icon>
                            </div>
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate max-w-24"><?= esc($cat['name'] ?? lang('App.others')) ?></span>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400"><?= $pct ?>%</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width:<?= $pct ?>%;background-color:<?= esc($cat['color'] ?? '#6366f1') ?>"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center h-32 text-slate-400 dark:text-slate-500">
                <ion-icon name="pie-chart-outline" class="text-3xl mb-2"></ion-icon>
                <p class="text-sm"><?= lang('App.no_data') ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Budget Progress -->
<?php if (! empty($budgets)): ?>
<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 mb-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white flex items-center gap-2">
            <ion-icon name="shield-checkmark-outline" class="text-emerald-400"></ion-icon>
            <?= lang('App.budget_progress') ?>
        </h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <?php foreach ($budgets as $b): ?>
        <div class="bg-slate-700/40 rounded-xl p-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-slate-800 dark:text-white truncate"><?= esc($b['name']) ?></span>
                <span class="text-xs <?= $b['percentage'] >= 100 ? 'text-red-400' : ($b['percentage'] >= 80 ? 'text-orange-400' : 'text-emerald-400') ?> font-semibold"><?= $b['percentage'] ?>%</span>
            </div>
            <div class="h-2 bg-slate-200 dark:bg-slate-600 rounded-full overflow-hidden mb-1.5">
                <div class="h-full rounded-full transition-all <?= $b['percentage'] >= 100 ? 'bg-red-500' : ($b['percentage'] >= 80 ? 'bg-orange-400' : 'bg-emerald-400') ?>" style="width:<?= min(100,$b['percentage']) ?>%"></div>
            </div>
            <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                <span><?= rupiah($b['spent']) ?></span>
                <span><?= rupiah($b['amount']) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Recent Transactions -->
<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white flex items-center gap-2">
            <ion-icon name="time-outline" class="text-emerald-400"></ion-icon>
            <?= lang('App.recent_transactions') ?>
        </h3>
        <a href="<?= base_url('transaction?month=' . $currentMonth) ?>" class="text-xs text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
            <?= lang('App.view_all') ?> <ion-icon name="chevron-forward-outline"></ion-icon>
        </a>
    </div>

    <?php if (! empty($recentTx)): ?>
    <div class="space-y-2">
        <?php foreach ($recentTx as $tx): ?>
        <div class="flex items-center gap-2 p-2 hover:bg-slate-100 dark:bg-slate-700/50 rounded-xl transition-colors group">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background-color:<?= esc($tx['category_color'] ?? '#6366f1') ?>20">
                <ion-icon name="<?= esc($tx['category_icon'] ?? 'wallet-outline') ?>"
                    style="color:<?= esc($tx['category_color'] ?? '#6366f1') ?>;"></ion-icon>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-800 dark:text-white truncate"><?= esc($tx['description'] ?: $tx['category_name'] ?: lang('App.transaction')) ?></p>
                <p class="text-xs text-slate-500 dark:text-slate-400"><?= esc($tx['category_name'] ?? '-') ?> · <?= date('d M Y', strtotime($tx['transaction_date'])) ?></p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-semibold <?= $tx['type'] === 'income' ? 'text-emerald-400' : 'text-red-400' ?>">
                    <?= $tx['type'] === 'income' ? '+' : '-' ?><?= rupiah($tx['amount']) ?>
                </p>
                <p class="text-xs text-slate-400 dark:text-slate-500 capitalize"><?= str_replace('_',' ', $tx['payment_method']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="flex flex-col items-center justify-center py-10 text-slate-400 dark:text-slate-500">
        <ion-icon name="receipt-outline" class="text-4xl mb-3"></ion-icon>
        <p class="text-sm font-medium"><?= lang('App.no_transactions_this_month') ?></p>
        <a href="<?= base_url('transaction/create') ?>" class="mt-3 text-xs text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
            <ion-icon name="add-circle-outline"></ion-icon> <?= lang('App.record_first_transaction') ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const chartData = <?= json_encode(array_values($chartData)) ?>;
const chartLabels = <?= json_encode(array_map(function($d) {
    $time = \CodeIgniter\I18n\Time::parse($d);
    return $time->toLocalizedString('EEE dd/MMM');
}, array_keys($chartData))) ?>;

const isDark = document.documentElement.classList.contains('dark');
const textColor = isDark ? '#cbd5e1' : '#64748b';
const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

const ctx = document.getElementById('weekChart').getContext('2d');

// Gradients for line fill
const gradientIncome = ctx.createLinearGradient(0, 0, 0, 400);
gradientIncome.addColorStop(0, 'rgba(52, 211, 153, 0.4)'); // emerald-400
gradientIncome.addColorStop(1, 'rgba(52, 211, 153, 0.0)');

const gradientExpense = ctx.createLinearGradient(0, 0, 0, 400);
gradientExpense.addColorStop(0, 'rgba(244, 63, 94, 0.4)'); // rose-500
gradientExpense.addColorStop(1, 'rgba(244, 63, 94, 0.0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartLabels,
        datasets: [
            {
                label: '<?= lang('App.income') ?>',
                data: chartData.map(d => d.income),
                borderColor: '#10b981', // emerald-500
                backgroundColor: gradientIncome,
                borderWidth: 3,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // curve
            },
            {
                label: '<?= lang('App.expense') ?>',
                data: chartData.map(d => d.expense),
                borderColor: '#f43f5e', // rose-500
                backgroundColor: gradientExpense,
                borderWidth: 3,
                pointBackgroundColor: '#f43f5e',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // curve
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                position: 'top',
                align: 'end',
                labels: { color: textColor, font: { size: 11, family: 'Inter' }, usePointStyle: true, pointStyle: 'circle' } 
            },
            tooltip: {
                backgroundColor: isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.95)',
                titleColor: isDark ? '#f8fafc' : '#0f172a',
                bodyColor: isDark ? '#cbd5e1' : '#475569',
                borderColor: isDark ? '#334155' : '#e2e8f0',
                borderWidth: 1,
                padding: 12,
                boxPadding: 6,
                usePointStyle: true,
                intersect: false,
                mode: 'index',
                callbacks: {
                    label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID'),
                }
            }
        },
        scales: {
            x: { 
                ticks: { color: textColor, font: { size: 10 } }, 
                grid: { color: gridColor, drawBorder: false, display: false } // clean vertical
            },
            y: { 
                ticks: { 
                    color: textColor, 
                    font: { size: 10 }, 
                    callback: v => 'Rp ' + (v > 0 ? (v/1000).toLocaleString('id-ID') + 'k' : '0')
                }, 
                grid: { color: gridColor, drawBorder: false, borderDash: [5, 5] },
                beginAtZero: true
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});
</script>
<?= $this->endSection() ?>
