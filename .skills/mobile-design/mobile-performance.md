
# Mobile Web Performance Optimization

## ⚡ The Performance Imperative

**Mobile web apps must load fast and run smoothly.**

Key metrics:

- **First Contentful Paint (FCP)**: ≤1.8s (Good), ≤1.0s (Excellent)
- **Largest Contentful Paint (LCP)**: ≤2.5s (Good), ≤1.5s (Excellent)
- **First Input Delay (FID)**: ≤100ms (Good), ≤50ms (Excellent)
- **Cumulative Layout Shift (CLS)**: ≤0.1 (Good), ≤0.05 (Excellent)
- **Time to Interactive (TTI)**: ≤3.8s (Good), ≤2.0s (Excellent)

**User perception:**

- ≤1s: Feels instant
- 1-3s: Feels fast
- 3-5s: Feels slow
- > 5s: User likely abandons
  >

**Bounce rate impact:**

- 1s: 10% bounce rate
- 3s: 30% bounce rate
- 5s: 50%+ bounce rate

---

## 🎯 Core Web Vitals

### 1. Largest Contentful Paint (LCP)

**What it measures**: When the largest content element (image, video, text block) is visible

**Target**: ≤2.5s

**Optimization strategies:**

- Optimize and compress images
- Preload important resources
- Use efficient CSS/JS
- Server-side rendering (PHP includes)
- Cache aggressively

### 2. First Input Delay (FID)

**What it measures**: Time from user interaction to browser response

**Target**: ≤100ms

**Optimization strategies:**

- Minimize JavaScript execution time
- Defer non-critical JavaScript
- Use web workers for heavy computations
- Break up long tasks (&gt;50ms)

### 3. Cumulative Layout Shift (CLS)

**What it measures**: Visual stability (how much content shifts)

**Target**: ≤0.1

**Optimization strategies:**

- Always specify image dimensions
- Use placeholder for images/videos
- Avoid dynamic content injection above existing content
- Reserve space for ads/iframes

---

## 📊 Performance Budget

**Define a budget for your mobile web app:**

| Resource            | Budget               | Measurement |
| ------------------- | -------------------- | ----------- |
| Total page weight   | ≤500KB (compressed) | Lighthouse  |
| JavaScript          | ≤200KB              | Lighthouse  |
| CSS                 | ≤100KB              | Lighthouse  |
| Images              | ≤200KB              | Lighthouse  |
| Fonts               | ≤100KB              | Lighthouse  |
| Third-party scripts | ≤100KB              | Lighthouse  |
| First load time     | ≤3s on 3G           | WebPageTest |
| Repeat load time    | ≤1s                 | WebPageTest |

---

## 🚀 Optimization Strategies

### 1. Critical Rendering Path Optimization

**Goal**: Render above-the-fold content as fast as possible

**The critical path:**

```
HTML Parse → DOM Construction → CSSOM Construction → Render Tree → Layout → Paint
                    ↑
              (CSS blocks rendering)
                    ↑
              (JS blocks parsing)
```

**Optimization steps:**

#### a. Minimize Render-Blocking Resources

**BAD:**

```html
<head>
  <link href="styles.css" rel="stylesheet">
  <script src="app.js"></script>
</head>
```

**GOOD:**

```html
<head>
  <!-- Inline critical CSS -->
  <style>
    /* Critical above-the-fold CSS */
    body { margin: 0; font-family: sans-serif; }
    .header { background: #fff; }
  </style>
  
  <!-- Defer non-critical CSS -->
  <link href="styles.css" rel="stylesheet" media="print" onload="this.media='all'">
  <noscript><link href="styles.css" rel="stylesheet"></noscript>
  
  <!-- Async JavaScript -->
  <script src="app.js" async></script>
  
  <!-- Defer JavaScript -->
  <script src="analytics.js" defer></script>
</head>
```

**PHP dynamic critical CSS:**

```php
<?php
$criticalCSS = file_get_contents('critical.css');
?>
<head>
  <style><?= $criticalCSS ?></style>
  <link href="styles.css" rel="preload" as="style" onload="this.rel='stylesheet'">
  <noscript><link href="styles.css" rel="stylesheet"></noscript>
</head>
```

#### b. Inline Critical CSS

**Identify critical CSS:**

- Above-the-fold content
- Visible without scrolling
- First \~14KB (TCP slow-start)

**Tools:**

- [Critical CSS Generator](https://www.sitelocity.com/critical-path-css-generator)
- [Penthouse](https://github.com/pocketjoso/penthouse)
- [Critical](https://github.com/addyosmani/critical)

**PHP implementation:**

```php
<?php
// Generate critical CSS for current page
$page = $_SERVER['REQUEST_URI'];
$criticalCSSFile = "critical/{$page}.css";

if (file_exists($criticalCSSFile)) {
  $criticalCSS = file_get_contents($criticalCSSFile);
  echo "<style>{$criticalCSS}</style>";
}

// Load full CSS asynchronously
echo '<link href="styles.css" rel="preload" as="style" onload="this.rel=\'stylesheet\'">';
?>
```

#### c. Defer Non-Critical JavaScript

**BAD:**

```html
<script src="analytics.js"></script>
<script src="ads.js"></script>
<script src="tracking.js"></script>
```

**GOOD:**

```html
<!-- Defer non-critical JS -->
<script src="analytics.js" defer></script>
<script src="ads.js" defer></script>
<script src="tracking.js" defer></script>

<!-- Or async if order doesn't matter -->
<script src="analytics.js" async></script>
```

**PHP conditional loading:**

```php
<?php
// Load analytics only on production
if (ENVIRONMENT === 'production') {
  echo '<script src="analytics.js" defer></script>';
}

// Load polyfills only for old browsers
if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ||
     strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false)) {
  echo '<script src="polyfills.js" defer></script>';
}
?>
```

### 2. Image Optimization

Images are typically **60-70% of page weight** on mobile.

#### a. Compression

**Tools:**

- [Squoosh](https://squoosh.app/) (Google)
- [TinyPNG](https://tinypng.com/)
- [ImageOptim](https://imageoptim.com/)
- [PHP ImageMagick](https://www.php.net/manual/en/book.imagick.php)

**PHP image compression:**

```php
<?php
function compressImage($source, $destination, $quality = 80) {
  if (extension_loaded('imagick')) {
    $image = new Imagick($source);
    $image->setImageCompressionQuality($quality);
    $image->stripImage(); // Remove metadata
    $image->writeImage($destination);
    $image->destroy();
  } else {
    // GD library fallback
    $image = imagecreatefromjpeg($source);
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
  }
}

// Compress uploaded images
if (isset($_FILES['image'])) {
  $source = $_FILES['image']['tmp_name'];
  $destination = 'uploads/' . basename($_FILES['image']['name']);
  compressImage($source, $destination, 85);
}
?>
```

#### b. Format Selection

| Format | Quality   | Size       | Transparency | Animation | Browser Support |
| ------ | --------- | ---------- | ------------ | --------- | --------------- |
| JPEG   | High      | Medium     | ❌           | ❌        | ⭐⭐⭐⭐⭐      |
| PNG    | High      | Large      | ✅           | ❌        | ⭐⭐⭐⭐⭐      |
| WebP   | High      | Small      | ✅           | ✅        | ⭐⭐⭐⭐ (95%+) |
| AVIF   | Very High | Very Small | ✅           | ✅        | ⭐⭐ (80%+)     |
| GIF    | Low       | Medium     | ✅           | ✅        | ⭐⭐⭐⭐⭐      |

**Recommendation:**

- Use **WebP** for most images (best compression/support balance)
- Use **AVIF** if you can afford to exclude some browsers
- Use **JPEG** for photos (fallback)
- Use **PNG** for graphics with transparency (fallback)

**PHP WebP conversion:**

```php
<?php
function convertToWebP($source, $destination, $quality = 80) {
  if (extension_loaded('imagick')) {
    $image = new Imagick($source);
    $image->setImageFormat('webp');
    $image->setImageCompressionQuality($quality);
    $image->setOption('webp:lossless', 'false');
    $image->writeImage($destination);
    $image->destroy();
  }
}

// Convert uploaded images to WebP
if (isset($_FILES['image'])) {
  $source = $_FILES['image']['tmp_name'];
  $webpDest = 'uploads/' . pathinfo($_FILES['image']['name'], PATHINFO_FILENAME) . '.webp';
  $fallbackDest = 'uploads/' . $_FILES['image']['name'];
  
  convertToWebP($source, $webpDest, 80);
  // Keep original as fallback
  move_uploaded_file($source, $fallbackDest);
}
?>
```

#### c. Responsive Images

**BAD:**

```html
<img src="large-image.jpg" width="100%">
```

**GOOD:**

```html
<picture>
  <source srcset="image.webp" type="image/webp">
  <source srcset="image.jpg" type="image/jpeg">
  <img 
    src="image.jpg" 
    srcset="image-480.jpg 480w, image-800.jpg 800w, image-1200.jpg 1200w"
    sizes="(max-width: 600px) 480px, (max-width: 1200px) 800px, 1200px"
    alt="Description"
    loading="lazy"
    decoding="async"
  >
</picture>
```

**PHP responsive images:**

```php
<?php
function responsiveImage($baseName, $alt = '', $sizes = [480, 800, 1200]) {
  $srcset = [];
  foreach ($sizes as $size) {
    $srcset[] = "{$baseName}-{$size}.webp {$size}w";
  }
  $srcset = implode(', ', $srcset);
  
  $sizesAttr = '(max-width: 600px) 480px, (max-width: 1200px) 800px, 1200px';
  
  return <<<HTML
<picture>
  <source srcset="{$srcset}" type="image/webp">
  <img 
    src="{$baseName}.jpg" 
    srcset="{$baseName}-480.jpg 480w, {$baseName}-800.jpg 800w, {$baseName}-1200.jpg 1200w"
    sizes="{$sizesAttr}"
    alt="{$alt}"
    loading="lazy"
    decoding="async"
  >
</picture>
HTML;
}

// Usage
echo responsiveImage('hero-image', 'Hero Image');
?>
```

#### d. Lazy Loading

**Native lazy loading:**

```html
<img src="image.jpg" loading="lazy" alt="Description">
<iframe src="video.html" loading="lazy"></iframe>
```

**PHP conditional lazy loading:**

```php
<?php
function lazyImage($src, $alt = '', $loading = 'lazy') {
  return "<img src='{$src}' alt='{$alt}' loading='{$loading}'>";
}

// Above the fold - eager
// echo lazyImage('hero.jpg', 'Hero', 'eager');

// Below the fold - lazy
// echo lazyImage('content-image.jpg', 'Content Image');
?>
```

#### e. Placeholders

**Avoid layout shift:**

```html
<div class="image-container">
  <img 
    src="image.jpg" 
    srcset="..." 
    sizes="..."
    alt="Description"
    loading="lazy"
    width="800"
    height="600"
  >
  <!-- Or use aspect-ratio -->
  <div style="aspect-ratio: 4/3;"></div>
</div>

<style>
  .image-container {
    position: relative;
    width: 100%;
    aspect-ratio: 4/3;
    background: #f0f0f0;
  }
  .image-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>
```

**PHP placeholder generation:**

```php
<?php
function imageWithPlaceholder($src, $alt = '', $width = 800, $height = 600) {
  $placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {$width} {$height}'%3E%3Crect width='{$width}' height='{$height}' fill='%23f0f0f0'/%3E%3C/svg%3E";
  
  return <<<HTML
<div style="position: relative; width: 100%; aspect-ratio: {$width}/{$height}; background: #f0f0f0;">
  <img 
    src="{$src}" 
    alt="{$alt}"
    loading="lazy"
    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
  >
</div>
HTML;
}
?>
```

### 3. CSS Optimization

#### a. Minification

**Tools:**

- [CSSNano](https://cssnano.co/)
- [Clean-CSS](https://github.com/clean-css/clean-css)
- [PHP CSS Minifier](https://github.com/matthiasmullie/minify)

**PHP CSS minification:**

```php
<?php
require_once 'vendor/autoload.php';
use MatthiasMullie\Minify\CSS;

$css = file_get_contents('styles.css');
$minifier = new CSS($css);
$minified = $minifier->minify();

file_put_contents('styles.min.css', $minified);
?>
```

#### b. Critical CSS Inlining

**PHP implementation:**

```php
<?php
// Extract critical CSS from main file
$criticalCSS = getCriticalCSS('styles.css');
?>
<head>
  <style><?= $criticalCSS ?></style>
  <link href="styles.css" rel="preload" as="style" onload="this.rel='stylesheet'">
</head>
```

#### c. Avoid Expensive Properties

**Expensive CSS properties:**

- `box-shadow` (especially with blur)
- `filter`
- `transform` (3D)
- `position: fixed` (on mobile)
- `border-radius` (large values)
- `@font-face` (multiple fonts)

**Cheap CSS properties:**

- `color`
- `background-color`
- `border`
- `opacity`
- `transform` (2D)
- `visibility`

#### d. Reduce CSS Complexity

**BAD:**

```css
/* Deeply nested selectors */
body div.container ul.nav li a {
  color: blue;
}
```

**GOOD:**

```css
/* Flat selectors */
.nav-link {
  color: blue;
}
```

**PHP CSS optimization:**

```php
<?php
// Inline small CSS instead of external file
$smallCSS = '.button { color: blue; }';
if (strlen($smallCSS) < 1000) {
  echo "<style>{$smallCSS}</style>";
} else {
  echo "<link href='small.css' rel='stylesheet'>";
}
?>
```

### 4. JavaScript Optimization

#### a. Minification and Bundling

**Tools:**

- [Terser](https://terser.org/) (JS minification)
- [Rollup](https://rollupjs.org/) (bundling)
- [Webpack](https://webpack.js.org/) (bundling)
- [PHP JS Minifier](https://github.com/matthiasmullie/minify)

**PHP JS minification:**

```php
<?php
require_once 'vendor/autoload.php';
use MatthiasMullie\Minify\JS;

$js = file_get_contents('app.js');
$minifier = new JS($js);
$minified = $minifier->minify();

file_put_contents('app.min.js', $minified);
?>
```

#### b. Code Splitting

**Load only what's needed:**

```html
<!-- Load common JS first -->
<script src="common.js" defer></script>

<!-- Load page-specific JS conditionally -->
<?php if ($page === 'home'): ?>
  <script src="home.js" defer></script>
<?php elseif ($page === 'product'): ?>
  <script src="product.js" defer></script>
<?php endif; ?>
```

#### c. Defer Non-Critical JS

**BAD:**

```html
<script src="analytics.js"></script>
<script src="ads.js"></script>
```

**GOOD:**

```html
<script src="analytics.js" defer></script>
<script src="ads.js" async></script>
```

#### d. Use Efficient APIs

**BAD:**

```javascript
// Query DOM repeatedly
document.querySelectorAll('.item').forEach(item => {
  item.addEventListener('click', () => {
    // Handle click
  });
});
```

**GOOD:**

```javascript
// Cache DOM references
const items = document.querySelectorAll('.item');
const handler = () => { /* Handle click */ };

items.forEach(item => {
  item.addEventListener('click', handler);
});

// Or use event delegation
document.body.addEventListener('click', (e) => {
  if (e.target.classList.contains('item')) {
    // Handle click
  }
});
```

### 5. Font Optimization

#### a. Font Loading Strategies

**BAD:**

```html
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
```

**BETTER:**

```html
<!-- Preload font -->
<link rel="preload" href="fonts/roboto.woff2" as="font" type="font/woff2" crossorigin>

<!-- Load with display=swap -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">

<!-- Or self-host -->
<link href="fonts/roboto.css" rel="stylesheet">
```

**PHP font preload:**

```php
<?php
$fonts = [
  'roboto-regular.woff2',
  'roboto-bold.woff2'
];

foreach ($fonts as $font) {
  echo "<link rel='preload' href='fonts/{$font}' as='font' type='font/woff2' crossorigin>\n";
}
?>
```

#### b. Font Format Selection

| Format | Compression | Browser Support | License |
| ------ | ----------- | --------------- | ------- |
| WOFF2  | Best        | Modern (95%+)   | Free    |
| WOFF   | Good        | Most (90%+)     | Free    |
| TTF    | Medium      | Most            | Varies  |
| OTF    | Medium      | Most            | Varies  |
| EOT    | Poor        | IE only         | Free    |

**Recommendation:** Use WOFF2 with WOFF fallback

**CSS:**

```css
@font-face {
  font-family: 'Roboto';
  src: 
    url('roboto-regular.woff2') format('woff2'),
    url('roboto-regular.woff') format('woff');
  font-weight: 400;
  font-display: swap;
}
```

#### c. Limit Font Weights

**BAD:**

```css
@font-face {
  font-family: 'Roboto';
  src: url('roboto-light.woff2') format('woff2');
  font-weight: 300;
}
@font-face {
  font-family: 'Roboto';
  src: url('roboto-regular.woff2') format('woff2');
  font-weight: 400;
}
@font-face {
  font-family: 'Roboto';
  src: url('roboto-bold.woff2') format('woff2');
  font-weight: 700;
}
@font-face {
  font-family: 'Roboto';
  src: url('roboto-black.woff2') format('woff2');
  font-weight: 900;
}
```

**GOOD:**

```css
/* Only load what you need */
@font-face {
  font-family: 'Roboto';
  src: url('roboto-regular.woff2') format('woff2');
  font-weight: 400;
}
@font-face {
  font-family: 'Roboto';
  src: url('roboto-bold.woff2') format('woff2');
  font-weight: 700;
}
```

### 6. Caching Strategies

#### a. HTTP Caching

**Cache headers:**

```php
<?php
// PHP cache headers
$expires = 60 * 60 * 24 * 365; // 1 year
header("Cache-Control: public, max-age={$expires}");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expires) . " GMT");

// For HTML pages (shorter cache)
$htmlExpires = 60 * 10; // 10 minutes
header("Cache-Control: public, max-age={$htmlExpires}");
?>
```

**HTAccess caching:**

```apache
<IfModule mod_expires.c>
  ExpiresActive On
  
  # Images, fonts, CSS, JS
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType font/woff2 "access plus 1 year"
  ExpiresByType font/woff "access plus 1 year"
  ExpiresByType text/css "access plus 1 year"
  ExpiresByType application/javascript "access plus 1 year"
  
  # HTML
  ExpiresByType text/html "access plus 10 minutes"
</IfModule>
```

#### b. Service Worker Caching (PWA)

**Basic service worker:**

```javascript
// sw.js
const CACHE_NAME = 'my-app-v1';
const urlsToCache = [
  '/',
  '/styles.css',
  '/app.js',
  '/images/logo.webp'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => response || fetch(event.request))
  );
});

self.addEventListener('activate', (event) => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
```

**PHP service worker registration:**

```php
<?php
// In your HTML template
if ('serviceWorker' in $GLOBALS['_SERVER']) {
  echo <<<HTML
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js');
    });
  }
</script>
HTML;
}
?>
```

### 7. Server-Side Optimization (PHP)

#### a. Output Compression

**PHP gzip compression:**

```php
<?php
// Enable output compression
if (!ob_start("ob_gzhandler")) {
  ob_start();
}

// Or in php.ini
zlib.output_compression = On
zlib.output_compression_level = 6
?>
```

#### b. Minify HTML Output

**PHP HTML minification:**

```php
<?php
function minifyHTML($html) {
  // Remove comments
  $html = preg_replace('/<!--(.*)-->/is', '', $html);
  
  // Remove whitespace between tags
  $html = preg_replace('/>\s+</', '><', $html);
  $html = preg_replace('/\s+>/', '>', $html);
  
  // Remove empty attributes
  $html = preg_replace('/\s+=\s+("|\')\1/', '', $html);
  
  return $html;
}

// Usage
$html = ob_get_clean();
echo minifyHTML($html);
?>
```

#### c. Database Optimization

**PHP database best practices:**

```php
<?php
// Use prepared statements (security + performance)
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
$stmt->execute([$category]);
$products = $stmt->fetchAll();

// Limit results
$stmt = $pdo->prepare("SELECT * FROM products LIMIT 20");
$stmt->execute();

// Use indexes
// CREATE INDEX idx_category ON products(category)

// Cache database results
$cache = new Memcached();
$cacheKey = 'products_' . $category;

if ($cache->get($cacheKey)) {
  $products = $cache->get($cacheKey);
} else {
  $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
  $stmt->execute([$category]);
  $products = $stmt->fetchAll();
  $cache->set($cacheKey, $products, 3600); // Cache for 1 hour
}
?>
```

#### d. Opcode Caching

**PHP opcode caching:**

- [OPcache](https://www.php.net/manual/en/book.opcache.php) (Built-in)
- [APCu](https://www.php.net/manual/en/book.apcu.php) (User caching)

**php.ini configuration:**

```ini
; Enable OPcache
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=180
opcache.fast_shutdown=1

; Enable APCu for user caching
apc.enabled=1
apc.shm_size=32M
```

### 8. CDN Usage

**Benefits:**

- Faster delivery (edge locations)
- Reduced server load
- Better scalability

**PHP CDN integration:**

```php
<?php
// Cloudflare, AWS CloudFront, etc.
$cdnUrl = 'https://cdn.example.com';

// Serve static assets from CDN
function asset($path) {
  global $cdnUrl;
  return $cdnUrl . '/' . ltrim($path, '/');
}

// Usage
echo '<img src="' . asset('images/logo.webp') . '">';
?>
```

---

## 📊 Performance Measurement

### 1. Lighthouse

**Run Lighthouse:**

- Chrome DevTools → Lighthouse tab
- Or command line: `lighthouse https://example.com --output=html --output-path=report.html`

**PHP Lighthouse automation:**

```php
<?php
// Run Lighthouse programmatically (requires Node.js)
shell_exec('lighthouse https://example.com --output=json --output-path=report.json');
$report = json_decode(file_get_contents('report.json'), true);

// Check scores
$scores = $report['lhr']['summary'];
if ($scores['performance'] < 0.9) {
  echo "Performance score is low: " . ($scores['performance'] * 100);
}
?>
```

### 2. WebPageTest

**Test with:**

- Multiple locations
- Multiple devices
- Multiple connection speeds (3G, 4G)
- Repeat views (cached)

**URL:** [https://www.webpagetest.org/](https://www.webpagetest.org/)

### 3. Chrome User Experience Report

**Real user data:**

- Field data from Chrome users
- Core Web Vitals metrics
- URL: [https://developers.google.com/web/tools/chrome-user-experience-report](https://developers.google.com/web/tools/chrome-user-experience-report)

### 4. PHP Performance Monitoring

**Track metrics:**

```php
<?php
// Track page load time
$start = microtime(true);

// ... your code ...

$end = microtime(true);
$loadTime = $end - $start;

// Log to database or file
file_put_contents(
  'performance.log',
  date('Y-m-d H:i:s') . " - " . $_SERVER['REQUEST_URI'] . " - " . round($loadTime, 3) . "s\n",
  FILE_APPEND
);

// Or use a monitoring service
// New Relic, DataDog, etc.
?>
```

---

## 🎯 Performance Checklist

### Before Development

- [ ] Performance budget defined?
- [ ] Image optimization strategy?
- [ ] Caching strategy?
- [ ] CDN configured?
- [ ] Compression enabled?

### During Development

- [ ] Viewport meta tag present?
- [ ] Critical CSS inlined?
- [ ] Non-critical CSS/JS deferred?
- [ ] Images optimized and responsive?
- [ ] Fonts preloaded?
- [ ] Lazy loading implemented?
- [ ] Touch targets ≥44px?

### Before Release

- [ ] Lighthouse score ≥90?
- [ ] All images optimized?
- [ ] CSS/JS minified?
- [ ] Caching configured?
- [ ] Tested on real devices?
- [ ] Tested on slow network (3G)?
- [ ] Core Web Vitals passing?

---

## 📚 Resources

### Tools

- [Lighthouse](https://developers.google.com/web/tools/lighthouse)
- [WebPageTest](https://www.webpagetest.org/)
- [PageSpeed Insights](https://pagespeed.web.dev/)
- [Chrome DevTools](https://developer.chrome.com/docs/devtools/)
- [Squoosh](https://squoosh.app/) (Image compression)

### Guides

- [Google Web Fundamentals](https://developers.google.com/web/fundamentals)
- [MDN Performance](https://developer.mozilla.org/en-US/docs/Web/Performance)
- [Web Performance Optimization](https://hpbn.co/)
- [CSS Tricks: Performance](https://css-tricks.com/tag/performance/)

### PHP-Specific

- [PHP Performance](https://www.php.net/manual/en/performance.php)
- [OPcache Documentation](https://www.php.net/manual/en/book.opcache.php)
- [PHP Benchmarking](https://github.com/phpbench/phpbench)

---

## 🎯 Summary

**Mobile web performance is non-negotiable.**

- **Measure first**: Use Lighthouse, WebPageTest
- **Optimize images**: Compress, WebP, responsive
- **Minimize resources**: Critical CSS, defer JS
- **Cache everything**: HTTP cache, service worker
- **Use efficient code**: Minify, bundle, tree-shake
- **Test on real devices**: Especially low-end ones

**Remember**: A slow mobile web app loses 50%+ of users. Speed = Revenue.
