# Food Waste & Profit Optimization System - COMPLETE SETUP GUIDE

## ✅ System Features (Client Requirements Fulfilled)

### Admin Features:
- ✅ Food Items Management (Add/Edit/Delete products with cost & selling price)
- ✅ Daily Production Tracking (Record how many items produced)
- ✅ Ingredient Management (Track ingredients and usage)
- ✅ Leftover Detection (Automatic calculation: Produced - Sold = Leftover)
- ✅ Waste Analytics (Identify waste patterns)
- ✅ Auto Discount Suggestions (AI suggests discounts based on leftovers & time)
- ✅ Discount Management (Accept/Reject/Modify discounts)
- ✅ Customer Notifications (Display active discounts)

### Customer Features:
- ✅ Public View (No login required)
- ✅ View Discounted Items (See all active discounts)
- ✅ Discount Details (Percentage, time limits, original price)
- ✅ Auto-refresh (Updates every 30 seconds)

---

## 🚀 INSTALLATION STEPS

### Step 1: Copy Project
```
Copy the entire "Food" folder to:
C:\xampp\htdocs\Food
```

### Step 2: Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" to create database
3. Database name: `food_waste_opt`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

### Step 3: Import Database Schema
1. Select `food_waste_opt` database
2. Click "SQL" tab
3. Open file: `Food/database/schema.sql`
4. Copy all content and paste in SQL tab
5. Click "Go" to execute

### Step 4: Configure Database Connection
1. Navigate to: `Food/app/config/`
2. Copy `config.example.php` → `config.php`
3. Edit `config.php`:
```php
return [
  'app_name' => 'Food Waste Management',
  'db' => [
    'host' => 'localhost',
    'name' => 'food_waste_opt',
    'user' => 'root',
    'pass' => '',  // Your MySQL password (usually empty for XAMPP)
  ],
];
```

### Step 5: Start XAMPP
1. Open XAMPP Control Panel
2. Start Apache
3. Start MySQL

### Step 6: Access the System
- **Admin Panel**: `http://localhost/Food/public/`
- **Customer View**: `http://localhost/Food/public/customer.php`

---

## 👤 FIRST TIME SETUP

### Register Admin Account
1. Go to: `http://localhost/Food/public/`
2. Click "Register"
3. Fill in details:
   - Name: Your Name
   - Email: admin@restaurant.com
   - Password: (your password)
4. Click "Register"
5. **First user automatically becomes Admin!**

---

## 📋 COMPLETE WORKFLOW (How to Use)

### Day 1: Initial Setup

#### 1. Add Food Items
1. Go to: **Food Items** → **Add Food Item**
2. Example:
   - Name: `Chicken Roll`
   - Category: `Main Course`
   - Cost Price: `2.50` (cost to make)
   - Selling Price: `5.00` (customer pays)
3. Add more items:
   - Pizza - $8.00
   - Burger - $6.00
   - Fried Rice - $4.50

#### 2. Add Ingredients (Optional - for advanced tracking)
1. Go to: **Ingredients** → **Add Ingredient**
2. Example:
   - Flour - $0.50/kg
   - Chicken - $3.00/kg
   - Oil - $2.00/liter

---

### Daily Operations

#### Morning: Record Production
1. Go to: **Production** → Select today's date
2. Add production for each item:
   - Chicken Roll: **100 items** produced
   - Pizza: **50 items** produced
   - Burger: **80 items** produced
3. Click "Add Production"

#### During the Day: Update Sales
1. Go to: **Production** page
2. In the "Sold" column, enter quantity sold
3. Example: Chicken Roll sold **70 items**
4. System automatically calculates: **Leftover = 100 - 70 = 30**

#### Afternoon/Evening: Check Discount Suggestions
1. Go to: **Discounts** page
2. System shows **AI Suggestions**:
   - "Chicken Roll - 30% OFF (30 items leftover)"
3. Click **"Accept & Activate"** to create discount
4. Or click **"Reject"** to ignore

#### Manual Discount Creation
1. Go to: **Discounts** → **AI Suggestions**
2. Modify discount percentage if needed
3. Set time period (e.g., 6:00 PM - 9:00 PM)
4. Click "Accept & Activate"

---

## 👥 CUSTOMER VIEW

### How Customers See Discounts

#### Access Customer Page:
```
http://localhost/Food/public/customer.php
```

#### What Customers See:
- 🍽️ Food item name
- 💸 Discount percentage (e.g., 30% OFF)
- 💰 Original price vs Discounted price
- ⏰ Time limit (e.g., "Available until 9:00 PM")
- 📦 Items left (e.g., "30 items left")

#### Features:
- ✅ No login required
- ✅ Auto-refreshes every 30 seconds
- ✅ Shows only active discounts
- ✅ Beautiful gradient design

---

## 🎯 DISCOUNT LOGIC (How AI Works)

### Automatic Suggestions Based On:

#### 1. Time-Based Discounts:
- **After 6:00 PM**: 30-40% discount
- **After 3:00 PM**: 15-25% discount
- **Anytime**: 20% if leftover > 70%

#### 2. Leftover Percentage:
- **> 50% leftover**: Suggest 40% discount
- **> 30% leftover**: Suggest 25% discount
- **> 70% leftover**: Suggest 20% discount

#### Example:
```
Chicken Roll:
- Produced: 100
- Sold: 40
- Leftover: 60 (60% waste)
- Time: 6:30 PM
→ AI Suggests: 40% OFF
```

---

## 📊 DASHBOARD OVERVIEW

### Today's Stats:
- **Today's Production**: Total items produced
- **Today's Leftovers**: Items not sold
- **Active Discounts**: Current offers
- **Sales (7 days)**: Weekly revenue

### Quick Actions:
- 🍽️ Manage Food Items
- 📊 View Production
- 💸 Manage Discounts
- 👥 Open Customer View

---

## 🔧 TROUBLESHOOTING

### Problem: "Route not found"
**Solution**: Make sure you're accessing `http://localhost/Food/public/` (with `/public/`)

### Problem: Database connection error
**Solution**: 
1. Check XAMPP MySQL is running
2. Verify `config.php` settings
3. Ensure database `food_waste_opt` exists

### Problem: No discounts showing on customer page
**Solution**:
1. Go to Admin → Production
2. Add production and sales data
3. Go to Discounts → Accept suggestions
4. Make sure discount status is "Active"

### Problem: CSS not loading
**Solution**: Clear browser cache or use Ctrl+F5

---

## 📱 DISPLAY OPTIONS

### For Restaurant Display Board:
1. Open customer page on a tablet/TV browser
2. Full-screen mode (F11)
3. Auto-refreshes every 30 seconds
4. Shows beautiful gradient background

### For SMS Notifications (Future):
- Currently shows web notifications
- Can integrate Twilio/SMS gateway later

---

## 🎓 PROJECT FEATURES SUMMARY

### ✅ All Client Requirements Met:

1. ✅ **Admin Panel**: Full restaurant management
2. ✅ **Food Management**: Add/Edit/Delete items with pricing
3. ✅ **Production Tracking**: Daily production quantities
4. ✅ **Leftover Detection**: Automatic calculation
5. ✅ **Waste Analytics**: Percentage and patterns
6. ✅ **Auto Discount Suggestions**: AI-based recommendations
7. ✅ **Discount Management**: Accept/Reject/Modify
8. ✅ **Customer View**: Public page (no login)
9. ✅ **Real-time Updates**: Auto-refresh every 30 seconds
10. ✅ **Notifications**: Display board ready

---

## 🚀 QUICK START CHECKLIST

- [ ] XAMPP installed and running
- [ ] Database `food_waste_opt` created
- [ ] Schema imported from `schema.sql`
- [ ] `config.php` configured
- [ ] Admin account registered
- [ ] Food items added
- [ ] Production recorded
- [ ] Discounts activated
- [ ] Customer view tested

---

## 📞 SUPPORT

For issues or questions:
1. Check database connection in `config.php`
2. Verify XAMPP services are running
3. Check browser console for errors
4. Ensure all files are in correct folders

---

## 🎉 YOU'RE READY!

Your system is now complete with:
- ✅ Restaurant management
- ✅ Production tracking
- ✅ Leftover detection
- ✅ Auto discount suggestions
- ✅ Customer discount view
- ✅ Real-time updates

**Start by adding your food items and recording today's production!**
