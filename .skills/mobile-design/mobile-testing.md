
# Mobile Web Testing (PHP/HTML)

## 🎯 The Mobile Web Testing Challenge

Mobile web apps must work:

- **Everywhere**: Different devices, browsers, screen sizes, OS versions
- **Always**: Network conditions from 5G to offline
- **For everyone**: Different user types, accessibility needs
- **Under stress**: Low memory, slow CPU, interruptions

**Core principle**: Test under **real-world conditions**, not ideal lab conditions.

---

## 📱 Testing Pyramid for Mobile Web

```
        ▲
        │   E2E Tests (UI Tests)
        │   ~10% of tests
        │   Slow, flaky, expensive
        │
        ├─ Integration Tests
        │   ~20% of tests
        │   Test component interactions, API calls
        │
        ├─ Unit Tests
        │   ~70% of tests
        │   Fast, reliable, cheap
        │
        ▼
```

**Invert the pyramid for mobile web:**

- **70% Unit Tests**: PHP functions, JavaScript functions, business logic
- **20% Integration Tests**: PHP + HTML interactions, form submissions
- **10% E2E Tests**: Critical user journeys across pages

---

## 🧪 Unit Testing

### PHP Unit Testing

**Setup with PHPUnit:**

```bash
composer require --dev phpunit/phpunit
```

**phpunit.xml configuration:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="tests/bootstrap.php">
    <testsuites>
        <testsuite name="Application Test Suite">
            <directory>tests/</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist processUncoveredFilesFromWhitelist="true">
            <directory suffix=".php">src/</directory>
        </whitelist>
    </filter>
</phpunit>
```

**Example test:**

```php
<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User('John', 'john@example.com');
      
        $this->assertEquals('John', $user->getName());
        $this->assertEquals('john@example.com', $user->getEmail());
    }
  
    public function testUserValidation()
    {
        $user = new User('', 'invalid-email');
      
        $this->assertFalse($user->isValid());
        $this->assertContains('Name is required', $user->getErrors());
        $this->assertContains('Email is invalid', $user->getErrors());
    }
}
```

**Run tests:**

```bash
./vendor/bin/phpunit
```

### JavaScript Unit Testing

**Setup with Jest:**

```bash
npm install --save-dev jest @babel/preset-env
```

**package.json:**

```json
{
  "scripts": {
    "test": "jest"
  },
  "babel": {
    "presets": ["@babel/preset-env"]
  }
}
```

**Example test:**

```javascript
// sum.js
function sum(a, b) {
  return a + b;
}
module.exports = sum;

// sum.test.js
test('adds 1 + 2 to equal 3', () => {
  expect(sum(1, 2)).toBe(3);
});

test('adds negative numbers', () => {
  expect(sum(-1, -2)).toBe(-3);
});
```

**Run tests:**

```bash
npm test
```

---

## 🔗 Integration Testing

### PHP Integration Testing

**Test form handling:**

```php
<?php
class FormHandlerTest extends TestCase
{
    public function testFormSubmission()
    {
        // Simulate POST request
        $_POST = [
            'name' => 'John',
            'email' => 'john@example.com'
        ];
      
        // Include form handler
        ob_start();
        include 'form-handler.php';
        $output = ob_get_clean();
      
        // Assert database was updated
        $stmt = $this->pdo->query("SELECT * FROM users WHERE email = 'john@example.com'");
        $user = $stmt->fetch();
      
        $this->assertNotFalse($user);
        $this->assertEquals('John', $user['name']);
    }
}
```

**Test API endpoints:**

```php
<?php
class ApiTest extends TestCase
{
    public function testUserApi()
    {
        // Create test user
        $this->createTestUser();
      
        // Make request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/users/1';
      
        ob_start();
        include 'api.php';
        $response = ob_get_clean();
      
        // Assert response
        $data = json_decode($response, true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
    }
}
```

### JavaScript Integration Testing

**Test DOM manipulation:**

```javascript
// button.test.js
import { fireEvent, getByText } from '@testing-library/dom';

describe('Button', () => {
  it('triggers action when clicked', () => {
    // Create DOM
    const div = document.createElement('div');
    div.innerHTML = `<button id="my-button">Click me</button>`;
    document.body.appendChild(div);
  
    // Add event listener
    const handleClick = jest.fn();
    document.getElementById('my-button').addEventListener('click', handleClick);
  
    // Trigger click
    fireEvent.click(getByText('Click me'));
  
    // Assert
    expect(handleClick).toHaveBeenCalled();
  
    // Cleanup
    document.body.removeChild(div);
  });
});
```

---

## 🌐 E2E Testing

### 1. Cypress

**Setup:**

```bash
npm install cypress --save-dev
npx cypress open
```

**Example test:**

```javascript
// cypress/integration/login.spec.js
describe('Login', () => {
  it('logs in successfully', () => {
    cy.visit('/login');
  
    cy.get('#email').type('user@example.com');
    cy.get('#password').type('password');
    cy.get('button[type="submit"]').click();
  
    cy.url().should('include', '/dashboard');
    cy.contains('Welcome').should('be.visible');
  });
  
  it('shows error on invalid login', () => {
    cy.visit('/login');
  
    cy.get('#email').type('invalid@example.com');
    cy.get('#password').type('wrong');
    cy.get('button[type="submit"]').click();
  
    cy.contains('Invalid credentials').should('be.visible');
  });
});
```

**Run tests:**

```bash
npx cypress run
```

**PHP integration with Cypress:**

```php
<?php
// In your test setup, you can seed the database
// cypress/support/index.js
before(() => {
  cy.request('POST', '/api/test/setup');
});

after(() => {
  cy.request('POST', '/api/test/teardown');
});
?>
```

### 2. Selenium WebDriver

**Setup:**

```bash
composer require --dev php-webdriver/webdriver
```

**Example test:**

```php
<?php
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

require_once('vendor/autoload.php');

$host = 'http://localhost:4444/wd/hub';
$driver = RemoteWebDriver::create($host);

try {
    $driver->get('https://example.com/login');
  
    $email = $driver->findElement(WebDriverBy::id('email'));
    $email->sendKeys('user@example.com');
  
    $password = $driver->findElement(WebDriverBy::id('password'));
    $password->sendKeys('password');
  
    $submit = $driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'));
    $submit->click();
  
    // Assert URL changed
    $this->assertStringContainsString('/dashboard', $driver->getCurrentURL());
  
} finally {
    $driver->quit();
}
```

### 3. Playwright

**Setup:**

```bash
npm install --save-dev @playwright/test
```

**Example test:**

```javascript
// tests/login.spec.js
const { test, expect } = require('@playwright/test');

test('login test', async ({ page }) => {
  await page.goto('https://example.com/login');
  
  await page.fill('#email', 'user@example.com');
  await page.fill('#password', 'password');
  await page.click('button[type="submit"]');
  
  await expect(page).toHaveURL(/dashboard/);
  await expect(page.locator('text=Welcome')).toBeVisible();
});
```

**Run tests:**

```bash
npx playwright test
```

---

## 📱 Platform-Specific Testing

### 1. Browser Testing

**Test on:**

- Chrome (Android, Desktop)
- Safari (iOS, macOS)
- Firefox (Android, Desktop)
- Samsung Internet
- Edge
- Opera

**BrowserStack:**

```bash
# Run tests on BrowserStack
npx cypress run --browser browserstack:chrome@latest:Windows 10
```

**Sauce Labs:**

```bash
# Run tests on Sauce Labs
npx cypress run --browser sauce:chrome@latest:Windows 10
```

### 2. Device Testing

**Test on:**

- iPhone (various models)
- iPad (various models)
- Android phones (various manufacturers)
- Android tablets
- Kindle Fire

**Real Device Testing:**

- Use actual devices (most accurate)
- Use BrowserStack/Sauce Labs (cloud devices)
- Use Chrome DevTools device emulation

**Chrome DevTools Device Mode:**

1. Open DevTools (F12)
2. Click device icon (📱) or Ctrl+Shift+M
3. Select device preset
4. Test responsive design and touch

### 3. Network Condition Testing

**Chrome DevTools Network Throttling:**

1. Open DevTools (F12)
2. Go to Network tab
3. Select throttling preset:

- No throttling (default)
- Slow 3G
- Fast 3G
- Offline

**Cypress network testing:**

```javascript
// Simulate slow network
cy.intercept('*', (req) => {
  req.continue((res) => {
    res.setDelay(1000); // 1 second delay
  });
});

// Simulate offline
cy.intercept('*', (req) => {
  req.destroy(); // Block all requests
});
```

---

## 🔍 Testing Different Conditions

### 1. Viewport Testing

**Test different screen sizes:**

```javascript
// Cypress
const sizes = [
  [375, 667],  // iPhone SE
  [390, 844],  // iPhone 12/13/14
  [414, 896],  // iPhone 12/13/14 Pro Max
  [768, 1024], // iPad
  [1024, 768], // iPad landscape
  [1920, 1080] // Desktop
];

sizes.forEach(([width, height]) => {
  it(`works on ${width}x${height}`, () => {
    cy.viewport(width, height);
    cy.visit('/');
    // Your assertions
  });
});
```

### 2. Orientation Testing

**Test portrait and landscape:**

```javascript
// Cypress
it('works in portrait', () => {
  cy.viewport(375, 667); // Portrait
  cy.visit('/');
  // Your assertions
});

it('works in landscape', () => {
  cy.viewport(667, 375); // Landscape
  cy.visit('/');
  // Your assertions
});
```

### 3. Accessibility Testing

**axe-core:**

```bash
npm install --save-dev axe-core @cypress/webpack-dev-server
```

```javascript
// cypress/support/index.js
import 'cypress-axe';

// In your tests
it('has no accessibility violations', () => {
  cy.visit('/');
  cy.injectAxe();
  cy.checkA10y();
});
```

**Manual accessibility checks:**

- Keyboard navigation (Tab, Shift+Tab)
- Screen reader testing (VoiceOver, TalkBack, NVDA)
- Color contrast (use [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/))
- Focus indicators
- ARIA labels

### 4. Performance Testing

**Lighthouse:**

```bash
# Run Lighthouse
lighthouse https://example.com --output=html --output-path=report.html

# Run Lighthouse in CI
lighthouse https://example.com --chrome-flags="--headless" --output=json > report.json
```

**Cypress Lighthouse:**

```bash
npm install --save-dev cypress-lighthouse
```

```javascript
// In your test
cy.lighthouse();
```

---

## 🚀 CI/CD Testing

### GitHub Actions Example

```yaml
name: Mobile Web CI

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: ['8.1', '8.2']
        node: ['16']
    steps:
      - uses: actions/checkout@v2
    
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}
    
      - name: Setup Node
        uses: actions/setup-node@v2
        with:
          node-version: ${{ matrix.node }}
    
      - name: Install PHP dependencies
        run: composer install
    
      - name: Install Node dependencies
        run: npm install
    
      - name: Run PHPUnit tests
        run: ./vendor/bin/phpunit
    
      - name: Run Jest tests
        run: npm test
    
      - name: Run Cypress tests
        uses: cypress-io/github-action@v2
        with:
          start: npm start
          wait-on: 'http://localhost:3000'
    
      - name: Run Lighthouse
        run: |
          npm install -g lighthouse
          lighthouse http://localhost:3000 --output=json > lighthouse.json
          npm install -g lighthouse-score-calculator
          lighthouse-score lighthouse.json
```

### GitLab CI Example

```yaml
stages:
  - test
  - deploy

php_unit:
  stage: test
  image: php:8.1
  script:
    - apt-get update && apt-get install -y git unzip
    - curl -sS https://getcomposer.org/installer | php
    - php composer.phar install
    - ./vendor/bin/phpunit

javascript:
  stage: test
  image: node:16
  script:
    - npm install
    - npm test

cypress:
  stage: test
  image: cypress/included:8.3.0
  script:
    - npm start &
    - npx wait-on http://localhost:3000
    - npx cypress run

lighthouse:
  stage: test
  image: node:16
  script:
    - npm install -g lighthouse
    - lighthouse http://localhost:3000 --output=json > lighthouse.json
    - npm install -g lighthouse-score-calculator
    - lighthouse-score lighthouse.json
```

---

## 🎯 Testing Checklist

### Unit Tests

- [ ] All PHP functions tested?
- [ ] All JavaScript functions tested?
- [ ] Edge cases covered?
- [ ] Error cases covered?
- [ ] Mock external dependencies?
- [ ] Tests run in &lt;10s?

### Integration Tests

- [ ] PHP + HTML interactions tested?
- [ ] Form submissions tested?
- [ ] API endpoints tested?
- [ ] Database interactions tested?
- [ ] Error handling tested?
- [ ] Loading states tested?

### E2E Tests

- [ ] Critical user journeys covered?
- [ ] Login flow tested?
- [ ] Onboarding tested?
- [ ] Core features tested?
- [ ] Error recovery tested?
- [ ] Offline mode tested?

### Platform Tests

- [ ] Chrome tested?
- [ ] Safari tested?
- [ ] Firefox tested?
- [ ] Edge tested?
- [ ] Different screen sizes tested?
- [ ] Different orientations tested?

### Condition Tests

- [ ] Offline mode tested?
- [ ] Slow network tested?
- [ ] Different permissions tested?
- [ ] Accessibility tested?

### Before Release

- [ ] All tests passing?
- [ ] Tested on real devices?
- [ ] Tested on CI?
- [ ] Performance tested?
- [ ] Security tested?

---

## 📊 Test Coverage

**Target coverage:**

- **PHP Unit tests**: 80%+ of business logic
- **JavaScript Unit tests**: 80%+ of functions
- **Integration tests**: 70%+ of component interactions
- **E2E tests**: Critical user journeys only

**Coverage tools:**

- **PHP**: PHPUnit with Xdebug
- **JavaScript**: Jest with Istanbul

**PHPUnit coverage:**

```bash
./vendor/bin/phpunit --coverage-html coverage
```

**Jest coverage:**

```bash
npx jest --coverage
```

---

## 🔐 Security Testing

### 1. Static Analysis

**PHP:**

```bash
composer require --dev phpstan/phpstan
./vendor/bin/phpstan analyse src --level=5

composer require --dev vimeo/psalm
./vendor/bin/psalm --init
./vendor/bin/psalm
```

**JavaScript:**

```bash
npm install --save-dev eslint eslint-plugin-security
npx eslint . --ext .js
```

**Check for:**

- SQL injection vulnerabilities
- XSS vulnerabilities
- CSRF vulnerabilities
- Hardcoded secrets
- Insecure file operations
- Missing input validation

### 2. Dynamic Analysis

**Tools:**

- **OWASP ZAP**: Automated security testing
- **Burp Suite**: Manual security testing
- **SQLMap**: SQL injection testing
- **XSS Hunter**: XSS detection

**OWASP ZAP:**

```bash
# Run ZAP scan
docker run -t owasp/zap2docker zap-baseline.py -t https://example.com -r report.html
```

### 3. Penetration Testing

**Test for:**

- SQL injection (', ", UNION, etc.)
- XSS (, onerror=, etc.)
- CSRF (missing tokens, state-changing GET requests)
- Authentication bypass (weak passwords, session fixation)
- Authorization bypass (IDOR, privilege escalation)
- File upload vulnerabilities
- Server misconfigurations

---

## 📚 Resources

### PHP Testing

- [PHPUnit](https://phpunit.de/)
- [PHPStan](https://phpstan.org/) (Static analysis)
- [Psalm](https://psalm.dev/) (Static analysis)
- [Pest](https://pestphp.com/) (Alternative to PHPUnit)
- [Codeception](https://codeception.com/) (Acceptance testing)

### JavaScript Testing

- [Jest](https://jestjs.io/)
- [Cypress](https://www.cypress.io/)
- [Playwright](https://playwright.dev/)
- [Selenium](https://www.selenium.dev/)
- [Puppeteer](https://pptr.dev/)

### E2E Testing

- [Cypress](https://www.cypress.io/)
- [Playwright](https://playwright.dev/)
- [Selenium](https://www.selenium.dev/)
- [WebdriverIO](https://webdriver.io/)

### Tools

- [Lighthouse](https://developers.google.com/web/tools/lighthouse)
- [BrowserStack](https://www.browserstack.com/)
- [Sauce Labs](https://saucelabs.com/)
- [OWASP ZAP](https://www.zaproxy.org/)

---

## 🎯 Summary

**Test smart, not hard.**

- **Unit tests**: Fast, reliable, comprehensive
- **Integration tests**: Test interactions, not UI
- **E2E tests**: Critical journeys only
- **Test on real devices**: Emulators are not enough
- **Test real conditions**: Offline, slow network, different browsers
- **Automate everything**: CI/CD is your friend

**Remember**: A bug in production costs 100x more than a bug caught in testing.
