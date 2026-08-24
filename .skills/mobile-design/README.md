
# Mobile Design Skill (PHP/HTML)

This skill provides comprehensive guidance for building **mobile-first web applications** using PHP, HTML, CSS, and JavaScript. It focuses on Progressive Web Apps (PWA), responsive design, touch psychology, performance optimization, and testing strategies specifically for mobile web development.

## 📚 Skill Structure

### Core Files

| File                                                      | Purpose                                               | Priority         |
| --------------------------------------------------------- | ----------------------------------------------------- | ---------------- |
| [`SKILL.md`](SKILL.md)                                   | Main skill instructions and workflow                  | ⭐ Required      |
| [`mobile-design-thinking.md`](mobile-design-thinking.md) | Anti-memorization, contextual thinking for mobile web | ⭐⭐ CRITICAL    |
| [`touch-psycology.md`](touch-psycology.md)             | Fitts' Law, gestures, thumb zone for web              | ⭐⭐ CRITICAL    |
| [`mobile-performance.md`](mobile-performance.md)         | Web performance optimization (Core Web Vitals)        | ⭐⭐ CRITICAL    |
| [`pwa-fundamentals.md`](pwa-fundamentals.md)             | Service workers, manifest, offline caching            | ⭐⭐ Recommended |
| [`mobile-navigation.md`](mobile-navigation.md)           | Navigation patterns for mobile web                    | ⭐ Recommended   |
| [`mobile-testing.md`](mobile-testing.md)                 | Testing strategies for mobile web                     | ⭐ Recommended   |
| [`mobile-debugging.md`](mobile-debugging.md)             | Debugging mobile web issues                           | ⭐ Recommended   |

### When to Use This Skill

Load this skill when working on:

- ✅ Mobile-first responsive websites
- ✅ Progressive Web Apps (PWA)
- ✅ PHP/HTML mobile web applications
- ✅ Touch-optimized web interfaces
- ✅ Mobile web performance optimization
- ✅ Responsive design implementation

### When NOT to Use This Skill

Do NOT load this skill for:

- ❌ Native iOS/Android app development (Swift, Kotlin, React Native, Flutter)
- ❌ Desktop-only applications
- ❌ Backend API development (unless it's for a mobile web frontend)

---

## 🎯 Quick Start

### 1. Load the Skill

When a user requests mobile web development, load this skill:

```
Load mobile-design skill
```

### 2. Ask Critical Questions

Before starting any mobile web project, ask:

- **Target Devices**: "Phones, tablets, or both?"
- **Framework**: "Vanilla PHP/HTML, Laravel, Symfony, WordPress, or other?"
- **Type**: "Responsive site or Progressive Web App (PWA)?"
- **Offline Support**: "Does this need to work offline?"
- **Performance Budget**: "What's the target load time?"

### 3. Complete the Checkpoint

Before writing any code, complete the mandatory checkpoint:

```
🧠 CHECKPOINT:

Target Devices: [ Phones / Tablets / Both ]
Framework: [ Vanilla PHP/HTML / Laravel / Symfony / WordPress / Other ]
Type: [ Responsive Site / PWA / Hybrid ]
Files Read: [ List the skill files you've read ]

3 Principles I Will Apply:
1. Mobile-first CSS with media queries
2. 48px touch targets, thumb zone for primary CTAs
3. Optimized images with srcset and WebP

Anti-Patterns I Will Avoid:
1. Fixed widths -> Flexible layouts
2. Large unoptimized images -> Responsive images
3. No viewport meta -> Always include viewport tag
```

### 4. Read Relevant Files

Read the **CRITICAL** files first:

1. `mobile-design-thinking.md` - Forces contextual thinking
2. `touch-psycology.md` - Touch interaction principles
3. `mobile-performance.md` - Performance optimization

Then read additional files based on your project needs:

- `pwa-fundamentals.md` - For Progressive Web Apps
- `mobile-navigation.md` - For navigation patterns
- `mobile-testing.md` - For testing strategies
- `mobile-debugging.md` - For debugging techniques

---

## 📋 Implementation Checklists

### Pre-Development Checklist

- [ ] Target devices identified (phones/tablets/both)?
- [ ] Framework chosen (vanilla PHP/HTML/Laravel/etc.)?
- [ ] Responsive vs PWA decided?
- [ ] Performance budget defined?
- [ ] Offline requirements known?
- [ ] Deep linking planned?

### Development Checklist

- [ ] Viewport meta tag present?
- [ ] Touch targets &gt;= 44-48px?
- [ ] Primary CTA in thumb zone?
- [ ] Loading state exists?
- [ ] Error state with retry exists?
- [ ] Works on small screens?
- [ ] Images optimized and responsive?
- [ ] CSS/JS minified?

### Before Release Checklist

- [ ] Lighthouse score &gt;=90?
- [ ] All images optimized?
- [ ] Caching configured?
- [ ] Tested on real devices?
- [ ] Tested on different browsers?
- [ ] Accessibility checked?
- [ ] Performance tested?

---

## 🚀 Common Patterns

### Responsive Design

```css
/* Mobile-first CSS */
.container {
  width: 100%;
  padding: 16px;
}

@media (min-width: 768px) {
  .container {
    max-width: 720px;
    margin: 0 auto;
  }
}

@media (min-width: 1024px) {
  .container {
    max-width: 960px;
  }
}
```

### Touch Targets

```css
button, [role="button"], a {
  min-width: 44px;
  min-height: 44px;
  padding: 12px 16px;
}

input, textarea, select {
  min-height: 48px;
  padding: 12px 16px;
}
```

### Responsive Images

```html
<picture>
  <source srcset="image.webp" type="image/webp">
  <img 
    src="image.jpg" 
    srcset="image-480.jpg 480w, image-800.jpg 800w, image-1200.jpg 1200w"
    sizes="(max-width: 600px) 480px, (max-width: 1200px) 800px, 1200px"
    alt="Description"
    loading="lazy"
  >
</picture>
```

### PHP Dynamic Images

```php
<?php
function responsiveImage($src, $alt = '', $sizes = [480, 800, 1200]) {
  $srcset = [];
  foreach ($sizes as $size) {
    $srcset[] = "{$src}-{$size}.webp {$size}w";
  }
  return "<img src='{$src}.jpg' srcset='" . implode(', ', $srcset) . "' alt='{$alt}' loading='lazy'>";
}
?>
```

---

## 🎯 Anti-Patterns to Avoid

### ❌ Performance Sins

| Anti-Pattern             | Fix                                         |
| ------------------------ | ------------------------------------------- |
| Large unoptimized images | Compress, use WebP, responsive images       |
| Render-blocking CSS/JS   | Async/defer, inline critical CSS            |
| No viewport meta tag     | Always include`<meta name="viewport"...>` |
| Fixed widths             | Use percentages, max-width, flexbox         |

### ❌ Touch/UX Sins

| Anti-Pattern                    | Fix                          |
| ------------------------------- | ---------------------------- |
| Touch target&lt; 44px           | Minimum 44x44px              |
| Spacing&lt; 8px between targets | Minimum 8-12px gap           |
| Hover-only interactions         | Provide tap alternative      |
| No loading state                | Always show loading feedback |

### ❌ Architecture Sins

| Anti-Pattern           | Fix                                  |
| ---------------------- | ------------------------------------ |
| Business logic in HTML | Separate PHP logic from presentation |
| Inline styles          | Use external CSS                     |
| Table-based layouts    | Use flexbox/grid                     |
| No semantic HTML       | Use proper HTML5 tags                |

---

## 📊 Key Metrics

### Core Web Vitals

| Metric                         | Good    | Excellent |
| ------------------------------ | ------- | --------- |
| LCP (Largest Contentful Paint) | ≤2.5s  | ≤1.5s    |
| FID (First Input Delay)        | ≤100ms | ≤50ms    |
| CLS (Cumulative Layout Shift)  | ≤0.1   | ≤0.05    |

### Performance Budget

| Resource          | Budget  |
| ----------------- | ------- |
| Total page weight | ≤500KB |
| JavaScript        | ≤200KB |
| CSS               | ≤100KB |
| Images            | ≤200KB |
| Fonts             | ≤100KB |

---

## 🔧 Tools

### Development

- **Chrome DevTools**: Inspect, debug, test
- **Firefox DevTools**: Alternative debugging
- **Safari Web Inspector**: iOS debugging
- **Visual Studio Code**: Code editor with PHP/JS support

### Testing

- **Lighthouse**: Performance, accessibility, SEO audit
- **Cypress**: E2E testing
- **Jest**: JavaScript unit testing
- **PHPUnit**: PHP unit testing

### Performance

- **WebPageTest**: Cross-device performance testing
- **Lighthouse**: Built into Chrome DevTools
- **PageSpeed Insights**: Google's performance tool

### Debugging

- **Vorlon.js**: Remote debugging
- **Weinre**: Web Inspector Remote
- **BrowserStack**: Cross-browser testing
- **Sauce Labs**: Cross-browser testing

---

## 📚 Learning Path

### Beginner

1. Read `mobile-design-thinking.md`
2. Read `touch-psycology.md`
3. Practice with simple responsive layouts
4. Test on real mobile devices

### Intermediate

1. Read `mobile-performance.md`
2. Read `mobile-navigation.md`
3. Implement a mobile-first design
4. Optimize performance with Lighthouse

### Advanced

1. Read `pwa-fundamentals.md`
2. Build a Progressive Web App
3. Implement offline functionality

### Expert

1. Read all files
2. Read `mobile-testing.md`
3. Read `mobile-debugging.md`
4. Build complex PWAs with caching strategies
5. Implement comprehensive testing

---

## 🎓 Example Projects

### 1. Simple Responsive Blog

- Mobile-first design
- Responsive images
- Touch-optimized navigation
- Performance optimized

### 2. E-commerce PWA

- Progressive Web App
- Offline browsing
- Add to home screen
- Push notifications

### 3. Mobile Web App

- Complex navigation
- Form optimization
- Touch interactions
- Performance budget

---

## 📞 Support

For questions about this skill:

- Check the relevant files first
- Review the checklists
- Test on real devices
- Measure performance with Lighthouse

---

## 📝 Changelog

### v1.0.0 (2026-08-24)

- Initial release: Mobile web design skill for PHP/HTML
- Focus on responsive design, PWA, touch psychology, performance
- Removed native app framework content (React Native, Flutter, etc.)
- Added PHP/HTML specific examples and patterns

---

## 🎯 Summary

**Mobile web development is different from desktop development.**

This skill provides:

- ✅ Mobile-first design principles
- ✅ Touch interaction guidelines
- ✅ Performance optimization strategies
- ✅ Responsive design patterns
- ✅ PWA implementation guidance
- ✅ Testing and debugging techniques
- ✅ PHP/HTML specific examples

**Remember**: The best mobile web apps are those that work for everyone, everywhere, on any device.
