<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white leading-tight"><?= lang('App.manage_personal_categories') ?></h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= lang('App.manage_categories_desc') ?></p>
    </div>
    <button onclick="addCat()" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2">
        <ion-icon name="add-outline" class="text-lg"></ion-icon> <?= lang('App.new_category') ?>
    </button>
</div>

<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-3 sm:px-6 py-4 font-semibold"><?= lang('App.category_name') ?></th>
                    <th class="px-3 sm:px-6 py-4 font-semibold"><?= lang('App.type') ?></th>
                    <th class="px-3 sm:px-6 py-4 font-semibold text-right"><?= lang('App.actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                <?php if(empty($categories)): ?>
                <tr>
                    <td colspan="3" class="px-3 sm:px-6 py-8 text-center text-slate-400 dark:text-slate-500">
                        <ion-icon name="pricetags-outline" class="text-4xl mb-2 opacity-50"></ion-icon>
                        <p><?= lang('App.category_no_data') ?></p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($categories as $cat): ?>
                    <tr class="hover:bg-slate-700/20 transition-colors group">
                        <td class="px-3 sm:px-6 py-4">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-slate-800 dark:text-white shadow-lg shrink-0" style="background-color: <?= esc($cat['color']) ?>">
                                    <ion-icon name="<?= esc($cat['icon']) ?>" class="text-lg sm:text-xl"></ion-icon>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 dark:text-white text-sm sm:text-base truncate"><?= esc($cat['name']) ?></p>
                                    <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 capitalize truncate"><?= esc($cat['type']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-4">
                            <?php if($cat['type'] === 'income'): ?>
                                <span class="bg-emerald-500/10 text-emerald-400 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold border border-emerald-500/20 whitespace-nowrap inline-flex items-center">
                                    <ion-icon name="trending-up-outline" class="hidden sm:inline-block mr-1"></ion-icon><?= lang('App.income') ?>
                                </span>
                            <?php else: ?>
                                <span class="bg-red-500/10 text-red-400 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold border border-red-500/20 whitespace-nowrap inline-flex items-center">
                                    <ion-icon name="trending-down-outline" class="hidden sm:inline-block mr-1"></ion-icon><?= lang('App.expense') ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right">
                            <?php if($cat['user_id'] == session('user_id')): ?>
                            <div class="flex items-center justify-end gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick='editCat(<?= json_encode($cat) ?>)' class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition-colors flex items-center justify-center" title="<?= lang('App.edit') ?>">
                                    <ion-icon name="create-outline"></ion-icon>
                                </button>
                                <button onclick="deleteCat(<?= $cat['id'] ?>)" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors flex items-center justify-center" title="<?= lang('App.delete') ?>">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            </div>
                            <?php else: ?>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic opacity-50"><?= lang('App.system') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function addCat() {
    Modal.show({
        title: '<ion-icon name="pricetag-outline" class="text-emerald-500"></ion-icon> <?= lang('App.new_category') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.category_name') ?></label>
                    <input id="modal-name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="<?= lang('App.category_name') ?>...">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.type') ?></label>
                    <select id="modal-type" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none">
                        <option value="expense"><?= lang('App.expense') ?></option>
                        <option value="income"><?= lang('App.income') ?></option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Icon</label>
                        <input id="modal-icon" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" value="wallet-outline">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.primary_color') ?></label>
                        <input id="modal-color" type="color" class="w-full h-11 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none p-1" value="#6366f1">
                    </div>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save') ?>',
        confirmColorClass: 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20',
        onConfirm: () => {
            const data = {
                name: document.getElementById('modal-name').value,
                type: document.getElementById('modal-type').value,
                icon: document.getElementById('modal-icon').value,
                color: document.getElementById('modal-color').value
            };
            if(!data.name) { Toast.fire({ icon: 'error', title: 'Name is required' }); return; }
            submitCat(data);
        }
    });
}

function editCat(cat) {
    Modal.show({
        title: '<ion-icon name="create-outline" class="text-indigo-500"></ion-icon> <?= lang('App.edit_category') ?>',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.category_name') ?></label>
                    <input id="modal-name" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${cat.name}">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.type') ?></label>
                    <select id="modal-type" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none">
                        <option value="expense" ${cat.type === 'expense' ? 'selected' : ''}><?= lang('App.expense') ?></option>
                        <option value="income" ${cat.type === 'income' ? 'selected' : ''}><?= lang('App.income') ?></option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Icon</label>
                        <input id="modal-icon" class="w-full h-11 px-4 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" value="${cat.icon}">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5"><?= lang('App.primary_color') ?></label>
                        <input id="modal-color" type="color" class="w-full h-11 rounded-xl bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none p-1" value="${cat.color}">
                    </div>
                </div>
            </div>
        `,
        confirmText: '<?= lang('App.save_changes') ?>',
        confirmColorClass: 'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20',
        onConfirm: () => {
            const data = {
                id: cat.id,
                name: document.getElementById('modal-name').value,
                type: document.getElementById('modal-type').value,
                icon: document.getElementById('modal-icon').value,
                color: document.getElementById('modal-color').value
            };
            if(!data.name) { Toast.fire({ icon: 'error', title: 'Name is required' }); return; }
            submitCat(data);
        }
    });
}

function submitCat(data) {
    showLoading();
    const formData = new FormData();
    Object.keys(data).forEach(key => formData.append(key, data[key]));

    const url = data.id ? '<?= base_url('category/update/') ?>' + data.id : '<?= base_url('category/store') ?>';

    fetch(url, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
            hideLoading();
            if (res.status === 'success') {
                Modal.hide();
                Toast.fire({ icon: 'success', title: res.message }).then(() => location.reload());
            } else {
                Toast.fire({ icon: 'error', title: res.message });
            }
        })
        .catch(err => { hideLoading(); Toast.fire({ icon: 'error', title: 'Error' }); });
}

function deleteCat(id) {
    Modal.show({
        title: '<ion-icon name="trash-outline" class="text-red-500"></ion-icon> <?= lang('App.delete_category_confirm') ?>',
        html: '<p class="text-slate-600 dark:text-slate-400"><?= lang('App.delete_category_desc') ?></p>',
        confirmText: '<?= lang('App.delete') ?>',
        confirmColorClass: 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
        onConfirm: () => {
            showLoading();
            fetch('<?= base_url('category/delete/') ?>' + id, { 
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if(data.status === 'success') {
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
<?= $this->endSection() ?>
