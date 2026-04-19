<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="CashFlow - Personal Finance Management">
    <title><?= lang('App.signin') ?> | CashFlow</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981', // primary
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Theme Handling Script -->
    <script>
        if (localStorage.theme === 'dark') {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <!-- Icons & ALerts -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Hide scrollbar for a cleaner look if content overflows slightly */
        ::-webkit-scrollbar { display: none; }
        .glass-panel {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        }
        .dark .glass-panel {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .input-icon-wrapper ion-icon {
            transition: color 0.3s ease;
        }
        input:focus + .input-icon-wrapper ion-icon,
        input:not(:placeholder-shown) + .input-icon-wrapper ion-icon {
            color: #10b981; /* brand-500 */
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-700 dark:text-slate-200 relative flex items-center justify-center p-4 py-12">

    <!-- Ambient Background Decorations -->
    <div class="fixed inset-0 w-full h-full pointer-events-none z-0 overflow-hidden">
    </div>

    <!-- Language Switcher -->
    <div class="fixed top-6 right-6 z-50">
        <a href="<?= base_url('lang/' . (service('request')->getLocale() === 'en' ? 'id' : 'en')) ?>" 
           class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-emerald-500 border border-slate-200 dark:border-slate-700 transition-all shadow-lg"
           title="<?= lang('App.language') ?>">
            <span class="text-[10px] font-extrabold uppercase"><?= service('request')->getLocale() === 'en' ? 'ID' : 'EN' ?></span>
        </a>
    </div>

    <!-- Login Container -->
    <main class="w-full max-w-md relative z-10 animate-fade-in-up">
        
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <img src="<?= base_url('logo1.png') ?>" alt="CashFlow Logo" class="h-16 mx-auto mb-2">
            <p class="text-slate-500 dark:text-slate-400 font-medium">Smart Personal Finance.</p>
        </div>

        <!-- Glass Card -->
        <div class="glass-panel bg-white/80 dark:bg-slate-800/70 border border-slate-200 dark:border-white/5 rounded-[2rem] p-8 sm:p-10 relative overflow-hidden">
            <!-- Subtle top highlight for 3D effect -->
            <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2"><?= lang('App.welcome_back') ?></h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-8"><?= lang('App.signin_desc') ?></p>

            <form action="<?= base_url('auth/login') ?>" method="POST" id="loginForm" class="space-y-5">
                <?= csrf_field() ?>

                <!-- Field: Identifier -->
                <div class="space-y-2">
                    <label for="login" class="block text-sm font-medium text-slate-600 dark:text-slate-300 ml-1"><?= lang('App.email_or_username') ?></label>
                    <div class="relative flex items-center group">
                        <input 
                            type="text" 
                            id="login" 
                            name="login" 
                            value="<?= esc(old('login')) ?>"
                            placeholder="<?= lang('App.login_placeholder') ?>" 
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-700/50 rounded-2xl py-3.5 pl-12 pr-4 text-slate-800 dark:text-white placeholder:text-slate-400 dark:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all peer"
                            required autocomplete="username"
                        >
                        <div class="input-icon-wrapper absolute left-4 text-slate-400 dark:text-slate-500 pointer-events-none peer-focus:text-brand-500 transition-colors">
                            <ion-icon name="person-outline" class="text-xl"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Field: Password -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="block text-sm font-medium text-slate-600 dark:text-slate-300"><?= lang('App.password') ?></label>
                        <!-- Forgot password link can go here in the future -->
                    </div>
                    <div class="relative flex items-center group">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="••••••••" 
                            class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-700/50 rounded-2xl py-3.5 pl-12 pr-12 text-slate-800 dark:text-white placeholder:text-slate-400 dark:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all peer"
                            required autocomplete="current-password"
                        >
                        <div class="input-icon-wrapper absolute left-4 text-slate-400 dark:text-slate-500 pointer-events-none peer-focus:text-brand-500 transition-colors">
                            <ion-icon name="lock-closed-outline" class="text-xl"></ion-icon>
                        </div>
                        <button type="button" id="togglePassword" class="absolute right-4 text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:text-slate-300 focus:outline-none transition-colors">
                            <ion-icon name="eye-outline" class="text-xl"></ion-icon>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="w-full relative group overflow-hidden bg-brand-500 text-slate-800 dark:text-white font-bold rounded-2xl py-3.5 mt-4 transition-transform active:scale-[0.98]">
                    <div class="absolute inset-0 bg-gradient-to-r from-brand-600 to-brand-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <span class="relative flex items-center justify-center gap-2">
                        <?= lang('App.signin') ?>
                        <ion-icon name="arrow-forward-outline" class="text-lg group-hover:translate-x-1 transition-transform"></ion-icon>
                    </span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    <?= lang('App.no_account') ?> 
                    <a href="<?= base_url('auth/register') ?>" class="text-brand-400 hover:text-brand-300 font-semibold hover:underline transition-all"><?= lang('App.signup_now') ?></a>
                </p>
            </div>
        </div>

        <!-- Branding Footer -->
        <div class="mt-8 text-center">
            <a href="https://appsbee.my.id" target="_blank" class="group relative inline-flex items-center justify-center text-[10px] uppercase font-bold tracking-[0.4em] transition-all duration-500 h-4 min-w-[180px]">
                <span class="absolute inset-0 flex items-center justify-center transition-all duration-500 group-hover:opacity-0 group-hover:scale-95 animate-shimmer-brand bg-[linear-gradient(to_right,#64748b_20%,#10b981_40%,#10b981_60%,#64748b_80%)] bg-[length:200%_auto] bg-clip-text text-transparent">
                    appsbee 2026
                </span>
                <span class="absolute inset-0 flex items-center justify-center opacity-0 scale-110 group-hover:opacity-100 group-hover:scale-100 transition-all duration-500 text-emerald-400 whitespace-nowrap">
                    visit website
                </span>
            </a>
        </div>

        <style>
            @keyframes shimmer {
                to { background-position: 200% center; }
            }
            .animate-shimmer-brand {
                animation: shimmer 3s linear infinite;
            }
        </style>

    </main>

    <!-- Scripts -->
    <script>
        // Password Visibility Toggle
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = toggleBtn.querySelector('ion-icon');

        toggleBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.setAttribute('name', isPassword ? 'eye-off-outline' : 'eye-outline');
            
            // Add a little pop animation
            toggleIcon.style.transform = 'scale(0.8)';
            setTimeout(() => toggleIcon.style.transform = 'scale(1)', 100);
        });



        // Form Submit Loading State Integration (Optional but good UX)
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const span = btn.querySelector('span');
            span.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-slate-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <?= lang('App.loading') ?>`;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            // We do NOT call preventDefault(), let the form submit naturally to CodeIgniter.
        });

        // Flash Messages using SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f8fafc' : '#1e293b',
                iconColor: '#10b981',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            <?php if (session()->getFlashdata('error')): ?>
                Toast.fire({
                    icon: 'error',
                    title: <?= json_encode(session()->getFlashdata('error')) ?>,
                    iconColor: '#ef4444' // red-500
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                Toast.fire({
                    icon: 'success',
                    title: <?= json_encode(session()->getFlashdata('success')) ?>
                });
            <?php endif; ?>
        });
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
