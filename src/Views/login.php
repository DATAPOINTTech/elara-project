<?php
$appConfig = $appConfig ?? (require dirname(__DIR__, 2) . '/config/app.php');
$parlor = $appConfig['parlor'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff & Admin Portal Login | <?= htmlspecialchars($parlor['name']) ?></title>
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
                            500: '#b88c58',
                            600: '#a37645',
                            700: '#855d37',
                            800: '#6c4b2f',
                            900: '#583e28',
                        },
                        charcoal: {
                            800: '#272522',
                            900: '#1c1917',
                            950: '#141210',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf7f2;
            color: #1c1917;
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 selection:bg-brand-200">

    <div class="max-w-md w-full space-y-6">
        
        <!-- Brand Header Card -->
        <div class="text-center space-y-3">
            <a href="/" class="inline-block group">
                <div class="w-20 h-20 mx-auto bg-white p-1 rounded-3xl border border-brand-200 shadow-md group-hover:scale-105 transition duration-200">
                    <img src="/assets/images/elara_logo.jpg" alt="ELARA Logo" class="w-full h-full object-contain rounded-2xl">
                </div>
            </a>
            <div>
                <h1 class="text-2xl font-black text-charcoal-900 font-cinzel tracking-wider">ELARA BEAUTÉ STUDIO</h1>
                <p class="text-xs uppercase tracking-[0.2em] text-brand-600 font-bold mt-0.5">By Elia & Sehar</p>
                <p class="text-[11px] text-charcoal-400 mt-1">Main Qasimabad Road, Hyderabad &bull; Staff Authentication</p>
            </div>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white rounded-3xl border border-brand-200 shadow-xl p-8 space-y-5">
            
            <div class="pb-3 border-b border-brand-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-charcoal-900 uppercase tracking-wider">Secure Staff Sign In</h2>
                    <p class="text-[11px] text-charcoal-400">Admin management & User-based POS access</p>
                </div>
                <span class="w-7 h-7 rounded-lg bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-lock"></i>
                </span>
            </div>

            <?php if (!empty($error)): ?>
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'logged_out'): ?>
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>You have successfully logged out.</span>
            </div>
            <?php endif; ?>

            <form action="/login" method="POST" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1 uppercase tracking-wider text-[10px]">Username or Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-charcoal-400 text-xs"></i>
                        <input 
                            type="text" 
                            name="username" 
                            id="loginUsername"
                            required 
                            placeholder="admin or cashier1" 
                            class="w-full pl-9 pr-3 py-2.5 bg-brand-50/40 border border-brand-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium text-charcoal-900"
                        >
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-charcoal-700 mb-1 uppercase tracking-wider text-[10px]">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-charcoal-400 text-xs"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="loginPassword"
                            required 
                            placeholder="••••••••" 
                            class="w-full pl-9 pr-10 py-2.5 bg-brand-50/40 border border-brand-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 font-medium text-charcoal-900"
                        >
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-charcoal-400 hover:text-charcoal-700">
                            <i id="passwordEyeIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full py-3 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/30 rounded-xl font-bold tracking-wider uppercase text-xs transition shadow-md flex items-center justify-center space-x-2"
                >
                    <i class="fa-solid fa-arrow-right-to-bracket text-brand-400"></i>
                    <span>Authenticate & Access Studio</span>
                </button>
            </form>

            <!-- Quick Auto-Fill Credential Badges -->
            <div class="pt-3 border-t border-brand-100 text-[11px] space-y-2">
                <span class="text-charcoal-400 font-medium block">Quick Fill Test Accounts:</span>
                <div class="grid grid-cols-2 gap-2">
                    <button 
                        type="button" 
                        onclick="fillCredentials('admin', 'elara123')" 
                        class="p-2 rounded-xl bg-brand-50 hover:bg-brand-100 border border-brand-200 text-left transition"
                    >
                        <strong class="text-charcoal-900 block font-bold text-[11px]">👑 Admin Access</strong>
                        <span class="text-charcoal-500 text-[10px]">admin / elara123</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="fillCredentials('cashier1', 'elara123')" 
                        class="p-2 rounded-xl bg-brand-50 hover:bg-brand-100 border border-brand-200 text-left transition"
                    >
                        <strong class="text-charcoal-900 block font-bold text-[11px]">💳 Cashier Access</strong>
                        <span class="text-charcoal-500 text-[10px]">cashier1 / elara123</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Back to Website Link & DATAPOINT Footer -->
        <div class="text-center space-y-4">
            <a href="/" class="inline-flex items-center text-xs font-semibold text-charcoal-500 hover:text-brand-700 transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i>
                <span>Return to Public Web Portal</span>
            </a>

            <!-- Designed, Developed and Hosted By DATAPOINT Technologies -->
            <div class="pt-4 border-t border-brand-200/80 flex flex-col sm:flex-row items-center justify-center gap-2.5 text-[11px] text-charcoal-500">
                <span>Designed, Developed and Hosted By :</span>
                <div class="inline-flex items-center space-x-2 bg-white border border-brand-200 px-2.5 py-1 rounded-xl shadow-2xs">
                    <img src="/assets/images/datapoint_logo.png" alt="DATAPOINT Technologies" class="h-5 w-auto object-contain">
                    <span class="font-extrabold text-charcoal-900 text-[10px]">DATAPOINT Technologies</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('loginPassword');
            const eyeIcon = document.getElementById('passwordEyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function fillCredentials(user, pass) {
            document.getElementById('loginUsername').value = user;
            document.getElementById('loginPassword').value = pass;
        }
    </script>
</body>
</html>
