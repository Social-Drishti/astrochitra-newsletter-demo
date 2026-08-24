---
name: mobile-design
description: Mobile-first web design for PHP/HTML/CSS/JS. Use when building mobile web apps, PWAs, responsive pages, touch interactions, mobile navigation, mobile performance optimization, or mobile testing/debugging. NOT for native iOS/Android development.
---

# Mobile Web Design System (PHP/HTML)

**Philosophy**: Touch-first. Battery-conscious. Platform-respectful. Offline-capable.

**Core Principle**: Mobile is NOT a small desktop. THINK mobile constraints, ASK platform choice.

## When to Load This Skill

Use this skill when:

- Building mobile web applications with PHP/HTML/CSS/JavaScript
- Designing mobile-first responsive websites
- Creating Progressive Web Apps (PWA)
- Optimizing web performance for mobile devices
- Implementing touch interactions for web
- Planning mobile web architecture

## Runtime Validation

Execute these tools for automated validation:

| Tool          | Purpose                               | Usage             |
| ------------- | ------------------------------------- | ----------------- |
| Lighthouse    | Performance, accessibility, SEO audit | Chrome DevTools   |
| WebPageTest   | Cross-device performance testing      | webpagetest.org   |
| Responsinator | Responsive design testing             | responsinator.com |
| BrowserStack  | Cross-browser/device testing          | browserstack.com  |

## MANDATORY: Read Reference Files Before Working

**DO NOT start development until you read the relevant files.**

### Universal (Always Read - CRITICAL)

| File                      | Content                                                  | Priority |
| ------------------------- | -------------------------------------------------------- | -------- |
| mobile-design-thinking.md | ANTI-MEMORIZATION: Forces thinking, prevents AI defaults | FIRST    |
| touch-psycology.md       | Fitts' Law, gestures, haptics, thumb zone                | CRITICAL |
| mobile-performance.md     | Web performance, 60fps, memory optimization              | CRITICAL |
| mobile-testing.md         | Testing strategies for mobile web                        | CRITICAL |
| mobile-debugging.md       | Debugging mobile web issues                              | CRITICAL |
| pwa-fundamentals.md       | Service workers, manifest, offline caching               | Read     |
| mobile-navigation.md      | Mobile web navigation patterns                           | Read     |

> mobile-design-thinking.md is PRIORITY! This file ensures AI thinks contextually instead of using memorized patterns.

---

## CRITICAL: ASK BEFORE ASSUMING (MANDATORY)

**STOP!** If the user's request is open-ended, DO NOT default to memorized patterns.

**You MUST Ask If Not Specified:**

| Aspect             | Ask                                     | Why                           |
| ------------------ | --------------------------------------- | ----------------------------- |
| Target Devices     | Which mobile devices/browsers?          | Affects design decisions      |
| Framework          | Vanilla PHP/HTML, or using a framework? | Determines patterns and tools |
| Responsive vs PWA  | Responsive site or Progressive Web App? | Core architecture decision    |
| Offline Support    | Does this need to work offline?         | Affects caching strategy      |
| Performance Budget | What's the performance budget?          | Guides optimization           |

---

## Mobile Web Anti-Patterns

### Performance Sins

| NEVER DO                 | Why It's Wrong           | ALWAYS DO                                                                |
| ------------------------ | ------------------------ | ------------------------------------------------------------------------ |
| Large unoptimized images | Slow load, data usage    | Compress, use WebP, responsive images                                    |
| Render-blocking CSS/JS   | Delays page rendering    | Async/defer, inline critical CSS                                         |
| Synchronous XHR          | Blocks main thread       | Use fetch/axios with async/await                                         |
| No viewport meta tag     | Broken responsive layout | `<meta name="viewport" content="width=device-width, initial-scale=1">` |
| Fixed widths             | Breaks on small screens  | Use percentages, max-width, flexbox                                      |
| Too many HTTP requests   | Slow page load           | Combine files, use sprites, lazy load                                    |
| No caching               | Slow repeat visits       | Set proper cache headers                                                 |

### Touch/UX Sins

| NEVER DO                        | Why It's Wrong               | ALWAYS DO                    |
| ------------------------------- | ---------------------------- | ---------------------------- |
| Touch target&lt; 44px           | Impossible to tap accurately | Minimum 44x44px              |
| Spacing&lt; 8px between targets | Accidental taps on neighbors | Minimum 8-12px gap           |
| Hover-only interactions         | Mobile has no hover          | Provide tap alternative      |
| No loading state                | User thinks app crashed      | ALWAYS show loading feedback |
| No error state                  | User stuck, no recovery path | Show error with retry option |
| No viewport meta                | Layout broken on mobile      | Include viewport meta tag    |
| Desktop-first CSS               | Mobile layout breaks         | Mobile-first CSS             |

### Security Sins

| NEVER DO             | Why It's Wrong              | ALWAYS DO                       |
| -------------------- | --------------------------- | ------------------------------- |
| Plain text passwords | Easily intercepted          | Use HTTPS, hash passwords       |
| No CSRF protection   | Vulnerable to attacks       | Use CSRF tokens                 |
| No input validation  | XSS, SQL injection          | Validate and sanitize all input |
| Session in URL       | Security risk, shared links | Use cookies/sessions            |
| Hardcoded secrets    | Exposed in source code      | Use environment variables       |

### Architecture Sins

| NEVER DO               | Why It's Wrong                       | ALWAYS DO                            |
| ---------------------- | ------------------------------------ | ------------------------------------ |
| Business logic in HTML | Untestable, unmaintainable           | Separate PHP logic from presentation |
| Inline styles          | Unmaintainable, no reuse             | Use external CSS                     |
| Table-based layouts    | Not responsive, accessibility issues | Use flexbox/grid                     |
| No semantic HTML       | SEO and accessibility issues         | Use proper HTML5 tags                |
| No error handling      | Users see raw errors                 | Graceful error pages                 |

---

## Platform Decision Matrix

### When to Unify vs Diverge

|                | UNIFY (same on all) | DIVERGE (platform-specific)       |
| -------------- | ------------------- | --------------------------------- |
| Business Logic | Always              | -                                 |
| Data Layer     | Always              | -                                 |
| Core Content   | Always              | -                                 |
| Navigation     | -                   | iOS: bottom tabs, Android: drawer |
| Gestures       | -                   | Platform conventions              |
| Form inputs    | -                   | iOS vs Android date pickers       |

### Mobile Web vs Native

| Feature         | Mobile Web (PWA)         | Native App        |
| --------------- | ------------------------ | ----------------- |
| Development     | PHP/HTML/CSS/JS          | Platform-specific |
| Distribution    | URL                      | App Store         |
| Updates         | Instant                  | App Store review  |
| Offline         | Service Worker           | Built-in          |
| Hardware Access | Limited                  | Full              |
| Discovery       | SEO                      | App Store         |
| Performance     | Good (with optimization) | Best              |

---

## Mobile UX Psychology (Quick Reference)

### Fitts' Law for Touch

- **Desktop**: Cursor is precise (1px)
- **Mobile**: Finger is imprecise (\~7mm contact area)

**Touch targets MUST be 44-48px minimum**
**Important actions in THUMB ZONE (bottom of screen)**
**Destructive actions AWAY from easy reach**

### Thumb Zone (One-Handed Usage)

```
┌─────────────────────────────────────────────────────┐
│  ┌─────────────────────────────────────────────┐    │
│  │                   HARD TO REACH                   │    │
│  │              (Requires hand shift)              │    │
│  └─────────────────────────────────────────────┘    │
│                                                         │
│  ┌─────────────────────────────────────────────┐    │
│  │                  OK TO REACH                    │    │
│  │               (Stretch required)                │    │
│  └─────────────────────────────────────────────┘    │
│                                                         │
│  ┌─────────────────────────────────────────────┐    │
│  │                  EASY TO REACH                   │    │
│  │           (Thumb's natural resting area)        │    │
│  └─────────────────────────────────────────────┘    │
│                                                         │
│                   [ HOME BUTTON AREA ]                   │
└─────────────────────────────────────────────────────┘
```

### Mobile-Specific Cognitive Load

| Desktop            | Mobile Difference              |
| ------------------ | ------------------------------ |
| Multiple windows   | ONE task at a time             |
| Keyboard shortcuts | Touch gestures                 |
| Hover states       | NO hover (tap or nothing)      |
| Large viewport     | Limited space, scroll vertical |
| Stable attention   | Interrupted constantly         |

---

## Performance Principles (Quick Reference)

### Critical Rendering Path Optimization

**Goal**: Render above-the-fold content in 1s

**Steps:**

1. Minimize render-blocking resources
2. Inline critical CSS
3. Defer non-critical JS
4. Lazy load below-the-fold content

### Mobile Web Performance Checklist

- [ ] Viewport meta tag present
- [ ] Critical CSS inlined
- [ ] Non-critical CSS deferred
- [ ] JavaScript deferred or async
- [ ] Images optimized and responsive
- [ ] Fonts preloaded
- [ ] Above-the-fold content loads in 1s
- [ ] Full page loads in 3s

### Image Optimization

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

**PHP dynamic images:**

```php
<?php
$imageUrl = 'image.jpg';
$webpUrl = 'image.webp';
$srcset = 'image-480.jpg 480w, image-800.jpg 800w, image-1200.jpg 1200w';
?>
<picture>
  <source srcset="<?= $webpUrl ?>" type="image/webp">
  <img 
    src="<?= $imageUrl ?>" 
    srcset="<?= $srcset ?>"
    sizes="(max-width: 600px) 480px, (max-width: 1200px) 800px, 1200px"
    alt="Description"
    loading="lazy"
  >
</picture>
```

---

## CHECKPOINT (MANDATORY Before Any Mobile Work)

**Before writing ANY mobile web code, you MUST complete this checkpoint:**

```
CHECKPOINT:

Target Devices: [ Phones / Tablets / Both ]
Framework: [ Vanilla PHP/HTML / Laravel / Symfony / WordPress / Other ]
Type: [ Responsive Site / PWA / Hybrid ]
Files Read: [ List the skill files you've read ]

3 Principles I Will Apply:
1. _______________
2. _______________
3. _______________

Anti-Patterns I Will Avoid:
1. _______________
2. _______________
```

**Example:**

```
CHECKPOINT:

Target Devices: Phones + Tablets
Framework: Vanilla PHP/HTML/CSS
Type: Responsive Site + PWA
Files Read: touch-psycology.md, mobile-performance.md, pwa-fundamentals.md

3 Principles I Will Apply:
1. Mobile-first CSS with media queries
2. 48px touch targets, thumb zone for primary CTAs
3. Optimized images with srcset and WebP

Anti-Patterns I Will Avoid:
1. Fixed widths -> Flexible layouts
2. Large unoptimized images -> Responsive images
3. No viewport meta -> Always include viewport tag
```

> Can't fill the checkpoint? GO BACK AND READ THE SKILL FILES.

---

## Pre-Development Checklist

### Before Starting ANY Mobile Web Project

- [ ] Target devices identified? (Phones / Tablets / Both)
- [ ] Framework chosen? (Vanilla / Laravel / Symfony / WordPress)
- [ ] Responsive vs PWA decided?
- [ ] Performance budget defined?
- [ ] Offline requirements known?
- [ ] Deep linking planned?

### Before Every Page

- [ ] Viewport meta tag present?
- [ ] Touch targets &gt;= 44-48px?
- [ ] Primary CTA in thumb zone?
- [ ] Loading state exists?
- [ ] Error state with retry exists?
- [ ] Works on small screens?

### Before Release

- [ ] Lighthouse score &gt;=90?
- [ ] All images optimized?
- [ ] CSS/JS minified?
- [ ] Caching configured?
- [ ] Tested on real devices?
- [ ] Accessibility checked?

---

## Reference Files

For deeper guidance on specific areas:

| File                      | When to Use                                                 |
| ------------------------- | ----------------------------------------------------------- |
| mobile-design-thinking.md | FIRST! Anti-memorization, forces context-based thinking     |
| touch-psycology.md       | Understanding touch interaction, Fitts' Law, gesture design |
| mobile-performance.md     | Optimizing web performance, 60fps, memory/battery           |
| pwa-fundamentals.md       | Service workers, manifest, offline caching                  |
| mobile-navigation.md      | Navigation patterns for mobile web                          |
| mobile-testing.md         | Testing strategies for mobile web                           |
| mobile-debugging.md       | Debugging mobile web issues                                 |

---

## Design for the WORST Conditions

Mobile web users experience:

- Slow networks: 2G, 3G, or congested WiFi
- Old devices: Low CPU, limited memory
- Small screens: 3-5 inch displays
- Interrupted sessions: Phone calls, notifications
- Battery constraints: Every byte costs power

**Rule of thumb**: If it works under the worst conditions, it works everywhere.

---

## Skill Workflow

1. Load this skill when mobile web development is requested
2. Ask mandatory questions if requirements not specified
3. Read reference files based on the project needs
4. Complete the checkpoint before writing any code
5. Follow anti-patterns list to avoid common mistakes
6. Use performance principles for all implementations
7. Validate with Lighthouse and other tools
8. Check pre-release checklist before deployment
