<?php

declare(strict_types=1);

namespace Elara\Services;

use Firebase\JWT\JWT;
use Exception;

class FirestoreService
{
    private array $config;
    private bool $isLive = false;
    private ?string $accessToken = null;
    private int $tokenExpiry = 0;
    private string $storageFile;
    private array $localDb = [];

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? (require dirname(__DIR__, 2) . '/config/firestore.php');
        $this->storageFile = $this->config['emulator_storage_path'];
        $this->initStorage();
        $this->checkCloudConnection();
    }

    /**
     * Determine if real Google Cloud Firestore credentials are valid.
     */
    private function checkCloudConnection(): void
    {
        $credsPath = $this->config['credentials_file'];
        if (file_exists($credsPath)) {
            $raw = @file_get_contents($credsPath);
            $data = json_decode($raw, true);
            if (
                is_array($data) &&
                isset($data['client_email'], $data['private_key'], $data['project_id']) &&
                strpos($data['private_key'], '-----BEGIN PRIVATE KEY-----') !== false &&
                strpos($data['private_key'], 'replace_with_your_private_key_id') === false &&
                !empty($data['private_key']) &&
                strlen($data['private_key']) > 200
            ) {
                try {
                    $token = $this->getOAuthToken($data);
                    if ($token) {
                        $this->isLive = true;
                        $this->accessToken = $token;
                    }
                } catch (\Throwable $e) {
                    error_log("Firestore Cloud Auth error: " . $e->getMessage());
                    $this->isLive = false;
                }
            }
        }
    }

    public function isLiveCloud(): bool
    {
        return $this->isLive;
    }

    public function getProjectId(): string
    {
        return $this->config['project_id'];
    }

    /**
     * Generate OAuth2 Access Token for Google Cloud Firestore REST API using Service Account JWT.
     */
    private function getOAuthToken(array $creds): ?string
    {
        $now = time();
        if ($this->accessToken && $this->tokenExpiry > $now + 60) {
            return $this->accessToken;
        }

        $payload = [
            'iss' => $creds['client_email'],
            'scope' => $this->config['scope'],
            'aud' => $this->config['oauth_token_uri'],
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwt = JWT::encode($payload, $creds['private_key'], 'RS256');

        $ch = curl_init($this->config['oauth_token_uri']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 10,
        ]);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            $json = json_decode($res, true);
            if (!empty($json['access_token'])) {
                $this->accessToken = $json['access_token'];
                $this->tokenExpiry = $now + ($json['expires_in'] ?? 3600);
                return $this->accessToken;
            }
        }

        return null;
    }

    /**
     * Initialize Local Firestore JSON Emulator and pre-seed with rich Pakistan Salon data.
     */
    private function initStorage(): void
    {
        $dir = dirname($this->storageFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        if (file_exists($this->storageFile)) {
            $data = json_decode(file_get_contents($this->storageFile), true);
            $this->localDb = is_array($data) ? $data : [];
        } else {
            $this->localDb = [];
        }

        // Auto-seed if collections are empty or missing PKR data
        if (empty($this->localDb['services']) || empty($this->localDb['inventory']) || empty($this->localDb['currency']) || $this->localDb['currency'] !== 'PKR') {
            $this->seedDefaults(true);
        }
    }

    private function persistLocalDb(): void
    {
        file_put_contents(
            $this->storageFile,
            json_encode($this->localDb, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Pre-seed default luxury salon services, retail inventory, and stylists in Pakistani Rupees (PKR).
     */
    public function seedDefaults(bool $force = false): void
    {
        if ($force) {
            $this->localDb = [];
        }

        $this->localDb['currency'] = 'PKR';

        // 1. Pakistani Beauté Studio Services
        $this->localDb['services'] = [
            'srv_01' => [
                'id' => 'srv_01',
                'name' => 'Signature 24K Gold Glow Facial',
                'category' => 'Skin & Facial',
                'price' => 8500.00,
                'duration' => 60,
                'description' => 'Pure 24K gold foil infusion with ultrasonic deep pore vacuuming and collagen lifting mask.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_02' => [
                'id' => 'srv_02',
                'name' => 'Hydra-Oxygenating Skin Rebirth',
                'category' => 'Skin & Facial',
                'price' => 12000.00,
                'duration' => 75,
                'description' => 'Multi-step vortex suction peel, intense hyaluronic hydration, and antioxidant cold hammer therapy.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1512290900672-1f48e35cfd80?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_03' => [
                'id' => 'srv_03',
                'name' => 'Brazilian Keratin Hair Smoothing',
                'category' => 'Hair Care',
                'price' => 24000.00,
                'duration' => 150,
                'description' => 'Formaldehyde-free intense organic keratin smoothing treatment for glossy, mirror-shine frizz-free hair.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1560869713-7d0a29430803?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_04' => [
                'id' => 'srv_04',
                'name' => 'Balayage, Babylights & Tone Gloss',
                'category' => 'Hair Care',
                'price' => 32000.00,
                'duration' => 180,
                'description' => 'Hand-painted dimensional seamless contouring with tone matching and Olaplex bond repair.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_05' => [
                'id' => 'srv_05',
                'name' => 'Crown Stylist Cut & Bouncy Blowdry',
                'category' => 'Hair Care',
                'price' => 4500.00,
                'duration' => 45,
                'description' => 'Bespoke precision layer cutting with scalp tension massage and voluminous red-carpet blowout.',
                'popular' => false,
                'image' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_06' => [
                'id' => 'srv_06',
                'name' => 'Deluxe Gel Velvet Manicure',
                'category' => 'Nails & Spa',
                'price' => 3500.00,
                'duration' => 50,
                'description' => 'Russian cuticle care, organic brown sugar exfoliation, shape architecture, and chip-free gel polish.',
                'popular' => false,
                'image' => 'https://images.unsplash.com/photo-1632345031435-8727f6897d53?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_07' => [
                'id' => 'srv_07',
                'name' => 'Aromatherapy Rose Pedicure',
                'category' => 'Nails & Spa',
                'price' => 4000.00,
                'duration' => 60,
                'description' => 'Fresh rose petal foot soak, herbal callus buffing, lavender mud mask & hot towel steam compress.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_08' => [
                'id' => 'srv_08',
                'name' => 'Full Sculpted Acrylic Extensions Set',
                'category' => 'Nails & Spa',
                'price' => 8500.00,
                'duration' => 90,
                'description' => 'Custom French ombre or chrome glaze finish with nail art accents and strengthening overlay.',
                'popular' => false,
                'image' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_09' => [
                'id' => 'srv_09',
                'name' => 'Royal Barat & Walima Bridal Couture Makeup',
                'category' => 'Bridal & Glam',
                'price' => 65000.00,
                'duration' => 210,
                'description' => 'Celebrity HD airbrush bridal makeup by Sehar, bespoke royal updo styling by Elia, 3D mink lashes, dupatta & jewelry setting.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1546804784-896d0dca3805?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_10' => [
                'id' => 'srv_10',
                'name' => 'Soft Glam Engagement / Nikkah Makeup',
                'category' => 'Bridal & Glam',
                'price' => 25000.00,
                'duration' => 90,
                'description' => 'Glass skin finish, soft smoked winged eye, contoured lip sculpt, and textured Hollywood waves.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_11' => [
                'id' => 'srv_11',
                'name' => 'Royal Bridal Henna / Mehendi Artisan Suite',
                'category' => 'Henna & Mehendi',
                'price' => 16000.00,
                'duration' => 180,
                'description' => 'Organic herbal Rajasthani henna: intricate bridal motifs extending up to elbows and feet with dark stain assurance.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_12' => [
                'id' => 'srv_12',
                'name' => 'Party Henna / Mehendi Artisan Design (Both Hands)',
                'category' => 'Henna & Mehendi',
                'price' => 4500.00,
                'duration' => 45,
                'description' => 'Contemporary Arabic floral belts or traditional Mughlai jali motifs for festive occasions.',
                'popular' => true,
                'image' => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_13' => [
                'id' => 'srv_13',
                'name' => 'Swedish Aromatherapy Tension Relief Massage',
                'category' => 'Massage & Body',
                'price' => 9500.00,
                'duration' => 60,
                'description' => 'Full body muscular rejuvenation with warmed jasmine and therapeutic lavender oils.',
                'popular' => false,
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_14' => [
                'id' => 'srv_14',
                'name' => 'Volcanic Hot Stone Deep Tissue Therapy',
                'category' => 'Massage & Body',
                'price' => 12500.00,
                'duration' => 75,
                'description' => 'Basalt heated volcanic stones combined with deep muscular stress release techniques.',
                'popular' => false,
                'image' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            'srv_15' => [
                'id' => 'srv_15',
                'name' => 'Dermaplaning & Brightening Enzyme Peel',
                'category' => 'Skin & Facial',
                'price' => 7500.00,
                'duration' => 45,
                'description' => 'Medical-grade surgical blade gentle peach fuzz removal followed by cooling peptide mask.',
                'popular' => false,
                'image' => 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=600&q=80',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // 2. Retail Beauty Products in PKR
        $this->localDb['inventory'] = [
            'inv_01' => [
                'id' => 'inv_01',
                'name' => 'Olaplex No. 3 Hair Perfector 100ml',
                'category' => 'Hair Care',
                'sku' => 'OLP-003',
                'stock' => 14,
                'min_stock' => 5,
                'cost_price' => 6800.00,
                'selling_price' => 9500.00,
                'supplier' => 'Olaplex Official Pakistan',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_02' => [
                'id' => 'inv_02',
                'name' => 'Moroccanoil Treatment Original 100ml',
                'category' => 'Hair Care',
                'sku' => 'MRO-100',
                'stock' => 18,
                'min_stock' => 6,
                'cost_price' => 8500.00,
                'selling_price' => 12800.00,
                'supplier' => 'Argan Luxury Imports Pakistan',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_03' => [
                'id' => 'inv_03',
                'name' => 'L\'Oréal Professionnel Mythic Oil 100ml',
                'category' => 'Hair Care',
                'sku' => 'LOR-MYTH',
                'stock' => 16,
                'min_stock' => 5,
                'cost_price' => 4200.00,
                'selling_price' => 6500.00,
                'supplier' => 'L\'Oréal Pakistan Distribution',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_04' => [
                'id' => 'inv_04',
                'name' => 'Dermalogica Daily Microfoliant 74g',
                'category' => 'Skin & Facial',
                'sku' => 'DER-MIC74',
                'stock' => 8,
                'min_stock' => 4,
                'cost_price' => 12500.00,
                'selling_price' => 18000.00,
                'supplier' => 'Dermalogica Official PK',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_05' => [
                'id' => 'inv_05',
                'name' => 'Pure Rosewater Botanical Facial Mist 200ml',
                'category' => 'Skin & Facial',
                'sku' => 'ROS-TN200',
                'stock' => 3, // LOW STOCK
                'min_stock' => 8,
                'cost_price' => 950.00,
                'selling_price' => 1850.00,
                'supplier' => 'Organic Punjab Botanicals',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_06' => [
                'id' => 'inv_06',
                'name' => 'Charlotte Tilbury Airbrush Setting Spray 100ml',
                'category' => 'Cosmetics',
                'sku' => 'CT-AIR100',
                'stock' => 2, // LOW STOCK
                'min_stock' => 5,
                'cost_price' => 8200.00,
                'selling_price' => 11500.00,
                'supplier' => 'Luxury Beauty Imports Lahore',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_07' => [
                'id' => 'inv_07',
                'name' => 'OPI Pro-Stay GelColor Top Coat 15ml',
                'category' => 'Nails & Spa',
                'sku' => 'OPI-TOP01',
                'stock' => 25,
                'min_stock' => 8,
                'cost_price' => 3200.00,
                'selling_price' => 4800.00,
                'supplier' => 'OPI Pakistan',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_08' => [
                'id' => 'inv_08',
                'name' => 'Organic Rajasthani Henna Cones (Pack of 6)',
                'category' => 'Henna & Mehendi',
                'sku' => 'HEN-RAJS6',
                'stock' => 40,
                'min_stock' => 15,
                'cost_price' => 600.00,
                'selling_price' => 1200.00,
                'supplier' => 'Artisan Henna Collective',
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'inv_09' => [
                'id' => 'inv_09',
                'name' => 'Kérastase Elixir Ultime L\'Huile Originale',
                'category' => 'Hair Care',
                'sku' => 'KER-ULT50',
                'stock' => 10,
                'min_stock' => 4,
                'cost_price' => 10500.00,
                'selling_price' => 15500.00,
                'supplier' => 'L\'Oréal Luxury Division',
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // 3. Studio Artists Roster
        $this->localDb['staff'] = [
            'stf_01' => [
                'id' => 'stf_01',
                'name' => 'Elia Khan',
                'role' => 'Co-Founder & Creative Director (Master Hair Artist)',
                'commission_rate' => 20,
                'phone' => '+92 (300) 845-2581',
                'status' => 'active',
            ],
            'stf_02' => [
                'id' => 'stf_02',
                'name' => 'Sehar Tariq',
                'role' => 'Co-Founder & Celebrity Bridal Makeup Artist',
                'commission_rate' => 20,
                'phone' => '+92 (300) 845-2582',
                'status' => 'active',
            ],
            'stf_03' => [
                'id' => 'stf_03',
                'name' => 'Amina Al-Mansoor',
                'role' => 'Lead Esthetician & Skin Aesthetician',
                'commission_rate' => 12,
                'phone' => '+92 (300) 845-2583',
                'status' => 'active',
            ],
            'stf_04' => [
                'id' => 'stf_04',
                'name' => 'Farheen Noor',
                'role' => 'Master Henna & Mehendi Artisan',
                'commission_rate' => 15,
                'phone' => '+92 (300) 845-2584',
                'status' => 'active',
            ],
            'stf_05' => [
                'id' => 'stf_05',
                'name' => 'Chloe Bennett',
                'role' => 'Senior Nail Artist & Spa Specialist',
                'commission_rate' => 10,
                'phone' => '+92 (300) 845-2585',
                'status' => 'active',
            ],
        ];

        // 4. Sample PKR Invoices
        $today = date('Y-m-d');
        $this->localDb['invoices'] = [
            'INV-PKR-1001' => [
                'id' => 'INV-PKR-1001',
                'invoice_number' => 'INV-PKR-1001',
                'customer_name' => 'Mahnoor Bilal',
                'customer_phone' => '+923018249911',
                'customer_email' => 'mahnoor.bilal@example.pk',
                'stylist_id' => 'stf_02',
                'stylist_name' => 'Sehar Tariq',
                'items' => [
                    [
                        'id' => 'srv_09',
                        'type' => 'service',
                        'name' => 'Royal Barat & Walima Bridal Couture Makeup',
                        'price' => 65000.00,
                        'quantity' => 1,
                        'total' => 65000.00,
                    ],
                    [
                        'id' => 'srv_11',
                        'type' => 'service',
                        'name' => 'Royal Bridal Henna / Mehendi Artisan Suite',
                        'price' => 16000.00,
                        'quantity' => 1,
                        'total' => 16000.00,
                    ]
                ],
                'subtotal' => 81000.00,
                'discount_percent' => 5,
                'discount_amount' => 4050.00,
                'tax_percent' => 5.0,
                'tax_amount' => 3847.50,
                'total' => 80797.50,
                'payment_method' => 'Card',
                'status' => 'Paid',
                'notes' => 'Bridal consultation completed with complimentary facial kit.',
                'created_at' => $today . ' 12:30:00',
                'pdf_path' => 'storage/invoices/INV-PKR-1001.pdf',
            ],
            'INV-PKR-1002' => [
                'id' => 'INV-PKR-1002',
                'invoice_number' => 'INV-PKR-1002',
                'customer_name' => 'Ayla Jehangir',
                'customer_phone' => '+923214567890',
                'customer_email' => 'ayla.j@example.pk',
                'stylist_id' => 'stf_01',
                'stylist_name' => 'Elia Khan',
                'items' => [
                    [
                        'id' => 'srv_04',
                        'type' => 'service',
                        'name' => 'Balayage, Babylights & Tone Gloss',
                        'price' => 32000.00,
                        'quantity' => 1,
                        'total' => 32000.00,
                    ],
                    [
                        'id' => 'inv_01',
                        'type' => 'product',
                        'name' => 'Olaplex No. 3 Hair Perfector 100ml',
                        'price' => 9500.00,
                        'quantity' => 1,
                        'total' => 9500.00,
                    ]
                ],
                'subtotal' => 41500.00,
                'discount_percent' => 0,
                'discount_amount' => 0.00,
                'tax_percent' => 5.0,
                'tax_amount' => 2075.00,
                'total' => 43575.00,
                'payment_method' => 'Online',
                'status' => 'Paid',
                'notes' => 'Touchup recommended in 8 weeks.',
                'created_at' => $today . ' 15:45:00',
                'pdf_path' => 'storage/invoices/INV-PKR-1002.pdf',
            ]
        ];

        $this->persistLocalDb();
    }

    /**
     * Get all documents from a collection.
     */
    public function getCollection(string $collection, ?string $orderBy = null, ?int $limit = null, array $filters = []): array
    {
        if ($this->isLive) {
            try {
                return $this->getCloudCollection($collection, $orderBy, $limit, $filters);
            } catch (\Throwable $e) {
                error_log("Firestore REST Error: " . $e->getMessage() . ". Falling back to local emulator.");
            }
        }

        $items = $this->localDb[$collection] ?? [];
        $result = array_values($items);

        // Apply filters
        if (!empty($filters)) {
            $result = array_filter($result, function ($item) use ($filters) {
                foreach ($filters as $key => $val) {
                    if (!isset($item[$key]) || $item[$key] != $val) {
                        return false;
                    }
                }
                return true;
            });
            $result = array_values($result);
        }

        // Order by
        if ($orderBy) {
            $desc = false;
            if (str_starts_with($orderBy, '-')) {
                $desc = true;
                $orderBy = substr($orderBy, 1);
            }
            usort($result, function ($a, $b) use ($orderBy, $desc) {
                $valA = $a[$orderBy] ?? '';
                $valB = $b[$orderBy] ?? '';
                if ($valA == $valB) return 0;
                $cmp = ($valA < $valB) ? -1 : 1;
                return $desc ? -$cmp : $cmp;
            });
        }

        // Limit
        if ($limit !== null && $limit > 0) {
            $result = array_slice($result, 0, $limit);
        }

        return $result;
    }

    /**
     * Get single document by ID.
     */
    public function getDocument(string $collection, string $id): ?array
    {
        if ($this->isLive) {
            try {
                return $this->getCloudDocument($collection, $id);
            } catch (\Throwable $e) {
                error_log("Firestore REST getDocument error: " . $e->getMessage());
            }
        }

        return $this->localDb[$collection][$id] ?? null;
    }

    /**
     * Create document in collection.
     */
    public function createDocument(string $collection, array $data, ?string $customId = null): array
    {
        $id = $customId ?: ($collection . '_' . bin2hex(random_bytes(6)));
        $data['id'] = $id;
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        if ($this->isLive) {
            try {
                $this->saveCloudDocument($collection, $id, $data);
            } catch (\Throwable $e) {
                error_log("Firestore REST create error: " . $e->getMessage());
            }
        }

        $this->localDb[$collection][$id] = $data;
        $this->persistLocalDb();

        return $data;
    }

    /**
     * Update an existing document.
     */
    public function updateDocument(string $collection, string $id, array $data): ?array
    {
        $existing = $this->getDocument($collection, $id) ?? [];
        $merged = array_merge($existing, $data);
        $merged['id'] = $id;
        $merged['updated_at'] = date('Y-m-d H:i:s');

        if ($this->isLive) {
            try {
                $this->saveCloudDocument($collection, $id, $merged);
            } catch (\Throwable $e) {
                error_log("Firestore REST update error: " . $e->getMessage());
            }
        }

        $this->localDb[$collection][$id] = $merged;
        $this->persistLocalDb();

        return $merged;
    }

    /**
     * Delete document.
     */
    public function deleteDocument(string $collection, string $id): bool
    {
        if ($this->isLive) {
            try {
                $this->deleteCloudDocument($collection, $id);
            } catch (\Throwable $e) {
                error_log("Firestore REST delete error: " . $e->getMessage());
            }
        }

        if (isset($this->localDb[$collection][$id])) {
            unset($this->localDb[$collection][$id]);
            $this->persistLocalDb();
            return true;
        }

        return false;
    }

    /* Cloud Firestore helpers */
    private function getCloudEndpoint(string $collection, ?string $docId = null): string
    {
        $base = sprintf(
            "%s/projects/%s/databases/%s/documents/%s",
            $this->config['rest_endpoint'],
            $this->config['project_id'],
            $this->config['database'],
            $collection
        );
        return $docId ? "$base/$docId" : $base;
    }

    private function getCloudCollection(string $collection, ?string $orderBy = null, ?int $limit = null, array $filters = []): array
    {
        $url = $this->getCloudEndpoint($collection);
        if ($limit) $url .= '?pageSize=' . $limit;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 8,
        ]);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) throw new Exception("Firestore list error HTTP $status");

        $data = json_decode($res, true);
        $docs = $data['documents'] ?? [];
        $out = [];
        foreach ($docs as $doc) {
            $parsed = $this->decodeFirestoreFields($doc['fields'] ?? []);
            $parsed['id'] = basename($doc['name']);
            $out[] = $parsed;
        }
        return $out;
    }

    private function getCloudDocument(string $collection, string $id): ?array
    {
        $url = $this->getCloudEndpoint($collection, $id);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 8,
        ]);
        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 404) return null;
        if ($status !== 200) throw new Exception("Firestore get doc error HTTP $status");

        $doc = json_decode($res, true);
        $parsed = $this->decodeFirestoreFields($doc['fields'] ?? []);
        $parsed['id'] = $id;
        return $parsed;
    }

    private function saveCloudDocument(string $collection, string $id, array $data): void
    {
        $url = $this->getCloudEndpoint($collection, $id);
        $payload = ['fields' => $this->encodeFirestoreFields($data)];
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 8,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    private function deleteCloudDocument(string $collection, string $id): void
    {
        $url = $this->getCloudEndpoint($collection, $id);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $this->accessToken],
            CURLOPT_TIMEOUT => 8,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    private function encodeFirestoreFields(array $data): array
    {
        $fields = [];
        foreach ($data as $key => $val) {
            $fields[$key] = $this->encodeFirestoreValue($val);
        }
        return $fields;
    }

    private function encodeFirestoreValue($val): array
    {
        if (is_null($val)) return ['nullValue' => null];
        if (is_bool($val)) return ['booleanValue' => $val];
        if (is_int($val)) return ['integerValue' => (string)$val];
        if (is_float($val)) return ['doubleValue' => $val];
        if (is_string($val)) return ['stringValue' => $val];
        if (is_array($val)) {
            $isAssoc = array_keys($val) !== range(0, count($val) - 1);
            if ($isAssoc) {
                return ['mapValue' => ['fields' => $this->encodeFirestoreFields($val)]];
            } else {
                $values = [];
                foreach ($val as $item) {
                    $values[] = $this->encodeFirestoreValue($item);
                }
                return ['arrayValue' => ['values' => $values]];
            }
        }
        return ['stringValue' => (string)$val];
    }

    private function decodeFirestoreFields(array $fields): array
    {
        $result = [];
        foreach ($fields as $key => $valObj) {
            $result[$key] = $this->decodeFirestoreValue($valObj);
        }
        return $result;
    }

    private function decodeFirestoreValue(array $valObj)
    {
        if (array_key_exists('stringValue', $valObj)) return $valObj['stringValue'];
        if (array_key_exists('integerValue', $valObj)) return (int)$valObj['integerValue'];
        if (array_key_exists('doubleValue', $valObj)) return (float)$valObj['doubleValue'];
        if (array_key_exists('booleanValue', $valObj)) return (bool)$valObj['booleanValue'];
        if (array_key_exists('nullValue', $valObj)) return null;
        if (array_key_exists('timestampValue', $valObj)) return $valObj['timestampValue'];
        if (array_key_exists('mapValue', $valObj)) {
            return $this->decodeFirestoreFields($valObj['mapValue']['fields'] ?? []);
        }
        if (array_key_exists('arrayValue', $valObj)) {
            $arr = [];
            foreach ($valObj['arrayValue']['values'] ?? [] as $sub) {
                $arr[] = $this->decodeFirestoreValue($sub);
            }
            return $arr;
        }
        return null;
    }
}
