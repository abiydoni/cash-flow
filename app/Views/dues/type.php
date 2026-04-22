<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
    <div class="flex items-center gap-3">
        <a href="<?= base_url('dues') ?>" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-emerald-500 hover:border-emerald-500/50 transition-all shadow-sm">
            <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white"><?= esc($type['name']) ?></h2>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold"><?= lang('App.select') ?> <?= lang('App.member') ?> (<?= $year ?>)</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <div class="flex items-center gap-2 bg-white dark:bg-slate-800 p-1 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm no-print">
            <?php for($y = date('Y')-1; $y <= date('Y')+1; $y++): ?>
                <a href="?year=<?= $y ?>" class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all <?= $y == $year ? 'bg-emerald-500 text-slate-800 dark:text-white shadow-lg shadow-emerald-500/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' ?>">
                    <?= $y ?>
                </a>
            <?php endfor; ?>
        </div>
        <button onclick="window.print()" class="h-10 px-6 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white font-bold text-xs transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-2 no-print">
            <ion-icon name="print-outline" class="text-lg"></ion-icon> <?= lang('App.print') ?>
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 no-print">
    <?php if(empty($members)): ?>
        <div class="col-span-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-12 text-center text-slate-500">
            <ion-icon name="people-outline" class="text-6xl mb-4 opacity-20"></ion-icon>
            <p><?= lang('App.no_data_member') ?></p>
            <a href="<?= base_url('member') ?>" class="mt-4 inline-flex items-center gap-2 bg-emerald-500 text-slate-900 px-4 py-2 rounded-xl font-bold text-xs">
                <ion-icon name="add-outline"></ion-icon> <?= lang('App.add') ?> <?= lang('App.member') ?>
            </a>
        </div>
    <?php else: ?>
        <?php foreach($members as $m): ?>
            <a href="<?= base_url('dues/member/' . $m['id'] . '?type_id=' . $type['id'] . '&year=' . $year) ?>" class="group block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 hover:shadow-xl hover:border-emerald-500/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center text-slate-800 dark:text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                        <?= strtoupper(substr($m['name'], 0, 1)) ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-slate-800 dark:text-white truncate group-hover:text-emerald-400 transition-colors"><?= esc($m['name']) ?></h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full <?= $m['is_active'] ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium tracking-tight">Status: <?= $m['is_active'] ? lang('App.active') : lang('App.inactive') ?></p>
                        </div>
                    </div>
                    <div class="text-slate-300 dark:text-slate-600 group-hover:text-emerald-400 transition-colors">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Landscape Matrix Print Report -->
<div class="hidden print:block bg-white text-slate-900 p-0">
    <div class="flex justify-between items-end border-b-4 border-slate-900 pb-4 mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-black uppercase tracking-tighter text-slate-900 leading-none">CashFlow</h1>
            <div class="border-l-2 border-slate-300 pl-3">
                <h2 class="text-lg font-bold text-slate-800 uppercase italic"><?= lang('App.yearly_ledger') ?></h2>
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest"><?= esc($type['name']) ?> - <?= $year ?></p>
            </div>
        </div>
        <div class="text-right text-[9px] font-bold text-slate-500 uppercase">
            <?= lang('App.print_date') ?>: <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <table class="w-full text-[9px] border-collapse border border-slate-800">
        <thead>
            <tr class="bg-black text-white">
                <th class="border border-slate-800 px-2 py-3 w-8 text-center">NO</th>
                <th class="border border-slate-800 px-3 py-3 text-left w-48"><?= lang('App.member_name') ?></th>
                <?php foreach($months as $mName): ?>
                    <th class="border border-slate-800 px-1 py-3 text-center uppercase tracking-tighter w-12"><?= substr($mName, 0, 3) ?></th>
                <?php endforeach; ?>
                <th class="border border-slate-800 px-2 py-3 text-right w-24">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $i = 1; 
                $grandTotal = 0;
                $monthlyTotals = array_fill(1, 12, 0);
            ?>
            <?php foreach($members as $m): ?>
                <?php 
                    $memberTotal = 0;
                    $joinDate = strtotime($m['join_date'] ?? '');
                    $joinYear = date('Y', $joinDate);
                    $joinMonth = date('n', $joinDate);
                ?>
                <tr class="even:bg-slate-50">
                    <td class="border border-slate-300 px-1 py-2 text-center font-bold text-slate-400"><?= $i++ ?></td>
                    <td class="border border-slate-300 px-3 py-2 font-bold uppercase truncate"><?= esc($m['name']) ?></td>
                    <?php for($mIdx = 1; $mIdx <= 12; $mIdx++): ?>
                        <?php 
                            $paid = $paymentMatrix[$m['id']][$mIdx] ?? 0;
                            $memberTotal += $paid;
                            $monthlyTotals[$mIdx] += $paid;
                            $isDisabled = ($year < $joinYear) || ($year == $joinYear && $mIdx < $joinMonth);
                        ?>
                        <td class="border border-slate-300 px-1 py-2 text-center">
                            <?php if($paid > 0): ?>
                                <span class="font-bold text-emerald-600">L</span>
                            <?php elseif($isDisabled): ?>
                                <span class="text-slate-200 text-[7px] italic">-</span>
                            <?php else: ?>
                                <span class="text-red-400 font-bold opacity-30">X</span>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                    <td class="border border-slate-300 px-2 py-2 text-right font-black bg-slate-100/50">
                        <?= number_format($memberTotal, 0, ',', '.') ?>
                    </td>
                </tr>
                <?php $grandTotal += $memberTotal; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bg-slate-900 text-white font-bold">
            <tr>
                <td colspan="2" class="border border-slate-800 px-3 py-3 text-right uppercase tracking-widest text-[10px]"><?= lang('App.total_per_month') ?></td>
                <?php for($mIdx = 1; $mIdx <= 12; $mIdx++): ?>
                    <td class="border border-slate-800 px-1 py-3 text-center text-[8px] bg-slate-800">
                        <?= number_format($monthlyTotals[$mIdx], 0, '', '.') ?>
                    </td>
                <?php endfor; ?>
                <td class="border border-slate-800 px-2 py-3 text-right text-[11px] bg-emerald-600">
                    <?= number_format($grandTotal, 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="flex justify-between items-start mt-12 p-8">
        <div class="text-[8px] text-slate-400 max-w-xs italic">
            <p><?= lang('App.legal_disclaimer_report') ?></p>
            <p><?= lang('App.verified_digital_signature') ?></p>
        </div>
        <div class="text-center">
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-16"><?= lang('App.finance_officer') ?></p>
            <div class="w-48 border-b border-slate-400 mb-2"></div>
            <p class="text-[10px] font-black text-slate-900 uppercase tracking-widest"><?= session()->get('full_name') ?></p>
        </div>
    </div>
</div>

<style>
@media print {
    @page { size: landscape; margin: 5mm 10mm; }
    body { background: white !important; font-family: 'Inter', sans-serif !important; }
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    table { width: 100% !important; border-collapse: collapse !important; table-layout: fixed; }
    th, td { border: 1px solid #475569 !important; word-wrap: break-word; }
    thead tr { background: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .bg-emerald-600 { background-color: #059669 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .bg-slate-900 { background-color: #0f172a !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .bg-slate-800 { background-color: #1e293b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>

<?= $this->endSection() ?>
