<?php
$lang = isset($_GET['lang']) && in_array($_GET['lang'], ['en','hi','mr']) ? $_GET['lang'] : 'en';
$jsonPath = __DIR__ . '/content.json';
$contentAll = json_decode(file_get_contents($jsonPath), true);
$langContent = $contentAll[$lang] ?? $contentAll['en'];
$title = $langContent['meta']['title'];

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'astrochitra.com';
$baseUrl = $scheme . '://' . $host;
// October has no OG image yet; fallback to September's
$ogImage = $baseUrl . '/assets/september_2026/og_image.webp';

header('Cache-Control: public, max-age=3600, stale-while-revalidate=86400');
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../admin/db.php';
if (function_exists('track_view')) {
  track_view($pdo, 'october-2026', 'October 2026 | AstroChitra Monthly Newsletter');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="theme-color" content="#0A1D4A">
  <link rel="preload" as="image" href="../../assets/october_2026/background.png" type="image/png">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
  <meta property="og:description" content="AstroChitra October 2026. Your cosmic roadmap with monthly transits, rashifal, and practical guidance.">
  <meta property="og:image" content="<?php echo $ogImage; ?>">
  <meta property="og:image:width" content="3072">
  <meta property="og:image:height" content="2048">
  <meta property="og:image:type" content="image/webp">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>">
  <meta name="twitter:description" content="AstroChitra October 2026. Your cosmic roadmap with monthly transits, rashifal, and practical guidance.">
  <meta name="twitter:image" content="<?php echo $ogImage; ?>">
  <title><?php echo htmlspecialchars($title); ?></title>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Dekko&family=Nunito+Sans:wght@400;600;700;900&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Dekko&family=Nunito+Sans:wght@400;600;700;900&display=swap" media="print" onload="this.media='all'">
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Dekko&family=Nunito+Sans:wght@400;600;700;900&display=swap"></noscript>
  <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/light/style.css">
  <style>
    @font-face {
      font-family: 'Plus Jakarta Sans';
      src: url('../../assets/october_2026/fonts/plus-jakarta-sans-latin.woff2') format('woff2');
      font-weight: 200 800;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Marcellus';
      src: url('../../assets/Marcellus.ttf') format('truetype');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    }

    @font-face {
      font-family: 'Dekko';
      src: url('../../assets/october_2026/fonts/dekko-devanagari.woff2') format('woff2');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
      unicode-range: U+0900-097F, U+200C-200D, U+A830-A839;
    }

    @font-face {
      font-family: 'Dekko';
      src: url('../../assets/october_2026/fonts/dekko-latin.woff2') format('woff2');
      font-weight: 400;
      font-style: normal;
      font-display: swap;
    }

    /* ================= TOKENS ================= */
    :root {
      --bg: #0A1D4A;
      --bg-a: #0E2560;
      --bg-b: #16407F;
      --ink: #F6F2FF;
      --muted: #A9BAEA;
      --gold: #E8C564;
      --gold-hi: #FFE49C;
      --gold-dim: rgba(232, 197, 100, .22);
      --hair: rgba(255, 255, 255, .08);
      --hair-strong: rgba(255, 255, 255, .16);
      --caution: #D4645A;
      --caution-soft: rgba(212, 100, 90, .14);
      --whatsapp: #25D366;
      --font-display: 'Marcellus', Georgia, 'Times New Roman', serif;
      --font-body: 'Plus Jakarta Sans', -apple-system, 'Segoe UI', Helvetica, Arial, sans-serif;
      --font-deva: 'Dekko', cursive;
      --nav-h: 64px;
      --safe-t: env(safe-area-inset-top, 0px);
      --safe-b: env(safe-area-inset-bottom, 0px);
      --chrome-t: calc(12px + var(--safe-t));
      --pad-t: calc(var(--safe-t) + 76px);
      --pad-b: calc(var(--nav-h) + var(--safe-b) + 36px);
      --cover-img: url('../../assets/october_2026/background.png');
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: var(--font-body);
      background: var(--bg);
      color: var(--ink);
      font-size: 16px;
      font-weight: 300;
      line-height: 1.6;
      overflow: hidden;
      -webkit-font-smoothing: antialiased;
      overscroll-behavior: none;
    }

    <?php if ($lang !== 'en'): ?>
    body {
      font-family: 'Dekko', 'Nunito Sans', sans-serif;
    }
    <?php endif; ?>

    img {
      max-width: 100%;
      display: block;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    button {
      font-family: inherit;
      color: inherit;
    }

    :focus-visible {
      outline: 2px solid var(--gold);
      outline-offset: 3px;
      border-radius: 6px;
    }

    /* ================= COSMOS OVERLAY ================= */
    .cosmos {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background-image:
        radial-gradient(1px 1px at 8% 24%, rgba(255, 255, 255, .6) 50%, transparent 51%),
        radial-gradient(1px 1px at 16% 70%, rgba(255, 255, 255, .3) 50%, transparent 51%),
        radial-gradient(1.5px 1.5px at 24% 42%, rgba(255, 255, 255, .42) 50%, transparent 51%),
        radial-gradient(1px 1px at 33% 13%, rgba(255, 255, 255, .3) 50%, transparent 51%),
        radial-gradient(1px 1px at 41% 62%, rgba(255, 255, 255, .5) 50%, transparent 51%),
        radial-gradient(1.5px 1.5px at 52% 28%, rgba(255, 255, 255, .36) 50%, transparent 51%),
        radial-gradient(1px 1px at 61% 76%, rgba(255, 255, 255, .44) 50%, transparent 51%),
        radial-gradient(1px 1px at 70% 18%, rgba(255, 255, 255, .3) 50%, transparent 51%),
        radial-gradient(1.5px 1.5px at 79% 52%, rgba(255, 255, 255, .4) 50%, transparent 51%),
        radial-gradient(1px 1px at 87% 34%, rgba(255, 255, 255, .5) 50%, transparent 51%),
        radial-gradient(1px 1px at 93% 74%, rgba(255, 255, 255, .34) 50%, transparent 51%),
        radial-gradient(1.5px 1.5px at 12% 88%, rgba(255, 255, 255, .3) 50%, transparent 51%),
        radial-gradient(1px 1px at 46% 94%, rgba(255, 255, 255, .36) 50%, transparent 51%),
        radial-gradient(1px 1px at 68% 90%, rgba(255, 255, 255, .28) 50%, transparent 51%),
        radial-gradient(1px 1px at 4% 50%, rgba(255, 255, 255, .3) 50%, transparent 51%),
        radial-gradient(1px 1px at 96% 8%, rgba(255, 255, 255, .4) 50%, transparent 51%);
    }

    /* ================= DECK ================= */
    .deck {
      position: fixed;
      inset: 0;
      z-index: 1;
      display: flex;
      transition: transform .55s cubic-bezier(.77, 0, .18, 1);
      will-change: transform;
    }

    .slide {
      flex: 0 0 100%;
      height: 100%;
      overflow: hidden;
      overscroll-behavior: contain;
      touch-action: pan-y;
      position: relative;
      background: var(--bg);
    }

    .slide.fits {
      overflow-y: hidden;
    }

    .slide-in {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: var(--pad-t) clamp(18px, 5vw, 34px) var(--pad-b);
      width: 100%;
      max-width: 1240px;
      margin: 0 auto;
      position: relative;
      z-index: 2;
      overflow-y: auto;
      overflow-x: hidden;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: thin;
      scrollbar-color: rgba(232, 197, 100, .35) transparent;
    }

    .slide-in::-webkit-scrollbar {
      width: 5px;
    }

    .slide-in::-webkit-scrollbar-thumb {
      background: rgba(232, 197, 100, .3);
      border-radius: 99px;
    }

    .slide.fits .slide-in {
      overflow-y: hidden;
    }

    .slide:not(.fits) .slide-in {
      justify-content: flex-start;
    }

    /* slide finishes */
    .s-guruji,
    .s-products,
    .s-myth,
    .s-know {
      background: var(--bg-a);
    }

    .s-rashifal,
    .s-watch,
    .s-testi {
      background: var(--bg-b);
    }

    /* constellation line art */
    .const {
      position: absolute;
      pointer-events: none;
      color: var(--gold-dim);
      opacity: .9;
      z-index: 0;
    }

    .const svg {
      width: 100%;
      height: 100%;
      display: block;
    }

    .c-hero-r {
      right: -30px;
      top: 16%;
      width: min(300px, 46vw);
      height: min(190px, 30vw);
      transform: rotate(6deg);
    }

    .c-hero-l {
      left: -36px;
      bottom: 14%;
      width: min(240px, 40vw);
      height: min(150px, 24vw);
      transform: rotate(-8deg);
      opacity: .6;
    }

    .c-guruji-r {
      right: -20px;
      top: 10%;
      width: min(220px, 38vw);
      height: min(140px, 24vw);
    }

    .c-mantra-l {
      left: -34px;
      top: 18%;
      width: min(260px, 42vw);
      height: min(160px, 26vw);
      transform: rotate(-10deg);
      opacity: .7;
    }

    .c-transits-r {
      right: -28px;
      bottom: 16%;
      width: min(240px, 40vw);
      height: min(150px, 24vw);
      opacity: .7;
    }

    .c-final-r {
      right: -26px;
      top: 12%;
      width: min(260px, 44vw);
      height: min(160px, 26vw);
      transform: rotate(8deg);
      opacity: .6;
    }

    /* ================= SHARED UI ================= */
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      min-height: 48px;
      padding: 12px 26px;
      border-radius: 999px;
      font-weight: 600;
      font-size: .92rem;
      letter-spacing: .01em;
      border: 1px solid transparent;
      line-height: 1.15;
      text-align: center;
      cursor: pointer;
      transition: transform .12s ease, background .15s ease, border-color .15s ease, color .15s ease;
    }

    .btn:active {
      transform: scale(.97);
    }

    .btn-gold {
      background: var(--gold);
      color: #0A1D4A;
      border-color: var(--gold);
    }

    .btn-gold:hover {
      background: var(--gold-hi);
      border-color: var(--gold-hi);
    }

    .btn-outline {
      background: transparent;
      color: var(--gold-hi);
      border-color: var(--gold-dim);
    }

    .btn-outline:hover {
      background: rgba(232, 197, 100, .08);
      border-color: var(--gold);
    }

    .btn-ink {
      background: var(--bg-b);
      color: var(--gold-hi);
      border-color: var(--hair-strong);
    }

    .btn-ink:hover {
      border-color: var(--gold);
    }

    .kicker {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      font-size: .7rem;
      font-weight: 600;
      letter-spacing: .26em;
      text-transform: uppercase;
      color: var(--muted);
    }

    .kicker .tick {
      width: 34px;
      height: 1px;
      background: var(--gold);
      opacity: .9;
    }

    .kicker .tick.right {
      margin-left: 2px;
    }

    .sec-head {
      position: relative;
      max-width: 760px;
      margin: 0 auto clamp(18px, 3.4vh, 30px);
    }

    .sec-head.left {
      text-align: left;
      margin-left: 0;
    }

    .sec-head.center {
      text-align: center;
    }

    .sec-head h1,
    .sec-head h2 {
      font-family: var(--font-display);
      font-weight: 400;
      line-height: 1.12;
      color: var(--ink);
      margin-top: 12px;
      letter-spacing: .01em;
    }

    .sec-head h2 {
      font-size: clamp(2rem, 6.4vw, 3.3rem);
    }

    .sec-head h1 {
      font-size: clamp(2.6rem, 8.6vw, 4.6rem);
    }

    .sec-head h2 em,
    .sec-head h1 em {
      color: var(--gold);
      font-style: italic;
    }

    .sec-head p {
      color: var(--muted);
      margin-top: 10px;
      font-size: clamp(.9rem, 2.6vw, 1rem);
      font-weight: 300;
      line-height: 1.6;
    }

    /* reveal animation */
    .rv {
      opacity: 0;
      transform: translateY(16px);
      transition: opacity .55s ease var(--d, 0s), transform .55s cubic-bezier(.22, .9, .35, 1) var(--d, 0s);
    }

    .slide.active .rv {
      opacity: 1;
      transform: none;
    }

    /* ================= FIXED CHROME ================= */
    .progress {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      z-index: 400;
      background: rgba(232, 197, 100, .14);
    }

    .progress span {
      display: block;
      height: 100%;
      width: 0;
      background: linear-gradient(90deg, var(--gold), var(--gold-hi));
      transition: width .45s ease;
    }

    .chrome-btn {
      position: fixed;
      top: var(--chrome-t);
      z-index: 340;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: rgba(14, 37, 96, .6);
      border: 1px solid var(--hair-strong);
      color: var(--ink);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 10px 26px -10px rgba(6, 16, 52, .5);
      font-size: 21px;
      transition: background .15s, border-color .15s, color .15s, transform .12s;
    }

    .chrome-btn:hover {
      background: var(--gold);
      border-color: var(--gold);
      color: #0A1D4A;
    }

    .chrome-btn:active {
      transform: scale(.94);
    }

    .menu-btn {
      right: 14px;
    }

    .share-btn {
      left: 14px;
    }

    .comment-btn {
      right: 70px;
    }

    /* ================= LANGUAGE SWITCHER ================= */
    .lang-switcher{
      position:fixed;top:var(--chrome-t);
      left:calc(14px + 48px + 6px);
      right:calc(70px + 48px + 6px);
      z-index:340;
      display:flex;align-items:center;height:48px;
      background:var(--bg-a);border:1px solid var(--hair-strong);
      border-radius:999px;overflow:hidden;
      box-shadow:0 6px 18px -4px rgba(14, 37, 96, .5), inset 0 1px 0 var(--hair-strong);
    }
    .lang-ic{
      display:flex;align-items:center;justify-content:center;
      padding:0 0 0 12px;color:var(--gold-hi);flex:none;
    }
    .lang-switcher button{
      flex:1;border:none;background:none;padding:9px 0;font-size:.68rem;font-weight:600;
      letter-spacing:.08em;cursor:pointer;position:relative;
      color:var(--muted);transition:color .2s;text-align:center;
      font-family:'Nunito Sans',sans-serif;
    }
    .lang-switcher button:hover{color:var(--gold-hi);}
    .lang-switcher button.active{color:var(--bg);}
    .lang-switcher button.active::before{
      content:"";position:absolute;inset:3px 4px;border-radius:999px;
      background:var(--gold);z-index:-1;
      animation:langSlide .25s cubic-bezier(.4,0,.2,1);
    }
    @keyframes langSlide{from{opacity:0;transform:scale(.85);}to{opacity:1;transform:none;}}
    .lang-divider{
      width:1px;height:20px;background:var(--hair-strong);flex:none;
    }
    @media(min-width:540px){
      .lang-switcher button{font-size:.72rem;}
    }

    .menu-btn .ic-close {
      display: none;
    }

    body.menu-open .menu-btn .ic-open {
      display: none;
    }

    body.menu-open .menu-btn .ic-close {
      display: block;
    }

    .share-toast {
      position: fixed;
      left: 50%;
      bottom: calc(var(--nav-h) + var(--safe-b) + 30px);
      transform: translateX(-50%) translateY(8px);
      z-index: 360;
      background: var(--bg-a);
      color: var(--ink);
      border: 1px solid var(--gold-dim);
      border-radius: 999px;
      padding: 11px 24px;
      font-size: .82rem;
      font-weight: 400;
      opacity: 0;
      visibility: hidden;
      transition: opacity .25s, visibility .25s, transform .25s;
      white-space: nowrap;
    }

    .share-toast.show {
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(0);
    }

    .comment-btn .glow {
      position: absolute;
      top: 6px;
      right: 6px;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--gold);
      animation: pulse 2.4s ease-in-out infinite;
    }

    @keyframes pulse {

      0%,
      100% {
        box-shadow: 0 0 0 0 rgba(232, 197, 100, .5);
      }

      50% {
        box-shadow: 0 0 0 7px rgba(232, 197, 100, 0);
      }
    }

    .menu-backdrop {
      position: fixed;
      inset: 0;
      z-index: 320;
      background: rgba(9, 22, 64, .7);
      opacity: 0;
      visibility: hidden;
      transition: opacity .22s;
    }

    body.menu-open .menu-backdrop {
      opacity: 1;
      visibility: visible;
    }

    .menu-panel {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      z-index: 330;
      width: min(330px, 86vw);
      background: rgba(14, 37, 96, .78);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-left: 1px solid var(--hair);
      transform: translateX(102%);
      transition: transform .28s cubic-bezier(.4, 0, .2, 1);
      padding: calc(var(--chrome-t) + 64px) 16px calc(var(--safe-b) + 20px);
      overflow-y: auto;
      box-shadow: -18px 0 44px rgba(6, 16, 52, .5);
    }

    body.menu-open .menu-panel {
      transform: translateX(0);
    }

    .menu-title {
      font-size: .66rem;
      font-weight: 600;
      letter-spacing: .24em;
      text-transform: uppercase;
      color: var(--muted);
      text-align: center;
      margin-bottom: 16px;
      display: block;
    }

    .menu-list {
      display: flex;
      flex-direction: column;
      gap: 6px;
      list-style: none;
    }

    .menu-list a {
      font-size: .9rem;
      font-weight: 500;
      color: var(--ink);
      background: rgba(16, 16, 33, .6);
      border: 1px solid var(--hair);
      border-radius: 12px;
      min-height: 46px;
      padding: 9px 13px;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: border-color .15s, background .15s, color .15s;
    }

    .menu-list a:hover {
      background: rgba(232, 197, 100, .08);
      border-color: var(--gold-dim);
      color: var(--gold-hi);
    }

    .menu-list a.current {
      background: rgba(232, 197, 100, .12);
      border-color: var(--gold);
      color: var(--gold-hi);
    }

    .menu-no {
      flex: none;
      font-size: .68rem;
      font-weight: 600;
      letter-spacing: .08em;
      color: var(--gold);
      width: 22px;
      text-align: right;
    }

    /* ================= FOOTER BAR ================= */
    .footer-bar {
      position: fixed;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 300;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 0 14px calc(12px + var(--safe-b));
      pointer-events: none;
    }

    .footer-bar>* {
      pointer-events: auto;
    }

    .deck-nav {
      flex: 1;
      min-width: 0;
      display: flex;
      align-items: center;
      gap: 4px;
      background: rgba(14, 37, 96, .72);
      border: 1px solid var(--hair-strong);
      border-bottom-color: rgba(232, 197, 100, .35);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-radius: 999px;
      padding: 4px 6px;
      box-shadow: 0 14px 34px rgba(6, 16, 52, .45), inset 0 1px 0 rgba(255, 255, 255, .05);
    }

    .wa-sub {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      flex: none;
      height: 46px;
      padding: 0 18px;
      border-radius: 999px;
      background: var(--gold);
      border: 1px solid rgba(232, 197, 100, .8);
      color: #0A1D4A;
      cursor: pointer;
      font-size: .8rem;
      font-weight: 700;
      letter-spacing: .02em;
      white-space: nowrap;
      box-shadow: 0 6px 16px -6px rgba(232, 197, 100, .5);
      transition: background .15s;
    }

    .wa-sub:hover {
      background: var(--gold-hi);
    }

    .wa-sub i {
      font-size: 17px;
    }

    .nav-btn {
      flex: none;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      cursor: pointer;
      background: rgba(232, 197, 100, .12);
      color: var(--gold-hi);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      transition: background .15s, transform .15s, color .15s;
    }

    .nav-btn:hover {
      background: var(--gold);
      color: #0A1D4A;
      transform: scale(1.05);
    }

    .nav-btn:disabled {
      opacity: .3;
      cursor: default;
      transform: none;
    }

    .segs {
      position: relative;
      flex: 1 1 auto;
      min-width: 0;
      max-width: 100%;
      overflow: hidden;
      display: block;
    }

    .segs-track {
      position: relative;
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 0 4px;
      width: max-content;
      min-width: 100%;
      transition: transform .55s cubic-bezier(.77, 0, .18, 1);
      will-change: transform;
    }

    .segs-track.no-anim {
      transition: none;
    }

    .seg {
      flex: 0 0 auto;
      width: 24px;
      height: 26px;
      border: none;
      background: none;
      padding: 0;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .seg i {
      display: block;
      width: 100%;
      height: 3px;
      border-radius: 99px;
      background: rgba(232, 197, 100, .22);
      transition: background .2s, transform .2s;
    }

    .seg.on i {
      background: var(--gold-hi);
      transform: scaleY(1.5);
      box-shadow: 0 0 12px rgba(232, 197, 100, .6);
    }

    .seg.done i {
      background: rgba(232, 197, 100, .5);
    }

    @media(prefers-reduced-motion:reduce) {
      .segs-track {
        transition: none;
      }
    }

    .nav-count {
      display: none;
      align-items: baseline;
      gap: 4px;
      color: rgba(246, 242, 255, .6);
      font-size: .66rem;
      letter-spacing: .06em;
      padding: 0 4px 0 6px;
      white-space: nowrap;
    }

    .nav-count b {
      color: var(--gold-hi);
      font-size: .85rem;
    }

    @media(min-width:540px) {
      .nav-count {
        display: inline-flex;
      }
    }

    @media(max-width:430px) {
      .footer-bar {
        gap: 8px;
        padding-left: 10px;
        padding-right: 10px;
      }

      .nav-btn {
        width: 32px;
        height: 32px;
        font-size: 16px;
      }

      .seg {
        width: 19px;
      }

      .wa-sub {
        padding: 0 12px;
        font-size: .72rem;
        height: 42px;
      }
    }

    @media(max-width:380px) {
      .nav-count {
        display: none;
      }
    }

    @media(max-width:480px) {
      .btn, .quiet-link {
        min-height: 44px;
        padding: 10px 18px;
        font-size: .82rem;
      }
    }

    /* WhatsApp subscribe popup */
    .wa-modal {
      position: fixed;
      inset: 0;
      z-index: 380;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      background: rgba(9, 22, 64, .72);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      opacity: 0;
      visibility: hidden;
      transition: opacity .22s, visibility .22s;
    }

    .wa-modal.open {
      opacity: 1;
      visibility: visible;
    }

    .wa-modal-card {
      position: relative;
      width: 100%;
      max-width: 340px;
      text-align: center;
      background: var(--bg-a);
      border: 1px solid var(--gold-dim);
      border-radius: 22px;
      padding: 32px 24px 26px;
      box-shadow: 0 30px 70px -20px rgba(6, 16, 52, .6);
      transform: translateY(12px) scale(.96);
      transition: transform .22s cubic-bezier(.22, .9, .35, 1);
    }

    .wa-modal.open .wa-modal-card {
      transform: none;
    }

    .wa-modal-ic {
      width: 58px;
      height: 58px;
      margin: 0 auto 16px;
      border-radius: 50%;
      background: rgba(232, 197, 100, .12);
      color: var(--gold-hi);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      outline: 1px solid var(--gold-dim);
      outline-offset: 5px;
    }

    .wa-modal-card h3 {
      font-family: var(--font-display);
      font-size: 1.4rem;
      color: var(--ink);
      margin-bottom: 9px;
    }

    .wa-modal-card p {
      font-size: .9rem;
      color: var(--muted);
      line-height: 1.6;
      margin: 0 0 20px;
      font-weight: 300;
    }

    .wa-modal-close {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      background: var(--bg);
      color: var(--muted);
      cursor: pointer;
      font-size: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .15s, color .15s;
    }

    .wa-modal-close:hover {
      background: rgba(232, 197, 100, .12);
      color: var(--gold-hi);
    }

    /* ================= SLIDE 1 : COVER ================= */
    .s-hero::after {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 0;
      background: var(--cover-img) center 26%/cover no-repeat;
      opacity: .82;
    }

    .s-hero::before {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 1;
      background:
        radial-gradient(ellipse at 50% 122%, rgba(232, 197, 100, .14), transparent 58%),
        linear-gradient(180deg, rgba(9, 22, 64, .8) 0%, rgba(9, 22, 64, .4) 45%, rgba(9, 22, 64, .92) 100%);
    }

    .hero-wrap {
      text-align: center;
      max-width: 820px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 2;
    }

    .swipe-hint {
      position: absolute;
      left: 50%;
      bottom: calc(var(--nav-h) + var(--safe-b) + 30px);
      transform: translateX(-50%);
      z-index: 4;
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 15px 28px 15px 32px;
      border-radius: 999px;
      border: 1px solid rgba(232, 197, 100, .6);
      background: linear-gradient(180deg, rgba(22, 64, 127, .82), rgba(9, 22, 64, .88));
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow:
        0 0 0 6px rgba(232, 197, 100, .10),
        0 0 34px -6px rgba(232, 197, 100, .4),
        0 24px 48px -20px rgba(6, 16, 52, .75);
      font-size: clamp(.8rem, 3.4vw, .95rem);
      font-weight: 700;
      letter-spacing: .26em;
      text-transform: uppercase;
      color: var(--gold-hi);
      pointer-events: none;
    }
    .swipe-hint::after {
      content: "";
      position: absolute;
      left: 50%;
      top: 50%;
      width: 96%;
      height: 175%;
      transform: translate(-50%, -50%);
      background: radial-gradient(50% 50% at 50% 50%, rgba(232, 197, 100, .26), transparent 70%);
      z-index: -1;
      opacity: 0;
      animation: hintPulse 2.4s ease-in-out infinite;
      pointer-events: none;
    }
    @keyframes hintPulse {
      0%, 100% { opacity: .18; }
      50% { opacity: .75; }
    }
    .swipe-caret {
      position: relative;
      height: 26px;
      width: 20px;
    }
    .swipe-caret i {
      position: absolute;
      left: 0;
      top: 50%;
      font-size: 26px;
      line-height: 1;
      font-style: normal;
      color: var(--gold-hi);
      opacity: 0;
      animation: swipeLoop 1.5s cubic-bezier(.45, .05, .55, .95) infinite;
    }
    .swipe-caret i.b { animation-delay: .22s; }
    @keyframes swipeLoop {
      0% { opacity: 0; transform: translate(0, -50%); }
      30% { opacity: 1; }
      100% { opacity: 0; transform: translate(16px, -50%); }
    }
    @media (prefers-reduced-motion: reduce) {
      .swipe-caret i { animation: none; opacity: .7; }
      .swipe-caret i.b { opacity: .3; }
      .swipe-hint::after { animation: none; opacity: .4; }
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      margin-bottom: clamp(26px, 5vh, 42px);
      background: rgba(14, 37, 96, .5);
      border: 1px solid var(--gold-dim);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 999px;
      padding: 8px 22px 8px 10px;
    }

    .brand-badge img {
      height: 38px;
      width: auto;
    }

    .brand-badge i {
      width: 1px;
      height: 24px;
      background: var(--gold-dim);
    }

    .brand-badge span {
      font-size: .72rem;
      letter-spacing: .26em;
      text-transform: uppercase;
      color: var(--gold-hi);
      font-weight: 600;
    }

    .sec-head.glow-title h1 {
      color: var(--ink);
    }

    .hero-kicker-row {
      margin-bottom: 2px;
    }

    .sec-head.hero-title h1 {
      color: #fffdf8 !important;
    }

    .hero-title {
      padding-top: 10px;
    }

    /* ================= SLIDE 2 : GURUJI ================= */
    .guruji-col {
      max-width: 880px;
      margin: 0 auto;
      width: 100%;
    }

    .guruji-row {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: clamp(20px, 4vh, 34px);
      text-align: center;
    }

    .guruji-photo {
      width: 138px;
      height: 138px;
      border-radius: 50%;
      object-fit: cover;
      object-position: center top;
      outline: 2px solid var(--gold-dim);
      outline-offset: 6px;
      box-shadow: 0 22px 48px -16px rgba(6, 16, 52, .6);
    }

    .guruji-quote {
      font-family: var(--font-display);
      font-size: clamp(1.5rem, 5.2vw, 2.4rem);
      line-height: 1.38;
      color: var(--ink);
      max-width: 760px;
    }

    .guruji-quote::before {
      content: "“";
      display: block;
      font-size: 3rem;
      line-height: .4;
      color: var(--gold);
      font-family: Georgia, serif;
      margin-top: 1.2rem;
    }

    .guruji-quote::after {
      content: "”";
    }

    .guruji-quote-label {
      display: block;
      font-size: .78rem;
      font-weight: 600;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: .5rem;
    }

    .guruji-quote-alt {
      font-size: clamp(1.05rem, 3.2vw, 1.35rem);
      line-height: 1.5;
      color: var(--muted);
      margin-top: 1.1rem;
      padding-left: 1.1rem;
      border-left: 2px solid color-mix(in srgb, var(--gold) 40%, transparent);
    }

    .guruji-quote-alt::before,
    .guruji-quote-alt::after {
      content: none;
    }

    .consult-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      gap: 8px 22px;
      margin-top: clamp(16px, 3vh, 26px);
    }

    .quiet-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      min-height: 48px;
      padding: 12px 26px;
      border-radius: 999px;
      font-weight: 600;
      font-size: .92rem;
      letter-spacing: .01em;
      line-height: 1.15;
      background: var(--whatsapp);
      color: #0A1D4A;
      border: 1px solid var(--whatsapp);
      cursor: pointer;
      transition: background .15s ease, border-color .15s ease, color .15s ease, transform .12s ease;
    }

    .quiet-link i {
      font-size: 18px;
    }

    .quiet-link:hover {
      background: #1ebe57;
      border-color: #1ebe57;
      color: #0A1D4A;
    }

    .quiet-link:active {
      transform: scale(.97);
    }

    @media(min-width:900px) {
      .guruji-row {
        flex-direction: row;
        text-align: left;
      }

      .guruji-quote::before {
        margin-top: 0;
      }

      .consult-links {
        justify-content: flex-start;
      }
    }

    /* ================= SLIDE 3 : RASHIFAL ================= */
    .rashi-list {
      width: 100%;
      max-width: 1140px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr;
      gap: clamp(14px, 2.5vh, 22px);
    }

    .rashi-card {
      background: rgba(9, 22, 64, .72);
      border: 1px solid var(--hair);
      border-radius: 16px;
      box-shadow: 0 18px 40px -22px rgba(6, 16, 52, .6);
      overflow: hidden;
      transition: border-color .2s, box-shadow .2s;
    }

    .rashi-card:hover {
      border-color: var(--gold-dim);
    }

    details.rashi-card>summary {
      list-style: none;
    }

    details.rashi-card>summary::-webkit-details-marker {
      display: none;
    }

    details.rashi-card>summary::marker {
      display: none;
      content: "";
    }

    .rashi-card-hdr {
      display: flex;
      align-items: center;
      gap: clamp(10px, 2.5vw, 16px);
      padding: clamp(14px, 2.5vh, 20px) clamp(14px, 3vw, 22px);
      background: linear-gradient(135deg, var(--bg-b) 0%, var(--bg-a) 100%);
      border-bottom: 1px solid var(--hair);
      position: relative;
      overflow: hidden;
      cursor: pointer;
      -webkit-tap-highlight-color: transparent;
    }

    .rashi-card-hdr::after {
      content: "";
      position: absolute;
      top: -30px;
      right: -30px;
      width: 84px;
      height: 84px;
      border-radius: 50%;
      border: 1px solid var(--gold-dim);
      pointer-events: none;
    }

    details[open]>.rashi-card-hdr {
      border-bottom-color: rgba(232, 197, 100, .35);
    }

    .rashi-arrow {
      flex: none;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--bg);
      border: 1px solid var(--hair-strong);
      margin-left: auto;
      color: var(--gold);
      font-size: 14px;
      transition: transform .3s ease, background .2s, border-color .2s;
    }

    details[open]>.rashi-card-hdr .rashi-arrow {
      transform: rotate(180deg);
      background: rgba(232, 197, 100, .1);
      border-color: var(--gold);
    }

    .rashi-glyph {
      flex: 0 0 auto;
      width: clamp(120px, 36%, 200px);
      aspect-ratio: 1;
      border-radius: 50%;
      background: radial-gradient(circle at 32% 26%, var(--bg-b) 0%, var(--bg) 78%);
      border: 1px solid rgba(232, 197, 100, .42);
      box-shadow: 0 12px 30px -14px rgba(232, 197, 100, .45), inset 0 0 0 3px var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: clamp(14px, 7%, 26px);
    }

    .rashi-glyph img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      filter: brightness(1.08);
    }

    .rashi-hdr-txt {
      min-width: 0;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
    }

    .rashi-hdr-txt h3 {
      font-family: var(--font-body);
      font-weight: 600;
      font-size: clamp(1.18rem, 4vw, 1.36rem);
      color: var(--ink);
      line-height: 1.25;
      margin: 0;
      letter-spacing: 0;
    }

    .rashi-dates {
      display: block;
      font-size: .64rem;
      font-weight: 500;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--muted);
      margin-top: 3px;
    }

    .rashi-card-body {
      padding: clamp(14px, 2.5vh, 20px) clamp(14px, 3vw, 22px) clamp(16px, 3vh, 24px);
    }

    .rashi-overview {
      font-size: .88rem;
      color: var(--muted);
      line-height: 1.62;
      margin-bottom: clamp(14px, 2.5vh, 20px);
    }

    .rashi-pending {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0 0 14px;
      padding: 8px 14px;
      border: 1px dashed color-mix(in srgb, var(--gold) 55%, transparent);
      border-radius: 10px;
      background: rgba(232, 197, 100, .07);
      color: var(--gold-hi);
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .14em;
      text-transform: uppercase;
    }

    .rashi-pending i {
      font-size: 16px;
    }

    .tl-head {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
    }

    .tl-head-ic {
      flex: none;
      width: 30px;
      height: 30px;
      border-radius: 9px;
      background: rgba(232, 197, 100, .1);
      color: var(--gold);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }

    .tl-head span {
      font-size: .66rem;
      font-weight: 600;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--gold-hi);
    }

    .tl {
      position: relative;
      padding-left: 22px;
      margin-bottom: clamp(14px, 2.5vh, 20px);
    }

    .tl::before {
      content: "";
      position: absolute;
      left: 5px;
      top: 6px;
      bottom: 6px;
      width: 2px;
      background: linear-gradient(to bottom, rgba(232, 197, 100, .7), rgba(232, 197, 100, .25));
      border-radius: 99px;
    }

    .tl-item {
      position: relative;
      padding-bottom: clamp(12px, 2vh, 18px);
    }

    .tl-item:last-child {
      padding-bottom: 0;
    }

    .tl-dot {
      position: absolute;
      left: -22px;
      top: 3px;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: var(--gold);
      border: 2px solid var(--bg-a);
      box-shadow: 0 0 0 2px var(--gold-dim);
    }

    .tl-date {
      display: inline-block;
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--gold-hi);
      background: rgba(232, 197, 100, .1);
      border-radius: 999px;
      padding: 3px 11px;
      margin-bottom: 4px;
    }

    .tl-planet {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 5px;
    }

    .tl-planet>img {
      flex: none;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: var(--bg);
      border: 1px solid var(--gold-dim);
      padding: 5px;
      object-fit: contain;
    }

    .tl-planet-name {
      font-size: .83rem;
      font-weight: 500;
      color: var(--ink);
      line-height: 1.3;
      letter-spacing: 0;
    }

    .tl-planet-name b {
      color: var(--gold-hi);
      font-weight: 500;
    }

    .tl-house {
      display: inline-block;
      font-size: .62rem;
      font-weight: 500;
      letter-spacing: .08em;
      color: var(--gold);
      background: rgba(232, 197, 100, .08);
      border-radius: 999px;
      padding: 2px 9px;
      margin-top: 4px;
    }

    .tl-effect {
      font-size: .82rem;
      color: var(--muted);
      line-height: 1.6;
      margin-top: 4px;
    }

    .rashi-extra {
      background: rgba(232, 197, 100, .05);
      border: 1px dashed var(--gold-dim);
      border-radius: 11px;
      padding: clamp(10px, 1.8vh, 14px) clamp(12px, 2.5vw, 16px);
      margin-bottom: clamp(14px, 2.5vh, 20px);
    }

    .rashi-extra-hdr {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
      color: var(--gold-hi);
      font-size: 16px;
    }

    .rashi-extra-hdr span {
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--gold-hi);
    }

    .rashi-extra-item {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      margin-top: 8px;
    }

    .rashi-extra-item:first-of-type {
      margin-top: 0;
    }

    .rashi-extra-dot {
      flex: none;
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: var(--gold);
      margin-top: 6px;
    }

    .rashi-extra-item>div {
      min-width: 0;
    }

    .rashi-extra-item strong {
      font-size: .8rem;
      color: var(--ink);
      display: block;
      line-height: 1.35;
      font-weight: 600;
    }

    .rashi-extra-item p {
      font-size: .78rem;
      color: var(--muted);
      line-height: 1.58;
      margin-top: 2px;
    }

    .rashi-best {
      background: rgba(232, 197, 100, .06);
      border: 1px solid var(--gold-dim);
      border-radius: 11px;
      padding: clamp(10px, 1.8vh, 14px) clamp(12px, 2.5vw, 16px);
      margin-bottom: 10px;
      position: relative;
      overflow: hidden;
    }

    .rashi-best::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 3px;
      height: 100%;
      background: var(--gold);
      border-radius: 0 99px 99px 0;
    }

    .rashi-best-hdr {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 5px;
      color: var(--gold);
      font-size: 15px;
    }

    .rashi-best-hdr strong {
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--gold-hi);
    }

    .rashi-best .rashi-days {
      font-size: .74rem;
      font-weight: 600;
      color: var(--ink);
      display: block;
      margin-bottom: 3px;
    }

    .rashi-best>p {
      font-size: .8rem;
      color: var(--muted);
      line-height: 1.55;
      margin: 0;
    }

    .rashi-caution {
      background: var(--caution-soft);
      border: 1px solid rgba(212, 100, 90, .3);
      border-radius: 11px;
      padding: clamp(10px, 1.8vh, 14px) clamp(12px, 2.5vw, 16px);
      position: relative;
      overflow: hidden;
    }

    .rashi-caution::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 3px;
      height: 100%;
      background: var(--caution);
      border-radius: 0 99px 99px 0;
    }

    .rashi-caution-hdr {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 5px;
      color: var(--caution);
      font-size: 15px;
    }

    .rashi-caution-hdr strong {
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--caution);
    }

    .rashi-caution .rashi-days {
      font-size: .74rem;
      font-weight: 600;
      color: var(--ink);
      display: block;
      margin-bottom: 3px;
    }

    .rashi-caution>p {
      font-size: .8rem;
      color: var(--muted);
      line-height: 1.55;
      margin: 0;
    }

    @media(min-width:700px) {
      .rashi-list {
        grid-template-columns: repeat(2, 1fr);
        column-gap: clamp(20px, 3vw, 44px);
      }
    }

    @media(min-width:1060px) {
      .rashi-list {
        grid-template-columns: repeat(3, 1fr);
      }

      .rashi-card-body {
        padding: clamp(16px, 2.5vh, 24px) clamp(18px, 2.5vw, 26px) clamp(18px, 3vh, 26px);
      }
    }

    /* ================= SLIDE 4 : PRODUCTS ================= */
    .prod-showcase {
      width: 100%;
      max-width: 640px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: clamp(14px, 2.5vh, 22px);
      position: relative;
    }

    .tool-card {
      display: block;
      /* border: 1px solid var(--hair); */
      border-radius: 20px;
      color: var(--ink);
      /* background: var(--bg); */
      padding: clamp(14px, 2.4vh, 20px);
      overflow: hidden;
      transition: border-color .2s, transform .15s;
    }

    .tool-card:hover {
      border-color: rgba(232, 197, 100, .4);
    }

    .tool-card:active {
      transform: scale(.99);
    }

    .tool-hdr {
      display: flex;
      align-items: center;
      gap: clamp(12px, 2.5vw, 16px);
    }

    .tool-ic {
      flex: none;
      width: 48px;
      height: 48px;
      border-radius: 14px;
      background: radial-gradient(circle at 32% 26%, var(--bg-b) 0%, var(--bg) 78%);
      border: 1px solid rgba(232, 197, 100, .38);
      box-shadow: inset 0 0 0 2px var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 9px;
      transition: border-color .2s;
    }

    .tool-ic i.ph {
      font-size: 23px;
      color: var(--gold-hi);
    }

    .tool-card:hover .tool-ic {
      border-color: var(--gold);
    }

    .tool-ic img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      filter: brightness(1.08);
    }

    .tool-txt {
      min-width: 0;
      flex: 1;
    }

    .tool-txt strong {
      display: block;
      font-size: 1.08rem;
      font-weight: 600;
      letter-spacing: .01em;
      line-height: 1.25;
      transition: color .2s;
    }

    .tool-card:hover .tool-txt strong {
      color: var(--gold-hi);
    }

    .tool-txt span {
      display: block;
      font-size: .82rem;
      color: var(--muted);
      margin-top: 3px;
      line-height: 1.45;
    }

    .tool-arrow {
      flex: none;
      color: var(--gold-hi);
      opacity: .7;
      font-size: 18px;
      transition: transform .2s, opacity .2s;
    }

    .tool-card:hover .tool-arrow {
      transform: translateX(4px);
      opacity: 1;
    }

    .phone-frame {
      position: relative;
      display: block;
      width: 100%;
      /* height: 55vh; */
      /* aspect-ratio: 9 / 19.5; */
      margin: 16px auto 2px;
      border-radius: 0px;
      /* padding: 6px; */
      /* background: linear-gradient(180deg, #1B3E8F 0%, #0E2560 100%); */
      /* border: 1px solid rgba(232, 197, 100, .35); */
      box-shadow: 0 18px 40px -18px rgba(6, 16, 52, .7);
    }

    .phone-frame::before {
      content: "";
      position: absolute;
      left: -1px;
      top: 88px;
      width: 2px;
      height: 26px;
      border-radius: 2px 0 0 2px;
      background: rgba(255, 255, 255, .28);
    }

    .phone-frame::after {
      content: "";
      position: absolute;
      left: 50%;
      top: 14px;
      transform: translateX(-50%);
      z-index: 2;
      width: 38px;
      height: 6px;
      border-radius: 99px;
      background: rgba(255, 255, 255, .28);
    }

    .phone-frame img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center top;
      /* border-radius: 18px; */
    }

    .browser-frame {
      margin-top: 16px;
      /* border: 1px solid rgba(232, 197, 100, .25); */
      border-radius: 14px;
      overflow: hidden;
      /* background: var(--bg); */
    }

    .browser-bar {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 12px;
      background: rgba(255, 255, 255, .04);
      border-bottom: 1px solid var(--hair);
    }

    .browser-dots {
      display: flex;
      gap: 4px;
      flex: none;
    }

    .browser-dots i {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(246, 242, 255, .3);
    }

    .browser-url {
      flex: 1;
      text-align: center;
      min-width: 0;
      font-size: .6rem;
      letter-spacing: .05em;
      color: var(--muted);
      background: rgba(255, 255, 255, .05);
      border-radius: 99px;
      padding: 4px 10px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .browser-frame img {
      display: block;
      width: 100%;
      aspect-ratio: 2.16/1;
      object-fit: cover;
      object-position: center top;
    }

    .more-tools {
      margin-top: 6px;
    }

    .more-label {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .24em;
      text-transform: uppercase;
      color: var(--muted);
      padding: 2px 2px 4px;
    }

    .more-label::after {
      content: "";
      flex: 1;
      height: 1px;
      background: linear-gradient(90deg, var(--hair-strong), transparent);
    }

    .tool-mini {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 13px 4px;
      border-top: 1px solid var(--hair);
      color: var(--ink);
      transition: background .15s;
    }

    .tool-mini:hover {
      background: rgba(232, 197, 100, .03);
    }

    .tool-mini .tool-ic {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      padding: 8px;
    }

    .tool-mini .tool-txt strong {
      font-size: 1rem;
    }

    .tool-mini:hover .tool-txt strong {
      color: var(--gold-hi);
    }

    /* ================= SLIDE 5 : MANTRA ================= */
    .mantra-wrap {
      max-width: 820px;
      margin: 0 auto;
      position: relative;
    }

    .mantra-kicker {
      font-family: var(--font-display);
      color: var(--gold);
      font-size: clamp(1.5rem, 5vw, 2.4rem);
      line-height: 1.05;
    }

    .mantra-kicker .dot {
      color: var(--gold-dim);
    }

    .mantra-sanskrit {
      margin: clamp(20px, 4vh, 30px) 0 6px;
      font-family: var(--font-deva);
      font-size: clamp(2rem, 7vw, 3.4rem);
      line-height: 1.35;
      color: #fffdf8;
      font-weight: 400;
    }

    .mantra-meaning {
      color: var(--muted);
      max-width: 680px;
      margin: clamp(14px, 3vh, 22px) 0 0;
      font-size: .94rem;
      line-height: 1.72;
    }

    .mantra-tip {
      margin: clamp(24px, 5vh, 34px) 0 0;
      max-width: 680px;
      background: rgba(232, 197, 100, .06);
      border: 1px solid var(--gold-dim);
      border-left: 4px solid var(--gold);
      border-radius: 14px;
      padding: 16px 20px;
      font-size: .9rem;
      color: var(--ink);
      line-height: 1.66;
    }

    .mantra-tip strong {
      color: var(--gold-hi);
      font-weight: 600;
    }

    .mantra-sections {
      margin: clamp(18px, 4vh, 26px) 0 0;
      max-width: 680px;
      display: grid;
      gap: clamp(20px, 4vh, 28px);
    }

    .mantra-section-hd {
      font-family: var(--font-display);
      font-size: clamp(1.02rem, 2.4vw, 1.18rem);
      line-height: 1.3;
      color: var(--gold-hi);
      margin: 0 0 .55rem;
      font-weight: 600;
    }

    .mantra-section p {
      color: var(--muted);
      margin: 0 0 .8rem;
      font-size: .94rem;
      line-height: 1.72;
    }

    .mantra-section p:last-child {
      margin-bottom: 0;
    }

    

    /* ================= SLIDE 7 : MYTH VS REALITY ================= */
    .myth-cols {
      width: 100%;
      max-width: 960px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px;
      position: relative;
    }

    .myth-col {
      padding: clamp(18px, 3vh, 26px) clamp(16px, 3vw, 26px);
      border-radius: 20px;
    }

    .myth-col.myth-bad {
      background: var(--caution-soft);
      border: 1px solid rgba(212, 100, 90, .28);
    }

    .myth-col.myth-good {
      background: rgba(232, 197, 100, .06);
      border: 1px solid var(--gold-dim);
    }

    .myth-label {
      display: inline-flex;
      align-items: center;
      gap: 9px;
      font-size: .66rem;
      font-weight: 700;
      letter-spacing: .24em;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 999px;
    }

    .myth-no {
      color: var(--caution);
      border: 1px solid rgba(212, 100, 90, .4);
    }

    .myth-yes {
      color: var(--gold-hi);
      border: 1px solid var(--gold-dim);
    }

    .myth-col h3 {
      font-family: var(--font-display);
      font-weight: 400;
      font-size: 1.5rem;
      margin: 16px 0 8px;
      line-height: 1.25;
      color: var(--ink);
    }

    .myth-col p {
      font-size: .9rem;
      color: var(--muted);
      line-height: 1.72;
    }

    @media(min-width:900px) {
      .myth-cols {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
      }
    }

    /* ================= SLIDE 8 : WATCH & FOLLOW ================= */
    .watch-grid {
      display: flex;
      flex-direction: column;
      gap: clamp(12px, 2vh, 20px);
      max-width: 1080px;
      margin: 0 auto;
      width: 100%;
    }

    .yt-frame {
      border: 1px solid var(--hair-strong);
      border-radius: 16px;
      overflow: hidden;
      background: #16407F;
      box-shadow: 0 24px 54px -24px rgba(6, 16, 52, .6);
      position: relative;
      z-index: 10;
      touch-action: auto;
    }

    .yt-frame iframe {
      width: 100%;
      aspect-ratio: 16/9;
      display: block;
      border: 0;
      pointer-events: auto;
      touch-action: auto;
    }

    .rule-label {
      display: flex;
      align-items: center;
      gap: 14px;
      margin: clamp(16px, 3vh, 28px) auto 12px;
      width: 100%;
      text-align: center;
      font-size: .66rem;
      font-weight: 600;
      letter-spacing: .24em;
      text-transform: uppercase;
      color: var(--gold-hi);
    }

    .rule-label::before,
    .rule-label::after {
      content: "";
      flex: 1;
      height: 1px;
      background: var(--hair);
    }

    .rule-label i {
      color: var(--gold);
      font-size: 14px;
    }

    .rule-label.tight {
      margin-top: 0;
    }

    .channel-links {
      display: flex;
      flex-wrap: wrap;
      gap: 11px;
      margin-top: 14px;
    }

    .insta-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 11px;
    }

    .insta-tile {
      position: relative;
      border-radius: 14px;
      overflow: hidden;
      border: 1px solid var(--hair);
      background: #0E2560;
      cursor: pointer;
      box-shadow: 0 18px 40px -20px rgba(6, 16, 52, .5);
    }

    .insta-tile video {
      width: 100%;
      aspect-ratio: 9/16;
      object-fit: cover;
      display: block;
      pointer-events: none;
    }

    .reel-play {
      position: absolute;
      inset: 0;
      margin: auto;
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: rgba(9, 22, 64, .7);
      color: #fffdf8;
      border: 1px solid var(--gold-dim);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      pointer-events: none;
      transition: opacity .18s, transform .18s;
    }

    .insta-tile.playing .reel-play {
      opacity: 0;
      transform: scale(.85);
    }

    .insta-ic {
      position: absolute;
      top: 9px;
      right: 9px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: rgba(9, 22, 64, .72);
      color: #fffdf8;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    @media(min-width:480px) {
      .insta-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    @media(min-width:1000px) {
      .watch-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        align-items: start;
        column-gap: 44px;
      }
    }

    /* ================= SLIDE 9 : DID YOU KNOW + BLOGS ================= */
    .fact {
      max-width: 820px;
      margin: 0 auto;
      display: flex;
      gap: 18px;
      align-items: flex-start;
    }

    .fact-ic {
      flex: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(232, 197, 100, .1);
      color: var(--gold-hi);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      outline: 1px solid var(--gold-dim);
      outline-offset: 4px;
    }

    .fact h3 {
      font-family: var(--font-display);
      font-weight: 400;
      font-size: 1.6rem;
      color: var(--ink);
      line-height: 1.25;
    }

    .fact p {
      font-size: .9rem;
      color: var(--muted);
      margin-top: 7px;
      line-height: 1.7;
    }

    .blog-list {
      width: 100%;
      max-width: 1020px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr;
      column-gap: 44px;
    }

    .blog-item {
      padding: 15px 4px;
      border-bottom: 1px solid var(--hair);
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .blog-img {
      width: 100%;
      height: auto;
      border-radius: 10px;
      margin-bottom: 12px;
      border: 1px solid var(--hair);
    }

    .blog-cat {
      font-size: .62rem;
      font-weight: 600;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--gold);
    }

    .blog-item h3 {
      font-family: var(--font-body);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--ink);
      margin-top: 6px;
      line-height: 1.45;
      letter-spacing: 0;
    }

    .blog-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      min-height: 40px;
      margin-top: 8px;
      font-size: .84rem;
      font-weight: 600;
      color: var(--gold-hi);
    }

    .blog-link i {
      font-size: 15px;
      transition: transform .15s;
    }

    .blog-link:hover i {
      transform: translateX(3px);
    }

    @media(min-width:700px) {
      .blog-list {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    /* ================= SLIDE 10 : TESTIMONIALS ================= */
    .testi-wrap {
      max-width: 400px;
      margin: 0 auto;
      text-align: center;
      width: 100%;
    }

    .testi-tile {
      width: 100%;
      max-width: 320px;
      margin: 0 auto;
      border-radius: 20px;
      border: 1px solid var(--gold-dim);
      outline: 1px solid var(--hair);
      outline-offset: 5px;
      box-shadow: 0 26px 60px -22px rgba(6, 16, 52, .55);
    }

    .testi-video {
      width: 100%;
      aspect-ratio: 9/16;
      border-radius: 20px;
      background: #16407F;
      object-fit: cover;
      display: block;
    }

    .stars {
      display: flex;
      gap: 6px;
      color: var(--gold);
      justify-content: center;
      margin-top: 22px;
      font-size: 15px;
    }

    .testi-caption {
      font-size: .86rem;
      color: var(--muted);
      margin-top: 12px;
      font-weight: 300;
    }

    /* ================= SLIDE 11 : SHARE & COMMENT ================= */
    .share-success {
      max-width: 560px;
      margin: 0 auto 16px;
      text-align: center;
      background: rgba(232, 197, 100, .08);
      border: 1px solid var(--gold-dim);
      border-radius: 14px;
      padding: 14px 18px;
      color: var(--gold-hi);
      font-weight: 500;
      font-size: .9rem;
    }

    .share-form {
      width: 100%;
      max-width: 560px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .share-form label {
      display: flex;
      flex-direction: column;
      gap: 7px;
      font-size: .82rem;
      font-weight: 500;
      color: var(--ink);
      letter-spacing: .02em;
    }

    .share-form label em {
      font-weight: 400;
      color: var(--muted);
      font-style: normal;
    }

    .share-form input,
    .share-form textarea {
      font-family: inherit;
      font-size: 16px;
      color: var(--ink);
      background: rgba(9, 22, 64, .7);
      border: 1px solid var(--hair-strong);
      border-radius: 12px;
      min-height: 50px;
      padding: 13px 15px;
      outline: none;
      width: 100%;
      resize: vertical;
      transition: border-color .15s;
    }

    .share-form input::placeholder,
    .share-form textarea::placeholder {
      color: rgba(169, 186, 234, .6);
    }

    .share-form input:focus,
    .share-form textarea:focus {
      border-color: var(--gold);
    }

    .share-note {
      font-size: .76rem;
      color: var(--muted);
      text-align: center;
      line-height: 1.55;
    }

    /* ================= SLIDE 12 : FINAL + FOOTER ================= */
    .s-final {
      background:
        radial-gradient(ellipse at 50% 110%, rgba(232, 197, 100, .18), transparent 60%),
        linear-gradient(180deg, var(--bg), var(--bg-a) 55%, var(--bg) 100%);
    }

    .final-wrap {
      text-align: center;
      max-width: 720px;
      margin: 0 auto;
      position: relative;
      z-index: 2;
      width: 100%;
    }

    .final-wrap h2 {
      color: #fffdf8;
      line-height: 1.12;
      font-family: var(--font-display);
      font-weight: 400;
      font-size: clamp(2.4rem, 8vw, 4.2rem);
      margin-top: 14px;
    }

    .final-wrap h2 em {
      color: var(--gold);
      font-style: italic;
    }

    .final-wrap p {
      color: var(--muted);
      max-width: 540px;
      margin: 16px auto 0;
      font-size: .96rem;
      line-height: 1.7;
    }

    .final-actions {
      display: flex;
      flex-direction: column;
      width: min(340px, 100%);
      gap: 12px;
      margin: clamp(24px, 4.5vh, 36px) auto 0;
    }

    @media(min-width:640px) {
      .final-actions {
        flex-direction: row;
        width: auto;
        justify-content: center;
      }
    }

    .foot {
      border-top: 1px solid var(--hair);
      padding-top: 26px;
      margin-top: clamp(34px, 7vh, 52px);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 16px;
      text-align: center;
    }

    .foot-brand {
      display: flex;
      align-items: center;
      gap: 11px;
    }

    .foot-brand img {
      height: 34px;
      width: auto;
      opacity: .95;
    }

    .foot-tag {
      font-size: .68rem;
      letter-spacing: .26em;
      text-transform: uppercase;
      color: var(--muted);
    }

    .foot-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 4px 8px;
      font-size: .84rem;
      color: rgba(246, 242, 255, .72);
    }

    .foot-links a {
      padding: 8px 10px;
      border-radius: 8px;
      min-height: 36px;
      display: inline-flex;
      align-items: center;
    }

    .foot-links a:hover {
      color: var(--gold-hi);
    }

    .foot-social {
      display: flex;
      gap: 12px;
    }

    .soc {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      border: 1px solid var(--gold-dim);
      color: var(--gold-hi);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      transition: background .15s;
    }

    .soc:hover {
      background: rgba(232, 197, 100, .1);
    }

    .foot-copy {
      font-size: .72rem;
      color: rgba(166, 160, 182, .7);
      letter-spacing: .06em;
      font-weight: 400;
    }

    /* ================= TWINKLE (decorative, gold only) ================= */
    .twinkle {
      position: absolute;
      color: var(--gold);
      animation: twink 3.4s ease-in-out infinite;
      pointer-events: none;
      z-index: 1;
    }

    @keyframes twink {

      0%,
      100% {
        opacity: .35;
      }

      50% {
        opacity: .9;
      }
    }

    /* ================= MOTION PREFS ================= */
    @media(prefers-reduced-motion:reduce) {
      .deck {
        transition: none;
      }

      .rv {
        opacity: 1;
        transform: none;
        transition: none;
      }

      .comment-btn .glow,
      .twinkle {
        animation: none;
      }

      * {
        scroll-behavior: auto !important;
      }
    }

    @media(prefers-reduced-transparency:reduce) {

      .chrome-btn,
      .brand-badge,
      .deck-nav,
      .menu-panel {
        background: var(--bg-a);
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
      }

      .wa-modal {
        background: rgba(9, 22, 64, .94);
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
      }

      .s-hero::after {
        opacity: .9;
      }
    }

    /* ================= PAGE LOADER ================= */
    .page-loader {
      position: fixed;
      inset: 0;
      z-index: 1000;
      background: var(--bg);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity .35s ease, visibility .35s ease;
    }

    .page-loader.hidden {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }

    .loader-spinner {
      width: 48px;
      height: 48px;
      border: 2px solid rgba(232, 197, 100, .18);
      border-top-color: var(--gold);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body>

  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="constellation" viewBox="0 0 200 140">
      <g fill="none" stroke="currentColor" stroke-width="1">
        <path d="M20 110 L48 78 L84 96 L124 40 L168 62" />
        <path d="M84 96 L100 118 L140 102 L196 116" />
        <path d="M124 40 L150 20" />
      </g>
      <g fill="currentColor">
        <circle cx="20" cy="110" r="2.8" />
        <circle cx="48" cy="78" r="2" />
        <circle cx="84" cy="96" r="2.8" />
        <circle cx="124" cy="40" r="2" />
        <circle cx="168" cy="62" r="2.8" />
        <circle cx="100" cy="118" r="1.6" />
        <circle cx="140" cy="102" r="2" />
        <circle cx="196" cy="116" r="2" />
        <circle cx="150" cy="20" r="1.8" />
      </g>
    </symbol>
  </svg>

  <div id="pageLoader" class="page-loader" aria-hidden="true">
    <div class="loader-spinner"></div>
  </div>

  <div class="cosmos" aria-hidden="true"></div>

  <div class="progress" aria-hidden="true"><span id="progFill"></span></div>

  <button class="chrome-btn share-btn" id="shareBtn" aria-label="Share newsletter">
    <i class="ph-light ph-share-network" aria-hidden="true"></i>
  </button>
<div class="share-toast" id="shareToast"><?php echo htmlspecialchars($langContent['shareToast']); ?></div>

<div class="lang-switcher" id="langSwitcher">
  <span class="lang-ic"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10A15.3 15.3 0 0 1 12 2z"/></svg></span>
  <button data-lang="en" class="<?php echo $lang==='en'?'active':''; ?>">EN</button>
  <span class="lang-divider"></span>
  <button data-lang="hi" class="<?php echo $lang==='hi'?'active':''; ?>">हि</button>
  <span class="lang-divider"></span>
  <button data-lang="mr" class="<?php echo $lang==='mr'?'active':''; ?>">म</button>
</div>

<button class="chrome-btn comment-btn" id="commentBtn" aria-label="Go to comments">
    <i class="ph-light ph-chat-circle" aria-hidden="true"></i>
    <span class="glow" aria-hidden="true"></span>
  </button>

  <button class="chrome-btn menu-btn" id="menuBtn" aria-label="Open menu" aria-expanded="false" aria-controls="menuPanel">
    <i class="ph-light ph-list ic-open" aria-hidden="true"></i>
    <i class="ph-light ph-x ic-close" aria-hidden="true"></i>
  </button>
  <div class="menu-backdrop" id="menuBackdrop"></div>
  <nav class="menu-panel" id="menuPanel" aria-label="Newsletter sections">
    <span class="menu-title"><?php echo htmlspecialchars($langContent['menu']['title']); ?></span>
    <ul class="menu-list" id="menuList">
      <?php foreach ($langContent['menu']['items'] as $mi => $menuItem): ?>
        <li><a href="#<?php echo ['cover', 'guruji', 'rashifal', 'products', 'mantra', 'myth', 'watch', 'know', 'testimonials', 'share', 'consult'][$mi]; ?>" data-slide="<?php echo $mi; ?>"><span class="menu-no"><?php echo str_pad($mi + 1, 2, '0', STR_PAD_LEFT); ?></span><?php echo htmlspecialchars($menuItem); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <!-- ==================== DECK ==================== -->
  <main class="deck" id="deck">

    <!-- ============ SLIDE 1 : COVER ============ -->
    <section class="slide s-hero" id="cover" aria-label="Cover">
      <svg class="const c-hero-r" viewBox="0 0 200 140" aria-hidden="true">
        <use href="#constellation" />
      </svg>
      <svg class="const c-hero-l" viewBox="0 0 200 140" aria-hidden="true">
        <use href="#constellation" />
      </svg>
      <div class="slide-in">
        <div class="hero-wrap">
          <div class="brand-badge rv">
            <img src="../../assets/astrochitra-logo.png" alt="AstroChitra logo">
            <i></i>
            <span><?php echo htmlspecialchars($langContent['hero']['brandName']); ?></span>
          </div>
          <div class="sec-head center hero-title">
            <span class="kicker rv" style="--d:.1s;"><span class="tick"></span><?php echo htmlspecialchars($langContent['hero']['kicker']); ?><span class="tick right"></span></span>
            <h1 class="rv" style="--d:.18s;"><?php echo $langContent['hero']['headline']; ?></h1>
          </div>
        </div>
      </div>
      <div class="swipe-hint" aria-hidden="true">
        <span class="swipe-txt">Swipe</span>
        <span class="swipe-caret"><i class="ph-light ph-caret-right a"></i><i class="ph-light ph-caret-right b"></i></span>
      </div>
    </section>

    <!-- ============ SLIDE 2 : GURUJI SPEAKS ============ -->
    <section class="slide s-guruji" id="guruji" aria-label="Guruji speaks">
      <svg class="const c-guruji-r" viewBox="0 0 200 140" aria-hidden="true">
        <use href="#constellation" />
      </svg>
      <div class="slide-in">
        <div class="sec-head left rv" style="max-width:720px;">
          <h1><?php echo htmlspecialchars($langContent['guruji']['eyebrowText']); ?></h1>
        </div>
        <div class="guruji-col">
          <div class="guruji-row rv" style="--d:.08s;">
            <img class="guruji-photo" src="../../assets/Guruji.jpg" alt="Portrait of Guruji" loading="lazy" decoding="async">
            <div>
              <?php if (!empty($langContent['guruji']['quoteLabel'])): ?>
                <span class="guruji-quote-label"><?php echo htmlspecialchars($langContent['guruji']['quoteLabel']); ?></span>
              <?php endif; ?>
              <?php foreach ($langContent['guruji']['quoteBlock'] as $q): ?>
                <?php if ($q['lang'] !== $lang) continue; ?>
                <p class="guruji-quote" lang="<?php echo htmlspecialchars($q['lang']); ?>"><?php echo nl2br(htmlspecialchars($q['text'])); ?></p>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="consult-links rv" style="--d:.2s;">
            <a class="btn btn-gold" href="https://astrochitra.com/consultation" target="_blank" rel="noopener"><?php echo htmlspecialchars($langContent['guruji']['bookBtn']); ?></a>
            <a class="quiet-link" href="https://wa.me/919820616655" target="_blank" rel="noopener">
              <i class="ph-light ph-whatsapp-logo" aria-hidden="true"></i>
              <?php echo htmlspecialchars($langContent['guruji']['whatsappLink']); ?>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ SLIDE 3 : RASHIFAL ============ -->
    <section class="slide s-rashifal" id="rashifal" aria-label="Monthly rashifal">
      <div class="slide-in">
        <div class="sec-head center rv">
          <span class="kicker"><span class="tick"></span><?php echo htmlspecialchars($langContent['rashifal']['eyebrowText']); ?><span class="tick right"></span></span>
          <h2><?php echo $langContent['rashifal']['title']; ?></h2>
          <p><?php echo htmlspecialchars($langContent['rashifal']['subtitle']); ?></p>
        </div>
        <div class="rashi-list">
          <?php
          $signFiles = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
          foreach ($langContent['rashifal']['signs'] as $ri => $rashi):
          ?>
            <details class="rashi-card rv" <?php echo $ri === 0 ? ' open' : ''; ?><?php echo $ri > 0 ? ' style="--d:' . sprintf('%.2fs', ($ri % 4) * 0.04) . '"' : ''; ?>>
              <summary class="rashi-card-hdr">
                <div class="rashi-glyph"><img src="../../assets/signs/<?php echo $signFiles[$ri]; ?>.svg" alt="" loading="lazy"></div>
                <div class="rashi-hdr-txt">
                  <h3><?php echo htmlspecialchars($rashi['name']); ?></h3>
                  <span class="rashi-dates"><?php echo htmlspecialchars($rashi['dates']); ?></span>
                </div>
                <span class="rashi-arrow" aria-hidden="true"><i class="ph-light ph-caret-down"></i></span>
              </summary>
              <div class="rashi-card-body">
                <?php if (!empty($rashi['pending'])): ?>
                  <p class="rashi-pending"><i class="ph-light ph-warning" aria-hidden="true"></i><?php echo htmlspecialchars($langContent['rashifal']['pendingLabel']); ?></p>
                <?php endif; ?>
                <p class="rashi-overview"><?php echo htmlspecialchars($rashi['overview']); ?></p>

                <?php if (!empty($rashi['transits'])): ?>
                <div class="tl-head">
                  <span class="tl-head-ic"><i class="ph-light ph-clock" aria-hidden="true"></i></span>
                  <span><?php echo htmlspecialchars($langContent['rashifal']['transitsLabel']); ?></span>
                </div>
                <div class="tl">
                  <?php foreach ($rashi['transits'] as $t): ?>
                    <div class="tl-item">
                      <span class="tl-dot" aria-hidden="true"></span>
                      <?php if (!empty($t['date'])): ?>
                        <span class="tl-date"><?php echo htmlspecialchars($t['date']); ?></span>
                      <?php endif; ?>
                      <?php if (!empty($t['planet']) || !empty($t['planetIcon'])): ?>
                        <div class="tl-planet">
                          <?php if (!empty($t['planetIcon'])): ?>
                            <img src="../../assets/planets/<?php echo htmlspecialchars($t['planetIcon']); ?>.svg" alt="" loading="lazy">
                          <?php endif; ?>
                          <?php if (!empty($t['planet'])): ?>
                            <span class="tl-planet-name"><?php echo htmlspecialchars($t['planet']); ?></span>
                          <?php endif; ?>
                          <?php if (!empty($t['from']) || !empty($t['to'])): ?>
                            <span class="tl-planet-name"><b>&middot;</b> <?php echo htmlspecialchars($t['from'] ?? ''); ?> &rarr; <?php echo htmlspecialchars($t['to'] ?? ''); ?></span>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                      <?php if (!empty($t['house'])): ?>
                        <span class="tl-house"><?php echo htmlspecialchars($t['house']); ?></span>
                      <?php endif; ?>
                      <p class="tl-effect"><?php echo htmlspecialchars($t['effect']); ?></p>
                    </div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($rashi['extraTransits'])): ?>
                  <div class="rashi-extra">
                    <div class="rashi-extra-hdr">
                      <i class="ph-light ph-planet" aria-hidden="true"></i>
                      <span><?php echo htmlspecialchars($langContent['rashifal']['extraTransitsLabel']); ?></span>
                    </div>
                    <?php foreach ($rashi['extraTransits'] as $ex): ?>
                      <div class="rashi-extra-item">
                        <span class="rashi-extra-dot" aria-hidden="true"></span>
                        <div>
                          <strong><?php echo htmlspecialchars($ex['planet']); ?> <b>&middot;</b> <?php echo htmlspecialchars($ex['position']); ?></strong>
                          <p><?php echo htmlspecialchars($ex['effect']); ?></p>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <div class="rashi-best">
                  <div class="rashi-best-hdr"><i class="ph-light ph-check" aria-hidden="true"></i>
                    <strong><?php echo htmlspecialchars($langContent['rashifal']['bestDaysLabel']); ?></strong>
                  </div>
                  <span class="rashi-days"><?php echo htmlspecialchars($rashi['bestDays']['range']); ?></span>
                  <p><?php echo htmlspecialchars($rashi['bestDays']['note']); ?></p>
                </div>

                <div class="rashi-caution">
                  <div class="rashi-caution-hdr"><i class="ph-light ph-seal-question" aria-hidden="true"></i>
                    <strong><?php echo htmlspecialchars($langContent['rashifal']['cautionDaysLabel']); ?></strong>
                  </div>
                  <span class="rashi-days"><?php echo htmlspecialchars($rashi['cautionDays']['range']); ?></span>
                  <p><?php echo htmlspecialchars($rashi['cautionDays']['note']); ?></p>
                </div>
              </div>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ============ SLIDE 4 : WEB PRODUCTS ============ -->
    <section class="slide s-products" id="products" aria-label="AstroChitra web products">
      <div class="slide-in">
        <div class="sec-head left rv">
          <h1><?php echo htmlspecialchars($langContent['products']['title']); ?></h1>
          <p><?php echo htmlspecialchars($langContent['products']['subtitle']); ?></p>
        </div>
        <div class="prod-showcase">
          <?php
          $piAll = $langContent['products']['items'];
          $show = [
            ['i' => 4, 'ic' => 'panchang_icon.svg', 'url' => 'panchang.astrochitra.com', 'href' => 'https://panchang.astrochitra.com/', 'shot' => 'panchang_app_ss.png', 'alt' => 'Panchang app screenshot'],
            ['i' => 3, 'ic' => 'journal_icon.svg', 'url' => 'blogs.astrochitra.com', 'href' => 'https://blogs.astrochitra.com/', 'shot' => 'journal_desktop_ss.png', 'alt' => 'AstroChitra Journal website screenshot'],
            ['i' => 5, 'ic' => 'ask', 'url' => 'ask.astrochitra.com', 'href' => 'https://ask.astrochitra.com/', 'shot' => 'ask_ss.png', 'alt' => 'Ask Guruji website screenshot']
          ];
          foreach ($show as $si => $s):
            $P = $piAll[$s['i']];
          ?>
            <a href="<?php echo $s['href']; ?>" target="_blank" rel="noopener" class="tool-card rv" <?php echo ' style="--d:' . sprintf('%.2fs', ($si + 1) * 0.05) . '"'; ?>>
              <span class="tool-hdr">
                <span class="tool-ic">
                  <?php if ($s['ic'] === 'ask'): ?>
                    <i class="ph-light ph-chat-circle" aria-hidden="true"></i>
                  <?php else: ?>
                    <img src="../../assets/svg_icons/<?php echo $s['ic']; ?>" alt="" loading="lazy">
                  <?php endif; ?>
                </span>
                <span class="tool-txt"><strong><?php echo htmlspecialchars($P['name']); ?></strong><span><?php echo htmlspecialchars($P['desc']); ?></span></span>
                <span class="tool-arrow"><i class="ph-light ph-arrow-right" aria-hidden="true"></i></span>
              </span>
              <?php if ($s['i'] === 4): ?>
                <span class="phone-frame"><img src="../../assets/october_2026/<?php echo $s['shot']; ?>" alt="<?php echo htmlspecialchars($s['alt']); ?>" loading="lazy"></span>
              <?php else: ?>
                <span class="browser-frame">
                  <span class="browser-bar"><span class="browser-dots"><i></i><i></i><i></i></span><span class="browser-url"><?php echo $s['url']; ?></span></span>
                  <img src="../../assets/october_2026/<?php echo $s['shot']; ?>" alt="<?php echo htmlspecialchars($s['alt']); ?>" loading="lazy">
                </span>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>

          <div class="more-tools">
            <span class="more-label">More from AstroChitra</span>
            <?php
            $quick = [
              ['i' => 0, 'ic' => 'kundli_icon.svg', 'href' => 'https://astrochitra.com/kundli'],
              ['i' => 1, 'ic' => 'matchmaking_icon.svg', 'href' => 'https://astrochitra.com/matchmaking'],
              ['i' => 2, 'ic' => 'insights_icon.svg', 'href' => 'https://astrochitra.com/insights']
            ];
            foreach ($quick as $qi => $q):
              $Q = $piAll[$q['i']];
            ?>
              <a href="<?php echo $q['href']; ?>" target="_blank" rel="noopener" class="tool-mini rv" <?php echo ' style="--d:' . sprintf('%.2fs', ($qi + 1) * 0.05) . '"'; ?>>
                <span class="tool-ic"><img src="../../assets/svg_icons/<?php echo $q['ic']; ?>" alt="" loading="lazy"></span>
                <span class="tool-txt"><strong><?php echo htmlspecialchars($Q['name']); ?></strong><span><?php echo htmlspecialchars($Q['desc']); ?></span></span>
                <span class="tool-arrow"><i class="ph-light ph-arrow-right" aria-hidden="true"></i></span>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ SLIDE 8 : WATCH & FOLLOW ============ -->
    <section class="slide s-watch" id="watch" aria-label="Watch and follow">
      <div class="slide-in">
        <div class="sec-head center rv">
          <h2><?php echo htmlspecialchars($langContent['watch']['title']); ?></h2>
          <p><?php echo htmlspecialchars($langContent['watch']['subtitle']); ?></p>
        </div>
        <div class="watch-grid">
          <div class="rv">
            <p class="rule-label tight"><?php echo htmlspecialchars($langContent['watch']['youtubeLabel']); ?></p>
            <div class="yt-frame">
              <iframe src="https://www.youtube.com/embed/mHkaw76botE?si=1zGQiLN66gboMP9h" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div class="channel-links">
              <a href="https://www.youtube.com/@AstroChitraAstrology" target="_blank" rel="noopener" class="btn btn-outline">
                <i class="ph-light ph-youtube-logo" aria-hidden="true"></i>
                <?php echo htmlspecialchars($langContent['watch']['visitChannel']); ?>
              </a>
            </div>
          </div>
          <div class="rv" style="--d:.1s;">
            <p class="rule-label tight"><?php echo htmlspecialchars($langContent['watch']['instagramLabel']); ?></p>
            <div class="insta-grid">
              <?php foreach ($langContent['watch']['reels'] as $ri => $reel): ?>
              <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="<?php echo htmlspecialchars('Play Instagram reel ' . ($ri + 1)); ?>">
                <video src="<?php echo htmlspecialchars($reel['video']); ?>" poster="<?php echo htmlspecialchars($reel['thumbnail']); ?>" muted loop playsinline preload="none"></video>
                <span class="reel-play"><i class="ph-light ph-play" aria-hidden="true"></i></span>
                <span class="insta-ic"><i class="ph-light ph-instagram-logo" aria-hidden="true"></i></span>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="channel-links">
              <a href="https://www.instagram.com/astrochitra.official/" target="_blank" rel="noopener" class="btn btn-ink">
                <i class="ph-light ph-instagram-logo" aria-hidden="true"></i>
                <?php echo htmlspecialchars($langContent['watch']['followInsta']); ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ SLIDE 5 : MANTRA ============ -->
    <section class="slide s-mantra" id="mantra" aria-label="Mantra of the month">
      <svg class="const c-mantra-l" viewBox="0 0 200 140" aria-hidden="true">
        <use href="#constellation" />
      </svg>
      <div class="slide-in">
        <div class="mantra-wrap">
          <h2 class="mantra-kicker rv"><?php echo htmlspecialchars($langContent['mantra']['kicker']); ?><span class="dot">.</span></h2>
          <p class="mantra-sanskrit rv" style="--d:.1s;" lang="hi"><?php echo $langContent['mantra']['sanskrit']; ?></p>
          <div class="mantra-sections">
            <?php foreach ($langContent['mantra']['sections'] as $si => $sec): ?>
              <div class="mantra-section rv" style="--d:<?php echo sprintf('%.2fs', .22 + $si * .06); ?>">
                <h3 class="mantra-section-hd"><?php echo htmlspecialchars($sec['heading']); ?></h3>
                <?php foreach ($sec['paras'] as $para): ?>
                  <p><?php echo htmlspecialchars($para); ?></p>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    

    <!-- ============ SLIDE 7 : MYTH VS REALITY ============ -->
    <section class="slide s-myth" id="myth" aria-label="Myth vs reality">
      <div class="slide-in">
        <div class="sec-head center rv">
          <h1><?php echo htmlspecialchars($langContent['myth']['title']); ?></h1>
          <p><?php echo htmlspecialchars($langContent['myth']['subtitle']); ?></p>
        </div>
        <div class="myth-cols">
          <div class="myth-col myth-bad rv">
            <span class="myth-label myth-no"><i class="ph-light ph-x" aria-hidden="true"></i><?php echo htmlspecialchars($langContent['myth']['mythLabel']); ?></span>
            <h3><?php echo htmlspecialchars($langContent['myth']['topic']); ?></h3>
            <?php foreach ($langContent['myth']['mythText'] as $mpara): ?>
              <p><?php echo htmlspecialchars($mpara); ?></p>
            <?php endforeach; ?>
          </div>
          <div class="myth-col myth-good rv" style="--d:.08s;">
            <span class="myth-label myth-yes"><i class="ph-light ph-check" aria-hidden="true"></i><?php echo htmlspecialchars($langContent['myth']['realityLabel']); ?></span>
            <?php foreach ($langContent['myth']['realityText'] as $rpara): ?>
              <p><?php echo htmlspecialchars($rpara); ?></p>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>


    <!-- ============ SLIDE 9 : DID YOU KNOW + BLOGS ============ -->
    <section class="slide s-know" id="know" aria-label="Did you know and blogs">
      <div class="slide-in">
        <div class="sec-head left rv">
          <h1><?php echo htmlspecialchars($langContent['know']['title']); ?></h1>
        </div>

        <div class="blog-list">
          <?php foreach ($langContent['know']['blogs'] as $bi => $blog): ?>
            <article class="blog-item rv" <?php echo $bi > 0 ? ' style="--d:' . sprintf('%.2fs', $bi * 0.05) . '"' : ''; ?>>
              <img class="blog-img" src="../../assets/october_2026/blogs/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" loading="lazy" decoding="async">
              <span class="blog-cat"><?php echo htmlspecialchars($blog['category']); ?></span>
              <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
              <a href="<?php echo htmlspecialchars($blog['url']); ?>" target="_blank" rel="noopener" class="blog-link"><?php echo htmlspecialchars($langContent['know']['readArticle']); ?>
                <i class="ph-light ph-arrow-right" aria-hidden="true"></i>
              </a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- ============ SLIDE 10 : TESTIMONIALS ============ -->
    <section class="slide s-testi" id="testimonials" aria-label="Testimonials">
      <div class="slide-in">
        <div class="sec-head center rv">
          <h2><?php echo htmlspecialchars($langContent['testimonials']['title']); ?></h2>
        </div>
        <div class="testi-wrap">
          <div class="insta-tile testi-tile rv" data-reel role="button" tabindex="0" aria-label="Play testimonial video">
            <video class="testi-video" src="../../assets/september_2026/testimonial.mp4" poster="../../assets/september_2026/testimonial_thumbnail.jpg" muted loop playsinline preload="none"></video>
            <span class="reel-play"><i class="ph-light ph-play" aria-hidden="true"></i></span>
          </div>
          <div class="stars rv" aria-hidden="true">
            <i class="ph-light ph-star"></i><i class="ph-light ph-star"></i><i class="ph-light ph-star"></i><i class="ph-light ph-star"></i><i class="ph-light ph-star"></i>
          </div>
          <p class="testi-caption rv">A reader shares how the monthly guidance landed for her.</p>
        </div>
      </div>
    </section>

    <!-- ============ SLIDE 11 : SHARE & COMMENT ============ -->
    <section class="slide s-share" id="share" aria-label="Share and comment">
      <div class="slide-in">
        <div class="sec-head center rv">
          <h2><?php echo htmlspecialchars($langContent['share']['title']); ?></h2>
          <p><?php echo htmlspecialchars($langContent['share']['subtitle']); ?></p>
        </div>
        <?php if (isset($_GET['shared'])): ?>
          <div class="share-success"><?php echo htmlspecialchars($langContent['share']['successMsg']); ?></div>
        <?php endif; ?>
        <form method="post" action="../../api/interaction.php" class="share-form rv" style="--d:.08s;">
          <input type="hidden" name="slug" value="october-2026">
          <input type="hidden" name="redirect" value="<?php echo htmlspecialchars(strtok($_SERVER['REQUEST_URI'] ?? '/2026/october', '?')); ?>">
          <label><?php echo htmlspecialchars($langContent['share']['emailLabel']); ?>
            <input type="email" name="email" placeholder="<?php echo htmlspecialchars($langContent['share']['emailPlaceholder']); ?>" autocomplete="email" inputmode="email" required>
          </label>
          <label><?php echo htmlspecialchars($langContent['share']['phoneLabel']); ?> <em><?php echo htmlspecialchars($langContent['share']['phoneOptional']); ?></em>
            <input type="tel" name="phone" placeholder="<?php echo htmlspecialchars($langContent['share']['phonePlaceholder']); ?>" autocomplete="tel" inputmode="tel">
          </label>
          <label><?php echo htmlspecialchars($langContent['share']['commentLabel']); ?> <em><?php echo htmlspecialchars($langContent['share']['commentOptional']); ?></em>
            <textarea name="message" rows="3" placeholder="<?php echo htmlspecialchars($langContent['share']['commentPlaceholder']); ?>"></textarea>
          </label>
          <button type="submit" class="btn btn-gold" style="width:100%;"><?php echo htmlspecialchars($langContent['share']['submitBtn']); ?></button>
          <p class="share-note"><?php echo htmlspecialchars($langContent['share']['privacyNote']); ?></p>
        </form>
      </div>
    </section>

    <!-- ============ SLIDE 12 : FINAL CTA + FOOTER ============ -->
    <section class="slide s-final" id="consult" aria-label="Consultation and footer">
      <svg class="const c-final-r" viewBox="0 0 200 140" aria-hidden="true">
        <use href="#constellation" />
      </svg>
      <i class="ph-light ph-sparkle twinkle" style="top:16%;left:12%;font-size:15px;" aria-hidden="true"></i>
      <i class="ph-light ph-sparkle twinkle" style="top:70%;left:22%;font-size:11px;animation-delay:.8s;" aria-hidden="true"></i>
      <div class="slide-in">
        <div class="final-wrap">
          <h2 class="rv"><?php echo $langContent['final']['title']; ?></h2>
          <p class="rv" style="--d:.1s;"><?php echo htmlspecialchars($langContent['final']['subtitle']); ?></p>
          <div class="final-actions rv" style="--d:.18s;">
            <a href="tel:+919820616655" class="btn btn-gold">
              <i class="ph-light ph-phone-call" aria-hidden="true"></i>
              <?php echo htmlspecialchars($langContent['final']['callBtn']); ?>
            </a>
            <button class="btn btn-outline" data-goto="10">
              <i class="ph-light ph-share-network" aria-hidden="true"></i>
              <?php echo htmlspecialchars($langContent['final']['shareBtn']); ?>
            </button>
          </div>
          <footer class="foot rv" style="--d:.26s;">
            <div class="foot-brand">
              <img src="../../assets/astrochitra-logo.png" alt="AstroChitra logo" loading="lazy">
              <span class="foot-tag"><?php echo htmlspecialchars($langContent['final']['footerTag']); ?></span>
            </div>
            <nav class="foot-links" aria-label="Footer">
              <?php foreach ($langContent['final']['footerLinks'] as $fl): ?>
                <a href="<?php echo htmlspecialchars($fl['url']); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($fl['text']); ?></a>
              <?php endforeach; ?>
            </nav>
            <div class="foot-social">
              <a href="https://www.youtube.com/@AstroChitraAstrology" target="_blank" rel="noopener" class="soc" aria-label="YouTube">
                <i class="ph-light ph-youtube-logo" aria-hidden="true"></i>
              </a>
              <a href="https://www.instagram.com/astrochitra.official/" target="_blank" rel="noopener" class="soc" aria-label="Instagram">
                <i class="ph-light ph-instagram-logo" aria-hidden="true"></i>
              </a>
              <a href="https://www.facebook.com/p/Astro-Chitra-61587728232221/" target="_blank" rel="noopener" class="soc" aria-label="Facebook">
                <i class="ph-light ph-facebook-logo" aria-hidden="true"></i>
              </a>
            </div>
            <p class="foot-copy"><?php echo htmlspecialchars($langContent['final']['footerCopy']); ?></p>
          </footer>
        </div>
      </div>
    </section>

  </main>

  <!-- ==================== FIXED BOTTOM BAR ==================== -->
  <div class="footer-bar">
    <button class="wa-sub" id="waSubBtn" aria-haspopup="dialog" aria-controls="waModal" aria-label="Subscribe to the newsletter">
      <i class="ph-light ph-whatsapp-logo" aria-hidden="true"></i>
      <?php echo htmlspecialchars($langContent['waPill']); ?>
    </button>

    <div class="deck-nav" id="deckNav">
      <button class="nav-btn" id="prevBtn" aria-label="Previous section">
        <i class="ph-light ph-caret-left" aria-hidden="true"></i>
      </button>
      <div class="segs" id="segs" role="tablist" aria-label="Sections"></div>
      <span class="nav-count" aria-hidden="true"><b id="curNo">01</b>/<span id="totNo">12</span></span>
      <button class="nav-btn" id="nextBtn" aria-label="Next section">
        <i class="ph-light ph-caret-right" aria-hidden="true"></i>
      </button>
    </div>
  </div>

  <div class="wa-modal" id="waModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="waModalTitle">
    <div class="wa-modal-card">
      <button class="wa-modal-close" id="waModalClose" aria-label="Close">
        <i class="ph-light ph-x" aria-hidden="true"></i>
      </button>
      <div class="wa-modal-ic"><i class="ph-light ph-whatsapp-logo" aria-hidden="true"></i></div>
      <h3 id="waModalTitle"><?php echo htmlspecialchars($langContent['whatsappModal']['title']); ?></h3>
      <p><?php echo htmlspecialchars($langContent['whatsappModal']['text']); ?></p>
      <a href="https://chat.whatsapp.com/ELhfV9OyIldBs6H4mjMLW5" target="_blank" rel="noopener" class="btn btn-gold" style="width:100%;">
        <i class="ph-light ph-arrow-right" aria-hidden="true"></i>
        <?php echo htmlspecialchars($langContent['whatsappModal']['joinBtn']); ?>
      </a>
    </div>
  </div>

  <script>
    (function() {
      var deck = document.getElementById('deck'),
        slides = [].slice.call(document.querySelectorAll('.slide')),
        segsWrap = document.getElementById('segs'),
        prevBtn = document.getElementById('prevBtn'),
        nextBtn = document.getElementById('nextBtn'),
        progFill = document.getElementById('progFill'),
        curNo = document.getElementById('curNo'),
        totNo = document.getElementById('totNo'),
        menuBtn = document.getElementById('menuBtn');

      var names = <?php echo json_encode($langContent['menu']['items'], JSON_UNESCAPED_UNICODE); ?>;
      var idx = 0;

      totNo.textContent = (slides.length < 10 ? '0' : '') + slides.length;

      var segsTrack = document.createElement('div');
      segsTrack.className = 'segs-track';
      segsWrap.appendChild(segsTrack);

      slides.forEach(function(_, i) {
        var s = document.createElement('button');
        s.className = 'seg';
        s.type = 'button';
        s.setAttribute('role', 'tab');
        s.setAttribute('aria-label', 'Go to ' + (names[i] || ('Section ' + (i + 1))));
        s.appendChild(document.createElement('i'));
        s.addEventListener('click', function() {
          goTo(i);
        });
        segsTrack.appendChild(s);
      });
      var segs = [].slice.call(segsTrack.children);

      function pad(n) {
        return n < 10 ? '0' + n : '' + n;
      }

      function render() {
        deck.style.transform = 'translateX(-' + (idx * 100) + '%)';
        segs.forEach(function(s, i) {
          s.classList.toggle('on', i === idx);
          s.classList.toggle('done', i < idx);
          s.setAttribute('aria-selected', i === idx ? 'true' : 'false');
        });
        slides.forEach(function(s, i) {
          s.classList.toggle('active', i === idx);
        });
        prevBtn.disabled = (idx === 0);
        nextBtn.disabled = (idx === slides.length - 1);
        progFill.style.width = (((idx + 1) / slides.length) * 100) + '%';
        curNo.textContent = pad(idx + 1);
        var el = segs[idx],
          clip = segsWrap.clientWidth;
        var center = segsTrack.offsetLeft + el.offsetLeft + el.offsetWidth / 2;
        var tx = Math.max((clip - segsTrack.scrollWidth), Math.min(0, (clip / 2 - center)));
        segsTrack.style.transform = 'translateX(' + tx + 'px)';
        [].forEach.call(document.querySelectorAll('#menuList a'), function(a) {
          a.classList.toggle('current', parseInt(a.dataset.slide, 10) === idx);
        });
        if (history.replaceState) {
          var u = new URL(window.location.href);
          u.hash = slides[idx].id;
          history.replaceState(null, '', u.toString());
        }
      }

      function goTo(i, instant) {
        i = Math.max(0, Math.min(slides.length - 1, i));
        if (instant) {
          deck.style.transition = 'none';
          segsTrack.classList.add('no-anim');
        }
        idx = i;
        render();
        var inner = slides[i].querySelector('.slide-in');
        if (inner) {
          inner.scrollTop = 0;
        }
        if (instant) {
          void deck.offsetWidth;
          deck.style.transition = '';
          segsTrack.classList.remove('no-anim');
        }
      }

      prevBtn.addEventListener('click', function() {
        goTo(idx - 1);
      });
      nextBtn.addEventListener('click', function() {
        goTo(idx + 1);
      });

      document.querySelectorAll('[data-goto]').forEach(function(b) {
        b.addEventListener('click', function() {
          goTo(parseInt(b.dataset.goto, 10));
        });
      });

      document.querySelectorAll('#menuList a').forEach(function(a) {
        a.addEventListener('click', function(e) {
          e.preventDefault();
          openMenu(false);
          goTo(parseInt(a.dataset.slide, 10));
        });
      });

      function openMenu(o) {
        document.body.classList.toggle('menu-open', o);
        menuBtn.setAttribute('aria-expanded', o ? 'true' : 'false');
        menuBtn.setAttribute('aria-label', o ? 'Close menu' : 'Open menu');
      }
      menuBtn.addEventListener('click', function() {
        openMenu(!document.body.classList.contains('menu-open'));
      });
      document.getElementById('menuBackdrop').addEventListener('click', function() {
        openMenu(false);
      });

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          openMenu(false);
        }
        var t = e.target;
        if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || t.isContentEditable)) {
          return;
        }
        if (e.key === 'ArrowRight' || e.key === 'PageDown') {
          e.preventDefault();
          goTo(idx + 1);
        } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
          e.preventDefault();
          goTo(idx - 1);
        } else if (e.key === 'Home') {
          e.preventDefault();
          goTo(0);
        } else if (e.key === 'End') {
          e.preventDefault();
          goTo(slides.length - 1);
        }
      });

      /* touch swipe with axis lock (vertical scroll inside slides still works) */
      var sx = 0,
        sy = 0,
        st = 0,
        axis = null,
        ignoreTouch = false;

      function noSwipe(el) {
        return el.closest && el.closest('.deck-nav,.menu-panel');
      }
      deck.addEventListener('touchstart', function(e) {
        if (e.touches.length !== 1) {
          axis = null;
          ignoreTouch = true;
          return;
        }
        if (noSwipe(e.target)) {
          axis = null;
          ignoreTouch = true;
          return;
        }
        ignoreTouch = false;
        sx = e.touches[0].clientX;
        sy = e.touches[0].clientY;
        st = Date.now();
        axis = null;
      }, {
        passive: true
      });

      deck.addEventListener('touchmove', function(e) {
        if (ignoreTouch) {
          return;
        }
        if (axis === 'y' || e.touches.length !== 1) {
          return;
        }
        var dx = e.touches[0].clientX - sx,
          dy = e.touches[0].clientY - sy;
        if (!axis) {
          if (Math.abs(dx) > 8 || Math.abs(dy) > 8) {
            axis = Math.abs(dx) > Math.abs(dy) * 1.2 ? 'x' : 'y';
          }
          if (axis !== 'x') {
            return;
          }
        }
        if ((idx === 0 && dx > 0) || (idx === slides.length - 1 && dx < 0)) {
          return;
        }
        e.preventDefault();
        deck.style.transition = 'none';
        deck.style.transform = 'translateX(calc(-' + (idx * 100) + '% + ' + dx + 'px))';
      }, {
        passive: false
      });

      deck.addEventListener('touchend', function(e) {
        if (ignoreTouch) {
          return;
        }
        if (axis !== 'x') {
          axis = null;
          return;
        }
        axis = null;
        var dx = (e.changedTouches[0].clientX - sx),
          dt = Date.now() - st;
        deck.style.transition = '';
        deck.style.transform = 'translateX(-' + (idx * 100) + '%)';
        if (dt < 900 && Math.abs(dx) > 56) {
          goTo(idx + (dx < 0 ? 1 : -1));
        }
      });

      /* share button */
      var shareBtn = document.getElementById('shareBtn'),
        toast = document.getElementById('shareToast');

      function showToast() {
        toast.classList.add('show');
        setTimeout(function() {
          toast.classList.remove('show');
        }, 2200);
      }
      shareBtn.addEventListener('click', function() {
        var url = location.href.split('#')[0];
        if (navigator.share) {
          navigator.share({
            title: document.title,
            url: url
          }).catch(function() {});
        } else if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(showToast, function() {});
        }
      });

      /* comment button */
      document.getElementById('commentBtn').addEventListener('click', function() {
        goTo(10);
      });

      /* WhatsApp subscribe popup */
      var waSubBtn = document.getElementById('waSubBtn'),
        waModal = document.getElementById('waModal'),
        waModalClose = document.getElementById('waModalClose');

      function openWa() {
        waModal.classList.add('open');
        waModal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        waModalClose.focus();
      }

      function closeWa() {
        waModal.classList.remove('open');
        waModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        waSubBtn.focus();
      }
      waSubBtn.addEventListener('click', openWa);
      waModalClose.addEventListener('click', closeWa);
      waModal.addEventListener('click', function(e) {
        if (e.target === waModal) {
          closeWa();
        }
      });
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && waModal.classList.contains('open')) {
          closeWa();
        }
      });

      /* language switcher — preserves current hash */
      document.querySelectorAll('#langSwitcher button').forEach(function(btn){
        btn.addEventListener('click',function(){
          var newLang=btn.getAttribute('data-lang');
          if(newLang==='<?php echo $lang; ?>')return;
          var u=new URL(window.location.href);
          u.searchParams.set('lang',newLang);
          window.location.href=u.toString();
        });
      });

      /* reels */
      var reels = document.querySelectorAll('[data-reel]');

      function pauseOthers(cur) {
        reels.forEach(function(t) {
          var v = t.querySelector('video');
          if (v && v !== cur) {
            v.pause();
          }
        });
      }
      reels.forEach(function(tile) {
        var video = tile.querySelector('video');

        function toggle() {
          if (!video) {
            return;
          }
          if (video.paused) {
            pauseOthers(video);
            video.muted = false;
            video.play().catch(function() {});
          } else {
            video.pause();
          }
        }
        tile.addEventListener('click', toggle);
        tile.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle();
          }
        });
        if (video) {
          video.addEventListener('play', function() {
            tile.classList.add('playing');
          });
          video.addEventListener('pause', function() {
            tile.classList.remove('playing');
          });
        }
      });

      /* no-scroll on slides whose content fits the viewport */
      function checkFit() {
        slides.forEach(function(s) {
          var inner = s.querySelector('.slide-in');
          if (!inner) {
            return;
          }
          s.classList.toggle('fits', inner.scrollHeight <= s.clientHeight + 1);
        });
      }
      var rzT = null;
      window.addEventListener('resize', function() {
        clearTimeout(rzT);
        rzT = setTimeout(checkFit, 120);
      });
      window.addEventListener('orientationchange', function() {
        setTimeout(checkFit, 250);
      });
      window.addEventListener('load', checkFit);
      setTimeout(checkFit, 350);

      /* init from hash */
      var h = location.hash.replace('#', '');
      var start = slides.findIndex(function(s) {
        return s.id === h;
      });
      if (start < 0) {
        start = 0;
      }
      deck.style.transition = 'none';
      idx = start;
      render();
      var initInner = slides[start].querySelector('.slide-in');
      if (initInner) {
        initInner.scrollTop = 0;
      }
      requestAnimationFrame(function() {
        requestAnimationFrame(function() {
          deck.style.transition = '';
        });
      });

      // hide page loader
      var loader = document.getElementById('pageLoader');
      if (loader) {
        loader.classList.add('hidden');
      }
    })();
  </script>

  <script src="../../assets/js/ac-track.js" data-slug="october-2026" defer></script>

</body>

</html>