<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-8 no-print">
    <div class="flex items-center gap-3">
        <a href="<?= base_url('report?year=' . $year) ?>" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-emerald-500 transition-all shadow-sm">
            <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white"><?= lang('App.reports') ?> <?= $monthName ?> <?= $year ?></h2>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold"><?= lang('App.details') ?></p>
        </div>
    </div>
    <button onclick="window.print()" class="h-10 px-6 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white font-bold text-xs transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-2">
        <ion-icon name="print-outline" class="text-lg"></ion-icon> <?= lang('App.print') ?>
    </button>
</div>

<div class="print-container space-y-10 pb-12">
    
    <!-- Professional Print Header -->
    <div class="hidden print:flex justify-between items-start border-b-4 border-slate-900 pb-8 mb-10">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white text-3xl shadow-lg">
                <ion-icon name="wallet"></ion-icon>
            </div>
            <div>
                <h1 class="text-3xl font-bold uppercase tracking-tighter text-slate-900">CashFlow</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest"><?= lang('App.financial_mgmt') ?></p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-slate-900 uppercase italic"><?= lang('App.monthly_statement') ?></h2>
            <p class="text-sm font-bold text-indigo-500 uppercase"><?= $monthName ?> <?= $year ?></p>
            <p class="text-[9px] text-slate-400 mt-2"><?= lang('App.generate_date') ?>: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>

    <!-- Monthly Stats Overview -->
    <?php 
        $in = 0; $out = 0;
        foreach($transactions as $t) {
            if($t['type'] == 'income') $in += $t['amount'];
            else $out += $t['amount'];
        }
        $duesTotal = 0;
        foreach($dues as $d) $duesTotal += $d['amount_paid'];
    ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 print:grid-cols-4 print:gap-4 no-print">
        <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-2xl bg-white dark:bg-slate-800 text-center">
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.opening_balance') ?></p>
            <p class="text-sm font-bold text-slate-600 dark:text-slate-300 whitespace-nowrap">Rp <?= number_format($openingBalance, 0, ',', '.') ?></p>
        </div>
        <div class="p-4 border border-emerald-500/20 rounded-2xl bg-emerald-500/5 print:border-slate-300 print:bg-white text-center group">
            <p class="text-[8px] font-bold text-emerald-600 print:text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.revenue') ?></p>
            <p class="text-sm font-bold text-slate-900 whitespace-nowrap">Rp <?= number_format($in, 0, ',', '.') ?></p>
        </div>
        <div class="p-4 border border-red-500/20 rounded-2xl bg-red-500/5 print:border-slate-300 print:bg-white text-center">
            <p class="text-[8px] font-bold text-red-600 print:text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.expenditure') ?></p>
            <p class="text-sm font-bold text-slate-900 whitespace-nowrap">Rp <?= number_format($out, 0, ',', '.') ?></p>
        </div>
        <div class="p-4 border border-slate-900 rounded-2xl bg-slate-900 text-white print:border-slate-900 print:bg-slate-50 print:text-slate-900 text-center shadow-lg">
            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?= lang('App.net_balance') ?></p>
            <p class="text-sm font-bold whitespace-nowrap">Rp <?= number_format($in - $out + $openingBalance, 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-10">


        <!-- Comprehensive Transaction List -->
        <div class="bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-[2rem] overflow-hidden shadow-2xl print:rounded-2xl print:border-slate-400">
            <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 print:bg-slate-100 flex justify-between items-center">
                <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-widest flex items-center gap-2">
                    <div class="w-1.5 h-4 bg-indigo-500 rounded-full"></div>
                    <?= lang('App.transaction_history') ?>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <th class="px-2 py-3 text-[8px] font-bold text-slate-400 uppercase tracking-tighter"><?= lang('App.date') ?></th>
                            <th class="px-2 py-3 text-[8px] font-bold text-slate-400 uppercase tracking-tighter"><?= lang('App.description') ?></th>
                            <th class="px-2 py-3 text-[8px] font-bold text-slate-400 uppercase tracking-tighter"><?= lang('App.category') ?></th>
                            <th class="px-2 py-3 text-[8px] font-bold text-slate-400 uppercase tracking-tighter text-right"><?= lang('App.amount') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-[9px]">
                        <!-- Row for Opening Balance (Previous Month) -->
                        <tr class="bg-slate-50/50 dark:bg-slate-900/50 italic">
                            <td class="px-2 py-2 text-slate-400 font-bold whitespace-nowrap">-</td>
                            <td class="px-2 py-2 text-slate-500 font-bold" colspan="2">Saldo Awal Bulan</td>
                            <td class="px-2 py-2 text-right font-bold text-slate-400 whitespace-nowrap">
                                <?= number_format($openingBalance, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php foreach($transactions as $t): ?>
                            <tr>
                                <td class="px-2 py-2 text-slate-500 font-bold whitespace-nowrap"><?= date('d/m/y', strtotime($t['transaction_date'])) ?></td>
                                <td class="px-2 py-2 font-bold text-slate-700 dark:text-slate-200 truncate max-w-[100px]"><?= esc($t['description']) ?></td>
                                <td class="px-2 py-2">
                                    <span class="px-1 py-0.5 rounded-md text-[7px] font-bold uppercase tracking-tighter" style="background:<?= $t['category_color'] ?>15; color:<?= $t['category_color'] ?>">
                                        <?= esc($t['category_name']) ?>
                                    </span>
                                </td>
                                <td class="px-2 py-2 text-right font-bold <?= $t['type']=='income' ? 'text-emerald-500' : 'text-red-500' ?> whitespace-nowrap">
                                    <span class="hidden sm:inline opacity-30 mr-0.5"><?= $t['type']=='income' ? '+' : '-' ?></span><?= number_format($t['amount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <!-- Table Footer for Transaction Summary -->
                    <tfoot class="bg-slate-100 dark:bg-slate-900/80 font-bold border-t-2 border-slate-900 dark:border-slate-700">
                        <tr class="text-slate-600 dark:text-slate-400">
                            <td colspan="3" class="px-3 py-2 text-[9px] uppercase tracking-widest text-right">Sub Total (Bulan Ini)</td>
                            <td class="px-2 py-2 text-[9px] text-right whitespace-nowrap">
                                <?= $in - $out >= 0 ? '+' : '' ?>Rp <?= number_format($in - $out, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="3" class="px-3 py-3 text-[10px] uppercase tracking-widest text-right">Total Saldo Akhir Bulan</td>
                            <td class="px-2 py-3 text-[10px] text-right whitespace-nowrap font-black">
                                Rp <?= number_format($in - $out + $openingBalance, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Monthly Summary (outside table - screen only) -->
            <div class="no-print bg-slate-900 px-4 py-4 sm:px-8 sm:py-5 flex justify-between items-center text-white">
                <span class="text-[9px] uppercase tracking-widest text-slate-400">Total Saldo Akhir Bulan</span>
                <span class="text-sm font-bold whitespace-nowrap">Rp <?= number_format($in - $out + $openingBalance, 0, ',', '.') ?></span>
            </div>
        </div>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="hidden print:flex justify-between items-end pt-12 mt-12 border-t border-slate-200">
        <div class="space-y-4">
            <div class="h-16 w-48 border-b-2 border-slate-300"></div>
            <p class="text-[10px] font-bold text-slate-800 uppercase tracking-widest"><?= lang('App.finance_officer') ?></p>
        </div>
        <div class="text-right italic text-[9px] text-slate-400">
            <p><?= lang('App.legal_disclaimer_report') ?></p>
            <p><?= lang('App.verified_digital_signature') ?></p>
        </div>
    </div>
</div>

<style>
@media print {
    @page { margin: 15mm; size: portrait; }
    .sidebar, header, nav, .no-print { display: none !important; }
    body { background: white !important; font-family: 'Inter', sans-serif !important; }
    .print-container { max-width: 100% !important; margin: 0 !important; }
    .bg-white, .dark\:bg-slate-800 { background: white !important; }
    .text-slate-800, .dark\:text-white { color: black !important; }
    .rounded-\[2rem\], .rounded-3xl, .rounded-2xl, .rounded-xl, .rounded-lg { border-radius: 0 !important; }
    table { width: 100% !important; border-collapse: collapse !important; border: 1px solid #ddd !important; }
    thead tr { background: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    th { background: #000 !important; color: #fff !important; font-weight: 900 !important; border: 1px solid #333 !important; text-transform: uppercase !important; }
    td { border: 1px solid #ddd !important; color: #1e293b !important; }
    .divide-y > * + * { border-top-width: 1px !important; border-color: #ddd !important; }
}
</style>

<?= $this->endSection() ?>
