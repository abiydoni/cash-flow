<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white leading-tight"><?= lang('App.manage_personal_categories') ?></h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1"><?= lang('App.manage_categories_desc') ?></p>
    </div>
    <button onclick="document.getElementById('modalAddCategory').classList.remove('hidden')" class="bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white px-4 py-2 rounded-xl font-semibold text-sm transition-colors flex items-center justify-center gap-2">
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
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                <?php if(empty($categories)): ?>
                <tr>
                    <td colspan="2" class="px-3 sm:px-6 py-8 text-center text-slate-400 dark:text-slate-500">
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
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Add -->
<div id="modalAddCategory" class="fixed inset-0 bg-slate-900/40 dark:bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 opacity-0 transition-opacity duration-300" style="display: none;">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/30">
            <h3 class="font-bold text-slate-800 dark:text-white text-lg"><?= lang('App.new_category') ?></h3>
            <button onclick="closeModal()" class="text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white transition-colors">
                <ion-icon name="close-outline" class="text-2xl"></ion-icon>
            </button>
        </div>
        <form action="<?= base_url('category/store') ?>" method="POST" class="p-6">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2"><?= lang('App.category_name') ?></label>
                    <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium placeholder-slate-500" placeholder="<?= lang('App.category_name') ?>...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2"><?= lang('App.type') ?></label>
                    <select name="type" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all font-medium appearance-none">
                        <option value="expense"><?= lang('App.expense') ?></option>
                        <option value="income"><?= lang('App.income') ?></option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2"><?= lang('App.icons_hint') ?></label>
                        <input type="text" name="icon" value="wallet-outline" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all text-sm">
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1"><?= lang('App.search') ?> di <a href="https://ionic.io/ionicons" target="_blank" class="text-emerald-400 hover:underline">ionic.io/ionicons</a></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2"><?= lang('App.primary_color') ?></label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" value="#6366f1" required class="h-11 w-11 rounded-xl cursor-pointer border-0 p-0 bg-transparent">
                            <span class="text-sm font-mono text-slate-500 dark:text-slate-400"><?= lang('App.select_color') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 rounded-xl font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:bg-slate-600 transition-colors"><?= lang('App.cancel') ?></button>
                <button type="submit" class="flex-1 px-4 py-3 rounded-xl font-semibold text-slate-800 dark:text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30 transition-all"><?= lang('App.save') ?></button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const modal = document.getElementById('modalAddCategory');
const modalContent = modal.querySelector('div');

// Fix display transition for modal
document.querySelector('[onclick="document.getElementById(\'modalAddCategory\').classList.remove(\'hidden\')"]').onclick = function() {
    modal.style.display = 'flex';
    // small delay to allow display block to apply before changing opacity
    setTimeout(() => {
        modal.classList.remove('hidden');
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
    }, 10);
};

function closeModal() {
    modal.classList.add('opacity-0');
    modalContent.classList.add('scale-95');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

function deleteCat(id) {
    Swal.fire({
        title: '<?= lang('App.delete_category_confirm') ?>',
        text: '<?= lang('App.delete_category_desc') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<?= lang('App.delete_yes') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9',
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading('<?= lang('App.processing') ?>');
            fetch('<?= base_url('category/delete/') ?>' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    window.location.reload();
                } else {
                    hideLoading();
                    Toast.fire({ icon: 'error', title: data.message });
                }
            })
            .catch(err => {
                hideLoading();
                Toast.fire({ icon: 'error', title: '<?= lang('App.something_went_wrong') ?>' });
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
