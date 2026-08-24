
# Progressive Web Apps (PWA) with PHP/HTML

## 🎯 What is a PWA?

A **Progressive Web App** is a web application that uses modern web capabilities to deliver an app-like experience.

**Characteristics:**

- **Progressive**: Works for every user, regardless of browser
- **Responsive**: Fits any form factor (mobile, tablet, desktop)
- **Connectivity independent**: Works offline or on low-quality networks
- **App-like**: Feels like a native app
- **Fresh**: Always up-to-date
- **Safe**: Served via HTTPS
- **Discoverable**: Identifiable as an "application" thanks to W3C manifest
- **Re-engageable**: Can re-engage users through notifications
- **Installable**: Can be "installed" on the home screen
- **Linkable**: Can be shared via URL

---

## 🏗️ PWA Core Components

### 1. Web App Manifest

**Purpose**: Defines how the app appears to users and how it should be launched

**Basic manifest (manifest.json):**

```json
{
  "name": "My App",
  "short_name": "MyApp",
  "description": "A progressive web app",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#007bff",
  "icons": [
    {
      "src": "icon-192x192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "icon-512x512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ]
}
```

**PHP dynamic manifest:**

```php
<?php
header('Content-Type: application/json');

$manifest = [
  'name' => 'My App',
  'short_name' => 'MyApp',
  'description' => 'A progressive web app',
  'start_url' => '/',
  'display' => 'standalone',
  'background_color' => '#ffffff',
  'theme_color' => '#007bff',
  'icons' => [
    [
      'src' => 'icon-192x192.png',
      'sizes' => '192x192',
      'type' => 'image/png'
    ],
    [
      'src' => 'icon-512x512.png',
      'sizes' => '512x512',
      'type' => 'image/png'
    ]
  ]
];

echo json_encode($manifest, JSON_PRETTY_PRINT);
?>
```

**Link to manifest in HTML:**

```html
<link rel="manifest" href="/manifest.json">
```

**PHP manifest link:**

```php
<?php
echo '<link rel="manifest" href="' . htmlspecialchars($manifestUrl) . '">';
?>
```

### 2. Service Worker

**Purpose**: Intercepts network requests, caches resources, enables offline functionality

**Basic service worker (sw.js):**

```javascript
// sw.js
const CACHE_NAME = 'my-app-v1';
const urlsToCache = [
  '/',
  '/styles.css',
  '/app.js',
  '/images/logo.png',
  '/manifest.json'
];

// Install service worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(urlsToCache))
  );
});

// Fetch resources
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => response || fetch(event.request))
  );
});

// Activate service worker
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

**Register service worker:**

```html
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js')
        .then((registration) => {
          console.log('ServiceWorker registration successful');
        })
        .catch((error) => {
          console.log('ServiceWorker registration failed: ', error);
        });
    });
  }
</script>
```

**PHP service worker registration:**

```php
<?php
// In your HTML template footer
echo <<<HTML
<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('{$swUrl}')
      .then(registration => console.log('ServiceWorker registered'))
      .catch(error => console.log('ServiceWorker error: ', error));
  });
}
</script>
HTML;
?>
```

### 3. HTTPS

**Purpose**: Security requirement for service workers and PWA features

**Options:**

- **Let's Encrypt**: Free SSL certificates
- **Cloudflare**: Free SSL with CDN
- **Hosting provider**: Most offer free SSL
- **Self-signed**: For development only

**PHP HTTPS detection:**

```php
<?php
// Check if using HTTPS
$isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

// Redirect HTTP to HTTPS
if (!$isHttps && !defined('DEVELOPMENT')) {
  header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit;
}

// Set HSTS header (after successful HTTPS requests)
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
?>
```

---

## 📦 PWA Caching Strategies

### 1. Cache-First (Offline-First)

**Use case**: Assets that should work offline (HTML, CSS, JS, images)

```javascript
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached response if available
        if (response) {
          return response;
        }
        // Otherwise fetch from network
        return fetch(event.request);
      })
  );
});
```

### 2. Network-First (Cache Fallback)

**Use case**: Data that should be fresh if possible, but has a fallback

```javascript
self.addEventListener('fetch', (event) => {
  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // Clone and cache the response
        const responseClone = response.clone();
        caches.open(CACHE_NAME)
          .then((cache) => cache.put(event.request, responseClone));
        return response;
      })
      .catch(() => {
        // If network fails, return cached response
        return caches.match(event.request);
      })
  );
});
```

### 3. Cache-Only

**Use case**: Assets that never change (versioned files)

```javascript
self.addEventListener('fetch', (event) => {
  if (event.request.url.includes('/static/')) {
    event.respondWith(caches.match(event.request));
  }
});
```

### 4. Network-Only

**Use case**: API requests that should always be fresh

```javascript
self.addEventListener('fetch', (event) => {
  if (event.request.url.includes('/api/')) {
    event.respondWith(fetch(event.request));
  }
});
```

### 5. Stale-While-Revalidate

**Use case**: Data that should be updated in background

```javascript
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached response immediately
        const responseClone = response.clone();
      
        // Update cache in background
        fetch(event.request)
          .then((newResponse) => {
            caches.open(CACHE_NAME)
              .then((cache) => cache.put(event.request, newResponse));
          });
      
        return responseClone;
      })
      .catch(() => fetch(event.request))
  );
});
```

---

## 📱 PWA Installation

### 1. Install Prompt

**Detect if PWA can be installed:**

```javascript
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent the mini-infobar from appearing
  e.preventDefault();
  
  // Stash the event so it can be triggered later
  deferredPrompt = e;
  
  // Show install button
  const installButton = document.getElementById('install-button');
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', () => {
      // Show the install prompt
      deferredPrompt.prompt();
    
      // Wait for the user to respond
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the install prompt');
        } else {
          console.log('User dismissed the install prompt');
        }
        deferredPrompt = null;
      });
    });
  }
});

// Check if app is already installed
window.addEventListener('appinstalled', (e) => {
  console.log('App was installed');
  // Hide install button
  const installButton = document.getElementById('install-button');
  if (installButton) {
    installButton.style.display = 'none';
  }
});
```

**PHP install button:**

```php
<?php
// In your HTML
echo <<<HTML
<button id="install-button" style="display: none;">
  Install App
</button>
<script>
// Install prompt logic here
</script>
HTML;
?>
```

### 2. Install Criteria

**For Chrome to show install prompt:**

- [ ] Registered service worker
- [ ] Web app manifest with:
  - [ ] `short_name` or `name`
  - [ ] `start_url`
  - [ ] `display` (standalone, fullscreen, or minimal-ui)
  - [ ] At least one icon (192x192 or 512x512)
- [ ] Served over HTTPS
- [ ] Site has been visited at least twice with 5+ minutes between visits

---

## 🔔 Push Notifications

### 1. Notification Permission

**Request permission:**

```javascript
function requestNotificationPermission() {
  if (!('Notification' in window)) {
    console.log('This browser does not support notifications.');
    return;
  }

  Notification.requestPermission().then((permission) => {
    if (permission === 'granted') {
      console.log('Notification permission granted.');
    } else {
      console.log('Notification permission denied.');
    }
  });
}
```

**PHP permission check:**

```php
<?php
// In your HTML
echo <<<HTML
<button onclick="requestNotificationPermission()">
  Enable Notifications
</button>
<script>
function requestNotificationPermission() {
  if (!('Notification' in window)) {
    alert('This browser does not support notifications.');
    return;
  }
  Notification.requestPermission().then(permission => {
    if (permission === 'granted') {
      alert('Notifications enabled!');
    }
  });
}
</script>
HTML;
?>
```

### 2. Display Notification

**Show a notification:**

```javascript
function showNotification(title, options = {}) {
  if (!('Notification' in window)) {
    return;
  }

  if (Notification.permission === 'granted') {
    new Notification(title, options);
  } else if (Notification.permission !== 'denied') {
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        new Notification(title, options);
      }
    });
  }
}

// Example
showNotification('Hello!', {
  body: 'This is a notification from your PWA',
  icon: '/images/icon-192x192.png',
  data: {
    url: '/notifications/1'
  }
});
```

### 3. Notification Click Handling

**Handle notification clicks:**

```javascript
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  
  if (event.notification.data.url) {
    clients.openWindow(event.notification.data.url);
  }
});
```

### 4. Push API (Advanced)

**For server-initiated notifications:**

**Service worker push listener:**

```javascript
self.addEventListener('push', (event) => {
  const data = event.data.json();
  
  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon,
      data: {
        url: data.url
      }
    })
  );
});
```

**PHP push notification (using Web Push library):**

```php
<?php
require_once 'vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Initialize WebPush
$webPush = new WebPush(['VAPID' => ['subject' => 'mailto:you@example.com',
    'publicKey' => 'YOUR_PUBLIC_KEY',
    'privateKey' => 'YOUR_PRIVATE_KEY']]);

// Send notification
$subscription = new Subscription(
    $endpoint,
    $publicKey,
    $authToken
);

$webPush->sendNotification(
    $subscription,
    json_encode([
        'title' => 'New Message',
        'body' => 'You have a new message',
        'icon' => '/images/icon-192x192.png',
        'url' => '/messages'
    ])
);
?>
```

---

## 🌐 Background Sync

**Use case**: Sync data when network becomes available

```javascript
// Register background sync
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData());
  }
});

function syncData() {
  // Get pending data from IndexedDB
  return getPendingData().then((data) => {
    if (data.length > 0) {
      // Send to server
      return fetch('/api/sync', {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
          'Content-Type': 'application/json'
        }
      });
    }
  });
}

// Trigger sync from client
function triggerSync() {
  if ('serviceWorker' in navigator && 'SyncManager' in window) {
    navigator.serviceWorker.ready.then((sw) => {
      sw.sync.register('sync-data');
    });
  }
}
```

**PHP sync endpoint:**

```php
<?php
// sync.php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Process synced data
foreach ($input as $item) {
  // Save to database
  $stmt = $pdo->prepare("INSERT INTO data (content) VALUES (?)");
  $stmt->execute([$item['content']]);
}

echo json_encode(['status' => 'success']);
?>
```

---

## 📊 PWA Analytics

### 1. Track PWA Events

**Track service worker events:**

```javascript
// In your service worker
self.addEventListener('install', () => {
  // Track installation
  trackPWAEvent('install');
});

self.addEventListener('activate', () => {
  // Track activation
  trackPWAEvent('activate');
});

self.addEventListener('fetch', () => {
  // Track cache hits/misses
});

function trackPWAEvent(eventName) {
  if (navigator.onLine) {
    fetch('/api/track-pwa', {
      method: 'POST',
      body: JSON.stringify({ event: eventName }),
      headers: { 'Content-Type': 'application/json' }
    });
  } else {
    // Queue for later
    queuePWAEvent(eventName);
  }
}
```

**PHP tracking endpoint:**

```php
<?php
// track-pwa.php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$event = $input['event'] ?? '';

// Save to database
$stmt = $pdo->prepare("INSERT INTO pwa_events (event_type, user_agent, created_at) VALUES (?, ?, NOW())");
$stmt->execute([
  $event,
  $_SERVER['HTTP_USER_AGENT'] ?? ''
]);

echo json_encode(['status' => 'tracked']);
?>
```

### 2. Track Offline Usage

**Detect offline/online:**

```javascript
window.addEventListener('offline', () => {
  trackPWAEvent('offline');
});

window.addEventListener('online', () => {
  trackPWAEvent('online');
});
```

---

## 🔧 PWA Testing

### 1. Lighthouse PWA Audit

**Run Lighthouse:**

1. Open Chrome DevTools
2. Go to Lighthouse tab
3. Select "Progressive Web App"
4. Click "Generate report"

**Or command line:**

```bash
lighthouse https://example.com --output=html --output-path=pwa-report.html --chrome-flags="--headless"
```

### 2. PWA Checklist

**Basics:**

- [ ] Web app manifest exists
- [ ] Service worker registered
- [ ] HTTPS enabled
- [ ] Responsive design
- [ ] Offline page works

**Installation:**

- [ ] Install prompt appears
- [ ] App can be installed
- [ ] App launches from home screen
- [ ] Splash screen appears
- [ ] Theme color matches app

**Offline:**

- [ ] App works offline
- [ ] Cached assets load
- [ ] Offline page shown for uncached routes
- [ ] Data syncs when back online

**Notifications:**

- [ ] Permission can be requested
- [ ] Notifications display
- [ ] Notification clicks handled

### 3. PWA Tools

- [Lighthouse](https://developers.google.com/web/tools/lighthouse) (Audit)
- [Workbox](https://developers.google.com/web/tools/workbox) (Service worker library)
- [PWA Builder](https://www.pwabuilder.com/) (Generate PWA)
- [PWACompat](https://github.com/GoogleChromeLabs/pwacompat) (Polyfills)
- [Web App Manifest Validator](https://manifest-validator.appspot.com/)

---

## 📦 PWA with PHP Frameworks

### 1. Laravel PWA

**Package:** [Laravel PWA](https://github.com/laravel-pwa/laravel-pwa)

**Installation:**

```bash
composer require laravel-pwa/laravel-pwa
php artisan vendor:publish --provider="LaravelPWA\Providers\LaravelPWAServiceProvider"
php artisan pwa:install
php artisan pwa:publish
```

**Configuration:**

```php
// config/pwa.php
return [
  'name' => 'My App',
  'short_name' => 'MyApp',
  'start_url' => '/',
  'display' => 'standalone',
  'background_color' => '#ffffff',
  'theme_color' => '#007bff',
  'icons' => [
    '192x192' => '/images/icon-192x192.png',
    '512x512' => '/images/icon-512x512.png',
  ],
];
```

### 2. Symfony PWA

**Bundle:** [Symfony PWA Bundle](https://github.com/1ma/symfony-pwa-bundle)

**Installation:**

```bash
composer require 1ma/symfony-pwa-bundle
```

### 3. WordPress PWA

**Plugins:**

- [Super Progressive Web Apps](https://wordpress.org/plugins/super-progressive-web-apps/)
- [PWA](https://wordpress.org/plugins/pwa/)
- [PWA + AMP](https://wordpress.org/plugins/pwa-amp/)

---

## 🎯 PWA Checklist

### Before Development

- [ ] HTTPS configured?
- [ ] Web app manifest created?
- [ ] Service worker planned?
- [ ] Offline strategy defined?
- [ ] Icons prepared?

### During Development

- [ ] Service worker registered?
- [ ] Assets cached?
- [ ] Offline page created?
- [ ] Manifest linked?
- [ ] Theme colors set?

### Before Release

- [ ] Lighthouse PWA score ≥90?
- [ ] Tested offline?
- [ ] Tested install prompt?
- [ ] Tested on iOS and Android?
- [ ] Tested push notifications?
- [ ] Tested background sync?

---

## 📚 Resources

### Guides

- [Google PWA Guide](https://developers.google.com/web/progressive-web-apps)
- [MDN PWA](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps)
- [Web Fundamentals: PWA](https://developers.google.com/web/fundamentals/codelabs/your-first-pwapp/)

### Tools

- [Workbox](https://developers.google.com/web/tools/workbox) (Service worker library)
- [PWA Builder](https://www.pwabuilder.com/) (Generate PWA)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse) (Audit)

### PHP-Specific

- [Laravel PWA](https://github.com/laravel-pwa/laravel-pwa)
- [Symfony PWA Bundle](https://github.com/1ma/symfony-pwa-bundle)
- [WordPress PWA Plugins](https://wordpress.org/plugins/tags/pwa/)

### Service Worker Libraries

- [Workbox](https://developers.google.com/web/tools/workbox) (Google)
- [sw-precache](https://github.com/GoogleChromeLabs/sw-precache) (Precaching)
- [sw-toolbox](https://github.com/GoogleChromeLabs/sw-toolbox) (Runtime caching)

---

## 🎯 Summary

**PWA = The best of web and native.**

- **Web app manifest**: Defines app appearance
- **Service worker**: Enables offline functionality
- **HTTPS**: Required for security
- **Installable**: Can be added to home screen
- **Offline-first**: Works without network
- **Push notifications**: Re-engage users
- **Background sync**: Sync when online

**Remember**: A PWA is just a web app with superpowers. Start with a good web app, then add PWA features.
