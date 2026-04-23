<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 no-print">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <ion-icon name="stats-chart-outline" class="text-emerald-400"></ion-icon>
            <?= lang('App.reports') ?>
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400"><?= lang('App.financial_summary') ?> <?= $year ?></p>
    </div>
    
    <div class="flex items-center gap-3">
        <form method="GET" class="flex items-center gap-2 bg-white dark:bg-slate-800 p-1.5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <select name="year" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-slate-600 dark:text-slate-300 focus:ring-0 cursor-pointer pl-4 pr-10">
                <?php for($y = date('Y')-2; $y <= date('Y')+1; $y++): ?>
                    <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>

        <button onclick="window.print()" class="h-10 px-6 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white font-bold text-xs transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-2">
            <ion-icon name="print-outline" class="text-lg"></ion-icon> <?= lang('App.print') ?>
        </button>
    </div>
</div>

<!-- Main Report Content -->
<div class="print-container space-y-10">
    
    <!-- Professional Print Header -->
    <div class="hidden print:flex justify-between items-start border-b-4 border-slate-900 pb-8 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-white text-2xl shadow-lg">
                <ion-icon name="wallet"></ion-icon>
            </div>
            <div>
                <h1 class="text-xl font-bold uppercase tracking-tighter text-slate-900">CashFlow</h1>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest"><?= lang('App.financial_mgmt') ?></p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-slate-900 uppercase italic leading-tight"><?= lang('App.annual_report') ?></h2>
            <p class="text-xs font-bold text-emerald-500 uppercase"><?= lang('App.fiscal_year') ?> <?= $year ?></p>
            <p class="text-[8px] text-slate-400 mt-1"><?= lang('App.generate_date') ?>: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>

    <!-- Executive Summary Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 print:grid-cols-4 print:gap-4 no-print">
        <!-- Opening Balance Card -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg relative overflow-hidden group print:rounded-2xl print:border-slate-300">
            <div class="relative">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.opening_balance') ?></p>
                <h3 class="text-sm font-bold text-slate-600 dark:text-slate-300 whitespace-nowrap">Rp <?= number_format($openingBalance, 0, ',', '.') ?></h3>
                <div class="mt-2 flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-lg bg-slate-500/10 flex items-center justify-center text-slate-500 text-[10px]"><ion-icon name="calendar-clear-outline"></ion-icon></div>
                    <span class="text-[8px] font-bold text-slate-500 uppercase"><?= $year - 1 ?></span>
                </div>
            </div>
        </div>

        <!-- Income Card -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg relative overflow-hidden group print:rounded-2xl print:border-slate-300">
            <div class="relative">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.total_income') ?></p>
                <h3 class="text-sm font-bold text-emerald-500 whitespace-nowrap">Rp <?= number_format($totalIncome, 0, ',', '.') ?></h3>
                <div class="mt-2 flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-[10px]"><ion-icon name="arrow-up-outline"></ion-icon></div>
                    <span class="text-[8px] font-bold text-slate-500 uppercase"><?= lang('App.gross_income') ?></span>
                </div>
            </div>
        </div>

        <!-- Expense Card -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg relative overflow-hidden group print:rounded-2xl print:border-slate-300">
            <div class="relative">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.total_expense') ?></p>
                <h3 class="text-sm font-bold text-red-500 whitespace-nowrap">Rp <?= number_format($totalExpense, 0, ',', '.') ?></h3>
                <div class="mt-2 flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-lg bg-red-500/10 flex items-center justify-center text-red-500 text-[10px]"><ion-icon name="arrow-down-outline"></ion-icon></div>
                    <span class="text-[8px] font-bold text-slate-500 uppercase"><?= lang('App.gross_expense') ?></span>
                </div>
            </div>
        </div>

        <!-- Net Balance -->
        <div class="bg-slate-900 p-4 rounded-2xl border border-slate-800 shadow-lg relative overflow-hidden group print:rounded-2xl print:border-slate-900 print:bg-slate-50">
            <div class="relative">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1"><?= lang('App.net_balance') ?></p>
                <h3 class="text-sm font-bold text-white print:text-slate-900 whitespace-nowrap">Rp <?= number_format($totalIncome - $totalExpense + $openingBalance, 0, ',', '.') ?></h3>
                <div class="mt-2 flex items-center gap-1.5">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-900 text-[10px]"><ion-icon name="wallet-outline"></ion-icon></div>
                    <span class="text-[8px] font-bold text-slate-400 uppercase print:text-slate-600"><?= lang('App.current_cash') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance Analysis -->
    <div class="bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-[2.5rem] overflow-hidden shadow-2xl print:rounded-2xl print:border-slate-400">
        <div class="p-8 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 print:bg-slate-100">
            <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-3 uppercase tracking-tighter text-lg">
                <div class="w-2 h-6 bg-emerald-500 rounded-full"></div>
                <?= lang('App.monthly_performance_analysis') ?>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="px-1 py-2 text-[8px] font-bold uppercase tracking-tighter border-r border-slate-700 w-6 text-center">No</th>
                        <th class="px-1 py-2 text-[8px] font-bold uppercase tracking-tighter border-r border-slate-700 w-12"><?= lang('App.month') ?></th>
                        <th class="px-1 py-2 text-[8px] font-bold uppercase tracking-tighter border-r border-slate-700 text-right"><?= lang('App.revenue') ?></th>
                        <th class="px-1 py-2 text-[8px] font-bold uppercase tracking-tighter border-r border-slate-700 text-right"><?= lang('App.expenditure') ?></th>
                        <th class="px-1 py-2 text-[8px] font-bold uppercase tracking-tighter text-right"><?= lang('App.net_balance') ?></th>
                        <th class="px-1 py-2 text-[8px] font-bold uppercase tracking-tighter text-right no-print w-8">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <!-- Row for Opening Balance (Previous Year) -->
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 italic">
                        <td class="px-1 py-2 text-[8px] font-bold text-slate-400 text-center border-r border-slate-50 dark:border-slate-700">-</td>
                        <td class="px-1 py-2 text-[8px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-tighter border-r border-slate-50 dark:border-slate-700">
                            Saldo Awal <?= $year ?>
                        </td>
                        <td class="px-1 py-2 text-[9px] font-bold text-slate-400 text-right border-r border-slate-50 dark:border-slate-700 whitespace-nowrap tracking-tighter">
                            <?= number_format($openingBalance, 0, ',', '.') ?>
                        </td>
                        <td class="px-1 py-2 text-[9px] font-bold text-slate-400 text-right border-r border-slate-50 dark:border-slate-700 whitespace-nowrap tracking-tighter">
                            0
                        </td>
                        <td class="px-1 py-2 text-[9px] font-bold text-right text-slate-400 whitespace-nowrap tracking-tighter">
                            <?= number_format($openingBalance, 0, ',', '.') ?>
                        </td>
                        <td class="px-1 py-2 text-right no-print"></td>
                    </tr>
                    <?php 
                    $months = [
                        1=>lang('App.january'), 2=>lang('App.february'), 3=>lang('App.march'), 4=>lang('App.april'),
                        5=>lang('App.may'), 6=>lang('App.june'), 7=>lang('App.july'), 8=>lang('App.august'),
                        9=>lang('App.september'), 10=>lang('App.october'), 11=>lang('App.november'), 12=>lang('App.december')
                    ];
                    foreach($trends as $mIdx => $val): 
                        $balance = $val['income'] - $val['expense'];
                        // Abbreviate month for mobile
                        $shortMonth = substr($months[$mIdx], 0, 3);
                    ?>
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-500/10 transition-all group">
                        <td class="px-1 py-2 text-[8px] font-bold text-slate-400 text-center border-r border-slate-50 dark:border-slate-700"><?= $mIdx ?></td>
                        <td class="px-1 py-2 text-[8px] font-bold text-slate-800 dark:text-slate-200 uppercase tracking-tighter border-r border-slate-50 dark:border-slate-700">
                            <span class="sm:hidden"><?= $shortMonth ?></span>
                            <span class="hidden sm:inline"><?= $months[$mIdx] ?></span>
                        </td>
                        <td class="px-1 py-2 text-[9px] font-bold text-emerald-500 text-right border-r border-slate-50 dark:border-slate-700 whitespace-nowrap tracking-tighter">
                            <?= number_format($val['income'], 0, ',', '.') ?>
                        </td>
                        <td class="px-1 py-2 text-[9px] font-bold text-red-400 text-right border-r border-slate-50 dark:border-slate-700 whitespace-nowrap tracking-tighter">
                            <?= number_format($val['expense'], 0, ',', '.') ?>
                        </td>
                        <td class="px-1 py-2 text-[9px] font-bold text-right <?= $balance >= 0 ? 'text-emerald-500' : 'text-red-400' ?> whitespace-nowrap tracking-tighter">
                            <?= number_format($balance, 0, ',', '.') ?>
                        </td>
                        <td class="px-1 py-2 text-right no-print">
                            <a href="<?= base_url("report/month/{$year}/{$mIdx}") ?>" class="inline-flex items-center justify-center w-9 h-9 text-emerald-500 hover:text-emerald-600 transition-all group/btn" title="<?= lang('App.view_report') ?>">
                                <ion-icon name="print-outline" class="text-xl"></ion-icon>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <!-- Restore TFOOT only for PRINT -->
                <tfoot class="hidden print:table-footer-group bg-slate-100 text-slate-900 font-bold border-t-2 border-slate-900">
                    <tr>
                        <td class="px-1"></td>
                        <td class="px-2 py-3 text-[10px] uppercase font-black uppercase tracking-widest border-r border-slate-300"><?= lang('App.total_annual_flow') ?></td>
                        <td class="px-2 py-3 text-[10px] text-right border-r border-slate-300 whitespace-nowrap">Rp <?= number_format($totalIncome + $openingBalance, 0, ',', '.') ?></td>
                        <td class="px-2 py-3 text-[10px] text-right border-r border-slate-300 whitespace-nowrap">Rp <?= number_format($totalExpense, 0, ',', '.') ?></td>
                        <td class="px-2 py-3 text-[10px] text-right whitespace-nowrap">Rp <?= number_format($totalIncome - $totalExpense + $openingBalance, 0, ',', '.') ?></td>
                        <td class="px-2 py-3 no-print"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Annual Summary (outside table - screen only) -->
        <div class="no-print bg-slate-900 px-4 py-4 sm:px-8 sm:py-6 flex flex-col gap-3">
            <div class="flex justify-between items-center pb-2 border-b border-white/5">
                <span class="text-[9px] uppercase tracking-widest text-slate-400"><?= lang('App.total_annual_income') ?> (+Saldo Awal)</span>
                <span class="text-sm font-bold text-emerald-400 whitespace-nowrap">Rp <?= number_format($totalIncome + $openingBalance, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-white/5">
                <span class="text-[9px] uppercase tracking-widest text-slate-400"><?= lang('App.total_annual_expense') ?></span>
                <span class="text-sm font-bold text-red-400 whitespace-nowrap">Rp <?= number_format($totalExpense, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center pt-1">
                <span class="text-[10px] font-bold uppercase tracking-widest text-white">Total Saldo Akhir <?= $year ?></span>
                <span class="text-lg font-bold text-white whitespace-nowrap">Rp <?= number_format($totalIncome - $totalExpense + $openingBalance, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="hidden print:flex justify-between items-end pt-12 mt-12 border-t border-slate-200">
        <div class="space-y-4">
            <div class="h-16 w-48 border-b-2 border-slate-300"></div>
            <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest"><?= lang('App.finance_officer') ?></p>
        </div>
        <div class="text-right italic text-[9px] text-slate-400">
            <p><?= lang('App.legal_disclaimer_report') ?></p>
            <p><?= lang('App.verified_digital_signature') ?></p>
        </div>
    </div>
</div>

<style>
@media print {
    @page { margin: 10mm; size: portrait; }
    .no-print { display: none !important; }
    body { background: white !important; font-family: 'Inter', sans-serif !important; }
    .bg-white, .dark\:bg-slate-800 { background: white !important; border-color: #eee !important; }
    .text-white, .dark\:text-white { color: black !important; }
    .shadow-xl, .shadow-2xl { box-shadow: none !important; }
    
    /* Condense for single page */
    .print-container { margin: 0 !important; padding: 0 !important; color: black !important; gap: 1rem !important; }
    .space-y-10 > :not([hidden]) ~ :not([hidden]) { margin-top: 1rem !important; }
    .mb-10 { margin-bottom: 1rem !important; }
    .mt-12 { margin-top: 1.5rem !important; }
    .pb-8 { padding-bottom: 1rem !important; }
    .pt-12 { padding-top: 1rem !important; }
    .px-8 { padding-left: 1rem !important; padding-right: 1rem !important; }
    .py-5, .py-6 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
    .h-16 { height: 3rem !important; }
    .w-16 { width: 3rem !important; }
    .text-3xl { font-size: 1.5rem !important; }
    .text-2xl { font-size: 1.25rem !important; }
    .text-lg { font-size: 0.875rem !important; }
    .text-xl { font-size: 1rem !important; }

    table { width: 100% !important; border-collapse: collapse !important; border: 1px solid #ddd !important; }
    thead tr { background: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    th { background: #000 !important; color: #fff !important; font-weight: 900 !important; border: 1px solid #333 !important; padding: 6px 8px !important; font-size: 8pt !important; text-transform: uppercase !important; }
    td { border: 1px solid #ddd !important; color: #1e293b !important; padding: 4px 8px !important; font-size: 8pt !important; }
    .rounded-\[2rem\], .rounded-3xl, .rounded-\[2\.5rem\], .rounded-2xl, .rounded-xl, .rounded-lg { border-radius: 0 !important; }
    .divide-y > * + * { border-top-width: 1px !important; border-color: #ddd !important; }

    /* Prevent breaking */
    .print-container { overflow: visible !important; }
    table, tr, td, th { page-break-inside: avoid !important; }
}
</style>

<?= $this->endSection() ?>
