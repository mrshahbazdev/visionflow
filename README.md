# VisionFlow — Value-to-Mission Operating System

**VisionFlow** is an enterprise-grade web application that enables organizations to co-create, trace, and embed their organizational purpose — from **Values → Principles → Strategic Goals → Vision → Missions**. It is a _living operating system_ for organizational alignment, not static documentation.

---

## Features

### Core Pipeline
- **Values Workshop** — Collaborative creation, clustering, and prioritization of core values with anonymous input and multi-round voting
- **Principles Builder** — Transform approved values into actionable principles using structured sentence templates with consensus tracking
- **Strategic Goals Canvas** — Define long-term directional goals categorized by Market, Impact, and Organization with full traceability
- **Vision Co-Creation** — Collaboratively draft, iterate, and approve a unified vision statement with emotional resonance voting
- **Mission Generator** — Derive active missions from the approved vision with ownership assignment and automated review cadence

### Visibility & Embedding
- **Project Linking** — Every project references a mission, ensuring purpose alignment
- **Decision Log** — Structured audit trail answering "Which value/mission does this decision support?"
- **Dashboard Widgets** — Configurable always-on vision dashboard
- **Embeddable Widgets** — Public API & iframe/JS snippets for company homepage integration

### Team Collaboration
- **Role-Based Access** — 5-level hierarchy: Owner → Admin → Leader → Member → Viewer
- **Team Invitations** — Email-based invitations with token validation and 7-day expiry
- **Work Assignments** — Assign missions and projects to team members using RACI roles (Responsible, Accountable, Consulted, Informed)

### Internationalization
- Full **English** and **German** support (runtime switchable)
- All UI strings, validation messages, and templates available in both languages

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11+ (PHP 8.3+) |
| Frontend | Vue 3 (Composition API) + Inertia.js |
| Styling | Tailwind CSS 3+ |
| Database | PostgreSQL 16+ |
| Cache / Queue | Redis 7+ |
| Search | Laravel Scout + Meilisearch |
| Real-time | Laravel Reverb (WebSockets) |
| Auth | Laravel Fortify + Sanctum |
| Testing | Pest PHP, Vitest, Cypress |

---

## Requirements

- PHP 8.3+
- Composer 2+
- Node.js 20+ & npm
- PostgreSQL 16+
- Redis 7+ (optional, for cache/queues)

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/mrshahbazdev/visionflow.git
cd visionflow
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure your database connection:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=visionflow
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Database migration

```bash
php artisan migrate
```

### 5. Build frontend assets

```bash
npm run build
```

### 6. Start the application

For local development:

```bash
php artisan serve
npm run dev
```

For production, configure your web server to point to the `public/` directory.

---

## Shared Hosting Deployment

VisionFlow includes a root `.htaccess` file that redirects all traffic to the `public/` directory, making it compatible with shared hosting environments where you cannot change the document root.

### Steps:

1. Upload all project files to your shared hosting root (e.g., `public_html/`)
2. Configure `.env` with your hosting's database credentials
3. Run migrations via SSH or your hosting's terminal:
   ```bash
   php artisan migrate --force
   ```
4. Ensure the `storage/` and `bootstrap/cache/` directories are writable:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
5. Frontend assets are pre-built and committed to `public/build/` — no build step needed on the server

> **Note:** The root `.htaccess` handles redirecting requests to `public/`. Your hosting's `public/.htaccess` handles Laravel's routing. Both files are required.

---

## Project Architecture

VisionFlow follows the **Action Pattern** architecture:

```
Request → Middleware → FormRequest (validate + authorize)
       → Controller → Action → Service(s) → Model(s)
       → Action fires Event(s)
       → Controller returns Inertia::render()
```

- **Actions** (`app/Actions/`) — Single-responsibility write operations
- **Services** (`app/Services/`) — Complex business logic
- **DTOs** (`app/DTOs/`) — Data transfer using `spatie/laravel-data`
- **Enums** (`app/Enums/`) — PHP 8.1 backed enums for statuses and types
- **Thin Controllers** — Validate, delegate, respond (max ~15 lines per method)

### Multi-tenancy

Single database with `organization_id` scoping. All tenant data is automatically scoped via the `BelongsToOrganization` trait and `OrganizationScope`.

---

## Testing

```bash
# Backend tests (Pest PHP)
php artisan test

# Frontend type check
npx vue-tsc --noEmit

# Frontend build check
npm run build
```

---

## Directory Structure

```
app/
├── Actions/          # Single-responsibility action classes
├── Enums/            # PHP 8.1 backed enums
├── Http/Controllers/ # Thin controllers
├── Models/           # Eloquent models
├── Notifications/    # Mail/broadcast notifications
├── Policies/         # Authorization policies
└── Services/         # Business logic services

resources/js/
├── Components/       # Vue 3 shared components
├── Layouts/          # Page layouts
├── Pages/            # Inertia pages (mirrors routes)
└── i18n/             # EN + DE translation files
```

---

## License

VisionFlow is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
