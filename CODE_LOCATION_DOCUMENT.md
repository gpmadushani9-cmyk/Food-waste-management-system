# CODE LOCATION DOCUMENT
## Food Waste & Profit Optimization System

**Student Assignment Submission Guide**  
**Version**: 1.0.0  
**Date**: <?= date('Y-m-d') ?>

---

## 📁 PROJECT STRUCTURE OVERVIEW

```
Food/
├── public/              # FRONTEND (Entry Points)
├── app/
│   ├── controllers/     # BACKEND (Business Logic)
│   ├── models/          # BACKEND (Data Layer)
│   ├── views/           # FRONTEND (UI Templates)
│   └── core/            # BACKEND (Framework Core)
└── database/            # DATABASE (Schema & Data)
```

---

## 1️⃣ FRONTEND COMPONENTS

### 1.1 Main Entry Point (Admin Panel)
**Location**: `public/index.php`  
**Purpose**: Main application entry point, routes all admin requests  
**Code**:
```php
<?php
require __DIR__ . '/../app/bootstrap.php';

$router = new Router();
$router->dispatch();
```

---

### 1.2 Customer View (Public Page)
**Location**: `public/customer.php`  
**Purpose**: Public-facing page showing discounts (no login required)  
**Key Features**:
- Display active discounts
- Show all menu items
- Email subscription
- Auto-refresh every 30 seconds
- Notification system

**Code Snippet**:
```php
// Get active discounts
$discounts = DB::query("
    SELECT d.*, fi.name, fi.selling_price, fi.category
    FROM discounts d 
    JOIN food_items fi ON d.food_item_id = fi.id 
    WHERE d.discount_date = ? 
    AND d.status = 'active' 
    AND d.start_time <= ? 
    AND d.end_time >= ?
    ORDER BY d.discount_percentage DESC", 
    [$today, $current_time, $current_time]);
```

---

### 1.3 CSS Styling
**Location**: `public/assets/css/styles.css`  
**Purpose**: All visual styling for the application  
**Contains**:
- Layout styles
- Component styles
- Responsive design
- Color scheme
- Animations

---

### 1.4 JavaScript
**Location**: `public/assets/js/app.js`  
**Purpose**: Client-side interactivity  
**Features**:
- Form validation
- AJAX requests
- Dynamic updates
- Chart rendering

---

### 1.5 View Templates (Frontend UI)

#### Dashboard View
**Location**: `app/views/dashboard/index.php`  
**Purpose**: Admin dashboard with statistics  
**Displays**:
- Today's production stats
- Active discounts count
- Waste percentage
- Sales charts

#### Food Items List
**Location**: `app/views/food_items/list.php`  
**Purpose**: Display all food items with edit/delete options

#### Food Items Form
**Location**: `app/views/food_items/form.php`  
**Purpose**: Add/Edit food items form

#### Production View
**Location**: `app/views/production/index.php`  
**Purpose**: Track daily production and sales

#### Discounts View
**Location**: `app/views/discounts/index.php`  
**Purpose**: Manage discounts and view AI suggestions

#### Login/Register Views
**Location**: `app/views/auth/login.php` & `app/views/auth/register.php`  
**Purpose**: User authentication forms

---

## 2️⃣ BACKEND COMPONENTS

### 2.1 Controllers (Business Logic)

#### Dashboard Controller
**Location**: `app/controllers/DashboardController.php`  
**Purpose**: Handle dashboard data and statistics  
**Key Code**:
```php
public function index(): void {
    Auth::requireLogin();
    
    $today = date('Y-m-d');
    
    // Today's production stats
    $todayProduction = DB::query("
      SELECT SUM(quantity_produced) as total_produced, 
             SUM(quantity_sold) as total_sold, 
             SUM(quantity_leftover) as total_leftover
      FROM daily_production WHERE production_date = ?", [$today])[0];
    
    // Active discounts count
    $activeDiscounts = DB::query("
      SELECT COUNT(*) as count FROM discounts 
      WHERE discount_date = ? AND status = 'active'", [$today])[0]['count'];
    
    // Waste percentage
    $wastePercentage = $todayProduction['total_produced'] > 0 
      ? ($todayProduction['total_leftover'] / $todayProduction['total_produced']) * 100 
      : 0;
    
    $this->render('dashboard/index', [
      'todayProduction' => $todayProduction,
      'activeDiscounts' => $activeDiscounts,
      'wastePercentage' => $wastePercentage,
    ]);
}
```

---

#### Food Items Controller
**Location**: `app/controllers/FoodItemsController.php`  
**Purpose**: CRUD operations for food items  
**Methods**:
- `index()` - List all food items
- `create()` - Add new food item
- `edit()` - Update food item
- `delete()` - Remove food item

**Key Code**:
```php
public function create() {
    if ($_POST) {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $cost_price = (float)($_POST['cost_price'] ?? 0);
        $selling_price = (float)($_POST['selling_price'] ?? 0);
        
        if ($name && $category) {
            DB::query("INSERT INTO food_items (name, category, cost_price, selling_price) VALUES (?, ?, ?, ?)", 
                [$name, $category, $cost_price, $selling_price]);
            header('Location: index.php?r=food_items/index');
            exit;
        }
    }
    $this->render('food_items/form', compact('mode', 'item', 'error'));
}
```

---

#### Production Controller
**Location**: `app/controllers/ProductionController.php`  
**Purpose**: Track daily production and sales  
**Methods**:
- `index()` - Display production for a date
- `add()` - Record production quantity
- `update_sales()` - Update sold quantity and calculate leftovers

**Key Code**:
```php
public function update_sales() {
    if ($_POST) {
        $id = (int)($_POST['id'] ?? 0);
        $quantity_sold = (int)($_POST['quantity_sold'] ?? 0);
        
        // Calculate leftover automatically
        $production = DB::query("SELECT quantity_produced FROM daily_production WHERE id = ?", [$id])[0];
        if ($production) {
            $leftover = max(0, $production['quantity_produced'] - $quantity_sold);
            DB::query("UPDATE daily_production SET quantity_sold = ?, quantity_leftover = ? WHERE id = ?", 
                [$quantity_sold, $leftover, $id]);
        }
    }
    header('Location: index.php?r=production/index');
}
```

---

#### Discounts Controller
**Location**: `app/controllers/DiscountsController.php`  
**Purpose**: AI discount suggestions and discount management  
**Methods**:
- `index()` - Display discounts and suggestions
- `getSuggestions()` - AI algorithm for discount recommendations
- `create()` - Create new discount
- `activate()` / `cancel()` - Manage discount status
- `sendDiscountEmails()` - Email notifications to subscribers

**Key Code (AI Algorithm)**:
```php
private function getSuggestions() {
    $today = date('Y-m-d');
    $current_time = date('H:i:s');
    
    // Get today's leftovers
    $leftovers = DB::query("
        SELECT dp.*, fi.name, fi.selling_price, fi.cost_price
        FROM daily_production dp 
        JOIN food_items fi ON dp.food_item_id = fi.id 
        WHERE dp.production_date = ? AND dp.quantity_leftover > 0", [$today]);
    
    $suggestions = [];
    foreach ($leftovers as $item) {
        $leftover_qty = $item['quantity_leftover'];
        $leftover_percentage = ($leftover_qty / $item['quantity_produced']) * 100;
        
        // AI Logic: Suggest discount based on time and leftover percentage
        $discount = 0;
        if ($current_time >= '18:00:00') {
            $discount = $leftover_percentage > 50 ? 40 : 30;
        } elseif ($current_time >= '15:00:00') {
            $discount = $leftover_percentage > 30 ? 25 : 15;
        } elseif ($leftover_percentage > 70) {
            $discount = 20;
        }
        
        if ($discount > 0) {
            $suggestions[] = [
                'food_item_id' => $item['food_item_id'],
                'food_name' => $item['name'],
                'leftover_qty' => $leftover_qty,
                'suggested_discount' => $discount,
                'original_price' => $item['selling_price'],
                'discounted_price' => $item['selling_price'] * (1 - $discount/100)
            ];
        }
    }
    
    return $suggestions;
}
```

---

#### Auth Controller
**Location**: `app/controllers/AuthController.php`  
**Purpose**: User authentication (login/register/logout)

---

### 2.2 Models (Data Layer)

#### User Model
**Location**: `app/models/User.php`  
**Purpose**: User data operations  
**Code**:
```php
final class User {
  public static function countAll(): int {
    $stmt = DB::pdo()->query("SELECT COUNT(*) AS c FROM users");
    return (int)$stmt->fetch()['c'];
  }

  public static function findByEmail(string $email): ?array {
    $stmt = DB::pdo()->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function create(string $name, string $email, string $password, string $role): int {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = DB::pdo()->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hash, $role]);
    return (int)DB::pdo()->lastInsertId();
  }
}
```

#### Other Models
- **Location**: `app/models/MenuItem.php` - Menu items operations
- **Location**: `app/models/Ingredient.php` - Ingredients operations
- **Location**: `app/models/Sale.php` - Sales data operations
- **Location**: `app/models/Waste.php` - Waste tracking operations

---

### 2.3 Core Framework

#### Database Class
**Location**: `app/core/DB.php`  
**Purpose**: Database connection and query execution  
**Code**:
```php
final class DB {
  private static ?PDO $pdo = null;

  public static function pdo(): PDO {
    if (self::$pdo) return self::$pdo;
    $db = config('db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $db['host'], $db['name'], $db['charset']);
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];
    self::$pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
    return self::$pdo;
  }

  public static function query(string $sql, array $params = []): array {
    $stmt = self::pdo()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }
}
```

#### Router Class
**Location**: `app/core/Router.php`  
**Purpose**: Route HTTP requests to appropriate controllers

#### Auth Class
**Location**: `app/core/Auth.php`  
**Purpose**: Authentication and session management

#### CSRF Class
**Location**: `app/core/CSRF.php`  
**Purpose**: CSRF token generation and validation

#### Controller Base Class
**Location**: `app/core/Controller.php`  
**Purpose**: Base controller with render method

---

### 2.4 Configuration

#### Config File
**Location**: `app/config/config.php`  
**Purpose**: Database credentials and app settings  
**Structure**:
```php
return [
  'db' => [
    'host' => 'localhost',
    'name' => 'food_waste_opt',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
  ]
];
```

---

## 3️⃣ DATABASE COMPONENTS

### 3.1 Database Schema
**Location**: `database/schema.sql`  
**Purpose**: Complete database structure

#### Key Tables:

**users** - Admin/staff accounts
```sql
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**food_items** - Restaurant products
```sql
CREATE TABLE IF NOT EXISTS food_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(140) NOT NULL,
  category VARCHAR(60) NOT NULL,
  cost_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  selling_price DECIMAL(10,2) NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**daily_production** - Production tracking
```sql
CREATE TABLE IF NOT EXISTS daily_production (
  id INT AUTO_INCREMENT PRIMARY KEY,
  food_item_id INT NOT NULL,
  production_date DATE NOT NULL,
  quantity_produced INT NOT NULL,
  quantity_sold INT NOT NULL DEFAULT 0,
  quantity_leftover INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_production (food_item_id, production_date),
  CONSTRAINT fk_production_food FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**discounts** - Discount management
```sql
CREATE TABLE IF NOT EXISTS discounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  food_item_id INT NOT NULL,
  discount_date DATE NOT NULL,
  discount_percentage INT NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  status ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'pending',
  leftover_quantity INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_discount_food FOREIGN KEY (food_item_id) REFERENCES food_items(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**notifications** - Customer notifications
```sql
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  type ENUM('discount','general') NOT NULL DEFAULT 'discount',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**email_subscribers** - Email subscription list
```sql
CREATE TABLE IF NOT EXISTS email_subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL UNIQUE,
  unsubscribe_token VARCHAR(64) NOT NULL UNIQUE,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 3.2 Sample Data
**Location**: `database/sample_data.sql`  
**Purpose**: Test data for development

---

## 4️⃣ DATA FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────┐
│                    USER INTERACTION                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  FRONTEND (public/index.php or public/customer.php)         │
│  - Receives HTTP request                                     │
│  - Displays UI (HTML/CSS/JS)                                 │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  ROUTER (app/core/Router.php)                               │
│  - Parses URL                                                │
│  - Routes to appropriate controller                          │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  CONTROLLER (app/controllers/*.php)                         │
│  - Validates input                                           │
│  - Applies business logic                                    │
│  - Calls models for data                                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  MODEL (app/models/*.php)                                   │
│  - Executes database queries                                 │
│  - Returns data to controller                                │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  DATABASE (app/core/DB.php)                                 │
│  - PDO connection                                            │
│  - Prepared statements                                       │
│  - Query execution                                           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  MySQL DATABASE (food_waste_opt)                            │
│  - Stores all data                                           │
│  - Returns results                                           │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│  VIEW (app/views/*.php)                                     │
│  - Receives data from controller                             │
│  - Renders HTML with data                                    │
│  - Sends response to browser                                 │
└─────────────────────────────────────────────────────────────┘
```

---

## 5️⃣ KEY FEATURES & CODE LOCATIONS

### Feature 1: Food Items Management
- **Frontend**: `app/views/food_items/list.php`, `app/views/food_items/form.php`
- **Backend**: `app/controllers/FoodItemsController.php`
- **Database**: `food_items` table

### Feature 2: Daily Production Tracking
- **Frontend**: `app/views/production/index.php`
- **Backend**: `app/controllers/ProductionController.php`
- **Database**: `daily_production` table

### Feature 3: Automatic Leftover Calculation
- **Backend**: `app/controllers/ProductionController.php` → `update_sales()` method
- **Logic**: `leftover = quantity_produced - quantity_sold`

### Feature 4: AI Discount Suggestions
- **Backend**: `app/controllers/DiscountsController.php` → `getSuggestions()` method
- **Algorithm**: Time-based + percentage-based discount calculation

### Feature 5: Discount Management
- **Frontend**: `app/views/discounts/index.php`
- **Backend**: `app/controllers/DiscountsController.php`
- **Database**: `discounts` table

### Feature 6: Customer View (Public)
- **Frontend**: `public/customer.php`
- **Features**: No login, auto-refresh, discount display

### Feature 7: Email Notifications
- **Backend**: `app/controllers/DiscountsController.php` → `sendDiscountEmails()` method
- **Database**: `email_subscribers` table

### Feature 8: Dashboard & Analytics
- **Frontend**: `app/views/dashboard/index.php`
- **Backend**: `app/controllers/DashboardController.php`
- **Data**: Aggregated from multiple tables

---

## 6️⃣ SECURITY FEATURES & LOCATIONS

### Password Hashing
- **Location**: `app/models/User.php`
- **Code**: `password_hash($password, PASSWORD_DEFAULT)`

### SQL Injection Prevention
- **Location**: `app/core/DB.php`
- **Method**: PDO prepared statements with parameter binding

### CSRF Protection
- **Location**: `app/core/CSRF.php`
- **Usage**: Token generation and validation in forms

### Session Management
- **Location**: `app/core/Auth.php`
- **Features**: Secure session handling, login checks

### XSS Prevention
- **Location**: All view files
- **Method**: `htmlspecialchars()` for output escaping

---

## 7️⃣ FILE SUMMARY TABLE

| Component | Type | Location | Purpose |
|-----------|------|----------|---------|
| Admin Entry | Frontend | `public/index.php` | Main application entry |
| Customer Page | Frontend | `public/customer.php` | Public discount view |
| Styles | Frontend | `public/assets/css/styles.css` | All CSS styling |
| Scripts | Frontend | `public/assets/js/app.js` | JavaScript functionality |
| Dashboard | Backend | `app/controllers/DashboardController.php` | Statistics & analytics |
| Food Items | Backend | `app/controllers/FoodItemsController.php` | CRUD for food items |
| Production | Backend | `app/controllers/ProductionController.php` | Production tracking |
| Discounts | Backend | `app/controllers/DiscountsController.php` | AI suggestions & discounts |
| Auth | Backend | `app/controllers/AuthController.php` | Login/register |
| User Model | Backend | `app/models/User.php` | User data operations |
| Database | Backend | `app/core/DB.php` | PDO connection & queries |
| Router | Backend | `app/core/Router.php` | URL routing |
| Schema | Database | `database/schema.sql` | Database structure |
| Sample Data | Database | `database/sample_data.sql` | Test data |

---

## 8️⃣ TECHNOLOGY STACK

### Backend
- **Language**: PHP 8+
- **Database**: MySQL / MariaDB
- **Database Access**: PDO (PHP Data Objects)
- **Architecture**: MVC (Model-View-Controller)

### Frontend
- **HTML5**: Semantic markup
- **CSS3**: Modern styling with gradients, flexbox, grid
- **JavaScript**: Vanilla JS (no frameworks)
- **Fonts**: Google Fonts (Inter)

### Security
- **Password**: Bcrypt hashing
- **SQL**: Prepared statements
- **CSRF**: Token validation
- **XSS**: Output escaping

---

## 9️⃣ INSTALLATION SUMMARY

1. **Database**: Import `database/schema.sql` into `food_waste_opt` database
2. **Config**: Copy `app/config/config.example.php` to `config.php` and update credentials
3. **Access**: Navigate to `http://localhost/Food/public/`
4. **Register**: First user becomes admin automatically

---

## 🔟 TESTING LOCATIONS

- **Test Cases**: `TESTING_CHECKLIST.md`
- **Setup Guide**: `SETUP_GUIDE.md`
- **User Manual**: `QUICK_REFERENCE.md`

---

## 📝 ASSIGNMENT SUBMISSION CHECKLIST

✅ **Frontend Code Locations Documented**  
✅ **Backend Code Locations Documented**  
✅ **Database Schema Documented**  
✅ **Data Flow Explained**  
✅ **Key Features Mapped to Files**  
✅ **Security Features Documented**  
✅ **Technology Stack Listed**  
✅ **File Summary Table Provided**

---

**Document Created**: <?= date('Y-m-d H:i:s') ?>  
**Project**: Food Waste & Profit Optimization System  
**Version**: 1.0.0  
**Status**: ✅ Complete & Production Ready
