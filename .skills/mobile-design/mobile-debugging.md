
# Mobile Web Debugging (PHP/HTML)

## 🎯 The Mobile Web Debugging Challenge

Debugging mobile web apps is challenging because:

- **No direct access**: Can't easily inspect DOM on mobile devices
- **Different environments**: iOS, Android, various browsers
- **Network variability**: Hard to reproduce network issues
- **Touch interactions**: Hard to debug touch events
- **Performance issues**: Hard to profile on real devices

**Core principle**: Use the right tool for the right problem.

---

## 🛠️ Debugging Tools Overview

| Tool                 | Platform         | Use Case              | Access             |
| -------------------- | ---------------- | --------------------- | ------------------ |
| Chrome DevTools      | Android, Desktop | DOM, CSS, JS, Network | Chrome on desktop  |
| Safari Web Inspector | iOS, macOS       | DOM, CSS, JS, Network | Safari on macOS    |
| Firefox DevTools     | Android, Desktop | DOM, CSS, JS, Network | Firefox on desktop |
| Edge DevTools        | Android, Desktop | DOM, CSS, JS, Network | Edge on desktop    |
| Vorlon.js            | Cross-platform   | Remote debugging      | Open source        |
| Weinre               | Cross-platform   | Remote debugging      | Apache Cordova     |
| BrowserStack         | Cloud            | Cross-browser testing | Paid               |
| Sauce Labs           | Cloud            | Cross-browser testing | Paid               |

---

## 🌐 Remote Debugging

### 1. Chrome DevTools for Android

**Method 1: USB Debugging**

1. **Enable USB debugging on Android:**

- Settings → About phone → Tap "Build number" 7 times
- Settings → Developer options → Enable "USB debugging"

2. **Connect device to computer:**

```bash
   # Check device is connected
   adb devices
  
   # Forward port
   adb forward tcp:9222 localhost:9222
```

3. **Open Chrome on device:**

- Open Chrome
- Go to your site

4. **Open DevTools on desktop:**

- Open Chrome on desktop
- Go to `chrome://inspect`
- Click "Inspect" next to your device

**Method 2: WiFi Debugging (Android 11+)**

1. **Enable WiFi debugging:**

- Settings → Developer options → Enable "Wireless debugging"
- Note the pairing code

2. **Pair device:**

```bash
   adb pair PHONE_IP:PAIRING_PORT PAIRING_CODE
```

3. **Connect:**

```bash
   adb connect PHONE_IP:DEBUG_PORT
```

4. **Open DevTools:**

- Go to `chrome://inspect`
- Click "Inspect"

### 2. Safari Web Inspector for iOS

**Method 1: macOS + USB**

1. **Enable Web Inspector on iOS:**

- Settings → Safari → Advanced → Enable "Web Inspector"

2. **Connect device to Mac:**

- Use USB cable
- Trust the computer on device

3. **Open Safari on Mac:**

- Enable Develop menu: Safari → Preferences → Advanced → "Show Develop menu"

4. **Open Web Inspector:**

- Develop → \[Device Name\] → \[Your Site\]

**Method 2: iOS Simulator**

1. **Open Simulator:**

```bash
   open -a Simulator
```

2. **Open Safari in Simulator:**

- Go to your site

3. **Open Web Inspector on Mac:**

- Develop → Simulator → \[Your Site\]

### 3. Firefox DevTools for Android

**Method 1: USB Debugging**

1. **Enable USB debugging on Android** (same as Chrome)
2. **Connect device:**

```bash
   adb forward tcp:6000 tcp:6000
```

3. **Open Firefox on device:**

- Go to `about:config`
- Set `devtools.debugger.remote-enabled` to `true`
- Set `devtools.chrome.enabled` to `true`
- Set `devtools.debugger.prompt-connection` to `false`

4. **Open DevTools on desktop:**

- Open Firefox on desktop
- Go to `about:debugging`
- Click "Connect to a device"
- Enter `localhost:6000`

### 4. Cross-Platform Remote Debugging

#### Vorlon.js

**Setup:**

```bash
npm install -g vorlon
vorlon
```

**Client-side:**

```html
<script src="http://localhost:1337/vorlon.js"></script>
```

**Access:**

- Open `http://localhost:1337` in desktop browser
- See connected devices and debug

#### Weinre (Web Inspector Remote)

**Setup:**

```bash
npm install -g weinre
weinre --httpPort 8080 --boundHost -all-
```

**Client-side:**

```html
<script src="http://localhost:8080/target/target-script-min.js#anonymous"></script>
```

**Access:**

- Open `http://localhost:8080/client/` in desktop browser
- See connected devices and debug

---

## 📱 Device Emulation

### 1. Chrome DevTools Device Mode

**Steps:**

1. Open DevTools (F12 or Ctrl+Shift+I)
2. Click device icon (📱) or Ctrl+Shift+M
3. Select device preset
4. Test responsive design

**Features:**

- Device presets (iPhone, iPad, Android, etc.)
- Custom device dimensions
- Device pixel ratio
- User agent switching
- Touch emulation
- Network throttling
- Geolocation override
- Orientation (portrait/landscape)

**PHP device detection:**

```php
<?php
// Detect if request is from mobile device
function isMobile() {
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
  
  $mobileAgents = [
    'Mobile', 'Android', 'iPhone', 'iPad', 'iPod',
    'BlackBerry', 'Windows Phone', 'Opera Mini', 'IEMobile'
  ];
  
  foreach ($mobileAgents as $agent) {
    if (strpos($userAgent, $agent) !== false) {
      return true;
    }
  }
  
  return false;
}

// Detect device type
function getDeviceType() {
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
  
  if (strpos($userAgent, 'iPad') !== false) {
    return 'tablet';
  }
  
  if (strpos($userAgent, 'Mobile') !== false ||
      strpos($userAgent, 'Android') !== false ||
      strpos($userAgent, 'iPhone') !== false) {
    return 'phone';
  }
  
  return 'desktop';
}

// Usage
if (isMobile()) {
  // Load mobile-specific content
}

$device = getDeviceType();
if ($device === 'phone') {
  // Phone-specific optimizations
}
?>
```

### 2. Firefox Responsive Design Mode

**Steps:**

1. Open DevTools (F12 or Ctrl+Shift+I)
2. Click responsive design icon (📱)
3. Select device preset
4. Test responsive design

**Features:**

- Device presets
- Custom dimensions
- Pixel ratio
- Touch simulation
- Network throttling

### 3. Safari Responsive Design Mode

**Steps:**

1. Open Safari
2. Enable Develop menu: Safari → Preferences → Advanced → "Show Develop menu"
3. Develop → Enter Responsive Design Mode
4. Select device preset

---

## 🐛 Common Debugging Scenarios

### 1. Layout Issues

**Problem**: Layout looks wrong on mobile

**Debugging steps:**

1. Open DevTools device mode
2. Select device preset
3. Inspect element (right-click → Inspect)
4. Check CSS in Styles panel
5. Check computed styles
6. Check box model
7. Check for overflow issues

**Common issues:**

- Missing viewport meta tag
- Fixed widths
- Missing flexbox/grid
- Overflow: hidden
- Position: absolute/relative issues

**PHP layout debug helper:**

```php
<?php
// Add debug outline to all elements
function debugLayout($enabled = false) {
  if (!$enabled) return;
  
  echo <<<HTML
<style>
  * { outline: 1px solid red !important; }
  body { outline: none !important; }
</style>
HTML;
}

// Usage (add ?debug=layout to URL)
if (isset($_GET['debug']) && $_GET['debug'] === 'layout') {
  debugLayout(true);
}
?>
```

### 2. Touch Event Issues

**Problem**: Touch events not working

**Debugging steps:**

1. Check if touch events are supported
2. Check for event listeners
3. Check for event propagation issues
4. Check for passive event listeners

**JavaScript debug:**

```javascript
// Check touch support
console.log('Touch supported:', 'ontouchstart' in window);

// Log touch events
document.addEventListener('touchstart', (e) => {
  console.log('Touch start:', e);
  console.log('Touches:', e.touches);
  console.log('Target:', e.target);
});

document.addEventListener('touchend', (e) => {
  console.log('Touch end:', e);
});

document.addEventListener('touchmove', (e) => {
  console.log('Touch move:', e);
});

// Check for existing listeners
const element = document.getElementById('my-element');
console.log('Event listeners:', getEventListeners(element));
```

**PHP touch event debug:**

```php
<?php
// Add touch debug script
echo <<<HTML
<script>
console.log('Touch supported:', 'ontouchstart' in window);
document.addEventListener('touchstart', (e) => console.log('Touch:', e));
</script>
HTML;
?>
```

### 3. Network Issues

**Problem**: App not working on slow network or offline

**Debugging steps:**

1. Open DevTools → Network tab
2. Enable throttling (Slow 3G, Fast 3G, Offline)
3. Reload page
4. Check network requests
5. Check service worker (Application → Service Workers)
6. Check cache (Application → Cache Storage)

**Service worker debug:**

```javascript
// Check if service worker is registered
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then((registrations) => {
    console.log('Service Workers:', registrations);
  
    registrations.forEach((reg) => {
      console.log('SW URL:', reg.active?.scriptURL);
      reg.active?.addEventListener('fetch', (e) => {
        console.log('SW Fetch:', e.request.url);
      });
    });
  });
}

// Check cache
caches.keys().then((cacheNames) => {
  cacheNames.forEach((cacheName) => {
    caches.open(cacheName).then((cache) => {
      cache.keys().then((keys) => {
        console.log(`Cache ${cacheName}:`, keys);
      });
    });
  });
});
```

**PHP network debug:**

```php
<?php
// Log network requests
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all requests
file_put_contents(
  'debug.log',
  date('Y-m-d H:i:s') . " - " . $_SERVER['REQUEST_URI'] . "\n",
  FILE_APPEND
);

// Check if online
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
  // Not AJAX, log full request
}
?>
```

### 4. Performance Issues

**Problem**: App is slow on mobile

**Debugging steps:**

1. Open DevTools → Performance tab
2. Click record
3. Perform actions
4. Stop recording
5. Analyze timeline

**Common performance issues:**

- Large images
- Render-blocking CSS/JS
- Too many DOM elements
- Expensive CSS properties
- Heavy JavaScript
- No lazy loading

**PHP performance debug:**

```php
<?php
// Measure execution time
$start = microtime(true);

// ... your code ...

$end = microtime(true);
$executionTime = $end - $start;

// Log slow requests
if ($executionTime > 1) { // >1 second
  error_log("Slow request: " . $_SERVER['REQUEST_URI'] . " took " . round($executionTime, 3) . "s");
}

// Memory usage
echo "Memory usage: " . memory_get_usage() . " bytes";
echo "Peak memory: " . memory_get_peak_usage() . " bytes";
?>
```

**JavaScript performance debug:**

```javascript
// Measure function execution time
function measureTime(fn) {
  const start = performance.now();
  const result = fn();
  const end = performance.now();
  console.log(`${fn.name} took ${end - start}ms`);
  return result;
}

// Usage
measureTime(() => {
  // Your code
});

// Memory usage
console.log('Memory:', performance.memory);
```

### 5. Form Issues

**Problem**: Form not submitting correctly on mobile

**Debugging steps:**

1. Check form HTML
2. Check form validation
3. Check JavaScript event listeners
4. Check for keyboard issues
5. Check for touch issues

**Form debug:**

```javascript
// Log form submission
document.querySelector('form').addEventListener('submit', (e) => {
  e.preventDefault();
  console.log('Form submitted');
  console.log('Form data:', new FormData(e.target));
  
  // Check validation
  if (!e.target.checkValidity()) {
    console.log('Form invalid');
    console.log('Validation message:', e.target.reportValidity());
  }
});

// Check input events
document.querySelectorAll('input, textarea, select').forEach((input) => {
  input.addEventListener('focus', (e) => console.log('Focus:', e.target));
  input.addEventListener('blur', (e) => console.log('Blur:', e.target));
  input.addEventListener('change', (e) => console.log('Change:', e.target));
  input.addEventListener('input', (e) => console.log('Input:', e.target));
});

// Check keyboard
document.addEventListener('keydown', (e) => {
  console.log('Key down:', e.key, e.code);
});
```

**PHP form debug:**

```php
<?php
// Log form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  error_log('Form submitted: ' . print_r($_POST, true));
  error_log('Files: ' . print_r($_FILES, true));
  
  // Check for empty fields
  foreach ($_POST as $key => $value) {
    if (empty($value)) {
      error_log("Empty field: {$key}");
    }
  }
}

// Debug form HTML
function debugForm($html) {
  // Add data attributes for debugging
  $html = str_replace('<form', '<form data-debug="form"', $html);
  $html = preg_replace('/<input/i', '<input data-debug="input"', $html);
  $html = preg_replace('/<textarea/i', '<textarea data-debug="textarea"', $html);
  $html = preg_replace('/<select/i', '<select data-debug="select"', $html);
  return $html;
}
?>
```

### 6. Console Issues

**Problem**: Console.log not working on mobile

**Solutions:**

**Method 1: Remote console**

```javascript
// Send logs to server
function remoteLog(message, data = {}) {
  if (navigator.onLine) {
    fetch('/api/log', {
      method: 'POST',
      body: JSON.stringify({ message, data, url: location.href }),
      headers: { 'Content-Type': 'application/json' }
    });
  } else {
    // Queue for later
    localStorage.setItem('pendingLogs', JSON.stringify({
      message,
      data,
      url: location.href,
      timestamp: Date.now()
    }));
  }
}

// Override console
console.log = (...args) => {
  remoteLog('LOG', { args });
  console._log(...args);
};
console.error = (...args) => {
  remoteLog('ERROR', { args });
  console._error(...args);
};
```

**PHP log endpoint:**

```php
<?php
// log.php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Save to file
file_put_contents(
  'console.log',
  date('Y-m-d H:i:s') . " - " . ($input['message'] ?? '') . " - " . 
  print_r($input['data'] ?? [], true) . "\n",
  FILE_APPEND
);

echo json_encode(['status' => 'logged']);
?>
```

**Method 2: Alert fallback**

```javascript
// Fallback to alert for mobile
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
  window.console = {
    log: (message) => alert('LOG: ' + message),
    error: (message) => alert('ERROR: ' + message),
    warn: (message) => alert('WARN: ' + message),
    info: (message) => alert('INFO: ' + message)
  };
}
```

---

## 📊 Debugging Workflow

### Step 1: Reproduce the Issue

- Identify the exact steps to reproduce
- Note the device, browser, OS version
- Note the network conditions
- Note any user actions

### Step 2: Isolate the Problem

- Is it a layout issue?
- Is it a JavaScript issue?
- Is it a PHP issue?
- Is it a network issue?
- Is it a performance issue?

### Step 3: Use the Right Tool

- **Layout**: DevTools Elements/Styles panels
- **JavaScript**: DevTools Console/Sources panels
- **Network**: DevTools Network panel
- **Performance**: DevTools Performance panel
- **PHP**: Error logs, Xdebug

### Step 4: Fix and Test

- Make the fix
- Test on the same device/browser
- Test on other devices/browsers
- Verify the fix doesn't break anything else

### Step 5: Document

- Document the issue and fix
- Update any relevant documentation
- Add tests to prevent regression

---

## 🎯 Debugging Checklist

### Before Debugging

- [ ] Issue reproduced?
- [ ] Steps to reproduce documented?
- [ ] Device/browser/OS noted?
- [ ] Network conditions noted?
- [ ] User actions noted?

### During Debugging

- [ ] Right tool selected?
- [ ] Console open?
- [ ] Network tab open?
- [ ] Device mode enabled?
- [ ] Throttling enabled?

### After Debugging

- [ ] Issue fixed?
- [ ] Fix tested on original device?
- [ ] Fix tested on other devices?
- [ ] No regressions?
- [ ] Documented?

---

## 📚 Resources

### Tools

- [Chrome DevTools](https://developer.chrome.com/docs/devtools/)
- [Safari Web Inspector](https://developer.apple.com/library/archive/documentation/AppleApplications/Conceptual/Safari_Developer_Guide/Articles/InspectingWebsitesWithWebInspector.html)
- [Firefox DevTools](https://developer.mozilla.org/en-US/docs/Tools)
- [Vorlon.js](http://vorlonjs.io/)
- [Weinre](https://people.apache.org/~pmuellr/weinre-docs/latest/)
- [BrowserStack](https://www.browserstack.com/)
- [Sauce Labs](https://saucelabs.com/)

### Guides

- [Google: Debugging Mobile Web](https://developers.google.com/web/tools/chrome-devtools)
- [MDN: Debugging](https://developer.mozilla.org/en-US/docs/Tools/Debugger)
- [Chrome DevTools Tips](https://developer.chrome.com/docs/devtools/tips/)

### PHP Debugging

- [Xdebug](https://xdebug.org/) (PHP debugger)
- [PHP Debug Bar](https://github.com/barryvdh/laravel-debugbar) (Laravel)
- [Whoops](https://github.com/filp/whoops) (Better error handling)
- [Monolog](https://github.com/Seldaek/monolog) (Logging)

### JavaScript Debugging

- [Chrome DevTools](https://developer.chrome.com/docs/devtools/javascript/)
- [Safari Web Inspector](https://developer.apple.com/library/archive/documentation/AppleApplications/Conceptual/Safari_Developer_Guide/Articles/InspectingWebsitesWithWebInspector.html)
- [Firefox Debugger](https://developer.mozilla.org/en-US/docs/Tools/Debugger)

---

## 🎯 Summary

**Debugging mobile web apps requires the right tools and approach.**

- **Use remote debugging**: Chrome DevTools, Safari Web Inspector
- **Emulate devices**: Device mode in DevTools
- **Check common issues**: Layout, touch, network, performance, forms
- **Log everything**: Console, server logs, error tracking
- **Test on real devices**: Emulators are not enough
- **Reproduce first**: Can't fix what you can't reproduce

**Remember**: The best debuggers are the ones that prevent bugs in the first place.
