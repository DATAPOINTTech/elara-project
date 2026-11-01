<?php
$currentPage = 'pos';
$pageTitle = 'POS Terminal - ' . $appConfig['parlor']['name'];
$currency = $appConfig['parlor']['currency_symbol'];
$taxRate = (float)$appConfig['parlor']['tax_rate'];
require __DIR__ . '/layouts/header.php';
?>

<script>
    window.SALON_CURRENCY = <?= json_encode($currency) ?>;
    window.SALON_TAX_RATE = <?= json_encode($taxRate) ?>;
    window.CURRENT_CASHIER = <?= json_encode($currentUser['name'] ?? 'Front Desk Cashier') ?>;
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- POS Header & Cashier Indicator -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-charcoal-900 border border-brand-300/40 text-brand-300 flex items-center justify-center font-bold text-sm shadow-xs">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-charcoal-900 font-cinzel flex items-center">
                    POS Checkout Terminal
                </h1>
                <p class="text-xs text-charcoal-400">Select studio treatments & retail beauty products for billing</p>
            </div>
        </div>

        <!-- Cashier Status & Search Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Active Cashier Pill -->
            <div class="flex items-center space-x-2 bg-brand-50 border border-brand-200 px-3 py-2 rounded-xl text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-charcoal-500">Cashier:</span>
                <strong id="activeCashierDisplay" class="text-charcoal-900 font-extrabold"><?= htmlspecialchars($currentUser['name'] ?? 'Front Desk') ?></strong>
                <span class="text-[10px] uppercase font-bold text-brand-700 bg-brand-100 px-1.5 py-0.5 rounded"><?= htmlspecialchars($currentUser['role'] ?? 'cashier') ?></span>
            </div>

            <!-- Search Bar -->
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-charcoal-400 text-xs"></i>
                <input 
                    type="text" 
                    id="catalogSearch" 
                    placeholder="Search treatments or products..." 
                    class="w-full pl-9 pr-4 py-2 bg-white border border-brand-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 shadow-2xs placeholder-charcoal-300"
                >
            </div>
        </div>
    </div>

    <!-- Category Tabs Styled in Logo Palette -->
    <div class="flex items-center space-x-2 overflow-x-auto pb-2 custom-scrollbar text-xs font-bold uppercase tracking-wider">
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-charcoal-900 text-brand-300 shadow-xs border border-brand-300/40 transition whitespace-nowrap" data-category="all">
            ✨ All Items
        </button>
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-white text-charcoal-600 hover:bg-brand-50 border border-brand-200 transition whitespace-nowrap" data-category="Skin & Facial">
            🌸 Skin & Facial
        </button>
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-white text-charcoal-600 hover:bg-brand-50 border border-brand-200 transition whitespace-nowrap" data-category="Hair Care">
            💇‍♀️ Hair Care
        </button>
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-white text-charcoal-600 hover:bg-brand-50 border border-brand-200 transition whitespace-nowrap" data-category="Nails & Spa">
            💅 Nails & Spa
        </button>
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-white text-charcoal-600 hover:bg-brand-50 border border-brand-200 transition whitespace-nowrap" data-category="Bridal & Glam">
            👰 Bridal & Glam
        </button>
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-white text-charcoal-600 hover:bg-brand-50 border border-brand-200 transition whitespace-nowrap" data-category="Massage & Body">
            💆‍♀️ Massage & Body
        </button>
        <button class="cat-tab-btn px-4 py-2 rounded-xl bg-white text-charcoal-600 hover:bg-brand-50 border border-brand-200 transition whitespace-nowrap" data-category="Retail Products">
            🛍️ Retail Products
        </button>
    </div>

    <!-- Grid Layout: Catalog (Left) + Cart Sidebar (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Catalog Items Grid (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <div id="catalogGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                
                <!-- Services -->
                <?php foreach ($services as $srv): ?>
                <div 
                    class="catalog-item-card bg-white p-4 rounded-2xl border border-brand-200 shadow-2xs hover:shadow-md hover:border-brand-400 transition duration-150 flex flex-col justify-between cursor-pointer group"
                    data-category="<?= htmlspecialchars($srv['category'] ?? 'General') ?>"
                    data-name="<?= htmlspecialchars($srv['name']) ?>"
                    data-type="service"
                    onclick="addToCart('<?= $srv['id'] ?>', '<?= htmlspecialchars(addslashes($srv['name'])) ?>', 'service', <?= (float)$srv['price'] ?>)"
                >
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-brand-50 text-brand-800 border border-brand-200">
                                <?= htmlspecialchars($srv['category']) ?>
                            </span>
                            <span class="text-[10px] text-charcoal-400 font-semibold">
                                <i class="fa-regular fa-clock mr-1"></i><?= (int)($srv['duration'] ?? 45) ?>m
                            </span>
                        </div>
                        <h4 class="font-bold text-xs text-charcoal-900 group-hover:text-brand-700 transition leading-snug mb-1">
                            <?= htmlspecialchars($srv['name']) ?>
                        </h4>
                        <p class="text-[11px] text-charcoal-400 line-clamp-2 leading-relaxed">
                            <?= htmlspecialchars($srv['description'] ?? '') ?>
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-brand-100 flex items-center justify-between">
                        <span class="text-sm font-black text-charcoal-900">
                            <?= $currency ?><?= number_format((float)$srv['price'], 2) ?>
                        </span>
                        <button class="w-8 h-8 rounded-xl bg-brand-50 group-hover:bg-charcoal-900 group-hover:text-brand-300 text-charcoal-700 flex items-center justify-center transition shadow-2xs border border-brand-200">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Retail Products -->
                <?php foreach ($inventory as $prod): ?>
                <?php $stock = (int)($prod['stock'] ?? 0); ?>
                <div 
                    class="catalog-item-card bg-white p-4 rounded-2xl border border-brand-200 shadow-2xs hover:shadow-md hover:border-brand-400 transition duration-150 flex flex-col justify-between cursor-pointer group <?= $stock <= 0 ? 'opacity-60 pointer-events-none' : '' ?>"
                    data-category="Retail Products"
                    data-name="<?= htmlspecialchars($prod['name']) ?>"
                    data-type="product"
                    onclick="addToCart('<?= $prod['id'] ?>', '<?= htmlspecialchars(addslashes($prod['name'])) ?>', 'product', <?= (float)$prod['selling_price'] ?>, <?= $stock ?>)"
                >
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-brand-100/60 text-brand-900 border border-brand-200">
                                Retail Product
                            </span>
                            <span class="text-[10px] font-bold <?= $stock <= 5 ? 'text-amber-700' : 'text-charcoal-500' ?>">
                                <?= $stock ?> in stock
                            </span>
                        </div>
                        <h4 class="font-bold text-xs text-charcoal-900 group-hover:text-brand-700 transition leading-snug mb-1">
                            <?= htmlspecialchars($prod['name']) ?>
                        </h4>
                        <p class="text-[11px] text-charcoal-400">
                            SKU: <?= htmlspecialchars($prod['sku'] ?? 'N/A') ?>
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-brand-100 flex items-center justify-between">
                        <span class="text-sm font-black text-charcoal-900">
                            <?= $currency ?><?= number_format((float)$prod['selling_price'], 2) ?>
                        </span>
                        <button class="w-8 h-8 rounded-xl bg-brand-50 group-hover:bg-charcoal-900 group-hover:text-brand-300 text-charcoal-700 flex items-center justify-center transition shadow-2xs border border-brand-200">
                            <i class="fa-solid fa-cart-plus text-xs"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

        </div>

        <!-- Cart Sidebar (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-brand-200 shadow-lg p-5 space-y-5 sticky top-24">
            
            <div class="flex items-center justify-between pb-3 border-b border-brand-100">
                <div class="flex items-center space-x-2.5">
                    <span class="w-8 h-8 rounded-xl bg-charcoal-900 text-brand-300 border border-brand-400/40 flex items-center justify-center font-bold text-xs">
                        <i class="fa-solid fa-receipt"></i>
                    </span>
                    <div>
                        <h3 class="font-black text-sm text-charcoal-900 font-cinzel">Current Order</h3>
                        <p class="text-[11px] text-charcoal-400">Items selected for guest billing</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="cartCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-black bg-brand-100 text-brand-900 border border-brand-200">0</span>
                    <button onclick="clearCart()" class="text-xs text-charcoal-400 hover:text-rose-600 transition" title="Clear Cart">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <!-- Client & Stylist Selectors -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-brand-50/50 p-3.5 rounded-2xl border border-brand-200/80">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-500 mb-1">Guest Name</label>
                    <input 
                        type="text" 
                        id="customerName" 
                        placeholder="e.g. Eleanor Vance" 
                        class="w-full text-xs px-2.5 py-1.5 bg-white border border-brand-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand-500"
                    >
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-500 mb-1">WhatsApp / Phone</label>
                    <input 
                        type="text" 
                        id="customerPhone" 
                        placeholder="+1 555-0192" 
                        class="w-full text-xs px-2.5 py-1.5 bg-white border border-brand-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand-500"
                    >
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-500 mb-1">Guest Email (Optional)</label>
                    <input 
                        type="email" 
                        id="customerEmail" 
                        placeholder="guest@example.com" 
                        class="w-full text-xs px-2.5 py-1.5 bg-white border border-brand-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand-500"
                    >
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-charcoal-500 mb-1">Salon Artist</label>
                    <select id="stylistSelect" class="w-full text-xs px-2.5 py-1.5 bg-white border border-brand-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand-500">
                        <?php foreach ($staff as $st): ?>
                        <option value="<?= $st['id'] ?>"><?= htmlspecialchars($st['name']) ?> (<?= htmlspecialchars($st['role']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Items List Container -->
            <div class="space-y-2 max-h-56 overflow-y-auto custom-scrollbar pr-1">
                <div id="cartEmptyNotice" class="text-center py-8 text-xs text-charcoal-400">
                    <i class="fa-solid fa-basket-shopping text-2xl mb-2 text-brand-300 block"></i>
                    No items added yet. Click treatments or products to add.
                </div>
                <div id="cartItemsList" class="space-y-2"></div>
            </div>

            <!-- Financial Calculations Breakdown -->
            <div class="pt-3 border-t border-brand-200 space-y-2 text-xs">
                <div class="flex items-center justify-between text-charcoal-600">
                    <span>Subtotal:</span>
                    <strong id="displaySubtotal" class="text-charcoal-900"><?= $currency ?>0.00</strong>
                </div>

                <div class="flex items-center justify-between text-charcoal-600">
                    <div class="flex items-center space-x-1.5">
                        <span>Discount:</span>
                        <input 
                            type="number" 
                            id="cartDiscount" 
                            value="0" 
                            min="0" 
                            max="100" 
                            class="w-12 text-center text-xs px-1 py-0.5 border border-brand-200 rounded bg-white"
                        >%
                    </div>
                    <span id="displayDiscount" class="text-brand-700 font-bold">- <?= $currency ?>0.00</span>
                </div>

                <div class="flex items-center justify-between text-charcoal-600">
                    <span><?= htmlspecialchars($appConfig['parlor']['tax_name']) ?> (<?= $taxRate ?>%):</span>
                    <span id="displayTax" class="text-charcoal-900">+ <?= $currency ?>0.00</span>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-brand-100 text-sm">
                    <strong class="text-charcoal-900 font-cinzel">Grand Total:</strong>
                    <strong id="displayTotal" class="text-xl font-extrabold text-charcoal-900"><?= $currency ?>0.00</strong>
                </div>
            </div>

            <!-- Payment Method & Complete Button -->
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-charcoal-500">Payment:</label>
                    <select id="paymentMethod" class="flex-1 text-xs px-3 py-2 bg-brand-50/50 border border-brand-200 rounded-xl font-semibold text-charcoal-800">
                        <option value="Cash">💵 Cash</option>
                        <option value="Card">💳 Credit / Debit Card</option>
                        <option value="Online">📲 Online / Mobile Transfer</option>
                    </select>
                </div>

                <input 
                    type="text" 
                    id="saleNotes" 
                    placeholder="Stylist aftercare note or special instructions..." 
                    class="w-full text-xs px-3 py-2 border border-brand-200 rounded-xl bg-brand-50/30"
                >

                <button 
                    id="checkoutBtn" 
                    onclick="processCheckout()" 
                    disabled 
                    class="w-full py-3.5 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/40 rounded-2xl font-black text-xs tracking-wider uppercase shadow-md transition flex items-center justify-center space-x-2 opacity-50 cursor-not-allowed"
                >
                    <i class="fa-solid fa-check-circle text-brand-400"></i>
                    <span>Complete Sale & Issue Invoice</span>
                </button>
            </div>

        </div>

    </div>

</div>

<!-- Checkout Success Modal -->
<div id="checkoutSuccessModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-brand-200 space-y-5">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-full bg-brand-50 border border-brand-200 text-brand-700 mx-auto flex items-center justify-center text-2xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="text-xl font-extrabold text-charcoal-900 font-cinzel">Sale Completed!</h3>
            <p class="text-xs text-charcoal-400">Invoice successfully recorded to Google Cloud Firestore.</p>
        </div>

        <div class="bg-brand-50/50 p-4 rounded-2xl border border-brand-200 text-xs space-y-1.5">
            <div class="flex justify-between">
                <span class="text-charcoal-500">Invoice:</span>
                <strong id="successInvoiceNum" class="text-charcoal-900">#INV-0000</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-charcoal-500">Guest:</span>
                <strong id="successClient" class="text-charcoal-900">Guest</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-charcoal-500">Billed By:</span>
                <strong id="successCashier" class="text-charcoal-900"><?= htmlspecialchars($currentUser['name'] ?? 'Front Desk') ?></strong>
            </div>
            <div class="flex justify-between">
                <span class="text-charcoal-500">Payment:</span>
                <span id="successPayment" class="font-semibold text-charcoal-800">Cash</span>
            </div>
            <div class="flex justify-between text-sm pt-2 border-t border-brand-200 font-bold">
                <span class="text-charcoal-900">Total Paid:</span>
                <span id="successTotal" class="text-charcoal-900 font-extrabold text-base">$0.00</span>
            </div>
        </div>

        <!-- 3 Instant Action Buttons -->
        <div class="space-y-2.5">
            <!-- Print 80mm -->
            <a id="modalPrintBtn" href="#" target="_blank" class="w-full py-2.5 bg-charcoal-900 hover:bg-black text-brand-300 border border-brand-400/40 rounded-xl text-xs font-bold tracking-wider uppercase transition flex items-center justify-center shadow-xs">
                <i class="fa-solid fa-print mr-2 text-brand-400"></i> Print 80mm Thermal Receipt
            </a>

            <!-- Download PDF -->
            <a id="modalPdfBtn" href="#" target="_blank" class="w-full py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold tracking-wider uppercase transition flex items-center justify-center shadow-xs">
                <i class="fa-solid fa-file-pdf mr-2"></i> Download Branded PDF Invoice
            </a>

            <!-- WhatsApp Share -->
            <a id="modalWaBtn" href="#" target="_blank" class="w-full py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold tracking-wider uppercase transition flex items-center justify-center shadow-xs">
                <i class="fa-brands fa-whatsapp mr-2 text-base"></i> Send Receipt via WhatsApp
            </a>
        </div>

        <button onclick="closeSuccessModal()" class="w-full py-2 text-xs font-semibold text-charcoal-400 hover:text-charcoal-700 transition">
            Close & Start Next Sale
        </button>

    </div>
</div>

<script src="/assets/js/pos.js"></script>

<?php require __DIR__ . '/layouts/footer.php'; ?>
