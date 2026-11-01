<?php
$currentPage = 'portal';
$pageTitle = 'ELARA BEAUTÉ STUDIO | By Elia & Sehar - Haute Beauté & Luxury Day Spa';
$currency = $appConfig['parlor']['currency_symbol'];
$parlor = $appConfig['parlor'];
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
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
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf7f2;
            color: #1c1917;
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-cormorant { font-family: 'Cormorant Garamond', Georgia, serif; }
        .hero-pattern {
            background-color: #141210;
            background-image: radial-gradient(#2b2520 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .video-overlay {
            background: linear-gradient(180deg, rgba(20,18,16,0.3) 0%, rgba(20,18,16,0.85) 100%);
        }
    </style>
</head>
<body class="antialiased selection:bg-brand-200 selection:text-charcoal-900 flex flex-col min-h-screen">

    <!-- Top Luxury Strip: White & Gold with DATAPOINT Blue Accent -->
    <div class="bg-slate-950 text-amber-300 px-4 py-2 text-[11px] border-b border-amber-500/30 tracking-wider">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="font-bold text-white tracking-widest uppercase">ELARA Beauté Studio</span>
                <span class="text-amber-400">&bull;</span>
                <span class="text-amber-200 font-medium">Haute Couture Bridal Season &bull; Luxury Private Suites</span>
            </div>
            <div class="flex items-center space-x-4 text-xs font-semibold">
                <a href="<?= htmlspecialchars($parlor['social']['instagram'] ?? 'https://www.instagram.com/elarabeautestudio/') ?>" target="_blank" class="inline-flex items-center text-amber-300 hover:text-white transition">
                    <i class="fa-brands fa-instagram mr-1.5 text-sm text-pink-400"></i>
                    <span>@elarabeautestudio</span>
                </a>
                <span class="text-slate-700">&bull;</span>
                <a href="https://wa.me/923352004959" target="_blank" class="text-blue-400 hover:text-blue-300 font-bold transition">
                    Concierge Online &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Luxury Portal Navigation Bar with White & Golden Theme + DATAPOINT Black & Blue Accents -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b-2 border-amber-400/70 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-24 sm:h-28">
                
                <!-- Left: Enlarged Official Brand Logo Only (Heading & Address Removed as requested) -->
                <a href="/" class="group flex items-center py-1">
                    <div class="bg-white p-2 rounded-2xl border-2 border-amber-400/80 shadow-sm group-hover:border-blue-600 group-hover:shadow-md transition-all duration-300 shrink-0">
                        <img 
                            src="/assets/images/elara_logo.jpg" 
                            alt="ELARA Logo" 
                            class="h-16 sm:h-20 md:h-24 w-auto object-contain rounded-xl transition duration-300 group-hover:scale-105"
                        >
                    </div>
                </a>

                <!-- Right: ONLY Tabs for Web Portal, POS Portal, Admin Login Switching + WhatsApp Logo Only -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    
                    <!-- Switching Tab: Web Portal (Active) -->
                    <a 
                        href="/" 
                        class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-bold tracking-wide transition flex items-center space-x-2 bg-slate-950 hover:bg-black text-amber-300 border border-amber-400/60 shadow-xs"
                        title="Web Portal (Active Showcase)"
                    >
                        <i class="fa-solid fa-globe text-sm text-blue-400"></i>
                        <span class="hidden sm:inline">Web Portal</span>
                    </a>

                    <!-- Switching Tab: POS Portal -->
                    <a 
                        href="/pos" 
                        class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-bold tracking-wide transition flex items-center space-x-2 bg-white hover:bg-amber-50 text-slate-800 border border-slate-200 hover:border-amber-400 hover:text-amber-700 shadow-2xs"
                        title="POS Cashier Terminal & Invoicing"
                    >
                        <i class="fa-solid fa-cash-register text-sm text-amber-500"></i>
                        <span class="hidden sm:inline">POS Portal</span>
                    </a>

                    <!-- Switching Tab: Admin Login -->
                    <a 
                        href="/login" 
                        class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs font-bold tracking-wide transition flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white border border-blue-500 shadow-xs hover:shadow"
                        title="Staff & Management Login"
                    >
                        <i class="fa-solid fa-user-shield text-sm text-sky-200"></i>
                        <span class="hidden sm:inline">Admin Login</span>
                    </a>

                    <!-- WhatsApp Icon Only (No phone number text, as requested) -->
                    <a 
                        href="https://wa.me/923352004959?text=Salam%20ELARA%20Beaut%C3%A9%20Studio!%20I%20would%20like%20to%20inquire%20for%20bridal%20makeup%20and%20studio%20appointments." 
                        target="_blank"
                        class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center transition shadow-2xs hover:shadow hover:scale-105 border border-emerald-400 shrink-0"
                        title="Chat on WhatsApp"
                    >
                        <i class="fa-brands fa-whatsapp text-lg sm:text-xl"></i>
                    </a>

                </div>

            </div>
        </div>
    </header>

    <!-- HERO SECTION (Modern Aesthetic Light Theme - Pakistani Bridal Photoshoot & Makeup Readiness Showcase) -->
    <section class="relative bg-[#fcfaf7] bg-[radial-gradient(#eadeca_1px,transparent_1px)] [background-size:24px_24px] text-charcoal-900 overflow-hidden py-12 lg:py-20 border-b border-brand-200/90">
        <!-- Soft Warm Champagne & Blush Ambient Light Glow -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-brand-200/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 -right-32 w-96 h-96 bg-brand-100/60 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <!-- LEFT COLUMN: Editorial Typography & Booking CTAs -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white border border-brand-300 text-brand-900 text-xs font-bold tracking-widest uppercase shadow-2xs">
                        <i class="fa-solid fa-crown text-brand-600"></i>
                        <span>HAUTE BEAUTÉ & ROYAL BRIDAL COUTURE &bull; HYDERABAD</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black font-cinzel leading-[1.15] tracking-wide text-charcoal-900">
                        Royal Pakistani Bridal <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-700 via-brand-600 to-amber-700">Glamour & Couture</span>
                    </h1>

                    <p class="text-base sm:text-lg text-charcoal-600 font-cormorant italic leading-relaxed">
                        Curated by <strong>Elia &amp; Sahar</strong>. Transforming Hyderabad brides with 16-hour sweatproof glass-skin formulation, bespoke velvet dupatta draping, royal jewelry anchoring, and handcrafted Rajasthani herbal henna.
                    </p>

                    <!-- Direct CTAs: White, Golden, Black & DATAPOINT Blue -->
                    <div class="pt-2 flex flex-wrap items-center gap-3.5">
                        <a 
                            href="https://wa.me/923352004959?text=Salam%20ELARA%20Studio!%20I%20want%20to%20consult%20for%20an%20upcoming%20wedding/bridal%20date." 
                            target="_blank"
                            class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs tracking-widest uppercase shadow-md shadow-emerald-700/20 transition flex items-center space-x-2 group"
                        >
                            <i class="fa-brands fa-whatsapp text-base group-hover:scale-110 transition"></i>
                            <span>WhatsApp Booking: 0335 2004959</span>
                        </a>

                        <a 
                            href="#bridal" 
                            class="px-5 py-3.5 bg-blue-600 hover:bg-blue-700 text-white border border-amber-300 rounded-xl font-bold text-xs tracking-widest uppercase shadow-md shadow-blue-600/20 transition flex items-center space-x-2"
                        >
                            <span>Explore Bridal Looks</span>
                            <i class="fa-solid fa-arrow-down text-[11px] text-amber-300"></i>
                        </a>

                        <a 
                            href="/pos" 
                            class="px-4 py-3.5 bg-slate-950 hover:bg-black text-amber-300 border border-amber-400/60 rounded-xl font-bold text-xs tracking-widest uppercase shadow-xs transition flex items-center space-x-2"
                        >
                            <i class="fa-solid fa-cash-register text-xs text-amber-400"></i>
                            <span class="hidden sm:inline">POS Terminal</span>
                        </a>
                    </div>

                    <!-- Luxury Studio Trust Badges Strip -->
                    <div class="pt-6 border-t border-brand-200 grid grid-cols-3 gap-4 text-left">
                        <div class="space-y-0.5">
                            <div class="text-2xl sm:text-3xl font-extrabold text-charcoal-900 font-cinzel">1,200+</div>
                            <div class="text-[11px] text-charcoal-500 uppercase tracking-wider font-semibold">Brides Styled</div>
                        </div>
                        <div class="space-y-0.5">
                            <div class="text-2xl sm:text-3xl font-extrabold text-charcoal-900 font-cinzel">100%</div>
                            <div class="text-[11px] text-charcoal-500 uppercase tracking-wider font-semibold">Organic Henna</div>
                        </div>
                        <div class="space-y-0.5">
                            <div class="text-2xl sm:text-3xl font-extrabold text-charcoal-900 font-cinzel">16-Hr</div>
                            <div class="text-[11px] text-charcoal-500 uppercase tracking-wider font-semibold">Flawless Stay</div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Pakistani Bridal Photoshoot & Makeup Readiness Visual Showcase -->
                <div class="lg:col-span-5 relative">
                    
                    <!-- Decorative Soft Golden Frame Glow -->
                    <div class="absolute -inset-2 bg-gradient-to-tr from-brand-200/50 via-brand-100/30 to-brand-300/40 rounded-3xl blur-lg pointer-events-none"></div>

                    <!-- Main Featured Pakistani Bride Showcase Card -->
                    <div class="relative bg-white rounded-3xl overflow-hidden border border-brand-200 shadow-xl group">
                        
                        <!-- Primary High-Res Pakistani Bride Portrait (Embroidered Lehenga, Dupatta & Glamour Makeup) -->
                        <div class="relative h-[440px] sm:h-[500px] overflow-hidden">
                            <img 
                                src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=1000&q=80" 
                                alt="Asian Pakistani Bride Photoshoot - Scarlet Zari Lehenga & Royal Bridal Makeup by ELARA" 
                                class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-700"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/70 via-transparent to-transparent"></div>
                            
                            <!-- Floating Badge: Bridal Couture Highlight -->
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3.5 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider flex items-center space-x-1.5 shadow-md">
                                <i class="fa-solid fa-crown text-brand-600"></i>
                                <span>Royal Barat &amp; Walima Couture</span>
                            </div>

                            <!-- Floating Badge: 16-Hour Airbrush Base -->
                            <div class="absolute top-4 right-4 bg-emerald-50/95 backdrop-blur-md border border-emerald-300 text-emerald-900 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider flex items-center space-x-1.5 shadow-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>16-Hr HD Airbrush</span>
                            </div>

                            <!-- Inset Macro Photo: Backstage Makeup Readiness & Eyes -->
                            <div class="absolute bottom-4 right-4 w-32 sm:w-36 aspect-square rounded-2xl overflow-hidden border-2 border-white shadow-2xl hidden sm:block group/inset hover:scale-110 transition duration-300">
                                <img 
                                    src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=600&q=80" 
                                    alt="Bridal Makeup Readiness & Vanity Mirror Touchup" 
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute inset-0 bg-charcoal-950/30 flex items-end p-1.5">
                                    <span class="text-[9px] font-extrabold text-white uppercase tracking-wider bg-black/75 px-1.5 py-0.5 rounded">Makeup Prep</span>
                                </div>
                            </div>

                            <!-- Bottom Highlights Description -->
                            <div class="absolute bottom-4 left-4 right-36 sm:right-40 text-left space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-300 tracking-widest block">Bridal Artistry Spotlight</span>
                                <h4 class="text-sm sm:text-base font-bold text-white font-cinzel leading-tight">
                                    Scarlet Zari Lehenga &bull; Smoky Kohl Eyes &bull; Matha Patti Setting
                                </h4>
                                <p class="text-[11px] text-charcoal-200 line-clamp-2 font-light">
                                    Signature bridal reveal styled exclusively at Main Qasimabad Road, Hyderabad.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- HERO NAVIGATION TABS RIBBON (White & Golden Theme with DATAPOINT Blue Accents) -->
            <div class="pt-6 border-t border-amber-200/90">
                <div class="text-center mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-[0.25em] text-amber-700">Explore Studio Sanctuaries</span>
                </div>
                <div class="bg-white border-2 border-amber-300/80 rounded-2xl p-2 shadow-xs flex items-center justify-start sm:justify-center space-x-1 sm:space-x-2 overflow-x-auto custom-scrollbar">
                    <a href="#story" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase text-slate-800 hover:text-blue-600 hover:bg-blue-50/70 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-sparkles text-amber-500 text-xs"></i>
                        <span>Our Story</span>
                    </a>
                    <a href="#making-of-elara" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-film text-blue-600 text-xs"></i>
                        <span>Making of ELARA</span>
                    </a>
                    <a href="#bridal" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase text-slate-800 hover:text-blue-600 hover:bg-blue-50/70 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-crown text-amber-500 text-xs"></i>
                        <span>Royal Bridal</span>
                    </a>
                    <a href="#henna" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase text-slate-800 hover:text-blue-600 hover:bg-blue-50/70 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-leaf text-emerald-600 text-xs"></i>
                        <span>Rajasthani Henna</span>
                    </a>
                    <a href="#hair" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase text-slate-800 hover:text-blue-600 hover:bg-blue-50/70 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-scissors text-blue-600 text-xs"></i>
                        <span>Hair Artistry</span>
                    </a>
                    <a href="#services" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase text-slate-800 hover:text-blue-600 hover:bg-blue-50/70 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-gem text-amber-500 text-xs"></i>
                        <span>Treatment Menu</span>
                    </a>
                    <a href="#contact" class="px-3.5 py-2 rounded-xl text-xs font-bold tracking-wider uppercase text-slate-800 hover:text-blue-600 hover:bg-blue-50/70 transition whitespace-nowrap flex items-center space-x-1.5">
                        <i class="fa-solid fa-location-dot text-blue-600 text-xs"></i>
                        <span>Studio Location</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: OUR STORY & FOUNDERS -->
    <section id="story" class="py-20 bg-[#faf7f2]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 relative">
                    <div class="rounded-3xl overflow-hidden border border-brand-200 shadow-xl relative group">
                        <img 
                            src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=80" 
                            alt="Elia & Sehar Studio Atmosphere" 
                            class="w-full h-[480px] object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                            <span class="text-xs uppercase tracking-widest text-brand-300 font-bold mb-1">Couture Sanctuary</span>
                            <h3 class="text-xl font-bold font-cinzel">ELARA Beauté Studio</h3>
                            <p class="text-xs text-charcoal-300">Main Qasimabad Road, Hyderabad, Sindh</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-white p-5 rounded-2xl border border-brand-200 shadow-lg max-w-xs hidden sm:block">
                        <div class="flex items-center space-x-3">
                            <img src="/assets/images/elara_logo.jpg" alt="Crest" class="h-10 w-auto">
                            <div>
                                <h4 class="font-bold text-xs text-charcoal-900 font-cinzel">ELARA BEAUTÉ STUDIO</h4>
                                <p class="text-[10px] text-brand-700">Main Qasimabad Road, Hyderabad</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600 block">The Founders & Philosophy</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-charcoal-900 font-cinzel leading-tight">
                        Crafted by Elia & Sehar for the Discerning Women of Hyderabad
                    </h2>
                    <p class="text-sm text-charcoal-600 leading-relaxed font-normal">
                        Located on the bustling <strong>Main Qasimabad Road near Ali CNG in Hyderabad</strong>, <strong>ELARA BEAUTÉ STUDIO</strong> is the brainchild of <strong>Elia Khan</strong> (Master Hair Architect & Creative Director) and <strong>Sehar Tariq</strong> (Celebrity Bridal & Glamour Artist).
                    </p>
                    <p class="text-sm text-charcoal-600 leading-relaxed font-normal">
                        We blend European hair and aesthetics technology with regal South Asian bridal traditions—from rich, dark Rajasthani herbal henna motifs to luminous airbrushed glass-skin makeup that endures through long Pakistani wedding nights.
                    </p>

                    <div class="grid grid-cols-2 gap-4 pt-4 text-xs">
                        <div class="p-4 bg-white rounded-2xl border border-brand-200 shadow-2xs">
                            <i class="fa-solid fa-gem text-brand-600 text-lg mb-2 block"></i>
                            <strong class="text-charcoal-900 block font-bold">Premium Imports</strong>
                            <span class="text-charcoal-500 text-[11px]">Olaplex, Moroccanoil, Charlotte Tilbury & Dermalogica</span>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-brand-200 shadow-2xs">
                            <i class="fa-solid fa-leaf text-emerald-600 text-lg mb-2 block"></i>
                            <strong class="text-charcoal-900 block font-bold">Safe & Organic</strong>
                            <span class="text-charcoal-500 text-[11px]">Chemical-free henna and hypoallergenic skincare formulations</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION: THE MAKING OF ELARA (DOCUMENTARY SHOWCASE & INSTAGRAM REELS) -->
    <section id="making-of-elara" class="py-24 bg-[#faf7f2] text-charcoal-900 relative overflow-hidden border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14 relative z-10">
            
            <!-- Section Header & Official Instagram Bio Card -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-brand-200 pb-10">
                <div class="space-y-3 max-w-2xl">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-brand-100 border border-brand-300 text-brand-800 text-xs font-bold uppercase tracking-widest">
                        <i class="fa-solid fa-film text-brand-600"></i>
                        <span>Documentary Archives &bull; Instagram Exclusives</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black font-cinzel leading-tight text-charcoal-900">
                        The Making of <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-700 via-amber-700 to-brand-600">ELARA</span>
                    </h2>
                    <p class="text-sm text-charcoal-600 font-normal leading-relaxed">
                        Follow the journey from bare concrete to Hyderabad's premier beauty sanctuary. Explore our Pakistani bridal photoshoot sessions, backstage makeup readiness BTS, organic Rajasthani henna brewing, and royal bride reveals direct from our official Instagram <strong class="text-brand-800 font-bold">@elarabeautestudio</strong>.
                    </p>
                </div>

                <!-- Instagram Account Profile Card -->
                <div class="bg-white border border-brand-200 p-5 rounded-2xl flex items-center space-x-4 shadow-sm">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-full p-[2px] bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600">
                            <img src="/assets/images/elara_logo.jpg" alt="ELARA Instagram" class="w-full h-full object-cover rounded-full bg-white p-0.5">
                        </div>
                        <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <div class="flex items-center space-x-1.5">
                            <span class="font-bold text-sm text-charcoal-900">@elarabeautestudio</span>
                            <i class="fa-solid fa-circle-check text-blue-500 text-xs"></i>
                        </div>
                        <p class="text-[11px] text-charcoal-500">Main Qasimabad Road, Hyderabad</p>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="mt-2 inline-flex items-center space-x-1.5 px-3 py-1 bg-gradient-to-r from-pink-600 via-rose-600 to-amber-600 hover:opacity-90 text-white rounded-lg text-[11px] font-bold tracking-wider uppercase transition shadow-xs"
                        >
                            <i class="fa-brands fa-instagram text-xs"></i>
                            <span>Follow on Instagram</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 6 VIDEO REELS GRID: THE MAKING OF ELARA -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Reel 1: Live Bridal Photoshoot BTS -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-brand-200 hover:border-brand-400 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="relative h-72 overflow-hidden cursor-pointer" onclick="openInstagramReel('https://www.instagram.com/elarabeautestudio/')">
                        <img 
                            src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=700&q=80" 
                            alt="Pakistani Bridal Photoshoot BTS" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-charcoal-950/20 group-hover:bg-charcoal-950/10 transition"></div>
                        
                        <!-- Reel Badge -->
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase flex items-center space-x-1.5 shadow-xs">
                            <i class="fa-brands fa-instagram text-pink-500"></i>
                            <span>Reel 01 &bull; Architecture &bull; Bridal Shoot</span>
                        </div>

                        <!-- Play Button Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-charcoal-900/90 text-brand-300 flex items-center justify-center pl-1 text-lg shadow-xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded font-mono">
                            0:58
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 block mb-1">EPISODE 01 &bull; PAKISTANI BRIDAL SHOOT</span>
                            <h3 class="text-base font-bold text-charcoal-900 font-cinzel leading-snug">
                                Cinematic Barat Bride 4K Photoshoot & Studio Lighting
                            </h3>
                            <p class="text-xs text-charcoal-600 mt-2 line-clamp-3 leading-relaxed">
                                Behind the camera: multi-angle studio strobe tests ensuring scarlet zari lehenga embroidery, kundan jewelry, and 16-hour sweatproof makeup radiate without flash bounce.
                            </p>
                        </div>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="inline-flex items-center text-xs font-bold text-brand-700 hover:text-brand-900 group-hover:translate-x-1 transition space-x-2 pt-2 border-t border-brand-100"
                        >
                            <span>Watch Reel on @elarabeautestudio</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Reel 2: Backstage Makeup Readiness -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-brand-200 hover:border-brand-400 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="relative h-72 overflow-hidden cursor-pointer" onclick="openInstagramReel('https://www.instagram.com/elarabeautestudio/')">
                        <img 
                            src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=700&q=80" 
                            alt="Bridal Makeup Readiness & Vanity Prep" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-charcoal-950/20 group-hover:bg-charcoal-950/10 transition"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase flex items-center space-x-1.5 shadow-xs">
                            <i class="fa-brands fa-instagram text-pink-500"></i>
                            <span>Reel 02 &bull; Makeup Readiness</span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-charcoal-900/90 text-brand-300 flex items-center justify-center pl-1 text-lg shadow-xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded font-mono">
                            1:24
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 block mb-1">EPISODE 02 &bull; MAKEUP READINESS BTS</span>
                            <h3 class="text-base font-bold text-charcoal-900 font-cinzel leading-snug">
                                Backstage Vanity Prep: Skin Priming, Airbrush & Cut-Crease
                            </h3>
                            <p class="text-xs text-charcoal-600 mt-2 line-clamp-3 leading-relaxed">
                                Step-by-step bridal skin prep: moisture barrier lock, micro-contouring, and humidity-proof foundation application formulated to keep Pakistani brides matte and glowing all evening.
                            </p>
                        </div>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="inline-flex items-center text-xs font-bold text-brand-700 hover:text-brand-900 group-hover:translate-x-1 transition space-x-2 pt-2 border-t border-brand-100"
                        >
                            <span>Watch Reel on @elarabeautestudio</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Reel 3: Organic Sojat Henna Lab -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-brand-200 hover:border-brand-400 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="relative h-72 overflow-hidden cursor-pointer" onclick="openInstagramReel('https://www.instagram.com/elarabeautestudio/')">
                        <img 
                            src="https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=700&q=80" 
                            alt="Artisanal Henna Brewing" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-charcoal-950/20 group-hover:bg-charcoal-950/10 transition"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase flex items-center space-x-1.5 shadow-xs">
                            <i class="fa-brands fa-instagram text-pink-500"></i>
                            <span>Reel 03 &bull; Henna Lab</span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-charcoal-900/90 text-brand-300 flex items-center justify-center pl-1 text-lg shadow-xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded font-mono">
                            0:45
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 block mb-1">EPISODE 03 &bull; ORGANIC BOTANICALS</span>
                            <h3 class="text-base font-bold text-charcoal-900 font-cinzel leading-snug">
                                Brewing Sojat Henna: Eucalyptus, Tea-Tree & Hand-Rolled Cones
                            </h3>
                            <p class="text-xs text-charcoal-600 mt-2 line-clamp-3 leading-relaxed">
                                No chemicals, zero PPD. Watch the step-by-step artisanal brewing of our henna paste in the studio lab, creating silky strings and rich mahogany bridal stains that blossom over 48 hours.
                            </p>
                        </div>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="inline-flex items-center text-xs font-bold text-brand-700 hover:text-brand-900 group-hover:translate-x-1 transition space-x-2 pt-2 border-t border-brand-100"
                        >
                            <span>Watch Reel on @elarabeautestudio</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Reel 4: Dupatta Draping & Matha Patti Anchoring -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-brand-200 hover:border-brand-400 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="relative h-72 overflow-hidden cursor-pointer" onclick="openInstagramReel('https://www.instagram.com/elarabeautestudio/')">
                        <img 
                            src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=700&q=80" 
                            alt="Velvet Dupatta Draping & Matha Patti" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-charcoal-950/20 group-hover:bg-charcoal-950/10 transition"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase flex items-center space-x-1.5 shadow-xs">
                            <i class="fa-brands fa-instagram text-pink-500"></i>
                            <span>Reel 04 &bull; Dupatta & Bridal Reveal</span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-charcoal-900/90 text-brand-300 flex items-center justify-center pl-1 text-lg shadow-xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded font-mono">
                            1:10
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 block mb-1">EPISODE 04 &bull; DUPATTA & JEWELRY ARCHITECTURE</span>
                            <h3 class="text-base font-bold text-charcoal-900 font-cinzel leading-snug">
                                Heavy Velvet Dupatta Draping & Matha Patti Anchoring
                            </h3>
                            <p class="text-xs text-charcoal-600 mt-2 line-clamp-3 leading-relaxed">
                                The poignant moments before walking down the aisle. Sahar anchors heavy velvet bridal dupattas with specialized weight-distribution techniques for effortless regal posture.
                            </p>
                        </div>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="inline-flex items-center text-xs font-bold text-brand-700 hover:text-brand-900 group-hover:translate-x-1 transition space-x-2 pt-2 border-t border-brand-100"
                        >
                            <span>Watch Reel on @elarabeautestudio</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Reel 5: French Balayage & Bridal Hair Prep -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-brand-200 hover:border-brand-400 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="relative h-72 overflow-hidden cursor-pointer" onclick="openInstagramReel('https://www.instagram.com/elarabeautestudio/')">
                        <img 
                            src="https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=700&q=80" 
                            alt="Hair Artistry & French Balayage" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-charcoal-950/20 group-hover:bg-charcoal-950/10 transition"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase flex items-center space-x-1.5 shadow-xs">
                            <i class="fa-brands fa-instagram text-pink-500"></i>
                            <span>Reel 05 &bull; Hair Architecture</span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-charcoal-900/90 text-brand-300 flex items-center justify-center pl-1 text-lg shadow-xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded font-mono">
                            0:52
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 block mb-1">EPISODE 05 &bull; FRENCH BALAYAGE</span>
                            <h3 class="text-base font-bold text-charcoal-900 font-cinzel leading-snug">
                                Dimensional Ash Caramel Balayage & Olaplex Bonding
                            </h3>
                            <p class="text-xs text-charcoal-600 mt-2 line-clamp-3 leading-relaxed">
                                Elia Khan demonstrates free-hand clay balayage painting tailored for dark Asian hair shafts. Preserving cuticle health with Olaplex No. 1 and finish styling with bouncy French blowouts.
                            </p>
                        </div>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="inline-flex items-center text-xs font-bold text-brand-700 hover:text-brand-900 group-hover:translate-x-1 transition space-x-2 pt-2 border-t border-brand-100"
                        >
                            <span>Watch Reel on @elarabeautestudio</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Reel 6: Daylight Bridal Suites & Studio Tour -->
                <div class="group bg-white rounded-3xl overflow-hidden border border-brand-200 hover:border-brand-400 transition-all duration-300 shadow-sm hover:shadow-xl flex flex-col">
                    <div class="relative h-72 overflow-hidden cursor-pointer" onclick="openInstagramReel('https://www.instagram.com/elarabeautestudio/')">
                        <img 
                            src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=700&q=80" 
                            alt="Daylight Bridal Suites & Studio Tour" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                        >
                        <div class="absolute inset-0 bg-charcoal-950/20 group-hover:bg-charcoal-950/10 transition"></div>
                        
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-brand-200 text-charcoal-900 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase flex items-center space-x-1.5 shadow-xs">
                            <i class="fa-brands fa-instagram text-pink-500"></i>
                            <span>Reel 06 &bull; Daylight Suites</span>
                        </div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-charcoal-900/90 text-brand-300 flex items-center justify-center pl-1 text-lg shadow-xl group-hover:scale-110 group-hover:bg-brand-600 group-hover:text-white transition">
                                <i class="fa-solid fa-play"></i>
                            </div>
                        </div>

                        <div class="absolute bottom-3 right-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] px-2 py-0.5 rounded font-mono">
                            1:35
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-brand-700 block mb-1">EPISODE 06 &bull; STUDIO SANCTUARY</span>
                            <h3 class="text-base font-bold text-charcoal-900 font-cinzel leading-snug">
                                High-CRI Daylight Vanity Suites on Main Qasimabad Road
                            </h3>
                            <p class="text-xs text-charcoal-600 mt-2 line-clamp-3 leading-relaxed">
                                Take a tour through ELARA's private bridal suites. Engineered with 95+ CRI glare-free makeup stations, private changing chambers, and luxury seating for wedding families.
                            </p>
                        </div>
                        <a 
                            href="https://www.instagram.com/elarabeautestudio/" 
                            target="_blank" 
                            class="inline-flex items-center text-xs font-bold text-brand-700 hover:text-brand-900 group-hover:translate-x-1 transition space-x-2 pt-2 border-t border-brand-100"
                        >
                            <span>Watch Reel on @elarabeautestudio</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- INSTAGRAM PHOTO STREAM CAROUSEL & CTA -->
            <div class="bg-white border border-brand-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-700 block mb-1">Instagram Visual Stream</span>
                        <h3 class="text-xl sm:text-2xl font-bold font-cinzel text-charcoal-900">Moments Captured at @elarabeautestudio</h3>
                    </div>
                    <a 
                        href="https://www.instagram.com/elarabeautestudio/" 
                        target="_blank" 
                        class="px-5 py-2.5 bg-gradient-to-r from-pink-600 via-rose-600 to-amber-600 hover:opacity-90 text-white rounded-xl text-xs font-bold tracking-wider uppercase transition shadow-xs inline-flex items-center space-x-2 self-start sm:self-auto"
                    >
                        <i class="fa-brands fa-instagram text-sm"></i>
                        <span>View All Posts & Highlights</span>
                    </a>
                </div>

                <!-- 6 Photo Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden border border-brand-200 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=400&q=80" alt="Makeup Readiness BTS" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </div>
                    </a>
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden border border-brand-200 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=400&q=80" alt="Pakistani Bridal Shoot" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </div>
                    </a>
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden border border-brand-200 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=400&q=80" alt="Dupatta Draping & Matha Patti" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </div>
                    </a>
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden border border-brand-200 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=400&q=80" alt="Bridal Hair & Balayage" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </div>
                    </a>
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden border border-brand-200 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=400&q=80" alt="Botanical Henna Brewing" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </div>
                    </a>
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden border border-brand-200 shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=400&q=80" alt="Daylight Vanity Setup" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: BRIDAL COUTURE GLAMOUR -->
    <section id="bridal" class="py-20 bg-white border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600">The Bridal Suite</span>
                <h2 class="text-3xl sm:text-4xl font-black text-charcoal-900 font-cinzel">
                    Royal Pakistani Bridal Artistry
                </h2>
                <p class="text-xs sm:text-sm text-charcoal-500 font-medium">
                    Every bride deserves an imperial glow. Designed specifically for Barat, Walima, Nikkah, and Mehndi ceremonies.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Barat Bride -->
                <div class="group rounded-3xl overflow-hidden border border-brand-200 bg-[#faf7f2] shadow-2xs hover:shadow-lg transition">
                    <div class="h-80 overflow-hidden relative">
                        <img 
                            src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=600&q=80" 
                            alt="Pakistani Barat Bridal Lehenga Glamour" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        >
                        <span class="absolute top-4 right-4 bg-charcoal-900/90 text-brand-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-brand-400/30 backdrop-blur-sm">
                            Royal Barat
                        </span>
                    </div>
                    <div class="p-6 space-y-3">
                        <h3 class="font-bold text-lg text-charcoal-900 font-cinzel">Royal Barat Couture Glamour</h3>
                        <p class="text-xs text-charcoal-500 leading-relaxed">
                            Ultra-HD airbrush formulation, statement smoky or classic gold eyes, 3D mink lashes, royal bridal updo styling, dupatta setting & jewelry anchoring.
                        </p>
                        <div class="pt-3 border-t border-brand-200 flex items-center justify-between">
                            <span class="text-sm font-black text-charcoal-900">From <?= $currency ?>65,000</span>
                            <a href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>?text=Salam%20Sehar!%20I%20would%20like%20to%20book%20the%20Royal%20Barat%20Bridal%20package." target="_blank" class="text-xs font-bold text-brand-700 hover:text-brand-900">
                                Inquire &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Walima Bride -->
                <div class="group rounded-3xl overflow-hidden border border-brand-200 bg-[#faf7f2] shadow-2xs hover:shadow-lg transition">
                    <div class="h-80 overflow-hidden relative">
                        <img 
                            src="https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=600&q=80" 
                            alt="Walima Reception Glow" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        >
                        <span class="absolute top-4 right-4 bg-charcoal-900/90 text-brand-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-brand-400/30 backdrop-blur-sm">
                            Walima Glam
                        </span>
                    </div>
                    <div class="p-6 space-y-3">
                        <h3 class="font-bold text-lg text-charcoal-900 font-cinzel">Walima Dewy Glow & Waves</h3>
                        <p class="text-xs text-charcoal-500 leading-relaxed">
                            Soft pastels, luminous glass skin, champagne highlighter contouring, and textured Hollywood waves or romantic low textured bun.
                        </p>
                        <div class="pt-3 border-t border-brand-200 flex items-center justify-between">
                            <span class="text-sm font-black text-charcoal-900">From <?= $currency ?>55,000</span>
                            <a href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>?text=Salam%20Sehar!%20I%20want%20to%20inquire%20for%20Walima%20Bridal%20Makeup." target="_blank" class="text-xs font-bold text-brand-700 hover:text-brand-900">
                                Inquire &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Nikkah / Engagement -->
                <div class="group rounded-3xl overflow-hidden border border-brand-200 bg-[#faf7f2] shadow-2xs hover:shadow-lg transition">
                    <div class="h-80 overflow-hidden relative">
                        <img 
                            src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80" 
                            alt="Nikkah Soft Glam & Matha Patti" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        >
                        <span class="absolute top-4 right-4 bg-charcoal-900/90 text-brand-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-brand-400/30 backdrop-blur-sm">
                            Nikkah & Engagement
                        </span>
                    </div>
                    <div class="p-6 space-y-3">
                        <h3 class="font-bold text-lg text-charcoal-900 font-cinzel">Nikkah Soft Radiance</h3>
                        <p class="text-xs text-charcoal-500 leading-relaxed">
                            Subtle rosy undertones, feather-light lashes, sculpted lips, and elegant dupatta pinning for daytime ceremonies.
                        </p>
                        <div class="pt-3 border-t border-brand-200 flex items-center justify-between">
                            <span class="text-sm font-black text-charcoal-900">From <?= $currency ?>25,000</span>
                            <a href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>?text=Salam%20Sehar!%20I%20want%20to%20book%20Nikkah/Engagement%20Makeup." target="_blank" class="text-xs font-bold text-brand-700 hover:text-brand-900">
                                Inquire &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: HENNA & MEHENDI ARTISTRY -->
    <section id="henna" class="py-20 bg-[#faf7f2] border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600 block">Artisan Tradition</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-charcoal-900 font-cinzel leading-tight">
                        Organic Rajasthani Henna & Intricate Mehendi Motifs
                    </h2>
                    <p class="text-sm text-charcoal-600 leading-relaxed">
                        Handcrafted by senior artist <strong>Farheen Noor</strong>, our henna is 100% natural and freshly brewed using premium Rajasthani leaves, eucalyptus, and tea tree oil for a deep, long-lasting mahogany stain.
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-800 flex items-center justify-center text-xs font-bold mt-0.5">✓</div>
                            <div>
                                <strong class="text-xs font-bold text-charcoal-900 block">Royal Bridal Suite:</strong>
                                <span class="text-xs text-charcoal-500">Intricate portraits, jali work, bridal palanquins, and peacock motifs up to elbows and feet (<?= $currency ?>16,000).</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 rounded-full bg-brand-100 text-brand-800 flex items-center justify-center text-xs font-bold mt-0.5">✓</div>
                            <div>
                                <strong class="text-xs font-bold text-charcoal-900 block">Contemporary Arabic & Indo-Western:</strong>
                                <span class="text-xs text-charcoal-500">Flowing floral bel, negative space mandalas, and finger jewelry designs (from <?= $currency ?>4,500).</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a 
                            href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>?text=Salam!%20I%20want%20to%20book%20a%20Bridal%20Henna/Mehendi%20session%20at%20ELARA%20Studio." 
                            target="_blank"
                            class="inline-flex items-center px-6 py-3 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/30 rounded-xl text-xs font-bold tracking-wider uppercase shadow-xs transition"
                        >
                            <i class="fa-brands fa-whatsapp mr-2 text-brand-400 text-sm"></i>
                            <span>Reserve Bridal Henna Slot</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl overflow-hidden border border-brand-200 shadow-sm h-64">
                        <img 
                            src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80" 
                            alt="Bridal Henna Hands" 
                            class="w-full h-full object-cover hover:scale-105 transition duration-300"
                        >
                    </div>
                    <div class="rounded-2xl overflow-hidden border border-brand-200 shadow-sm h-64 mt-6">
                        <img 
                            src="https://images.unsplash.com/photo-1596704017254-9b121068fb31?auto=format&fit=crop&w=600&q=80" 
                            alt="Traditional Henna Art" 
                            class="w-full h-full object-cover hover:scale-105 transition duration-300"
                        >
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: HAIR ARTISTRY & STYLING -->
    <section id="hair" class="py-20 bg-white border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600">Hair Studio by Elia Khan</span>
                <h2 class="text-3xl sm:text-4xl font-black text-charcoal-900 font-cinzel">
                    Haute Hair Architecture & Color
                </h2>
                <p class="text-xs sm:text-sm text-charcoal-500 font-medium">
                    From deep restructuring Brazilian keratin to hand-painted balayage and voluminous bounce blowdrys.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Service Card 1 -->
                <div class="p-5 rounded-3xl bg-[#faf7f2] border border-brand-200 shadow-2xs space-y-3">
                    <div class="h-44 rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1560869713-7d0a29430803?auto=format&fit=crop&w=500&q=80" alt="Keratin Smoothing" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-sm text-charcoal-900">Brazilian Keratin Smoothing</h3>
                    <p class="text-xs text-charcoal-500">Formaldehyde-free intense silk smoothing lasting 4-6 months.</p>
                    <div class="pt-2 font-black text-sm text-brand-700"><?= $currency ?>24,000</div>
                </div>

                <!-- Service Card 2 -->
                <div class="p-5 rounded-3xl bg-[#faf7f2] border border-brand-200 shadow-2xs space-y-3">
                    <div class="h-44 rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=500&q=80" alt="Balayage & Gloss" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-sm text-charcoal-900">Balayage & Bespoke Gloss</h3>
                    <p class="text-xs text-charcoal-500">Seamless sun-kissed blending with Olaplex bond reinforcement.</p>
                    <div class="pt-2 font-black text-sm text-brand-700"><?= $currency ?>32,000</div>
                </div>

                <!-- Service Card 3 -->
                <div class="p-5 rounded-3xl bg-[#faf7f2] border border-brand-200 shadow-2xs space-y-3">
                    <div class="h-44 rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=500&q=80" alt="Crown Cut & Blowdry" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-sm text-charcoal-900">Crown Cut & Bouncy Blowdry</h3>
                    <p class="text-xs text-charcoal-500">Customized face-framing layer cut with scalp massage & blowout.</p>
                    <div class="pt-2 font-black text-sm text-brand-700"><?= $currency ?>4,500</div>
                </div>

                <!-- Service Card 4 -->
                <div class="p-5 rounded-3xl bg-[#faf7f2] border border-brand-200 shadow-2xs space-y-3">
                    <div class="h-44 rounded-2xl overflow-hidden mb-3">
                        <img src="https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=500&q=80" alt="Nail Bar & Pedicure" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-sm text-charcoal-900">Deluxe Velvet Manicure & Spa</h3>
                    <p class="text-xs text-charcoal-500">Rose foot soak, Russian cuticle care, and chip-free gel polish.</p>
                    <div class="pt-2 font-black text-sm text-brand-700"><?= $currency ?>3,500</div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: TREATMENT MENU & PKR RATES -->
    <section id="services" class="py-20 bg-[#faf7f2] border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600 block">Interactive Studio Menu</span>
                    <h2 class="text-3xl font-extrabold text-charcoal-900 font-cinzel">
                        Treatments & Rates (PKR)
                    </h2>
                </div>
                <div class="flex items-center space-x-2 text-xs font-bold">
                    <button onclick="filterMenu('all')" class="menu-tab px-3 py-1.5 rounded-lg bg-charcoal-900 text-brand-300 border border-brand-400/30 transition">All</button>
                    <button onclick="filterMenu('Bridal & Glam')" class="menu-tab px-3 py-1.5 rounded-lg bg-white text-charcoal-700 border border-brand-200 transition">Bridal</button>
                    <button onclick="filterMenu('Henna & Mehendi')" class="menu-tab px-3 py-1.5 rounded-lg bg-white text-charcoal-700 border border-brand-200 transition">Henna</button>
                    <button onclick="filterMenu('Hair Care')" class="menu-tab px-3 py-1.5 rounded-lg bg-white text-charcoal-700 border border-brand-200 transition">Hair</button>
                    <button onclick="filterMenu('Skin & Facial')" class="menu-tab px-3 py-1.5 rounded-lg bg-white text-charcoal-700 border border-brand-200 transition">Facials</button>
                    <button onclick="filterMenu('Massage & Body')" class="menu-tab px-3 py-1.5 rounded-lg bg-white text-charcoal-700 border border-brand-200 transition">Spa</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($services as $srv): ?>
                <div class="menu-item bg-white p-5 rounded-3xl border border-brand-200 shadow-2xs hover:shadow-md transition flex flex-col justify-between" data-category="<?= htmlspecialchars($srv['category'] ?? 'General') ?>">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-brand-50 text-brand-800 border border-brand-200">
                                <?= htmlspecialchars($srv['category']) ?>
                            </span>
                            <span class="text-[11px] text-charcoal-400 font-semibold">
                                <i class="fa-regular fa-clock mr-1 text-brand-600"></i><?= (int)($srv['duration'] ?? 45) ?> mins
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-charcoal-900 mb-1 leading-snug">
                            <?= htmlspecialchars($srv['name']) ?>
                        </h4>
                        <p class="text-xs text-charcoal-500 leading-relaxed">
                            <?= htmlspecialchars($srv['description'] ?? '') ?>
                        </p>
                    </div>

                    <div class="pt-4 mt-3 border-t border-brand-100 flex items-center justify-between">
                        <span class="text-base font-black text-charcoal-900">
                            <?= $currency ?><?= number_format((float)$srv['price'], 0) ?>
                        </span>
                        <a 
                            href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>?text=Salam%20ELARA%20Studio!%20I%20would%20like%20to%20book%20the%20'<?= urlencode($srv['name']) ?>'%20treatment%20(<?= urlencode($currency . number_format($srv['price'], 0)) ?>)." 
                            target="_blank"
                            class="px-3 py-1.5 bg-brand-50 hover:bg-brand-600 hover:text-white text-brand-800 border border-brand-200 rounded-xl text-xs font-bold transition flex items-center"
                        >
                            <i class="fa-brands fa-whatsapp mr-1 text-emerald-600 text-xs"></i>
                            <span>Book Now</span>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- SECTION: INSTAGRAM FEED & SOCIAL SHOWCASE -->
    <section id="gallery" class="py-20 bg-charcoal-950 text-white border-t border-brand-900/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-pink-500/15 border border-pink-400/30 text-pink-300 text-xs font-bold tracking-widest uppercase">
                        <i class="fa-brands fa-instagram text-sm text-pink-400"></i>
                        <span>@elarabeautestudio &bull; Live Studio Feed</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold font-cinzel text-white">
                        Follow Our Journey on Instagram
                    </h2>
                    <p class="text-xs sm:text-sm text-brand-200/80 font-medium max-w-xl">
                        Discover daily bridal transformations, behind-the-scenes henna craft, and bespoke hair couture in Hyderabad.
                    </p>
                </div>

                <a 
                    href="https://www.instagram.com/elarabeautestudio/" 
                    target="_blank"
                    class="px-5 py-3 bg-gradient-to-r from-purple-600 via-pink-600 to-amber-600 hover:opacity-90 text-white rounded-xl text-xs font-bold tracking-wider uppercase shadow-md transition flex items-center space-x-2 self-start md:self-auto"
                >
                    <i class="fa-brands fa-instagram text-base"></i>
                    <span>Follow @elarabeautestudio</span>
                </a>
            </div>

            <!-- Visual 6-Card Instagram Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                
                <!-- Insta Post 1 -->
                <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-brand-400/20 shadow-md block">
                    <img src="https://images.unsplash.com/photo-1546804784-896d0dca3805?auto=format&fit=crop&w=400&q=80" alt="Royal Barat Bridal" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center p-2 text-center text-white text-xs">
                        <i class="fa-brands fa-instagram text-2xl mb-1 text-pink-400"></i>
                        <span class="font-bold text-[11px]">Royal Barat Bride</span>
                        <span class="text-[10px] text-brand-300 mt-1"><i class="fa-solid fa-heart mr-1 text-rose-500"></i> 1,480</span>
                    </div>
                    <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black/60 text-white flex items-center justify-center text-[10px]">
                        <i class="fa-solid fa-play"></i>
                    </div>
                </a>

                <!-- Insta Post 2 -->
                <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-brand-400/20 shadow-md block">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=400&q=80" alt="Bridal Henna Art" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center p-2 text-center text-white text-xs">
                        <i class="fa-brands fa-instagram text-2xl mb-1 text-pink-400"></i>
                        <span class="font-bold text-[11px]">Rajasthani Mehendi</span>
                        <span class="text-[10px] text-brand-300 mt-1"><i class="fa-solid fa-heart mr-1 text-rose-500"></i> 940</span>
                    </div>
                </a>

                <!-- Insta Post 3 -->
                <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-brand-400/20 shadow-md block">
                    <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=400&q=80" alt="Balayage Transformation" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center p-2 text-center text-white text-xs">
                        <i class="fa-brands fa-instagram text-2xl mb-1 text-pink-400"></i>
                        <span class="font-bold text-[11px]">Bespoke Balayage</span>
                        <span class="text-[10px] text-brand-300 mt-1"><i class="fa-solid fa-heart mr-1 text-rose-500"></i> 1,120</span>
                    </div>
                    <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black/60 text-white flex items-center justify-center text-[10px]">
                        <i class="fa-solid fa-play"></i>
                    </div>
                </a>

                <!-- Insta Post 4 -->
                <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-brand-400/20 shadow-md block">
                    <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=400&q=80" alt="24K Gold Facial" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center p-2 text-center text-white text-xs">
                        <i class="fa-brands fa-instagram text-2xl mb-1 text-pink-400"></i>
                        <span class="font-bold text-[11px]">24K Gold Glow</span>
                        <span class="text-[10px] text-brand-300 mt-1"><i class="fa-solid fa-heart mr-1 text-rose-500"></i> 780</span>
                    </div>
                </a>

                <!-- Insta Post 5 -->
                <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-brand-400/20 shadow-md block">
                    <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=400&q=80" alt="Nikkah Soft Glam" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center p-2 text-center text-white text-xs">
                        <i class="fa-brands fa-instagram text-2xl mb-1 text-pink-400"></i>
                        <span class="font-bold text-[11px]">Nikkah Radiance</span>
                        <span class="text-[10px] text-brand-300 mt-1"><i class="fa-solid fa-heart mr-1 text-rose-500"></i> 1,650</span>
                    </div>
                </a>

                <!-- Insta Post 6 -->
                <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="group relative rounded-2xl overflow-hidden aspect-square border border-brand-400/20 shadow-md block">
                    <img src="https://images.unsplash.com/photo-1632345031435-8727f6897d53?auto=format&fit=crop&w=400&q=80" alt="Gel Nail Architecture" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col items-center justify-center p-2 text-center text-white text-xs">
                        <i class="fa-brands fa-instagram text-2xl mb-1 text-pink-400"></i>
                        <span class="font-bold text-[11px]">Velvet Gel Nails</span>
                        <span class="text-[10px] text-brand-300 mt-1"><i class="fa-solid fa-heart mr-1 text-rose-500"></i> 690</span>
                    </div>
                </a>

            </div>

            <!-- Social Media Channels Bar -->
            <div class="pt-6 border-t border-brand-400/20 flex flex-wrap items-center justify-between gap-4 text-xs">
                <div class="text-charcoal-400">
                    Connect with ELARA on all official channels:
                </div>
                <div class="flex items-center space-x-3">
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="px-3.5 py-1.5 bg-[#25211d] hover:bg-pink-900/40 text-brand-200 border border-brand-400/30 rounded-xl transition flex items-center">
                        <i class="fa-brands fa-instagram mr-1.5 text-pink-400"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="<?= htmlspecialchars($parlor['social']['facebook'] ?? 'https://www.facebook.com/elarabeautestudio') ?>" target="_blank" class="px-3.5 py-1.5 bg-[#25211d] hover:bg-blue-900/40 text-brand-200 border border-brand-400/30 rounded-xl transition flex items-center">
                        <i class="fa-brands fa-facebook-f mr-1.5 text-blue-400"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="<?= htmlspecialchars($parlor['social']['tiktok'] ?? 'https://www.tiktok.com/@elarabeautestudio') ?>" target="_blank" class="px-3.5 py-1.5 bg-[#25211d] hover:bg-neutral-800 text-brand-200 border border-brand-400/30 rounded-xl transition flex items-center">
                        <i class="fa-brands fa-tiktok mr-1.5 text-white"></i>
                        <span>TikTok</span>
                    </a>
                    <a href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>" target="_blank" class="px-3.5 py-1.5 bg-[#25211d] hover:bg-emerald-900/40 text-brand-200 border border-brand-400/30 rounded-xl transition flex items-center">
                        <i class="fa-brands fa-whatsapp mr-1.5 text-emerald-400"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: STUDIO REVIEWS & TESTIMONIALS -->
    <section class="py-20 bg-white border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-xl mx-auto space-y-2">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-brand-600">Guest Experiences</span>
                <h2 class="text-3xl font-extrabold text-charcoal-900 font-cinzel">What Our Clients Say</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-[#faf7f2] border border-brand-200 space-y-3">
                    <div class="text-amber-500 text-xs space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-charcoal-600 italic leading-relaxed">
                        "Sehar gave me the exact royal Barat look I had dreamed of for years. The airbrush stayed flawless through the entire photoshoot and stage lighting. Truly Hyderabad's finest bridal studio!"
                    </p>
                    <div class="pt-2 border-t border-brand-200">
                        <strong class="text-xs font-bold text-charcoal-900 block">Mahnoor Bilal</strong>
                        <span class="text-[10px] text-brand-700">Barat Bride &bull; Qasimabad, Hyderabad</span>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-[#faf7f2] border border-brand-200 space-y-3">
                    <div class="text-amber-500 text-xs space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-charcoal-600 italic leading-relaxed">
                        "Farheen's organic henna stain was so rich and dark! No chemicals, no itching, just pure herbal fragrance and stunning Mughlai motifs. Everyone asked me where I got my mehndi done."
                    </p>
                    <div class="pt-2 border-t border-brand-200">
                        <strong class="text-xs font-bold text-charcoal-900 block">Zainab Al-Hamdani</strong>
                        <span class="text-[10px] text-brand-700">Mehendi Bride &bull; Hyderabad</span>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-[#faf7f2] border border-brand-200 space-y-3">
                    <div class="text-amber-500 text-xs space-x-1">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-xs text-charcoal-600 italic leading-relaxed">
                        "Elia is a magician with hair! My balayage tone is so seamless and the keratin smoothing transformed my frizzy hair into mirror-like silk. Highly recommend their bespoke treatments."
                    </p>
                    <div class="pt-2 border-t border-brand-200">
                        <strong class="text-xs font-bold text-charcoal-900 block">Ayla Jehangir</strong>
                        <span class="text-[10px] text-brand-700">Balayage Client &bull; Hyderabad</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION: STUDIO LOCATION & CONTACT -->
    <section id="contact" class="py-20 bg-[#faf7f2] text-charcoal-900 border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center space-x-3 bg-white p-2.5 rounded-2xl border border-brand-200 shadow-xs">
                        <img src="/assets/images/elara_logo.jpg" alt="ELARA Logo" class="h-12 w-auto rounded-lg border border-brand-200">
                        <div>
                            <span class="font-black font-cinzel text-base tracking-wider text-charcoal-900 block">ELARA BEAUTÉ STUDIO</span>
                            <span class="text-brand-700 block text-[11px] font-bold uppercase">BY ELIA &amp; SAHAR</span>
                        </div>
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-bold font-cinzel text-charcoal-900 leading-tight">
                        Visit Our Private Studio on Main Qasimabad Road, Hyderabad
                    </h2>

                    <div class="space-y-4 text-xs text-charcoal-600 leading-relaxed">
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-location-dot text-brand-700 text-sm mt-0.5"></i>
                            <div>
                                <strong class="text-charcoal-900">Address:</strong><br>
                                <?= htmlspecialchars($parlor['address']) ?>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-phone text-brand-700 text-sm mt-0.5"></i>
                            <div>
                                <strong class="text-charcoal-900">Direct Concierge:</strong><br>
                                <?= htmlspecialchars($parlor['phone']) ?>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i class="fa-brands fa-whatsapp text-emerald-600 text-sm mt-0.5"></i>
                            <div>
                                <strong class="text-charcoal-900">WhatsApp Bookings:</strong><br>
                                <?= htmlspecialchars($parlor['whatsapp']) ?>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-clock text-brand-700 text-sm mt-0.5"></i>
                            <div>
                                <strong class="text-charcoal-900">Operating Hours:</strong><br>
                                Tuesday to Sunday: 11:00 AM – 8:30 PM<br>
                                <span class="text-brand-800 italic">Mondays reserved exclusively for VIP bridal bookings.</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <a 
                            href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>" 
                            target="_blank"
                            class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold tracking-wider uppercase shadow-xs transition flex items-center"
                        >
                            <i class="fa-brands fa-whatsapp mr-2 text-base"></i>
                            <span>Chat on WhatsApp</span>
                        </a>
                        <a 
                            href="/dashboard" 
                            class="px-5 py-3 bg-white hover:bg-brand-50 text-brand-800 border border-brand-200 rounded-xl text-xs font-bold tracking-wider uppercase transition shadow-2xs"
                        >
                            Management Portal
                        </a>
                    </div>
                </div>

                <!-- Studio Map / Visual Representation Card -->
                <div class="lg:col-span-6 bg-white p-7 sm:p-8 rounded-3xl border border-brand-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-brand-800 uppercase tracking-widest font-cinzel">Direct Appointment Request</h3>
                    <form onsubmit="handlePortalBooking(event)" class="space-y-3.5 text-xs">
                        <div>
                            <label class="block text-charcoal-700 font-semibold mb-1">Your Full Name</label>
                            <input type="text" id="bName" required placeholder="e.g. Mahnoor Bilal" class="w-full px-3.5 py-2.5 bg-[#faf7f2] border border-brand-200 rounded-xl text-charcoal-900 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:bg-white transition">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-charcoal-700 font-semibold mb-1">WhatsApp Mobile</label>
                                <input type="text" id="bPhone" required placeholder="0335 2004959" class="w-full px-3.5 py-2.5 bg-[#faf7f2] border border-brand-200 rounded-xl text-charcoal-900 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:bg-white transition">
                            </div>
                            <div>
                                <label class="block text-charcoal-700 font-semibold mb-1">Preferred Date</label>
                                <input type="date" id="bDate" required class="w-full px-3.5 py-2.5 bg-[#faf7f2] border border-brand-200 rounded-xl text-charcoal-900 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:bg-white transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-charcoal-700 font-semibold mb-1">Desired Service / Package</label>
                            <select id="bService" class="w-full px-3.5 py-2.5 bg-[#faf7f2] border border-brand-200 rounded-xl text-charcoal-900 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:bg-white transition">
                                <option value="Royal Barat Couture Makeup (Rs. 65,000)">Royal Barat Couture Makeup (Rs. 65,000)</option>
                                <option value="Walima Reception Glamour (Rs. 55,000)">Walima Reception Glamour (Rs. 55,000)</option>
                                <option value="Royal Bridal Henna / Mehendi Suite (Rs. 16,000)">Royal Bridal Henna Suite (Rs. 16,000)</option>
                                <option value="Brazilian Keratin Smoothing (Rs. 24,000)">Brazilian Keratin Smoothing (Rs. 24,000)</option>
                                <option value="Balayage & Gloss (Rs. 32,000)">Balayage & Gloss (Rs. 32,000)</option>
                                <option value="Signature 24K Gold Facial (Rs. 8,500)">Signature 24K Gold Facial (Rs. 8,500)</option>
                                <option value="Hydra-Oxygenating Skin Rebirth (Rs. 12,000)">Hydra-Oxygenating Skin Rebirth (Rs. 12,000)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold tracking-wider uppercase transition shadow-xs">
                            Submit & Open WhatsApp Confirmation
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#fcfaf7] text-charcoal-600 py-12 text-xs border-t border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Top Justified Row: Brand Identity & Social Links -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-brand-200">
                <!-- Left Hand Side: Logo kept close to Heading & Subheading -->
                <div class="flex items-center space-x-3 text-left self-start md:self-auto">
                    <div class="bg-white p-1.5 rounded-2xl border border-brand-200 shadow-xs shrink-0 flex items-center justify-center">
                        <img src="/assets/images/elara_logo.jpg" alt="ELARA Logo" class="h-11 sm:h-12 w-auto object-contain rounded-lg">
                    </div>
                    <div class="leading-tight">
                        <h3 class="font-black text-charcoal-900 text-base sm:text-xl tracking-wider font-cinzel whitespace-nowrap">
                            ELARA BEAUTÉ STUDIO
                        </h3>
                        <p class="text-brand-800 text-xs font-bold tracking-wider uppercase mt-1">
                            By Elia &amp; Sahar
                        </p>
                    </div>
                </div>

                <!-- Right Hand Side: Social Links (Justified) -->
                <div class="flex items-center space-x-3 self-end md:self-auto">
                    <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="w-10 h-10 rounded-xl bg-white hover:bg-gradient-to-r hover:from-pink-600 hover:to-rose-600 text-charcoal-700 hover:text-white flex items-center justify-center transition border border-brand-200 shadow-xs" title="Instagram @elarabeautestudio">
                        <i class="fa-brands fa-instagram text-base"></i>
                    </a>
                    <a href="<?= htmlspecialchars($parlor['social']['facebook'] ?? 'https://www.facebook.com/elarabeautestudio') ?>" target="_blank" class="w-10 h-10 rounded-xl bg-white hover:bg-blue-600 text-charcoal-700 hover:text-white flex items-center justify-center transition border border-brand-200 shadow-xs" title="Facebook">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="<?= htmlspecialchars($parlor['social']['tiktok'] ?? 'https://www.tiktok.com/@elarabeautestudio') ?>" target="_blank" class="w-10 h-10 rounded-xl bg-white hover:bg-black text-charcoal-700 hover:text-white flex items-center justify-center transition border border-brand-200 shadow-xs" title="TikTok">
                        <i class="fa-brands fa-tiktok text-sm"></i>
                    </a>
                    <a href="https://wa.me/<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>" target="_blank" class="w-10 h-10 rounded-xl bg-white hover:bg-emerald-600 text-charcoal-700 hover:text-white flex items-center justify-center transition border border-brand-200 shadow-xs" title="WhatsApp 0335 2004959">
                        <i class="fa-brands fa-whatsapp text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Middle Justified Row: Studio Location & Concierge Instagram -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-charcoal-600">
                <div class="text-left flex items-center space-x-1.5 self-start md:self-auto">
                    <i class="fa-solid fa-location-dot text-brand-700 text-xs"></i>
                    <span>Main Qasimabad Road, near Ali CNG, Hyderabad, Sindh, Pakistan &bull; WhatsApp: <strong class="text-charcoal-900 font-semibold">0335 2004959</strong></span>
                </div>
                <div class="text-right self-end md:self-auto">
                    Follow us on Instagram: <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="text-brand-800 hover:underline font-bold">@elarabeautestudio</a>
                </div>
            </div>

            <!-- Bottom Row: Designed, Developed and Hosted By : DATAPOINT Technologies (Logo and text kept together) -->
            <div class="pt-6 border-t border-brand-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <div class="flex items-center flex-wrap gap-2.5 bg-white border border-blue-200 px-4 py-2 rounded-2xl shadow-xs hover:shadow transition self-start sm:self-auto">
                    <span class="text-slate-600 font-medium">Designed, Developed and Hosted By :</span>
                    <div class="inline-flex items-center space-x-2.5">
                        <img src="/assets/images/datapoint_logo.png" alt="DATAPOINT Technologies Logo" class="h-8 w-auto object-contain">
                        <div class="text-[10px] text-left leading-tight">
                            <strong class="text-blue-900 font-black block tracking-wider text-xs">DATAPOINT Technologies</strong>
                            <span class="text-sky-600 text-[8px] font-bold tracking-widest uppercase">Smart Solutions &bull; Intelligent Future</span>
                        </div>
                    </div>
                </div>
                <div class="text-slate-500 text-[11px] self-end sm:self-auto">
                    &copy; <?= date('Y') ?> ELARA Beauté Studio &bull; Main Qasimabad Road, Hyderabad
                </div>
            </div>

        </div>
    </footer>

    <script>
        function filterMenu(category) {
            const tabs = document.querySelectorAll('.menu-tab');
            tabs.forEach(t => {
                t.classList.remove('bg-charcoal-900', 'text-brand-300', 'border-brand-400/30');
                t.classList.add('bg-white', 'text-charcoal-700', 'border-brand-200');
            });
            event.target.classList.add('bg-charcoal-900', 'text-brand-300', 'border-brand-400/30');
            event.target.classList.remove('bg-white', 'text-charcoal-700', 'border-brand-200');

            const items = document.querySelectorAll('.menu-item');
            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function handlePortalBooking(e) {
            e.preventDefault();
            const name = document.getElementById('bName').value;
            const phone = document.getElementById('bPhone').value;
            const date = document.getElementById('bDate').value;
            const srv = document.getElementById('bService').value;

            const studioWhatsApp = '<?= preg_replace('/\D+/', '', $parlor['whatsapp']) ?>';
            const msg = `Salam ELARA Beauté Studio!\nI would like to request an appointment:\n\n• Name: ${name}\n• Phone: ${phone}\n• Preferred Date: ${date}\n• Service: ${srv}\n\nPlease confirm availability. Thank you!`;

            window.open(`https://wa.me/${studioWhatsApp}?text=${encodeURIComponent(msg)}`, '_blank');
        }

        function openInstagramReel(url) {
            window.open(url || 'https://www.instagram.com/elarabeautestudio/', '_blank');
        }
    </script>
</body>
</html>

