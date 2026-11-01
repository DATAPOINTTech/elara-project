<?php

declare(strict_types=1);

namespace Elara\Controllers;

use Elara\Services\FirestoreService;

class ServiceController
{
    private FirestoreService $db;

    public function __construct(FirestoreService $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $services = $this->db->getCollection('services', 'name');
        
        // Group by category
        $categories = [];
        foreach ($services as $srv) {
            $cat = $srv['category'] ?? 'General';
            $categories[$cat][] = $srv;
        }

        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        require dirname(__DIR__) . '/Views/services.php';
    }

    public function apiList(): void
    {
        header('Content-Type: application/json');
        $services = $this->db->getCollection('services', 'name');
        echo json_encode(['success' => true, 'data' => $services]);
        exit;
    }

    public function store(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        $name = trim($input['name'] ?? '');
        $category = trim($input['category'] ?? 'Hair Care');
        $price = (float)($input['price'] ?? 0);
        $duration = (int)($input['duration'] ?? 45);
        $description = trim($input['description'] ?? '');

        if (empty($name) || $price <= 0) {
            header('Content-Type: application/json', true, 422);
            echo json_encode(['success' => false, 'error' => 'Valid service name and price are required.']);
            exit;
        }

        $id = 'srv_' . bin2hex(random_bytes(4));
        $data = [
            'id' => $id,
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'duration' => $duration,
            'description' => $description,
            'popular' => !empty($input['popular']),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $created = $this->db->createDocument('services', $data, $id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $created]);
            exit;
        }

        header('Location: /services?status=created');
        exit;
    }

    public function update(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = $input['id'] ?? '';

        if (empty($id)) {
            header('Content-Type: application/json', true, 422);
            echo json_encode(['success' => false, 'error' => 'Service ID is required.']);
            exit;
        }

        $updateData = [];
        if (isset($input['name'])) $updateData['name'] = trim($input['name']);
        if (isset($input['category'])) $updateData['category'] = trim($input['category']);
        if (isset($input['price'])) $updateData['price'] = (float)$input['price'];
        if (isset($input['duration'])) $updateData['duration'] = (int)$input['duration'];
        if (isset($input['description'])) $updateData['description'] = trim($input['description']);
        if (isset($input['popular'])) $updateData['popular'] = (bool)$input['popular'];

        $updated = $this->db->updateDocument('services', $id, $updateData);

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
            echo json_encode(['success' => false, 'error' => 'Service ID is required.']);
            exit;
        }

        $deleted = $this->db->deleteDocument('services', $id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $deleted]);
            exit;
        }

        header('Location: /services?status=deleted');
        exit;
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ||
               str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }
}

