# MODELS FOLDER EXPLANATION
## app/models/ - Data Access Layer

---

## 📁 Files in Models Folder (7 Files)

```
app/models/
├── User.php         # User authentication data
├── Ingredient.php   # Ingredients CRUD
├── MenuItem.php     # Menu items CRUD
├── Recipe.php       # Recipe ingredients & costs
├── Inventory.php    # Inventory transactions & stock
├── Sale.php         # Sales data & analytics
└── Waste.php        # Waste logs & analytics
```

---

## 🎯 What are Models?

**Models = Database Operations**
- Interact with database tables
- Execute SQL queries
- Return data to controllers
- No HTML, no business logic (just data)

---

## 1️⃣ User.php
**Purpose**: User authentication data operations

**Database Table**: `users`

**Methods**:
```php
User::countAll()                    // Count total users
User::findByEmail($email)           // Find user by email
User::create($name, $email, $pass, $role)  // Create new user
```

**Usage Example**:
```php
// Check if user exists
$user = User::findByEmail('admin@example.com');

// Create first admin
if (User::countAll() === 0) {
    User::create('Admin', 'admin@example.com', 'password', 'admin');
}
```

---

## 2️⃣ Ingredient.php
**Purpose**: Ingredients CRUD operations

**Database Table**: `ingredients`

**Methods**:
```php
Ingredient::all()                   // Get all ingredients
Ingredient::find($id)               // Get one ingredient
Ingredient::create($data)           // Add new ingredient
Ingredient::update($id, $data)      // Update ingredient
Ingredient::delete($id)             // Delete ingredient
```

**Usage Example**:
```php
// Get all ingredients
$ingredients = Ingredient::all();

// Create ingredient
Ingredient::create([
    'name' => 'Chicken',
    'unit' => 'kg',
    'cost_per_unit' => 5.50,
    'reorder_level' => 10
]);

// Update ingredient
Ingredient::update(1, [
    'name' => 'Chicken Breast',
    'cost_per_unit' => 6.00
]);
```

---

## 3️⃣ MenuItem.php
**Purpose**: Menu items CRUD operations

**Database Table**: `food_items`

**Methods**:
```php
MenuItem::all()                     // Get all menu items
MenuItem::find($id)                 // Get one menu item
MenuItem::create($data)             // Add new menu item
MenuItem::update($id, $data)        // Update menu item
MenuItem::delete($id)               // Delete menu item
```

**Usage Example**:
```php
// Get all menu items
$menu = MenuItem::all();

// Create menu item
MenuItem::create([
    'name' => 'Chicken Pizza',
    'price' => 12.99,
    'category' => 'Pizza',
    'cost_price' => 5.00
]);
```

---

## 4️⃣ Recipe.php
**Purpose**: Recipe ingredients and cost calculations

**Database Table**: `recipes`

**Methods**:
```php
Recipe::rowsForMenuItem($menuId)              // Get recipe ingredients
Recipe::upsert($menuId, $ingredientId, $qty)  // Add/update ingredient
Recipe::delete($menuId, $ingredientId)        // Remove ingredient
Recipe::recipeCostForMenuItem($menuId)        // Calculate recipe cost
```

**Usage Example**:
```php
// Get recipe for Pizza (menu_item_id = 1)
$ingredients = Recipe::rowsForMenuItem(1);

// Add ingredient to recipe (1 Pizza needs 0.5kg Chicken)
Recipe::upsert(1, 5, 0.5);

// Calculate total cost
$cost = Recipe::recipeCostForMenuItem(1);
// Returns: 0.5kg × $5.50 = $2.75
```

**Cost Calculation**:
```php
// Formula: SUM(quantity × cost_per_unit)
// Example: Pizza recipe
// - 0.5kg Chicken @ $5.50 = $2.75
// - 0.2kg Cheese @ $8.00 = $1.60
// Total Cost = $4.35
```

---

## 5️⃣ Inventory.php
**Purpose**: Inventory transactions and stock levels

**Database Table**: `inventory_transactions`

**Methods**:
```php
Inventory::addTxn($data)            // Add transaction (IN/OUT/ADJUST)
Inventory::recent($limit)           // Get recent transactions
Inventory::stockByIngredient()      // Get stock for all ingredients
Inventory::stockFor($ingredientId)  // Get stock for one ingredient
```

**Transaction Types**:
- `IN` = Stock received (add)
- `OUT` = Stock used (subtract)
- `ADJUST` = Manual adjustment

**Usage Example**:
```php
// Receive stock
Inventory::addTxn([
    'ingredient_id' => 1,
    'txn_type' => 'IN',
    'quantity' => 50,
    'note' => 'Weekly delivery',
    'txn_date' => '2024-01-15'
]);

// Use stock
Inventory::addTxn([
    'ingredient_id' => 1,
    'txn_type' => 'OUT',
    'quantity' => 10,
    'note' => 'Used for production',
    'txn_date' => '2024-01-15'
]);

// Get current stock
$stock = Inventory::stockFor(1);  // Returns: 40
```

**Stock Calculation**:
```php
// Formula: SUM(IN) - SUM(OUT) + SUM(ADJUST)
// Example:
// IN: +50, +30 = 80
// OUT: -10, -5 = -15
// Stock = 80 - 15 = 65
```

---

## 6️⃣ Sale.php
**Purpose**: Sales data and analytics

**Database Table**: `sales`

**Methods**:
```php
Sale::add($data)                        // Record sale
Sale::recent($limit)                    // Get recent sales
Sale::totalsByDate($from, $to)          // Sales by date
Sale::totalsByMenuItem($from, $to)      // Sales by menu item
Sale::forecastMenuItem($menuId, $days)  // Forecast demand
```

**Usage Example**:
```php
// Record sale
Sale::add([
    'sale_date' => '2024-01-15',
    'menu_item_id' => 1,
    'quantity' => 5,
    'revenue' => 64.95  // 5 × $12.99
]);

// Get sales for last 7 days
$sales = Sale::totalsByDate('2024-01-08', '2024-01-15');

// Forecast demand (moving average)
$forecast = Sale::forecastMenuItem(1, 7);
// Returns: Average daily sales for last 7 days
```

**Forecast Algorithm**:
```php
// Moving Average: SUM(daily_sales) / number_of_days
// Example: Last 7 days sales = [10, 12, 8, 15, 11, 9, 14]
// Forecast = (10+12+8+15+11+9+14) / 7 = 11.3 units/day
```

---

## 7️⃣ Waste.php
**Purpose**: Waste logs and analytics

**Database Table**: `waste_logs`

**Methods**:
```php
Waste::add($data)                           // Log waste
Waste::recent($limit)                       // Get recent waste logs
Waste::totalsByIngredient($from, $to)       // Waste by ingredient
Waste::totalsByMenuItem($from, $to)         // Waste by menu item
Waste::totals($from, $to)                   // Total waste cost & qty
```

**Usage Example**:
```php
// Log ingredient waste
Waste::add([
    'waste_date' => '2024-01-15',
    'ingredient_id' => 1,
    'menu_item_id' => null,
    'waste_type' => 'spoilage',
    'quantity' => 2,
    'cost' => 11.00,  // 2kg × $5.50
    'note' => 'Expired chicken'
]);

// Log prepared food waste
Waste::add([
    'waste_date' => '2024-01-15',
    'ingredient_id' => null,
    'menu_item_id' => 1,
    'waste_type' => 'overproduction',
    'quantity' => 3,
    'cost' => 13.05,  // 3 pizzas × $4.35 cost
    'note' => 'Unsold pizzas'
]);

// Get total waste for period
$totals = Waste::totals('2024-01-01', '2024-01-31');
// Returns: ['waste_cost' => 150.50, 'waste_qty' => 45]
```

---

## 📊 Model Summary Table

| Model | Table | Purpose | Key Methods |
|-------|-------|---------|-------------|
| **User** | users | Authentication | findByEmail, create, countAll |
| **Ingredient** | ingredients | Ingredients CRUD | all, find, create, update, delete |
| **MenuItem** | food_items | Menu items CRUD | all, find, create, update, delete |
| **Recipe** | recipes | Recipe ingredients | rowsForMenuItem, recipeCostForMenuItem |
| **Inventory** | inventory_transactions | Stock tracking | addTxn, stockByIngredient, stockFor |
| **Sale** | sales | Sales tracking | add, totalsByDate, forecastMenuItem |
| **Waste** | waste_logs | Waste tracking | add, totals, totalsByIngredient |

---

## 🔄 How Models Work

```
Controller → Calls Model Method
              ↓
Model → Executes SQL Query
              ↓
Database → Returns Data
              ↓
Model → Returns Data to Controller
              ↓
Controller → Passes Data to View
```

---

## 💡 Common Model Patterns

### Get All Records
```php
public static function all(): array {
    return DB::pdo()->query("SELECT * FROM table ORDER BY name")->fetchAll();
}
```

### Get One Record
```php
public static function find(int $id): ?array {
    $stmt = DB::pdo()->prepare("SELECT * FROM table WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}
```

### Create Record
```php
public static function create(array $data): void {
    $stmt = DB::pdo()->prepare("INSERT INTO table (name, price) VALUES (?, ?)");
    $stmt->execute([$data['name'], $data['price']]);
}
```

### Update Record
```php
public static function update(int $id, array $data): void {
    $stmt = DB::pdo()->prepare("UPDATE table SET name=?, price=? WHERE id=?");
    $stmt->execute([$data['name'], $data['price'], $id]);
}
```

### Delete Record
```php
public static function delete(int $id): void {
    $stmt = DB::pdo()->prepare("DELETE FROM table WHERE id=?");
    $stmt->execute([$id]);
}
```

### Aggregate Data
```php
public static function totals(string $from, string $to): array {
    $stmt = DB::pdo()->prepare("
        SELECT SUM(amount) as total, COUNT(*) as count 
        FROM table 
        WHERE date BETWEEN ? AND ?
    ");
    $stmt->execute([$from, $to]);
    return $stmt->fetch();
}
```

---

## 🔐 Security Features

### Prepared Statements (SQL Injection Prevention)
```php
// ✅ SAFE - Uses prepared statements
$stmt = DB::pdo()->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);

// ❌ UNSAFE - Direct string concatenation
$query = "SELECT * FROM users WHERE email='$email'";  // DON'T DO THIS!
```

### Type Casting
```php
// Ensure correct data types
(int)$data['id']           // Force integer
(float)$data['price']      // Force decimal
trim($data['name'])        // Remove whitespace
```

---

## 📊 Data Relationships

```
User (1) ──────────────────────────────────> (Many) Sales
                                              (Many) Waste Logs

Ingredient (1) ────> (Many) Recipes <──── (1) MenuItem
           │                                      │
           │                                      │
           └──> (Many) Inventory Transactions    │
           └──> (Many) Waste Logs                │
                                                  │
                                                  └──> (Many) Sales
                                                  └──> (Many) Waste Logs
```

---

## 💡 Usage Examples in Controllers

### Get and Display Data
```php
// In DashboardController
$ingredients = Ingredient::all();
$stock = Inventory::stockByIngredient();
$this->render('dashboard/index', compact('ingredients', 'stock'));
```

### Create Record
```php
// In IngredientsController
if ($_POST) {
    Ingredient::create([
        'name' => $_POST['name'],
        'unit' => $_POST['unit'],
        'cost_per_unit' => $_POST['cost'],
        'reorder_level' => $_POST['reorder']
    ]);
    $this->redirect('ingredients/index');
}
```

### Calculate Analytics
```php
// In ReportsController
$salesData = Sale::totalsByDate('2024-01-01', '2024-01-31');
$wasteData = Waste::totals('2024-01-01', '2024-01-31');
$forecast = Sale::forecastMenuItem(1, 7);
```

---

## 📝 Summary

**Models = Data Layer**
- Execute database queries
- Return data to controllers
- Handle CRUD operations
- Calculate analytics
- Use prepared statements (security)
- No HTML, no business logic

**Each model represents one database table and its operations.**

---

**7 models handle all database operations for the entire application.**
