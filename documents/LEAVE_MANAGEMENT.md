# Leave Management Module - Implementation Summary

## ✅ Completed Components

### 1. Database
- **Migration**: `2025_11_28_033234_create_leaves_table.php`
- **Table**: `leaves`
- **Fields**:
  - `id`, `user_id`, `leave_type`, `start_date`, `end_date`, `total_days`
  - `reason`, `attachment` (optional file upload)
  - `status` (pending/approved/rejected)
  - `reviewed_by`, `reviewed_at`, `admin_notes`
  - `timestamps`, `soft_deletes`

### 2. Model
- **File**: `app/Models/Leave.php`
- **Features**:
  - 6 leave types: Sick, Annual, Emergency, Unpaid, Maternity, Paternity
  - Relationships: user, reviewer
  - Scopes: pending, approved, rejected, byUser, upcoming, active
  - Helper methods: isPending(), isApproved(), isRejected(), isActive()
  - Badge colors for status and leave types

### 3. Controllers

#### Admin Controller (`app/Http/Controllers/Admin/LeaveController.php`)
- Full CRUD operations
- Approve/Reject leave requests
- Soft delete & restore
- Force delete with file cleanup
- File upload handling (images/PDFs up to 5MB)
- Filtering by status, type, user
- Search functionality

#### Doctor Controller (`app/Http/Controllers/Doctor/LeaveController.php`)
- View own leaves
- Create leave requests
- Edit pending leaves only
- Cancel pending leaves
- View leave statistics
- File upload for proof

#### Staff Controller (`app/Http/Controllers/Staff/LeaveController.php`)
- Same features as Doctor Controller
- View own leaves
- Create leave requests
- Edit pending leaves only
- Cancel pending leaves
- View leave statistics
- File upload for proof

### 4. Routes

#### Doctor Routes
```php
Route::resource('doctor.leaves', LeaveController::class);
```
- GET `/doctor/leaves` - List all leaves
- GET `/doctor/leaves/create` - Create form
- POST `/doctor/leaves` - Store leave
- GET `/doctor/leaves/{id}` - View leave
- GET `/doctor/leaves/{id}/edit` - Edit form
- PUT `/doctor/leaves/{id}` - Update leave
- DELETE `/doctor/leaves/{id}` - Delete leave

#### Staff Routes
```php
Route::resource('staff.leaves', LeaveController::class);
```
- Same as doctor routes

#### Admin Routes
```php
Route::resource('admin.leaves', LeaveController::class);
Route::post('/admin/leaves/{id}/restore', 'restore');
Route::delete('/admin/leaves/{id}/force-delete', 'forceDelete');
Route::post('/admin/leaves/{leave}/approve', 'approve');
Route::post('/admin/leaves/{leave}/reject', 'reject');
```

## 📋 Next Steps - Views to Create

### Admin Views (in `resources/views/admin/leaves/`)
1. `index.blade.php` - List all leaves with filters
2. `create.blade.php` - Create leave form
3. `edit.blade.php` - Edit leave form
4. `show.blade.php` - View leave details

### Doctor Views (in `resources/views/doctor/leaves/`)
1. `index.blade.php` - List own leaves with stats widget
2. `create.blade.php` - Apply for leave form
3. `edit.blade.php` - Edit pending leave
4. `show.blade.php` - View leave details

### Staff Views (in `resources/views/staff/leaves/`)
1. `index.blade.php` - List own leaves with stats widget
2. `create.blade.php` - Apply for leave form
3. `edit.blade.php` - Edit pending leave
4. `show.blade.php` - View leave details

## 🎨 Widget Features for Doctor/Staff Dashboard

### Leave Statistics Widget
- Total leaves taken
- Pending requests count
- Approved leaves count
- Rejected leaves count
- Upcoming leaves
- Quick apply button

### Leave Calendar Widget
- Visual calendar showing approved leaves
- Color-coded by leave type
- Current active leave indicator

## 🔐 Security Features

1. **Authorization**: Users can only view/edit their own leaves
2. **Edit Restrictions**: Only pending leaves can be edited/deleted
3. **File Validation**: Max 5MB, only JPG, PNG, PDF allowed
4. **Soft Deletes**: Leaves can be restored by admin
5. **Audit Trail**: Tracks who approved/rejected and when

## 📁 File Upload

- **Storage Path**: `storage/app/public/leave-attachments/`
- **Allowed Types**: JPG, JPEG, PNG, PDF
- **Max Size**: 5MB (5120 KB)
- **Optional**: Users can submit without attachment

## 🎯 Leave Types

1. **Sick Leave** - For illness
2. **Annual Leave** - Vacation/planned leave
3. **Emergency Leave** - Urgent situations
4. **Unpaid Leave** - Leave without pay
5. **Maternity Leave** - For mothers
6. **Paternity Leave** - For fathers

## 📊 Status Workflow

1. **Pending** → Initial state when submitted
2. **Approved** → Admin approves the leave
3. **Rejected** → Admin rejects with notes

## 🔄 Next Implementation Phase

1. Create all view files
2. Add leave widget to doctor/staff dashboards
3. Add email notifications (optional)
4. Add leave balance tracking (optional)
5. Add leave calendar view (optional)
