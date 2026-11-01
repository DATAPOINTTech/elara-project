<?php
$currentPage = 'services';
$pageTitle = 'Services Catalog - ' . $appConfig['parlor']['name'];
$currency = $appConfig['parlor']['currency_symbol'];
require __DIR__ . '/layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-charcoal-900 border border-brand-300/40 text-brand-300 flex items-center justify-center font-bold text-sm shadow-xs">
                <i class="fa-solid fa-scissors"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-charcoal-900 font-cinzel flex items-center">
                    Studio Treatments & Services
                </h1>
                <p class="text-xs text-charcoal-400">Curate luxury parlor offerings, pricing, service durations & descriptions</p>
            </div>
        </div>
        <button onclick="openNewServiceModal()" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold tracking-wider uppercase shadow-xs transition flex items-center self-start sm:self-auto">
            <i class="fa-solid fa-plus mr-1.5"></i> Add New Treatment
        </button>
    </div>

    <!-- Category Groups -->
    <?php foreach ($categories as $categoryName => $servicesList): ?>
    <div class="bg-white rounded-3xl border border-brand-200 shadow-2xs p-6 space-y-4">
        
        <div class="flex items-center justify-between pb-3 border-b border-brand-100">
            <div class="flex items-center space-x-2.5">
                <span class="w-8 h-8 rounded-xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-spa text-xs"></i>
                </span>
                <h2 class="text-base font-extrabold text-charcoal-900 font-cinzel"><?= htmlspecialchars($categoryName) ?></h2>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-100 text-brand-900 border border-brand-200">
                    <?= count($servicesList) ?> treatments
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($servicesList as $srv): ?>
            <div class="p-4 rounded-2xl border border-brand-200 hover:border-brand-400 hover:shadow-sm bg-brand-50/20 transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-charcoal-900"><?= htmlspecialchars($srv['name']) ?></span>
                        <?php if (!empty($srv['popular'])): ?>
                        <span class="text-[9px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-full bg-brand-100 text-brand-800 border border-brand-200">
                            Signature
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="text-[11px] text-charcoal-400 font-semibold mb-2">
                        <i class="fa-regular fa-clock mr-1 text-brand-600"></i><?= (int)($srv['duration'] ?? 45) ?> Minutes
                    </div>
                    <p class="text-xs text-charcoal-500 leading-relaxed mb-3">
                        <?= htmlspecialchars($srv['description'] ?? '') ?>
                    </p>
                </div>

                <div class="pt-3 border-t border-brand-100 flex items-center justify-between">
                    <span class="text-base font-black text-charcoal-900">
                        <?= $currency ?><?= number_format((float)$srv['price'], 2) ?>
                    </span>
                    <button onclick="deleteService('<?= $srv['id'] ?>')" class="text-charcoal-400 hover:text-rose-600 p-1.5 transition" title="Delete treatment">
                        <i class="fa-regular fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
    <?php endforeach; ?>

</div>

<!-- New Service Modal -->
<div id="newServiceModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-brand-200 space-y-5">
        
        <div class="flex items-center justify-between pb-3 border-b border-brand-100">
            <h3 class="text-lg font-bold text-charcoal-900 font-cinzel">Add Treatment to Catalog</h3>
            <button onclick="closeNewServiceModal()" class="text-charcoal-400 hover:text-charcoal-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form onsubmit="handleNewServiceSubmit(event)" class="space-y-4 text-xs">
            <div>
                <label class="block font-bold text-charcoal-700 mb-1">Treatment Name</label>
                <input type="text" id="sName" required placeholder="e.g. Ultrasonic Anti-Aging Facial" class="w-full px-3 py-2 border border-brand-200 rounded-xl focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Category</label>
                    <select id="sCat" class="w-full px-3 py-2 border border-brand-200 rounded-xl">
                        <option value="Skin & Facial">Skin & Facial</option>
                        <option value="Hair Care">Hair Care</option>
                        <option value="Nails & Spa">Nails & Spa</option>
                        <option value="Bridal & Glam">Bridal & Glam</option>
                        <option value="Massage & Body">Massage & Body</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-charcoal-700 mb-1">Duration (Minutes)</label>
                    <input type="number" id="sDuration" value="60" min="15" required class="w-full px-3 py-2 border border-brand-200 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block font-bold text-charcoal-700 mb-1">Price (<?= $currency ?>)</label>
                <input type="number" step="0.01" id="sPrice" value="75.00" min="1" required class="w-full px-3 py-2 border border-brand-200 rounded-xl font-bold text-charcoal-900">
            </div>

            <div>
                <label class="block font-bold text-charcoal-700 mb-1">Description & Protocol</label>
                <textarea id="sDesc" rows="3" placeholder="Brief outline of treatment benefits..." class="w-full px-3 py-2 border border-brand-200 rounded-xl"></textarea>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="sPopular" class="rounded border-brand-300 text-brand-600 focus:ring-brand-500">
                <label for="sPopular" class="font-bold text-charcoal-700">Mark as Signature / Popular Treatment</label>
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="closeNewServiceModal()" class="px-4 py-2 bg-brand-50 hover:bg-brand-100 text-charcoal-700 rounded-xl font-bold">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-xs">Save Treatment</button>
            </div>
        </form>

    </div>
</div>

<script>
function openNewServiceModal() {
    document.getElementById('newServiceModal').classList.remove('hidden');
}
function closeNewServiceModal() {
    document.getElementById('newServiceModal').classList.add('hidden');
}

async function handleNewServiceSubmit(e) {
    e.preventDefault();
    const payload = {
        name: document.getElementById('sName').value,
        category: document.getElementById('sCat').value,
        duration: parseInt(document.getElementById('sDuration').value),
        price: parseFloat(document.getElementById('sPrice').value),
        description: document.getElementById('sDesc').value,
        popular: document.getElementById('sPopular').checked
    };

    try {
        const res = await fetch('/api/services/store', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.error || 'Failed to save treatment.');
        }
    } catch (err) {
        alert('Server communication error.');
    }
}

async function deleteService(id) {
    if (!confirm('Are you sure you want to delete this treatment from the catalog?')) return;
    try {
        const res = await fetch('/api/services/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id })
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
