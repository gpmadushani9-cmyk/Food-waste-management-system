# USER MANUAL
## Food Waste & Profit Optimization System

**Version:** 1.0.0  
**Date:** December 2024  
**Document Type:** User Manual & System Guide

---

## TABLE OF CONTENTS

1. [Introduction](#1-introduction)
2. [System Overview](#2-system-overview)
3. [Getting Started](#3-getting-started)
4. [Admin Panel Guide](#4-admin-panel-guide)
5. [Customer Interface Guide](#5-customer-interface-guide)
6. [Daily Operations](#6-daily-operations)
7. [Troubleshooting](#7-troubleshooting)
8. [FAQ](#8-faq)
9. [Technical Support](#9-technical-support)

---

## 1. INTRODUCTION

### 1.1 Purpose
This user manual provides comprehensive instructions for using the Food Waste & Profit Optimization System. The system helps restaurants reduce food waste, increase profit, and engage customers through intelligent discount management.

### 1.2 Target Audience
- Restaurant Managers (Admin Users)
- Restaurant Staff (Staff Users)
- Customers (Public Users)

### 1.3 System Requirements
- **Server:** XAMPP/WAMP/MAMP (Apache + MySQL)
- **PHP Version:** 8.0 or higher
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **Browser:** Chrome, Firefox, Edge, Safari (latest versions)
- **Internet Connection:** Required for email notifications

---

## 2. SYSTEM OVERVIEW

### 2.1 Key Features

#### For Restaurant Managers:
- ✅ Food Items Management
- ✅ Daily Production Tracking
- ✅ Sales Recording
- ✅ Automatic Leftover Calculation
- ✅ AI-Powered Discount Suggestions
- ✅ Discount Management
- ✅ Waste Analytics
- ✅ Inventory Management
- ✅ Reports & Dashboard

#### For Customers:
- ✅ View Menu Items
- ✅ Browse Active Discounts
- ✅ Email Subscription for Offers
- ✅ Real-time Price Updates
- ✅ Mobile-Friendly Interface

### 2.2 System Workflow

```
Morning → Record Production
   ↓
Afternoon → Update Sales
   ↓
System → Calculate Leftovers
   ↓
AI → Suggest Discounts
   ↓
Admin → Accept/Reject Discounts
   ↓
Customer → View & Purchase Discounted Items
   ↓
Result → Reduced Waste + Increased Revenue
```

---

## 3. GETTING STARTED

### 3.1 System Access

#### Admin Panel Access:
- **URL:** `http://localhost/Food/public/`
- **Login Required:** Yes
- **Default Admin:** First registered user becomes admin

#### Customer View Access:
- **URL:** `http://localhost/Food/public/customer.php`
- **Login Required:** No (Public access)

### 3.2 First Time Login

**Step 1: Register Account**
1. Open admin panel URL
2. Click "Register" button
3. Fill in registration form:
   - Name
   - Email
   - Password
4. Click "Register"
5. First user automatically becomes Admin

**Step 2: Login**
1. Enter your email
2. Enter your password
3. Click "Login"

### 3.3 Dashboard Overview

After login, you'll see the main dashboard with:
- **KPI Cards:** Today's production, leftovers, active discounts, sales
- **Sales Chart:** 7-day revenue trends
- **Inventory Status:** Current stock levels
- **Quick Actions:** Links to main features

---

## 4. ADMIN PANEL GUIDE

### 4.1 Navigation Menu

The sidebar contains 9 main sections:

1. **Dashboard** - Overview and statistics
2. **Food Items** - Manage products
3. **Production** - Track daily production
4. **Discounts** - Manage offers
5. **Ingredients** - Manage raw materials
6. **Inventory** - Stock management
7. **Sales** - Record sales
8. **Waste** - Log waste
9. **Reports** - Analytics and reports

### 4.2 Food Items Management

#### Adding a New Food Item

**Step 1:** Click "Food Items" in sidebar  
**Step 2:** Click "Add Food Item" button  
**Step 3:** Fill in the form:
- **Food Item Name:** e.g., "Chicken Roll"
- **Category:** Select from dropdown (Main Course, Appetizer, Dessert, Beverage, Snack)
- **Cost Price (Rs.):** Total cost to make the item
- **Selling Price (Rs.):** Price customers pay

**Step 4:** Click "Add Food Item"

**Live Profit Calculator:**
- Automatically shows profit per item
- Displays profit margin percentage
- Updates in real-time as you type

#### Editing Food Items

1. Go to Food Items list
2. Click "Edit" button next to item
3. Modify details
4. Click "Update Food Item"

#### Deleting Food Items

1. Go to Food Items list
2. Click "Delete" button next to item
3. Confirm deletion
4. Item will be removed

### 4.3 Daily Production Tracking

#### Recording Production

**Step 1:** Click "Production" in sidebar  
**Step 2:** Click "Add Production" button  
**Step 3:** Fill in the form:
- **Production Date:** Select date (default: today)
- **Food Item:** Select from dropdown
- **Quantity Produced:** Enter number of items made

**Step 4:** Click "Add Production"

#### Updating Sales (Sold Quantity)

**Method 1: Direct Update**
1. Find the production record in the table
2. Enter sold quantity in the "Sold" column
3. System automatically calculates leftover
4. Formula: `Leftover = Produced - Sold`

**Method 2: Sales Section**
- Use the Sales section to record detailed sales
- Production table will update automatically

#### Understanding the Production Table

| Column | Description |
|--------|-------------|
| Date | Production date |
| Food Item | Product name |
| Produced | Total quantity made |
| Sold | Quantity sold (editable) |
| Leftover | Auto-calculated (Produced - Sold) |
| Waste % | Percentage of waste |
| Actions | Edit/Delete buttons |

### 4.4 Discount Management

#### How AI Suggestions Work

The system automatically suggests discounts based on:

**Time-Based Rules:**
- After 6:00 PM → 30-40% discount
- After 3:00 PM → 15-25% discount

**Leftover-Based Rules:**
- More than 70% leftover → 20% discount
- More than 50% leftover → 15% discount

#### Accepting Discount Suggestions

**Step 1:** Go to "Discounts" section  
**Step 2:** Review AI suggestions card  
**Step 3:** For each suggestion, you can:
- **Accept & Activate:** Discount goes live immediately
- **Reject:** Hide the suggestion

**Step 4:** Accepted discounts appear in "Active Discounts" table

#### Managing Active Discounts

**Activate Pending Discount:**
1. Find discount with "Pending" status
2. Click "Activate" button
3. Discount becomes visible to customers

**Cancel Active Discount:**
1. Find discount with "Active" status
2. Click "Cancel" button
3. Discount is removed from customer view

#### Discount Information Display

Each discount shows:
- Food item name and category
- Discount percentage
- Original price vs. discounted price
- Time period (start - end time)
- Leftover quantity
- Current status

### 4.5 Sales Recording

#### How Sales Tracking Works

**3-Step Process:**

**Step 1: Select Menu Item**
- Choose food item from dropdown
- Dropdown shows: Item name (Rs. price)

**Step 2: Enter Quantity**
- Input number of items sold
- Can be decimal (e.g., 2.5)

**Step 3: Auto Calculate**
- Revenue = Quantity × Price
- Automatically calculated and saved

#### Adding a Sale

1. Go to "Sales" section
2. Fill in the form:
   - **Date:** Select sale date
   - **Menu Item:** Select from dropdown
   - **Quantity Sold:** Enter amount
3. Click "Add Sale"
4. Revenue is auto-calculated

#### Viewing Recent Sales

The "Recent Sales" table shows:
- Sale date
- Menu item name
- Quantity sold
- Revenue (Rs.)

### 4.6 Inventory Management

#### Adding Ingredients

1. Click "Ingredients" in sidebar
2. Click "Add Ingredient"
3. Fill in details:
   - Name
   - Unit (kg, liters, pieces)
   - Cost per unit
   - Reorder level
4. Click "Add"

#### Managing Stock

1. Go to "Inventory" section
2. View current stock levels
3. Add transactions:
   - **IN:** Stock received
   - **OUT:** Stock used
   - **ADJUST:** Corrections

#### Stock Alerts

Dashboard shows:
- **Critical:** Stock below 50% of reorder level (Red)
- **Low Stock:** Stock below reorder level (Yellow)
- **In Stock:** Sufficient stock (Green)

### 4.7 Waste Logging

#### Recording Waste

1. Go to "Waste" section
2. Click "Add Waste Log"
3. Fill in form:
   - **Waste Date:** Select date
   - **Ingredient/Menu Item:** Choose one
   - **Waste Type:** Spoilage, Expired, Damaged, etc.
   - **Quantity:** Amount wasted
   - **Cost:** Estimated cost
   - **Note:** Optional description
4. Click "Add"

#### Waste Analytics

View waste reports by:
- Date range
- Ingredient
- Menu item
- Waste type
- Total cost

### 4.8 Reports & Analytics

#### Available Reports

1. **Sales Report:** Revenue by date/item
2. **Waste Report:** Waste by ingredient/item
3. **Production Report:** Production vs. sales
4. **Discount Report:** Discount effectiveness
5. **Inventory Report:** Stock levels and usage

#### Generating Reports

1. Go to "Reports" section
2. Select report type
3. Choose date range
4. Click "Generate"
5. View/Export results

---

## 5. CUSTOMER INTERFACE GUIDE

### 5.1 Accessing Customer View

**URL:** `http://localhost/Food/public/customer.php`

**No login required** - Public access for all customers

### 5.2 Navigation Tabs

**HOME Tab:**
- Restaurant introduction
- Full menu with prices
- Available quantities
- Active discount badges

**Special Discounts Tab:**
- Today's discount offers
- Savings calculator
- Time remaining
- Limited quantity alerts

### 5.3 Viewing Menu Items

#### Menu Card Information:
- 🍽️ Food emoji icon
- Item name
- Category badge
- Current price (Rs.)
- Original price (if discounted)
- Discount percentage badge
- Availability status

#### Availability Indicators:
- ✅ **Available:** Green badge with quantity
- ⏳ **Check back later:** Red badge (out of stock)

### 5.4 Special Discounts Page

#### Discount Card Shows:
- Food item name and category
- Large discount percentage
- Discounted price (Rs.)
- Original price (strikethrough)
- Savings amount
- Valid until time
- Items remaining

#### Example:
```
Chicken Roll
Main Course

30% OFF

Rs. 350.00
Rs. 500.00  Save Rs. 150.00

Until 9:00 PM
30 left
```

### 5.5 Email Subscription

#### Subscribing for Alerts:

**Step 1:** Scroll to bottom of page  
**Step 2:** Find "Get Discount Alerts via Email" section  
**Step 3:** Enter your email address  
**Step 4:** Click "Subscribe"  
**Step 5:** Confirmation message appears

#### What You'll Receive:
- Email notifications when new discounts are activated
- Discount details and savings information
- Direct link to view offers
- Unsubscribe option in every email

### 5.6 Auto-Refresh Feature

The customer page automatically refreshes every **30 seconds** to show:
- Latest discount offers
- Updated prices
- Current availability
- New menu items

---

## 6. DAILY OPERATIONS

### 6.1 Morning Routine (Opening)

**Time: 8:00 AM - 10:00 AM**

1. **Login to Admin Panel**
2. **Record Today's Production:**
   - Go to Production section
   - Add each food item produced
   - Enter quantities
3. **Check Inventory:**
   - Review stock levels
   - Note any low stock items
   - Plan reorders if needed
4. **Review Dashboard:**
   - Check yesterday's performance
   - Review pending tasks

### 6.2 Afternoon Routine (Mid-Day)

**Time: 12:00 PM - 3:00 PM**

1. **Update Sales:**
   - Go to Production section
   - Update sold quantities
   - System calculates leftovers
2. **Record Sales:**
   - Go to Sales section
   - Add sales transactions
   - Verify revenue calculations
3. **Monitor Inventory:**
   - Check ingredient usage
   - Update stock transactions

### 6.3 Evening Routine (Before Closing)

**Time: 5:00 PM - 7:00 PM**

1. **Final Sales Update:**
   - Update all sold quantities
   - Verify leftover calculations
2. **Review AI Discount Suggestions:**
   - Go to Discounts section
   - Review suggested discounts
   - Accept appropriate offers
3. **Activate Discounts:**
   - Activate accepted discounts
   - Verify customer view shows offers
4. **Monitor Customer Response:**
   - Check discount effectiveness
   - Adjust if needed

### 6.4 Night Routine (Closing)

**Time: 9:00 PM - 10:00 PM**

1. **Final Sales Count:**
   - Update final sold quantities
   - Record remaining leftovers
2. **Log Waste:**
   - Go to Waste section
   - Record any spoiled items
   - Enter waste costs
3. **Cancel Expired Discounts:**
   - Go to Discounts section
   - Cancel time-expired offers
4. **Review Daily Report:**
   - Check dashboard summary
   - Note improvements for tomorrow

### 6.5 Weekly Tasks

**Every Monday:**
- Review weekly sales report
- Analyze waste patterns
- Adjust production quantities
- Plan inventory orders

**Every Friday:**
- Generate weekly reports
- Review discount effectiveness
- Check inventory levels
- Plan weekend production

---

## 7. TROUBLESHOOTING

### 7.1 Common Issues & Solutions

#### Issue 1: Cannot Login

**Symptoms:** Login fails, error message appears

**Solutions:**
1. Verify email and password are correct
2. Check CAPS LOCK is off
3. Clear browser cache (Ctrl+F5)
4. Try different browser
5. Contact admin to reset password

#### Issue 2: Dropdown Menu is Empty

**Symptoms:** Food items dropdown shows no options

**Solutions:**
1. Add food items first (Food Items section)
2. Refresh the page (F5)
3. Check database connection
4. Verify food items exist in database

#### Issue 3: Discounts Not Showing on Customer Page

**Symptoms:** Customer view doesn't show active discounts

**Solutions:**
1. Verify discount status is "Active"
2. Check time period is current
3. Refresh customer page (F5)
4. Clear browser cache
5. Check leftover quantity > 0

#### Issue 4: Sales Not Saving

**Symptoms:** Error when adding sales

**Solutions:**
1. Run database migration SQL:
   ```sql
   ALTER TABLE sales DROP FOREIGN KEY fk_sales_menu;
   ALTER TABLE sales CHANGE menu_item_id food_item_id INT NOT NULL;
   ALTER TABLE sales ADD CONSTRAINT fk_sales_food 
     FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE;
   ```
2. Refresh page and try again

#### Issue 5: Waste Log Error

**Symptoms:** Cannot add waste log

**Solutions:**
1. Run database migration SQL:
   ```sql
   ALTER TABLE waste_logs DROP FOREIGN KEY fk_waste_menu;
   ALTER TABLE waste_logs CHANGE menu_item_id food_item_id INT NULL;
   ALTER TABLE waste_logs ADD CONSTRAINT fk_waste_food 
     FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE SET NULL;
   ```
2. Refresh page and try again

#### Issue 6: CSS Not Loading

**Symptoms:** Page looks unstyled, no colors

**Solutions:**
1. Hard refresh: Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)
2. Clear browser cache completely
3. Check CSS file path in browser console (F12)
4. Verify styles.css exists in public/assets/css/

#### Issue 7: Email Notifications Not Sending

**Symptoms:** Subscribers don't receive emails

**Solutions:**
1. Check MailHog is running (localhost:8025)
2. Verify SMTP settings in DiscountsController
3. Check email_subscribers table has active subscribers
4. Test email configuration

### 7.2 Database Issues

#### Resetting Database

If database is corrupted:

1. Open phpMyAdmin
2. Select food_waste_opt database
3. Click "Operations" tab
4. Click "Drop database"
5. Create new database: food_waste_opt
6. Import: database/schema.sql
7. (Optional) Import: database/sample_data.sql

#### Backup Database

**Regular Backups (Recommended Weekly):**

1. Open phpMyAdmin
2. Select food_waste_opt database
3. Click "Export" tab
4. Choose "Quick" method
5. Click "Go"
6. Save .sql file with date: `backup_2024_12_15.sql`

### 7.3 Performance Issues

#### Slow Page Loading

**Solutions:**
1. Clear browser cache
2. Restart XAMPP services
3. Optimize database (phpMyAdmin → Operations → Optimize table)
4. Check server resources (RAM, CPU)
5. Reduce auto-refresh interval

#### Database Connection Errors

**Solutions:**
1. Check XAMPP MySQL is running
2. Verify config.php database credentials
3. Test connection in phpMyAdmin
4. Restart MySQL service

---

## 8. FAQ (Frequently Asked Questions)

### 8.1 General Questions

**Q1: Who can access the admin panel?**  
A: Only registered users with valid login credentials. First user becomes admin automatically.

**Q2: Can customers create accounts?**  
A: No, customer view is public and doesn't require accounts. They can only subscribe with email.

**Q3: How often should I update production data?**  
A: Update production in the morning and sales throughout the day. Final update before closing.

**Q4: Can I modify discount percentages?**  
A: Currently, discounts are AI-suggested. You can accept or reject them.

**Q5: How long do discounts remain active?**  
A: Discounts are active until the end time specified or until you cancel them.

### 8.2 Technical Questions

**Q6: What happens if I delete a food item?**  
A: All related records (production, discounts, sales) will be deleted due to CASCADE constraint.

**Q7: Can I export reports?**  
A: Yes, reports can be exported from the Reports section.

**Q8: Is the system mobile-friendly?**  
A: Yes, both admin panel and customer view are fully responsive.

**Q9: Can multiple users login simultaneously?**  
A: Yes, multiple admin/staff users can work at the same time.

**Q10: How is leftover calculated?**  
A: Formula: `Leftover = Quantity Produced - Quantity Sold`

### 8.3 Business Questions

**Q11: How much can I save using this system?**  
A: Typical restaurants reduce waste by 70% and generate additional revenue from discounted sales. Potential savings: Rs. 87,000+ per year.

**Q12: When should I activate discounts?**  
A: Activate discounts 2-3 hours before closing time to maximize sales of leftovers.

**Q13: What discount percentage should I use?**  
A: Follow AI suggestions: 15-25% for afternoon, 30-40% for evening.

**Q14: How do I track ROI?**  
A: Compare waste costs before/after using the system. Check Reports section for analytics.

**Q15: Can I customize categories?**  
A: Yes, edit the category dropdown in food_items/form.php file.

---

## 9. TECHNICAL SUPPORT

### 9.1 System Information

**System Name:** Food Waste & Profit Optimization System  
**Version:** 1.0.0  
**Technology Stack:**
- Backend: PHP 8+
- Database: MySQL/MariaDB
- Frontend: HTML5, CSS3, JavaScript
- Server: Apache (XAMPP)

### 9.2 Documentation Files

Located in project root directory:

1. **README.md** - Quick overview
2. **SETUP_GUIDE.md** - Installation instructions
3. **QUICK_REFERENCE.md** - Daily operations guide
4. **VISUAL_SUMMARY.md** - Quick overview with diagrams
5. **PROJECT_COMPLETE.md** - Full handover document
6. **SYSTEM_ARCHITECTURE.md** - Technical details
7. **TESTING_CHECKLIST.md** - 150+ test cases
8. **USER_MANUAL.md** - This document

### 9.3 File Structure

```
Food/
├── app/
│   ├── config/          # Configuration files
│   ├── controllers/     # Business logic
│   ├── models/          # Data models
│   ├── views/           # UI templates
│   └── core/            # Framework core
├── database/
│   ├── schema.sql       # Database structure
│   ├── sample_data.sql  # Test data
│   ├── fix_sales_foreign_key.sql
│   └── fix_waste_foreign_key.sql
├── public/
│   ├── assets/          # CSS, JS, images
│   ├── index.php        # Main entry
│   └── customer.php     # Customer view
└── docs/                # Documentation
```

### 9.4 Important URLs

- **Admin Panel:** http://localhost/Food/public/
- **Customer View:** http://localhost/Food/public/customer.php
- **phpMyAdmin:** http://localhost/phpmyadmin
- **MailHog (Email Testing):** http://localhost:8025

### 9.5 Database Tables

Main tables in food_waste_opt database:

1. **users** - Admin/staff accounts
2. **food_items** - Products/menu items
3. **daily_production** - Production tracking
4. **discounts** - Discount offers
5. **sales** - Sales transactions
6. **ingredients** - Raw materials
7. **inventory_transactions** - Stock movements
8. **waste_logs** - Waste records
9. **email_subscribers** - Customer emails
10. **notifications** - System notifications

### 9.6 Security Features

- ✅ Password hashing (Bcrypt)
- ✅ SQL injection protection (PDO)
- ✅ XSS protection (HTML escaping)
- ✅ CSRF token validation
- ✅ Secure session management
- ✅ Role-based access control

### 9.7 Browser Compatibility

**Fully Supported:**
- Google Chrome 90+
- Mozilla Firefox 88+
- Microsoft Edge 90+
- Safari 14+

**Mobile Browsers:**
- Chrome Mobile
- Safari iOS
- Samsung Internet

### 9.8 Getting Help

**For Installation Issues:**
- Read SETUP_GUIDE.md
- Check system requirements
- Verify XAMPP is running

**For Usage Questions:**
- Read this User Manual
- Check QUICK_REFERENCE.md
- Review FAQ section

**For Technical Issues:**
- Check Troubleshooting section
- Review error messages
- Check browser console (F12)

**For Feature Requests:**
- Document your requirement
- Check if feature exists
- Contact development team

---

## APPENDIX A: Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| Ctrl+F5 | Hard refresh (clear cache) |
| F5 | Refresh page |
| F12 | Open browser console |
| Ctrl+Shift+R | Hard reload |
| Alt+← | Go back |
| Alt+→ | Go forward |

---

## APPENDIX B: Status Indicators

### Discount Status:
- 🟢 **Active** - Currently visible to customers
- ⏰ **Expired** - Time period ended
- ❌ **Cancelled** - Manually cancelled
- ⏸️ **Pending** - Not yet activated

### Stock Status:
- 🟢 **In Stock** - Sufficient quantity
- 🟡 **Low Stock** - Below reorder level
- 🔴 **Critical** - Below 50% of reorder level

### Availability Status:
- ✅ **Available** - Items in stock
- ⏳ **Check back later** - Out of stock

---

## APPENDIX C: Calculation Formulas

### Leftover Calculation:
```
Leftover = Quantity Produced - Quantity Sold
```

### Waste Percentage:
```
Waste % = (Leftover / Quantity Produced) × 100
```

### Discounted Price:
```
Discounted Price = Original Price × (1 - Discount % / 100)
```

### Savings:
```
Savings = Original Price - Discounted Price
```

### Profit:
```
Profit = Selling Price - Cost Price
```

### Profit Margin:
```
Profit Margin % = (Profit / Selling Price) × 100
```

### Revenue:
```
Revenue = Quantity Sold × Selling Price
```

---

## DOCUMENT REVISION HISTORY

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | Dec 2024 | Initial release | Development Team |

---

## END OF USER MANUAL

**For additional support, refer to other documentation files in the project.**

**Thank you for using the Food Waste & Profit Optimization System!**

---

**© 2024 Food Waste Management System. All rights reserved.**
