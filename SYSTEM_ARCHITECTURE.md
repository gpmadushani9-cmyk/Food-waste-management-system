# SYSTEM ARCHITECTURE & FLOW

## 🏗️ SYSTEM OVERVIEW

```
┌─────────────────────────────────────────────────────────────┐
│                    FOOD WASTE MANAGEMENT SYSTEM              │
│                                                              │
│  Goal: Reduce waste → Increase profit → Discount leftovers  │
└─────────────────────────────────────────────────────────────┘
```

---

## 👥 USER ROLES

```
┌──────────────┐                    ┌──────────────┐
│    ADMIN     │                    │   CUSTOMER   │
│  (Manager)   │                    │   (Public)   │
└──────────────┘                    └──────────────┘
      │                                     │
      │ Full Access                         │ View Only
      │ - Manage Food                       │ - See Discounts
      │ - Track Production                  │ - View Prices
      │ - Create Discounts                  │ - Check Time
      │ - View Analytics                    │ - No Login
      │                                     │
      └─────────────┬───────────────────────┘
                    │
            ┌───────▼────────┐
            │   DATABASE     │
            │ food_waste_opt │
            └────────────────┘
```

---

## 📊 DATA FLOW

### 1. PRODUCTION TRACKING FLOW

```
┌─────────────┐
│  Morning    │
│  Admin      │
│  Records    │
│  Production │
└──────┬──────┘
       │
       ▼
┌─────────────────────────────┐
│  Daily Production Table     │
│  ─────────────────────────  │
│  Food Item: Chicken Roll    │
│  Date: 2024-01-15          │
│  Produced: 100             │
│  Sold: 0 (initial)         │
│  Leftover: 0               │
└──────┬──────────────────────┘
       │
       ▼
┌─────────────┐
│  During Day │
│  Admin      │
│  Updates    │
│  Sales      │
└──────┬──────┘
       │
       ▼
┌─────────────────────────────┐
│  Updated Production         │
│  ─────────────────────────  │
│  Produced: 100             │
│  Sold: 70                  │
│  Leftover: 30 (auto calc)  │
│  Waste %: 30%              │
└─────────────────────────────┘
```

### 2. DISCOUNT SUGGESTION FLOW

```
┌──────────────────┐
│  AI Engine       │
│  Analyzes:       │
│  - Leftover qty  │
│  - Time of day   │
│  - Waste %       │
└────────┬─────────┘
         │
         ▼
┌─────────────────────────────┐
│  Suggestion Logic           │
│  ───────────────────────    │
│  IF leftover > 50%          │
│  AND time > 6:00 PM         │
│  THEN suggest 40% discount  │
│                             │
│  IF leftover > 30%          │
│  AND time > 3:00 PM         │
│  THEN suggest 25% discount  │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  Admin Reviews Suggestion   │
│  ───────────────────────    │
│  Chicken Roll - 30% OFF     │
│  Leftover: 30 items         │
│  Time: 6:00 PM - 9:00 PM    │
│                             │
│  [Accept] [Reject]          │
└────────┬────────────────────┘
         │
         ▼ (Accept)
┌─────────────────────────────┐
│  Discount Activated         │
│  Status: ACTIVE             │
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  Customer View Updated      │
│  Shows discount immediately │
└─────────────────────────────┘
```

### 3. CUSTOMER VIEW FLOW

```
┌──────────────┐
│  Customer    │
│  Opens       │
│  Public Page │
└──────┬───────┘
       │
       ▼
┌─────────────────────────────┐
│  System Queries Database    │
│  ───────────────────────    │
│  SELECT active discounts    │
│  WHERE date = TODAY         │
│  AND status = 'active'      │
│  AND current_time BETWEEN   │
│      start_time AND end_time│
└────────┬────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│  Display Discounts          │
│  ───────────────────────    │
│  🍽️ Chicken Roll            │
│  30% OFF                    │
│  $5.00 → $3.50              │
│  Until 9:00 PM              │
│  30 items left              │
└────────┬────────────────────┘
         │
         ▼ (Every 30 seconds)
┌─────────────────────────────┐
│  Auto Refresh               │
│  Check for new discounts    │
└─────────────────────────────┘
```

---

## 🗄️ DATABASE STRUCTURE

```
┌─────────────────────────────────────────────────────────┐
│                     DATABASE TABLES                      │
└─────────────────────────────────────────────────────────┘

┌──────────────┐         ┌──────────────────┐
│    users     │         │   food_items     │
├──────────────┤         ├──────────────────┤
│ id           │         │ id               │
│ name         │         │ name             │
│ email        │         │ category         │
│ password     │         │ cost_price       │
│ role         │         │ selling_price    │
└──────────────┘         └────────┬─────────┘
                                  │
                                  │ (1 to many)
                                  │
                         ┌────────▼──────────────┐
                         │  daily_production     │
                         ├───────────────────────┤
                         │ id                    │
                         │ food_item_id          │
                         │ production_date       │
                         │ quantity_produced     │
                         │ quantity_sold         │
                         │ quantity_leftover     │
                         └────────┬──────────────┘
                                  │
                                  │ (1 to many)
                                  │
                         ┌────────▼──────────────┐
                         │     discounts         │
                         ├───────────────────────┤
                         │ id                    │
                         │ food_item_id          │
                         │ discount_date         │
                         │ discount_percentage   │
                         │ start_time            │
                         │ end_time              │
                         │ status                │
                         │ leftover_quantity     │
                         └───────────────────────┘

┌──────────────────┐
│  notifications   │
├──────────────────┤
│ id               │
│ title            │
│ message          │
│ type             │
│ active           │
└──────────────────┘
```

---

## 🔄 COMPLETE SYSTEM WORKFLOW

```
DAY CYCLE:
─────────

08:00 AM │ Admin logs in
         │ Records production
         │ ↓
         │ [food_items] → [daily_production]
         │
         │
12:00 PM │ Admin updates sales
         │ System calculates leftovers
         │ ↓
         │ quantity_leftover = produced - sold
         │
         │
03:00 PM │ AI checks for leftovers
         │ Generates suggestions
         │ ↓
         │ IF leftover > 30% THEN suggest discount
         │
         │
06:00 PM │ Admin accepts suggestion
         │ Discount activated
         │ ↓
         │ [discounts] status = 'active'
         │ [notifications] created
         │
         │
06:01 PM │ Customer opens public page
         │ Sees active discounts
         │ ↓
         │ SELECT * FROM discounts WHERE active
         │
         │
09:00 PM │ Discount expires
         │ System auto-updates status
         │ ↓
         │ status = 'expired'
         │
         │
10:00 PM │ Admin reviews daily stats
         │ Plans tomorrow's production
```

---

## 🎯 KEY CALCULATIONS

### 1. Leftover Calculation
```
Leftover = Quantity Produced - Quantity Sold

Example:
Produced: 100
Sold: 70
Leftover: 100 - 70 = 30
```

### 2. Waste Percentage
```
Waste % = (Leftover / Produced) × 100

Example:
Leftover: 30
Produced: 100
Waste %: (30 / 100) × 100 = 30%
```

### 3. Discount Price
```
Discounted Price = Original Price × (1 - Discount% / 100)

Example:
Original: $5.00
Discount: 30%
Discounted: $5.00 × (1 - 30/100) = $5.00 × 0.70 = $3.50
```

### 4. Profit with Discount
```
Profit = Discounted Price - Cost Price

Example:
Discounted Price: $3.50
Cost Price: $2.50
Profit: $3.50 - $2.50 = $1.00
```

---

## 🔐 SECURITY FEATURES

```
┌─────────────────────────────────────┐
│  Authentication & Authorization     │
├─────────────────────────────────────┤
│  ✓ Password hashing (bcrypt)        │
│  ✓ Session management               │
│  ✓ CSRF protection                  │
│  ✓ Role-based access (admin/staff)  │
│  ✓ SQL injection prevention (PDO)   │
│  ✓ XSS protection (htmlspecialchars)│
└─────────────────────────────────────┘

Admin Routes:
  ✓ Require login
  ✓ Check user role
  ✓ Validate CSRF token

Customer Routes:
  ✓ No authentication needed
  ✓ Read-only access
  ✓ No sensitive data exposed
```

---

## 📱 RESPONSIVE DESIGN

```
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│   Desktop    │  │    Tablet    │  │    Mobile    │
│   (Admin)    │  │  (Display)   │  │  (Customer)  │
├──────────────┤  ├──────────────┤  ├──────────────┤
│ Full sidebar │  │ Collapsible  │  │ Bottom nav   │
│ All features │  │ Touch-ready  │  │ Swipe cards  │
│ Multi-column │  │ Large text   │  │ Single col   │
└──────────────┘  └──────────────┘  └──────────────┘
```

---

## 🚀 PERFORMANCE OPTIMIZATION

```
Database:
  ✓ Indexed columns (date, status)
  ✓ Foreign key constraints
  ✓ Optimized queries

Frontend:
  ✓ Minimal JavaScript
  ✓ CSS caching
  ✓ Auto-refresh (30s interval)

Backend:
  ✓ PDO prepared statements
  ✓ Session caching
  ✓ Efficient routing
```

---

## 📈 FUTURE ENHANCEMENTS

```
Phase 2 (Optional):
  □ SMS notifications (Twilio)
  □ Email alerts
  □ Multi-branch support
  □ Advanced analytics
  □ ML-based forecasting
  □ Mobile app
  □ QR code ordering
  □ Payment integration
```

---

**System Status: ✅ FULLY OPERATIONAL**

All client requirements implemented and tested!
