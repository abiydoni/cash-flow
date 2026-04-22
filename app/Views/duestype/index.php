<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="card-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.dues_type') ?>
    </h2>
    <button onclick="addDuesType()" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
        <ion-icon name="add-outline" class="text-lg"></ion-icon> <?= lang('App.add') ?> <?= lang('App.dues_type') ?>
    </button>
</div>

<div class="w-full">
    <!-- Dues Types List Table -->
    <div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-[9px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-semibold"><?= lang('App.dues_name') ?></th>
                            <th class="px-4 py-3 font-semibold text-right whitespace-nowrap"><?= lang('App.standard_tariff') ?></th>
                            <th class="px-3 py-3 font-semibold text-right"><?= lang('App.action') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        <?php if(empty($duesTypes)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <ion-icon name="card-outline" class="text-4xl mb-2 opacity-30"></ion-icon>
                                <p><?= lang('App.no_data_duestype') ?></p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($duesTypes as $dt): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group" id="dtype-<?= $dt['id'] ?>">
                                <td class="px-4 py-3">
                                    <p class="font-bold text-slate-800 dark:text-white text-[10px] leading-tight"><?= esc($dt['name']) ?></p>
                                    <p class="text-[9px] text-slate-500 opacity-60">#<?= $dt['id'] ?></p>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-500 whitespace-nowrap text-[10px]">
                                    Rp <?= number_format($dt['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="px-3 py-3 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button onclick="editDuesType(<?= esc(json_encode($dt), 'attr') ?>)" class="w-7 h-7 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                                             <ion-icon name="create-outline"></ion-icon>
                                         </button>
                                         <button onclick="deleteDuesType(<?= $dt['id'] ?>)" class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                                             <ion-icon name="trash-outline"></ion-icon>
                                         </button>
                                     </div>
                                 </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function addDuesType() {
    Modal.show({
        title: '<ion-icon name="add-circle-outline" class="text-emerald-500"></ion-icon> <?= lang('App.add') ?> <?= lang('App.dues_type') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.dues_name') ?></label>
                    <input id="modal-name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="e.g. Iuran Kebersihan">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.standard_tariff') ?> (IDR)</label>
                    <input id="modal-amount" type="number" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="0.00">
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.add') ?>',
        confirmColorClass: 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20',
        onConfirm: () => {
            const data = {
                name: document.getElementById('modal-name').value,
                amount: document.getElementById('modal-amount').value
            };
            if(!data.name) { Toast.fire({ icon: 'error', title: 'Name is required' }); return; }
            submitDuesType(data);
        }
    });
}

function editDuesType(dt) {
    Modal.show({
        title: '<ion-icon name="create-outline" class="text-indigo-500"></ion-icon> <?= lang('App.edit') ?> <?= lang('App.dues_type') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.dues_name') ?></label>
                    <input id="modal-name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${dt.name}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.standard_tariff') ?> (IDR)</label>
                    <input id="modal-amount" type="number" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${dt.amount}">
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save_changes') ?>',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            const data = {
                id: dt.id,
                name: document.getElementById('modal-name').value,
                amount: document.getElementById('modal-amount').value
            };
            if(!data.name) { Toast.fire({ icon: 'error', title: 'Name is required' }); return; }
            submitDuesType(data);
        }
    });
}

function submitDuesType(data) {
    showLoading();
    const formData = new FormData();
    if(data.id) formData.append('id', data.id);
    formData.append('name', data.name);
    formData.append('amount', data.amount);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch(`<?= base_url('duestype/store') ?>`, { 
        method: 'POST', 
        body: formData,
        headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(r => r.json())
        .then(res => {
            hideLoading();
            if (res.status === 'success') {
                Modal.hide();
                Toast.fire({ icon: 'success', title: res.message, timer: 2000 });
                updateDTypeRow(res.dtype, !data.id);
            } else {
                Toast.fire({ icon: 'error', title: res.message });
            }
        })
        .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
}

function updateDTypeRow(dt, isNew) {
    const tbody = document.querySelector('table tbody');
    const emptyState = tbody.querySelector('td[colspan]');
    if (emptyState) emptyState.parentElement.remove();

    const amountFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(dt.amount).replace('Rp', 'Rp ');

    const rowHTML = `
        <td class="px-4 py-3">
            <p class="font-bold text-slate-800 dark:text-white text-[10px] leading-tight">${dt.name}</p>
            <p class="text-[9px] text-slate-500 opacity-60">#${dt.id}</p>
        </td>
        <td class="px-4 py-3 text-right font-bold text-emerald-500 whitespace-nowrap text-[10px]">
            ${amountFormatted}
        </td>
        <td class="px-3 py-3 text-right">
            <div class="flex justify-end gap-1">
                <button onclick='editDuesType(${JSON.stringify(dt)})' class="w-7 h-7 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                        <ion-icon name="create-outline"></ion-icon>
                    </button>
                    <button onclick="deleteDuesType(${dt.id})" class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </div>
            </td>
    `;

    if (isNew) {
        const tr = document.createElement('tr');
        tr.id = `dtype-${dt.id}`;
        tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group';
        tr.innerHTML = rowHTML;
        tbody.insertBefore(tr, tbody.firstChild);
    } else {
        const tr = document.getElementById(`dtype-${dt.id}`);
        if (tr) tr.innerHTML = rowHTML;
    }
}

function deleteDuesType(id) {
    Modal.show({
        title: '<ion-icon name="trash-outline" class="text-red-500"></ion-icon> <?= lang('App.confirm_delete_duestype_title') ?>',
        html: '<p class="text-slate-600 dark:text-slate-400"><?= lang('App.confirm_delete_duestype_text') ?></p>',
        confirmText: '<?= lang('App.delete') ?>',
        confirmColorClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('duestype/delete/') ?>${id}`, { 
                method: 'POST',
                headers: { 
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        Modal.hide();
                        document.getElementById('dtype-'+id)?.remove();
                        Toast.fire({ icon: 'success', title: data.message });
                    } else {
                        Toast.fire({ icon: 'error', title: data.message });
                    }
                })
                .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
        }
    });
}
</script>
<?= $this->endSection() ?>
