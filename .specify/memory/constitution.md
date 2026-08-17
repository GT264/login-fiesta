<!--
  Sync Impact Report
  ==================
  Version change: 1.0.0 (initial ratification)
  Modified principles: n/a
  Added sections:
    - Seven Core Principles for the Login Fiesta package
    - Technology Stack matrix
    - Development Workflow
    - Governance
  Removed sections: n/a
  Templates requiring updates:
    - .specify/templates/plan-template.md: ✅ no changes needed (initial version)
    - .specify/templates/spec-template.md: ✅ no changes needed (initial version)
    - .specify/templates/tasks-template.md: ✅ no changes needed (initial version)
    - .specify/templates/checklist-template.md: ✅ no changes needed (initial version)
  Follow-up TODOs: none
-->

# Login Fiesta Constitution

## Core Principles

### I. Vue Composition API Only

All Vue components MUST use the Composition API exclusively with `<script setup lang="ts">` syntax.
The Options API MUST NOT be used anywhere in the package.

- Every `.vue` single-file component MUST declare its script block as `<script setup lang="ts">`.
- Reactivity MUST be managed via `ref()`, `reactive()`, `computed()`, and `watch()` — never via
  `data()`, `methods`, or `computed` options objects.
- TypeScript types MUST be explicitly declared for all props, emits, and composable return values.
- Composables (`use*`) MUST be the mechanism for sharing stateful logic across components.
- Use Ziggy `route()` to manage routes in Vue components, to use the Laravel route naming conventions.

**Rationale**: Uniformity across the package simplifies maintenance, improves type safety via
TypeScript integration, and aligns with Vue 3 ecosystem standards. The Options API is legacy
and creates friction with TypeScript inference.

### II. PSR-12 PHP Standards

All PHP source code MUST comply with PSR-12 (Extended Coding Style).

- Files MUST use `<?php` opening tags and MUST NOT use closing `?>` tags.
- Indentation MUST use 4 spaces (no tabs).
- Namespace and `use` declarations MUST follow PSR-12 ordering and grouping rules.
- Class names MUST use PascalCase; method, property, and variable names MUST use camelCase.
- Control structure keywords (if, for, foreach, while, etc.) MUST be followed by a single space.
- Method and function calls MUST NOT have a space between the name and opening parenthesis.
- Line length SHOULD NOT exceed 120 characters where reasonable.

**Rationale**: PSR-12 is the de facto standard for modern PHP, ensures code consistency
across contributors, and is automatically enforceable via tools like PHP_CodeSniffer or
Laravel Pint.

### III. Laravel and Fortify Best Practices

All server-side code MUST follow established Laravel conventions, with Laravel Fortify as the
sole authentication backend.

- Dependency injection MUST be used wherever possible (constructor injection preferred over
  facade usage or `app()` resolution).
- Service Providers MUST be the entry point for package registration (routes, views,
  translations, config).
- Configuration files MUST be publishable via `vendor:publish` with appropriate tags.
- Laravel Fortify MUST be the only mechanism for authentication and password management
  (login, registration, password reset, two-factor authentication, email verification).
  Hand-rolled authentication controllers or custom credential-validation logic MUST NOT be
  introduced.
- Fortify view responses (`loginView`, `registerView`, `resetPasswordView`,
  `confirmPasswordView`, `twoFactorChallengeView`, `verifyEmailView`, etc.) MUST be Inertia
  pages rendered via `Inertia::render()`, never Blade-only views. The Fortify service provider
  MUST be configured using `Fortify::loginView(fn () => Inertia::render(...))` and equivalent
  callbacks for each supported view.
- The package MUST declare `laravel/fortify` as a Composer dependency and register Fortify's
  service provider, so consumers do not need to wire up Fortify themselves.
- Fortify features (registration, reset passwords, two-factor authentication, email
  verification, profile updates) MUST be enabled or disabled declaratively through the
  package configuration file, never through hardcoded calls in package source.
- Authentication state sharing MUST follow Inertia conventions; the authenticated user and
  flash messages MUST be shared via `Inertia::share()` where applicable.

**Rationale**: Laravel Fortify provides a secure, battle-tested authentication backend that
integrates natively with Laravel's session and CSRF protections. Routing all authentication
through Fortify — and surfacing its views as Inertia pages — keeps the package secure by
default and consistent with the Inertia.js SPA architecture, eliminating duplicate or
hand-rolled auth logic.

### IV. Compiled Assets in Version Control

Compiled frontend assets in the `dist/` directory MUST be committed to the git repository
and treated as part of the package distribution.

- The `dist/` directory MUST NOT be listed in `.gitignore`.
- Vite configuration MUST NOT use `emptyOutDir: true` — this option wipes the entire
  `dist/` directory before each build, destroying committed compiled components such as
  Vue SFC render outputs.
- External dependencies MUST be declared as `external` in the Vite `rollupOptions`
  to prevent bundling duplicates at the consumer's end. The following packages MUST
  be externalized: `vue`, `/^vue\//`, and `@inertiajs/vue3`. This prevents the
  package and the consuming project from bundling two separate Inertia instances,
  which causes runtime breakage (e.g., `page.props` being `undefined` in components
  that call `usePage()`).
- Library entry point MUST be `src/resources/js/index.ts`, producing ES module output
  in `dist/index.js`.
- Preserve modules MUST be enabled (`preserveModules: true`) to maintain the directory
  structure expected by consumer import paths.

**Rationale**: Publishing compiled assets alongside source avoids requiring consumers to
run a build step for the package itself. Committing `dist/` to git eliminates the need
for a separate NPM publish workflow while keeping the package installable directly from
the repository.

### V. Package Architecture

The package MUST be self-contained under the `GT264\LoginFiesta` vendor namespace and
designed as a reusable Laravel library.

- PHP namespace root: `GT264\LoginFiesta\` (PSR-4 autoloaded from `src/`).
- Composer package name: `gt264/login-fiesta`.
- Console commands MUST be registered in the `LoginFiestaServiceProvider` and follow
  Laravel's artisan command naming conventions (e.g., `login-fiesta:install`).
- Configuration MUST be published as `config/login-fiesta.php` (merged via
  `mergeConfigFrom`).
- Translation files (`src/lang/`) MUST support at minimum English (`en`) and Italian
  (`it`), loaded through Laravel's translation conventions.
- The package MUST provide a `LoginFiestaServiceProvider` that registers Fortify and
  publishes the package's views, translations, and configuration.
- Vue components in `src/resources/js/Components/Auth/` are the canonical login UI
  and MUST be registered globally via the `LoginPlugin` Vue plugin.

**Rationale**: A clear namespace and predictable structure allow consumers to install
and extend the package without reverse-engineering internals. Centralizing Fortify and
auth-view registration in the service provider ensures the package works out of the box
after a single install.

### VI. Registry

Every shadcn-vue component in the package MUST be declared in the `registry.json`
and have a corresponding `registry/r/*.json` item file.

- The root `registry.json` file MUST list all registry item URLs.
- Each Vue component in `src/resources/js/Components/` MUST have a corresponding
  `registry/r/<item-name>.json` file defining its dependencies, registry
  dependencies, files, and metadata following the shadcn-vue v3 registry schema.
- When adding a new shadcn-vue UI component to `src/resources/js/Components/ui/`,
  its registry item MUST be created or updated in `registry/r/`.
- When adding a new composite component to `src/resources/js/Components/Auth/`,
  its registry item MUST be created in `registry/r/` with correct
  `registryDependencies` referencing both shadcn-vue base components (by name)
  and other login-fiesta components (by full raw GitHub URL).
- npm dependencies (`dependencies` field) MUST list all external packages
  required by the component at runtime.
- The `registry.json` array MUST list items in dependency order (leaf
  dependencies first, composite items last).
- Registry files MUST be valid JSON conforming to the
  `https://shadcn-vue.com/schema/registry-item.json` schema.

**Rationale**: The shadcn-vue registry mechanism is the canonical distribution
channel for login-fiesta's Vue components. It allows consumers to install
components via `npx shadcn-vue add <url>` with automatic dependency resolution.
Keeping registry items in sync with source code ensures the package is
consumable both as a Composer dependency (PHP layer) and via shadcn-vue CLI
(Vue layer), without requiring the consumer to manually wire up dependencies.

### VII. Presentation-Agnostic Components

Package components MUST separate data/behavior logic from visual presentation.
Styling, layout, and positioning decisions MUST remain the responsibility of the
consuming project.

- **Inertia-driven auth submission**: Login, registration, password reset, and all
  other authentication form submissions MUST be performed through Inertia's
  `router.post()` (or the `<form>` submitted via Inertia), preserving the stateful
  session and CSRF protections that Fortify relies on. Components MUST NOT bypass
  Inertia with direct `fetch`/`axios` calls to authentication endpoints, which
  would break CSRF protection and session establishment.
- **Toast trigger/position separation**: Package components MAY trigger toast
  notifications (e.g., on Inertia `onSuccess`/`onError` events) but MUST NOT render
  or position the toast container. The consuming project MUST own placement of the
  `<Toaster />` (or equivalent) component, typically within the host layout.
- **No imposed styling**: Package components MUST NOT hardcode colors, spacing, or
  positioning that override the consumer's design tokens. Components MUST rely on
  Tailwind CSS classes and shadcn-vue design tokens that the consuming project
  configures, not on package-defined visual defaults.
- Components MUST expose slots and/or props for structural customization (e.g.,
  custom logo, custom form fields, custom footer links) rather than requiring
  consumers to fork the component to change its appearance.

**Rationale**: login-fiesta is consumed by projects with their own design systems.
Coupling auth logic to a specific visual implementation would force consumers to
either accept the package's opinions on layout or fork components to override them.
Routing submissions through Inertia additionally keeps the authentication flow
compatible with Fortify's session/CSRF model, ensuring the package's security
guarantees hold regardless of how a consumer styles the page.

## Technology Stack

The package operates within the following technology matrix, which all contributions
and modifications MUST respect:

| Layer        | Technology                  | Version     |
|------------- |----------------------------|------------ |
| Backend      | PHP                         | >= 8.3      |
| Framework    | Laravel                     | ^13.0       |
| Auth         | laravel/fortify             | ^1.0        |
| Middleware   | inertiajs/inertia-laravel   | ^3.0        |
| Frontend     | Vue                         | ^3.4        |
| UI Library   | shadcn-vue                  | ^3.0        |
| Icons        | lucide-vue-next             | ^0.460      |
| CSS          | Tailwind CSS                | ^4.0        |
| Type Check   | TypeScript                  | ^5.0        |
| Bundler      | Vite                        | ^6.0        |

- shadcn-vue is the designated UI component system; UI components MUST use
  shadcn-vue patterns and Tailwind CSS tokens.
- lucide-vue-next MUST be the icon library; custom icon sets MUST NOT be
  introduced without explicit justification.
- Inertia.js v3 is the bridge between Laravel controllers and Vue pages; all
  page components served by the auth views MUST be Inertia pages.
- Laravel Fortify is the designated authentication backend; custom authentication
  controllers MUST NOT be introduced.

## Development Workflow

Contributions and modifications MUST follow this workflow:

0. **Design** (for every new feature or significant modification):
   - `/speckit-specify` generates `specs/[###-feature]/spec.md` with user stories and
     functional requirements.
   - `/speckit-plan` generates `specs/[###-feature]/plan.md` with the implementation plan.
   - `/speckit-tasks` generates `specs/[###-feature]/tasks.md` with the task breakdown.
   - These three artifacts MUST be created and kept up to date in the
     `specs/[###-feature]/` directory for the duration of the feature. They serve as
     design documentation and decision traceability.

1. **Branch**: Create a feature branch from `main` following the naming convention
   `[###-feature-name]` where `###` is the sequential feature number.
2. **Code Style**:
   - PHP: Run `./vendor/bin/pint` (or equivalent PSR-12 linter) before committing.
   - TypeScript/Vue: Run `vue-tsc --noEmit` to verify types compile.
3. **Build**: Run `npm run build` to produce compiled assets in `dist/`. Verify that
   the build output does not delete pre-existing committed files (Principle IV).
4. **Test**: Run the project's test suite (unit/feature tests via PHPUnit). New
   features SHOULD include tests covering the Fortify configuration, the published
   views, and the authentication flows enabled by the package.
5. **Commit**: Commit compiled `dist/` assets alongside source changes in the same
   commit. Use conventional commit messages (e.g., `feat:`, `fix:`, `docs:`).
6. **Review**: All changes MUST be reviewed for compliance with this constitution
   before merge. At minimum, verify:
   - No Options API introduced in `.vue` files.
   - No `emptyOutDir: true` in Vite configuration.
   - PSR-12 compliance in PHP files.
   - Dependency injection used over facades.
   - Fortify used as the sole authentication backend (no hand-rolled auth).
   - Auth submissions routed through Inertia, not direct `fetch`/`axios`.
   - Registry files in sync with Vue components (Principle VI).

## Governance

This constitution is the authoritative reference for the Login Fiesta project. It
supersedes all other development guidelines, coding standards documents, and informal
conventions.

### Amendment Procedure

1. Proposed amendments MUST be documented with rationale and impact analysis.
2. Amendments that remove or redefine existing principles constitute a MAJOR version
   bump (e.g., 1.x → 2.0).
3. Amendments that add new principles or materially expand guidance constitute a
   MINOR version bump (e.g., 1.0 → 1.1).
4. Clarifications, typo fixes, and non-semantic refinements constitute a PATCH bump
   (e.g., 1.0.0 → 1.0.1).
5. After amendment, all dependent templates (plan, spec, tasks, checklist) and
   runtime guidance files (README, docs/) MUST be reviewed for consistency and
   updated if necessary.

### Versioning Policy

The constitution follows Semantic Versioning (MAJOR.MINOR.PATCH):

- **MAJOR**: Backward-incompatible governance or principle removals/redefinitions.
- **MINOR**: New principle or section added, or materially expanded guidance.
- **PATCH**: Clarifications, wording improvements, typo fixes.

### Compliance Review

- Every new feature or significant modification MUST produce the design artifacts
  (`spec.md`, `plan.md`, `tasks.md`) in the `specs/[###-feature]/` directory before
  implementation begins.
- Every feature implementation plan (generated via `/speckit-plan`) MUST pass the
  Constitution Check gate before proceeding to implementation.
- Pull requests MUST include a brief compliance statement confirming adherence to
  all seven Core Principles.
- Complexity or deviations from principles MUST be explicitly justified in the
  implementation plan's Complexity Tracking table.

**Version**: 1.0.0 | **Ratified**: 2026-08-17 | **Last Amended**: 2026-08-17