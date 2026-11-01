# cPanel Deployment Guide — ELARA Beauté Studio 🌸🇵🇰

This guide outlines how to host the **ELARA Beauté Studio Management System & Web Portal** on any standard cPanel web hosting (e.g. Namecheap, Hostinger, Bluehost, Server4Sale, Nexus, GoDaddy).

---

## 📋 Prerequisites
1. **cPanel Web Hosting** with PHP 8.2 or PHP 8.3 support.
2. Enabled PHP extensions in cPanel:
   - `curl`
   - `mbstring`
   - `openssl`
   - `fileinfo`
   - `dom` / `xml`
   - `gd` (required for PDF logo and invoice image processing)

---

## 🚀 Step-by-Step Deployment Options

### Method A: Recommended Best Practice (Root outside `public_html`)

1. **Log in to cPanel** & open **File Manager**.
2. Create a folder named `elara_app` in your home directory (e.g., `/home/username/elara_app`).
3. Upload all project folders (`config`, `src`, `storage`, `vendor`, `.env`, `composer.json`, `serviceAccountKey.json`) into `/home/username/elara_app`.
4. Upload all files from the `public/` directory directly into your cPanel `public_html/`.
5. Open `public_html/index.php` and update the vendor path on line 13 to:
   ```php
   require_once dirname(__DIR__) . '/elara_app/vendor/autoload.php';
   ```
   *(This ensures sensitive files like `.env` and `serviceAccountKey.json` are completely outside the web accessible folder!)*

---

### Method B: Quick 1-Zip Upload (Direct to `public_html`)

If your cPanel host does not allow access outside `public_html`:
1. Compress the entire project into a `.zip` file.
2. In cPanel **File Manager**, navigate into `public_html/`.
3. Upload and extract the `.zip`.
4. Our pre-configured root `.htaccess` and root `index.php` automatically proxy all web traffic to the `public/` folder while strictly blocking public HTTP access to `.env`, `serviceAccountKey.json`, and `composer.json`.

---

## ⚙️ Configuration in cPanel

### 1. Select PHP Version (PHP 8.2 or 8.3)
1. Go to cPanel > **MultiPHP Manager** (or **Select PHP Version**).
2. Select your domain and choose **PHP 8.2** or **PHP 8.3**.
3. Under **PHP Extensions**, ensure `curl`, `gd`, `mbstring`, `fileinfo`, `openssl`, and `xml` are checked.

### 2. Configure `.env`
In cPanel File Manager, edit `.env`:
```env
APP_NAME="ELARA Beauté Studio"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://elara.datapointtechnology.com"

# WhatsApp & Contact
PARLOR_PHONE="0335 2004959"
PARLOR_WHATSAPP="+923352004959"
CURRENCY_SYMBOL="Rs. "
CURRENCY_CODE="PKR"
```

### 3. File Permissions
Ensure the following directories have write permissions (chmod `775` or `755`):
- `storage/`
- `public/storage/invoices/`

---

## 🌐 URLs & Routing
- **Public Customer Web Portal:** `https://elara.datapointtechnology.com/`
- **Studio Staff & Management Portal:** `https://elara.datapointtechnology.com/dashboard`
- **POS Terminal:** `https://elara.datapointtechnology.com/pos`
- **Services Catalog:** `https://elara.datapointtechnology.com/services`
- **Inventory & Restock:** `https://elara.datapointtechnology.com/inventory`
- **Guest Invoices History:** `https://elara.datapointtechnology.com/invoices`
- **Reports & Analytics:** `https://elara.datapointtechnology.com/reports`

