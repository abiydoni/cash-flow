<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="CashFlow - Aplikasi Manajemen Keuangan Pribadi">
    <title><?= $title ?? 'CashFlow' ?> | CashFlow App</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d',950:'#052e16' },
                        income:  { light:'#d1fae5', DEFAULT:'#10b981', dark:'#065f46' },
                        expense: { light:'#fee2e2', DEFAULT:'#ef4444', dark:'#7f1d1d' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.6rem 0.875rem; border-radius: 0.6rem;
            color: #475569; font-size: 13px; font-weight: 500;
            transition: all 0.2s; text-decoration: none;
        }
        .sidebar-link:hover { background: rgba(0,0,0,0.05); color: #0f172a; }
        .sidebar-link.active { background: rgba(0,0,0,0.08); color: #0f172a; font-weight: 600; }
        
        .dark .sidebar-link { color: #cbd5e1; }
        .dark .sidebar-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .dark .sidebar-link.active { background: rgba(255,255,255,0.2); color: #fff; font-weight: 600; }
        ion-icon { font-size: 1.25rem; flex-shrink: 0; }
        .card-glass { backdrop-filter: blur(10px); background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #4ade80; border-radius: 10px; }
        /* Master Loading Styles - Premium Glassmorphism */
        #masterLoading {
            display: none; position: fixed; inset: 0; z-index: 99999;
            background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            align-items: center; justify-content: center; flex-direction: column; gap: 1.5rem;
            transition: all 0.3s ease;
        }
        .loader-ring {
            width: 56px; height: 56px; border: 4px solid rgba(74, 222, 128, 0.1);
            border-radius: 50%; border-top-color: #4ade80; 
            box-shadow: 0 0 20px rgba(74, 222, 128, 0.2);
            animation: spin 0.8s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-text { 
            font-weight: 700; color: #fff; letter-spacing: 0.1em; text-transform: uppercase;
            font-size: 11px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            animation: pulse-op 1.5s ease-in-out infinite;
        }
        @keyframes pulse-op { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 h-full">

<div class="flex h-screen overflow-hidden">

    <!-- ─── SIDEBAR (Desktop) ──────────────────────────────────────────────── -->
    <aside id="sidebar" class="hidden lg:flex flex-col w-64 bg-slate-50 border-r border-slate-200 dark:bg-gradient-to-b dark:from-slate-800 dark:to-slate-900 dark:border-slate-700 flex-shrink-0">
        <!-- Logo -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center">
                    <ion-icon name="wallet" class="text-slate-800 dark:text-white text-xl"></ion-icon>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-800 dark:text-white leading-tight">CashFlow</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manajemen Keuangan</p>
                </div>
            </a>
        </div>

        <!-- User info -->
        <div class="px-4 py-4 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <?php if (session('avatar')): ?>
                    <img src="<?= base_url(session('avatar')) ?>" class="w-10 h-10 rounded-full object-cover ring-2 ring-emerald-400" alt="avatar">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-slate-800 dark:text-white font-bold">
                        <?= strtoupper(substr(session('full_name') ?? session('username'), 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white truncate"><?= esc(session('full_name') ?? session('username')) ?></p>
                    <p class="text-xs text-emerald-400 capitalize"><?= esc(session('role')) ?></p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3">
            <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>

            <a href="<?= base_url('dashboard') ?>" class="sidebar-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <ion-icon name="grid-outline"></ion-icon> <?= lang('App.dashboard') ?>
            </a>
            <a href="<?= base_url('transaction') ?>" class="sidebar-link <?= (uri_string() === 'transaction' || strpos(uri_string(), 'transaction/edit') !== false) ? 'active' : '' ?>">
                <ion-icon name="swap-horizontal-outline"></ion-icon> <?= lang('App.transaction') ?>
            </a>
            <a href="<?= base_url('transaction/create?type=income') ?>" class="sidebar-link <?= (uri_string() === 'transaction/create' && service('request')->getGet('type') === 'income') ? 'active' : '' ?>">
                <ion-icon name="trending-up-outline"></ion-icon> <?= lang('App.income') ?>
            </a>
            <a href="<?= base_url('transaction/create?type=expense') ?>" class="sidebar-link <?= (uri_string() === 'transaction/create' && service('request')->getGet('type') === 'expense') ? 'active' : '' ?>">
                <ion-icon name="trending-down-outline"></ion-icon> <?= lang('App.expense') ?>
            </a>

            <a href="<?= base_url('category') ?>" class="sidebar-link <?= (uri_string() === 'category') ? 'active' : '' ?>">
                <ion-icon name="pricetags-outline"></ion-icon> <?= lang('App.category') ?>
            </a>

            <?php if (session('role') === 'admin'): ?>
            <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-4 mb-2"><?= lang('App.admin') ?></p>
            <a href="<?= base_url('admin/users') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
                <ion-icon name="people-outline"></ion-icon> <?= lang('App.manage_users') ?>
            </a>
            <a href="<?= base_url('admin/transactions') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/transactions') !== false ? 'active' : '' ?>">
                <ion-icon name="list-outline"></ion-icon> <?= lang('App.all_transactions') ?>
            </a>
            <a href="<?= base_url('admin/categories') ?>" class="sidebar-link <?= strpos(uri_string(), 'admin/categories') !== false ? 'active' : '' ?>">
                <ion-icon name="pricetags-outline"></ion-icon> <?= lang('App.category') ?>
            </a>
            <?php endif; ?>

            <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-4 mb-2"><?= lang('App.account') ?></p>
            <a href="<?= base_url('profile') ?>" class="sidebar-link <?= strpos(uri_string(), 'profile') !== false ? 'active' : '' ?>">
                <ion-icon name="person-outline"></ion-icon> <?= lang('App.my_profile') ?>
            </a>
            <a href="<?= base_url('auth/logout') ?>" onclick="confirmLogout(event)" class="sidebar-link text-red-400 hover:bg-red-500/10 hover:text-red-300">
                <ion-icon name="log-out-outline"></ion-icon> <?= lang('App.logout') ?>
            </a>
        </nav>
    </aside>

    <!-- ─── MAIN CONTENT ───────────────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Bar -->
        <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-4 py-2 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white hover:bg-slate-100 dark:bg-slate-700 transition-colors" onclick="toggleMobileSidebar()">
                    <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
                </button>
                <div class="lg:hidden flex items-center gap-2">
                    <div class="w-7 h-7 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-lg flex items-center justify-center">
                        <ion-icon name="wallet" class="text-slate-800 dark:text-white text-sm"></ion-icon>
                    </div>
                    <span class="text-slate-800 dark:text-white font-bold text-sm">CashFlow</span>
                </div>
                <h2 class="hidden lg:block text-slate-800 dark:text-white font-semibold text-sm"><?= $title ?? 'Dashboard' ?></h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('lang/' . (service('request')->getLocale() === 'en' ? 'id' : 'en')) ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors" title="<?= lang('App.language') ?>">
                    <span class="text-[10px] font-extrabold uppercase"><?= service('request')->getLocale() === 'en' ? 'ID' : 'EN' ?></span>
                </a>
                <button onclick="toggleTheme()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors" title="Toggle Mode">
                    <ion-icon name="moon-outline" class="hidden dark:block mt-0.5"></ion-icon>
                    <ion-icon name="sunny-outline" class="block dark:hidden mt-0.5"></ion-icon>
                </button>
                <a href="<?= base_url('transaction/create?type=income') ?>" class="hidden sm:flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-slate-800 dark:text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors">
                    <ion-icon name="add-outline"></ion-icon> <?= lang('App.record') ?>
                </a>
                <a href="<?= base_url('profile') ?>" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <?php if (session('avatar')): ?>
                        <img src="<?= base_url(session('avatar')) ?>" class="w-8 h-8 rounded-full object-cover ring-2 ring-emerald-400" alt="avatar">
                    <?php else: ?>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-slate-800 dark:text-white text-xs font-bold">
                            <?= strtoupper(substr(session('full_name') ?? session('username'), 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </a>
            </div>
        </header>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden" onclick="toggleMobileSidebar()"></div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-800 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden overflow-y-auto">
            <div class="p-5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-xl flex items-center justify-center">
                        <ion-icon name="wallet" class="text-slate-800 dark:text-white"></ion-icon>
                    </div>
                    <span class="text-slate-800 dark:text-white font-bold">CashFlow</span>
                </div>
                <button onclick="toggleMobileSidebar()" class="text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:text-white">
                    <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                </button>
            </div>
            <div class="px-4 py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <?php if (session('avatar')): ?>
                        <img src="<?= base_url(session('avatar')) ?>" class="w-10 h-10 rounded-full object-cover ring-2 ring-emerald-400" alt="avatar">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-slate-800 dark:text-white font-bold">
                            <?= strtoupper(substr(session('full_name') ?? session('username'), 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white"><?= esc(session('full_name') ?? session('username')) ?></p>
                        <p class="text-xs text-emerald-400 capitalize"><?= esc(session('role')) ?></p>
                    </div>
                </div>
            </div>
            <nav class="py-4 px-3">
                <a href="<?= base_url('dashboard') ?>" class="sidebar-link" onclick="toggleMobileSidebar()"><ion-icon name="grid-outline"></ion-icon> <?= lang('App.dashboard') ?></a>
                <a href="<?= base_url('transaction') ?>" class="sidebar-link <?= (uri_string() === 'transaction' || strpos(uri_string(), 'transaction/edit') !== false) ? 'active' : '' ?>" onclick="toggleMobileSidebar()"><ion-icon name="swap-horizontal-outline"></ion-icon> <?= lang('App.transaction') ?></a>
                <a href="<?= base_url('transaction/create?type=income') ?>" class="sidebar-link <?= (uri_string() === 'transaction/create' && service('request')->getGet('type') === 'income') ? 'active' : '' ?>" onclick="toggleMobileSidebar()"><ion-icon name="trending-up-outline"></ion-icon> <?= lang('App.income') ?></a>
                <a href="<?= base_url('transaction/create?type=expense') ?>" class="sidebar-link <?= (uri_string() === 'transaction/create' && service('request')->getGet('type') === 'expense') ? 'active' : '' ?>" onclick="toggleMobileSidebar()"><ion-icon name="trending-down-outline"></ion-icon> <?= lang('App.expense') ?></a>
                <a href="<?= base_url('category') ?>" class="sidebar-link <?= (uri_string() === 'category') ? 'active' : '' ?>" onclick="toggleMobileSidebar()"><ion-icon name="pricetags-outline"></ion-icon> <?= lang('App.category') ?></a>
                <?php if (session('role') === 'admin'): ?>
                <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-4 mb-2"><?= lang('App.admin') ?></p>
                <a href="<?= base_url('admin/users') ?>" class="sidebar-link" onclick="toggleMobileSidebar()"><ion-icon name="people-outline"></ion-icon> <?= lang('App.manage_users') ?></a>
                <a href="<?= base_url('admin/transactions') ?>" class="sidebar-link" onclick="toggleMobileSidebar()"><ion-icon name="list-outline"></ion-icon> <?= lang('App.all_transactions') ?></a>
                <a href="<?= base_url('admin/categories') ?>" class="sidebar-link" onclick="toggleMobileSidebar()"><ion-icon name="pricetags-outline"></ion-icon> <?= lang('App.category') ?></a>
                <?php endif; ?>
                <p class="px-3 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-4 mb-2"><?= lang('App.account') ?></p>
                <a href="<?= base_url('profile') ?>" class="sidebar-link" onclick="toggleMobileSidebar()"><ion-icon name="person-outline"></ion-icon> <?= lang('App.my_profile') ?></a>
                <a href="<?= base_url('auth/logout') ?>" onclick="confirmLogout(event)" class="sidebar-link text-red-400"><ion-icon name="log-out-outline"></ion-icon> <?= lang('App.logout') ?></a>
            </nav>
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-3 lg:p-5 pb-20 lg:pb-5">

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
            <div id="flash-success" class="mb-4 flex items-center gap-3 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-3 rounded-xl">
                <ion-icon name="checkmark-circle-outline" class="text-xl flex-shrink-0"></ion-icon>
                <span class="text-sm"><?= session()->getFlashdata('success') ?></span>
                <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-slate-800 dark:text-white"><ion-icon name="close-outline"></ion-icon></button>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            <div id="flash-error" class="mb-4 flex items-center gap-3 bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-xl">
                <ion-icon name="alert-circle-outline" class="text-xl flex-shrink-0"></ion-icon>
                <span class="text-sm"><?= session()->getFlashdata('error') ?></span>
                <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-400 hover:text-slate-800 dark:text-white"><ion-icon name="close-outline"></ion-icon></button>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-4 bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2 mb-2"><ion-icon name="alert-circle-outline"></ion-icon><span class="font-semibold text-sm">Terdapat kesalahan:</span></div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<!-- ─── BOTTOM NAVIGATION (Mobile) ────────────────────────────────────────── -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 z-30">
    <div class="flex items-center justify-around py-1.5">
        <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg <?= uri_string() === 'dashboard' ? 'text-emerald-400' : 'text-slate-500 dark:text-slate-400' ?>">
            <ion-icon name="<?= uri_string() === 'dashboard' ? 'grid' : 'grid-outline' ?>"></ion-icon>
            <span class="text-xs font-medium"><?= lang('App.home') ?></span>
        </a>
        <a href="<?= base_url('transaction') ?>" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg <?= (uri_string() === 'transaction' || strpos(uri_string(), 'transaction/edit') !== false) ? 'text-emerald-400' : 'text-slate-500 dark:text-slate-400' ?>">
            <ion-icon name="swap-horizontal-outline"></ion-icon>
            <span class="text-xs font-medium"><?= lang('App.transaction') ?></span>
        </a>
        <a href="<?= base_url('transaction/create') ?>" class="flex flex-col items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-full flex items-center justify-center shadow-lg -mt-5">
                <ion-icon name="add-outline" class="text-slate-800 dark:text-white text-2xl"></ion-icon>
            </div>
            <span class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5"><?= lang('App.record') ?></span>
        </a>
        <a href="<?= base_url('profile') ?>" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg <?= strpos(uri_string(), 'profile') !== false ? 'text-emerald-400' : 'text-slate-500 dark:text-slate-400' ?>">
            <ion-icon name="<?= strpos(uri_string(), 'profile') !== false ? 'person' : 'person-outline' ?>"></ion-icon>
            <span class="text-xs font-medium"><?= lang('App.profile') ?></span>
        </a>
        <button onclick="toggleMobileSidebar()" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-lg text-slate-500 dark:text-slate-400">
            <ion-icon name="menu-outline"></ion-icon>
            <span class="text-xs font-medium"><?= lang('App.menu') ?></span>
        </button>
    </div>
</nav>

<script>
function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('mobileOverlay');
    const isOpen  = !sidebar.classList.contains('-translate-x-full');
    if (isOpen) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }
}

function toggleTheme() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
}

function confirmLogout(e) {
    e.preventDefault();
    Swal.fire({
        title: '<?= lang('App.logout') ?>?',
        text: '<?= lang('App.logout_confirm') ?>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<?= lang('App.logout_yes') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>',
        background: '#1e293b',
        color: '#f1f5f9',
    }).then((result) => {
        if (result.isConfirmed) window.location = '<?= base_url('auth/logout') ?>';
    });
}

// Auto dismiss flash messages
setTimeout(() => {
    ['flash-success','flash-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
}, 5000);

// SweetAlert default theme
const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
    background: '#1e293b', color: '#f1f5f9',
});
window.Toast = Toast;
</script>

<!-- Master Loading Overlay -->
<!-- Master Loading Overlay (Init as display:none) -->
<div id="masterLoading" style="display: none !important;">
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
    el.classList.remove('opacity-0');
}

function hideLoading() {
    const el = document.getElementById('masterLoading');
    if (!el) return;
    el.classList.add('opacity-0');
    setTimeout(() => { el.style.display = 'none'; }, 300);
}

// Intercept all Link Clicks (Page Transitions)
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (!link) return;
    
    // Safety checks
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank' || link.hasAttribute('download') || link.classList.contains('no-loading')) return;
    
    // Same page anchors or hashes
    const url = new URL(link.href);
    if (url.origin === window.location.origin && url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;

    // Show loading
    showLoading();
});

// Auto-intercept all form submissions
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.classList.contains('no-loading')) return;
    showLoading();
});

// Safety: Hide loading when page is shown (handles back/forward cache)
window.addEventListener('pageshow', (event) => {
    if (event.persisted) hideLoading();
});

// Fallback: Clear loading if user stays too long without navigation (e.g. failed transition)
// window.onbeforeunload = function() { showLoading(); };
</script>

<?= $this->renderSection('scripts') ?>

<script>hideLoading(); // Force hide on boot</script>
</body>
</html>
