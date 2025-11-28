# Fix: PostgreSQL Enum Type Issue

## Error
```
SQLSTATE[25P02]: In failed sql transaction: 7 ERROR: current transaction is aborted, commands ignored until end of transaction block
```

## Root Cause
PostgreSQL doesn't support Laravel's `enum()` method the same way MySQL does. When migrations use `enum()`, PostgreSQL throws an error, causing the transaction to abort.

## Solution Applied
Replaced all `enum()` types with `string()` types in migrations:

### Fixed Migrations:

1. **2024_01_01_000003_create_services_table.php**
   - Changed: `$table->enum('type', ['psychology', 'homeopathy', 'general'])`
   - To: `$table->string('type')->default('general')`

2. **2024_01_01_000006_create_patients_table.php**
   - Changed: `$table->enum('gender', ['male', 'female', 'other'])`
   - To: `$table->string('gender')->nullable()`

3. **2024_01_01_000007_create_doctors_table.php**
   - Changed: `$table->enum('type', ['psychology', 'homeopathy', 'general'])`
   - To: `$table->string('type')->default('general')`

4. **2024_01_01_000008_create_appointments_table.php**
   - Changed: `$table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled', 'no_show'])`
   - To: `$table->string('status')->default('scheduled')`

## How to Fix Existing Database

### Step 1: Reset Migration State
```bash
# Option A: If you can drop all tables (development)
php artisan migrate:fresh

# Option B: If you need to preserve data, manually fix the transaction
# Connect to PostgreSQL and run:
# ROLLBACK;
# Then delete failed migration records from migrations table
```

### Step 2: Clear Migration Cache
```bash
php artisan migrate:reset
# OR if that doesn't work:
php artisan db:wipe
```

### Step 3: Re-run Migrations
```bash
php artisan migrate
php artisan db:seed
```

## Alternative: Manual Database Fix

If you need to fix the transaction manually:

```sql
-- Connect to PostgreSQL
-- Check current transaction state
SELECT * FROM pg_stat_activity WHERE state = 'active';

-- If needed, rollback the failed transaction
ROLLBACK;

-- Then delete any partial migration records
DELETE FROM migrations WHERE migration = '2024_01_01_000001_create_users_table';

-- Re-run migrations
```

## Notes
- String types work the same as enums for validation purposes
- Application-level validation should still enforce allowed values
- This ensures compatibility with both PostgreSQL and MySQL
- All functionality remains the same, just stored as strings instead of enum types

## Verification
After fixing, run:
```bash
php artisan migrate:status
php artisan migrate
```

All migrations should now run successfully on PostgreSQL.

