# PUBLIC FOLDER EXPLANATION
## public/ - Web Root Directory

---

## 📁 Files in Public Folder

```
public/
├── index.php              # Main entry point (admin panel)
├── customer.php           # Customer view (public page)
└── assets/
    ├── css/
    │   └── styles.css     # All CSS styling
    ├── js/
    │   ├── app.js         # Main JavaScript
    │   └── simpleChart.js # Chart rendering
    └── .keep              # Keep folder in Git
```

---

## 🚪 ENTRY POINTS

### 1️⃣ index.php
**Purpose**: Main application entry point (admin panel)

**Location**: `public/index.php`

**Code**:
```php
<?php
require __DIR__ . '/../app/bootstrap.php';

$router = new Router();
$router->dispatch();
```

**What it does**:
1. Loads application bootstrap
2. Creates router instance
3. Routes request to appropriate controller

**URL**: `http://localhost/Food/public/index.php`

**Examples**:
- `index.php?r=dashboard/index` → DashboardController->index()
- `index.php?r=food_items/create` → FoodItemsController->create()
- `index.php?r=auth/login` → AuthController->login()

---

### 2️⃣ customer.php
**Purpose**: Public customer view (no login required)

**Location**: `public/customer.php`

**What it does**:
1. Displays active discounts
2. Shows all menu items
3. Email subscription
4. Auto-refresh every 30 seconds
5. Notification system

**Key Features**:
```php
// Get active discounts
$discounts = DB::query("
    SELECT d.*, fi.name, fi.selling_price 
    FROM discounts d 
    JOIN food_items fi ON d.food_item_id = fi.id 
    WHERE d.status = 'active' 
    AND d.start_time <= ? 
    AND d.end_time >= ?", 
    [$current_time, $current_time]);

// Display discounts
foreach ($discounts as $discount) {
    echo "30% OFF - Chicken Pizza - $9.09";
}

// Auto-refresh
<script>
setInterval(() => location.reload(), 30000);
</script>
```

**URL**: `http://localhost/Food/public/customer.php`

**No routing needed** - Direct access

---

## 🎨 ASSETS FOLDER

### 3️⃣ assets/css/styles.css
**Purpose**: All application styling

**Location**: `public/assets/css/styles.css`

**Contains**:
- **Layout styles**: Sidebar, header, footer
- **Component styles**: Buttons, cards, forms, tables
- **Utility classes**: Spacing, colors, typography
- **Responsive design**: Mobile, tablet, desktop
- **Animations**: Fade-in, slide, transitions
- **Theme colors**: Primary, success, danger, warning

**Key CSS Classes**:
```css
/* Layout */
.sidebar { /* Sidebar navigation */ }
.main-content { /* Main content area */ }
.container { /* Content wrapper */ }

/* Components */
.btn { /* Button base */ }
.btn--primary { /* Primary button */ }
.btn--outline { /* Outline button */ }
.card { /* Card container */ }
.table { /* Table styling */ }
.form__group { /* Form field group */ }

/* Status Pills */
.pill { /* Badge base */ }
.pill--success { /* Green badge */ }
.pill--danger { /* Red badge */ }
.pill--warning { /* Yellow badge */ }

/* Alerts */
.alert { /* Alert base */ }
.alert--success { /* Success message */ }
.alert--danger { /* Error message */ }

/* Grid */
.grid { /* Grid container */ }
.grid--2 { /* 2 columns */ }
.grid--3 { /* 3 columns */ }
.grid--4 { /* 4 columns */ }

/* Utilities */
.text-center { /* Center text */ }
.text-muted { /* Gray text */ }
.mb-lg { /* Margin bottom large */ }
.flex { /* Flexbox */ }
.flex--between { /* Space between */ }
```

**Responsive Breakpoints**:
```css
/* Mobile: < 768px */
/* Tablet: 768px - 1024px */
/* Desktop: > 1024px */
```

---

### 4️⃣ assets/js/app.js
**Purpose**: Main JavaScript functionality

**Location**: `public/assets/js/app.js`

**Features**:

#### Chart Rendering
```javascript
// Render sales chart
const sales = document.getElementById('salesChart');
renderBarChart(sales, labels, values, { 
    title: 'Sales Revenue Trend',
    color: '#6366f1'
});
```

#### Form Enhancement
```javascript
// Add loading state to forms
form.addEventListener('submit', function() {
    submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
    submitBtn.disabled = true;
});

// Real-time validation
input.addEventListener('blur', function() {
    if (this.value.trim() === '') {
        this.style.borderColor = 'red';
    } else {
        this.style.borderColor = 'green';
    }
});
```

#### Table Enhancement
```javascript
// Hover effects on table rows
row.addEventListener('mouseenter', function() {
    this.style.backgroundColor = '#f9f9f9';
    this.style.transform = 'scale(1.01)';
});
```

#### Search Functionality
```javascript
// Filter table rows
input.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
```

#### Notifications
```javascript
// Show notification
showNotification('Success!', 'success');
showNotification('Error occurred', 'danger');
```

#### Auto-save
```javascript
// Save form data to localStorage
input.addEventListener('input', function() {
    localStorage.setItem(`form_${this.name}`, this.value);
});

// Load saved data
const savedValue = localStorage.getItem(`form_${input.name}`);
if (savedValue) input.value = savedValue;
```

#### Keyboard Shortcuts
```javascript
// Ctrl/Cmd + K for search
if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    searchInput.focus();
}

// Escape to close alerts
if (e.key === 'Escape') {
    alerts.forEach(alert => alert.remove());
}
```

---

### 5️⃣ assets/js/simpleChart.js
**Purpose**: Lightweight chart rendering (no dependencies)

**Location**: `public/assets/js/simpleChart.js`

**Function**:
```javascript
renderBarChart(canvas, labels, values, options)
```

**Parameters**:
- `canvas` - Canvas element
- `labels` - Array of x-axis labels
- `values` - Array of y-axis values
- `options` - Chart configuration

**Usage Example**:
```javascript
// HTML
<canvas id="salesChart" 
        data-labels='["Mon", "Tue", "Wed"]' 
        data-values='[100, 150, 120]'></canvas>

// JavaScript
const canvas = document.getElementById('salesChart');
const labels = JSON.parse(canvas.dataset.labels);
const values = JSON.parse(canvas.dataset.values);

renderBarChart(canvas, labels, values, {
    title: 'Sales Chart',
    color: '#6366f1',
    width: 600,
    height: 300
});
```

**Features**:
- Bar charts
- Axis labels
- Grid lines
- Y-axis ticks
- Rotated x-axis labels
- Custom colors
- Responsive sizing

**Chart Elements**:
```javascript
// Draw axis
ctx.moveTo(padding, padding);
ctx.lineTo(padding, baseY);
ctx.lineTo(w - padding, baseY);

// Draw bars
ctx.fillRect(x, y, barWidth, barHeight);

// Draw labels
ctx.fillText(label, x, y);

// Draw grid lines
ctx.moveTo(padding, y);
ctx.lineTo(w - padding, y);
```

---

## 🔄 How Public Folder Works

```
1. Browser requests: http://localhost/Food/public/index.php?r=dashboard/index
                              ↓
2. index.php loads bootstrap.php
                              ↓
3. Router parses URL → dashboard/index
                              ↓
4. Router calls DashboardController->index()
                              ↓
5. Controller renders view with data
                              ↓
6. View includes CSS (styles.css) and JS (app.js)
                              ↓
7. Browser displays page with styling and interactivity
```

---

## 📊 File Purpose Summary

| File | Type | Purpose | Used By |
|------|------|---------|---------|
| **index.php** | PHP | Admin entry point | All admin pages |
| **customer.php** | PHP | Public customer view | Customers (no login) |
| **styles.css** | CSS | All styling | All pages |
| **app.js** | JavaScript | Interactivity | All pages |
| **simpleChart.js** | JavaScript | Chart rendering | Dashboard, Reports |

---

## 🌐 URL Structure

### Admin Panel
```
http://localhost/Food/public/index.php?r=controller/action

Examples:
- index.php?r=dashboard/index
- index.php?r=food_items/create
- index.php?r=production/index
- index.php?r=discounts/index
```

### Customer View
```
http://localhost/Food/public/customer.php

No routing - direct access
```

---

## 🔒 Security Notes

### index.php
- ✅ Requires login (via Auth::requireLogin())
- ✅ CSRF protection on forms
- ✅ Session-based authentication

### customer.php
- ✅ No login required (public)
- ✅ Read-only (no data modification)
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS prevention (htmlspecialchars)

### Assets
- ✅ Static files (CSS/JS)
- ✅ No server-side execution
- ✅ Cached by browser

---

## 📱 Responsive Design

### Mobile (< 768px)
- Sidebar collapses to hamburger menu
- Tables scroll horizontally
- Cards stack vertically
- Touch-friendly buttons

### Tablet (768px - 1024px)
- Sidebar visible
- 2-column grid
- Optimized spacing

### Desktop (> 1024px)
- Full sidebar
- 3-4 column grid
- Maximum content width

---

## 🎨 CSS Variables

```css
:root {
  /* Colors */
  --primary: #6366f1;
  --success: #10b981;
  --danger: #ef4444;
  --warning: #f59e0b;
  
  /* Text */
  --text-primary: #111827;
  --text-secondary: #6b7280;
  --text-muted: #9ca3af;
  
  /* Background */
  --bg-primary: #ffffff;
  --bg-secondary: #f9fafb;
  
  /* Border */
  --border-light: #e5e7eb;
  
  /* Spacing */
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 2rem;
}
```

---

## 💡 JavaScript Features

### Automatic Features
- ✅ Form loading states
- ✅ Table hover effects
- ✅ Search filtering
- ✅ Smooth scrolling
- ✅ Auto-save forms
- ✅ Keyboard shortcuts
- ✅ Notifications
- ✅ Chart rendering

### User Experience
- ✅ Real-time validation
- ✅ Loading indicators
- ✅ Error messages
- ✅ Success notifications
- ✅ Smooth animations
- ✅ Responsive interactions

---

## 📝 Summary

**public/ = Web Root**
- **index.php** - Admin panel entry (routing)
- **customer.php** - Public view (no routing)
- **styles.css** - All styling
- **app.js** - Interactivity & features
- **simpleChart.js** - Chart rendering

**All web requests start here. Assets (CSS/JS) enhance the user experience.**

---

**5 files in public folder handle all web access and user interface.**
