<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation" class="flex justify-center items-center gap-1 my-6">
    <?php if ($pager->hasPrevious()) : ?>
        <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-slate-700/50 text-slate-500 hover:bg-emerald-500 hover:text-white transition-all duration-200 shadow-sm border border-slate-200 dark:border-slate-700/50">
            <ion-icon name="chevron-back-outline" class="text-sm"></ion-icon><ion-icon name="chevron-back-outline" class="text-sm -ml-2"></ion-icon>
        </a>
        <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-slate-700/50 text-slate-500 hover:bg-emerald-500 hover:text-white transition-all duration-200 shadow-sm border border-slate-200 dark:border-slate-700/50">
            <ion-icon name="chevron-back-outline" class="text-sm"></ion-icon>
        </a>
    <?php endif ?>

    <?php foreach ($pager->links() as $link) : ?>
        <a href="<?= $link['uri'] ?>" class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all duration-200 shadow-sm border <?= $link['active'] ? 'bg-emerald-500 text-white border-emerald-500 shadow-emerald-500/20' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 hover:bg-emerald-500/10 hover:text-emerald-500 border-slate-200 dark:border-slate-700/50' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach ?>

    <?php if ($pager->hasNext()) : ?>
        <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-slate-700/50 text-slate-500 hover:bg-emerald-500 hover:text-white transition-all duration-200 shadow-sm border border-slate-200 dark:border-slate-700/50">
            <ion-icon name="chevron-forward-outline" class="text-sm"></ion-icon>
        </a>
        <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" class="w-9 h-9 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-slate-700/50 text-slate-500 hover:bg-emerald-500 hover:text-white transition-all duration-200 shadow-sm border border-slate-200 dark:border-slate-700/50">
            <ion-icon name="chevron-forward-outline" class="text-sm"></ion-icon><ion-icon name="chevron-forward-outline" class="text-sm -ml-2"></ion-icon>
        </a>
    <?php endif ?>
</nav>
