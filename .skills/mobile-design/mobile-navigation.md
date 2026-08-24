
# Mobile Web Navigation Patterns (PHP/HTML)

## 🎯 The Navigation Challenge

Mobile web navigation must:

- **Be accessible**: Work with touch, keyboard, screen readers
- **Be intuitive**: Follow user expectations
- **Be efficient**: Minimize taps, maximize thumb zone
- **Be responsive**: Adapt to different screen sizes
- **Be performant**: Load quickly, don't block rendering
- **Be consistent**: Same patterns across the app

---

## 📱 Navigation Patterns

### 1. Top Navigation Bar

**Best for**: Simple sites with few navigation items

**HTML:**

```html
<nav class="top-nav" role="navigation" aria-label="Main navigation">
  <ul class="nav-list">
    <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
    <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
    <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
  </ul>
</nav>
```

**CSS:**

```css
.top-nav {
  background: #fff;
  border-bottom: 1px solid #eee;
  padding: 0 16px;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  gap: 8px;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.nav-item {
  flex: 0 0 auto;
}

.nav-link {
  display: block;
  padding: 12px 16px;
  text-decoration: none;
  color: #333;
  white-space: nowrap;
  border-bottom: 2px solid transparent;
  transition: border-color 0.2s;
}

.nav-link:hover,
.nav-link:focus {
  border-bottom-color: #007bff;
  outline: none;
}

.nav-link.active {
  border-bottom-color: #007bff;
  color: #007bff;
  font-weight: 600;
}

/* Mobile - scrollable */
@media (max-width: 767px) {
  .nav-list {
    justify-content: flex-start;
  }
}

/* Desktop - centered */
@media (min-width: 768px) {
  .nav-list {
    justify-content: center;
  }
}
```

**PHP dynamic navigation:**

```php
<?php
function topNavigation($items, $activePath = '') {
  $navItems = '';
  foreach ($items as $url => $label) {
    $isActive = $url === $activePath;
    $activeClass = $isActive ? 'active' : '';
    $navItems .= "<li class='nav-item'><a href='{$url}' class='nav-link {$activeClass}'>{$label}</a></li>\n";
  }
  
  return <<<HTML
<nav class="top-nav" role="navigation" aria-label="Main navigation">
  <ul class="nav-list">
    {$navItems}
  </ul>
</nav>
HTML;
}

// Usage
$navItems = [
  '/' => 'Home',
  '/about' => 'About',
  '/contact' => 'Contact'
];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo topNavigation($navItems, $currentPath);
?>
```

---

### 2. Hamburger Menu (Mobile-First)

**Best for**: Sites with many navigation items

**HTML:**

```html
<nav class="mobile-nav" role="navigation" aria-label="Main navigation">
  <button 
    class="menu-toggle" 
    aria-label="Toggle menu"
    aria-expanded="false"
    aria-controls="main-menu"
  >
    <span class="hamburger"></span>
    <span class="sr-only">Menu</span>
  </button>
  <ul id="main-menu" class="nav-menu">
    <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
    <li class="nav-item"><a href="/about" class="nav-link">About</a></li>
    <li class="nav-item"><a href="/services" class="nav-link">Services</a></li>
    <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
  </ul>
</nav>
```

**CSS:**

```css
.mobile-nav {
  position: relative;
  background: #fff;
  border-bottom: 1px solid #eee;
  padding: 0 16px;
  z-index: 1000;
}

.menu-toggle {
  background: none;
  border: none;
  padding: 12px;
  cursor: pointer;
  min-width: 44px;
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hamburger {
  display: block;
  width: 24px;
  height: 2px;
  background: #333;
  position: relative;
  transition: all 0.2s;
}

.hamburger::before,
.hamburger::after {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  background: #333;
  left: 0;
  transition: all 0.2s;
}

.hamburger::before { top: -8px; }
.hamburger::after { top: 8px; }

.menu-toggle[aria-expanded="true"] .hamburger {
  background: transparent;
}

.menu-toggle[aria-expanded="true"] .hamburger::before {
  top: 0;
  transform: rotate(45deg);
}

.menu-toggle[aria-expanded="true"] .hamburger::after {
  top: 0;
  transform: rotate(-45deg);
}

.nav-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  list-style: none;
  padding: 16px;
  margin: 0;
  border: 1px solid #eee;
  max-height: calc(100vh - 60px);
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.nav-menu.active {
  display: block;
}

.nav-link {
  display: block;
  padding: 12px 16px;
  text-decoration: none;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
}

.nav-link:hover,
.nav-link:focus {
  background: #f5f5f5;
  outline: none;
}

.nav-link.active {
  background: #e9e9e9;
  color: #007bff;
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

/* Desktop - show menu horizontally */
@media (min-width: 768px) {
  .menu-toggle {
    display: none;
  }
  
  .nav-menu {
    display: flex;
    position: static;
    border: none;
    padding: 0;
    max-height: none;
    overflow: visible;
  }
  
  .nav-item {
    flex: 0 0 auto;
  }
  
  .nav-link {
    padding: 16px;
    border: none;
  }
}
```

**JavaScript:**

```javascript
// Toggle menu
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const navMenu = document.querySelector('.nav-menu');
  
  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', () => {
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
      navMenu.classList.toggle('active');
    });
  
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!menuToggle.contains(e.target) && !navMenu.contains(e.target)) {
        menuToggle.setAttribute('aria-expanded', 'false');
        navMenu.classList.remove('active');
      }
    });
  
    // Close menu when clicking a link (for single-page apps)
    navMenu.addEventListener('click', (e) => {
      if (e.target.classList.contains('nav-link')) {
        menuToggle.setAttribute('aria-expanded', 'false');
        navMenu.classList.remove('active');
      }
    });
  }
});
```

**PHP hamburger menu:**

```php
<?php
function hamburgerNavigation($items, $activePath = '') {
  $navItems = '';
  foreach ($items as $url => $label) {
    $isActive = $url === $activePath;
    $activeClass = $isActive ? 'active' : '';
    $navItems .= "<li class='nav-item'><a href='{$url}' class='nav-link {$activeClass}'>{$label}</a></li>\n";
  }
  
  return <<<HTML
<nav class="mobile-nav" role="navigation" aria-label="Main navigation">
  <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="main-menu">
    <span class="hamburger"></span>
    <span class="sr-only">Menu</span>
  </button>
  <ul id="main-menu" class="nav-menu">
    {$navItems}
  </ul>
</nav>
<style>
  /* CSS here (same as above) */
</style>
<script>
// JavaScript here (same as above)
</script>
HTML;
}

// Usage
$navItems = [
  '/' => 'Home',
  '/about' => 'About',
  '/services' => 'Services',
  '/contact' => 'Contact'
];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo hamburgerNavigation($navItems, $currentPath);
?>
```

---

### 3. Bottom Navigation (Tab Bar)

**Best for**: Apps with primary actions that should be always accessible

**HTML:**

```html
<nav class="bottom-nav" role="navigation" aria-label="Main navigation">
  <a href="/" class="nav-link" aria-label="Home">
    <span class="nav-icon">🏠</span>
    <span class="nav-text">Home</span>
  </a>
  <a href="/search" class="nav-link" aria-label="Search">
    <span class="nav-icon">🔍</span>
    <span class="nav-text">Search</span>
  </a>
  <a href="/favorites" class="nav-link" aria-label="Favorites">
    <span class="nav-icon">❤️</span>
    <span class="nav-text">Favorites</span>
  </a>
  <a href="/profile" class="nav-link" aria-label="Profile">
    <span class="nav-icon">👤</span>
    <span class="nav-text">Profile</span>
  </a>
</nav>
```

**CSS:**

```css
.bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-around;
  padding: 8px 0;
  background: white;
  border-top: 1px solid #eee;
  z-index: 1000;
}

.nav-link {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 8px 16px;
  min-width: 64px;
  color: #666;
  text-decoration: none;
  font-size: 12px;
  transition: color 0.2s;
}

.nav-link:hover,
.nav-link:focus {
  color: #007bff;
  outline: none;
}

.nav-link.active {
  color: #007bff;
}

.nav-icon {
  font-size: 24px;
  margin-bottom: 4px;
}

.nav-text {
  font-size: 11px;
}

/* Desktop - hide or adapt */
@media (min-width: 768px) {
  .bottom-nav {
    display: none;
  }
}
```

**PHP bottom navigation:**

```php
<?php
function bottomNavigation($items, $activePath = '') {
  $navLinks = '';
  foreach ($items as $url => $data) {
    $isActive = $url === $activePath;
    $activeClass = $isActive ? 'active' : '';
    $icon = $data['icon'] ?? '❓';
    $label = $data['label'] ?? '';
    $navLinks .= <<<HTML
<a href="{$url}" class="nav-link {$activeClass}" aria-label="{$label}">
  <span class="nav-icon">{$icon}</span>
  <span class="nav-text">{$label}</span>
</a>
HTML;
  }
  
  return <<<HTML
<nav class="bottom-nav" role="navigation" aria-label="Main navigation">
  {$navLinks}
</nav>
<style>
  /* CSS here (same as above) */
</style>
HTML;
}

// Usage
$navItems = [
  '/' => ['icon' => '🏠', 'label' => 'Home'],
  '/search' => ['icon' => '🔍', 'label' => 'Search'],
  '/favorites' => ['icon' => '❤️', 'label' => 'Favorites'],
  '/profile' => ['icon' => '👤', 'label' => 'Profile']
];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo bottomNavigation($navItems, $currentPath);
?>
```

---

### 4. Breadcrumb Navigation

**Best for**: Hierarchical content (e.g., e-commerce categories)

**HTML:**

```html
<nav class="breadcrumb" aria-label="Breadcrumb">
  <ol class="breadcrumb-list">
    <li class="breadcrumb-item">
      <a href="/" class="breadcrumb-link">Home</a>
    </li>
    <li class="breadcrumb-item">
      <a href="/category" class="breadcrumb-link">Category</a>
    </li>
    <li class="breadcrumb-item" aria-current="page">
      <span class="breadcrumb-page">Product Name</span>
    </li>
  </ol>
</nav>
```

**CSS:**

```css
.breadcrumb {
  padding: 16px 0;
  font-size: 14px;
}

.breadcrumb-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
}

.breadcrumb-link {
  color: #007bff;
  text-decoration: none;
  display: flex;
  align-items: center;
}

.breadcrumb-link:hover,
.breadcrumb-link:focus {
  text-decoration: underline;
}

.breadcrumb-page {
  color: #666;
}

.breadcrumb-item + .breadcrumb-item::before {
  content: '>';
  color: #999;
  margin: 0 8px;
}

.breadcrumb-item:last-child::before {
  content: '';
}

/* Mobile - smaller */
@media (max-width: 480px) {
  .breadcrumb {
    font-size: 12px;
    padding: 8px 0;
  }
  
  .breadcrumb-item + .breadcrumb-item::before {
    margin: 0 4px;
  }
}
```

**PHP breadcrumb:**

```php
<?php
function breadcrumb($items, $current = '') {
  $html = '<nav class="breadcrumb" aria-label="Breadcrumb"><ol class="breadcrumb-list">';
  
  foreach ($items as $url => $label) {
    $html .= "<li class='breadcrumb-item'><a href='{$url}' class='breadcrumb-link'>{$label}</a></li>";
  }
  
  if (!empty($current)) {
    $html .= "<li class='breadcrumb-item' aria-current='page'><span class='breadcrumb-page'>{$current}</span></li>";
  }
  
  $html .= '</ol></nav>';
  
  return $html;
}

// Usage
$breadcrumbs = [
  '/' => 'Home',
  '/category' => 'Category'
];
echo breadcrumb($breadcrumbs, 'Product Name');
?>
```

---

### 5. Pagination

**Best for**: Long lists of content (articles, products, search results)

**HTML:**

```html
<nav class="pagination" role="navigation" aria-label="Pagination">
  <a href="?page=1" class="page-link" aria-label="First page">«</a>
  <a href="?page=4" class="page-link" aria-label="Previous page">‹</a>
  <a href="?page=1" class="page-link">1</a>
  <span class="page-link active" aria-current="page">5</span>
  <a href="?page=6" class="page-link">6</a>
  <a href="?page=2" class="page-link" aria-label="Next page">›</a>
  <a href="?page=10" class="page-link" aria-label="Last page">»</a>
</nav>
```

**CSS:**

```css
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 4px;
  padding: 16px;
  flex-wrap: wrap;
}

.page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  min-height: 36px;
  padding: 8px 12px;
  color: #007bff;
  text-decoration: none;
  border: 1px solid #ddd;
  border-radius: 4px;
  transition: all 0.2s;
}

.page-link:hover,
.page-link:focus {
  background: #f0f0f0;
  border-color: #007bff;
  outline: none;
}

.page-link.active {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.page-link:disabled {
  opacity: 0.5;
  pointer-events: none;
}

/* Mobile - full width */
@media (max-width: 480px) {
  .pagination {
    padding: 8px;
  }
  
  .page-link {
    min-width: 32px;
    min-height: 32px;
    padding: 4px 8px;
    font-size: 14px;
  }
}
```

**PHP pagination:**

```php
<?php
function pagination($currentPage, $totalPages, $baseUrl = '') {
  if ($totalPages <= 1) return '';
  
  $html = '<nav class="pagination" role="navigation" aria-label="Pagination">';
  
  // First page
  $html .= "<a href='{$baseUrl}?page=1' class='page-link' aria-label='First page'>«</a>";
  
  // Previous page
  if ($currentPage > 1) {
    $html .= "<a href='{$baseUrl}?page=" . ($currentPage - 1) . "' class='page-link' aria-label='Previous page'>‹</a>";
  } else {
    $html .= "<span class='page-link' aria-label='Previous page' disabled>‹</span>";
  }
  
  // Page numbers
  $start = max(1, $currentPage - 2);
  $end = min($totalPages, $currentPage + 2);
  
  if ($start > 1) {
    $html .= "<a href='{$baseUrl}?page=1' class='page-link'>1</a>";
    if ($start > 2) {
      $html .= "<span class='page-link'>...</span>";
    }
  }
  
  for ($i = $start; $i <= $end; $i++) {
    if ($i === $currentPage) {
      $html .= "<span class='page-link active' aria-current='page'>{$i}</span>";
    } else {
      $html .= "<a href='{$baseUrl}?page={$i}' class='page-link'>{$i}</a>";
    }
  }
  
  if ($end < $totalPages) {
    if ($end < $totalPages - 1) {
      $html .= "<span class='page-link'>...</span>";
    }
    $html .= "<a href='{$baseUrl}?page={$totalPages}' class='page-link'>{$totalPages}</a>";
  }
  
  // Next page
  if ($currentPage < $totalPages) {
    $html .= "<a href='{$baseUrl}?page=" . ($currentPage + 1) . "' class='page-link' aria-label='Next page'>›</a>";
  } else {
    $html .= "<span class='page-link' aria-label='Next page' disabled>›</span>";
  }
  
  // Last page
  $html .= "<a href='{$baseUrl}?page={$totalPages}' class='page-link' aria-label='Last page'>»</a>";
  
  $html .= '</nav>';
  
  return $html;
}

// Usage
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$totalPages = 10;
echo pagination($currentPage, $totalPages, '/products');
?>
```

---

### 6. Off-Canvas Navigation (Drawer)

**Best for**: Apps with many navigation items, material design

**HTML:**

```html
<div class="drawer-layout">
  <nav class="drawer" role="navigation" aria-label="Main navigation">
    <div class="drawer-header">
      <h2 class="drawer-title">Menu</h2>
      <button class="drawer-close" aria-label="Close menu">×</button>
    </div>
    <ul class="drawer-menu">
      <li><a href="/" class="drawer-link">Home</a></li>
      <li><a href="/about" class="drawer-link">About</a></li>
      <li><a href="/services" class="drawer-link">Services</a></li>
      <li><a href="/contact" class="drawer-link">Contact</a></li>
    </ul>
  </nav>
  
  <div class="drawer-overlay"></div>
  
  <main class="drawer-content">
    <button class="drawer-toggle" aria-label="Open menu" aria-expanded="false">
      <span class="hamburger"></span>
    </button>
  
    <!-- Page content -->
  </main>
</div>
```

**CSS:**

```css
.drawer-layout {
  position: relative;
  overflow: hidden;
}

.drawer {
  position: fixed;
  top: 0;
  left: -280px;
  width: 280px;
  height: 100vh;
  background: white;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
  z-index: 2000;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

.drawer.active {
  transform: translateX(280px);
}

.drawer-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  border-bottom: 1px solid #eee;
}

.drawer-title {
  margin: 0;
  font-size: 18px;
}

.drawer-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  padding: 4px 8px;
}

.drawer-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.drawer-link {
  display: block;
  padding: 12px 16px;
  text-decoration: none;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
}

.drawer-link:hover,
.drawer-link:focus {
  background: #f5f5f5;
  outline: none;
}

.drawer-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1500;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s, visibility 0.3s;
}

.drawer-overlay.active {
  opacity: 1;
  visibility: visible;
}

.drawer-content {
  margin-left: 0;
  transition: margin-left 0.3s ease;
}

.drawer-content.shifted {
  margin-left: 280px;
}

.drawer-toggle {
  position: fixed;
  top: 16px;
  left: 16px;
  background: none;
  border: none;
  padding: 8px;
  cursor: pointer;
  z-index: 2001;
}

.hamburger {
  display: block;
  width: 24px;
  height: 2px;
  background: #333;
  position: relative;
}

.hamburger::before,
.hamburger::after {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  background: #333;
  left: 0;
}

.hamburger::before { top: -8px; }
.hamburger::after { top: 8px; }

/* Desktop - drawer always visible */
@media (min-width: 768px) {
  .drawer {
    left: 0;
    transform: none;
  }
  
  .drawer-content {
    margin-left: 280px;
  }
  
  .drawer-toggle {
    display: none;
  }
  
  .drawer-overlay {
    display: none;
  }
}
```

**JavaScript:**

```javascript
document.addEventListener('DOMContentLoaded', () => {
  const drawer = document.querySelector('.drawer');
  const drawerOverlay = document.querySelector('.drawer-overlay');
  const drawerToggle = document.querySelector('.drawer-toggle');
  const drawerClose = document.querySelector('.drawer-close');
  const drawerContent = document.querySelector('.drawer-content');
  
  function openDrawer() {
    drawer.classList.add('active');
    drawerOverlay.classList.add('active');
    drawerContent.classList.add('shifted');
    drawerToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  
  function closeDrawer() {
    drawer.classList.remove('active');
    drawerOverlay.classList.remove('active');
    drawerContent.classList.remove('shifted');
    drawerToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }
  
  if (drawerToggle) {
    drawerToggle.addEventListener('click', () => {
      const isExpanded = drawerToggle.getAttribute('aria-expanded') === 'true';
      if (isExpanded) {
        closeDrawer();
      } else {
        openDrawer();
      }
    });
  }
  
  if (drawerClose) {
    drawerClose.addEventListener('click', closeDrawer);
  }
  
  if (drawerOverlay) {
    drawerOverlay.addEventListener('click', closeDrawer);
  }
  
  // Close on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeDrawer();
    }
  });
});
```

**PHP off-canvas navigation:**

```php
<?php
function offCanvasNavigation($items, $activePath = '', $content) {
  $navLinks = '';
  foreach ($items as $url => $label) {
    $isActive = $url === $activePath;
    $activeClass = $isActive ? 'active' : '';
    $navLinks .= "<li><a href='{$url}' class='drawer-link {$activeClass}'>{$label}</a></li>\n";
  }
  
  return <<<HTML
<div class="drawer-layout">
  <nav class="drawer" role="navigation" aria-label="Main navigation">
    <div class="drawer-header">
      <h2 class="drawer-title">Menu</h2>
      <button class="drawer-close" aria-label="Close menu">×</button>
    </div>
    <ul class="drawer-menu">
      {$navLinks}
    </ul>
  </nav>
  
  <div class="drawer-overlay"></div>
  
  <main class="drawer-content">
    <button class="drawer-toggle" aria-label="Open menu" aria-expanded="false">
      <span class="hamburger"></span>
    </button>
  
    {$content}
  </main>
</div>
<style>
  /* CSS here (same as above) */
</style>
<script>
// JavaScript here (same as above)
</script>
HTML;
}

// Usage
$navItems = [
  '/' => 'Home',
  '/about' => 'About',
  '/services' => 'Services',
  '/contact' => 'Contact'
];
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pageContent = '<h1>Welcome to my site</h1><p>Content goes here...</p>';
echo offCanvasNavigation($navItems, $currentPath, $pageContent);
?>
```

---

## 🎯 Navigation Best Practices

### 1. Accessibility

**ARIA attributes:**

- `role="navigation"` for nav elements
- `aria-label` for navigation description
- `aria-current="page"` for current page
- `aria-expanded` for collapsible menus
- `aria-controls` for menu controls

**Keyboard navigation:**

- All navigation items must be focusable
- Use proper tab order
- Provide visible focus indicators
- Support arrow key navigation

**Screen reader support:**

- Use semantic HTML (`<nav>`, `<ul>`, `<li>`, `<a>`)
- Provide text alternatives for icons
- Use `aria-label` for icon-only buttons

### 2. Performance

- Minimize DOM elements in navigation
- Use CSS for styling, not images
- Lazy load non-critical navigation
- Cache navigation HTML

**PHP navigation caching:**

```php
<?php
// Cache navigation HTML
session_start();

if (!isset($_SESSION['navigation_html'])) {
  $navHtml = generateNavigation();
  $_SESSION['navigation_html'] = $navHtml;
}

echo $_SESSION['navigation_html'];
?>
```

### 3. Touch Targets

- Minimum 44x44px for touch targets
- Minimum 8px spacing between targets
- Primary navigation items should be 48x48px

### 4. Mobile-First

- Design navigation for mobile first
- Test on smallest screen first
- Add desktop enhancements later

### 5. Progressive Enhancement

- Navigation should work without JavaScript
- Enhance with JavaScript for better UX
- Provide fallbacks for unsupported features

---

## 📊 Navigation Checklist

### Before Implementation

- [ ] Navigation structure planned?
- [ ] Mobile-first approach?
- [ ] Accessibility requirements met?
- [ ] Performance considered?

### During Implementation

- [ ] Semantic HTML used?
- [ ] ARIA attributes added?
- [ ] Touch targets ≥44px?
- [ ] Keyboard navigable?
- [ ] Responsive design?

### Before Release

- [ ] Tested on mobile?
- [ ] Tested on tablet?
- [ ] Tested on desktop?
- [ ] Tested with keyboard?
- [ ] Tested with screen reader?
- [ ] Performance optimized?

---

## 📚 Resources

### Guides

- [MDN: Navigation](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/nav)
- [W3C: ARIA Navigation](https://www.w3.org/TR/wai-aria-practices-1.1/#navigation)
- [Google: Mobile Navigation](https://developers.google.com/web/fundamentals/design-and-ux/mobile/navigation-patterns)

### Examples

- [Bootstrap Navigation](https://getbootstrap.com/docs/5.0/components/navbar/)
- [Material Design Navigation](https://material.io/design/navigation/understanding-navigation.html)
- [Apple Human Interface Guidelines](https://developer.apple.com/design/human-interface-guidelines/patterns/navigation/)

### Tools

- [Accessibility Checker](https://www.deque.com/axe/)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse) (PWA audit)
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)

---

## 🎯 Summary

**Mobile web navigation should be:**

- **Accessible**: Work for all users
- **Intuitive**: Follow user expectations
- **Efficient**: Minimize taps, maximize thumb zone
- **Responsive**: Adapt to different screen sizes
- **Performant**: Load quickly
- **Consistent**: Same patterns across the app

**Remember**: Navigation is the roadmap of your app. Make it easy to follow.
