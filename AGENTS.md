# AGENTS.md — VisionFlow (Value-to-Mission OS)

> **This file is the single source of truth for AI agents working on this codebase.**
> Every pull request, feature, and refactor MUST conform to the architecture, conventions, and module specifications described below.

---

## 1. Project Overview

**VisionFlow** is an advanced, enterprise-grade Laravel application that enables organizations to co-create, trace, and embed their **Values → Principles → Strategic Goals → Vision → Mission** pipeline. It is a _living operating system_ for organizational purpose — not static documentation.

### Key Characteristics

| Attribute | Detail |
|---|---|
| **Framework** | Laravel 11+ (PHP 8.3+) |
| **Frontend** | Inertia.js + Vue 3 (Composition API, `<script setup>`) |
| **CSS** | Tailwind CSS 3+ with custom design tokens |
| **Database** | PostgreSQL 16+ |
| **Cache / Queue** | Redis 7+ |
| **Search** | Laravel Scout + Meilisearch |
| **Real-time** | Laravel Reverb (WebSockets) |
| **Languages** | English (`en`) & German (`de`) — runtime switchable |
| **Auth** | Laravel Fortify + Sanctum (SPA tokens) |
| **Testing** | Pest PHP (backend) + Vitest + Cypress (frontend) |
| **CI/CD** | GitHub Actions |
| **Containerization** | Docker Compose (dev), Kubernetes-ready (prod) |

---

## 2. Architecture

### 2.1 Directory Structure

```
visionflow/
├── app/
│   ├── Actions/              # Single-responsibility action classes
│   ├── Contracts/            # Interfaces / contracts
│   ├── DTOs/                 # Data Transfer Objects (spatie/laravel-data)
│   ├── Enums/                # PHP 8.1 backed enums
│   ├── Events/               # Domain events
│   ├── Exceptions/           # Custom exception classes
│   ├── Http/
│   │   ├── Controllers/      # Thin controllers (max ~15 lines per method)
│   │   ├── Middleware/        # Custom middleware
│   │   ├── Requests/         # Form requests with authorize() + rules()
│   │   └── Resources/        # API Resources / JSON transformers
│   ├── Jobs/                 # Queued jobs
│   ├── Listeners/            # Event listeners
│   ├── Models/               # Eloquent models (one per file)
│   ├── Notifications/        # Mail / broadcast / database notifications
│   ├── Observers/            # Model observers for lifecycle hooks
│   ├── Policies/             # Authorization policies
│   ├── Providers/            # Service providers
│   ├── Rules/                # Custom validation rules
│   ├── Services/             # Business logic services (domain layer)
│   └── Traits/               # Reusable model/service traits
├── config/                   # Laravel config files
├── database/
│   ├── factories/            # Model factories (Pest-compatible)
│   ├── migrations/           # Timestamped migrations
│   └── seeders/              # Database seeders
├── lang/
│   ├── en/                   # English translations
│   └── de/                   # German translations
├── resources/
│   ├── js/
│   │   ├── Components/       # Vue 3 shared components
│   │   ├── Composables/      # Vue composables (useXxx pattern)
│   │   ├── Layouts/          # Page layouts (App, Auth, Guest)
│   │   ├── Pages/            # Inertia pages (mirrors routes)
│   │   │   ├── Values/
│   │   │   ├── Principles/
│   │   │   ├── Goals/
│   │   │   ├── Vision/
│   │   │   ├── Missions/
│   │   │   ├── Dashboard/
│   │   │   ├── Settings/
│   │   │   └── Admin/
│   │   ├── Stores/           # Pinia stores
│   │   ├── Types/            # TypeScript interfaces & types
│   │   └── Utils/            # Frontend utility functions
│   ├── css/                  # Tailwind entrypoint + design tokens
│   └── views/                # Blade views (emails, PDF exports only)
├── routes/
│   ├── web.php               # Inertia routes
│   ├── api.php               # REST API v1 routes
│   ├── channels.php          # Broadcast channel auth
│   └── console.php           # Artisan console commands
├── tests/
│   ├── Feature/              # Feature / integration tests (Pest)
│   ├── Unit/                 # Unit tests (Pest)
│   └── Cypress/              # E2E browser tests
├── docker-compose.yml
├── Dockerfile
├── vite.config.ts
├── tailwind.config.ts
├── tsconfig.json
└── phpstan.neon              # Static analysis config (level 8)
```

### 2.2 Architectural Principles

1. **Action Pattern** — Every write operation goes through an `Action` class (`app/Actions/`). Controllers call Actions; Actions orchestrate Services, fire Events, and return DTOs.
2. **Thin Controllers** — Controllers do three things: validate (via FormRequest), delegate (to Action), respond (Inertia::render or Resource). Max ~15 lines per method.
3. **Service Layer** — Complex business logic lives in `app/Services/`. Services are injected via constructor DI, never `app()` helper.
4. **DTOs over arrays** — Use `spatie/laravel-data` for all data transfer. Never pass raw arrays between layers.
5. **Events for side effects** — Notifications, audit logs, cache invalidation, and broadcasts are triggered via Events/Listeners, not inline.
6. **Repository pattern is NOT used** — Eloquent is the data layer. Use query scopes, model methods, and Actions.
7. **Strict typing** — All PHP files use `declare(strict_types=1)`. All method signatures have full type declarations. No `mixed` except when truly unavoidable.
8. **No magic strings** — Use Enums for statuses, types, categories. Use constants for config keys.

### 2.3 Request Lifecycle

```
Request → Middleware → FormRequest (validate + authorize)
       → Controller → Action → Service(s) → Model(s)
       → Action fires Event(s)
       → Controller returns Inertia::render() or JsonResource
```

---

## 3. Core Modules

### Module 1: Values Workshop

**Purpose:** Collaborative creation, clustering, and prioritization of organizational core values.

#### Database Tables

| Table | Key Columns | Notes |
|---|---|---|
| `values` | `id`, `organization_id`, `title`, `description`, `status` (enum: draft/proposed/approved/archived), `sort_order`, `version`, `approved_at`, `approved_by` | Soft deletes, versioned |
| `value_cards` | `id`, `value_id`, `author_id`, `content`, `is_anonymous`, `created_at` | Individual input cards |
| `value_clusters` | `id`, `organization_id`, `name`, `description` | Grouping of related cards |
| `value_card_cluster` | `value_card_id`, `value_cluster_id` | Pivot table |
| `value_votes` | `id`, `value_id`, `user_id`, `score`, `round` | Prioritization votes |
| `value_versions` | `id`, `value_id`, `data` (JSON), `changed_by`, `created_at` | Full version history |

#### Key Features

- **Anonymous & Named Input:** `value_cards.is_anonymous` flag. When anonymous, `author_id` is stored but never exposed via API Resources.
- **Clustering:** Drag-and-drop card clustering in the UI. Backend: many-to-many via `value_card_cluster`.
- **Voting/Prioritization:** Multi-round weighted voting. Each round has configurable `max_votes_per_user`. Results calculated via `ValueVotingService`.
- **Version History:** Every update to a `Value` triggers `ValueUpdated` event → `CreateValueVersion` listener snapshots the full model to `value_versions`.
- **Approval Workflow:** Values move through `draft → proposed → approved → archived`. Only users with `value.approve` permission can approve.

#### API Endpoints (partial)

```
GET    /api/v1/values                    # List values (filterable by status)
POST   /api/v1/values                    # Create value
PATCH  /api/v1/values/{value}            # Update value
POST   /api/v1/values/{value}/approve    # Approve value
POST   /api/v1/values/{value}/archive    # Archive value
GET    /api/v1/values/{value}/versions   # Version history
POST   /api/v1/value-cards               # Submit card (anonymous or named)
POST   /api/v1/value-clusters            # Create cluster
POST   /api/v1/value-votes               # Cast vote
GET    /api/v1/value-votes/results       # Vote results per round
```

---

### Module 2: Principles Builder

**Purpose:** Transform approved values into actionable principles using structured sentence templates.

#### Database Tables

| Table | Key Columns | Notes |
|---|---|---|
| `principles` | `id`, `organization_id`, `value_id` (FK), `statement`, `template_key`, `status` (draft/proposed/approved), `alignment_score` | Links to value |
| `principle_templates` | `id`, `key`, `pattern_en`, `pattern_de` | e.g. "We believe in {value}, therefore we {action}" |
| `principle_consensus` | `id`, `principle_id`, `user_id`, `vote` (enum: agree/disagree/abstain), `comment` | Consensus tracking |

#### Key Features

- **Value → Principle Linking:** Every principle MUST reference an approved value via `value_id`.
- **Sentence Templates:** Predefined bilingual templates. Users fill placeholders. Custom templates allowed.
- **Consensus / Alignment Score:** `alignment_score` = `(agree_count / total_votes) * 100`. Recalculated via `PrincipleConsensusService` on every vote. Real-time broadcast via WebSocket channel `principles.{id}`.
- **Traceability:** Principle always traces back to its originating Value.

---

### Module 3: Strategic Goals Canvas

**Purpose:** Define long-term directional goals (non-KPI) categorized by domain, with full traceability to values.

#### Database Tables

| Table | Key Columns | Notes |
|---|---|---|
| `strategic_goals` | `id`, `organization_id`, `title`, `description`, `category` (enum: market/impact/organization), `time_horizon`, `status` | Non-KPI goals |
| `goal_value` | `goal_id`, `value_id` | Many-to-many: which values support this goal |
| `goal_principle` | `goal_id`, `principle_id` | Many-to-many: which principles support this goal |

#### Key Features

- **Categorization:** Three fixed categories — Market, Impact, Organization. Stored as PHP Enum `GoalCategory`.
- **Traceability:** Goals link to values AND principles. The UI shows the full ancestry chain.
- **Strategic Goals Map:** Visual canvas (Vue component) showing goals grouped by category with connecting lines to linked values/principles.
- **Non-KPI by design:** Goals describe direction, not measurable targets. No percentage/number fields.

---

### Module 4: Vision Co-Creation Workspace

**Purpose:** Collaboratively draft, iterate, and approve a single organizational vision statement.

#### Database Tables

| Table | Key Columns | Notes |
|---|---|---|
| `visions` | `id`, `organization_id`, `content`, `status` (drafting/reviewing/approved), `version`, `approved_at`, `approved_by`, `is_current` | Only one `is_current = true` per org |
| `vision_drafts` | `id`, `vision_id`, `author_id`, `content`, `created_at` | Iterative drafts |
| `vision_comments` | `id`, `vision_draft_id`, `user_id`, `body`, `parent_id`, `emotional_resonance` (1-5 scale) | Threaded comments with emotional voting |
| `vision_approvals` | `id`, `vision_id`, `user_id`, `decision` (approve/reject), `comment` | Final approval workflow |

#### Key Features

- **Single Source of Truth:** Exactly one vision with `is_current = true` per organization. Enforced at database level (partial unique index) and in `VisionService`.
- **Iterative Drafting:** Multiple drafts per vision. Each draft is a separate record with full authorship.
- **Emotional Resonance Voting:** Comments have a 1–5 resonance score. Aggregated resonance displayed per draft.
- **Approval Workflow:** Configurable quorum (e.g., 80% of leadership must approve). Managed by `VisionApprovalAction`.
- **Real-time Editing:** Draft changes broadcast via `vision.{id}.drafts` channel using Laravel Reverb.

---

### Module 5: Mission Generator

**Purpose:** Derive multiple active missions from the approved vision. Assign ownership and review cadence.

#### Database Tables

| Table | Key Columns | Notes |
|---|---|---|
| `missions` | `id`, `organization_id`, `vision_id` (FK), `title`, `description`, `template_key`, `owner_id` (FK users), `status` (active/paused/completed/archived), `review_cadence` (enum: monthly/quarterly/biannually/annually), `next_review_at` | Mission portfolio |
| `mission_templates` | `id`, `key`, `pattern_en`, `pattern_de` | Bilingual mission templates |
| `mission_reviews` | `id`, `mission_id`, `reviewer_id`, `status` (on_track/at_risk/off_track), `notes`, `reviewed_at` | Periodic review records |

#### Key Features

- **Vision → Mission Derivation:** Every mission MUST reference the current approved vision.
- **Mission Templates:** Predefined patterns to help teams formulate missions. Bilingual.
- **Ownership Assignment:** Each mission has a single `owner_id`. Owner receives review reminders.
- **Review Cadence:** Automated `MissionReviewReminder` notification job. Runs daily, checks `next_review_at`.
- **Active Mission Portfolio:** Dashboard view showing all active missions with status indicators.

---

### Module 6: Visibility & Embedding Layer

**Purpose:** Make the organizational purpose always visible, link it to everyday work, and track decision alignment.

#### Database Tables

| Table | Key Columns | Notes |
|---|---|---|
| `projects` | `id`, `organization_id`, `mission_id` (FK), `name`, `description`, `status` | Every project references a mission |
| `decision_logs` | `id`, `organization_id`, `user_id`, `title`, `description`, `decision`, `supporting_value_id`, `supporting_mission_id`, `created_at` | Decision audit trail |
| `dashboard_widgets` | `id`, `organization_id`, `widget_type`, `config` (JSON), `position`, `is_active` | Configurable dashboard |
| `embeddable_configs` | `id`, `organization_id`, `type` (homepage_widget/intranet), `settings` (JSON), `api_key`, `is_active` | External embed settings |

#### Key Features

- **Company Homepage Widget:** Embeddable `<iframe>` or JS snippet showing current Vision & Mission. Authenticated via `embeddable_configs.api_key`.
- **Project ↔ Mission Linking:** Every project MUST reference a mission. Enforced via `project.mission_id NOT NULL`.
- **Decision Log:** Structured log answering "Which value/mission does this decision support?" Used for alignment analytics.
- **Always-on Vision Dashboard:** Configurable widget-based dashboard. Default widgets: Vision Statement, Active Missions, Alignment Score, Recent Decisions.
- **Embeddable API:** Public API endpoint (`/api/v1/embed/{api_key}`) returning sanitized vision/mission data for external consumption.

---

## 4. Cross-Cutting Concerns

### 4.1 Multi-tenancy

- **Strategy:** Single database, `organization_id` scoped.
- **Enforcement:** Global scope `OrganizationScope` auto-applied to all tenant-aware models via `BelongsToOrganization` trait.
- **Middleware:** `EnsureOrganization` middleware sets `auth()->user()->organization_id` in context.
- **CRITICAL:** Every query on tenant data MUST be scoped. Never use `Model::all()` without scope.

### 4.2 Internationalization (i18n)

- **Supported locales:** `en` (English), `de` (German).
- **Backend translations:** `lang/en/*.php` and `lang/de/*.php`. Use `__('messages.key')` helper.
- **Frontend translations:** `lang/en.json` and `lang/de.json` loaded via Inertia shared data. Use `$t('key')` in Vue templates.
- **Locale switching:** `SetLocale` middleware reads `Accept-Language` header or `user.preferred_locale` DB column. Users can switch at runtime via Settings page.
- **Database content:** User-generated content (values, principles, vision, missions) is stored in the user's current locale. For orgs needing bilingual content, use `spatie/laravel-translatable` on relevant models.
- **Validation messages:** All validation errors returned in the user's active locale.
- **Date/Number formatting:** Use `Carbon` with locale for dates. Use `NumberFormatter` for numbers/currency.
- **RULE:** Every user-facing string MUST have both `en` and `de` translations. PRs missing translations will be rejected.

### 4.3 Authorization (RBAC)

#### Roles

| Role | Scope | Description |
|---|---|---|
| `super_admin` | Global | Platform administrator |
| `org_admin` | Organization | Full org management |
| `leader` | Organization | Can approve values, visions; manage goals |
| `member` | Organization | Can participate, vote, comment |
| `viewer` | Organization | Read-only access |

#### Permissions (examples)

```
values.create, values.update, values.approve, values.archive
principles.create, principles.vote
goals.create, goals.update
vision.draft, vision.comment, vision.approve
missions.create, missions.assign, missions.review
decisions.log, decisions.view
settings.manage, users.manage
```

- **Implementation:** `spatie/laravel-permission` package. Roles and permissions seeded via `RoleAndPermissionSeeder`.
- **Policy classes:** One per model (`ValuePolicy`, `VisionPolicy`, etc.). Always use `$this->authorize()` in controllers or `Gate::allows()` in Actions.

### 4.4 Audit Trail

- **Package:** `spatie/laravel-activitylog`
- **Scope:** All CUD operations on core models are logged automatically via `LogsActivity` trait.
- **Fields logged:** `causer_id`, `causer_type`, `subject_id`, `subject_type`, `description`, `properties` (old + new values), `organization_id`.
- **Retention:** 365 days. Pruned via scheduled `activitylog:clean` command.

### 4.5 Real-time & Broadcasting

- **Driver:** Laravel Reverb (WebSocket server).
- **Channels:**
  - `organization.{id}` — org-wide notifications
  - `values.{orgId}` — value workshop live updates
  - `vision.{id}.drafts` — collaborative drafting
  - `principles.{id}.consensus` — live alignment score
  - `dashboard.{orgId}` — widget data refresh
- **Frontend:** Use `Echo` via `laravel-echo` + `pusher-js` (Reverb-compatible).

### 4.6 API Versioning

- All API routes under `/api/v1/`.
- Use `JsonResource` classes for response transformation.
- Pagination: cursor-based (`cursorPaginate()`) for list endpoints.
- Rate limiting: 60 req/min for authenticated, 10 req/min for public embed API.

### 4.7 Search

- **Engine:** Meilisearch via Laravel Scout.
- **Indexed models:** `Value`, `Principle`, `StrategicGoal`, `Vision`, `Mission`, `DecisionLog`.
- **Multi-tenant search:** Search queries automatically scoped by `organization_id` filter.

---

## 5. Coding Standards

### 5.1 PHP / Laravel

```php
<?php

declare(strict_types=1);

namespace App\Actions\Values;

use App\DTOs\ValueData;
use App\Events\ValueCreated;
use App\Models\Value;

final class CreateValueAction
{
    public function execute(ValueData $data): Value
    {
        $value = Value::create($data->toArray());

        event(new ValueCreated($value));

        return $value;
    }
}
```

**Rules:**
- `declare(strict_types=1)` in every PHP file.
- `final` on Action classes (they should not be extended).
- One class per file. Class name matches filename.
- Use constructor promotion for DI.
- Return types on all methods. No `void` return type omission.
- Use named arguments for readability when calling methods with 3+ parameters.
- Use `match` over `switch`. Use null coalescing `??` over ternary when applicable.
- No `dd()`, `dump()`, `var_dump()`, `print_r()`, `ray()` in committed code.
- No `env()` calls outside `config/` files. Always use `config()` helper.
- Eloquent relationships: always define return types (`HasMany`, `BelongsTo`, etc.).
- Use `readonly` properties on DTOs and value objects.
- Static analysis: PHPStan level 8. Zero errors required.
- Code style: Laravel Pint with `laravel` preset. Run before every commit.

### 5.2 Vue 3 / TypeScript

```vue
<script setup lang="ts">
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import type { Value } from '@/Types/Value'

const props = defineProps<{
  values: Value[]
}>()

const { t } = useI18n()

const searchQuery = ref('')

const filteredValues = computed(() =>
  props.values.filter(v =>
    v.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)
</script>

<template>
  <div class="space-y-4">
    <input
      v-model="searchQuery"
      :placeholder="t('values.searchPlaceholder')"
      class="input-field"
    />
    <ValueCard
      v-for="value in filteredValues"
      :key="value.id"
      :value="value"
    />
  </div>
</template>
```

**Rules:**
- `<script setup lang="ts">` only. No Options API.
- All props and emits typed via TypeScript generics.
- Composables prefixed with `use` (e.g., `useValueVoting`).
- Components: PascalCase filenames, single-word names avoided (use `ValueCard` not `Card`).
- No `any` type. Define interfaces in `Types/` directory.
- Use Pinia stores for shared state, composables for component-local logic.
- Tailwind classes in templates. No inline styles. Extract to `@apply` only for highly reused patterns.
- Use `<Suspense>` for async components.
- Emit events with descriptive names: `@value-approved`, not `@click`.

### 5.3 Database / Migrations

```php
Schema::create('values', function (Blueprint $table) {
    $table->ulid('id')->primary();           // ULIDs, not auto-increment
    $table->foreignUlid('organization_id')
          ->constrained()
          ->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('status')->default('draft');
    $table->unsignedInteger('sort_order')->default(0);
    $table->unsignedInteger('version')->default(1);
    $table->timestamp('approved_at')->nullable();
    $table->foreignUlid('approved_by')->nullable()
          ->constrained('users')
          ->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();

    $table->index(['organization_id', 'status']);
});
```

**Rules:**
- **ULIDs** for all primary keys (`HasUlids` trait on models). No auto-incrementing IDs.
- Foreign keys with explicit `constrained()` and cascade behavior.
- Indexes on all foreign keys and commonly filtered columns.
- `softDeletes()` on all core domain models.
- Column order: `id`, foreign keys, data columns, status/flags, timestamps.
- Migration naming: `create_values_table`, `add_alignment_score_to_principles_table`.
- Seeders: `DatabaseSeeder` calls module-specific seeders. Factory-driven.
- **Never** use `->change()` in production migrations. Create new migration instead.

### 5.4 Testing

```php
// tests/Feature/Values/CreateValueTest.php

use App\Models\User;
use App\Models\Organization;

it('creates a value for the organization', function () {
    $user = User::factory()->leader()->create();

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/values', [
            'title' => 'Integrity',
            'description' => 'We act with honesty and transparency.',
        ]);

    $response->assertCreated()
        ->assertJsonPath('data.title', 'Integrity');

    $this->assertDatabaseHas('values', [
        'organization_id' => $user->organization_id,
        'title' => 'Integrity',
        'status' => 'draft',
    ]);
});

it('requires leader role to create values', function () {
    $user = User::factory()->member()->create();

    $this
        ->actingAs($user)
        ->postJson('/api/v1/values', [
            'title' => 'Integrity',
        ])
        ->assertForbidden();
});
```

**Rules:**
- Use **Pest PHP** syntax (`it()`, `test()`, `describe()`).
- Feature tests for every endpoint: happy path + auth + validation.
- Unit tests for Services, Actions, and complex business logic.
- Use `RefreshDatabase` trait for feature tests.
- Factories for all models with meaningful states (e.g., `->draft()`, `->approved()`).
- Minimum coverage target: 80%.
- Test translations: verify both `en` and `de` responses where applicable.
- E2E (Cypress): Cover critical flows — value creation workshop, vision approval, mission review.

---

## 6. Environment & Configuration

### 6.1 Required Environment Variables

```env
APP_NAME=VisionFlow
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_AVAILABLE_LOCALES=en,de

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=visionflow
DB_USERNAME=visionflow
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=visionflow
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=

MAIL_MAILER=smtp
```

### 6.2 Local Development Setup

```bash
# 1. Clone & install
git clone https://github.com/{org}/visionflow.git
cd visionflow
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database
docker compose up -d postgres redis meilisearch
php artisan migrate --seed

# 4. Run
php artisan serve        # Backend: http://localhost:8000
npm run dev              # Vite dev server
php artisan reverb:start # WebSocket server
php artisan queue:work   # Queue worker

# 5. Quality checks
./vendor/bin/pint        # Code style
./vendor/bin/phpstan     # Static analysis
php artisan test         # Pest tests
npx vitest               # Frontend tests
```

---

## 7. Git & PR Conventions

### 7.1 Branch Naming

```
feature/values-workshop-voting
feature/i18n-german-translations
fix/vision-approval-quorum
refactor/action-pattern-principles
chore/update-dependencies
```

### 7.2 Commit Messages

Format: `type(scope): description`

```
feat(values): add multi-round voting system
fix(vision): enforce single current vision per org
refactor(principles): extract consensus calculation to service
test(missions): add review cadence reminder tests
chore(deps): update laravel to 11.x
i18n(de): add German translations for goals module
```

Types: `feat`, `fix`, `refactor`, `test`, `chore`, `docs`, `i18n`, `perf`, `ci`

### 7.3 PR Requirements

Every PR MUST:
1. Pass PHPStan level 8 with zero errors
2. Pass Laravel Pint with zero changes needed
3. Pass all Pest tests
4. Include both `en` and `de` translations for any new user-facing strings
5. Include Feature tests for new endpoints (happy path + auth + validation)
6. Not decrease code coverage below 80%
7. Have a descriptive title following commit message format
8. Reference related issue(s)

---

## 8. Traceability Chain

The core differentiator of VisionFlow is the full traceability chain:

```
┌─────────┐     ┌────────────┐     ┌─────────────────┐     ┌─────────┐     ┌──────────┐
│  Values  │────▶│ Principles │────▶│ Strategic Goals  │────▶│ Vision  │────▶│ Missions │
└─────────┘     └────────────┘     └─────────────────┘     └─────────┘     └──────────┘
     │                │                     │                    │               │
     └────────────────┴─────────────────────┴────────────────────┴───────────────┘
                                        │
                                   ┌────┴─────┐
                                   │ Projects │
                                   │ Decisions│
                                   └──────────┘
```

**Enforcement Rules:**
- A `Principle` cannot exist without a linked `Value`.
- A `StrategicGoal` must link to at least one `Value` or `Principle`.
- A `Mission` cannot exist without a linked `Vision`.
- A `Project` cannot exist without a linked `Mission`.
- A `DecisionLog` entry should reference at least one `Value` or `Mission`.
- Deleting a `Value` that has linked Principles is blocked (soft-delete cascade check).

---

## 9. Future Expansion Modules (Planned)

> These modules are NOT yet implemented but are on the roadmap. Architecture decisions should accommodate them.

### 9.1 Decision Alignment Engine
- "Does this decision align with our values?"
- AI-assisted scoring comparing decision text against value/principle embeddings.
- Database: `decision_alignments` table with `score`, `matching_values[]`, `reasoning`.

### 9.2 Employee Onboarding Journey
- Interactive walkthrough of Vision, Mission, and Values for new hires.
- Progress tracking per employee. Quiz/acknowledgment checkpoints.
- Database: `onboarding_journeys`, `onboarding_steps`, `onboarding_progress`.

### 9.3 Culture Health Metrics
- Periodic pulse surveys linked to values.
- Aggregated culture health score per value dimension.
- Trend tracking and alerting.
- Database: `culture_surveys`, `survey_questions`, `survey_responses`, `culture_scores`.

---

## 10. Design System & UI Guidelines

### 10.1 Design Tokens

```css
/* Defined in resources/css/tokens.css */
:root {
  --color-primary: #2563eb;        /* Blue 600 */
  --color-primary-dark: #1d4ed8;   /* Blue 700 */
  --color-secondary: #7c3aed;     /* Violet 600 */
  --color-success: #059669;        /* Emerald 600 */
  --color-warning: #d97706;        /* Amber 600 */
  --color-danger: #dc2626;         /* Red 600 */
  --color-surface: #ffffff;
  --color-surface-dark: #1e293b;   /* Slate 800 (dark mode) */
  --color-text: #0f172a;           /* Slate 900 */
  --color-text-muted: #64748b;     /* Slate 500 */
  --radius-sm: 0.375rem;
  --radius-md: 0.5rem;
  --radius-lg: 0.75rem;
  --shadow-sm: 0 1px 2px rgb(0 0 0 / 0.05);
  --shadow-md: 0 4px 6px rgb(0 0 0 / 0.07);
}
```

### 10.2 Component Patterns

- **Cards:** Used for values, principles, goals. Consistent padding, shadow, hover state.
- **Kanban Boards:** For status-based views (draft → proposed → approved).
- **Canvas/Graph:** D3.js or Vue Flow for the Strategic Goals Map and Traceability visualization.
- **Modals:** Headless UI `Dialog` component for forms and confirmations.
- **Toast Notifications:** Headless UI `Notification` for success/error feedback.
- **Dark Mode:** Full dark mode support via Tailwind `dark:` variants. Toggle in user settings.

### 10.3 Responsive Design

- Mobile-first approach.
- Breakpoints: `sm` (640px), `md` (768px), `lg` (1024px), `xl` (1280px).
- Collaborative features (canvas, drag-and-drop) gracefully degrade on mobile to list views.

---

## 11. Deployment & Infrastructure

### 11.1 Docker Compose (Development)

```yaml
services:
  app:
    build: .
    ports: ["8000:8000"]
    volumes: [".:/var/www/html"]
    depends_on: [postgres, redis, meilisearch]

  postgres:
    image: postgres:16
    environment:
      POSTGRES_DB: visionflow
      POSTGRES_USER: visionflow
      POSTGRES_PASSWORD: password
    ports: ["5432:5432"]
    volumes: [pgdata:/var/lib/postgresql/data]

  redis:
    image: redis:7-alpine
    ports: ["6379:6379"]

  meilisearch:
    image: getmeili/meilisearch:latest
    ports: ["7700:7700"]

  reverb:
    build: .
    command: php artisan reverb:start
    ports: ["8080:8080"]

volumes:
  pgdata:
```

### 11.2 Production Considerations

- Use managed PostgreSQL (AWS RDS / DigitalOcean).
- Redis cluster for cache + queue + session + broadcasting.
- Meilisearch Cloud or self-hosted with persistence.
- Laravel Reverb behind a load balancer with sticky sessions.
- Horizontal scaling: stateless app servers behind ALB.
- Asset delivery via CDN (CloudFront / Cloudflare).
- Database backups: automated daily with 30-day retention.
- SSL/TLS everywhere. HSTS enabled.

---

## 12. Summary of Key Packages

| Package | Purpose |
|---|---|
| `laravel/framework` | Core framework |
| `inertiajs/inertia-laravel` | SPA bridge |
| `laravel/fortify` | Authentication |
| `laravel/sanctum` | API token auth |
| `laravel/reverb` | WebSocket server |
| `laravel/scout` | Full-text search |
| `spatie/laravel-permission` | RBAC |
| `spatie/laravel-activitylog` | Audit trail |
| `spatie/laravel-data` | DTOs |
| `spatie/laravel-translatable` | Model translations |
| `phpstan/phpstan` | Static analysis |
| `laravel/pint` | Code style |
| `pestphp/pest` | Testing framework |

---

_Last updated: 2026-04-28_
_Version: 1.0.0_
