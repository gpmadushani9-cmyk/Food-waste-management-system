# CORE FOLDER EXPLANATION
## app/core/ - Framework Core Files

---

## 📁 Files in Core Folder

```
app/core/
├── Auth.php         # User authentication & session management
├── Controller.php   # Base controller class
├── CSRF.php         # Security token protection
├── DB.php           # Database connection & queries
└── Router.php       # URL routing system
```

---

## 1️⃣ Auth.php - Authentication System

**Purpose**: Manages user login, logout, and access control

**Key Methods**:

```php
// Check if user is logged in
Auth::check()  // Returns true/false

// Get current user data
Auth::user()  // Returns user array or null

// Force login (redirect if not logged in)
Auth::requireLogin()  // Redirects to login page

// Login a user
Auth::login($user)  // Stores user in session

// Logout user
Auth::logout()  // Clears session

// Check if user is admin
Auth::isAdmin()  // Returns true/false
```

**Usage Example**:
```php
// In any controller
Auth::requireLogin();  // Must be logged in to access
$currentUser = Auth::user();  // Get logged in user info
```

---

## 2️⃣ Controller.php - Base Controller

**Purpose**: Parent class for all controllers, provides common methods

**Key Methods**:

```php
// Render a view with data
$this->render('dashboard/index', ['data' => $value])

// Redirect to another page
$this->redirect('food_items/index')

// Get URL parameter
$this->get('id', 0)

// Get form data
$this->post('name', '')
```

**Usage Example**:
```php
class DashboardController extends Controller {
    public function index() {
        $data = ['stats' => 100];
        $this->render('dashboard/index', $data);
    }
}
```

---

## 3️⃣ CSRF.php - Security Protection

**Purpose**: Prevents Cross-Site Request Forgery attacks

**Key Methods**:

```php
// Generate security token
CSRF::token()  // Returns random token string

// Verify token from form
CSRF::verify($token)  // Returns true/false
```

**Usage Example**:
```php
// In form (view):
<input type="hidden" name="csrf_token" value="<?= CSRF::token() ?>">

// In controller:
if (!CSRF::verify($_POST['csrf_token'])) {
    die('Invalid request');
}
```

---

## 4️⃣ DB.php - Database Operations

**Purpose**: Handles MySQL database connection and queries

**Key Methods**:

```php
// Get PDO connection
DB::pdo()  // Returns PDO object

// Execute query with parameters
DB::query($sql, $params)  // Returns array of results

// Get last inserted ID
DB::lastInsertId()  // Returns ID
```

**Usage Example**:
```php
// Select query
$users = DB::query("SELECT * FROM users WHERE role = ?", ['admin']);

// Insert query
DB::query("INSERT INTO food_items (name, price) VALUES (?, ?)", ['Pizza', 10.99]);

// Update query
DB::query("UPDATE food_items SET price = ? WHERE id = ?", [12.99, 5]);

// Delete query
DB::query("DELETE FROM food_items WHERE id = ?", [5]);
```

**Security**: Uses PDO prepared statements to prevent SQL injection

---

## 5️⃣ Router.php - URL Routing

**Purpose**: Maps URLs to controller actions

**How It Works**:

```
URL: index.php?r=food_items/create
         ↓
Router parses: controller = "food_items", action = "create"
         ↓
Calls: FoodItemsController->create()
```

**Route Map**:
```php
'auth' => AuthController
'dashboard' => DashboardController
'food_items' => FoodItemsController
'production' => ProductionController
'discounts' => DiscountsController
'sales' => SalesController
'waste' => WasteController
'reports' => ReportsController
```

**Usage Example**:
```php
// URL: index.php?r=food_items/edit
// Calls: FoodItemsController->edit()

// URL: index.php?r=dashboard/index
// Calls: DashboardController->index()
```

---

## 📊 How Core Files Work Together

```
1. User visits: index.php?r=food_items/create
                    ↓
2. Router.php → Parses URL → Finds FoodItemsController
                    ↓
3. Auth.php → Checks if user is logged in
                    ↓
4. FoodItemsController → Extends Controller.php
                    ↓
5. Controller calls DB.php → Executes database query
                    ↓
6. CSRF.php → Validates form security token
                    ↓
7. Controller.php → Renders view with data
```

---

## 🔐 Security Features

| File | Security Feature |
|------|------------------|
| **Auth.php** | Session management, access control |
| **CSRF.php** | Form token validation |
| **DB.php** | SQL injection prevention (prepared statements) |
| **Router.php** | Route validation |
| **Controller.php** | XSS prevention (htmlspecialchars in views) |

---

## 💡 Quick Reference

### Need to check login?
```php
Auth::requireLogin();
```

### Need to query database?
```php
DB::query("SELECT * FROM table WHERE id = ?", [$id]);
```

### Need to render a page?
```php
$this->render('view/name', ['data' => $value]);
```

### Need to protect form?
```php
<input type="hidden" name="csrf_token" value="<?= CSRF::token() ?>">
```

### Need to redirect?
```php
$this->redirect('controller/action');
```

---

## 📝 Summary

| File | Purpose | Main Function |
|------|---------|---------------|
| **Auth.php** | Authentication | Login/logout, check user |
| **Controller.php** | Base class | Render views, redirect |
| **CSRF.php** | Security | Token generation/validation |
| **DB.php** | Database | Execute queries safely |
| **Router.php** | Routing | Map URLs to controllers |

---

**All core files work together to provide a secure, organized MVC framework for the application.**
