# VIEWS FOLDER EXPLANATION
## app/views/ - User Interface Layer

---

## 📁 Files in Views Folder (23 Files in 11 Folders)

```
app/views/
├── layouts/              # Master templates
│   ├── main.php         # Admin layout (sidebar + header)
│   └── customer.php     # Customer layout (public view)
│
├── auth/                # Authentication pages
│   ├── login.php        # Login form
│   └── register.php     # Registration form
│
├── dashboard/           # Dashboard
│   └── index.php        # Main dashboard with stats
│
├── food_items/          # Food items management
│   ├── list.php         # List all food items
│   └── form.php         # Add/Edit food item form
│
├── production/          # Production tracking
│   └── index.php        # Production & sales tracking
│
├── discounts/           # Discount management
│   └── index.php        # Discounts & AI suggestions
│
├── customer/            # Public customer view
│   └── index.php        # Customer discount page
│
├── ingredients/         # Ingredients management
│   ├── list.php         # List ingredients
│   └── form.php         # Add/Edit ingredient
│
├── inventory/           # Inventory tracking
│   └── index.php        # Inventory transactions
│
├── menu/                # Menu items
│   ├── list.php         # List menu items
│   └── form.php         # Add/Edit menu item
│
├── recipes/             # Recipe management
│   └── list.php         # Recipe ingredients
│
├── sales/               # Sales tracking
│   └── index.php        # Sales form & history
│
├── waste/               # Waste logging
│   └── index.php        # Waste form & logs
│
└── reports/             # Analytics & reports
    ├── index.php        # Sales & waste reports
    ├── forecast.php     # Demand forecasting
    └── recommendations.php  # AI recommendations
```

---

## 🎨 LAYOUTS (Master Templates)

### 1️⃣ layouts/main.php
**Purpose**: Admin panel layout (used by all admin pages)

**Contains**:
- Sidebar navigation
- Top header bar
- User info & logout
- Footer
- CSS/JS includes

**Structure**:
```html
<!DOCTYPE html>
<html>
<head>
  <!-- CSS, fonts, meta tags -->
</head>
<body>
  <aside class="sidebar">
    <!-- Navigation menu -->
    - Dashboard
    - Food Items
    - Production
    - Discounts
    - Ingredients
    - Inventory
    - Sales
    - Waste
    - Reports
  </aside>
  
  <main class="main-content">
    <header><!-- Top bar --></header>
    <div class="content">
      <?php include $viewFile; ?>  <!-- Page content here -->
    </div>
  </main>
  
  <footer><!-- Footer --></footer>
</body>
</html>
```

**Features**:
- Active menu highlighting
- User name display
- Responsive sidebar
- Auto-loading states

---

### 2️⃣ layouts/customer.php
**Purpose**: Public customer layout (no login required)

**Contains**:
- Navigation (Home, Discounts)
- Notification bell
- Auto-refresh (30 seconds)
- Gradient background

**Structure**:
```html
<!DOCTYPE html>
<html>
<head><!-- CSS --></head>
<body style="background: gradient">
  <nav>
    - Home
    - Special Discounts
  </nav>
  
  <div class="notification-bell">🔔</div>
  
  <main>
    <?php include $viewFile; ?>  <!-- Page content -->
  </main>
  
  <script>
    // Auto-refresh every 30 seconds
    setInterval(() => location.reload(), 30000);
  </script>
</body>
</html>
```

---

## 🔐 AUTH VIEWS (Authentication)

### 3️⃣ auth/login.php
**Purpose**: User login form

**Contains**:
- Email input
- Password input
- CSRF token
- Error messages
- Link to register

**Form Fields**:
```html
<form method="post">
  <input type="hidden" name="csrf" value="<?= CSRF::token() ?>">
  <input type="email" name="email" required>
  <input type="password" name="password" required>
  <button type="submit">Sign In</button>
</form>
```

---

### 4️⃣ auth/register.php
**Purpose**: New user registration

**Contains**:
- Name input
- Email input
- Password input
- CSRF token
- Success/error messages

**Note**: First user automatically becomes admin

---

## 📊 DASHBOARD VIEW

### 5️⃣ dashboard/index.php
**Purpose**: Main admin dashboard

**Displays**:
- **KPI Cards**:
  - Today's production
  - Today's leftovers
  - Active discounts
  - 7-day sales total
  
- **Sales Chart**: Line chart showing 7-day revenue
- **Inventory Table**: Stock levels with alerts
- **Quick Actions**: Links to main features

**Key Elements**:
```php
<!-- KPI Cards -->
<div class="kpis">
  <div class="kpi">
    <div class="kpi__label">Today's Production</div>
    <div class="kpi__value"><?= $todayProduction['total_produced'] ?></div>
  </div>
</div>

<!-- Sales Chart -->
<canvas id="salesChart" 
        data-labels='<?= json_encode($labels) ?>' 
        data-values='<?= json_encode($values) ?>'></canvas>

<!-- Inventory Table -->
<table>
  <tr>
    <td><?= $ingredient['name'] ?></td>
    <td><?= $ingredient['stock'] ?></td>
    <td>
      <span class="pill pill--warning">Low Stock</span>
    </td>
  </tr>
</table>
```

---

## 🍽️ FOOD ITEMS VIEWS

### 6️⃣ food_items/list.php
**Purpose**: Display all food items

**Contains**:
- Table with all food items
- Name, category, cost, selling price
- Edit/Delete buttons
- Add new button

**Table Structure**:
```html
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Category</th>
      <th>Cost Price</th>
      <th>Selling Price</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Chicken Pizza</td>
      <td>Pizza</td>
      <td>$5.00</td>
      <td>$12.99</td>
      <td>
        <a href="?r=food_items/edit&id=1">Edit</a>
        <form method="post" action="?r=food_items/delete">
          <button>Delete</button>
        </form>
      </td>
    </tr>
  </tbody>
</table>
```

---

### 7️⃣ food_items/form.php
**Purpose**: Add/Edit food item form

**Form Fields**:
- Name (text)
- Category (text)
- Cost price (number)
- Selling price (number)

**Used for both**: Create and Edit (mode determined by controller)

---

## 📈 PRODUCTION VIEW

### 8️⃣ production/index.php
**Purpose**: Track daily production and sales

**Contains**:
- Date selector
- Add production form
- Production table with:
  - Food item name
  - Quantity produced
  - Quantity sold
  - Quantity leftover (auto-calculated)
  - Update sales button

**Key Features**:
```html
<!-- Add Production -->
<form method="post" action="?r=production/add">
  <select name="food_item_id"><!-- Food items --></select>
  <input type="number" name="quantity_produced">
  <input type="date" name="production_date">
  <button>Add Production</button>
</form>

<!-- Production Table -->
<table>
  <tr>
    <td>Chicken Pizza</td>
    <td>100</td>  <!-- Produced -->
    <td>70</td>   <!-- Sold -->
    <td>30</td>   <!-- Leftover = 100 - 70 -->
    <td>
      <form method="post" action="?r=production/update_sales">
        <input type="number" name="quantity_sold">
        <button>Update</button>
      </form>
    </td>
  </tr>
</table>
```

---

## 💸 DISCOUNTS VIEW

### 9️⃣ discounts/index.php
**Purpose**: AI suggestions and discount management

**Contains**:
- **AI Suggestions Section**:
  - Suggested discounts based on leftovers
  - Accept/Reject buttons
  
- **Active Discounts Table**:
  - Food item name
  - Discount percentage
  - Time period
  - Status
  - Cancel button

**AI Suggestions Display**:
```html
<div class="suggestions">
  <div class="suggestion-card">
    <h4>Chicken Pizza</h4>
    <p>30 items leftover (30% waste)</p>
    <div class="discount-badge">30% OFF</div>
    <p>Original: $12.99 → Discounted: $9.09</p>
    <form method="post" action="?r=discounts/create">
      <input type="hidden" name="food_item_id" value="1">
      <input type="hidden" name="discount_percentage" value="30">
      <button>Accept Suggestion</button>
    </form>
  </div>
</div>
```

---

## 👥 CUSTOMER VIEW

### 🔟 customer/index.php
**Purpose**: Public page showing discounts (no login)

**Contains**:
- All menu items with prices
- Active discounts highlighted
- Discount carousel
- Email subscription form
- Auto-refresh every 30 seconds

**Discount Display**:
```html
<div class="discount-card">
  <div class="discount-badge">30% OFF</div>
  <h3>Chicken Pizza</h3>
  <div class="price">
    <span class="original">$12.99</span>
    <span class="discounted">$9.09</span>
  </div>
  <p>Until 9:00 PM | 30 items left</p>
</div>
```

---

## 📦 OTHER VIEWS (Supporting Features)

### 1️⃣1️⃣ ingredients/list.php & form.php
**Purpose**: Manage ingredients
- List all ingredients
- Add/Edit ingredient form

### 1️⃣2️⃣ inventory/index.php
**Purpose**: Track inventory transactions
- Add transaction form (IN/OUT/ADJUST)
- Recent transactions table

### 1️⃣3️⃣ menu/list.php & form.php
**Purpose**: Manage menu items
- List menu items
- Add/Edit menu item

### 1️⃣4️⃣ recipes/list.php
**Purpose**: Manage recipe ingredients
- Select menu item
- Add/remove ingredients
- Set quantities

### 1️⃣5️⃣ sales/index.php
**Purpose**: Record sales
- Add sale form
- Recent sales table

### 1️⃣6️⃣ waste/index.php
**Purpose**: Log waste
- Add waste form
- Recent waste logs

### 1️⃣7️⃣ reports/index.php
**Purpose**: Sales & waste analytics
- Date range selector
- Charts and graphs
- Summary statistics

### 1️⃣8️⃣ reports/forecast.php
**Purpose**: Demand forecasting
- Predicted sales
- Recommended production

### 1️⃣9️⃣ reports/recommendations.php
**Purpose**: AI recommendations
- Waste reduction tips
- Production optimization

---

## 🎨 Common View Elements

### Form Structure
```html
<form method="post" action="?r=controller/action">
  <input type="hidden" name="csrf" value="<?= CSRF::token() ?>">
  
  <div class="form__group">
    <label>Field Name</label>
    <input type="text" name="field" required>
  </div>
  
  <button type="submit" class="btn btn--primary">Submit</button>
</form>
```

### Table Structure
```html
<table class="table">
  <thead>
    <tr>
      <th>Column 1</th>
      <th>Column 2</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= htmlspecialchars($item['value']) ?></td>
        <td>
          <a href="?r=controller/edit&id=<?= $item['id'] ?>">Edit</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
```

### Alert Messages
```html
<div class="alert alert--success">Success message</div>
<div class="alert alert--danger">Error message</div>
<div class="alert alert--warning">Warning message</div>
<div class="alert alert--info">Info message</div>
```

### Pills/Badges
```html
<span class="pill pill--success">Active</span>
<span class="pill pill--danger">Critical</span>
<span class="pill pill--warning">Low Stock</span>
<span class="pill pill--info">Pending</span>
```

---

## 🔒 Security in Views

### XSS Prevention
```php
<?= htmlspecialchars($user_input) ?>  // Always escape output
```

### CSRF Protection
```html
<input type="hidden" name="csrf" value="<?= CSRF::token() ?>">
```

### Safe Data Display
```php
// Numbers
<?= number_format($price, 2) ?>

// Dates
<?= date('M j, Y', strtotime($date)) ?>

// JSON
<?= htmlspecialchars(json_encode($data)) ?>
```

---

## 📊 View Summary Table

| View Folder | Files | Purpose | Layout Used |
|-------------|-------|---------|-------------|
| **layouts/** | 2 | Master templates | N/A |
| **auth/** | 2 | Login/Register | main.php |
| **dashboard/** | 1 | Main dashboard | main.php |
| **food_items/** | 2 | Food CRUD | main.php |
| **production/** | 1 | Production tracking | main.php |
| **discounts/** | 1 | AI discounts | main.php |
| **customer/** | 1 | Public view | customer.php |
| **ingredients/** | 2 | Ingredients CRUD | main.php |
| **inventory/** | 1 | Inventory tracking | main.php |
| **menu/** | 2 | Menu CRUD | main.php |
| **recipes/** | 1 | Recipe management | main.php |
| **sales/** | 1 | Sales tracking | main.php |
| **waste/** | 1 | Waste logging | main.php |
| **reports/** | 3 | Analytics | main.php |

---

## 💡 How Views Work

```
1. Controller prepares data
2. Controller calls: $this->render('view/name', $data)
3. Layout (main.php or customer.php) loads
4. Layout includes specific view file
5. View displays data using HTML + PHP
6. Browser renders final HTML
```

---

## 📝 Summary

**Views = User Interface**
- Display data from controllers
- HTML + PHP templates
- Use layouts for consistency
- Forms for user input
- Tables for data display
- Always escape output (security)
- No database queries (use controllers)

**23 view files organized in 11 folders handle all UI for the application.**
