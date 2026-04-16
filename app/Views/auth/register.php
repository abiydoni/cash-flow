<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="CashFlow - Daftar akun baru">
    <title><?= lang('App.register_now') ?> | CashFlow App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={darkMode: 'class',theme:{extend:{fontFamily:{sans:['Inter','system-ui','sans-serif']}}}}</script>
    <script>
        if (localStorage.theme === 'dark') {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 min-h-screen flex items-center justify-center p-4 py-10">

<!-- Ambient Background Decorations -->
<div class="fixed inset-0 w-full h-full pointer-events-none z-0 overflow-hidden">
    <!-- Floating Blobs (Subtle) -->
    <div class="absolute -top-[10%] -right-[10%] w-[50%] h-[50%] bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute -bottom-[10%] -left-[10%] w-[50%] h-[50%] bg-teal-500/10 dark:bg-teal-500/5 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2.5s;"></div>
    
    <!-- Large Decorative Watermark Icon (Bottom Right) -->
    <div class="absolute -bottom-16 -right-16 text-emerald-500/[0.05] dark:text-emerald-500/[0.03] rotate-12 transform-gpu">
        <ion-icon name="person-circle-outline" class="text-[200px] sm:text-[350px] lg:text-[500px]"></ion-icon>
    </div>
</div>

<div class="w-full max-w-sm relative z-10">
    <div class="fixed top-6 right-6 z-50">
        <a href="<?= base_url('lang/' . (service('request')->getLocale() === 'en' ? 'id' : 'en')) ?>" 
           class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-emerald-500 border border-slate-200 dark:border-slate-700 transition-all shadow-lg"
           title="<?= lang('App.language') ?>">
            <span class="text-[10px] font-extrabold uppercase"><?= service('request')->getLocale() === 'en' ? 'ID' : 'EN' ?></span>
        </a>
    </div>

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white"><?= lang('App.create_account') ?></h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1"><?= lang('App.signup_free') ?></p>
    </div>

    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-xl">

        <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-4 bg-red-500/10 border border-red-500/30 text-red-300 px-3 py-3 rounded-xl text-sm">
            <div class="flex items-center gap-2 mb-1 font-semibold"><ion-icon name="alert-circle-outline"></ion-icon> <?= lang('App.error') ?>:</div>
            <ul class="list-disc list-inside space-y-0.5">
                <?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/register') ?>" method="POST" id="regForm">
            <?= csrf_field() ?>
            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5"><?= lang('App.full_name') ?> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <ion-icon name="person-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                        <input name="full_name" type="text" value="<?= esc(old('full_name')) ?>" placeholder="<?= lang('App.full_name_placeholder') ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5"><?= lang('App.username') ?> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <ion-icon name="at-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                        <input name="username" type="text" value="<?= esc(old('username')) ?>" placeholder="<?= lang('App.username_placeholder') ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5"><?= lang('App.email') ?> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <ion-icon name="mail-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                        <input name="email" type="email" value="<?= esc(old('email')) ?>" placeholder="<?= lang('App.email_placeholder') ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5"><?= lang('App.password') ?> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <ion-icon name="lock-closed-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                        <input id="password" name="password" type="password" placeholder="<?= lang('App.password_min_6') ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-12 py-2.5 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
                        <button type="button" onclick="togglePass('password','eye1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">
                            <ion-icon id="eye1" name="eye-outline"></ion-icon>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-1.5"><?= lang('App.confirm_password') ?> <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <ion-icon name="lock-closed-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400"></ion-icon>
                        <input id="confirm_password" name="confirm_password" type="password" placeholder="<?= lang('App.password_repeat') ?>"
                            class="w-full bg-slate-100 dark:bg-slate-700/50 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-white placeholder-slate-500 rounded-xl pl-10 pr-12 py-2.5 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
                        <button type="button" onclick="togglePass('confirm_password','eye2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400">
                            <ion-icon id="eye2" name="eye-outline"></ion-icon>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:opacity-90 text-slate-800 dark:text-white font-semibold py-3 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 mt-2">
                    <ion-icon name="person-add-outline"></ion-icon> <?= lang('App.register_now') ?>
                </button>
            </div>
        </form>

        <div class="mt-5 text-center">
            <p class="text-slate-500 dark:text-slate-400 text-sm">
                <?= lang('App.already_have_account') ?> <a href="<?= base_url('auth/login') ?>" class="text-emerald-400 hover:text-emerald-300 font-medium"><?= lang('App.login_here') ?></a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePass(id, iconId) {
    const inp = document.getElementById(id);
    const icon = document.getElementById(iconId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.setAttribute('name', inp.type === 'text' ? 'eye-off-outline' : 'eye-outline');
}
</script>
    <!-- Master Loading Overlay (Init as display:none) -->
    <div id="masterLoading" style="display: none; position: fixed; inset: 0; z-index: 99999; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); align-items: center; justify-content: center; flex-direction: column; gap: 1.5rem; transition: all 0.3s ease;">
        <style>
            .loader-ring { width: 56px; height: 56px; border: 4px solid rgba(74, 222, 128, 0.1); border-radius: 50%; border-top-color: #4ade80; box-shadow: 0 0 20px rgba(74, 222, 128, 0.2); animation: spin 0.8s cubic-bezier(0.5, 0, 0.5, 1) infinite; }
            @keyframes spin { to { transform: rotate(360deg); } }
            .loading-text { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; letter-spacing: 0.1em; text-transform: uppercase; font-size: 11px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); animation: pulse-op 1.5s ease-in-out infinite; }
            @keyframes pulse-op { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        </style>
        <div class="loader-ring"></div>
        <div class="loading-text" id="masterLoadingText"><?= lang('App.processing') ?></div>
    </div>

    <script>
    // Global Loading Functions
    function showLoading(text = '<?= lang('App.processing') ?>') {
        const el = document.getElementById('masterLoading');
        if (!el) return;
        const textEl = document.getElementById('masterLoadingText');
        if (textEl) textEl.innerText = text;
        el.style.display = 'flex';
    }

    function hideLoading() {
        const el = document.getElementById('masterLoading');
        if (!el) return;
        el.style.display = 'none';
    }

    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('no-loading')) return;
        showLoading();
    });

    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank' || link.classList.contains('no-loading')) return;
        showLoading();
    });

    window.addEventListener('pageshow', (event) => { if (event.persisted) hideLoading(); });
    </script>
</body>
</html>
