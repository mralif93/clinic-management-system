# Fix: PostgreSQL Migration Error

## Error
```
SQLSTATE[25P02]: In failed sql transaction: 7 ERROR:  current transaction is aborted, commands ignored until end of transaction block (Connection: pgsql, SQL: alter table "users" add constraint "users_email_unique" unique ("email"))
```

## Root Cause
PostgreSQL doesn't support the `->after()` method which is MySQL-specific. When migrations use `->after()`, PostgreSQL throws an error, causing the transaction to abort. Subsequent commands in the same transaction are then ignored.

## Solution Applied
Removed all `->after()` method calls from migrations:

### Fixed Migrations:
1. **2024_01_01_000001_create_users_table.php**
   - Removed `->after('password')` from `failed_login_attempts`
   - Removed `->after('failed_login_attempts')` from `locked_until`

2. **2024_01_01_000006_create_patients_table.php**
   - Removed `->after('id')` from `user_id`
   - Removed `->after('user_id')` from `patient_id`

3. **2024_01_01_000007_create_doctors_table.php**
   - Removed `->after('id')` from `user_id`
   - Removed `->after('user_id')` from `doctor_id`

4. **2024_01_01_000010_create_staff_table.php**
   - Removed `->after('user_id')` from `staff_id`

## How to Fix Existing Database

If you already have a failed migration, you need to:

### Option 1: Fresh Migration (Recommended for Development)
```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh
php artisan db:seed
```

### Option 2: Rollback and Re-run (If you have data to preserve)
```bash
# Rollback all migrations
php artisan migrate:rollback --step=20

# Re-run migrations
php artisan migrate
```

### Option 3: Manual Fix (If migrations are partially applied)
```sql
-- Connect to PostgreSQL
-- Check which migrations have been applied
SELECT * FROM migrations;

-- If needed, manually fix the transaction
BEGIN;
-- Fix any issues
COMMIT;

-- Then re-run migrations
php artisan migrate
```

## Notes
- Column order doesn't affect functionality in PostgreSQL
- The `->after()` method is purely cosmetic for column positioning
- All constraints and relationships will work correctly without `->after()`
- This fix ensures compatibility with both PostgreSQL and MySQL

## Verification
After fixing, run:
```bash
php artisan migrate:status
php artisan migrate
```

All migrations should now run successfully on PostgreSQL.

