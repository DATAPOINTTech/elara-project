    </main>

    <!-- Footer -->
    <footer class="bg-[#fcfaf7] border-t border-brand-200 py-8 text-xs text-charcoal-500 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Top Justified Row: Studio Identity & Contact -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-brand-200/60">
                <!-- Left Hand Side: Logo kept close to Heading & Subheading -->
                <div class="flex items-center space-x-3 text-left self-start md:self-auto">
                    <div class="bg-white p-1 rounded-xl border border-brand-300 shadow-2xs shrink-0 flex items-center justify-center">
                        <img src="/assets/images/elara_logo.jpg" alt="ELARA Logo" class="h-11 sm:h-12 w-auto object-contain rounded-lg">
                    </div>
                    <div class="leading-tight">
                        <h3 class="font-black text-charcoal-900 text-base sm:text-lg tracking-wider font-cinzel whitespace-nowrap">
                            ELARA BEAUTÉ STUDIO
                        </h3>
                        <p class="text-brand-600 text-xs font-bold tracking-wider uppercase mt-0.5">
                            By Elia &amp; Sahar
                        </p>
                    </div>
                </div>

                <!-- Right Hand Side: Contact Info (Justified) -->
                <div class="text-center md:text-right text-[11px] text-charcoal-400 space-y-0.5 self-end md:self-auto">
                    <div><i class="fa-solid fa-location-dot text-brand-600 mr-1"></i> <?= htmlspecialchars($appConfig['parlor']['address'] ?? '') ?></div>
                    <div>
                        Concierge: <span class="text-charcoal-800 font-bold"><?= htmlspecialchars($appConfig['parlor']['phone'] ?? '') ?></span> &bull; 
                        WhatsApp: <span class="text-brand-700 font-bold"><?= htmlspecialchars($appConfig['parlor']['whatsapp'] ?? '') ?></span> &bull;
                        Instagram: <a href="https://www.instagram.com/elarabeautestudio/" target="_blank" class="text-pink-600 font-bold hover:underline">@elarabeautestudio</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Designed, Developed and Hosted By DATAPOINT Technologies (Logo and text kept together) -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center flex-wrap gap-2.5 bg-white border border-blue-200 px-3.5 py-1.5 rounded-xl shadow-2xs self-start sm:self-auto">
                    <span class="text-slate-600 font-medium">Designed, Developed and Hosted By :</span>
                    <div class="inline-flex items-center space-x-2">
                        <img src="/assets/images/datapoint_logo.png" alt="DATAPOINT Technologies Logo" class="h-6 w-auto object-contain">
                        <div class="text-[10px] text-left leading-none font-bold">
                            <strong class="text-blue-900 block font-extrabold">DATAPOINT Technologies</strong>
                            <span class="text-sky-600 text-[8px] font-semibold tracking-wider">Smart Solutions &bull; Intelligent Future</span>
                        </div>
                    </div>
                </div>
                <div class="text-slate-400 text-[11px] self-end sm:self-auto">
                    &copy; <?= date('Y') ?> ELARA Beauté Studio &bull; Management Console
                </div>
            </div>

        </div>
    </footer>

    <!-- Floating ELARA AI Assistant Drawer / Modal -->
    <div id="elaraChatModal" class="fixed inset-y-0 right-0 z-50 w-full sm:w-96 bg-white shadow-2xl border-l border-brand-200 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <!-- Chat Header -->
        <div class="p-4 bg-charcoal-900 text-white flex items-center justify-between border-b border-brand-400/20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-white p-0.5 border border-brand-300/40 shadow-sm flex items-center justify-center overflow-hidden">
                    <img src="/assets/images/elara_logo.jpg" alt="ELARA AI" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-bold text-sm text-brand-200 font-cinzel">ELARA Copilot</h3>
                    <p class="text-[10px] text-brand-400/90 font-medium">Beauté Studio Intelligence Advisor</p>
                </div>
            </div>
            <button onclick="toggleElaraChat()" class="text-brand-300 hover:text-white p-1 rounded-lg transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Live Quick Prompts -->
        <div class="bg-brand-50 p-2.5 border-b border-brand-200 flex items-center space-x-1.5 overflow-x-auto text-[11px] font-semibold text-brand-900 whitespace-nowrap custom-scrollbar">
            <button onclick="sendQuickPrompt('How are our sales today?')" class="px-2.5 py-1 bg-white hover:bg-brand-100 rounded-lg border border-brand-200 shadow-2xs transition">
                📊 Today's Sales
            </button>
            <button onclick="sendQuickPrompt('Which products are low on stock?')" class="px-2.5 py-1 bg-white hover:bg-brand-100 rounded-lg border border-brand-200 shadow-2xs transition">
                ⚠️ Low Stock Alert
            </button>
            <button onclick="sendQuickPrompt('Suggest a luxury bridal package for our clients')" class="px-2.5 py-1 bg-white hover:bg-brand-100 rounded-lg border border-brand-200 shadow-2xs transition">
                👰 Bridal Packages
            </button>
            <button onclick="sendQuickPrompt('Draft a weekend promotional WhatsApp message')" class="px-2.5 py-1 bg-white hover:bg-brand-100 rounded-lg border border-brand-200 shadow-2xs transition">
                📣 WhatsApp Promo
            </button>
        </div>

        <!-- Chat Messages Container -->
        <div id="chatMessages" class="flex-1 p-4 overflow-y-auto space-y-3.5 custom-scrollbar bg-[#faf7f2]/50">
            <!-- Welcome message -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 rounded-full bg-charcoal-900 border border-brand-300/40 text-brand-300 flex items-center justify-center text-xs shrink-0 mt-0.5 font-bold">
                    <i class="fa-solid fa-sparkles text-[10px]"></i>
                </div>
                <div class="bg-white p-3.5 rounded-2xl rounded-tl-none border border-brand-200 shadow-2xs text-xs text-charcoal-800 leading-relaxed max-w-[85%]">
                    Welcome to <strong>ELARA BEAUTÉ STUDIO</strong>! I am your studio operations and concierge copilot. I track live sales, salon treatments, inventory restock alerts, and can generate personalized luxury treatment packages or WhatsApp promotional messages. How may I assist you?
                </div>
            </div>
        </div>

        <!-- Chat Input Form -->
        <div class="p-3 bg-white border-t border-brand-200">
            <form id="elaraChatForm" onsubmit="handleChatSubmit(event)" class="flex items-center space-x-2">
                <input 
                    type="text" 
                    id="chatInput" 
                    placeholder="Ask about sales, stock, treatments..." 
                    class="flex-1 text-xs px-3.5 py-2.5 rounded-xl border border-brand-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-brand-50/30"
                    autocomplete="off"
                >
                <button type="submit" class="p-2.5 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/40 rounded-xl shadow-xs transition">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
            <div class="text-[10px] text-charcoal-400 mt-1.5 text-center flex items-center justify-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Live Studio Context Active</span>
                <span>&bull;</span>
                <span class="text-brand-700 font-semibold">Gemini / Expert Engine</span>
            </div>
        </div>
    </div>

    <!-- Floating Chat Trigger Button (Bottom Right) -->
    <div class="fixed bottom-6 right-6 z-40">
        <button onclick="toggleElaraChat()" class="relative group p-3.5 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/40 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition duration-200 flex items-center justify-center">
            <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-brand-500 border-2 border-white"></span>
            </span>
            <i class="fa-solid fa-wand-magic-sparkles text-lg group-hover:rotate-12 transition"></i>
        </button>
    </div>

    <!-- ELARA Chat JS Script -->
    <script src="/assets/js/elara-chat.js"></script>
</body>
</html>
