<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit      = ($mode === 'edit');
$tx          = $transaction ?? [];
$activeType  = old('type', $tx['type'] ?? $type ?? 'expense');
$payMethods  = [
    'cash'          => ['label'=>lang('App.cash'),       'icon'=>'cash-outline'],
    'bank_transfer' => ['label'=>lang('App.bank_transfer'),'icon'=>'phone-portrait-outline'],
    'credit_card'   => ['label'=>lang('App.credit_card'), 'icon'=>'card-outline'],
    'debit_card'    => ['label'=>lang('App.debit_card'),  'icon'=>'card-outline'],
    'e_wallet'      => ['label'=>lang('App.e_wallet'),     'icon'=>'wallet-outline'],
    'other'         => ['label'=>lang('App.others'),      'icon'=>'ellipsis-horizontal-outline'],
];
?>

<div class="w-full">
    <!-- Header -->
    <div class="flex items-center gap-2 mb-4">
        <a href="<?= base_url('transaction') ?>" class="w-9 h-9 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:bg-slate-600 rounded-xl flex items-center justify-center text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white transition-colors">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <h2 class="text-base font-bold text-slate-800 dark:text-white">
            <?= $isEdit ? lang('App.edit_transaction') : lang('App.add_transaction') ?>
        </h2>
    </div>

    <!-- Type Toggle -->
    <?php if (! $isEdit): ?>
    <div class="flex gap-2 mb-3 p-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl">
        <a href="?type=income"
            class="flex-1 flex items-center justify-center gap-2 py-1.5 rounded-xl text-[11px] font-semibold transition-all <?= $activeType === 'income' ? 'bg-emerald-500 text-slate-800 dark:text-white shadow-lg shadow-emerald-500/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white' ?>">
            <ion-icon name="trending-up-outline"></ion-icon> <?= lang('App.income') ?>
        </a>
        <a href="?type=expense"
            class="flex-1 flex items-center justify-center gap-2 py-1.5 rounded-xl text-[11px] font-semibold transition-all <?= $activeType === 'expense' ? 'bg-red-500 text-slate-800 dark:text-white shadow-lg shadow-red-500/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white' ?>">
            <ion-icon name="trending-down-outline"></ion-icon> <?= lang('App.expense') ?>
        </a>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= $isEdit ? base_url('transaction/update/'.$tx['id']) : base_url('transaction/store') ?>" method="POST" class="space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" name="type" id="typeInput" value="<?= esc($activeType) ?>">

        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-3 space-y-2">

            <!-- Category -->
            <div>
                <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">
                    <?= lang('App.category') ?> <span class="text-red-400">*</span>
                </label>
                <?php
                $cats = $activeType === 'income' ? $incomeCategories : $expenseCategories;
                $selCat = old('category_id', $tx['category_id'] ?? '');
                ?>
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 xl:grid-cols-12 gap-1.5" id="categoryGrid">
                    <?php foreach ($cats as $cat): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="category_id" value="<?= $cat['id'] ?>" class="sr-only"
                            <?= $selCat == $cat['id'] ? 'checked' : '' ?> required>
                        <div class="cat-card flex flex-col items-center gap-1 p-1.5 rounded-lg border-[1.5px] border-transparent bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-100 dark:bg-slate-700 transition-all text-center"
                            style="--cat-color:<?= esc($cat['color']) ?>">
                            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background-color:<?= esc($cat['color']) ?>20">
                                <ion-icon name="<?= esc($cat['icon']) ?>" style="color:<?= esc($cat['color']) ?>;font-size:0.875rem;"></ion-icon>
                            </div>
                            <span class="text-[9px] font-medium text-slate-600 dark:text-slate-300 leading-tight truncate w-full px-0.5"><?= esc($cat['name']) ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-0.5">
                    <?= lang('App.amount') ?> (Rp) <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm font-semibold">Rp</span>
                    <input id="amountInput" name="amount" type="number" min="1" step="1"
                        value="<?= esc(old('amount', $tx['amount'] ?? '')) ?>"
                        placeholder="0"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-1.5 text-[11px] focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        required oninput="formatAmount(this)">
                </div>
                <p id="amountFormatted" class="text-xs text-slate-500 dark:text-slate-400 mt-1 pl-1"></p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-0.5"><?= lang('App.description') ?></label>
                <input name="description" type="text" value="<?= esc(old('description', $tx['description'] ?? '')) ?>"
                    placeholder="<?= lang('App.description') ?> <?= lang('App.transaction') ?>..."
                    class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-1.5 text-[11px] focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <!-- Date -->
            <div>
                <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-0.5">
                    <?= lang('App.date') ?> <?= lang('App.transaction') ?> <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <ion-icon name="calendar-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                    <input name="transaction_date" type="date"
                        value="<?= esc(old('transaction_date', $tx['transaction_date'] ?? date('Y-m-d'))) ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl pl-10 pr-4 py-1.5 text-[11px] focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        required>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1"><?= lang('App.payment_method') ?></label>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-1.5">
                    <?php $selMethod = old('payment_method', $tx['payment_method'] ?? 'cash'); ?>
                    <?php foreach ($payMethods as $key => $pm): ?>
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="<?= $key ?>" class="sr-only"
                            <?= $selMethod === $key ? 'checked' : '' ?>>
                        <div class="pay-card flex flex-col sm:flex-row items-center justify-center gap-1 p-1.5 rounded-lg border-[1.5px] border-transparent bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[9px] font-medium transition-all text-center">
                            <ion-icon name="<?= $pm['icon'] ?>" class="text-[14px] sm:text-xs text-slate-600 dark:text-slate-300"></ion-icon>
                            <span class="text-[9px] leading-tight truncate px-0.5 w-full"><?= $pm['label'] ?></span>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Note (collapsible) -->
            <div>
                <button type="button" onclick="toggleNote()" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:text-slate-200 flex items-center gap-1 mb-2 transition-colors">
                    <ion-icon name="chevron-down-outline" id="noteChevron"></ion-icon> <?= lang('App.add_note_ref') ?>
                </button>
                <div id="noteSection" class="hidden space-y-3">
                    <textarea name="note" rows="3" placeholder="<?= lang('App.note_placeholder') ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-1.5 text-[11px] focus:outline-none focus:border-emerald-500 resize-none"><?= esc(old('note', $tx['note'] ?? '')) ?></textarea>
                    <input name="reference_no" type="text" value="<?= esc(old('reference_no', $tx['reference_no'] ?? '')) ?>"
                        placeholder="<?= lang('App.ref_no') ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-1.5 text-[11px] focus:outline-none focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" id="submitBtn"
            class="w-full <?= $activeType === 'income' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 shadow-emerald-500/20' : 'bg-gradient-to-r from-red-500 to-rose-600 shadow-red-500/20' ?> text-slate-800 dark:text-white font-semibold py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg text-sm">
            <ion-icon name="<?= $isEdit ? 'checkmark-circle-outline' : 'add-circle-outline' ?>"></ion-icon>
            <?= $isEdit ? lang('App.save') : lang('App.save_transaction') ?>
        </button>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Category card selection highlight
document.querySelectorAll('.cat-card').forEach(card => {
    const radio = card.closest('label').querySelector('input[type=radio]');
    if (radio.checked) highlightCatCard(card);
    radio.addEventListener('change', () => {
        document.querySelectorAll('.cat-card').forEach(c => {
            c.style.removeProperty('border-color');
            c.style.removeProperty('background-color');
        });
        highlightCatCard(card);
    });
});
function highlightCatCard(card) {
    const color = card.style.getPropertyValue('--cat-color').trim();
    if(color) {
        card.style.borderColor = color;
        card.style.backgroundColor = color + '20';
    }
}

// Payment method highlight
document.querySelectorAll('.pay-card').forEach(card => {
    const radio = card.closest('label').querySelector('input[type=radio]');
    if (radio.checked) {
        card.classList.add('border-emerald-500', 'text-emerald-400', 'bg-emerald-500/10');
    }
    radio.addEventListener('change', () => {
        document.querySelectorAll('.pay-card').forEach(c => {
            c.classList.remove('border-emerald-500','text-emerald-400','bg-emerald-500/10');
        });
        card.classList.add('border-emerald-500', 'text-emerald-400', 'bg-emerald-500/10');
    });
});

// Format amount display
function formatAmount(input) {
    const val = parseInt(input.value);
    document.getElementById('amountFormatted').textContent =
        isNaN(val) ? '' : 'Rp ' + val.toLocaleString('id-ID');
}
// Init
const ai = document.getElementById('amountInput');
if (ai.value) formatAmount(ai);

function toggleNote() {
    const sec = document.getElementById('noteSection');
    const chev = document.getElementById('noteChevron');
    sec.classList.toggle('hidden');
    chev.setAttribute('name', sec.classList.contains('hidden') ? 'chevron-down-outline' : 'chevron-up-outline');
}

// If note has value, show it
<?php if (!empty($tx['note']) || !empty($tx['reference_no'])): ?>
toggleNote();
<?php endif; ?>

// Submit loading state handled by Master Loading in main.php
</script>
<?= $this->endSection() ?>
