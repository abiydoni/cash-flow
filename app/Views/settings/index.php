<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 bg-slate-900 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-white shadow-xl rotate-3 group hover:rotate-0 transition-transform duration-300">
            <ion-icon name="settings-outline" class="text-3xl"></ion-icon>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white"><?= lang('App.settings') ?></h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-widest font-semibold"><?= lang('App.system_configuration') ?></p>
        </div>
    </div>

    <!-- Reset Data Section -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
        <!-- Glow Effect -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-red-500/5 rounded-full blur-3xl"></div>
        
        <div class="p-8 relative">
            <div class="flex items-start gap-6">
                <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-500 shadow-inner">
                    <ion-icon name="nuclear-outline" class="text-3xl"></ion-icon>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2"><?= lang('App.reset_data_factory') ?></h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed max-w-2xl">
                        <?= lang('App.reset_data_description') ?>
                    </p>
                    
                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                            <ion-icon name="checkmark-circle" class="text-emerald-500"></ion-icon> <?= lang('App.keep_users') ?>
                        </span>
                        <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-[10px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                            <ion-icon name="checkmark-circle" class="text-emerald-500"></ion-icon> <?= lang('App.keep_system_categories') ?>
                        </span>
                        <span class="px-3 py-1 bg-red-100 dark:bg-red-500/10 rounded-lg text-[10px] font-bold text-red-500 uppercase tracking-wider flex items-center gap-2">
                            <ion-icon name="alert-circle" class="text-red-500"></ion-icon> <?= lang('App.delete_all_transactions') ?>
                        </span>
                    </div>

                    <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-700">
                        <form action="<?= base_url('settings/reset') ?>" method="POST" id="resetForm" class="space-y-6">
                            <?= csrf_field() ?>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                                    <?= lang('App.reset_confirmation_label') ?> <span class="text-red-500 opacity-60 italic">"RESET"</span>
                                </label>
                                <input type="text" name="confirmation" required placeholder="Type RESET here..."
                                    class="w-full max-w-sm bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-2xl px-6 py-4 text-base font-black tracking-widest focus:outline-none focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all text-center uppercase placeholder:font-normal placeholder:tracking-normal placeholder:text-slate-300">
                            </div>
                            
                            <button type="button" onclick="confirmReset()" class="group relative flex items-center gap-3 bg-red-500 hover:bg-red-600 text-slate-800 dark:text-white font-bold px-10 py-4 rounded-2xl transition-all shadow-xl shadow-red-500/20 active:scale-95">
                                <ion-icon name="trash-outline" class="text-xl group-hover:rotate-12 transition-transform"></ion-icon>
                                <?= lang('App.reset_system_now') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Config Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Backup Section -->
        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-xl group hover:border-emerald-500/30 transition-all duration-300">
            <div class="flex items-center gap-5 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shadow-inner group-hover:scale-110 transition-transform">
                    <ion-icon name="cloud-download-outline" class="text-3xl"></ion-icon>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800 dark:text-white"><?= lang('App.database_backup') ?></h4>
                    <p class="text-[10px] text-slate-400 tracking-wider font-semibold uppercase italic"><?= lang('App.secure_your_data') ?></p>
                </div>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-8">
                <?= lang('App.backup_description') ?>
            </p>
            <button onclick="confirmBackup()" class="no-loading inline-flex items-center gap-3 bg-slate-900 dark:bg-slate-700 hover:bg-emerald-500 text-white font-bold px-8 py-4 rounded-2xl transition-all shadow-lg active:scale-95 w-full justify-center">
                <ion-icon name="download-outline" class="text-xl"></ion-icon>
                <?= lang('App.download_sql_dump') ?>
            </button>
        </div>

        <!-- Notification Section -->
        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-700 shadow-xl group hover:border-indigo-500/30 transition-all duration-300">
            <div class="flex items-center gap-5 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 shadow-inner group-hover:scale-110 transition-transform">
                    <ion-icon name="notifications-outline" class="text-3xl"></ion-icon>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-slate-800 dark:text-white"><?= lang('App.notif_settings') ?></h4>
                    <p class="text-[10px] text-slate-400 tracking-wider font-semibold uppercase italic"><?= lang('App.whatsapp_integration') ?></p>
                </div>
            </div>
            
            <form action="<?= base_url('admin/settings/notifications') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1"><?= lang('App.wa_gateway_url') ?></label>
                    <input type="url" name="wa_gateway_url" value="<?= esc($settings['wa_gateway_url'] ?? '') ?>" placeholder="https://api.whatsapp.com/..."
                        class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1"><?= lang('App.wa_api_key') ?></label>
                    <input type="text" name="wa_api_key" value="<?= esc($settings['wa_api_key'] ?? '') ?>" placeholder="Enter API Key here..."
                        class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-xl px-4 py-3 text-sm focus:border-indigo-500 transition-all">
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-tight"><?= lang('App.enable_notifications') ?></span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="notif_enabled" value="1" class="sr-only peer" <?= ($settings['notif_enabled'] ?? '0') == '1' ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-500"></div>
                    </label>
                </div>
                <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    <ion-icon name="save-outline"></ion-icon>
                    <?= lang('App.save_settings') ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmReset() {
    const input = document.querySelector('input[name="confirmation"]').value;
    if (input !== 'RESET') {
        Toast.fire({ icon: 'error', title: '<?= lang('App.please_type_reset') ?>' });
        return;
    }

    Swal.fire({
        title: '<?= lang('App.reset_critical_title') ?>',
        text: '<?= lang('App.reset_critical_text') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#475569',
        confirmButtonText: '<?= lang('App.yes_reset_everything') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9',
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            document.getElementById('resetForm').submit();
        }
    });
}

function confirmBackup() {
    Swal.fire({
        title: '<?= lang('App.backup_confirm_title') ?>',
        text: '<?= lang('App.backup_confirm_text') ?>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#475569',
        confirmButtonText: '<?= lang('App.yes_download_now') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b', color: '#f1f5f9',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/settings/backup') ?>';
        }
    });
}
</script>
<?= $this->endSection() ?>
