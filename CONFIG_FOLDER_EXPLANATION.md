# CONFIG FOLDER EXPLANATION
## app/config/ - Configuration Files

---

## 📁 Files in Config Folder

```
app/config/
├── config.php          # Main configuration (ACTIVE - used by app)
└── config.example.php  # Template file (for setup/distribution)
```

---

## 1️⃣ config.php - Main Configuration File

**Purpose**: Stores all application settings (database, security, app info)

**⚠️ IMPORTANT**: This file contains sensitive data (database password) - never commit to Git!

**Structure**:

```php
<?php
return [
  'app_name' => 'Food Waste & Profit Optimizer',
  'base_url' => '/food-waste-profit-optimizer/public',

  'db' => [
    'host' => '127.0.0.1',        // Database server
    'name' => 'food_waste_opt',   // Database name
    'user' => 'root',              // Database username
    'pass' => '',                  // Database password
    'charset' => 'utf8mb4'         // Character encoding
  ],

  'security' => [
    'session_name' => 'fwpo_session',      // Session cookie name
    'csrf_key' => 'fwpo_csrf_token'        // CSRF token key
  ]
];
```

---

## 2️⃣ config.example.php - Template File

**Purpose**: Example configuration for new installations

**Usage**:
1. Copy `config.example.php` → `config.php`
2. Edit `config.php` with your database credentials
3. Keep `config.example.php` unchanged (for reference)

**Why separate files?**
- `config.example.php` = Safe to share (no passwords)
- `config.php` = Contains real passwords (keep private)

---

## 📊 Configuration Sections

### App Settings
```php
'app_name' => 'Food Waste & Profit Optimizer'  // Application name
'base_url' => '/Food/public'                    // URL path
```

### Database Settings
```php
'db' => [
    'host' => '127.0.0.1',        // localhost or IP
    'name' => 'food_waste_opt',   // Database name in phpMyAdmin
    'user' => 'root',              // MySQL username
    'pass' => '',                  // MySQL password (empty for XAMPP)
    'charset' => 'utf8mb4'         // UTF-8 encoding
]
```

### Security Settings
```php
'security' => [
    'session_name' => 'fwpo_session',      // Unique session identifier
    'csrf_key' => 'fwpo_csrf_token'        // CSRF protection key
]
```

---

## 🔧 How to Use Config Values

**In any PHP file**:

```php
// Get database settings
$dbConfig = config('db');
$host = config('db.host');
$name = config('db.name');

// Get app name
$appName = config('app_name');

// Get security settings
$sessionName = config('security.session_name');
```

**Example in DB.php**:
```php
$db = config('db');
$dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
$pdo = new PDO($dsn, $db['user'], $db['pass']);
```

---

## 🚀 Setup Process

### Step 1: Copy Template
```bash
Copy: config.example.php → config.php
```

### Step 2: Edit config.php
```php
'db' => [
    'host' => '127.0.0.1',           // Change if needed
    'name' => 'food_waste_opt',      // Your database name
    'user' => 'root',                 // Your MySQL username
    'pass' => 'your_password',        // Your MySQL password
    'charset' => 'utf8mb4'
]
```

### Step 3: Save and Test
- Access: `http://localhost/Food/public/`
- If database error → check config.php settings

---

## 🔐 Security Notes

### ✅ DO:
- Keep `config.php` private
- Use strong database passwords in production
- Add `config.php` to `.gitignore`
- Change default session names

### ❌ DON'T:
- Commit `config.php` to Git
- Share `config.php` publicly
- Use default passwords in production
- Store API keys here (use environment variables)

---

## 📝 Common Configurations

### Local Development (XAMPP)
```php
'db' => [
    'host' => '127.0.0.1',
    'name' => 'food_waste_opt',
    'user' => 'root',
    'pass' => '',              // Empty for XAMPP
    'charset' => 'utf8mb4'
]
```

### Production Server
```php
'db' => [
    'host' => 'localhost',
    'name' => 'production_db',
    'user' => 'db_user',
    'pass' => 'strong_password_here',
    'charset' => 'utf8mb4'
]
```

### Remote Database
```php
'db' => [
    'host' => '192.168.1.100',     // Remote IP
    'name' => 'food_waste_opt',
    'user' => 'remote_user',
    'pass' => 'remote_password',
    'charset' => 'utf8mb4'
]
```

---

## 🐛 Troubleshooting

### Error: "Database connection failed"
**Fix**: Check config.php database settings
```php
// Verify:
- Database name exists in phpMyAdmin
- Username is correct (usually 'root')
- Password is correct (usually empty for XAMPP)
- MySQL service is running
```

### Error: "config.php not found"
**Fix**: Copy config.example.php to config.php
```bash
Copy: app/config/config.example.php → app/config/config.php
```

### Error: "Access denied for user"
**Fix**: Check MySQL username/password
```php
'user' => 'root',      // Correct username
'pass' => '',          // Correct password
```

---

## 📊 File Comparison

| Feature | config.php | config.example.php |
|---------|------------|-------------------|
| **Purpose** | Active configuration | Template/Example |
| **Contains** | Real passwords | Placeholder values |
| **Used by** | Application | Developers (setup) |
| **Git** | Ignored (.gitignore) | Committed (safe) |
| **Edit** | Yes (customize) | No (keep as template) |

---

## 💡 Quick Reference

### Access config value:
```php
config('db.host')           // Get database host
config('app_name')          // Get app name
config('security.csrf_key') // Get CSRF key
```

### Update config:
1. Open `app/config/config.php`
2. Edit values
3. Save file
4. Restart server (if needed)

---

## 📝 Summary

**config.php**:
- Main configuration file
- Contains database credentials
- Used by entire application
- Keep private (sensitive data)

**config.example.php**:
- Template for new installations
- Safe to share (no passwords)
- Copy to config.php for setup
- Don't modify (keep as reference)

---

**Both files have identical structure - only config.php is used by the application.**
