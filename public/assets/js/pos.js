// ELARA Parlor & Spa - POS Terminal Engine

let cart = [];
let currentCategory = 'all';
let currentSearch = '';

document.addEventListener('DOMContentLoaded', () => {
    initCategoryTabs();
    initSearchFilter();
    initDiscountInput();
    updateCartUi();
});

function initCategoryTabs() {
    const tabs = document.querySelectorAll('.cat-tab-btn');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('bg-rose-600', 'text-white', 'shadow-sm');
                t.classList.add('bg-white', 'text-slate-600', 'hover:bg-slate-50');
            });
            tab.classList.add('bg-rose-600', 'text-white', 'shadow-sm');
            tab.classList.remove('bg-white', 'text-slate-600', 'hover:bg-slate-50');

            currentCategory = tab.dataset.category;
            filterCatalogItems();
        });
    });
}

function initSearchFilter() {
    const searchInput = document.getElementById('catalogSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value.toLowerCase().trim();
            filterCatalogItems();
        });
    }
}

function initDiscountInput() {
    const discountInput = document.getElementById('cartDiscount');
    if (discountInput) {
        discountInput.addEventListener('input', () => {
            recalculateTotals();
        });
    }
}

function filterCatalogItems() {
    const items = document.querySelectorAll('.catalog-item-card');
    items.forEach(card => {
        const itemCat = card.dataset.category;
        const itemName = (card.dataset.name || '').toLowerCase();
        const itemType = card.dataset.type;

        let matchesCat = (currentCategory === 'all') || (itemCat === currentCategory) || (currentCategory === 'Retail Products' && itemType === 'product');
        let matchesSearch = !currentSearch || itemName.includes(currentSearch);

        if (matchesCat && matchesSearch) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

function addToCart(id, name, type, price, stock = null) {
    price = parseFloat(price);
    const existing = cart.find(i => i.id === id);

    if (existing) {
        if (type === 'product' && stock !== null && existing.quantity >= stock) {
            alert(`Only ${stock} units available in inventory.`);
            return;
        }
        existing.quantity += 1;
    } else {
        if (type === 'product' && stock !== null && stock <= 0) {
            alert(`This retail product is currently out of stock.`);
            return;
        }
        cart.push({
            id: id,
            name: name,
            type: type,
            price: price,
            quantity: 1,
            maxStock: stock
        });
    }

    updateCartUi();
}

function changeQuantity(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    if (delta > 0 && item.type === 'product' && item.maxStock !== null && item.quantity >= item.maxStock) {
        alert(`Maximum inventory stock (${item.maxStock}) reached.`);
        return;
    }

    item.quantity += delta;
    if (item.quantity <= 0) {
        cart = cart.filter(i => i.id !== id);
    }

    updateCartUi();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    updateCartUi();
}

function clearCart() {
    if (cart.length > 0 && confirm('Clear all items from the current cart?')) {
        cart = [];
        updateCartUi();
    }
}

function recalculateTotals() {
    const subtotal = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
    const discountPercent = Math.min(100, Math.max(0, parseFloat(document.getElementById('cartDiscount')?.value || 0)));
    const discountAmount = subtotal * (discountPercent / 100);
    const taxableAmount = Math.max(0, subtotal - discountAmount);
    const taxRate = parseFloat(window.SALON_TAX_RATE || 8.0);
    const taxAmount = taxableAmount * (taxRate / 100);
    const grandTotal = taxableAmount + taxAmount;

    const curr = window.SALON_CURRENCY || '$';

    document.getElementById('displaySubtotal').textContent = curr + subtotal.toFixed(2);
    document.getElementById('displayDiscount').textContent = '- ' + curr + discountAmount.toFixed(2);
    document.getElementById('displayTax').textContent = '+ ' + curr + taxAmount.toFixed(2);
    document.getElementById('displayTotal').textContent = curr + grandTotal.toFixed(2);

    return { subtotal, discountPercent, discountAmount, taxRate, taxAmount, grandTotal };
}

function updateCartUi() {
    const container = document.getElementById('cartItemsList');
    const emptyNotice = document.getElementById('cartEmptyNotice');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const badge = document.getElementById('cartCountBadge');

    if (!container) return;

    const totalCount = cart.reduce((sum, i) => sum + i.quantity, 0);
    if (badge) badge.textContent = totalCount;

    if (cart.length === 0) {
        container.innerHTML = '';
        if (emptyNotice) emptyNotice.classList.remove('hidden');
        if (checkoutBtn) {
            checkoutBtn.disabled = true;
            checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        recalculateTotals();
        return;
    }

    if (emptyNotice) emptyNotice.classList.add('hidden');
    if (checkoutBtn) {
        checkoutBtn.disabled = false;
        checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    const curr = window.SALON_CURRENCY || '$';

    container.innerHTML = cart.map(item => `
        <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-rose-100 shadow-2xs hover:border-rose-200 transition">
            <div class="flex-1 min-w-0 pr-2">
                <div class="font-bold text-xs text-slate-800 truncate">${item.name}</div>
                <div class="flex items-center space-x-1.5 mt-0.5">
                    <span class="text-[10px] px-1.5 py-0.5 rounded ${item.type === 'service' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700'} font-semibold uppercase">
                        ${item.type}
                    </span>
                    <span class="text-[11px] text-slate-500 font-medium">${curr}${item.price.toFixed(2)} each</span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                    <button onclick="changeQuantity('${item.id}', -1)" class="w-6 h-6 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition text-xs font-bold">-</button>
                    <span class="w-6 text-center text-xs font-bold text-slate-800">${item.quantity}</span>
                    <button onclick="changeQuantity('${item.id}', 1)" class="w-6 h-6 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition text-xs font-bold">+</button>
                </div>
                <div class="text-xs font-extrabold text-slate-900 w-16 text-right">
                    ${curr}${(item.price * item.quantity).toFixed(2)}
                </div>
                <button onclick="removeFromCart('${item.id}')" class="text-slate-400 hover:text-rose-600 p-1 transition" title="Remove item">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </div>
        </div>
    `).join('');

    recalculateTotals();
}

async function processCheckout() {
    if (cart.length === 0) {
        alert('Please add items to cart before checkout.');
        return;
    }

    const customerName = document.getElementById('customerName')?.value.trim() || 'Walk-in Guest';
    const customerPhone = document.getElementById('customerPhone')?.value.trim() || '';
    const customerEmail = document.getElementById('customerEmail')?.value.trim() || '';
    const stylistId = document.getElementById('stylistSelect')?.value || '';
    const paymentMethod = document.getElementById('paymentMethod')?.value || 'Cash';
    const discountPercent = parseFloat(document.getElementById('cartDiscount')?.value || 0);
    const notes = document.getElementById('saleNotes')?.value.trim() || '';

    const payload = {
        customer_name: customerName,
        customer_phone: customerPhone,
        customer_email: customerEmail,
        stylist_id: stylistId,
        payment_method: paymentMethod,
        discount_percent: discountPercent,
        notes: notes,
        cashier_name: window.CURRENT_CASHIER || 'Front Desk Cashier',
        items: cart
    };

    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Processing...`;
    }

    try {
        const response = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {
            showSuccessModal(data);
            // Reset cart
            cart = [];
            updateCartUi();
            // Reset form fields
            if (document.getElementById('customerName')) document.getElementById('customerName').value = '';
            if (document.getElementById('customerPhone')) document.getElementById('customerPhone').value = '';
            if (document.getElementById('customerEmail')) document.getElementById('customerEmail').value = '';
            if (document.getElementById('cartDiscount')) document.getElementById('cartDiscount').value = '0';
            if (document.getElementById('saleNotes')) document.getElementById('saleNotes').value = '';
        } else {
            alert('Checkout error: ' + (data.error || 'Unknown error.'));
        }
    } catch (err) {
        console.error('Checkout error:', err);
        alert('Server connection error while processing payment.');
    } finally {
        if (checkoutBtn) {
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = `<i class="fa-solid fa-check-circle mr-1.5"></i> Complete Sale & Issue Invoice`;
        }
    }
}

function showSuccessModal(data) {
    const modal = document.getElementById('checkoutSuccessModal');
    if (!modal) return;

    const inv = data.invoice;
    const curr = window.SALON_CURRENCY || '$';

    document.getElementById('successInvoiceNum').textContent = '#' + inv.invoice_number;
    document.getElementById('successClient').textContent = inv.customer_name;
    if (document.getElementById('successCashier')) {
        document.getElementById('successCashier').textContent = inv.cashier_name || window.CURRENT_CASHIER || 'Front Desk';
    }
    document.getElementById('successTotal').textContent = curr + parseFloat(inv.total).toFixed(2);
    document.getElementById('successPayment').textContent = inv.payment_method;

    // Set buttons
    const printBtn = document.getElementById('modalPrintBtn');
    if (printBtn) {
        printBtn.href = data.receipt_url + '&autoprint=1';
    }

    const pdfBtn = document.getElementById('modalPdfBtn');
    if (pdfBtn) {
        pdfBtn.href = `/invoice/download?id=${inv.invoice_number}&download=1`;
    }

    const waBtn = document.getElementById('modalWaBtn');
    if (waBtn) {
        if (data.whatsapp_link) {
            waBtn.href = data.whatsapp_link;
            waBtn.classList.remove('hidden');
        } else {
            waBtn.classList.add('hidden');
        }
    }

    modal.classList.remove('hidden');
}

function closeSuccessModal() {
    const modal = document.getElementById('checkoutSuccessModal');
    if (modal) modal.classList.add('hidden');
}

