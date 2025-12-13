# Module Refactoring Documentation

## Overview

This document outlines the comprehensive plan to convert Packages and Team from Settings-based JSON storage to full database modules (following the Services module pattern), and implement admin-controlled module visibility through the Pages management system.

**Project Goal**: Transform Packages and Team into proper database-driven modules with full CRUD capabilities, while enabling administrators to control which modules appear on the public-facing website.

## Architecture Changes

### Current State

- **Services**: ✅ Full module (Model, Controller, Admin CRUD, Public routes)
- **Packages**: ❌ Settings-based (JSON in `packages` setting, managed via SettingsController)
- **Team**: ❌ Settings-based (JSON in `team_members` setting, managed via SettingsController)
- **Pages**: ⚠️ Database table exists but doesn't control module visibility

### Target State

- **Services**: ✅ Unchanged (reference implementation)
- **Packages**: ✅ Full module (Model, Controller, Admin CRUD, Public routes)
- **Team**: ✅ Full module (Model, Controller, Admin CRUD, Public routes)
- **Pages**: ✅ Controls module visibility via `is_published` flag for system pages

## Implementation Phases

### Phase 1: Create Packages Module

**Objective**: Convert Packages from Settings-based storage to a full database module.

**Tasks**:
1. Create `packages` database table migration
2. Create `Package` model with relationships, scopes, and slug generation
3. Create `PackageController` (public) and `Admin\PackageController` (admin CRUD)
4. Create admin views (index, create, edit, show)
5. Create public views (index, show)
6. Add package routes to `web.php`

**Database Schema**:
```sql
packages
- id (bigint)
- name (string)
- slug (string, unique)
- description (text, nullable)
- original_price (decimal 10,2, nullable)
- price (decimal 10,2)
- sessions (string, nullable) // e.g., "2X SESSIONS"
- duration (string, nullable) // e.g., "1 HOUR PER SESSION"
- image (string, nullable) // file path or URL
- is_active (boolean, default true)
- timestamps
- soft_deletes
```

**Key Files**:
- `database/migrations/YYYY_MM_DD_create_packages_table.php`
- `app/Models/Package.php`
- `app/Http/Controllers/PackageController.php`
- `app/Http/Controllers/Admin/PackageController.php`
- `resources/views/packages/index.blade.php`
- `resources/views/packages/show.blade.php`
- `resources/views/admin/packages/*.blade.php`

### Phase 2: Create Team Module

**Objective**: Convert Team from Settings-based storage to a full database module.

**Tasks**:
1. Create `team_members` database table migration
2. Create `TeamMember` model with relationships, scopes, and ordering
3. Create `TeamController` (public) and `Admin\TeamController` (admin CRUD)
4. Create admin views (index, create, edit, show)
5. Create public view (index)
6. Add team routes to `web.php`

**Database Schema**:
```sql
team_members
- id (bigint)
- name (string)
- title (string, nullable)
- bio (text, nullable)
- photo (string, nullable) // file path or URL
- order (integer, default 0)
- is_active (boolean, default true)
- timestamps
- soft_deletes
```

**Key Files**:
- `database/migrations/YYYY_MM_DD_create_team_members_table.php`
- `app/Models/TeamMember.php`
- `app/Http/Controllers/TeamController.php`
- `app/Http/Controllers/Admin/TeamController.php`
- `resources/views/team/index.blade.php`
- `resources/views/admin/team/*.blade.php`

### Phase 3: Update Pages Management for Module Visibility

**Objective**: Enable admin control of module visibility through Pages table.

**Tasks**:
1. Update `Page` model to add scopes for module pages
2. Update `HomeController` to check Page visibility before showing modules
3. Update `Admin\PageController` to ensure system pages can toggle visibility
4. Update admin pages index view to show module visibility status

**Logic**:
- When `Page` with `type='packages'` and `is_published=true` → show packages route
- When `Page` with `type='team'` and `is_published=true` → show team route
- When `Page` with `type='services'` and `is_published=true` → show services route

**Key Files**:
- `app/Models/Page.php`
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/Admin/PageController.php`
- `resources/views/admin/pages/index.blade.php`

### Phase 4: Data Migration

**Objective**: Migrate existing Settings data to new database tables.

**Tasks**:
1. Create migration to auto-migrate packages data from Settings
2. Create migration to auto-migrate team_members data from Settings
3. Create migration to ensure services page exists in Pages table
4. Preserve all existing data (photos, descriptions, pricing, etc.)

**Migration Strategy**:
- Read `packages` setting (JSON) → Create Package records
- Read `team_members` setting (JSON) → Create TeamMember records
- Ensure Pages table has entries for `packages`, `team`, and `services` types
- Set `is_published=true` for existing modules

**Key Files**:
- `database/migrations/YYYY_MM_DD_migrate_packages_from_settings.php`
- `database/migrations/YYYY_MM_DD_migrate_team_from_settings.php`
- `database/migrations/YYYY_MM_DD_add_services_page_to_pages.php`

### Phase 5: Update Routes & Public Views

**Objective**: Update public routes and views to use new module structure.

**Tasks**:
1. Update `routes/web.php` to check Page visibility
2. Update public package and team views to use new controller data structure
3. Update navigation/menus to check Page visibility

**Route Changes**:
```php
Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
```

**Key Files**:
- `routes/web.php`
- `resources/views/packages.blade.php` → `resources/views/packages/index.blade.php`
- `resources/views/team.blade.php` → `resources/views/team/index.blade.php`

### Phase 6: Cleanup & Remove Settings Dependencies

**Objective**: Remove old Settings-based code and dependencies.

**Tasks**:
1. Remove `SettingsController` methods (`editPackages()`, `editTeam()`)
2. Remove packages/team editing sections from Settings views
3. Remove legacy page edit routes
4. Update `HomeController` to remove or redirect old methods

**Key Files**:
- `app/Http/Controllers/Admin/SettingsController.php`
- `resources/views/admin/settings/partials/pages.blade.php`
- `routes/web.php`
- `app/Http/Controllers/HomeController.php`

### Phase 7: Verification & Testing

**Objective**: Comprehensive testing of all routes, controllers, templates, and functionality.

**Testing Areas**:
1. **Routes Testing**: Verify all public and admin routes work correctly
2. **Controller Testing**: Test all CRUD operations, error handling, edge cases
3. **Template Testing**: Ensure all Blade templates render without errors
4. **Database Testing**: Verify migrations, relationships, scopes, data integrity
5. **Visibility Testing**: Confirm Page visibility controls work as expected
6. **Error Checking**: Check logs, test edge cases, verify no broken links

**Tools**:
- `php artisan route:list` - List all routes
- `php artisan migrate:status` - Check migration status
- Browser DevTools - Check for JavaScript errors
- Laravel Debugbar - Check queries and errors
- `php artisan tinker` - Test model operations
- Manual browser testing - Test all user flows

### Phase 8: Custom Error Pages Design & Implementation

**Objective**: Create professional, user-friendly error pages matching the application's design system.

**Error Pages**:
- **404.blade.php** - Page Not Found
- **500.blade.php** - Internal Server Error
- **403.blade.php** - Forbidden/Access Denied
- **419.blade.php** - CSRF Token Mismatch
- **503.blade.php** - Service Unavailable

**Design Standards**:
- Clear error message with prominent error code
- Visual hierarchy with large error code, clear heading, helpful description
- Actionable CTAs (Go Home, Go Back, Contact Support)
- Consistent branding (clinic logo, colors, typography)
- Responsive design (mobile, tablet, desktop)
- Accessibility compliance

**Key Files**:
- `resources/views/errors/404.blade.php`
- `resources/views/errors/500.blade.php`
- `resources/views/errors/403.blade.php`
- `resources/views/errors/419.blade.php`
- `resources/views/errors/503.blade.php`
- `app/Exceptions/Handler.php` (if modifications needed)

## Key Implementation Details

### Page Visibility Check Pattern

```php
// In HomeController or middleware
public function checkModuleVisibility($moduleType) {
    $page = Page::where('type', $moduleType)->first();
    if (!$page || !$page->is_published) {
        abort(404); // or redirect
    }
    return true;
}
```

### Migration Strategy

- Use `get_setting()` helper to read existing Settings
- Parse JSON arrays
- Create database records
- Preserve all existing data (photos as base64 → file storage conversion)

### Backward Compatibility

- Keep Settings data temporarily during migration
- Add feature flag or config to switch between old/new system
- Provide rollback migration if needed

## Rollout Strategy

1. **Phase 1** - Packages module (test thoroughly)
2. **Phase 2** - Team module (test thoroughly)
3. **Phase 3** - Page visibility integration
4. **Phase 4** - Data migration (run in maintenance mode)
5. **Phase 5** - Route updates
6. **Phase 6** - Cleanup (after confirming everything works)
7. **Phase 7** - Verification & Testing (comprehensive check before deployment)
8. **Phase 8** - Custom Error Pages (enhance user experience for error scenarios)

## Testing Checklist

### General Testing
- [ ] Packages CRUD works in admin
- [ ] Team CRUD works in admin
- [ ] Public packages page shows only when Page is published
- [ ] Public team page shows only when Page is published
- [ ] Data migration preserves all existing data
- [ ] Old Settings routes redirect to new module routes
- [ ] Navigation menus respect Page visibility
- [ ] Soft deletes work correctly
- [ ] Image uploads work for both modules

### Error Pages Testing
- [ ] 404 page displays correctly for non-existent routes
- [ ] 500 page displays correctly for server errors
- [ ] 403 page displays correctly for unauthorized access
- [ ] 419 page displays correctly for CSRF token errors
- [ ] 503 page displays correctly for maintenance mode
- [ ] All error pages are responsive
- [ ] All error pages match branding
- [ ] Navigation buttons work correctly

## Notes

- Services module already exists and works - use it as reference
- Page model already has `type` field supporting 'packages' and 'team'
- Need to add 'services' type support to Pages table
- Consider adding `order` field to TeamMember for display ordering
- Image handling: convert base64 from Settings to file storage during migration
- Error pages should match `resources/views/layouts/public.blade.php` styling

## Reference Files

**Services Module (Reference)**:
- `app/Models/Service.php`
- `app/Http/Controllers/ServiceController.php`
- `app/Http/Controllers/Admin/ServiceController.php`
- `database/migrations/2024_01_01_000003_create_services_table.php`
- `resources/views/services/index.blade.php`
- `resources/views/admin/services/*.blade.php`

**Current Settings-Based Implementation**:
- `app/Http/Controllers/HomeController.php` (packages, team methods)
- `resources/views/packages.blade.php`
- `resources/views/team.blade.php`
- `resources/views/admin/settings/partials/pages.blade.php`

## Timeline Estimate

- **Phase 1**: 2-3 hours (Packages module)
- **Phase 2**: 2-3 hours (Team module)
- **Phase 3**: 1-2 hours (Page visibility)
- **Phase 4**: 1-2 hours (Data migration)
- **Phase 5**: 1 hour (Route updates)
- **Phase 6**: 1 hour (Cleanup)
- **Phase 7**: 2-3 hours (Testing)
- **Phase 8**: 2-3 hours (Error pages)

**Total Estimated Time**: 12-18 hours

---

*Last Updated: {{ date('Y-m-d') }}*
*Plan Version: 1.0*
