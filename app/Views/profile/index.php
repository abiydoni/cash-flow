<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php $u = $user ?? []; ?>

<div class="w-full space-y-4">
    <!-- Header Card with Avatar -->
    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 border border-emerald-500/20 rounded-2xl p-6 shadow-xl shadow-emerald-500/10">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            <!-- Avatar Upload -->
            <div class="relative flex-shrink-0">
                <div id="avatarPreview" class="w-24 h-24 rounded-2xl overflow-hidden bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center">
                    <?php if (!empty($u['avatar'])): ?>
                        <img src="<?= base_url($u['avatar']) ?>" class="w-full h-full object-cover" alt="avatar">
                    <?php else: ?>
                        <span class="text-4xl font-bold text-slate-800 dark:text-white"><?= strtoupper(substr($u['full_name'] ?? $u['username'] ?? '?', 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <label for="avatarInput" class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 hover:bg-emerald-600 rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-colors">
                    <ion-icon name="camera-outline" class="text-slate-800 dark:text-white text-sm"></ion-icon>
                </label>
            </div>

            <!-- Basic Info -->
            <div class="text-center sm:text-left flex-1">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white"><?= esc($u['full_name'] ?? 'User') ?></h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs">@<?= esc($u['username'] ?? '') ?></p>
                <div class="flex flex-wrap items-center gap-2 mt-2 justify-center sm:justify-start">
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full <?= ($u['role']??'')  === 'admin' ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400' ?>">
                        <ion-icon name="<?= ($u['role']??'') === 'admin' ? 'shield-checkmark-outline' : 'person-outline' ?>"></ion-icon>
                        <?= ucfirst($u['role'] ?? 'user') ?>
                    </span>
                    <?php if (!empty($u['city'])): ?>
                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300">
                        <ion-icon name="location-outline"></ion-icon> <?= esc($u['city']) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($u['bio'])): ?>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2"><?= esc($u['bio']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-0.5">
        <button onclick="showTab('profile')" id="tab-profile"
            class="flex-1 text-[13px] font-semibold py-2 rounded-xl transition-all bg-white/10 text-slate-800 dark:text-white flex items-center justify-center gap-1.5">
            <ion-icon name="person-outline"></ion-icon> <span class="hidden sm:inline"><?= lang('App.profile_data') ?></span>
        </button>
        <button onclick="showTab('finance')" id="tab-finance"
            class="flex-1 text-[13px] font-semibold py-2 rounded-xl transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white flex items-center justify-center gap-1.5">
            <ion-icon name="wallet-outline"></ion-icon> <span class="hidden sm:inline"><?= lang('App.finance') ?></span>
        </button>
        <button onclick="showTab('password')" id="tab-password"
            class="flex-1 text-[13px] font-semibold py-2 rounded-xl transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white flex items-center justify-center gap-1.5">
            <ion-icon name="lock-closed-outline"></ion-icon> <span class="hidden sm:inline"><?= lang('App.password') ?></span>
        </button>
    </div>

    <!-- Profile Form -->
    <form action="<?= base_url('profile/update') ?>" method="POST" enctype="multipart/form-data" id="profileForm">
        <?= csrf_field() ?>
        <input type="file" id="avatarInput" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">

        <!-- Tab: Profile -->
        <div id="pane-profile" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 space-y-3">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-3 flex items-center gap-2">
                <ion-icon name="person-outline" class="text-emerald-400"></ion-icon> <?= lang('App.personal_info') ?>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input name="full_name" type="text" value="<?= esc($u['full_name'] ?? '') ?>"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input name="email" type="email" value="<?= esc($u['email'] ?? '') ?>"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.phone_number') ?></label>
                    <input name="phone_number" type="tel" value="<?= esc($u['phone_number'] ?? '') ?>" placeholder="08xxx"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.gender') ?></label>
                    <select name="gender" class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium">
                        <option value=""><?= lang('App.select_option') ?></option>
                        <option value="male"   <?= ($u['gender']??'') === 'male'   ? 'selected' : '' ?>><?= lang('App.male') ?></option>
                        <option value="female" <?= ($u['gender']??'') === 'female' ? 'selected' : '' ?>><?= lang('App.female') ?></option>
                        <option value="other"  <?= ($u['gender']??'') === 'other'  ? 'selected' : '' ?>><?= lang('App.others') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.date_of_birth') ?></label>
                    <input name="date_of_birth" type="date" value="<?= esc($u['date_of_birth'] ?? '') ?>"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.address') ?></label>
                    <textarea name="address" rows="2" placeholder="<?= lang('App.address_placeholder') ?>"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium resize-none"><?= esc($u['address'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.city') ?></label>
                    <input name="city" type="text" value="<?= esc($u['city'] ?? '') ?>" placeholder="Jakarta"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.province') ?></label>
                    <input name="province" type="text" value="<?= esc($u['province'] ?? '') ?>" placeholder="DKI Jakarta"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.postal_code') ?></label>
                    <input name="postal_code" type="text" value="<?= esc($u['postal_code'] ?? '') ?>" placeholder="10220"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.bio') ?></label>
                    <textarea name="bio" rows="2" placeholder="<?= lang('App.bio_placeholder') ?>"
                        class="w-full bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500 transition-all font-medium resize-none"><?= esc($u['bio'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Tab: Finance Settings -->
        <div id="pane-finance" class="hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 space-y-3">
            <h3 class="text-sm font-semibold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-3 flex items-center gap-2">
                <ion-icon name="wallet-outline" class="text-emerald-400"></ion-icon> <?= lang('App.finance_settings') ?>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.currency') ?></label>
                    <select name="currency" class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-emerald-500">
                        <option value="IDR" <?= ($u['currency']??'IDR')==='IDR' ? 'selected' : '' ?>>🇮🇩 IDR - Rupiah</option>
                        <option value="USD" <?= ($u['currency']??'')==='USD' ? 'selected' : '' ?>>🇺🇸 USD - Dollar</option>
                        <option value="SGD" <?= ($u['currency']??'')==='SGD' ? 'selected' : '' ?>>🇸🇬 SGD - S. Dollar</option>
                        <option value="MYR" <?= ($u['currency']??'')==='MYR' ? 'selected' : '' ?>>🇲🇾 MYR - Ringgit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.income_target') ?></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">Rp</span>
                        <input name="monthly_income_target" type="number" min="0"
                            value="<?= esc($u['monthly_income_target'] ?? 0) ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl pl-10 pr-4 py-2 text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.expense_limit') ?></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 text-sm">Rp</span>
                        <input name="monthly_expense_limit" type="number" min="0"
                            value="<?= esc($u['monthly_expense_limit'] ?? 0) ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white rounded-xl pl-10 pr-4 py-2 text-xs focus:outline-none focus:border-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button (for profile & finance tabs) -->
        <div id="saveWrapper" class="mt-4">
            <button type="submit"
                class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:opacity-90 text-slate-800 dark:text-white font-semibold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                <ion-icon name="save-outline"></ion-icon> <?= lang('App.save') ?>
            </button>
        </div>
    </form>

    <!-- Tab: Change Password (separate form) -->
    <form action="<?= base_url('profile/password') ?>" method="POST" id="pane-password" class="hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 space-y-3">
        <?= csrf_field() ?>
        <h3 class="text-sm font-semibold text-slate-800 dark:text-white border-b border-slate-200 dark:border-slate-700 pb-3 flex items-center gap-2">
            <ion-icon name="lock-closed-outline" class="text-emerald-400"></ion-icon> <?= lang('App.password_change') ?>
        </h3>
        <div class="space-y-3">
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.old_password') ?> <span class="text-red-400">*</span></label>
                <div class="relative">
                    <ion-icon name="lock-closed-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                    <input name="old_password" type="password" placeholder="<?= lang('App.password_current') ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2 text-xs focus:outline-none focus:border-emerald-500" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.new_password') ?> <span class="text-red-400">*</span></label>
                <div class="relative">
                    <ion-icon name="lock-open-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                    <input name="new_password" type="password" placeholder="<?= lang('App.password_min_6') ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2 text-xs focus:outline-none focus:border-emerald-500" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5"><?= lang('App.confirm_new_password') ?> <span class="text-red-400">*</span></label>
                <div class="relative">
                    <ion-icon name="shield-checkmark-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                    <input name="confirm_password" type="password" placeholder="<?= lang('App.password_repeat') ?>"
                        class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2 text-xs focus:outline-none focus:border-emerald-500" required>
                </div>
            </div>
        </div>
        <button type="submit"
            class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:opacity-90 text-slate-800 dark:text-white font-semibold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2">
            <ion-icon name="key-outline"></ion-icon> <?= lang('App.password_change') ?>
        </button>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function showTab(name) {
    const panes = ['profile','finance','password'];
    panes.forEach(p => {
        document.getElementById('pane-'+p)?.classList.add('hidden');
        document.getElementById('tab-'+p)?.classList.remove('bg-white/10','text-slate-800', 'dark:text-white');
        document.getElementById('tab-'+p)?.classList.add('text-slate-500', 'dark:text-slate-400');
    });
    document.getElementById('pane-'+name)?.classList.remove('hidden');
    document.getElementById('tab-'+name)?.classList.add('bg-white/10','text-slate-800', 'dark:text-white');
    document.getElementById('tab-'+name)?.classList.remove('text-slate-500', 'dark:text-slate-400');
    // Show/hide save button
    document.getElementById('saveWrapper').style.display =
        (name === 'password') ? 'none' : 'block';
}

function previewAvatar(input) {
    const [file] = input.files;
    if (file) {
        const prev = document.getElementById('avatarPreview');
        const reader = new FileReader();
        reader.onload = e => {
            prev.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="preview">`;
        };
        reader.readAsDataURL(file);
    }
}

// Init
showTab('profile');
</script>
<?= $this->endSection() ?>
