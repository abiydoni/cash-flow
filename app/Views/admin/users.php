<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="people-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.manage_users') ?>
    </h2>
    <div class="flex items-center gap-3">
        <span class="hidden sm:inline text-sm text-slate-500 dark:text-slate-400"><?= count($users) ?> <?= lang('App.registered_users') ?></span>
        <button onclick="addUser()" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
            <ion-icon name="add-outline" class="text-lg"></ion-icon> <?= lang('App.add') ?> <?= lang('App.user') ?>
        </button>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden">
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3"><?= lang('App.user') ?></th>
                    <th class="text-left text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3"><?= lang('App.role') ?></th>
                    <th class="text-left text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3"><?= lang('App.status') ?></th>
                    <th class="text-right text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3"><?= lang('App.actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-slate-700/30 transition-colors" id="user-row-<?= $u['id'] ?>">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-slate-800 dark:text-white text-sm font-bold flex-shrink-0">
                                <?= strtoupper(substr($u['full_name'] ?? $u['username'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800 dark:text-white"><?= esc($u['full_name'] ?? '-') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 rounded-lg <?= $u['role']==='admin' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>">
                            <?= ucfirst($u['role']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <?php if ($u['role'] !== 'admin'): ?>
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="status-switch-<?= $u['id'] ?>" class="sr-only peer" 
                                    <?= $u['is_active'] ? 'checked' : '' ?> 
                                    onchange="toggleUser(<?= $u['id'] ?>, this)">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500 shadow-sm"></div>
                            </label>
                            <span class="text-[10px] font-bold uppercase tracking-wider <?= $u['is_active'] ? 'text-emerald-500' : 'text-slate-400' ?>" id="status-text-<?= $u['id'] ?>">
                                <?= $u['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest italic">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick='editUser(<?= json_encode($u) ?>)'
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-400 transition-colors"
                                title="<?= lang('App.edit') ?>">
                                <ion-icon name="create-outline"></ion-icon>
                            </button>
                            <button onclick="changePassword(<?= $u['id'] ?>)"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 transition-colors"
                                title="<?= lang('App.change_password') ?>">
                                <ion-icon name="key-outline"></ion-icon>
                            </button>
                            <?php if ($u['id'] != session('user_id')): ?>
                            <button onclick="deleteUser(<?= $u['id'] ?>)"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 transition-colors"
                                title="<?= lang('App.delete') ?>">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                            <?php else: ?>
                            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest"><?= lang('App.your_account') ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden divide-y divide-slate-200 dark:divide-slate-700/50">
        <?php foreach ($users as $u): ?>
        <div class="p-4" id="user-row-<?= $u['id'] ?>">
            <div class="flex items-start gap-3">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-slate-800 dark:text-white font-bold flex-shrink-0">
                    <?= strtoupper(substr($u['full_name'] ?? $u['username'], 0, 1)) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white"><?= esc($u['full_name'] ?? '-') ?></p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-[10px] px-2 py-0.5 rounded-lg <?= $u['role']==='admin' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>"><?= ucfirst($u['role']) ?></span>
                        <?php if ($u['role'] !== 'admin'): ?>
                        <div class="flex items-center gap-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="status-switch-m-<?= $u['id'] ?>" class="sr-only peer" 
                                    <?= $u['is_active'] ? 'checked' : '' ?> 
                                    onchange="toggleUser(<?= $u['id'] ?>, this)">
                                <div class="w-8 h-4 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                            </label>
                            <span class="text-[9px] font-bold uppercase tracking-tighter <?= $u['is_active'] ? 'text-emerald-500' : 'text-slate-400' ?>" id="status-text-m-<?= $u['id'] ?>">
                                <?= $u['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1.5">
                    <div class="flex items-center gap-1.5">
                        <button onclick='editUser(<?= json_encode($u) ?>)' class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-400">
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button onclick="changePassword(<?= $u['id'] ?>)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-500/20 text-amber-400">
                            <ion-icon name="key-outline"></ion-icon>
                        </button>
                        <?php if ($u['id'] != session('user_id')): ?>
                        <button onclick="deleteUser(<?= $u['id'] ?>)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/20 text-red-400">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function addUser() {
    Modal.show({
        title: '<ion-icon name="person-add-outline" class="text-emerald-500"></ion-icon> <?= lang('App.add') ?> <?= lang('App.user') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.full_name') ?></label>
                    <input id="modal-full_name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="Full Name...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.username') ?></label>
                    <input id="modal-username" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="Username...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.email') ?></label>
                    <input id="modal-email" type="email" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="Email...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.password') ?></label>
                    <input id="modal-password" type="password" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="Password...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.role') ?></label>
                    <select id="modal-role" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save') ?>',
        confirmColorClass: 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20',
        onConfirm: () => {
            const data = {
                full_name: document.getElementById('modal-full_name').value,
                username: document.getElementById('modal-username').value,
                email: document.getElementById('modal-email').value,
                password: document.getElementById('modal-password').value,
                role: document.getElementById('modal-role').value
            };
            if(!data.username || !data.password) { Toast.fire({ icon: 'error', title: 'Missing required fields' }); return; }
            submitUser(data);
        }
    });
}

function editUser(u) {
    Modal.show({
        title: '<ion-icon name="create-outline" class="text-indigo-500"></ion-icon> <?= lang('App.edit') ?> <?= lang('App.user') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.full_name') ?></label>
                    <input id="modal-full_name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${u.full_name || ''}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.username') ?></label>
                    <input id="modal-username" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${u.username}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.email') ?></label>
                    <input id="modal-email" type="email" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${u.email}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.role') ?></label>
                    <select id="modal-role" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none">
                        <option value="user" ${u.role === 'user' ? 'selected' : ''}>User</option>
                        <option value="admin" ${u.role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save_changes') ?>',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            const data = {
                id: u.id,
                full_name: document.getElementById('modal-full_name').value,
                username: document.getElementById('modal-username').value,
                email: document.getElementById('modal-email').value,
                role: document.getElementById('modal-role').value
            };
            if(!data.username) { Toast.fire({ icon: 'error', title: 'Username is required' }); return; }
            submitUser(data);
        }
    });
}

function changePassword(id) {
    Modal.show({
        title: '<ion-icon name="key-outline" class="text-amber-500"></ion-icon> <?= lang('App.change_password') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.new_password') ?></label>
                    <input id="modal-password" type="password" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all outline-none" placeholder="New Password...">
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.update_password') ?>',
        confirmColorClass: 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/20',
        onConfirm: () => {
            const password = document.getElementById('modal-password').value;
            if(!password || password.length < 6) { Toast.fire({ icon: 'error', title: 'Password must be at least 6 characters' }); return; }
            
            showLoading();
            const formData = new FormData();
            formData.append('password', password);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch(`<?= base_url('admin/users/password/') ?>${id}`, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        Modal.hide();
                        Toast.fire({ icon: 'success', title: data.message });
                    } else {
                        Toast.fire({ icon: 'error', title: data.message });
                    }
                })
                .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
        }
    });
}

function submitUser(data) {
    showLoading();
    const formData = new FormData();
    Object.keys(data).forEach(key => {
        if (data[key] !== undefined && data[key] !== null) {
            formData.append(key, data[key]);
        }
    });
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    const url = data.id ? `<?= base_url('admin/users/update/') ?>${data.id}` : `<?= base_url('admin/users/store') ?>`;

    fetch(url, { 
        method: 'POST', 
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(async r => {
            const isJson = r.headers.get('content-type')?.includes('application/json');
            const res = isJson ? await r.json() : null;
            
            if (!r.ok) throw new Error(res?.message || 'Server error: ' + r.status);
            return res;
        })
        .then(res => {
            hideLoading();
            if (res && res.status === 'success') {
                Modal.hide();
                Toast.fire({ 
                    icon: 'success', 
                    title: res.message,
                    timer: 1500 
                }).then(() => {
                    location.reload();
                });
            } else {
                Toast.fire({ icon: 'error', title: res?.message || 'Unknown error occurred' });
            }
        })
        .catch(err => { 
            hideLoading(); 
            console.error('Submission Error:', err);
            Toast.fire({ icon: 'error', title: err.message || 'Network error or server failure' }); 
        });
}

function toggleUser(id, el) {
    const isNowChecked = el.checked;
    Modal.show({
        title: '<ion-icon name="help-circle-outline" class="text-indigo-500"></ion-icon> Update Status?',
        html: '<p class="text-slate-600 dark:text-slate-400">Ubah status aktif user ini?</p>',
        confirmText: 'Ya, Ubah',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('admin/users/toggle/') ?>${id}`, { 
                method: 'POST',
                headers: { '<?= csrf_header() ?>': '<?= csrf_hash() ?>' }
            })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    Modal.hide();
                    const sw = document.getElementById('status-switch-'+id);
                    const swM = document.getElementById('status-switch-m-'+id);
                    const txt = document.getElementById('status-text-'+id);
                    const txtM = document.getElementById('status-text-m-'+id);
                    
                    const activeTxt = '<?= lang('App.active') ?>';
                    const inactiveTxt = '<?= lang('App.inactive') ?>';

                    if (sw) sw.checked = data.active;
                    if (swM) swM.checked = data.active;

                    if (data.active) {
                        if (txt) { txt.textContent = activeTxt; txt.classList.remove('text-slate-400'); txt.classList.add('text-emerald-500'); }
                        if (txtM) { txtM.textContent = activeTxt; txtM.classList.remove('text-slate-400'); txtM.classList.add('text-emerald-500'); }
                    } else {
                        if (txt) { txt.textContent = inactiveTxt; txt.classList.remove('text-emerald-500'); txt.classList.add('text-slate-400'); }
                        if (txtM) { txtM.textContent = inactiveTxt; txtM.classList.remove('text-emerald-500'); txtM.classList.add('text-slate-400'); }
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

function deleteUser(id) {
    Modal.show({
        title: '<ion-icon name="trash-outline" class="text-red-500"></ion-icon> <?= lang('App.delete_user') ?>',
        html: '<p class="text-slate-600 dark:text-slate-400">Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.</p>',
        confirmText: '<?= lang('App.delete') ?>',
        confirmColorClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            showLoading();
            fetch(`<?= base_url('admin/users/delete/') ?>${id}`, { 
                method: 'POST',
                headers: { '<?= csrf_header() ?>': '<?= csrf_hash() ?>' }
            })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        Modal.hide();
                        document.getElementById('user-row-'+id)?.remove();
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
