# EMAIL SUBSCRIPTION SETUP

## ✅ What's Been Added:

1. **Email Subscription Box** - On customer view page
2. **Show All Food Items** - Customers see full menu with availability
3. **Auto Email Alerts** - When discount is activated, emails sent to subscribers
4. **Unsubscribe Link** - In every email

---

## 🚀 SETUP STEPS:

### Step 1: Add Email Subscribers Table
```sql
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select database: food_waste_opt
3. Click "SQL" tab
4. Run this SQL:
```

```sql
CREATE TABLE IF NOT EXISTS email_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  active TINYINT(1) DEFAULT 1,
  unsubscribe_token VARCHAR(64) UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Step 2: Test the System

1. **Open Customer View:**
   ```
   http://localhost/Food/public/customer.php
   ```

2. **You'll see:**
   - 🔔 Email subscription box at top
   - 🔥 Special discounts section (if any active)
   - 🍽️ Full menu with all food items
   - ✅ Availability status for each item

3. **Subscribe to emails:**
   - Enter your email
   - Click "Subscribe"
   - You'll get confirmation message

4. **Test email sending:**
   - Login as admin
   - Go to Discounts
   - Accept a discount suggestion
   - Email will be sent to all subscribers!

---

## 📧 EMAIL FEATURES:

### What Customers Get:
- Beautiful HTML email
- Discount percentage (e.g., 30% OFF)
- Original vs discounted price
- Savings amount
- Time limit
- "View All Offers" button
- Unsubscribe link

### Email Example:
```
Subject: 🔥 30% OFF on Chicken Roll!

Body:
🍽️ Special Discount Alert!

Chicken Roll
30% OFF - Limited Time Only!

$3.50 (was $5.00)
You save $1.50!

⏰ Hurry! Valid until 9:00 PM

[View All Offers Button]

Unsubscribe link at bottom
```

---

## 🎯 CUSTOMER VIEW FEATURES:

### 1. Email Subscription
- Simple form: just email, no password
- Success/error messages
- One-click subscribe

### 2. Active Discounts Section
- Shows only items with active discounts
- Large discount badges
- Original vs discounted prices
- Time limits
- Items left count

### 3. Full Menu Section
- Shows ALL food items
- Discount badge if applicable
- Availability status
- Regular price or discounted price
- Category tags

### 4. Notifications
- Latest updates
- Discount announcements

---

## 🔧 HOW IT WORKS:

### Workflow:
```
1. Customer visits public page
2. Sees email subscription box
3. Enters email → Subscribes
4. Email saved to database

Later...

5. Admin creates discount
6. System sends email to ALL subscribers
7. Customer receives beautiful HTML email
8. Customer clicks "View All Offers"
9. Sees discount on public page
10. Comes to restaurant and buys!
```

---

## ⚙️ EMAIL CONFIGURATION:

### Current Setup (PHP mail()):
- Uses PHP's built-in mail() function
- Works on most servers
- May go to spam folder

### To Improve Deliverability:

**Option 1: Configure PHP mail (XAMPP)**
1. Edit `php.ini`
2. Set SMTP settings
3. Restart Apache

**Option 2: Use Gmail SMTP**
1. Install PHPMailer
2. Configure Gmail credentials
3. Better deliverability

**Option 3: Use Email Service (Best)**
- SendGrid (free: 100 emails/day)
- Mailgun (free: 5,000 emails/month)
- Professional delivery

---

## 📱 CUSTOMER EXPERIENCE:

### What Customers See:

**Top Section:**
```
┌─────────────────────────────────────┐
│ 🔔 Get Discount Alerts via Email   │
│ [your@email.com] [Subscribe]        │
└─────────────────────────────────────┘
```

**Discount Section (if active):**
```
┌─────────────────────────────────────┐
│ 🔥 Special Discounts Today!         │
│                                     │
│ [Chicken Roll - 30% OFF]            │
│ [Pizza - 25% OFF]                   │
└─────────────────────────────────────┘
```

**Menu Section:**
```
┌─────────────────────────────────────┐
│ 🍽️ Our Menu                         │
│                                     │
│ [Chicken Roll] [Pizza] [Burger]     │
│ [Fried Rice]   [Cake]  [Juice]      │
│                                     │
│ Each shows:                         │
│ - Price (discounted if applicable)  │
│ - Availability                      │
│ - Category                          │
└─────────────────────────────────────┘
```

---

## ✅ TESTING CHECKLIST:

- [ ] Email subscribers table created
- [ ] Customer page loads
- [ ] Email subscription form visible
- [ ] Can subscribe with email
- [ ] Success message shows
- [ ] Duplicate email shows error
- [ ] All food items display
- [ ] Discounts show with badge
- [ ] Availability status shows
- [ ] Create discount as admin
- [ ] Email sent to subscribers
- [ ] Email looks good (HTML)
- [ ] Unsubscribe link works

---

## 🎉 YOU'RE DONE!

Now customers can:
- ✅ View full menu
- ✅ See which items available
- ✅ Subscribe for email alerts
- ✅ Get notified about discounts
- ✅ Unsubscribe anytime

And you can:
- ✅ Build email subscriber list
- ✅ Auto-notify customers
- ✅ Increase discount sales
- ✅ Reduce waste even more!

---

**Next Step:** Add some food items and test the email subscription!
