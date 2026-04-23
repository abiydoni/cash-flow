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
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-[9px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-semibold"><?= lang('App.dues_name') ?></th>
                            <th class="px-4 py-3 font-semibold text-center whitespace-nowrap">Periode</th>
                            <th class="px-4 py-3 font-semibold text-center whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 font-semibold text-right whitespace-nowrap"><?= lang('App.standard_tariff') ?></th>
                            <th class="px-3 py-3 font-semibold text-right"><?= lang('App.action') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        <?php if(empty($duesTypes)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
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
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded-lg text-[9px] font-bold uppercase tracking-widest <?= $dt['period'] === 'monthly' ? 'bg-blue-500/10 text-blue-500' : ($dt['period'] === 'yearly' ? 'bg-purple-500/10 text-purple-500' : 'bg-orange-500/10 text-orange-500') ?>">
                                        <?= $dt['period'] === 'monthly' ? 'Bulanan' : ($dt['period'] === 'yearly' ? 'Tahunan' : 'Sekali Bayar') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <label class="custom-toggle">
                                        <input type="checkbox" onchange="toggleDuesTypeStatus(<?= $dt['id'] ?>)" <?= $dt['is_active'] ? 'checked' : '' ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-500 whitespace-nowrap text-[10px]">
                                    Rp <?= number_format($dt['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="px-3 py-3 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button onclick="editDuesType(<?= esc(json_encode($dt), 'attr') ?>)" class="w-7 h-7 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                                             <ion-icon name="create-outline"></ion-icon>
                                         </button>
                                         <?php if($dt['usage_count'] > 0): ?>
                                             <button type="button" class="w-7 h-7 rounded-lg bg-slate-500/10 text-slate-400 flex items-center justify-center cursor-not-allowed opacity-50" title="Tidak dapat dihapus karena sudah ada data pembayaran">
                                                 <ion-icon name="trash-outline"></ion-icon>
                                             </button>
                                         <?php else: ?>
                                             <button onclick="deleteDuesType(<?= $dt['id'] ?>)" class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                                                 <ion-icon name="trash-outline"></ion-icon>
                                             </button>
                                         <?php endif; ?>
                                     </div>
                                 </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="block sm:hidden divide-y divide-slate-100 dark:divide-slate-700/50">
                <?php if(empty($duesTypes)): ?>
                    <div class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                        <ion-icon name="card-outline" class="text-4xl mb-2 opacity-30"></ion-icon>
                        <p><?= lang('App.no_data_duestype') ?></p>
                    </div>
                <?php else: ?>
                    <?php foreach($duesTypes as $dt): ?>
                    <div class="p-4 bg-white dark:bg-slate-800" id="dtype-mobile-<?= $dt['id'] ?>">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white text-[11px] leading-tight"><?= esc($dt['name']) ?></p>
                                <p class="text-[11px] font-bold text-emerald-500 mt-1">Rp <?= number_format($dt['amount'], 0, ',', '.') ?></p>
                            </div>
                            <div class="flex flex-col items-end gap-1.5">
                                <span class="px-2 py-1 rounded-lg text-[8px] font-bold uppercase tracking-widest <?= $dt['period'] === 'monthly' ? 'bg-blue-500/10 text-blue-500' : ($dt['period'] === 'yearly' ? 'bg-purple-500/10 text-purple-500' : 'bg-orange-500/10 text-orange-500') ?>">
                                    <?= $dt['period'] === 'monthly' ? 'Bulanan' : ($dt['period'] === 'yearly' ? 'Tahunan' : 'Sekali Bayar') ?>
                                </span>
                                <label class="custom-toggle">
                                    <input type="checkbox" onchange="toggleDuesTypeStatus(<?= $dt['id'] ?>)" <?= $dt['is_active'] ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-slate-50 dark:border-slate-700/50">
                            <button onclick="editDuesType(<?= esc(json_encode($dt), 'attr') ?>)" class="w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                                <ion-icon name="create-outline"></ion-icon>
                            </button>
                            <?php if($dt['usage_count'] > 0): ?>
                                <button type="button" class="w-8 h-8 rounded-lg bg-slate-500/10 text-slate-400 flex items-center justify-center cursor-not-allowed opacity-50" title="Tidak dapat dihapus karena sudah ada data pembayaran">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            <?php else: ?>
                                <button onclick="deleteDuesType(<?= $dt['id'] ?>)" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Periode Pembayaran</label>
                    <select id="modal-period" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none">
                        <option value="monthly">Bulanan</option>
                        <option value="yearly">Tahunan</option>
                        <option value="once">Sekali Bayar</option>
                    </select>
                </div>
                <div class="flex items-center justify-between bg-slate-100 dark:bg-slate-700/50 p-4 rounded-xl border border-slate-200 dark:border-slate-600">
                    <div>
                        <label class="block text-[12px] font-bold text-slate-800 dark:text-slate-200 mb-0.5">Status Aktif</label>
                        <p class="text-[9px] text-slate-500">Iuran aktif muncul di pembayaran</p>
                    </div>
                    <label class="custom-toggle">
                        <input type="checkbox" id="modal-is_active" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.add') ?>',
        confirmColorClass: 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20',
        onConfirm: () => {
            const data = {
                name: document.getElementById('modal-name').value,
                amount: document.getElementById('modal-amount').value,
                period: document.getElementById('modal-period').value,
                is_active: document.getElementById('modal-is_active').checked ? 1 : 0
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
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Periode Pembayaran</label>
                    <select id="modal-period" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none">
                        <option value="monthly" ${dt.period === 'monthly' ? 'selected' : ''}>Bulanan</option>
                        <option value="yearly" ${dt.period === 'yearly' ? 'selected' : ''}>Tahunan</option>
                        <option value="once" ${dt.period === 'once' ? 'selected' : ''}>Sekali Bayar</option>
                    </select>
                </div>
                <div class="flex items-center justify-between bg-slate-100 dark:bg-slate-700/50 p-4 rounded-xl border border-slate-200 dark:border-slate-600">
                    <div>
                        <label class="block text-[12px] font-bold text-slate-800 dark:text-slate-200 mb-0.5">Status Aktif</label>
                        <p class="text-[9px] text-slate-500">Iuran aktif muncul di pembayaran</p>
                    </div>
                    <label class="custom-toggle">
                        <input type="checkbox" id="modal-is_active" ${dt.is_active == 1 ? 'checked' : ''}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save_changes') ?>',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            const data = {
                id: dt.id,
                name: document.getElementById('modal-name').value,
                amount: document.getElementById('modal-amount').value,
                period: document.getElementById('modal-period').value,
                is_active: document.getElementById('modal-is_active').checked ? 1 : 0
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
    formData.append('period', data.period);
    formData.append('is_active', data.is_active);
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
        <td class="px-4 py-3 text-center">
            <span class="px-2 py-1 rounded-lg text-[9px] font-bold uppercase tracking-widest ${dt.period === 'monthly' ? 'bg-blue-500/10 text-blue-500' : (dt.period === 'yearly' ? 'bg-purple-500/10 text-purple-500' : 'bg-orange-500/10 text-orange-500')}">
                ${dt.period === 'monthly' ? 'Bulanan' : (dt.period === 'yearly' ? 'Tahunan' : 'Sekali Bayar')}
            </span>
        </td>
        <td class="px-4 py-3 text-center">
            <span class="px-2 py-1 rounded-lg text-[9px] font-bold uppercase tracking-widest ${dt.is_active == 1 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-500'}">
                ${dt.is_active == 1 ? 'Aktif' : 'Non-Aktif'}
            </span>
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

function toggleDuesTypeStatus(id) {
    showLoading();
    fetch(`<?= base_url('duestype/toggleActive/') ?>${id}`, {
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
            Toast.fire({ icon: 'success', title: data.message });
        } else {
            Toast.fire({ icon: 'error', title: data.message });
            // Sync UI back if it failed
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(err => { 
        hideLoading(); 
        Toast.fire({ icon: 'error', title: 'Terjadi kesalahan sistem' }); 
        setTimeout(() => location.reload(), 1500);
    });
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
