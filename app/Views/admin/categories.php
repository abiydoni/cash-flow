<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="pricetags-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.manage_users') ?> <?= lang('App.category') ?>
    </h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Add Category Form -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white mb-4"><?= lang('App.add') ?> <?= lang('App.category') ?> Global</h3>
            <form action="<?= base_url('admin/categories/store') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.category_name') ?> <span class="text-red-400">*</span></label>
                    <input name="name" type="text" value="<?= esc(old('name')) ?>" placeholder="<?= lang('App.category_name') ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500" required>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.type') ?> <span class="text-red-400">*</span></label>
                    <select name="type" class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500" required>
                        <option value="income">📈 <?= lang('App.income') ?></option>
                        <option value="expense">📉 <?= lang('App.expense') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.icons_hint') ?></label>
                    <input name="icon" type="text" value="<?= esc(old('icon', 'wallet-outline')) ?>" placeholder="wallet-outline"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1"><?= lang('App.search') ?>: <a href="https://ionic.io/ionicons" target="_blank" class="text-emerald-400">ionic.io/ionicons</a></p>
                </div>
                <div>
                    <label class="block text-xs text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.primary_color') ?></label>
                    <div class="flex items-center gap-3">
                        <input name="color" type="color" value="<?= esc(old('color', '#6366f1')) ?>"
                            class="w-12 h-10 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl cursor-pointer">
                        <span class="text-xs text-slate-500 dark:text-slate-400"><?= lang('App.select_color') ?></span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white font-semibold py-2.5 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                    <ion-icon name="add-outline"></ion-icon> <?= lang('App.add') ?> <?= lang('App.category') ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Categories List Table -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-[9px] sm:text-[10px] tracking-wider">
                        <tr>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 font-semibold"><?= lang('App.category_name') ?></th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 font-semibold text-center"><?= lang('App.type') ?></th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3 font-semibold text-center hidden sm:table-cell"><?= lang('App.category_type') ?></th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 font-semibold text-right"><?= lang('App.actions') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        <?php if(empty($categories)): ?>
                        <tr>
                            <td colspan="4" class="px-2 sm:px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                <ion-icon name="pricetags-outline" class="text-3xl sm:text-4xl mb-2 opacity-30"></ion-icon>
                                <p><?= lang('App.no_data') ?></p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($categories as $cat): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group" id="cat-<?= $cat['id'] ?>">
                                <td class="px-2 sm:px-6 py-2 sm:py-3">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-slate-800 dark:text-white shadow-lg shrink-0" style="background-color: <?= esc($cat['color']) ?>">
                                            <ion-icon name="<?= esc($cat['icon']) ?>" class="text-base sm:text-lg"></ion-icon>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-white text-[10px] sm:text-sm truncate"><?= esc($cat['name']) ?></p>
                                            <div class="mt-0 flex items-center gap-1.5">
                                                <p class="text-[8px] sm:text-xs text-slate-500 dark:text-slate-400 truncate opacity-60">ID: #<?= $cat['id'] ?></p>
                                                <!-- Owner badge on mobile -->
                                                <div class="sm:hidden">
                                                    <?php if($cat['owner'] === 'Global' || strtolower($cat['owner']) === 'administrator' || strtolower($cat['owner']) === 'admin'): ?>
                                                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-500 px-1 py-0.25 rounded text-[7px] font-bold border border-slate-200 dark:border-slate-700">SYS</span>
                                                    <?php else: ?>
                                                        <span class="bg-indigo-500/10 text-indigo-400 px-1 py-0.25 rounded text-[7px] font-bold border border-indigo-500/20"><?= esc($cat['owner']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-1.5 sm:px-4 py-2 sm:py-3 text-center">
                                    <?php if($cat['type'] === 'income'): ?>
                                        <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-1.5 sm:px-2 py-0.5 text-[8px] sm:text-xs font-semibold text-emerald-400 border border-emerald-500/20 whitespace-nowrap">
                                            <ion-icon name="trending-up-outline" class="hidden sm:inline-block mr-1"></ion-icon><?= lang('App.income') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center rounded-full bg-red-500/10 px-1.5 sm:px-2 py-0.5 text-[8px] sm:text-xs font-semibold text-red-400 border border-red-500/20 whitespace-nowrap">
                                            <ion-icon name="trending-down-outline" class="hidden sm:inline-block mr-1"></ion-icon><?= lang('App.expense') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-1.5 sm:px-4 py-2 sm:py-3 text-center hidden sm:table-cell">
                                    <?php if($cat['owner'] === 'Global' || strtolower($cat['owner']) === 'administrator' || strtolower($cat['owner']) === 'admin'): ?>
                                        <span class="inline-block bg-slate-100 dark:bg-slate-900 text-slate-500 dark:text-slate-400 px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-xs font-bold border border-slate-200 dark:border-slate-700 whitespace-nowrap uppercase">
                                            SYSTEM
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-1.5 sm:px-2 py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-xs font-bold whitespace-nowrap uppercase truncate max-w-[40px] sm:max-w-none">
                                            <?= esc($cat['owner']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-1 sm:px-6 py-2 sm:py-3 text-right">
                                    <div class="flex justify-end gap-1 sm:gap-1.5">
                                        <button onclick="deleteCat(<?= $cat['id'] ?>)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="<?= lang('App.delete') ?>">
                                            <ion-icon name="trash-outline" class="text-[10px] sm:text-xs"></ion-icon>
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
function deleteCat(id) {
    Swal.fire({
        title: '<?= lang('App.delete_category_confirm') ?>',
        text: '<?= lang('App.delete_category_global_warning') ?>',
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
        fetch(`<?= base_url('admin/categories/delete/') ?>${id}`, { 
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
                    document.getElementById('cat-'+id)?.remove();
                    Toast.fire({ icon: 'success', title: data.message });
                } else {
                    Toast.fire({ icon: 'error', title: data.message });
                }
            })
            .catch(err => {
                hideLoading();
                Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' });
            });
    });
}
</script>
<?= $this->endSection() ?>
