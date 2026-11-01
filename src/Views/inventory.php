<?php
$currentPage = 'inventory';
$pageTitle = 'Inventory & Stock - ' . $appConfig['parlor']['name'];
$currency = $appConfig['parlor']['currency_symbol'];
require __DIR__ . '/layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-charcoal-900 border border-brand-300/40 text-brand-300 flex items-center justify-center font-bold text-sm shadow-xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-charcoal-900 font-cinzel flex items-center">
                    Retail Products & Inventory
                </h1>
                <p class="text-xs text-charcoal-400">Manage studio backbar supplies, retail beauty products & safety thresholds</p>
            </div>
        </div>
        <button onclick="openNewProductModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold tracking-wider uppercase shadow-xs transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-plus mr-1.5"></i> Add New Product
        </button>
    </div>

    <!-- Inventory Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Total Catalog Items</p>
                <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= (int)$totalItems ?> SKUs</h3>
                <p class="text-[11px] text-charcoal-500 font-medium mt-1">Retail & treatment supplies</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-barcode"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Low Stock Warnings</p>
                <h3 class="text-2xl font-extrabold <?= $lowStockCount > 0 ? 'text-amber-700' : 'text-charcoal-900' ?> mt-1"><?= (int)$lowStockCount ?> Products</h3>
                <p class="text-[11px] text-charcoal-500 font-medium mt-1">Below minimum threshold</p>
            </div>
            <div class="w-12 h-12 rounded-2xl <?= $lowStockCount > 0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-brand-50 text-brand-700 border-brand-200' ?> border flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-charcoal-400">Inventory Valuation</p>
                <h3 class="text-2xl font-extrabold text-charcoal-900 mt-1"><?= $currency ?><?= number_format($totalValuation, 2) ?></h3>
                <p class="text-[11px] text-emerald-700 font-semibold mt-1">At wholesale cost</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center text-xl shadow-2xs">
                <i class="fa-solid fa-vault"></i>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-brand-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-charcoal-900 font-cinzel">Current Stock Levels</h2>
            <div class="text-xs text-charcoal-400">Synchronized with Google Cloud Firestore</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-charcoal-600">
                <thead class="bg-brand-50/70 uppercase text-[10px] text-charcoal-500 font-bold border-b border-brand-200">
                    <tr>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Product Name</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4 text-center">In Stock</th>
                        <th class="py-3 px-4 text-center">Min Safety</th>
                        <th class="py-3 px-4 text-right">Cost Price</th>
                        <th class="py-3 px-4 text-right">Retail Price</th>
                        <th class="py-3 px-4">Supplier</th>
                        <th class="py-3 px-4 text-center">Adjust Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-100">
                    <?php foreach ($products as $prod): ?>
                    <?php 
                        $stock = (int)($prod['stock'] ?? 0);
                        $min = (int)($prod['min_stock'] ?? 5);
                        $isLow = $stock <= $min;
                    ?>
                    <tr class="hover:bg-brand-50/40 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-charcoal-800">
                            <?= htmlspecialchars($prod['sku'] ?? 'SKU-000') ?>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-charcoal-900">
                            <?= htmlspecialchars($prod['name']) ?>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-900 border border-brand-200">
                                <?= htmlspecialchars($prod['category'] ?? 'Hair Care') ?>
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black <?= $isLow ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' ?>">
                                <?= $stock ?> units
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center text-charcoal-500 font-semibold">
                            <?= $min ?>
                        </td>
                        <td class="py-3.5 px-4 text-right text-charcoal-500">
                            <?= $currency ?><?= number_format((float)($prod['cost_price'] ?? 0), 2) ?>
                        </td>
                        <td class="py-3.5 px-4 text-right font-extrabold text-charcoal-900">
                            <?= $currency ?><?= number_format((float)($prod['selling_price'] ?? 0), 2) ?>
                        </td>
                        <td class="py-3.5 px-4 text-charcoal-500 text-[11px]">
                            <?= htmlspecialchars($prod['supplier'] ?? 'Direct') ?>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="inline-flex items-center space-x-1 border border-brand-200 rounded-lg p-0.5 bg-brand-50/50">
                                <button onclick="adjustProductStock('<?= $prod['id'] ?>', -1)" class="w-6 h-6 flex items-center justify-center text-charcoal-600 hover:bg-brand-200 rounded text-xs font-bold transition" title="Deduct 1">-</button>
                                <button onclick="adjustProductStock('<?= $prod['id'] ?>', 5)" class="px-2 h-6 flex items-center justify-center text-brand-800 hover:bg-brand-200 rounded text-[10px] font-bold transition" title="Restock +5">+5</button>
                                <button onclick="adjustProductStock('<?= $prod['id'] ?>', 1)" class="w-6 h-6 flex items-center justify-center text-charcoal-600 hover:bg-brand-200 rounded text-xs font-bold transition" title="Add 1">+</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- New Product Modal -->
<div id="newProductModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-brand-200 space-y-5">
        
        <div class="flex items-center justify-between pb-3 border-b border-brand-100">
            <h3 class="text-lg font-bold text-charcoal-900 font-cinzel">Add Product to Inventory</h3>
            <button onclick="closeNewProductModal()" class="text-charcoal-400 hover:text-charcoal-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form onsubmit="handleNewProductSubmit(event)" class="space-y-4 text-xs">
            <div>
                <label class="block font-bold text-charcoal-700 mb-1">Product Title</label>
                <input type="text" id="pTitle" required placeholder="e.g. Olaplex No. 7 Bonding Oil" class="w-full px-3 py-2 border border-brand-200 rounded-xl focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Category</label>
                    <select id="pCat" class="w-full px-3 py-2 border border-brand-200 rounded-xl">
                        <option value="Hair Care">Hair Care</option>
                        <option value="Skin & Facial">Skin & Facial</option>
                        <option value="Nails & Spa">Nails & Spa</option>
                        <option value="Cosmetics">Cosmetics</option>
                        <option value="Spa Essentials">Spa Essentials</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">SKU Code</label>
                    <input type="text" id="pSku" placeholder="Auto-generated if empty" class="w-full px-3 py-2 border border-brand-200 rounded-xl font-mono">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Initial Stock Quantity</label>
                    <input type="number" id="pStock" value="10" min="0" required class="w-full px-3 py-2 border border-brand-200 rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Safety Min. Stock Alert</label>
                    <input type="number" id="pMinStock" value="5" min="1" required class="w-full px-3 py-2 border border-brand-200 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Wholesale Cost Price (<?= $currency ?>)</label>
                    <input type="number" step="0.01" id="pCost" value="15.00" min="0" required class="w-full px-3 py-2 border border-brand-200 rounded-xl">
                </div>
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Retail Selling Price (<?= $currency ?>)</label>
                    <input type="number" step="0.01" id="pSelling" value="28.00" min="0.01" required class="w-full px-3 py-2 border border-brand-200 rounded-xl font-bold text-charcoal-900">
                </div>
            </div>

            <div>
                <label class="block font-bold text-charcoal-700 mb-1">Distributor / Supplier</label>
                <input type="text" id="pSupplier" placeholder="e.g. Official Salon Brand Distributor" class="w-full px-3 py-2 border border-brand-200 rounded-xl">
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="closeNewProductModal()" class="px-4 py-2 bg-brand-50 hover:bg-brand-100 text-charcoal-700 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-xs">Save Product</button>
            </div>
        </form>

    </div>
</div>

<script>
function openNewProductModal() {
    document.getElementById('newProductModal').classList.remove('hidden');
}
function closeNewProductModal() {
    document.getElementById('newProductModal').classList.add('hidden');
}

async function handleNewProductSubmit(e) {
    e.preventDefault();
    const payload = {
        name: document.getElementById('pTitle').value,
        category: document.getElementById('pCat').value,
        sku: document.getElementById('pSku').value,
        stock: parseInt(document.getElementById('pStock').value),
        min_stock: parseInt(document.getElementById('pMinStock').value),
        cost_price: parseFloat(document.getElementById('pCost').value),
        selling_price: parseFloat(document.getElementById('pSelling').value),
        supplier: document.getElementById('pSupplier').value,
    };

    try {
        const res = await fetch('/api/inventory/store', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Failed to save product');
        }
    } catch (err) {
        alert('Server communication error.');
    }
}

async function adjustProductStock(id, delta) {
    try {
        const res = await fetch('/api/inventory/adjust', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id, delta: delta })
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        }
    } catch (err) {
        console.error(err);
    }
}
</script>

<?php require __DIR__ . '/layouts/footer.php'; ?>
