<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="people-circle-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.member_list') ?>
    </h2>
    <button onclick="addMember()" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
        <ion-icon name="person-add-outline" class="text-lg"></ion-icon> <?= lang('App.add') ?> <?= lang('App.member') ?>
    </button>
</div>

<div class="w-full">
    <!-- Members List Table -->
    <div>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-[9px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3 font-semibold"><?= lang('App.name') ?></th>
                            <th class="px-3 py-3 font-semibold text-center"><?= lang('App.status') ?></th>
                            <th class="px-4 py-3 font-semibold text-right"><?= lang('App.action') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        <?php if(empty($members)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <ion-icon name="people-outline" class="text-4xl mb-2 opacity-30"></ion-icon>
                                <p><?= lang('App.no_data_member') ?></p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($members as $m): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group text-[10px]" id="member-<?= $m['id'] ?>">
                                <td class="px-4 py-3">
                                    <p class="font-bold text-slate-800 dark:text-white"><?= esc($m['name']) ?></p>
                                    <p class="text-[9px] text-slate-500 opacity-60">ID: #<?= $m['id'] ?></p>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="status-switch-<?= $m['id'] ?>" class="sr-only peer" 
                                                <?= $m['is_active'] ? 'checked' : '' ?> 
                                                onchange="toggleMember(<?= $m['id'] ?>, this)">
                                            <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                                        </label>
                                        <span class="text-[9px] font-bold uppercase tracking-wider <?= $m['is_active'] ? 'text-emerald-500' : 'text-slate-400' ?>" id="status-text-<?= $m['id'] ?>">
                                            <?= $m['is_active'] ? lang('App.active') : lang('App.non_active') ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <button onclick="editMember(<?= esc(json_encode($m), 'attr') ?>)" class="w-7 h-7 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                                            <ion-icon name="create-outline"></ion-icon>
                                        </button>
                                        <button onclick="deleteMember(<?= $m['id'] ?>)" class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
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
function addMember() {
    Modal.show({
        title: '<ion-icon name="person-add-outline" class="text-emerald-500"></ion-icon> <?= lang('App.add') ?> <?= lang('App.member') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.name') ?></label>
                    <input id="modal-name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="e.g. Ahmad Suwito">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.join_date') ?></label>
                    <input id="modal-join-date" type="date" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" value="<?= date('Y-m-d') ?>">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.status') ?></label>
                    <select id="modal-status" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none">
                        <option value="1" selected><?= lang('App.active') ?></option>
                        <option value="0"><?= lang('App.non_active') ?></option>
                    </select>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.add') ?>',
        confirmColorClass: 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20',
        onConfirm: () => {
            const data = {
                name: document.getElementById('modal-name').value,
                join_date: document.getElementById('modal-join-date').value,
                is_active: document.getElementById('modal-status').value
            };
            if(!data.name) { Toast.fire({ icon: 'error', title: 'Name is required' }); return; }
            submitMember(data);
        }
    });
}

function editMember(m) {
    Modal.show({
        title: '<ion-icon name="create-outline" class="text-indigo-500"></ion-icon> <?= lang('App.edit') ?> <?= lang('App.member') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.name') ?></label>
                    <input id="modal-name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${m.name}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.join_date') ?></label>
                    <input id="modal-join-date" type="date" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${m.join_date}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.status') ?></label>
                    <select id="modal-status" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none">
                        <option value="1" ${m.is_active == 1 ? 'selected' : ''}><?= lang('App.active') ?></option>
                        <option value="0" ${m.is_active == 0 ? 'selected' : ''}><?= lang('App.non_active') ?></option>
                    </select>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save_changes') ?>',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            const data = {
                id: m.id,
                name: document.getElementById('modal-name').value,
                join_date: document.getElementById('modal-join-date').value,
                is_active: document.getElementById('modal-status').value
            };
            if(!data.name) { Toast.fire({ icon: 'error', title: 'Name is required' }); return; }
            submitMember(data);
        }
    });
}

function submitMember(data) {
    showLoading();
    const formData = new FormData();
    if(data.id) formData.append('id', data.id);
    formData.append('name', data.name);
    formData.append('join_date', data.join_date);
    formData.append('is_active', data.is_active);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch(`<?= base_url('member/store') ?>`, { 
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
                updateMemberRow(res.member, !data.id);
            } else {
                Toast.fire({ icon: 'error', title: res.message });
            }
        })
        .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
}

function updateMemberRow(m, isNew) {
    const tbody = document.querySelector('table tbody');
    const emptyState = tbody.querySelector('td[colspan]');
    if (emptyState) emptyState.parentElement.remove();

    const activeTxt = '<?= lang('App.active') ?>';
    const inactiveTxt = '<?= lang('App.non_active') ?>';

    const rowHTML = `
        <td class="px-4 py-3">
            <p class="font-bold text-slate-800 dark:text-white">${m.name}</p>
            <p class="text-[9px] text-slate-500 opacity-60">ID: #${m.id}</p>
        </td>
        <td class="px-3 py-3">
            <div class="flex items-center justify-center gap-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="status-switch-${m.id}" class="sr-only peer" 
                        ${m.is_active == 1 ? 'checked' : ''} 
                        onchange="toggleMember(${m.id}, this)">
                    <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                </label>
                <span class="text-[9px] font-bold uppercase tracking-wider ${m.is_active == 1 ? 'text-emerald-500' : 'text-slate-400'}" id="status-text-${m.id}">
                    ${m.is_active == 1 ? activeTxt : inactiveTxt}
                </span>
            </div>
        </td>
        <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1.5">
                <button onclick='editMember(${JSON.stringify(m)})' class="w-7 h-7 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
                    <ion-icon name="create-outline"></ion-icon>
                </button>
                <button onclick="deleteMember(${m.id})" class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                    <ion-icon name="trash-outline"></ion-icon>
                </button>
            </div>
        </td>
    `;

    if (isNew) {
        const tr = document.createElement('tr');
        tr.id = `member-${m.id}`;
        tr.className = 'hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group text-[10px]';
        tr.innerHTML = rowHTML;
        tbody.insertBefore(tr, tbody.firstChild);
    } else {
        const tr = document.getElementById(`member-${m.id}`);
        if (tr) tr.innerHTML = rowHTML;
    }
}

function toggleMember(id, el) {
    const isNowChecked = el.checked;
    Modal.show({
        title: '<ion-icon name="help-circle-outline" class="text-indigo-500"></ion-icon> Update Status?',
        html: '<p class="text-slate-600 dark:text-slate-400">Ubah status aktif anggota ini?</p>',
        confirmText: 'Ya, Ubah',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('member/toggle/') ?>${id}`, { 
                method: 'POST',
                headers: { 
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    Modal.hide();
                    const txt = document.getElementById('status-text-'+id);
                    const activeTxt = '<?= lang('App.active') ?>';
                    const inactiveTxt = '<?= lang('App.non_active') ?>';

                    if (data.active) {
                        if (txt) { txt.textContent = activeTxt; txt.classList.remove('text-slate-400'); txt.classList.add('text-emerald-500'); }
                    } else {
                        if (txt) { txt.textContent = inactiveTxt; txt.classList.remove('text-emerald-500'); txt.classList.add('text-slate-400'); }
                    }
                    Toast.fire({ icon: 'success', title: data.message });
                })
                .catch(err => {
                    hideLoading();
                    el.checked = !isNowChecked;
                    Toast.fire({ icon: 'error', title: 'Error' });
                });
        }
    });
}

function deleteMember(id) {
    Modal.show({
        title: '<ion-icon name="trash-outline" class="text-red-500"></ion-icon> <?= lang('App.confirm_delete_member_title') ?>',
        html: '<p class="text-slate-600 dark:text-slate-400"><?= lang('App.confirm_delete_member_text') ?></p>',
        confirmText: '<?= lang('App.delete') ?>',
        confirmColorClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('member/delete/') ?>${id}`, { 
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
                        document.getElementById('member-'+id)?.remove();
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
