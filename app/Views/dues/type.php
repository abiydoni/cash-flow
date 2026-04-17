<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <a href="<?= base_url('dues') ?>" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-emerald-500 hover:border-emerald-500/50 transition-all shadow-sm">
            <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white"><?= esc($type['name']) ?></h2>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold"><?= lang('App.select') ?> <?= lang('App.member') ?></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <?php if(empty($members)): ?>
        <div class="col-span-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-12 text-center text-slate-500">
            <ion-icon name="people-outline" class="text-6xl mb-4 opacity-20"></ion-icon>
            <p><?= lang('App.no_data_member') ?></p>
            <a href="<?= base_url('member') ?>" class="mt-4 inline-flex items-center gap-2 bg-emerald-500 text-slate-900 px-4 py-2 rounded-xl font-bold text-xs">
                <ion-icon name="add-outline"></ion-icon> <?= lang('App.add') ?> <?= lang('App.member') ?>
            </a>
        </div>
    <?php else: ?>
        <?php foreach($members as $m): ?>
            <a href="<?= base_url('dues/member/' . $m['id'] . '?type_id=' . $type['id']) ?>" class="group block bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 hover:shadow-xl hover:border-emerald-500/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center text-slate-800 dark:text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                        <?= strtoupper(substr($m['name'], 0, 1)) ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-slate-800 dark:text-white truncate group-hover:text-emerald-400 transition-colors"><?= esc($m['name']) ?></h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full <?= $m['is_active'] ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium tracking-tight">Status: <?= $m['is_active'] ? lang('App.active') : lang('App.inactive') ?></p>
                        </div>
                    </div>
                    <div class="text-slate-300 dark:text-slate-600 group-hover:text-emerald-400 transition-colors">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
