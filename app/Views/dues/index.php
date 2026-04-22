<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 no-print">
    <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
        <ion-icon name="calendar-outline" class="text-emerald-400"></ion-icon>
        <?= lang('App.dues_payment') ?>
    </h2>

</div>

<!-- Restructured Dues Selection -->
<div class="mb-8">
    <div class="flex items-center gap-2 text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">
        <ion-icon name="options-outline"></ion-icon>
        <?= lang('App.select') ?> <?= lang('App.dues_type') ?>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 no-print">
    <?php if(empty($duesTypes)): ?>
        <div class="col-span-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-12 text-center text-slate-500">
            <ion-icon name="alert-circle-outline" class="text-6xl mb-4 opacity-20"></ion-icon>
            <p><?= lang('App.no_data_duestype') ?></p>
            <a href="<?= base_url('duestype') ?>" class="mt-4 inline-flex items-center gap-2 bg-emerald-500 text-slate-900 px-4 py-2 rounded-xl font-bold text-xs">
                <ion-icon name="add-outline"></ion-icon> <?= lang('App.add') ?> <?= lang('App.dues_type') ?>
            </a>
        </div>
    <?php else: ?>
        <?php foreach($duesTypes as $dt): ?>
            <a href="<?= base_url('dues/type/' . $dt['id']) ?>" class="group block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 hover:shadow-xl hover:border-emerald-500/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-500 font-bold text-xl group-hover:bg-emerald-500 group-hover:text-white transition-all transform group-hover:rotate-12">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-slate-800 dark:text-white truncate group-hover:text-emerald-400 transition-colors">
                            <?php 
                                $slug = preg_replace('/[^a-z0-9]/', '_', strtolower(trim($dt['name'])));
                                $trans = lang('App.' . $slug);
                                echo ($trans === 'App.' . $slug) ? esc($dt['name']) : $trans;
                            ?>
                        </h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Tarif: Rp <?= number_format($dt['amount'], 0, ',', '.') ?></p>
                    </div>
                    <div class="text-slate-300 dark:text-slate-600 group-hover:text-emerald-400 transition-colors">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
@media print {
    @page { size: landscape; margin: 10mm; }
    body { background: white !important; }
    .print-only { display: block !important; width: 100% !important; margin: 0 !important; padding: 0 !important; }
    .no-print { display: none !important; }
    table { width: 100% !important; table-layout: fixed; border-collapse: collapse !important; }
    thead tr { background: #000 !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    th { font-size: 8px !important; padding: 6px 2px !important; border: 1px solid #333 !important; font-weight: 900 !important; text-transform: uppercase !important; }
    td { font-size: 8px !important; padding: 4px 2px !important; border: 1px solid #ddd !important; border-radius: 0 !important; }
    .rounded-\[2rem\], .rounded-3xl, .rounded-\[2\.5rem\], .rounded-2xl, .rounded-xl, .rounded-lg { border-radius: 0 !important; }
    .border-2 { border-width: 1px !important; }
}
</style>

<?= $this->endSection() ?>
