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
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
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
                                        <label class="custom-toggle">
                                            <input type="checkbox" id="status-switch-<?= $m['id'] ?>" 
                                                <?= $m['is_active'] ? 'checked' : '' ?> 
                                                onchange="toggleMember(<?= $m['id'] ?>, this)">
                                            <span class="toggle-slider"></span>
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

            <!-- Mobile Card View -->
            <div class="block sm:hidden divide-y divide-slate-100 dark:divide-slate-700/50">
                <?php if(empty($members)): ?>
                    <div class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                        <ion-icon name="people-outline" class="text-4xl mb-2 opacity-30"></ion-icon>
                        <p><?= lang('App.no_data_member') ?></p>
                    </div>
                <?php else: ?>
                    <?php foreach($members as $m): ?>
                    <div class="p-4 bg-white dark:bg-slate-800" id="member-mobile-<?= $m['id'] ?>">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                                    <ion-icon name="person" class="text-xl"></ion-icon>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 dark:text-white text-sm truncate"><?= esc($m['name']) ?></p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">ID: #<?= $m['id'] ?></p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5">
                                <label class="custom-toggle">
                                    <input type="checkbox" id="status-switch-mobile-<?= $m['id'] ?>" 
                                        <?= $m['is_active'] ? 'checked' : '' ?> 
                                        onchange="toggleMember(<?= $m['id'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="text-[8px] font-bold uppercase tracking-widest <?= $m['is_active'] ? 'text-emerald-500' : 'text-slate-400' ?>" id="status-text-mobile-<?= $m['id'] ?>">
                                    <?= $m['is_active'] ? lang('App.active') : lang('App.non_active') ?>
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-slate-50 dark:border-slate-700/50">
                            <span class="text-[9px] text-slate-500 dark:text-slate-400 italic"><?= lang('App.join_date') ?>: <?= date('d/m/Y', strtotime($m['join_date'])) ?></span>
                            <div class="flex gap-2">
                                <button onclick="editMember(<?= esc(json_encode($m), 'attr') ?>)" class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center" title="<?= lang('App.edit') ?>">
                                    <ion-icon name="create-outline"></ion-icon>
                                </button>
                                <button onclick="deleteMember(<?= $m['id'] ?>)" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center" title="<?= lang('App.delete') ?>">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            </div>
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
                <div class="flex items-center justify-between py-2">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= lang('App.status') ?></label>
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
                join_date: document.getElementById('modal-join-date').value,
                is_active: document.getElementById('modal-is_active').checked ? 1 : 0
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
                <div class="flex items-center justify-between py-2">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= lang('App.status') ?></label>
                    <label class="custom-toggle">
                        <input type="checkbox" id="modal-is_active" ${m.is_active == 1 ? 'checked' : ''}>
                        <span class="toggle-slider"></span>
                    </label>
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
                is_active: document.getElementById('modal-is_active').checked ? 1 : 0
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
            const txt = document.getElementById('status-text-'+id);
            const txtMobile = document.getElementById('status-text-mobile-'+id);
            const activeTxt = '<?= lang('App.active') ?>';
            const inactiveTxt = '<?= lang('App.non_active') ?>';

            const updateUI = (elTxt) => {
                if (!elTxt) return;
                if (data.active) {
                    elTxt.textContent = activeTxt; elTxt.classList.remove('text-slate-400'); elTxt.classList.add('text-emerald-500');
                } else {
                    elTxt.textContent = inactiveTxt; elTxt.classList.remove('text-emerald-500'); elTxt.classList.add('text-slate-400');
                }
            };

            updateUI(txt);
            updateUI(txtMobile);
            
            Toast.fire({ icon: 'success', title: data.message });
        })
        .catch(err => {
            hideLoading();
            el.checked = !isNowChecked;
            Toast.fire({ icon: 'error', title: 'Error' });
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
