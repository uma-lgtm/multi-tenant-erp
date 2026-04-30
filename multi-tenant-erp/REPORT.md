# Multi-Tenant SaaS ERP Application - Technical Report

## 1. Project Overview

This is a **multi-tenant SaaS ERP platform** built with Laravel 11 where each client (tenant) gets:
- A **dedicated subdomain** (e.g., `acme.localhost`, `beta.localhost`)
- A **completely isolated MySQL database** (e.g., `tenantacme`, `tenantbeta`)
- Their own **users, roles, and permissions**
- Independent **Invoicing** and **Inventory** modules

All tenants share the **same codebase** and **same server** while maintaining complete data isolation.

---

## 2. Technology Stack

| Layer         | Technology                          |
|---------------|-------------------------------------|
| Framework     | Laravel 11 (PHP 8.2)               |
| Multi-Tenancy | stancl/tenancy v3.10                |
| Auth          | Laravel Breeze (Blade + Tailwind)   |
| RBAC          | spatie/laravel-permission v6        |
| Frontend      | Blade Templates + Tailwind CSS      |
| Database      | MySQL (separate DB per tenant)      |
| Server        | PHP Built-in Server (dev)           |

---

## 3. Architecture Diagram

```
                    +---------------------------+
                    |    Central Domain          |
                    |    localhost:8000           |
                    |                            |
                    |  - Landing Page            |
                    |  - Signup Form             |
                    +------------+--------------+
                                 |
                    (Tenant Provisioning on Signup)
                                 |
              +------------------+------------------+
              |                                     |
   +----------v-----------+           +-------------v----------+
   |  acme.localhost:8000  |          |  beta.localhost:8000    |
   |                       |          |                         |
   |  DB: tenantacme       |          |  DB: tenantbeta         |
   |  User: John Doe       |          |  User: Jane Smith       |
   |  Invoices: 1          |          |  Invoices: 0            |
   |  Products: 1          |          |  Products: 0            |
   +----------+------------+          +-------------+-----------+
              |                                     |
              +------------------+------------------+
                                 |
                    +------------v--------------+
                    |     MySQL Server           |
                    |                            |
                    |  erp_central (shared)       |
                    |    - tenants table          |
                    |    - domains table          |
                    |                            |
                    |  tenantacme (isolated)      |
                    |    - users, invoices,       |
                    |      products, roles...     |
                    |                            |
                    |  tenantbeta (isolated)      |
                    |    - users, invoices,       |
                    |      products, roles...     |
                    +----------------------------+
```

---

## 4. Database Structure

### 4.1 Central Database (`erp_central`)

This database is shared across all tenants and stores only tenant metadata.

| Table       | Purpose                                | Records |
|-------------|----------------------------------------|---------|
| `tenants`   | Stores tenant ID, name, DB connection  | 2       |
| `domains`   | Maps subdomains to tenants             | 2       |
| `migrations`| Tracks central migration history       | 2       |

**Tenants Table:**
| ID   | Name      | Created At          |
|------|-----------|---------------------|
| acme | Acme Corp | 2026-04-30 21:19:04 |
| beta | Beta Inc  | 2026-04-30 21:22:57 |

**Domains Table:**
| Domain | Tenant ID |
|--------|-----------|
| acme   | acme      |
| beta   | beta      |

### 4.2 Tenant Database (e.g., `tenantacme`)

Each tenant gets a **full, independent database** with 16 tables:

| Table                   | Purpose                              |
|-------------------------|--------------------------------------|
| `users`                 | Tenant's users (auth)                |
| `invoices`              | Accounting module data               |
| `products`              | Inventory module data                |
| `roles`                 | RBAC roles (admin, staff)            |
| `permissions`           | RBAC permissions (8 permissions)     |
| `role_has_permissions`  | Role-permission assignments          |
| `model_has_roles`       | User-role assignments                |
| `model_has_permissions` | Direct user-permission assignments   |
| `sessions`              | User sessions                        |
| `password_reset_tokens` | Password reset flow                  |
| `cache` / `cache_locks` | Application cache                   |
| `jobs` / `job_batches` / `failed_jobs` | Queue system          |
| `migrations`            | Tenant migration history             |

### 4.3 Data Isolation Proof

| Metric         | Acme Corp (`tenantacme`) | Beta Inc (`tenantbeta`) |
|----------------|--------------------------|-------------------------|
| Users          | 1 (John Doe)             | 1 (Jane Smith)          |
| Invoices       | 1                        | 0                       |
| Products       | 1                        | 0                       |
| Roles          | 2 (admin, staff)         | 2 (admin, staff)        |
| Permissions    | 8                        | 8                       |

Beta Inc has **zero visibility** into Acme Corp's data. They operate on completely separate databases.

---

## 5. Application Flow

### 5.1 Tenant Signup Flow

```
User visits localhost:8000
        |
        v
   Landing Page (features, how it works)
        |
   Clicks "Get Started"
        |
        v
   Signup Form
   - Company Name: "Acme Corp"
   - Subdomain: "acme"
   - Admin Name: "John Doe"
   - Email: "john@acme.com"
   - Password: ********
        |
   Clicks "Create Workspace"
        |
        v
   Backend Processing (SignupController):
   1. Validate input (unique subdomain, email format, etc.)
   2. Create Tenant record in central DB
   3. Auto-create MySQL database "tenantacme"
   4. Run all tenant migrations (users, invoices, products, etc.)
   5. Seed default roles & permissions
   6. Create admin user inside tenant database
   7. Assign "admin" role to the user
        |
        v
   Redirect to acme.localhost:8000/login
        |
   User logs in with their credentials
        |
        v
   Tenant Dashboard (isolated workspace)
```

### 5.2 Tenant Request Lifecycle

```
Browser Request: GET http://acme.localhost:8000/dashboard
        |
        v
   Laravel Router matches tenant routes
        |
        v
   Middleware Stack:
   1. web (session, CSRF, cookies)
   2. InitializeTenancyBySubdomain
      - Extracts "acme" from "acme.localhost"
      - Looks up "acme" in the domains table
      - Finds tenant "acme" -> DB "tenantacme"
      - Switches DB connection to tenantacme
      - Switches storage path, cache prefix
   3. PreventAccessFromCentralDomains
   4. auth (verify user is logged in)
        |
        v
   DashboardController::index()
   - All Eloquent queries now run against tenantacme
   - Invoice::count() -> queries tenantacme.invoices
   - Product::count() -> queries tenantacme.products
        |
        v
   Returns tenant dashboard view
```

---

## 6. RBAC (Role-Based Access Control)

Each tenant database has its own roles and permissions, seeded automatically on signup.

### Roles
| Role    | Description                          |
|---------|--------------------------------------|
| `admin` | Full access to all modules           |
| `staff` | View-only access to invoices/products|

### Permissions (8 total)
| Permission       | Admin | Staff |
|------------------|-------|-------|
| view invoices    | Yes   | Yes   |
| create invoices  | Yes   | No    |
| edit invoices    | Yes   | No    |
| delete invoices  | Yes   | No    |
| view products    | Yes   | Yes   |
| create products  | Yes   | No    |
| edit products    | Yes   | No    |
| delete products  | Yes   | No    |

---

## 7. ERP Modules

### 7.1 Accounting - Invoices Module

| Feature          | Status  |
|------------------|---------|
| List invoices    | Working |
| Create invoice   | Working |
| View invoice     | Working |
| Status tracking  | Working (Draft / Sent / Paid) |
| Pagination       | Working |

**Invoice Fields:** Invoice Number, Client Name, Amount, Status, Due Date, Notes

### 7.2 Inventory - Products Module

| Feature          | Status  |
|------------------|---------|
| List products    | Working |
| Add product      | Working |
| View product     | Working |
| Stock tracking   | Working (quantity with color indicators) |
| Pagination       | Working |

**Product Fields:** Name, SKU, Description, Price, Quantity, Category

---

## 8. Security Features

| Feature                     | Implementation                                    |
|-----------------------------|---------------------------------------------------|
| CSRF Protection             | Laravel's built-in `@csrf` on all forms           |
| Password Hashing            | Bcrypt (12 rounds)                                |
| Database Isolation           | Separate MySQL database per tenant                |
| Session Isolation            | Per-subdomain session cookies                     |
| Input Validation             | Server-side validation on all form submissions    |
| SQL Injection Prevention     | Eloquent ORM with parameterized queries           |
| XSS Prevention               | Blade's `{{ }}` auto-escaping                    |
| Subdomain Validation         | Reserved words blocked (www, api, admin, etc.)   |
| Permission Cache Isolation   | Spatie cache key separated per tenant             |

---

## 9. File Structure

```
multi-tenant-erp/
|-- app/
|   |-- Http/Controllers/
|   |   |-- Central/
|   |   |   +-- SignupController.php        # Signup & tenant provisioning
|   |   +-- Tenant/
|   |       |-- DashboardController.php     # Tenant dashboard
|   |       |-- InvoiceController.php       # Invoice CRUD
|   |       +-- ProductController.php       # Product CRUD
|   |-- Models/
|   |   |-- Tenant.php                      # Multi-tenant model
|   |   |-- User.php                        # User with RBAC
|   |   |-- Invoice.php                     # Invoice model
|   |   +-- Product.php                     # Product model
|   +-- Providers/
|       +-- TenancyServiceProvider.php      # Tenancy event pipeline
|-- config/
|   |-- database.php                        # Central + tenant DB connections
|   |-- tenancy.php                         # Tenancy configuration
|   +-- session.php                         # Session cookie settings
|-- database/
|   |-- migrations/
|   |   |-- create_tenants_table.php        # Central: tenants
|   |   |-- create_domains_table.php        # Central: domains
|   |   +-- tenant/
|   |       |-- create_users_table.php      # Tenant: users
|   |       |-- create_permission_tables.php# Tenant: RBAC
|   |       |-- create_invoices_table.php   # Tenant: invoices
|   |       +-- create_products_table.php   # Tenant: products
|   +-- seeders/
|       +-- TenantDatabaseSeeder.php        # Seeds roles & permissions
|-- routes/
|   |-- web.php                             # Central routes (landing, signup)
|   |-- tenant.php                          # Tenant routes (dashboard, CRUD)
|   +-- tenant-auth.php                     # Tenant auth (login, logout)
+-- resources/views/
    |-- central/
    |   |-- landing.blade.php               # Marketing landing page
    |   +-- signup.blade.php                # Signup form
    |-- tenant/
    |   |-- dashboard.blade.php             # Tenant dashboard
    |   |-- invoices/ (index, create, show)
    |   +-- products/ (index, create, show)
    +-- layouts/
        |-- central.blade.php               # Central layout
        +-- tenant.blade.php                # Tenant layout with sidebar
```

---

## 10. How to Run

### Prerequisites
- PHP 8.2+
- Composer 2.x
- MySQL running locally
- Node.js 18+

### Setup Commands
```bash
cd multi-tenant-erp

# Install dependencies
composer install
npm install && npm run build

# Create central database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS erp_central"

# Run central migrations
php artisan migrate

# Start the server
php artisan serve --host=localhost --port=8000
```

### Demo Flow
1. Open `http://localhost:8000` in Chrome/Edge
2. Click "Get Started" and register "Acme Corp" with subdomain `acme`
3. Login at `http://acme.localhost:8000/login`
4. Create invoices and products
5. Go back to `http://localhost:8000` and register "Beta Inc" with subdomain `beta`
6. Login at `http://beta.localhost:8000/login`
7. Verify the dashboard shows 0 invoices and 0 products (data isolation proven)

---

## 11. Production Deployment Path

To take this from demo to production, the following additions are needed:

| Area               | What's Needed                                           |
|--------------------|---------------------------------------------------------|
| Domain             | Purchase domain (~$1/yr .xyz) + wildcard DNS            |
| Hosting            | VPS ($5/mo) or Railway/Render with MySQL add-on         |
| SSL                | Wildcard SSL certificate (free via Let's Encrypt)       |
| Billing            | Stripe + Laravel Cashier for subscription plans         |
| Queue              | Redis + Laravel Horizon for async tenant provisioning   |
| Admin Panel        | Filament PHP for central admin (manage all tenants)     |
| More ERP Modules   | HR, CRM, Payroll as needed                             |
| Backups            | Automated per-tenant database backups                   |

---

*Report generated on 2026-05-01*
*Built with Laravel 11, stancl/tenancy v3, spatie/laravel-permission v6*
