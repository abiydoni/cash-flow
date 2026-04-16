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
                    <th class="text-left text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3">User</th>
                    <th class="text-left text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3">Email</th>
                    <th class="text-left text-xs font-semibold text-slate-500 dark:text-slate-400 px-5 py-3">Kota</th>
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
                                <p class="text-xs text-slate-500 dark:text-slate-400">@<?= esc($u['username']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-300"><?= esc($u['email']) ?></td>
                    <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-300"><?= esc($u['city'] ?? '-') ?></td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-1 rounded-lg <?= $u['role']==='admin' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>">
                            <?= ucfirst($u['role']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span id="status-badge-<?= $u['id'] ?>" class="text-xs px-2 py-1 rounded-lg <?= $u['is_active'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' ?>">
                            <?= $u['is_active'] ? lang('App.active') : lang('App.inactive') ?>
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <?php if ($u['id'] != session('user_id')): ?>
                            <button onclick="toggleUser(<?= $u['id'] ?>, <?= $u['is_active'] ?>)"
                                class="text-xs px-3 py-1.5 rounded-lg <?= $u['is_active'] ? 'bg-orange-500/20 hover:bg-orange-500/30 text-orange-400' : 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400' ?> transition-colors font-medium"
                                id="toggle-btn-<?= $u['id'] ?>">
                                <?= $u['is_active'] ? lang('App.deactivate') : lang('App.activate') ?>
                            </button>
                            <button onclick="deleteUser(<?= $u['id'] ?>)"
                                class="text-xs px-3 py-1.5 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-red-400 transition-colors font-medium">
                                <?= lang('App.delete') ?>
                            </button>
                            <?php else: ?>
                            <span class="text-xs text-slate-400 dark:text-slate-500"><?= lang('App.your_account') ?></span>
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
                    <p class="text-xs text-slate-500 dark:text-slate-400">@<?= esc($u['username']) ?> · <?= esc($u['email']) ?></p>
                    <?php if (!empty($u['city'])): ?>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5"><ion-icon name="location-outline"></ion-icon> <?= esc($u['city']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="text-xs px-2 py-0.5 rounded-lg <?= $u['role']==='admin' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300' ?>"><?= ucfirst($u['role']) ?></span>
                        <span id="status-badge-<?= $u['id'] ?>" class="text-xs px-2 py-0.5 rounded-lg <?= $u['is_active'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' ?>"><?= $u['is_active'] ? lang('App.active') : lang('App.inactive') ?></span>
                        <?php if ($u['id'] != session('user_id')): ?>
                        <button onclick="toggleUser(<?= $u['id'] ?>, <?= $u['is_active'] ?>)" id="toggle-btn-<?= $u['id'] ?>"
                            class="text-xs px-2 py-0.5 rounded-lg <?= $u['is_active'] ? 'bg-orange-500/20 text-orange-400' : 'bg-emerald-500/20 text-emerald-400' ?> font-medium">
                            <?= $u['is_active'] ? lang('App.deactivate') : lang('App.activate') ?>
                        </button>
                        <button onclick="deleteUser(<?= $u['id'] ?>)" class="text-xs px-2 py-0.5 rounded-lg bg-red-500/20 text-red-400 font-medium"><?= lang('App.delete') ?></button>
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
function toggleUser(id, currentActive) {
    const action = currentActive ? 'nonaktifkan' : 'aktifkan';
    Swal.fire({
        title: `User?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: currentActive ? '#f97316' : '#10b981',
        cancelButtonColor: '#475569',
        confirmButtonText: `Ya!`,
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9',
    }).then(r => {
        if (!r.isConfirmed) return;
        showLoading();
        fetch(`<?= base_url('admin/users/toggle/') ?>${id}`, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                hideLoading();
                const badge = document.getElementById('status-badge-'+id);
                const btn   = document.getElementById('toggle-btn-'+id);
                if (data.active) {
                    badge.textContent = '<?= lang('App.active') ?>';
                    badge.className = 'text-xs px-2 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-400';
                    if (btn) { btn.textContent='<?= lang('App.deactivate') ?>'; btn.className = btn.className.replace('emerald','orange'); }
                } else {
                    badge.textContent = '<?= lang('App.inactive') ?>';
                    badge.className = 'text-xs px-2 py-0.5 rounded-lg bg-red-500/20 text-red-400';
                    if (btn) { btn.textContent='<?= lang('App.activate') ?>'; btn.className = btn.className.replace('orange','emerald'); }
                }
                Toast.fire({ icon: 'success', title: data.message });
            })
            .catch(err => {
                hideLoading();
                Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' });
            });
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
