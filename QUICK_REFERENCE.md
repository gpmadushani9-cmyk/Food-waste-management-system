# QUICK REFERENCE - Daily Operations

## 🌅 MORNING ROUTINE (8:00 AM - 10:00 AM)

### 1. Check Dashboard
- Login: `http://localhost/Food/public/`
- Review yesterday's stats
- Check inventory alerts

### 2. Record Today's Production
```
Navigation: Dashboard → Production
```
- Select today's date
- For each food item, enter quantity produced
- Example: Chicken Roll → 100 items

**Quick Entry:**
```
Chicken Roll:    100 items
Pizza:           50 items  
Burger:          80 items
Fried Rice:      60 items
```

---

## 🌞 DURING THE DAY (10:00 AM - 6:00 PM)

### Update Sales as Items Sell
```
Navigation: Production page
```
- Update "Sold" column in real-time
- System auto-calculates leftovers
- Monitor leftover percentage

**Example:**
```
Item          Produced  Sold  Leftover  Waste%
Chicken Roll  100       70    30        30%
Pizza         50        45    5         10%
Burger        80        75    5         6%
```

---

## 🌆 AFTERNOON (3:00 PM - 6:00 PM)

### Check Discount Suggestions
```
Navigation: Dashboard → Discounts
```

**AI Suggestions Appear When:**
- Leftover > 30% after 3:00 PM
- Leftover > 50% after 6:00 PM
- Leftover > 70% anytime

**Actions:**
1. Review suggested discount
2. Click "Accept & Activate" or "Reject"
3. Discount goes live immediately

---

## 🌃 EVENING (6:00 PM - 9:00 PM)

### Activate Evening Discounts
```
Navigation: Discounts
```

**Typical Evening Discounts:**
- 6:00 PM: 20-30% OFF
- 8:00 PM: 40-50% OFF (closing time)

**Customer View:**
- Share link: `http://localhost/Food/public/customer.php`
- Display on TV/tablet
- Auto-updates every 30 seconds

---

## 🌙 END OF DAY (9:00 PM - 10:00 PM)

### 1. Final Sales Update
- Update all "Sold" quantities
- Review final leftover count

### 2. Log Waste (if any)
```
Navigation: Waste
```
- Record unsold items as waste
- System tracks waste patterns

### 3. Review Dashboard
- Check today's performance
- Review profit margins
- Plan tomorrow's production

---

## 📱 CUSTOMER VIEW MANAGEMENT

### Activate Discount for Customers:
1. Go to: **Discounts**
2. Accept AI suggestion OR create manual discount
3. Status changes to "Active"
4. Customers see it immediately

### Deactivate Discount:
1. Go to: **Discounts**
2. Find active discount
3. Click "Cancel"
4. Removed from customer view

---

## 🎯 DISCOUNT DECISION GUIDE

### When to Activate Discounts:

**High Priority (Activate Now):**
- ❗ Leftover > 50% after 6:00 PM
- ❗ Perishable items with short shelf life
- ❗ Items expiring today

**Medium Priority (Consider):**
- ⚠️ Leftover 30-50% after 3:00 PM
- ⚠️ Slow-moving items
- ⚠️ Weather-dependent items

**Low Priority (Monitor):**
- ℹ️ Leftover < 30%
- ℹ️ Items that can be reused tomorrow
- ℹ️ Frozen items

---

## 💡 TIPS FOR SUCCESS

### Maximize Profit:
1. ✅ Record production accurately
2. ✅ Update sales in real-time
3. ✅ Activate discounts early (3-6 PM)
4. ✅ Monitor customer view regularly
5. ✅ Adjust production based on patterns

### Minimize Waste:
1. ✅ Use AI suggestions
2. ✅ Start with 20% discount, increase if needed
3. ✅ Promote discounts on social media
4. ✅ Display customer view prominently
5. ✅ Track waste patterns weekly

---

## 🔢 QUICK CALCULATIONS

### Profit with Discount:
```
Original Price:    $5.00
Discount 30%:      -$1.50
Discounted Price:  $3.50
Cost Price:        $2.50
Profit:            $1.00 (Better than $0 waste!)
```

### Waste vs Discount Comparison:
```
Scenario A (No Discount):
- 30 items leftover → $0 revenue → $75 loss

Scenario B (30% Discount):
- Sell 25 items at $3.50 → $87.50 revenue → $25 profit
```

**Discount is ALWAYS better than waste!**

---

## 📞 QUICK LINKS

- **Admin Panel**: `http://localhost/Food/public/`
- **Customer View**: `http://localhost/Food/public/customer.php`
- **Dashboard**: `http://localhost/Food/public/index.php?r=dashboard/index`
- **Production**: `http://localhost/Food/public/index.php?r=production/index`
- **Discounts**: `http://localhost/Food/public/index.php?r=discounts/index`

---

## ⚡ KEYBOARD SHORTCUTS

- **F5**: Refresh page
- **Ctrl + F5**: Hard refresh (clear cache)
- **F11**: Full screen (for customer display)
- **Ctrl + +**: Zoom in
- **Ctrl + -**: Zoom out

---

## 🆘 COMMON ISSUES

### Issue: Discount not showing on customer page
**Fix**: 
1. Check discount status is "Active"
2. Verify time period includes current time
3. Refresh customer page (F5)

### Issue: Can't update sales quantity
**Fix**: 
1. Ensure production was recorded first
2. Check sold quantity ≤ produced quantity
3. Refresh page and try again

### Issue: No AI suggestions appearing
**Fix**: 
1. Record today's production first
2. Update sales to create leftovers
3. Wait until after 3:00 PM
4. Click "Refresh" on discount page

---

## 📊 DAILY CHECKLIST

**Morning:**
- [ ] Login to admin panel
- [ ] Record today's production
- [ ] Check inventory levels

**Afternoon:**
- [ ] Update sales quantities
- [ ] Review discount suggestions
- [ ] Activate early discounts if needed

**Evening:**
- [ ] Check leftover percentages
- [ ] Activate evening discounts
- [ ] Monitor customer view

**Night:**
- [ ] Final sales update
- [ ] Log waste if any
- [ ] Review daily performance

---

**Remember: Every item sold at a discount is better than waste!**
