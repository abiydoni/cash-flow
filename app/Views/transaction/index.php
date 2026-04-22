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
        <button onclick="addTx('income')"
            class="flex items-center gap-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/40 text-emerald-300 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
            <ion-icon name="trending-up-outline"></ion-icon> <?= lang('App.income') ?>
        </button>
        <button onclick="addTx('expense')"
            class="flex items-center gap-1.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/40 text-red-300 text-xs font-semibold px-3 py-2 rounded-xl transition-colors">
            <ion-icon name="trending-down-outline"></ion-icon> <?= lang('App.expense') ?>
        </button>
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
        $totalIn  = $totals['total_income'] ?? 0;
        $totalOut = $totals['total_expense'] ?? 0;
        $opening  = $openingBalance ?? 0;
        $net      = $totalIn - $totalOut;
        $grandTotal = $opening + $net;
        ?>
        <div class="px-3 py-2 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-slate-500 dark:text-slate-400"><?= lang('App.total_transactions', [$totals['total_count'] ?? 0]) ?></span>
                <span class="text-emerald-400 font-semibold">+<?= rupiah($totalIn) ?></span>
                <span class="text-red-400 font-semibold">-<?= rupiah($totalOut) ?></span>
            </div>
            <div class="flex items-center justify-between sm:justify-end gap-2 pt-1.5 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-slate-700/50">
                <span class="text-[9px] uppercase tracking-widest text-slate-500 dark:text-slate-400 font-bold"><?= lang('App.balance') ?> (TOTAL)</span>
                <span class="text-sm font-black <?= $grandTotalBalance >= 0 ? 'text-emerald-400' : 'text-red-400' ?>"><?= rupiah($grandTotalBalance) ?></span>
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
                        <p class="text-xs font-bold <?= $tx['type'] === 'income' ? 'text-emerald-400' : 'text-red-400' ?> tx-amount">
                            <?= $tx['type'] === 'income' ? '+' : '-' ?><?= rupiah($tx['amount']) ?>
                        </p>
                        <span class="inline-block text-xs px-1.5 py-0.5 rounded-md <?= $tx['type']==='income' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' ?> tx-type-label">
                            <?= $types[$tx['type']]['label'] ?? $tx['type'] ?>
                        </span>
                    </div>
                    <?php if (empty($tx['dues_payment_id'])): ?>
                    <div class="flex items-center gap-1">
                        <button onclick="editTx(<?= esc(json_encode($tx), 'attr') ?>)" class="w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                            <ion-icon name="create-outline" class="text-sm"></ion-icon>
                        </button>
                        <button onclick="deleteTx(<?= $tx['id'] ?>)" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                            <ion-icon name="trash-outline" class="text-sm"></ion-icon>
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="w-8 h-8 flex items-center justify-center text-slate-400 opacity-50" title="<?= lang('App.dues_payment') ?>">
                        <ion-icon name="lock-closed-outline" class="text-sm"></ion-icon>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pager->getPageCount() > 1): ?>
        <div class="px-4 py-2 border-t border-slate-200 dark:border-slate-700">
            <?= $pager->links('default', 'App\Views\pager\tailwind') ?>
        </div>
        <?php endif; ?>
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
const incomeCategories = <?= json_encode($incomeCategories) ?>;
const expenseCategories = <?= json_encode($expenseCategories) ?>;
const typesLabel = <?= json_encode($types) ?>;

function addTx(type = 'expense') {
    renderTxModal({ type: type });
}

function editTx(tx) {
    renderTxModal(tx, true);
}

function renderTxModal(tx, isEdit = false) {
    const cats = tx.type === 'income' ? incomeCategories : expenseCategories;
    const activeColor = tx.type === 'income' ? '#10b981' : '#ef4444';
    
    let catsHtml = '';
    cats.forEach(c => {
        catsHtml += `
            <label class="cursor-pointer">
                <input type="radio" name="category_id" value="${c.id}" class="sr-only" ${tx.category_id == c.id ? 'checked' : ''} required>
                <div class="cat-card-m flex flex-col items-center gap-1 p-2 rounded-xl border-2 border-transparent bg-slate-100 dark:bg-slate-900/50 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-center" style="--c-color:${c.color}">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color:${c.color}20">
                        <ion-icon name="${c.icon}" style="color:${c.color}"></ion-icon>
                    </div>
                    <span class="text-[9px] font-bold text-slate-600 dark:text-slate-400 leading-tight truncate w-full px-0.5">${c.name}</span>
                </div>
            </label>
        `;
    });

    Modal.show({
        title: isEdit ? '<?= lang('App.edit_transaction') ?>' : '<?= lang('App.add_transaction') ?>',
        html: `
            <div class="space-y-5 text-left">
                <input type="hidden" id="modal-type" value="${tx.type}">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2"><?= lang('App.category') ?></label>
                    <div class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto p-1 custom-scrollbar">
                        ${catsHtml}
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2"><?= lang('App.amount') ?></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                            <input id="modal-amount" type="number" value="${tx.amount || ''}" class="w-full h-11 pl-10 pr-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 outline-none font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2"><?= lang('App.date') ?></label>
                        <input id="modal-date" type="date" value="${tx.transaction_date || '<?= date('Y-m-d') ?>'}" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2"><?= lang('App.description') ?></label>
                    <input id="modal-desc" type="text" value="${tx.description || ''}" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm outline-none" placeholder="Description...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2"><?= lang('App.payment_method') ?></label>
                    <select id="modal-method" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm outline-none">
                        <option value="cash" ${tx.payment_method === 'cash' ? 'selected' : ''}>Cash</option>
                        <option value="bank_transfer" ${tx.payment_method === 'bank_transfer' ? 'selected' : ''}>Bank Transfer</option>
                        <option value="e_wallet" ${tx.payment_method === 'e_wallet' ? 'selected' : ''}>E-Wallet</option>
                    </select>
                </div>
            </div>
            <style>
                .cat-card-m input:checked + div { border-color: var(--c-color); background-color: color-mix(in srgb, var(--c-color) 20%, transparent); }
            </style>
        `,
        confirmText: isEdit ? '<?= lang('App.save_changes') ?>' : '<?= lang('App.save') ?>',
        confirmColorClass: tx.type === 'income' ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20' : 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            const data = {
                id: tx.id,
                type: document.getElementById('modal-type').value,
                category_id: document.querySelector('input[name="category_id"]:checked')?.value,
                amount: document.getElementById('modal-amount').value,
                transaction_date: document.getElementById('modal-date').value,
                description: document.getElementById('modal-desc').value,
                payment_method: document.getElementById('modal-method').value
            };
            if(!data.category_id || !data.amount) { Toast.fire({ icon: 'error', title: 'Category and Amount are required' }); return; }
            submitTx(data);
        }
    });
}

function submitTx(data) {
    showLoading();
    const formData = new FormData();
    Object.keys(data).forEach(k => { if(data[k]) formData.append(k, data[k]); });
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    const url = data.id ? `<?= base_url('transaction/update/') ?>${data.id}` : `<?= base_url('transaction/store') ?>`;
    
    fetch(url, { 
        method: 'POST', 
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        hideLoading();
        if (res.status === 'success') {
            Modal.hide();
            Toast.fire({ icon: 'success', title: res.message });
            if (data.id) {
                updateTxRow(res.transaction);
            } else {
                location.reload(); // Complex to update summary/pagination, easier to reload for new ones
            }
        } else {
            Toast.fire({ icon: 'error', title: res.message });
        }
    })
    .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error connecting to server' }); });
}

function updateTxRow(tx) {
    const row = document.getElementById(`tx-${tx.id}`);
    if (!row) return;

    const icon = row.querySelector('ion-icon');
    const iconContainer = icon.parentElement;
    const descEl = row.querySelector('p.truncate');
    const catEl = row.querySelector('span.text-slate-500');
    const dateEl = row.querySelectorAll('span.text-slate-500')[1];
    const amountEl = row.querySelector('.tx-amount');
    const labelEl = row.querySelector('.tx-type-label');

    icon.setAttribute('name', tx.category_icon || 'wallet-outline');
    icon.style.color = tx.category_color;
    iconContainer.style.backgroundColor = tx.category_color + '15';
    
    descEl.textContent = tx.description || tx.category_name || 'Transaksi';
    catEl.textContent = tx.category_name || '-';
    dateEl.textContent = new Date(tx.transaction_date).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'});
    
    amountEl.textContent = (tx.type === 'income' ? '+' : '-') + 'Rp ' + parseInt(tx.amount).toLocaleString('id-ID');
    amountEl.className = `text-xs font-bold ${tx.type === 'income' ? 'text-emerald-400' : 'text-red-400'} tx-amount`;
}

function deleteTx(id) {
    Modal.show({
        title: '<ion-icon name="trash-outline" class="text-red-500"></ion-icon> <?= lang('App.confirm_delete_transaction_title') ?>',
        html: '<p class="text-slate-600 dark:text-slate-400"><?= lang('App.confirm_delete_transaction_text') ?></p>',
        confirmText: '<?= lang('App.delete') ?>',
        confirmColorClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('transaction/delete/') ?>` + id, { 
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
                        document.getElementById('tx-' + id)?.remove();
                        Toast.fire({ icon: 'success', title: data.message });
                    } else {
                        Toast.fire({ icon: 'error', title: data.message });
                    }
                })
                .catch(err => {
                    hideLoading();
                    Toast.fire({ icon: 'error', title: 'Error' });
                });
        }
    });
}
</script>

<?= $this->endSection() ?>
