<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="people-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.manage_users') ?>
    </h2>
    <span class="text-sm text-slate-500 dark:text-slate-400"><?= count($users) ?> <?= lang('App.registered_users') ?></span>
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
function toggleUser(id, el) {
    const isNowChecked = el.checked;
    Swal.fire({
        title: `User?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isNowChecked ? '#10b981' : '#f97316',
        cancelButtonColor: '#475569',
        confirmButtonText: `Ya!`,
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9',
    }).then(r => {
        if (!r.isConfirmed) {
            el.checked = !isNowChecked; // Revert
            return;
        }
        showLoading();
        fetch(`<?= base_url('admin/users/toggle/') ?>${id}`, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                hideLoading();
                const sw = document.getElementById('status-switch-'+id);
                const swM = document.getElementById('status-switch-m-'+id);
                const txt = document.getElementById('status-text-'+id);
                const txtM = document.getElementById('status-text-m-'+id);
                
                const activeTxt = '<?= lang('App.active') ?>';
                const inactiveTxt = '<?= lang('App.inactive') ?>';

                // Sync both switches
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
                Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' });
            });
    });
}

function editUser(user) {
    Swal.fire({
        title: '<?= lang('App.edit_user') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Full Name</label>
                    <input id="swal-full-name" class="w-full h-11 px-4 rounded-xl bg-slate-700 border-none text-white text-sm focus:ring-2 focus:ring-emerald-500 transition-all" value="${user.full_name || ''}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Username</label>
                    <input id="swal-username" class="w-full h-11 px-4 rounded-xl bg-slate-700 border-none text-white text-sm focus:ring-2 focus:ring-emerald-500 transition-all" value="${user.username}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</label>
                    <input id="swal-email" type="email" class="w-full h-11 px-4 rounded-xl bg-slate-700 border-none text-white text-sm focus:ring-2 focus:ring-emerald-500 transition-all" value="${user.email}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Role</label>
                    <select id="swal-role" class="w-full h-11 px-4 rounded-xl bg-slate-700 border-none text-white text-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<?= lang('App.save_changes') ?>',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#475569',
        background: '#1e293b', color: '#f1f5f9',
        preConfirm: () => {
            return {
                full_name: document.getElementById('swal-full-name').value,
                username: document.getElementById('swal-username').value,
                email: document.getElementById('swal-email').value,
                role: document.getElementById('swal-role').value
            }
        }
    }).then(result => {
        if (result.isConfirmed) {
            showLoading();
            const formData = new FormData();
            formData.append('full_name', result.value.full_name);
            formData.append('username', result.value.username);
            formData.append('email', result.value.email);
            formData.append('role', result.value.role);

            fetch(`<?= base_url('admin/users/update/') ?>${user.id}`, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        Toast.fire({ icon: 'success', title: data.message }).then(() => location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', html: data.message, background: '#1e293b', color: '#f1f5f9' });
                    }
                })
                .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' }); });
        }
    });
}

function changePassword(id) {
    Swal.fire({
        title: '<?= lang('App.change_password') ?>',
        html: `
            <div class="text-left">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">New Password</label>
                <input id="swal-password" type="password" class="w-full h-11 px-4 rounded-xl bg-slate-700 border-none text-white text-sm focus:ring-2 focus:ring-emerald-500 transition-all" placeholder="Min 6 chars">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<?= lang('App.update_password') ?>',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#475569',
        background: '#1e293b', color: '#f1f5f9',
        preConfirm: () => { return document.getElementById('swal-password').value; }
    }).then(result => {
        if (result.isConfirmed) {
            if (!result.value || result.value.length < 6) {
                Toast.fire({ icon: 'error', title: 'Password min 6 chars!' });
                return;
            }
            showLoading();
            const formData = new FormData();
            formData.append('password', result.value);

            fetch(`<?= base_url('admin/users/password/') ?>${id}`, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.status === 'success') {
                        Toast.fire({ icon: 'success', title: data.message });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', html: data.message, background: '#1e293b', color: '#f1f5f9' });
                    }
                })
                .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' }); });
        }
    });
}

function deleteUser(id) {
    Swal.fire({
        title: '<?= lang('App.delete_user_confirm') ?>',
        text: '<?= lang('App.delete_user_desc') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#475569',
        confirmButtonText: '<?= lang('App.delete_yes') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9',
    }).then(r => {
        if (!r.isConfirmed) return;
        showLoading();
        fetch(`<?= base_url('admin/users/delete/') ?>${id}`, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                hideLoading();
                document.getElementById('user-row-'+id)?.remove();
                Toast.fire({ icon: 'success', title: data.message });
            })
            .catch(err => {
                hideLoading();
                Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' });
            });
    });
}
</script>
<?= $this->endSection() ?>
