# ELARA Parlor & Spa Management System 🌸✨

A modular, production-ready full-stack PHP Web Application designed for luxury ladies' beauty parlors, salons, and wellness day spas.

---

## 🌟 Key Highlights & Capabilities

- **Modern MVC Architecture & RESTful API:** Clean separation of concerns with PSR-4 autoloading via Composer, modular controllers, and service layers.
- **Google Cloud Firestore NoSQL Integration:**
  - Full CRUD operations with collection querying, document creation, updates, and deletion.
  - Native dual-mode operation: Connects to live Google Cloud Firestore via service account JWT credentials (`serviceAccountKey.json`) OR gracefully falls back to a high-speed local persistent JSON Firestore emulator (`storage/firestore_db.json`) so the application operates seamlessly out of the box.
- **Interactive POS Terminal:**
  - Real-time catalog filtering by beauty category (Hair Care, Skin & Facial, Nails & Spa, Bridal & Glam, Massages, Retail Products) and instant live search.
  - Reactive shopping cart with live subtotal, configurable tax, customizable discounts, and stylist commission tagging.
  - Automatic inventory stock decrement upon retail product checkout.
- **Luxury PDF Invoices (Dompdf):**
  - Generates downloadable and shareable branded PDF invoices with parlor branding, client info, stylist station, breakdown, and electronic verification stamps.
  - Auto-saved to `public/storage/invoices/` with dedicated streaming and download endpoints.
- **ESC/POS Thermal Receipt Layout:**
  - 80mm & 58mm POS receipt layout with browser-optimized `@media print` CSS.
  - Dashed dividers, monospace typography, transaction metadata, and barcode simulation.
- **WhatsApp Integration:**
  - Generates WhatsApp Click-to-Chat deep links (`https://wa.me/{phone}?text=...`) pre-filled with luxury customer greetings, itemized service breakdown, grand total, and direct PDF invoice download URLs.
  - Includes structured message templates ready for Meta WhatsApp Cloud API (Graph API).
- **ELARA AI Salon Copilot & Operations Advisor:**
  - Floating AI assistant widget available on all pages.
  - Injected with real-time operational salon context: today's revenue, active transactions, low-stock inventory warnings, stylist roster, and catalog treatments.
  - Integrated with Google Gemini API (`gemini-1.5-flash`), OpenAI API, and intelligent rule-based salon expert engine.

---

## 📁 Directory Structure

```text
elara-parlor/
├── composer.json              # Project dependencies & PSR-4 autoloading
├── .env                       # Environment configuration
├── .env.example               # Template environment configuration
├── serviceAccountKey.json     # Google Cloud Service Account credentials
├── config/
│   ├── app.php                # App branding, tax rates, currency, AI keys
│   └── firestore.php          # Firestore endpoints & credentials setup
├── public/
│   ├── index.php              # Front controller & router
│   ├── assets/
│   │   ├── css/receipt.css    # 80mm/58mm thermal printer print stylesheet
│   │   └── js/
│   │       ├── pos.js         # POS terminal & cart reactive engine
│   │       └── elara-chat.js  # Floating ELARA AI chat assistant widget
│   └── storage/invoices/      # Generated PDF invoices directory
├── src/
│   ├── Controllers/
│   │   ├── BillingController.php      # POS checkout, receipt & PDF streaming
│   │   ├── ServiceController.php      # Salon treatments catalog CRUD
│   │   ├── InventoryController.php    # Retail products & stock adjustments
│   │   ├── ReportController.php       # Analytics, charts & CSV export
│   │   └── ElaraAgentController.php   # AI Salon Copilot & Gemini handler
│   ├── Services/
│   │   ├── FirestoreService.php       # Central Firestore CRUD & emulator
│   │   ├── PdfService.php             # Luxury branded PDF invoices via Dompdf
│   │   └── WhatsAppService.php        # wa.me deep links & Cloud API templates
│   └── Views/
│       ├── layouts/
│       │   ├── header.php             # Navigation, Firestore badge, AI trigger
│       │   └── footer.php             # Footer & Floating AI chat modal
│       ├── dashboard.php              # Metrics, recent transactions, quick actions
│       ├── pos.php                    # Full-screen POS Terminal
│       ├── inventory.php              # Inventory table, alerts & restock modal
│       ├── services.php               # Treatment catalog management
│       ├── invoices.php               # Invoice history, search & WhatsApp shares
│       ├── reports.php                # Financial analytics, commissions & CSV export
│       └── receipt_thermal.php        # 80mm ESC/POS thermal receipt view
├── test_runner.php            # Automated test suite (14 passing tests)
└── storage/
    └── firestore_db.json      # Persistent local Firestore emulator datastore
```

---

## 🚀 Quick Start Guide

### 1. Requirements
- PHP 8.2 or 8.3 (with `curl`, `dom`, `fileinfo`, `mbstring`, `openssl`, `gd` enabled)
- Composer

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
Review and customize `.env` as desired:
```env
APP_NAME="ELARA Parlor & Spa Management System"
APP_URL="http://localhost:8080"
PARLOR_NAME="ELARA Beaute Studio"
CURRENCY_SYMBOL="Rs"
PARLOR_TAX_RATE=8.0

# Optional: Add Gemini API Key for open-domain reasoning
GEMINI_API_KEY="your-gemini-api-key"
```

### 4. Start the Application
Run the PHP built-in web server:
```bash
php -S localhost:8080 -t public
```
Visit `http://localhost:8080` in your web browser.

---

## 🧪 Automated Testing

Execute the automated test suite verifying Firestore CRUD, Dompdf invoice generation, WhatsApp link generation, and AI agent reasoning:
```bash
php test_runner.php
```

All 14 tests pass with zero errors.

---

## ☁️ Connecting Live Google Cloud Firestore

1. In the Google Cloud Console, navigate to **IAM & Admin** > **Service Accounts**.
2. Create a service account with the **Cloud Datastore User** or **Firebase Admin** role.
3. Generate a JSON private key and replace `serviceAccountKey.json` in the project root.
4. Set `FIRESTORE_PROJECT_ID` in `.env` to your GCP Project ID.
5. The application will automatically detect your live credentials and switch the badge in the navigation bar to `🟢 Live Firestore (GCP)`.

---

## 📄 License
MIT License. Built for ELARA Luxury Parlor & Spa.

