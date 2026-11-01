<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['elara_user'] ?? null;
$currentPage = $currentPage ?? '';
$parlor = $appConfig['parlor'] ?? [];

$isLiveCloud = false;
if (isset($db)) {
    $isLiveCloud = $db->isLiveCloud();
} elseif (isset($this) && isset($this->db)) {
    $isLiveCloud = $this->db->isLiveCloud();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? $appConfig['name']) ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fbf9f5',
                            100: '#f5efe4',
                            200: '#eadeca',
                            300: '#dcc6a8',
                            400: '#caa77f',
                            500: '#b88c58', // Primary Warm Champagne Gold
                            600: '#a37645',
                            700: '#855d37',
                            800: '#6c4b2f',
                            900: '#583e28',
                        },
                        charcoal: {
                            50: '#f6f6f6',
                            100: '#e7e7e7',
                            200: '#d1d1d1',
                            300: '#b0b0b0',
                            400: '#888888',
                            500: '#6d6d6d',
                            600: '#5d5d5d',
                            700: '#4f4f4f',
                            800: '#2b2a29',
                            900: '#1c1917', // Logo Deep Charcoal
                        },
                        ivory: {
                            50: '#fdfcfb',
                            100: '#faf7f2',
                            200: '#f4ede2',
                            300: '#ebe1d1',
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts Playfair Display & Cormorant Garamond & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf7f2;
            color: #1c1917;
        }
        .font-serif-luxury {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }
        .logo-shadow {
            filter: drop-shadow(0 2px 4px rgba(28, 25, 23, 0.08));
        }
        .border-brand-soft {
            border-color: #eadeca;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f4ede2;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #dcc6a8;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-brand-200 selection:text-charcoal-900">

    <!-- Top Luxury Navigation Header -->
    <header class="bg-[#fcfaf7] border-b border-brand-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 sm:h-24">
                
                <!-- LEFT CORNER: Primary Official Brand Logo (Enlarged, Heading Removed) -->
                <div class="flex items-center space-x-3">
                    <a href="/" class="flex items-center group py-0.5" title="Switch to Web Portal">
                        <div class="bg-white p-1.5 rounded-xl border-2 border-amber-400/80 shadow-xs group-hover:border-blue-600 transition">
                            <img 
                                src="/assets/images/elara_logo.jpg" 
                                alt="ELARA Logo" 
                                class="h-14 sm:h-16 w-auto object-contain rounded-lg"
                            >
                        </div>
                    </a>

                    <!-- Live Firestore Badge -->
                    <div class="hidden xl:flex items-center ml-3">
                        <?php if ($isLiveCloud): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            Live Firestore
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50/80 text-amber-800 border border-amber-200/80" title="Firestore Local Emulator Active">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                            Firestore Emulator
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Navigation Links (Center) -->
                <nav class="hidden lg:flex items-center space-x-1">
                    <?php if (!$currentUser || $currentUser['role'] === 'admin'): ?>
                    <a href="/dashboard" class="px-3 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition <?= $currentPage === 'dashboard' ? 'bg-charcoal-900 text-brand-300 shadow-sm' : 'text-charcoal-700 hover:text-charcoal-900 hover:bg-brand-100/60' ?>">
                        <i class="fa-solid fa-chart-pie mr-1 text-[11px] text-brand-400"></i> Dashboard
                    </a>
                    <?php endif; ?>
                    <a href="/pos" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition shadow-sm <?= $currentPage === 'pos' ? 'bg-brand-600 text-white shadow-brand-200' : 'bg-brand-100 text-brand-900 hover:bg-brand-200' ?>">
                        <i class="fa-solid fa-cash-register mr-1 text-[11px]"></i> POS Terminal
                    </a>
                    <?php if (!$currentUser || $currentUser['role'] === 'admin'): ?>
                    <a href="/services" class="px-3 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition <?= $currentPage === 'services' ? 'bg-charcoal-900 text-brand-300 shadow-sm' : 'text-charcoal-700 hover:text-charcoal-900 hover:bg-brand-100/60' ?>">
                        <i class="fa-solid fa-scissors mr-1 text-[11px] text-brand-400"></i> Treatments
                    </a>
                    <a href="/inventory" class="px-3 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition <?= $currentPage === 'inventory' ? 'bg-charcoal-900 text-brand-300 shadow-sm' : 'text-charcoal-700 hover:text-charcoal-900 hover:bg-brand-100/60' ?>">
                        <i class="fa-solid fa-boxes-stacked mr-1 text-[11px] text-brand-400"></i> Inventory
                    </a>
                    <?php endif; ?>
                    <a href="/invoices" class="px-3 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition <?= $currentPage === 'invoices' ? 'bg-charcoal-900 text-brand-300 shadow-sm' : 'text-charcoal-700 hover:text-charcoal-900 hover:bg-brand-100/60' ?>">
                        <i class="fa-solid fa-receipt mr-1 text-[11px] text-brand-400"></i> Invoices
                    </a>
                    <?php if (!$currentUser || $currentUser['role'] === 'admin'): ?>
                    <a href="/reports" class="px-3 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition <?= $currentPage === 'reports' ? 'bg-charcoal-900 text-brand-300 shadow-sm' : 'text-charcoal-700 hover:text-charcoal-900 hover:bg-brand-100/60' ?>">
                        <i class="fa-solid fa-chart-line mr-1 text-[11px] text-brand-400"></i> Reports
                    </a>
                    <?php endif; ?>
                    <a href="/" target="_blank" class="px-3 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition text-brand-800 hover:bg-brand-100/70 border border-brand-200" title="Open Public Website">
                        <i class="fa-solid fa-globe mr-1 text-[11px] text-brand-600"></i> Web Portal
                    </a>
                </nav>

                <!-- RIGHT CORNER: Actions, Active User & Logo Insignia Badge -->
                <div class="flex items-center space-x-2.5">
                    <button onclick="toggleElaraChat()" class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-bold bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/30 shadow-xs transition">
                        <i class="fa-solid fa-wand-magic-sparkles mr-1.5 text-brand-400"></i>
                        <span class="hidden sm:inline">Ask ELARA</span>
                    </button>

                    <?php if ($currentUser): ?>
                    <!-- Logged In User Pill -->
                    <div class="hidden md:flex items-center space-x-2 bg-brand-50 border border-brand-200 px-2.5 py-1.5 rounded-xl">
                        <div class="w-6 h-6 rounded-full bg-charcoal-900 text-brand-300 flex items-center justify-center text-[10px] font-black">
                            <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="leading-tight text-left">
                            <span class="text-xs font-bold text-charcoal-900 block truncate max-w-[110px]"><?= htmlspecialchars(explode(' ', $currentUser['name'])[0]) ?></span>
                            <span class="text-[9px] font-bold uppercase tracking-wider <?= $currentUser['role'] === 'admin' ? 'text-brand-700' : 'text-emerald-700' ?>">
                                <?= htmlspecialchars($currentUser['role']) ?>
                            </span>
                        </div>
                        <a href="/logout" class="p-1 text-charcoal-400 hover:text-red-600 transition ml-1" title="Sign Out">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        </a>
                    </div>
                    <?php else: ?>
                    <a href="/login" class="inline-flex items-center px-3 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold shadow-xs transition">
                        <i class="fa-solid fa-lock mr-1.5 text-[11px]"></i> Login
                    </a>
                    <?php endif; ?>

                    <!-- RIGHT CORNER: Brand Emblem Crest -->
                    <div class="flex items-center border-l border-brand-200 pl-2.5">
                        <div class="w-10 h-10 rounded-xl bg-white border border-brand-200 p-0.5 shadow-2xs flex items-center justify-center overflow-hidden hover:scale-105 transition" title="ELARA Beauté Studio • By Elia & Sehar">
                            <img 
                                src="/assets/images/elara_logo.jpg" 
                                alt="ELARA Crest" 
                                class="w-full h-full object-cover rounded-lg"
                            >
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Mobile Navigation Strip -->
        <div class="lg:hidden border-t border-brand-200 bg-brand-50 px-4 py-2.5 flex items-center justify-around text-xs font-bold uppercase tracking-wider overflow-x-auto">
            <a href="/" class="p-1.5 <?= $currentPage === 'dashboard' ? 'text-brand-800 font-extrabold border-b-2 border-brand-600' : 'text-charcoal-600' ?>">Dashboard</a>
            <a href="/pos" class="p-1.5 <?= $currentPage === 'pos' ? 'text-brand-800 font-extrabold border-b-2 border-brand-600' : 'text-charcoal-600' ?>">POS</a>
            <a href="/services" class="p-1.5 <?= $currentPage === 'services' ? 'text-brand-800 font-extrabold border-b-2 border-brand-600' : 'text-charcoal-600' ?>">Treatments</a>
            <a href="/inventory" class="p-1.5 <?= $currentPage === 'inventory' ? 'text-brand-800 font-extrabold border-b-2 border-brand-600' : 'text-charcoal-600' ?>">Inventory</a>
            <a href="/invoices" class="p-1.5 <?= $currentPage === 'invoices' ? 'text-brand-800 font-extrabold border-b-2 border-brand-600' : 'text-charcoal-600' ?>">Invoices</a>
            <a href="/reports" class="p-1.5 <?= $currentPage === 'reports' ? 'text-brand-800 font-extrabold border-b-2 border-brand-600' : 'text-charcoal-600' ?>">Reports</a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow">
