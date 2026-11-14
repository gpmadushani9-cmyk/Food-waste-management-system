# CONTROLLERS FOLDER EXPLANATION
## app/controllers/ - Business Logic Layer

---

## 📁 Files in Controllers Folder (14 Files)

```
app/controllers/
├── AuthController.php          # Login, Register, Logout
├── DashboardController.php     # Main dashboard & statistics
├── FoodItemsController.php     # Food items CRUD
├── ProductionController.php    # Daily production tracking
├── DiscountsController.php     # AI suggestions & discounts
├── CustomerController.php      # Public customer view
├── SalesController.php         # Sales tracking
├── WasteController.php         # Waste logging
├── IngredientsController.php   # Ingredients management
├── InventoryController.php     # Inventory tracking
├── MenuController.php          # Menu items management
├── RecipesController.php       # Recipe management
├── ReportsController.php       # Analytics & reports
└── SubscribersController.php   # Email subscribers
```

---

## 🎯 CORE CONTROLLERS (Main Features)

### 1️⃣ AuthController.php
**Purpose**: User authentication (login/register/logout)

**Methods**:
- `login()` - User login form & validation
- `register()` - New user registration (first user = admin)
- `logout()` - End user session

**Key Code**:
```php
// Login validation
$user = User::findByEmail($email);
if (password_verify($pass, $user['password_hash'])) {
    Auth::login($user);
}

// Register (first user becomes admin)
$role = User::countAll() === 0 ? 'admin' : 'staff';
User::create($name, $email, $pass, $role);
```

**URL**: `index.php?r=auth/login`

---

### 2️⃣ DashboardController.php
**Purpose**: Main admin dashboard with statistics

**Methods**:
- `index()` - Display dashboard with stats

**Shows**:
- Today's production (produced/sold/leftover)
- Active discounts count
- Waste percentage
- Sales charts
- Stock levels

**Key Code**:
```php
// Today's production stats
$todayProduction = DB::query("
    SELECT SUM(quantity_produced) as total_produced, 
           SUM(quantity_sold) as total_sold, 
           SUM(quantity_leftover) as total_leftover
    FROM daily_production WHERE production_date = ?", [$today]);

// Waste percentage calculation
$wastePercentage = ($leftover / $produced) * 100;
```

**URL**: `index.php?r=dashboard/index`

---

### 3️⃣ FoodItemsController.php
**Purpose**: Manage food items (products restaurant sells)

**Methods**:
- `index()` - List all food items
- `create()` - Add new food item
- `edit()` - Update food item
- `delete()` - Remove food item

**Key Code**:
```php
// Create food item
DB::query("INSERT INTO food_items (name, category, cost_price, selling_price) 
           VALUES (?, ?, ?, ?)", 
           [$name, $category, $cost_price, $selling_price]);

// Update food item
DB::query("UPDATE food_items SET name = ?, price = ? WHERE id = ?", 
           [$name, $price, $id]);
```

**URL**: `index.php?r=food_items/index`

---

### 4️⃣ ProductionController.php
**Purpose**: Track daily production and sales

**Methods**:
- `index()` - Display production for selected date
- `add()` - Record production quantity
- `update_sales()` - Update sold quantity (auto-calculates leftovers)

**Key Code**:
```php
// Add production
DB::query("INSERT INTO daily_production (food_item_id, production_date, quantity_produced) 
           VALUES (?, ?, ?)", [$food_item_id, $date, $quantity]);

// Update sales & calculate leftover
$leftover = max(0, $quantity_produced - $quantity_sold);
DB::query("UPDATE daily_production SET quantity_sold = ?, quantity_leftover = ? 
           WHERE id = ?", [$quantity_sold, $leftover, $id]);
```

**URL**: `index.php?r=production/index`

---

### 5️⃣ DiscountsController.php
**Purpose**: AI discount suggestions & discount management

**Methods**:
- `index()` - Display discounts & suggestions
- `getSuggestions()` - AI algorithm for discount recommendations
- `create()` - Create new discount
- `activate()` - Activate discount
- `cancel()` - Cancel discount
- `sendDiscountEmails()` - Email notifications to subscribers

**AI Algorithm**:
```php
// Time-based + percentage-based discount logic
if ($current_time >= '18:00:00') {
    $discount = $leftover_percentage > 50 ? 40 : 30;  // Evening: 30-40%
} elseif ($current_time >= '15:00:00') {
    $discount = $leftover_percentage > 30 ? 25 : 15;  // Afternoon: 15-25%
} elseif ($leftover_percentage > 70) {
    $discount = 20;  // High waste: 20%
}
```

**Email Notification**:
```php
// Send email to all subscribers
$subscribers = DB::query("SELECT email FROM email_subscribers WHERE active = 1");
foreach ($subscribers as $sub) {
    mail($sub['email'], $subject, $message, $headers);
}
```

**URL**: `index.php?r=discounts/index`

---

### 6️⃣ CustomerController.php
**Purpose**: Public customer view (no login required)

**Methods**:
- `index()` - Display menu & active discounts
- `subscribe()` - Email subscription
- `unsubscribe()` - Unsubscribe from emails
- `api()` - JSON API for discounts

**Key Code**:
```php
// Get active discounts
$discounts = DB::query("
    SELECT d.*, fi.name, fi.selling_price 
    FROM discounts d 
    JOIN food_items fi ON d.food_item_id = fi.id 
    WHERE d.status = 'active' 
    AND d.start_time <= ? AND d.end_time >= ?", 
    [$current_time, $current_time]);

// Email subscription
$token = bin2hex(random_bytes(32));
DB::query("INSERT INTO email_subscribers (email, unsubscribe_token) 
           VALUES (?, ?)", [$email, $token]);
```

**URL**: `public/customer.php` (direct access, no routing)

---

## 📊 SUPPORTING CONTROLLERS

### 7️⃣ SalesController.php
**Purpose**: Track sales transactions

**Methods**:
- `index()` - Display sales form & recent sales
- `add()` - Record new sale

**Key Code**:
```php
$revenue = $quantity * $price;
Sale::add([
    'sale_date' => $date,
    'menu_item_id' => $menuItemId,
    'quantity' => $qty,
    'revenue' => $revenue
]);
```

---

### 8️⃣ WasteController.php
**Purpose**: Log food waste

**Methods**:
- `index()` - Display waste form & recent logs
- `add()` - Record waste entry

**Key Code**:
```php
// Calculate waste cost
$cost = $quantity * $cost_per_unit;
Waste::add([
    'waste_date' => $date,
    'waste_type' => $type,
    'quantity' => $qty,
    'cost' => $cost
]);
```

---

### 9️⃣ IngredientsController.php
**Purpose**: Manage ingredients

**Methods**:
- `index()` - List ingredients
- `create()` - Add ingredient
- `edit()` - Update ingredient
- `delete()` - Remove ingredient

---

### 🔟 InventoryController.php
**Purpose**: Track ingredient inventory

**Methods**:
- `index()` - Display inventory levels
- `add()` - Record inventory transaction (IN/OUT/ADJUST)

---

### 1️⃣1️⃣ MenuController.php
**Purpose**: Manage menu items

**Methods**:
- `index()` - List menu items
- `create()` - Add menu item
- `edit()` - Update menu item
- `delete()` - Remove menu item

---

### 1️⃣2️⃣ RecipesController.php
**Purpose**: Manage recipes (ingredient portions)

**Methods**:
- `index()` - Display recipes
- `edit()` - Update recipe ingredients

---

### 1️⃣3️⃣ ReportsController.php
**Purpose**: Analytics and forecasting

**Methods**:
- `index()` - Sales & waste reports
- `forecast()` - Demand forecasting
- `recommendations()` - AI recommendations

---

### 1️⃣4️⃣ SubscribersController.php
**Purpose**: Manage email subscribers

**Methods**:
- `index()` - List subscribers
- `delete()` - Remove subscriber

---

## 🔄 Controller Workflow

```
1. User Request → Router
2. Router → Calls Controller Method
3. Controller → Validates Input
4. Controller → Calls Model/DB
5. Model/DB → Returns Data
6. Controller → Processes Data
7. Controller → Renders View
8. View → Displays to User
```

---

## 📊 Controller Summary Table

| Controller | Purpose | Key Methods | URL |
|------------|---------|-------------|-----|
| **AuthController** | Login/Register | login, register, logout | auth/login |
| **DashboardController** | Statistics | index | dashboard/index |
| **FoodItemsController** | Food CRUD | index, create, edit, delete | food_items/index |
| **ProductionController** | Production tracking | index, add, update_sales | production/index |
| **DiscountsController** | AI discounts | index, create, getSuggestions | discounts/index |
| **CustomerController** | Public view | index, subscribe | customer/index |
| **SalesController** | Sales tracking | index, add | sales/index |
| **WasteController** | Waste logging | index, add | waste/index |
| **IngredientsController** | Ingredients | index, create, edit | ingredients/index |
| **InventoryController** | Inventory | index, add | inventory/index |
| **MenuController** | Menu items | index, create, edit | menu/index |
| **RecipesController** | Recipes | index, edit | recipes/index |
| **ReportsController** | Analytics | index, forecast | reports/index |
| **SubscribersController** | Email list | index, delete | subscribers/index |

---

## 🔐 Security in Controllers

### CSRF Protection
```php
if (!CSRF::verify($this->post('csrf'))) {
    http_response_code(403);
    exit;
}
```

### Login Check
```php
Auth::requireLogin();  // Redirect if not logged in
```

### Input Validation
```php
$name = trim((string)$this->post('name'));
$price = (float)$this->post('price', 0);
```

---

## 💡 Common Controller Patterns

### Display Page
```php
public function index() {
    Auth::requireLogin();
    $data = DB::query("SELECT * FROM table");
    $this->render('view/index', ['data' => $data]);
}
```

### Create Record
```php
public function create() {
    if ($_POST) {
        DB::query("INSERT INTO table (name) VALUES (?)", [$name]);
        $this->redirect('controller/index');
    }
    $this->render('view/form');
}
```

### Update Record
```php
public function edit() {
    $id = (int)$_GET['id'];
    if ($_POST) {
        DB::query("UPDATE table SET name = ? WHERE id = ?", [$name, $id]);
        $this->redirect('controller/index');
    }
    $item = DB::query("SELECT * FROM table WHERE id = ?", [$id])[0];
    $this->render('view/form', ['item' => $item]);
}
```

### Delete Record
```php
public function delete() {
    if ($_POST) {
        $id = (int)$_POST['id'];
        DB::query("DELETE FROM table WHERE id = ?", [$id]);
    }
    $this->redirect('controller/index');
}
```

---

## 📝 Summary

**Controllers = Business Logic**
- Receive user requests
- Validate input
- Process data
- Call models/database
- Render views
- Handle redirects

**All controllers extend `Controller` base class for common functionality.**

---

**14 controllers handle all application features from authentication to analytics.**
