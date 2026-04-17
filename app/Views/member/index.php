<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="people-circle-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.member_list') ?>
    </h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Add Member Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4" id="formTitle"><?= lang('App.add_member') ?></h3>
            <form id="memberForm" action="<?= base_url('member/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="memberId">
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.name') ?> <span class="text-red-400">*</span></label>
                    <input name="name" id="memberName" type="text" value="<?= esc(old('name')) ?>" placeholder="Contoh: Budi Santoso"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500" required>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.join_date') ?> <span class="text-red-400">*</span></label>
                    <input name="join_date" id="memberJoinDate" type="date" value="<?= esc(old('join_date', date('Y-m-d'))) ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500" required>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.status') ?></label>
                    <select name="is_active" id="memberStatus" class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                        <option value="1"><?= lang('App.active') ?></option>
                        <option value="0"><?= lang('App.non_active') ?></option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white font-semibold py-2.5 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                        <ion-icon name="save-outline"></ion-icon> <?= lang('App.save') ?>
                    </button>
                    <button type="button" onclick="resetForm()" id="btnReset" class="hidden bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 px-4 rounded-xl">
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Members List Table -->
    <div class="lg:col-span-2">
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
                                        <button onclick='editMember(<?= json_encode($m) ?>)' class="w-7 h-7 rounded-lg bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 flex items-center justify-center transition-colors" title="<?= lang('App.edit') ?>">
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
function editMember(data) {
    document.getElementById('formTitle').innerText = '<?= lang('App.edit_member') ?>';
    document.getElementById('memberId').value = data.id;
    document.getElementById('memberName').value = data.name;
    document.getElementById('memberJoinDate').value = data.join_date;
    document.getElementById('memberStatus').value = data.is_active;
    document.getElementById('btnReset').classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('formTitle').innerText = '<?= lang('App.add_member') ?>';
    document.getElementById('memberId').value = '';
    document.getElementById('memberForm').reset();
    document.getElementById('btnReset').classList.add('hidden');
}

function toggleMember(id, el) {
    const isNowChecked = el.checked;
    Swal.fire({
        title: '<?= lang('App.member') ?>?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: isNowChecked ? '#10b981' : '#f97316',
        cancelButtonColor: '#475569',
        confirmButtonText: 'Ya!',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9'
    }).then(r => {
        if (!r.isConfirmed) {
            el.checked = !isNowChecked;
            return;
        }
        showLoading();
        fetch(`<?= base_url('member/toggle/') ?>${id}`, { 
            method: 'POST',
            headers: { '<?= csrf_header() ?>': '<?= csrf_hash() ?>' }
        })
            .then(r => r.json())
            .then(data => {
                hideLoading();
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
    });
}

function deleteMember(id) {
    Swal.fire({
        title: '<?= lang('App.confirm_delete_member_title') ?>',
        text: '<?= lang('App.confirm_delete_member_text') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#475569',
        confirmButtonText: '<?= lang('App.yes_delete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        ...getSwalConfig(false)
    }).then(r => {
        if (!r.isConfirmed) return;
        showLoading();
        fetch(`<?= base_url('member/delete/') ?>${id}`, { 
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
                    document.getElementById('member-'+id)?.remove();
                    Toast.fire({ icon: 'success', title: data.message });
                } else {
                    Toast.fire({ icon: 'error', title: data.message });
                }
            })
            .catch(err => {
                hideLoading();
                Toast.fire({ icon: 'error', title: '<?= lang('App.error_occurred') ?>' });
            });
    });
}
</script>
<?= $this->endSection() ?>
