<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Interactive Header (no-print) -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
    <div class="flex items-center gap-3">
        <a href="<?= $selectedTypeId ? base_url('dues/type/' . $selectedTypeId) : base_url('dues') ?>" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-emerald-500 hover:border-emerald-500/50 transition-all">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <?= esc($member['name']) ?>
                <?php if($selectedType): ?>
                    <span class="text-[10px] bg-emerald-500/10 text-emerald-500 px-2 py-0.5 rounded-lg border border-emerald-500/20 uppercase tracking-widest font-bold">
                        <?php 
                            $slug = preg_replace('/[^a-z0-9]/', '_', strtolower(trim($selectedType['name'])));
                            $trans = lang('App.' . $slug);
                            echo ($trans === 'App.' . $slug) ? esc($selectedType['name']) : $trans;
                        ?>
                    </span>
                <?php endif; ?>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                <ion-icon name="calendar-outline"></ion-icon> <?= lang('App.year') ?>: 
                <span class="font-bold text-emerald-400"><?= $year ?></span>
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-2 bg-white dark:bg-slate-800 p-1.5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm no-print">
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

<!-- Screen Content (no-print) -->
<div class="bg-white dark:bg-slate-800 rounded-[2rem] overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700 no-print">
    <div class="p-8 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white uppercase tracking-wider flex items-center gap-2">
            <ion-icon name="list-outline" class="text-lg text-emerald-500"></ion-icon>
            <?= lang('App.monthly_dues_matrix') ?>
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <?php 
                    $totalTariff = 0;
                    foreach($duesTypes as $dt) $totalTariff += $dt['amount'];
                ?>
                <tr class="bg-slate-900 text-white">
                    <th class="px-1 py-4 text-[9px] font-semibold uppercase tracking-tighter border-b-2 border-slate-700 w-6 text-center">No</th>
                    <th class="px-3 py-4 text-[10px] font-semibold uppercase tracking-wider border-b-2 border-slate-700"><?= lang('App.month') ?></th>
                    <th class="px-3 py-4 text-[10px] font-semibold uppercase tracking-wider border-b-2 border-slate-700 text-center"><?= lang('App.total_tariff') ?></th>
                    <?php foreach($duesTypes as $dt): ?>
                        <th class="px-3 py-4 text-[10px] font-semibold uppercase tracking-wider border-b-2 border-slate-700 text-center">
                            <?php 
                                $slug = preg_replace('/[^a-z0-9]/', '_', strtolower(trim($dt['name'])));
                                $trans = lang('App.' . $slug);
                                echo ($trans === 'App.' . $slug) ? esc($dt['name']) : $trans;
                            ?>
                        </th>
                        <th class="px-1.5 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-700 text-center w-16">
                            <?= lang('App.actions') ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                <?php 
                    $joinDate  = strtotime($member['join_date']);
                    $joinYear  = (int)date('Y', $joinDate);
                    $joinMonth = (int)date('n', $joinDate);
                    $screenPaidTotal = 0;
                ?>
                <?php foreach($months as $mIdx => $mName): ?>
                    <?php 
                        $isDisabled   = ($year < $joinYear) || ($year == $joinYear && $mIdx < $joinMonth); 
                        $paymentCount = 0;
                        if (isset($paymentGrid[$mIdx])) {
                            foreach($paymentGrid[$mIdx] as $pg) $paymentCount += count($pg['records']);
                        }
                    ?>
                    <tr class="<?= $isDisabled ? 'opacity-40 grayscale' : 'hover:bg-emerald-50/30 dark:hover:bg-emerald-500/10 cursor-pointer' ?> transition-colors"
                        <?= $isDisabled ? '' : "onclick=\"openMonthDetailModal($mIdx, '$mName')\"" ?>>
                        <td class="px-1 py-3 text-center font-bold text-slate-400 text-[9px] border-r border-slate-100 dark:border-slate-700/30"><?= $mIdx ?></td>
                        <td class="px-3 py-3 font-semibold text-slate-800 dark:text-slate-200 uppercase text-[10px]">
                            <?= $mName ?>
                        </td>
                        <td class="px-3 py-3 text-center font-bold text-slate-500 dark:text-slate-400 text-[10px]">
                            <span class="text-[8px] mr-0.5 opacity-30 font-normal">Rp</span><?= number_format($totalTariff, 0, ',', '.') ?>
                        </td>
                        <?php foreach($duesTypes as $dt): ?>
                            <?php 
                                $pg = $paymentGrid[$mIdx][$dt['id']] ?? null; 
                                $isLunas = $pg && $pg['total_paid'] >= $dt['amount'];
                                if($pg) $screenPaidTotal += $pg['total_paid'];
                            ?>
                            <!-- Status & Nominal Combined -->
                            <td class="px-3 py-3 text-center text-[10px]">
                                <?php if($pg): ?>
                                    <div class="<?= $isLunas ? 'bg-emerald-500/10 text-emerald-500' : 'bg-orange-500/10 text-orange-500' ?> rounded-lg px-2 py-1.5 text-[10px] font-bold inline-block leading-tight border <?= $isLunas ? 'border-emerald-500/20' : 'border-orange-500/20' ?>">
                                        <span class="text-[8px] mr-px opacity-30 font-normal">Rp</span><?= number_format($pg['total_paid'], 0, ',', '.') ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Unified Actions Column -->
                            <td class="px-1.5 py-3 text-center border-l border-slate-100 dark:border-slate-700/30">
                                <div class="flex items-center justify-center gap-0.5">
                                    <?php if (!$isLunas): ?>
                                        <?php if ($isDisabled && !$pg): ?>
                                            <span class="text-[10px] text-slate-400"><ion-icon name="lock-closed-outline"></ion-icon></span>
                                        <?php else: ?>
                                            <button onclick="event.stopPropagation(); openPayModal(<?= $mIdx ?>, '<?= $mName ?>', <?= $dt['id'] ?>, '<?= esc($dt['name']) ?>', <?= $dt['amount'] - ($pg['total_paid'] ?? 0) ?>)" 
                                                class="w-7 h-7 flex items-center justify-center text-emerald-500 hover:text-emerald-600 transition-all active:scale-95" title="<?= $pg ? lang('App.pay_sisa') : lang('App.pay') ?>">
                                                <ion-icon name="card-outline" class="text-lg"></ion-icon>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if($pg): ?>
                                        <?php if ($paymentCount > 1): ?>
                                            <button onclick="event.stopPropagation(); openMonthDetailModal(<?= $mIdx ?>, '<?= $mName ?>')" class="w-7 h-7 flex items-center justify-center text-orange-400 hover:text-orange-500 transition-all active:scale-95" title="<?= lang('App.multiple_payments_hint') ?>">
                                                <ion-icon name="list-outline" class="text-lg"></ion-icon>
                                            </button>
                                        <?php else: ?>
                                            <button onclick="event.stopPropagation(); deletePayment(<?= $pg['records'][0]['id'] ?>)" class="w-7 h-7 flex items-center justify-center text-red-400 hover:text-red-500 transition-all active:scale-95" title="<?= lang('App.confirm_delete_payment_title') ?>">
                                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700">
                <tr>
                    <td colspan="3" class="px-6 py-6 font-semibold uppercase tracking-wider text-xs text-slate-500 text-right"><?= lang('App.total_annual_payment') ?></td>
                    <td colspan="<?= count($duesTypes) * 2 ?>" class="px-6 py-6 text-right text-xl font-bold text-emerald-500">
                        <span class="text-xs mr-2 opacity-50 font-bold">Rp</span><?= number_format($screenPaidTotal, 0, ',', '.') ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Professional Print Report (hidden print:block) -->
<div class="hidden print:block bg-white rounded-0 border-slate-400 shadow-none">
    <!-- Professional Print Header -->
    <div class="p-8 pb-0">
        <div class="flex justify-between items-end border-b-4 border-slate-900 pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center text-white text-xl shadow-md">
                    <ion-icon name="wallet-outline"></ion-icon>
                </div>
                <div>
                    <h1 class="text-2xl font-bold uppercase tracking-tighter text-slate-900 leading-none">CashFlow</h1>
                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-wider mt-1"><?= lang('App.financial_mgmt') ?></p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-black text-slate-900 uppercase italic leading-tight"><?= lang('App.individual_dues_report') ?></h2>
                <p class="text-xs font-bold text-emerald-500 uppercase"><?= lang('App.fiscal_year') ?> <?= $year ?></p>
                <p class="text-[8px] text-slate-400 mt-1"><?= lang('App.generate_date') ?>: <?= date('d/m/Y H:i') ?></p>
            </div>
        </div>

        <!-- Member Profile Block -->
        <div class="grid grid-cols-2 gap-4 border-b border-slate-200 pb-8 mb-8 mt-4">
            <div class="space-y-2">
                <table class="w-full text-[10px]">
                    <tr>
                        <td class="w-24 font-bold text-slate-400 uppercase tracking-widest py-1"><?= lang('App.member_name') ?></td>
                        <td class="w-4 text-slate-300 py-1">:</td>
                        <td class="font-bold text-slate-900 uppercase py-1"><?= esc($member['name']) ?></td>
                    </tr>
                    <tr>
                        <td class="w-24 font-bold text-slate-400 uppercase tracking-widest py-1"><?= lang('App.member_id') ?></td>
                        <td class="w-4 text-slate-300 py-1">:</td>
                        <td class="font-bold text-slate-700 py-1">#<?= str_pad($member['id'], 5, '0', STR_PAD_LEFT) ?></td>
                    </tr>
                </table>
            </div>
            <div class="space-y-2 border-l border-slate-100 pl-8">
                <table class="w-full text-[10px]">
                    <tr>
                        <td class="w-24 font-bold text-slate-400 uppercase tracking-widest py-1"><?= lang('App.fiscal_year') ?></td>
                        <td class="w-4 text-slate-300 py-1">:</td>
                        <td class="font-bold text-emerald-600 py-1"><?= $year ?></td>
                    </tr>
                    <tr>
                        <td class="w-24 font-bold text-slate-400 uppercase tracking-widest py-1"><?= lang('App.generate_date') ?></td>
                        <td class="w-4 text-slate-300 py-1">:</td>
                        <td class="font-bold text-slate-700 py-1"><?= date('d F Y, H:i') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Print Executive Summary -->
        <div class="grid grid-cols-3 gap-4 mb-4 pb-8">
            <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl">
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mb-1"><?= lang('App.total_tariff') ?></p>
                <h3 class="text-sm font-bold text-slate-900">Rp <?= number_format($totalTariff * 12, 0, ',', '.') ?></h3>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl">
                <p class="text-[8px] font-bold text-emerald-600 uppercase tracking-wider mb-1"><?= lang('App.total_paid') ?></p>
                <h3 class="text-sm font-bold text-emerald-600">Rp <?= number_format($screenPaidTotal, 0, ',', '.') ?></h3>
            </div>
            <div class="bg-red-50 border border-red-100 p-4 rounded-xl">
                <p class="text-[8px] font-bold text-red-600 uppercase tracking-wider mb-1"><?= lang('App.total_remaining') ?></p>
                <h3 class="text-sm font-bold text-red-600">Rp <?= number_format(($totalTariff * 12) - $screenPaidTotal, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto p-0">
        <table class="w-full text-left border-collapse table-fixed">
            <thead>
                <?php 
                    $totalTariff = 0;
                    foreach($duesTypes as $dt) $totalTariff += $dt['amount'];
                ?>
                <tr class="bg-black text-white">
                    <th class="px-2 py-5 text-[10px] font-bold uppercase tracking-wider border-r border-slate-800 w-10 text-center">No</th>
                    <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-wider border-r border-slate-800 w-32"><?= lang('App.month_period') ?></th>
                    <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-wider border-r border-slate-800 text-center w-28"><?= lang('App.tariff') ?></th>
                    <?php foreach($duesTypes as $dt): ?>
                        <th class="px-4 py-5 text-[10px] font-bold uppercase tracking-wider border-r border-slate-800 text-center">
                            <?php 
                                $slug = preg_replace('/[^a-z0-9]/', '_', strtolower(trim($dt['name'])));
                                $trans = lang('App.' . $slug);
                                echo ($trans === 'App.' . $slug) ? esc($dt['name']) : $trans;
                            ?>
                        </th>
                        <th class="px-4 py-5 text-[10px] font-bold uppercase tracking-wider border-r border-slate-800 text-center">
                            <?= lang('App.remaining') ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php 
                    $yearlyPaidTotal = 0;
                    $yearlyRemainingTotal = 0;
                ?>
                <?php foreach($months as $mIdx => $mName): ?>
                    <?php 
                        $isDisabled   = ($year < $joinYear) || ($year == $joinYear && $mIdx < $joinMonth); 
                    ?>
                    <tr>
                        <td class="px-2 py-4 text-center border-r border-slate-300 text-[10px] items-center justify-center font-bold text-slate-400">
                            <?= $mIdx ?>
                        </td>
                        <td class="px-8 py-4 font-bold text-slate-800 border-r border-slate-300 text-[10px] bg-slate-50 uppercase">
                            <?= $mName ?>
                        </td>
                        <td class="px-8 py-4 text-center font-bold text-slate-500 text-xs border-r border-slate-300">
                            <span class="text-[8px] mr-1 opacity-30 font-normal">Rp</span><?= number_format($totalTariff, 0, ',', '.') ?>
                        </td>
                        <?php foreach($duesTypes as $dt): ?>
                            <?php 
                                $pg = $paymentGrid[$mIdx][$dt['id']] ?? null; 
                                $isLunas = $pg && $pg['total_paid'] >= $dt['amount'];
                                $remaining = $isDisabled ? 0 : ($dt['amount'] - ($pg['total_paid'] ?? 0));
                                if($pg) $yearlyPaidTotal += $pg['total_paid'];
                                if(!$isDisabled) $yearlyRemainingTotal += $remaining;
                            ?>
                            <!-- Kolom Status -->
                            <td class="px-6 py-4 text-center border-r border-slate-300">
                                <?php if($pg): ?>
                                    <div class="text-slate-900 text-[10px] font-bold">
                                        <span class="text-[8px] mr-0.5 opacity-30 font-normal">Rp</span><?= number_format($pg['total_paid'], 0, ',', '.') ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[9px] text-slate-400 opacity-10">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Sisa -->
                            <td class="px-6 py-4 text-center border-r border-slate-300">
                                <?php if($remaining > 0): ?>
                                    <span class="text-[10px] font-bold text-red-500">
                                        <span class="text-[8px] mr-0.5 opacity-30 font-normal">Rp</span><?= number_format($remaining, 0, ',', '.') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-[9px] text-emerald-600 font-bold uppercase tracking-tighter italic"><?= lang('App.lunas') ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="border-t-4 border-slate-900">
                <tr class="bg-slate-100 text-slate-900">
                    <td colspan="2" class="px-8 py-4 font-bold uppercase text-[10px] tracking-wider border-r border-slate-200"><?= lang('App.total_annual_payment') ?> (<?= lang('App.paid') ?>)</td>
                    <td colspan="<?= count($duesTypes) * 2 ?>" class="px-8 py-4 text-right text-lg font-bold text-emerald-600">
                        <span class="text-xs mr-2 opacity-30 font-bold italic">Rp</span><?= number_format($yearlyPaidTotal, 0, ',', '.') ?>
                    </td>
                </tr>
                <tr class="bg-white text-slate-900 border-t border-slate-100">
                    <td colspan="2" class="px-8 py-4 font-bold uppercase text-[10px] tracking-wider border-r border-slate-200"><?= lang('App.remaining') ?> (<?= lang('App.unpaid') ?>)</td>
                    <td colspan="<?= count($duesTypes) * 2 ?>" class="px-8 py-4 text-right text-lg font-bold text-red-500">
                        <span class="text-xs mr-2 opacity-30 font-bold italic">Rp</span><?= number_format($yearlyRemainingTotal, 0, ',', '.') ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Print Footer -->
    <div class="flex justify-between items-end pt-12 mt-12 border-t border-slate-200 p-8">
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

function openMonthDetailModal(monthIdx, monthName) {
    let contentHtml = `<div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">`;
    
    duesTypes.forEach(dt => {
        const pg = paymentGrid[monthIdx] ? paymentGrid[monthIdx][dt.id] : null;
        const totalPaid = pg ? pg.total_paid : 0;
        const isLunas = totalPaid >= dt.amount;
        
        let statusBadge = pg 
            ? (isLunas ? `<span class="bg-emerald-500 text-slate-800 dark:text-white text-[10px] font-semibold px-2 py-0.5 rounded-full shadow-lg shadow-emerald-500/20"><?= lang('App.lunas') ?></span>` 
                       : `<span class="bg-orange-500 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full shadow-lg shadow-orange-500/20"><?= lang('App.belum_lunas') ?></span>`)
            : `<span class="bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-semibold px-2 py-0.5 rounded-full"><?= lang('App.belum_bayar') ?></span>`;

        contentHtml += `
            <div class="flex flex-col gap-2 p-4 rounded-2xl border ${pg ? 'bg-slate-100 dark:bg-slate-900/10 border-slate-200 dark:border-slate-700' : 'bg-slate-50 dark:bg-slate-900/30 border-slate-100 dark:border-slate-700/50'}">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                            ${duesTypeMap[dt.name] || dt.name}
                        </span>
                        <span class="text-[10px] text-slate-500">${txtTariff}: Rp ${parseInt(dt.amount).toLocaleString(locale)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold ${isLunas ? 'text-emerald-400' : 'text-orange-400'}">Rp ${parseInt(totalPaid).toLocaleString(locale)}</span>
                        ${statusBadge}
                    </div>
                </div>
                ${pg ? `<div class="mt-2 pt-2 border-t border-slate-200 dark:border-slate-700 space-y-2">
                    ${pg.records.map(r => `
                        <div class="flex items-center justify-between bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300">Rp ${parseInt(r.amount_paid).toLocaleString(locale)}</span>
                                <span class="text-[9px] text-slate-400">${new Date(r.payment_date).toLocaleDateString(locale, {day:'2-digit', month:'short', year:'numeric'})}</span>
                            </div>
                            <button onclick="deletePayment(${r.id})" class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-all" title="<?= lang('App.delete') ?>"><ion-icon name="trash-outline"></ion-icon></button>
                        </div>
                    `).join('')}
                </div>` : ''}
            </div>
        `;
    });
    contentHtml += `</div>`;

    Modal.show({
        title: `<ion-icon name="list-outline" class="text-indigo-500"></ion-icon> ${monthName} <?= $year ?>`,
        html: contentHtml,
        confirmText: '<?= lang('App.close') ?>',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => Modal.hide()
    });
}

function openPayModal(month, monthName, duesTypeId, duesTypeName, defaultAmount) {
    Modal.show({
        title: '<ion-icon name="card-outline" class="text-emerald-500"></ion-icon> <?= lang('App.dues_payment') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div class="bg-slate-100 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-500"><?= lang('App.dues') ?></span>
                        <span class="font-bold text-emerald-500">${duesTypeMap[duesTypeName] || duesTypeName}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500"><?= lang('App.month') ?></span>
                        <span class="font-bold text-emerald-500">${monthName}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.paid_amount') ?></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                        <input id="modal-amount" type="number" step="0.01" value="${defaultAmount}" class="w-full h-11 pl-10 pr-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.payment_date') ?></label>
                    <input id="modal-date" type="date" value="<?= date('Y-m-d') ?>" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none">
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.pay') ?>',
        confirmColorClass: 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20',
        onConfirm: () => {
            const formData = new FormData();
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            formData.append('member_id', '<?= $member['id'] ?>');
            formData.append('dues_type_id', duesTypeId);
            formData.append('month', month);
            formData.append('year', '<?= $year ?>');
            formData.append('amount', document.getElementById('modal-amount').value);
            formData.append('payment_date', document.getElementById('modal-date').value);

            showLoading();
            fetch('<?= base_url('dues/pay') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                hideLoading();
                if (data.status === 'success') {
                    Modal.hide();
                    location.reload();
                } else {
                    Toast.fire({ icon: 'error', title: data.message });
                }
            })
            .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
        }
    });
}

function deletePayment(id) {
    Modal.show({
        title: '<ion-icon name="trash-outline" class="text-red-500"></ion-icon> <?= lang('App.confirm_delete_payment_title') ?>',
        html: '<p class="text-slate-600 dark:text-slate-400"><?= lang('App.confirm_delete_payment_text') ?></p>',
        confirmText: '<?= lang('App.delete') ?>',
        confirmColorClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('dues/delete/') ?>${id}`, { 
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                }
            })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        Modal.hide();
                        location.reload();
                    } else {
                        Toast.fire({ icon: 'error', title: data.message });
                    }
                })
                .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
        }
    });
}
</script>

<style>
@media print {
    @page { size: portrait; margin: 10mm; }
    body { background: white !important; font-family: 'Inter', sans-serif !important; }
    .print-only { display: block !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
    .no-print { display: none !important; }
    table { width: 100% !important; table-layout: fixed; border-collapse: collapse !important; border: 1px solid #ddd !important; }
    thead tr { background: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    th { font-size: 7pt !important; padding: 4px 6px !important; border: 1px solid #333 !important; font-weight: 900 !important; text-transform: uppercase !important; }
    td { border: 1px solid #ddd !important; color: #1e293b !important; padding: 3px 6px !important; font-size: 7pt !important; }
    .rounded-\[2rem\], .rounded-3xl, .rounded-2xl, .rounded-xl, .rounded-lg { border-radius: 0 !important; }
    .grid > div, .bg-white, table { page-break-inside: avoid !important; }
    .border-2 { border-width: 1px !important; }
    
    /* Force smaller fonts for currency symbols */
    .font-mono { font-family: 'Courier New', monospace !important; font-size: 7pt !important; }
    .text-xl { font-size: 10pt !important; }
    .text-lg { font-size: 9pt !important; }
    .text-sm { font-size: 7pt !important; }
    .text-xs { font-size: 6.5pt !important; }

    /* Fix footer/signatures scale */
    .h-16 { height: 2.5rem !important; }
    .w-48 { width: 10rem !important; }
}
</style>
<?= $this->endSection() ?>
