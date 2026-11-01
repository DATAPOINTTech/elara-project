<?php

declare(strict_types=1);

namespace Elara\Controllers;

use Elara\Services\FirestoreService;

class InventoryController
{
    private FirestoreService $db;

    public function __construct(FirestoreService $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $products = $this->db->getCollection('inventory', 'name');
        
        $totalItems = count($products);
        $lowStockCount = 0;
        $totalValuation = 0.0;

        foreach ($products as $p) {
            $stock = (int)($p['stock'] ?? 0);
            $min = (int)($p['min_stock'] ?? 5);
            if ($stock <= $min) {
                $lowStockCount++;
            }
            $totalValuation += $stock * (float)($p['cost_price'] ?? 0);
        }

        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        require dirname(__DIR__) . '/Views/inventory.php';
    }

    public function apiList(): void
    {
        header('Content-Type: application/json');
        $products = $this->db->getCollection('inventory', 'name');
        echo json_encode(['success' => true, 'data' => $products]);
        exit;
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $name = trim($input['name'] ?? '');
        $sku = trim($input['sku'] ?? ('SKU-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6))));
        $category = trim($input['category'] ?? 'Hair Care');
        $stock = (int)($input['stock'] ?? 0);
        $minStock = (int)($input['min_stock'] ?? 5);
        $costPrice = (float)($input['cost_price'] ?? 0);
        $sellingPrice = (float)($input['selling_price'] ?? 0);
        $supplier = trim($input['supplier'] ?? 'Direct Brand Distributor');

        if (empty($name) || $sellingPrice <= 0) {
            header('Content-Type: application/json', true, 422);
            echo json_encode(['success' => false, 'error' => 'Product name and selling price are required.']);
            exit;
        }

        $id = 'inv_' . bin2hex(random_bytes(4));
        $data = [
            'id' => $id,
            'name' => $name,
            'sku' => $sku,
            'category' => $category,
            'stock' => $stock,
            'min_stock' => $minStock,
            'cost_price' => $costPrice,
            'selling_price' => $sellingPrice,
            'supplier' => $supplier,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $created = $this->db->createDocument('inventory', $data, $id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $created]);
            exit;
        }

        header('Location: /inventory?status=created');
        exit;
    }

    public function adjustStock(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = $input['id'] ?? '';
        $delta = (int)($input['delta'] ?? 0);

        if (empty($id) || $delta === 0) {
            header('Content-Type: application/json', true, 422);
            echo json_encode(['success' => false, 'error' => 'Product ID and stock delta are required.']);
            exit;
        }

        $item = $this->db->getDocument('inventory', $id);
        if (!$item) {
            header('Content-Type: application/json', true, 404);
            echo json_encode(['success' => false, 'error' => 'Product not found.']);
            exit;
        }

        $currentStock = (int)($item['stock'] ?? 0);
        $newStock = max(0, $currentStock + $delta);

        $updated = $this->db->updateDocument('inventory', $id, ['stock' => $newStock]);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $updated]);
        exit;
    }

    public function delete(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = $input['id'] ?? ($_GET['id'] ?? '');

        if (empty($id)) {
            header('Content-Type: application/json', true, 422);
            echo json_encode(['success' => false, 'error' => 'Product ID is required.']);
            exit;
        }

        $deleted = $this->db->deleteDocument('inventory', $id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $deleted]);
            exit;
        }

        header('Location: /inventory?status=deleted');
        exit;
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
               str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }
}

