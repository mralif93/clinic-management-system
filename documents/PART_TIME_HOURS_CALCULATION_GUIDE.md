# 📊 Part-Time Staff Hours Calculation Guide

**Date:** December 3, 2025  
**Purpose:** Explain how total hours are calculated for part-time staff payroll

---

## 🔍 Overview

Part-time staff salaries are calculated based on **total hours worked** multiplied by their **hourly rate**. The system automatically tracks attendance and calculates hours from clock-in/clock-out times.

---

## 📐 Total Hours Calculation Formula

### **Step 1: Individual Attendance Hours**

For each attendance record, the `total_hours` is calculated as:

```
total_hours = (clock_out_time - clock_in_time - break_duration) / 60
```

**Example:**
- Clock-in: 09:00 AM
- Clock-out: 05:00 PM (17:00)
- Break duration: 60 minutes (1 hour lunch)
- **Calculation:** (17:00 - 09:00 - 1:00) / 60 = 7 hours

### **Step 2: Sum All Approved Hours**

The system queries all attendance records for the pay period:

```php
$totalHours = Attendance::where('user_id', $userId)
    ->whereBetween('date', [$payPeriodStart, $payPeriodEnd])
    ->where('is_approved', true)  // Only approved attendance
    ->sum('total_hours');
```

**Important:** Only **approved** attendance records are counted!

### **Step 3: Calculate Salary**

```
Salary = Total Hours × Hourly Rate
```

**Example:**
- Total Hours: 160h
- Hourly Rate: RM 8.00
- **Salary:** 160 × 8.00 = **RM 1,280.00**

---

## 🗂️ Database Structure

### **Attendance Table Fields**

| Field | Type | Description |
|-------|------|-------------|
| `user_id` | Integer | Employee ID |
| `date` | Date | Attendance date |
| `clock_in_time` | DateTime | When employee clocked in |
| `clock_out_time` | DateTime | When employee clocked out |
| `break_duration` | Integer | Break time in minutes |
| `total_hours` | Decimal(8,2) | Calculated work hours |
| `is_approved` | Boolean | Approval status |
| `approved_by` | Integer | Admin who approved |
| `approved_at` | DateTime | Approval timestamp |

---

## 💻 Code Implementation

### **PayrollController - Calculate Basic Salary**

<augment_code_snippet path="app/Http/Controllers/Admin/PayrollController.php" mode="EXCERPT">
````php
case 'part_time':
    // Part-time: Calculate hourly (RM8/hour by default)
    $hourlyRate = $user->hourly_rate ?? \App\Models\Setting::get('payroll_part_time_hourly_rate', 8);

    // Get total hours worked in the pay period
    $totalHours = \App\Models\Attendance::where('user_id', $userId)
        ->whereBetween('date', [$payPeriodStart, $payPeriodEnd])
        ->where('is_approved', true)
        ->sum('total_hours');

    return $totalHours * $hourlyRate;
````
</augment_code_snippet>

### **Attendance Model - Calculate Total Hours**

<augment_code_snippet path="app/Models/Attendance.php" mode="EXCERPT">
````php
public function calculateTotalHours()
{
    if (!$this->clock_out_time) {
        return null;
    }

    $minutes = $this->clock_in_time->diffInMinutes($this->clock_out_time);
    $minutes -= $this->break_duration;

    return round($minutes / 60, 2);
}
````
</augment_code_snippet>

---

## 📋 Payslip Display

### **Hours Breakdown Section**

The payslip now includes a detailed **Hours Breakdown** section for part-time staff, showing:

1. **Date** - Each working day
2. **Hours Worked** - Hours for that day
3. **Rate** - Hourly rate (RM/hr)
4. **Amount** - Daily earnings

**Visual Design:**
- 🟠 Orange theme (matching part-time employment type)
- 📊 Table format with all attendance records
- 💰 Total row showing sum of hours and amount
- ℹ️ Info note explaining the calculation

### **Example Display:**

```
┌────────────┬───────────┬──────────┬───────────┐
│ Date       │ Hours     │ Rate     │ Amount    │
├────────────┼───────────┼──────────┼───────────┤
│ 03 Nov 2025│     8.00h │  RM 8.00 │  RM 64.00 │
│ 04 Nov 2025│     8.00h │  RM 8.00 │  RM 64.00 │
│ 05 Nov 2025│     8.00h │  RM 8.00 │  RM 64.00 │
│ ...        │       ... │      ... │       ... │
├────────────┼───────────┼──────────┼───────────┤
│ TOTAL (20) │   160.00h │  RM 8.00 │RM 1,280.00│
└────────────┴───────────┴──────────┴───────────┘
```

---

## ✅ Approval Workflow

### **Why Approval Matters**

Only **approved** attendance records are counted in payroll calculations. This ensures:

1. ✅ Accuracy - Admin verifies actual work hours
2. ✅ Control - Prevents unauthorized hours
3. ✅ Audit Trail - Tracks who approved and when

### **Approval Process**

1. Employee clocks in/out daily
2. System calculates `total_hours` automatically
3. Admin reviews attendance records
4. Admin approves attendance (`is_approved = true`)
5. Approved hours are included in payroll calculation

---

## 📊 Real Example: Jane Parttime

### **Employee Details**
- **Name:** Jane Parttime
- **Employment Type:** Part-Time
- **Hourly Rate:** RM 8.00
- **Pay Period:** November 1-30, 2025

### **Attendance Summary**
```
Total Working Days: 20 days
Hours per Day: 8.00h
Total Hours: 160.00h
All Status: ✅ Approved
```

### **Calculation**
```
160 hours × RM 8.00/hour = RM 1,280.00
```

### **Breakdown by Day**
```
03 Nov 2025: 8.00h × RM 8.00 = RM 64.00
04 Nov 2025: 8.00h × RM 8.00 = RM 64.00
05 Nov 2025: 8.00h × RM 8.00 = RM 64.00
... (17 more days)
────────────────────────────────────────
TOTAL: 160.00h × RM 8.00 = RM 1,280.00
```

---

## 🎯 Key Points

1. **Automatic Calculation** - Hours are calculated from clock-in/out times
2. **Break Deduction** - Break duration is automatically subtracted
3. **Approval Required** - Only approved attendance counts
4. **Transparent Display** - Full breakdown shown on payslip
5. **Accurate Tracking** - Down to 2 decimal places (e.g., 7.50h)

---

## 🔧 Configuration

### **Default Hourly Rate**

Set in Settings or per-user:
- **System Default:** RM 8.00/hour
- **User Override:** `users.hourly_rate` field
- **Setting Key:** `payroll_part_time_hourly_rate`

### **Break Duration**

Configured per attendance record:
- Stored in minutes
- Automatically deducted from total hours
- Default: 60 minutes (1 hour)

---

## 📱 Where to View

### **Admin View**
- **Payroll Detail:** `/admin/payrolls/{id}`
- **Payroll List:** `/admin/payrolls/{year}/{month}`
- **Create Payroll:** `/admin/payrolls/create`

### **Staff View**
- **My Payslips:** `/staff/payslips`
- **Payslip Detail:** `/staff/payslips/{id}`

---

## ✨ Features

✅ **Detailed Breakdown** - See every working day  
✅ **Orange Theme** - Consistent with part-time branding  
✅ **Automatic Calculation** - No manual entry needed  
✅ **Approval Control** - Admin verification required  
✅ **Transparent Display** - Full visibility for staff  
✅ **Print/Download** - Includes hours breakdown  

---

## 🎉 Summary

The part-time hours calculation system provides:
- **Accurate** tracking from clock-in/out
- **Transparent** breakdown on payslips
- **Controlled** approval workflow
- **Professional** presentation

**Formula:** `Total Approved Hours × Hourly Rate = Salary`


