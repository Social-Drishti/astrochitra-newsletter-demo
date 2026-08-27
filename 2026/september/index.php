<?php
$lang = isset($_GET['lang']) && in_array($_GET['lang'], ['en','hi','mr']) ? $_GET['lang'] : 'en';
$jsonPath = __DIR__ . '/content.json';
$contentAll = json_decode(file_get_contents($jsonPath), true);
$langContent = $contentAll[$lang] ?? $contentAll['en'];
$title = $langContent['meta']['title'] ?? 'AstroChitra Monthly Newsletter | September 2026';

header('Cache-Control: public, max-age=3600, stale-while-revalidate=86400');
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/../../admin/db.php';
if (function_exists('track_view')) {
    track_view($pdo, 'september-2026', 'September 2026 | AstroChitra Monthly Newsletter');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#2b1005">
<title><?php echo htmlspecialchars($title); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Dekko&family=Nunito+Sans:wght@400;600;700;900&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Dekko&family=Nunito+Sans:wght@400;600;700;900&display=swap" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Dekko&family=Nunito+Sans:wght@400;600;700;900&display=swap"></noscript>
<style>
  @font-face{font-family:'AstroChitra';src:url('../../assets/AstroChitra.ttf') format('truetype');font-weight:normal;font-style:normal;font-display:swap;}

  /* ================= TOKENS ================= */
  :root{
    --cream:#faf6ee; --parchment:#f4ecdd; --paper:#fffdf8;
    --ink:#33270e; --muted:#7a6a4f;
    --gold:#c9a227; --gold-light:#f0d98a; --gold-soft:rgba(240,217,138,.35);
    --hairline:rgba(240,217,138,.26);
    --rule:rgba(139,69,19,.22);
    --terracotta:#8b4513; --crimson:#ae172a; --crimson-dark:#7d0f1d;
    --olive:#654e12; --night:#2b1005; --cocoa:#471d0b;
    --line:#e4d9c3;
    --nav-h:64px;
    --safe-t:env(safe-area-inset-top,0px);
    --safe-b:env(safe-area-inset-bottom,0px);
    --chrome-t:calc(12px + var(--safe-t));
    --pad-t:calc(var(--safe-t) + 76px);
    --pad-b:calc(var(--nav-h) + var(--safe-b) + 36px);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html,body{height:100%;}
  body{
    font-family:<?php echo $lang==='en' ? "'Nunito Sans',sans-serif" : "'Dekko',cursive"; ?>,Georgia,'Times New Roman',serif;
    background:var(--cream); color:var(--ink);
    line-height:1.6; overflow:hidden;
    -webkit-font-smoothing:antialiased;
    overscroll-behavior:none;
  }
  img{max-width:100%;display:block;}
  a{text-decoration:none;color:inherit;}
  button{font-family:inherit;color:inherit;}
  :focus-visible{outline:2px solid var(--gold);outline-offset:3px;border-radius:6px;}

  /* ================= DECK ================= */
  .deck{
    position:fixed; inset:0;
    display:flex;
    transition:transform .55s cubic-bezier(.77,0,.18,1);
    will-change:transform;
  }
  .slide{
    flex:0 0 100%; height:100%;
    overflow:hidden;
    overscroll-behavior:contain;
    touch-action:pan-y;
    position:relative;
  }
  .slide.fits{overflow-y:hidden;}
  .slide-in{
    height:100%;
    display:flex; flex-direction:column; justify-content:center;
    padding:var(--pad-t) clamp(18px,5vw,32px) var(--pad-b);
    width:100%; max-width:1240px; margin:0 auto;
    position:relative; z-index:1;
    overflow-y:auto; overflow-x:hidden;
    -webkit-overflow-scrolling:touch;
    scrollbar-width:thin; scrollbar-color:rgba(139,69,19,.35) transparent;
  }
  .slide-in::-webkit-scrollbar{width:5px;}
  .slide-in::-webkit-scrollbar-thumb{background:rgba(139,69,19,.3);border-radius:99px;}
  .slide.fits .slide-in{overflow-y:hidden;}
  .slide:not(.fits) .slide-in{justify-content:flex-start;}

  .s-hero,.s-mantra,.s-final{background:var(--night);color:var(--cream);}
  .s-guruji,.s-transits,.s-know,.s-share{background:var(--parchment);}
  .s-rashifal,.s-myth,.s-watch,.s-testi{background:var(--cream);}
  .s-products{background:var(--cocoa);color:#f3e3cf;}

  .bg-texture::before,
  .s-hero::before,.s-final::before,.s-products::before{
    content:""; position:absolute; inset:0;
    background:url('../../assets/bg-parchment.png') center/cover;
    opacity:.08; pointer-events:none;
  }
  .s-guruji::before,.s-transits::before,.s-know::before,.s-share::before{opacity:.14;}

  .deco-ring{position:absolute;border:1.5px solid var(--gold-soft);border-radius:50%;pointer-events:none;z-index:0;}
  .s-hero .rg1{width:min(320px,80vw);height:min(320px,80vw);top:-110px;right:-90px;}
  .s-hero .rg2{width:180px;height:180px;bottom:-60px;left:-50px;}
  .s-mantra .rg1{width:340px;height:340px;top:-150px;right:-120px;border-color:rgba(240,217,138,.2);}
  .s-mantra .rg2{width:240px;height:240px;bottom:-130px;left:-90px;border-color:rgba(240,217,138,.2);}
  .s-final .rg1{width:360px;height:360px;top:-160px;left:-130px;border-color:rgba(240,217,138,.22);}
  .s-final .rg2{width:280px;height:280px;bottom:-140px;right:-110px;border-color:rgba(240,217,138,.22);}
  .deco-sign{position:absolute;opacity:.09;width:min(250px,38vw);pointer-events:none;z-index:0;}
  .ds-l{left:-60px;bottom:-20px;transform:rotate(-8deg);}
  .ds-r{right:-44px;top:calc(var(--chrome-t) + 46px);transform:rotate(10deg);width:min(175px,27vw);}
  .deco-planet{position:absolute;opacity:.13;width:min(200px,44vw);pointer-events:none;z-index:0;}

  /* ================= SHARED UI ================= */
  .btn{
    display:inline-flex;align-items:center;justify-content:center;gap:9px;
    min-height:48px;padding:12px 26px;border-radius:999px;font-weight:bold;font-size:.93rem;
    border:1.5px solid transparent;line-height:1.15;text-align:center;cursor:pointer;
    transition:transform .12s ease, background .15s ease, border-color .15s ease;
  }
  .btn:active{transform:scale(.97);}
  .btn-gold{background:var(--gold);color:var(--night);border-color:var(--gold);}
  .btn-gold:hover{background:var(--gold-light);}
  .btn-primary{background:var(--crimson);color:#fffdf8;border-color:var(--gold);}
  .btn-primary:hover{background:var(--crimson-dark);}
  .btn-outline{background:transparent;color:var(--terracotta);border-color:rgba(139,69,19,.6);}
  .btn-outline:hover{background:var(--paper);border-color:var(--terracotta);}

  .eyebrow{
    display:inline-flex;align-items:center;gap:10px;
    font-size:.7rem;font-weight:bold;letter-spacing:.04em;text-transform:uppercase;
    color:var(--terracotta);
  }
  .eyebrow::before,.eyebrow::after{content:"";width:26px;height:1px;background:var(--gold);}
  .eyebrow .no{color:var(--gold);letter-spacing:.08em;}

  .sec-head{position:relative;text-align:center;max-width:720px;margin:0 auto clamp(16px,3.4vh,28px);}
  .sec-head h2{line-height:1.18;margin-top:10px;color:var(--night);}
  .sec-head h2 em{color:var(--crimson);}
  .sec-head p{color:var(--muted);margin-top:8px;font-size:.92rem;}
  .ghost{
    position:absolute;left:50%;top:50%;transform:translate(-50%,-54%);
    line-height:1;
    color:rgba(139,69,19,.065);pointer-events:none;user-select:none;z-index:-1;
  }
  .s-products .ghost,.s-mantra .ghost{color:rgba(240,217,138,.07);}

  /* ================= LANGUAGE-SPECIFIC FONTS ================= */
  <?php if ($lang === 'en'): ?>
  /* English: AstroChitra for headings — big, thin, no italic, tight spacing */
  .ghost,
  .sec-head h2,
  .hero-wrap h1,
  .final-wrap h2,
  .fest-item h3,
  .myth-col h3,
  .fact h3,
  .blog-item h3 {
    font-family:'AstroChitra',Georgia,serif;
    font-weight:100;
    font-style:normal;
    letter-spacing:-0.1em;
  }
  .ghost{font-size:clamp(9.6rem,34vw,17rem);}
  .sec-head h2{font-size:clamp(3.1rem,10vw,4.5rem);}
  .hero-wrap h1{font-size:clamp(5.8rem,25vw,10.8rem);}
  .final-wrap h2{font-size:clamp(3.5rem,12.4vw,5.6rem);}
  .fest-item h3{font-size:1.95rem;}
  .fact h3{font-size:1.3rem;}
  .myth-col h3{font-size:1.25rem;}
  .blog-item h3{font-size:1.3rem;}
  /* Rashifal + transit names use the clean body font, bigger & clearer */
  .rashi-body h3{font-family:'Nunito Sans',sans-serif;font-weight:900;font-size:1.55rem;letter-spacing:0;}
  .transit-body h3{font-family:'Nunito Sans',sans-serif;font-weight:400;font-size:1.02rem;letter-spacing:0;}
  /* festival date label stays in the clean body font, not AstroChitra */
  .fest-date{font-family:'Nunito Sans',sans-serif;font-weight:700;}
  /* Kill any italic/em inside AstroChitra headings */
  .sec-head h2 em,
  .hero-wrap h1 em,
  .final-wrap h2 em{font-style:normal;}
  /* Headings with dashes get Nunito Sans instead of AstroChitra */
  .sec-head h2.dash,
  .myth-col h3.dash,
  .blog-item h3.dash{
    font-family:'Nunito Sans',sans-serif;
    letter-spacing:normal;
  }
  <?php else: ?>
  /* Hindi / Marathi: Dekko for headings — 1× headers, 1.5× content, no letter-spacing > 1 */
  .ghost,
  .sec-head h2,
  .hero-wrap h1,
  .final-wrap h2,
  .mantra-title,
  .rashi-body h3,
  .transit-body h3,
  .fest-item h3,
  .myth-col h3,
  .fact h3,
  .blog-item h3 {
    font-family:'Dekko',cursive;
    font-weight:400;
    font-style:normal;
    letter-spacing:normal;
  }
  /* 1.5× content sizes */
  .eyebrow{font-size:1.05rem;letter-spacing:.04em;}
  .eyebrow .no{letter-spacing:.05em;}
  .sec-head p{font-size:1.38rem;}
  .guruji-quote{font-size:clamp(1.8rem,6vw,2.8rem);}
  .guruji-role{font-size:1.17rem;}
  .rashi-dates{font-size:1rem;}
  .rashi-body h3{font-size:1.5rem;}
  .rashi-body>p{font-size:1.26rem;}
  .hit-tag{font-size:1rem;}
  .prod-txt strong{font-size:1.6rem;}
  .prod-txt span{font-size:1.3rem;}
  .mantra-title{font-size:clamp(3rem,12vw,5rem);}
  .mantra-sanskrit{font-size:clamp(2.5rem,8vw,4rem);}
  .mantra-meaning{font-size:1.38rem;}
  .transit-date{font-size:1rem;}
  .transit-tag{font-size:1rem;}
  .transit-body h3{font-size:1.3rem;}
  .fest-date{font-family:'Dekko',cursive;}
  .fest-item h3{font-size:1.9rem;line-height:1.2;}
  .fest-item p{font-size:1.28rem;}
  .myth-label{font-size:1rem;}
  .myth-col h3{font-size:1.45rem;}
  .myth-col p{font-size:1.32rem;}
  .myth-cta{font-size:1.28rem;}
  .fact-label{font-size:1rem;}
  .fact p{font-size:1.34rem;}
  .blog-cat{font-size:1rem;}
  .blog-item h3{font-size:1.42rem;}
  .blog-item p{font-size:1.25rem;}
  .blog-cta{font-size:1.25rem;}
  .testi-text{font-size:1.46rem;}
  .testi-person strong{font-size:1.32rem;}
  .testi-person span{font-size:1.11rem;}
  .share-form label{font-size:1.23rem;}
  .share-note{font-size:1.13rem;}
  .final-wrap p{font-size:1.43rem;}
  .foot-tag{font-size:1.02rem;}
  .foot-links{font-size:1.23rem;}
  .foot-copy{font-size:1.08rem;}
  .share-btn{font-size:1.38rem;}
  .lang-switcher button{font-size:1.14rem;}
  .nav-count b{font-size:1.43rem;}
  .menu-title{font-size:1.02rem;}
  .menu-item strong{font-size:1.29rem;}
  .menu-no{font-size:1rem;}
  .brand-badge span{font-size:1.11rem;}
  .slide-tag{font-size:1.01rem;}
  <?php endif; ?>

  .rule-label{
    display:flex;align-items:center;gap:14px;
    margin:clamp(26px,5vh,42px) auto 10px;
    width:100%;text-align:center;
    font-size:.7rem;font-weight:bold;letter-spacing:.04em;text-transform:uppercase;color:var(--terracotta);
  }
  .rule-label::before,.rule-label::after{content:"";flex:1;height:1px;background:var(--gold);}
  .rule-label.tight{margin-top:0;}

  /* reveal animation */
  .rv{opacity:0;transform:translateY(16px);transition:opacity .55s ease var(--d,0s), transform .55s cubic-bezier(.22,.9,.35,1) var(--d,0s);}
  .slide.active .rv{opacity:1;transform:none;}

  /* ================= FIXED CHROME ================= */
  .progress{position:fixed;top:0;left:0;right:0;height:3px;z-index:400;background:rgba(201,162,39,.18);}
  .progress span{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--gold),var(--gold-light));transition:width .45s ease;}

  .chrome-btn{
    position:fixed;top:var(--chrome-t);z-index:340;
    width:48px;height:48px;border-radius:50%;
    background:var(--paper);border:1.5px solid rgba(139,69,19,.55);color:var(--terracotta);
    display:flex;align-items:center;justify-content:center;cursor:pointer;
    box-shadow:0 8px 22px -8px rgba(43,16,5,.4);
    transition:background .15s,border-color .15s,color .15s,transform .12s;
  }
  .chrome-btn:hover{background:var(--gold-light);border-color:var(--gold);color:var(--night);}
  .chrome-btn:active{transform:scale(.94);}
  .menu-btn{right:14px;}
  .share-btn{left:14px;}
  .menu-btn .ic-close{display:none;}
  body.menu-open .menu-btn .ic-open{display:none;}
  body.menu-open .menu-btn .ic-close{display:block;}

  .share-toast{
    position:fixed;left:50%;bottom:calc(var(--nav-h) + var(--safe-b) + 30px);transform:translateX(-50%) translateY(8px);z-index:360;
    background:var(--night);color:var(--cream);border:1px solid var(--gold);
    border-radius:999px;padding:10px 22px;font-size:.82rem;
    opacity:0;visibility:hidden;transition:.25s;white-space:nowrap;
  }
  .share-toast.show{opacity:1;visibility:visible;transform:translateX(-50%) translateY(0);}

  .menu-backdrop{position:fixed;inset:0;z-index:320;background:rgba(43,16,5,.62);opacity:0;visibility:hidden;transition:opacity .22s;}
  body.menu-open .menu-backdrop{opacity:1;visibility:visible;}
  .menu-panel{
    position:fixed;top:0;right:0;bottom:0;z-index:330;width:min(330px,86vw);
    background:var(--paper);border-left:1px solid var(--line);
    transform:translateX(102%);transition:transform .28s cubic-bezier(.4,0,.2,1);
    padding:calc(var(--chrome-t) + 64px) 16px calc(var(--safe-b) + 20px);overflow-y:auto;
    box-shadow:-18px 0 44px rgba(43,16,5,.18);
  }
  body.menu-open .menu-panel{transform:translateX(0);}
  .menu-title{font-size:.68rem;font-weight:bold;letter-spacing:.04em;text-transform:uppercase;color:var(--muted);text-align:center;margin-bottom:14px;display:block;}
  .menu-list{display:flex;flex-direction:column;gap:6px;list-style:none;}
  .menu-list a{
    font-size:.86rem;font-weight:bold;color:var(--cocoa);
    background:var(--cream);border:1px solid var(--line);border-radius:12px;
    min-height:46px;padding:9px 13px;display:flex;align-items:center;gap:11px;
    transition:background .15s,border-color .15s;
  }
  .menu-list a:hover{background:var(--gold-light);border-color:var(--gold);color:var(--night);}
  .menu-list a.current{background:var(--gold-light);border-color:var(--gold);color:var(--night);}
  .menu-no{flex:none;font-size:.66rem;font-weight:bold;letter-spacing:.06em;color:var(--gold);width:20px;text-align:right;}

  /* ================= BOTTOM NAV ================= */
  .deck-nav{
    position:fixed;left:50%;bottom:calc(12px + var(--safe-b));transform:translateX(-50%);
    z-index:300;display:flex;align-items:center;gap:7px;
    background:rgba(43,16,5,.94);border:1px solid var(--gold);
    border-radius:999px;padding:9px 11px;
    box-shadow:0 14px 34px rgba(43,16,5,.4), inset 0 1px 0 rgba(240,217,138,.25);
    max-width:calc(100vw - 20px);
  }
  .nav-btn{
    flex:none;width:44px;height:44px;border-radius:50%;border:none;cursor:pointer;
    background:var(--gold);color:var(--night);
    display:flex;align-items:center;justify-content:center;
    transition:background .15s,transform .15s;
  }
  .nav-btn:hover{background:var(--gold-light);transform:scale(1.05);}
  .nav-btn:disabled{opacity:.3;cursor:default;transform:none;}
  .segs{display:flex;align-items:center;gap:3px;padding:0 3px;}
  .seg{
    width:15px;height:32px;border:none;background:none;padding:0;cursor:pointer;
    display:flex;align-items:center;justify-content:center;
  }
  .seg i{display:block;width:100%;height:4px;border-radius:99px;background:rgba(240,217,138,.32);transition:background .2s,transform .2s;}
  .seg.on i{background:var(--gold-light);transform:scaleY(1.5);}
  .seg.done i{background:rgba(240,217,138,.55);}
  .nav-count{
    display:none;align-items:baseline;gap:4px;color:#e8dcc2;
    font-size:.72rem;letter-spacing:.04em;padding:0 6px 0 8px;white-space:nowrap;
  }
  .nav-count b{color:var(--gold-light);font-size:.95rem;}
  @media(min-width:540px){.nav-count{display:inline-flex;}}
  /* ================= SLIDE 1 : COVER ================= */
  .hero-wrap{text-align:center;max-width:800px;margin:0 auto;display:flex;flex-direction:column;align-items:center;}
  .brand-badge{
    display:inline-flex;align-items:center;gap:12px;margin-bottom:clamp(24px,4.5vh,38px);
    background:rgba(255,253,248,.05);border:1px solid var(--gold-soft);
    border-radius:999px;padding:8px 20px 8px 9px;
  }
  .brand-badge img{height:40px;width:auto;}
  .brand-badge i{width:1px;height:24px;background:var(--gold-soft);}
  .brand-badge span{font-size:.74rem;letter-spacing:.04em;text-transform:uppercase;color:var(--gold-light);font-weight:bold;}
  .hero-kicker{
    display:inline-flex;align-items:center;gap:9px;font-size:.67rem;font-weight:bold;letter-spacing:.05em;text-transform:uppercase;
    color:var(--gold-light);
  }
  .hero-kicker i{width:26px;height:1px;background:var(--gold-soft);}
  .hero-wrap h1{
    margin-top:clamp(14px,2.6vh,22px);
    line-height:1.02;color:#fffdf8;
  }
  .hero-wrap h1 em{color:var(--gold-light);}
  .hero-hint{
    margin-top:clamp(28px,6vh,52px);
    font-size:.68rem;letter-spacing:.04em;text-transform:uppercase;color:#bfae8d;
    display:inline-flex;align-items:center;gap:9px;
  }
  .hero-hint svg{animation:nudge 1.6s ease infinite;}
  @keyframes nudge{0%,100%{transform:translateX(0);}50%{transform:translateX(6px);}}

  /* ================= SLIDE 2 : GURUJI ================= */
  .guruji-col{max-width:820px;margin:0 auto;display:flex;flex-direction:column;align-items:center;gap:16px;text-align:center;width:100%;}
  .guruji-photo{
    width:132px;height:132px;border-radius:50%;object-fit:cover;object-position:center top;
    outline:2px solid var(--gold);outline-offset:6px;
    box-shadow:0 18px 40px -14px rgba(71,29,11,.4);
  }
  .quote-mark {
    color: var(--gold);
    font-size: 6rem;
    line-height: .4;
    font-family: Georgia, serif;
    /* height: 26px; */
    padding-top: 1rem;
    margin-bottom: -1.5rem;
}
.guruji-row.rv {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

  .guruji-quote{font-size:clamp(1.32rem,4.8vw,2rem);font-style:italic;line-height:1.42;color:var(--cocoa);max-width:680px;}
  .guruji-name{font-weight:bold;color:var(--terracotta);letter-spacing:.04em;}
  .guruji-role{font-size:.78rem;color:var(--muted);letter-spacing:.04em;}
  .consult-links{display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:8px 22px;margin-top:10px;}
  .quiet-link{
    display:inline-flex;align-items:center;gap:8px;
    min-height:48px;padding:8px 4px;font-weight:bold;font-size:.92rem;color:var(--terracotta);
    border-bottom:1px solid transparent;transition:border-color .15s,color .15s;
  }
  .quiet-link:hover{border-bottom-color:var(--gold);color:var(--cocoa);}
  @media(min-width:900px){
    .guruji-row{display:flex;align-items:center;gap:clamp(30px,4vw,56px);text-align:left;}
    .guruji-row .quote-mark{height:auto;}
  }

  /* ================= SLIDE 3 : RASHIFAL ================= */
  .rashi-list{width:100%;max-width:1140px;margin:0 auto;display:grid;grid-template-columns:1fr;}
  .rashi-item{display:flex;gap:14px;padding:15px 4px;border-top:1px solid var(--line);align-items:flex-start;}
  .rashi-glyph{
    flex:none;width:52px;height:52px;border-radius:50%;
    background:var(--parchment);border:1px solid var(--gold);
    display:flex;align-items:center;justify-content:center;padding:9px;
  }
  .rashi-glyph img{width:100%;height:100%;object-fit:contain;}
  .rashi-body{min-width:0;}
  .rashi-top{display:flex;align-items:baseline;gap:9px;flex-wrap:wrap;}
  .rashi-body h3{color:var(--night);}
  .rashi-dates{font-size:.67rem;letter-spacing:.07em;color:var(--muted);text-transform:uppercase;}
  .rashi-body>p{font-size:.84rem;color:var(--muted);margin-top:4px;line-height:1.55;}
  .hits{display:flex;flex-wrap:wrap;gap:4px 18px;margin-top:7px;}
  .hit{
    font-size:.66rem;font-weight:bold;letter-spacing:.06em;text-transform:uppercase;color:var(--olive);
    display:inline-flex;align-items:center;gap:7px;
  }
  .hit::before{content:"";width:5px;height:5px;border-radius:50%;background:currentColor;flex:none;}
  .hit-hot{color:var(--crimson);}
  @media(min-width:700px){.rashi-list{grid-template-columns:repeat(2,1fr);column-gap:44px;}}
  @media(min-width:1060px){.rashi-list{grid-template-columns:repeat(3,1fr);}}

  /* ================= SLIDE 4 : PRODUCTS ================= */
  .prod-list{width:100%;max-width:780px;margin:0 auto;}
  .prod-item{
    display:flex;align-items:center;gap:15px;
    padding:14px 4px;border-top:1px solid var(--hairline);
    color:#fffdf8;transition:background .15s;
  }
  .prod-item:last-child{border-bottom:1px solid var(--hairline);}
  .prod-item:hover{background:rgba(240,217,138,.06);}
  .prod-ic{
    flex:none;width:44px;height:44px;border-radius:12px;
    display:flex;align-items:center;justify-content:center;
  }
  .prod-ic img{width:100%;height:100%;object-fit:contain;}
  .prod-txt{min-width:0;flex:1;}
  .prod-txt strong{display:block;font-size:1.12rem;letter-spacing:.01em;transition:color .15s;}
  .prod-item:hover .prod-txt strong{color:var(--gold-light);}
  .prod-txt span{display:block;font-size:.88rem;color:#cbbd9f;margin-top:2px;line-height:1.45;}
  .prod-arrow{flex:none;color:var(--gold-light);opacity:.75;transition:transform .15s,opacity .15s;}
  .prod-item:hover .prod-arrow{transform:translateX(4px);opacity:1;}
  @media(min-width:760px){.prod-item{padding:15px 10px;}}

  /* ================= SLIDE 5 : MANTRA ================= */
  .mantra-wrap{text-align:left;max-width:780px;margin:0 auto;position:relative;}
  .mantra-title{
    font-family:'AstroChitra',Georgia,serif;
    font-weight:100;font-style:normal;letter-spacing:-0.1em;line-height:1.02;
    color:var(--gold-light);
    font-size:clamp(4rem,15vw,7rem);
  }
  .s-mantra .mantra-title{color:var(--gold-light);}
  .mantra-sanskrit{margin:clamp(24px,4vh,32px) 0 14px;font-size:clamp(1.7rem,6.6vw,2.8rem);line-height:1.3;color:#fffdf8;font-weight:normal;}
  .mantra-sanskrit em{font-style:italic;color:var(--gold-light);}
  .mantra-meaning{color:#d8cbb2;font-style:italic;max-width:680px;margin:0 0;font-size:.92rem;line-height:1.68;}
  .mantra-tip{
    margin:clamp(26px,5vh,36px) 0 0;max-width:640px;
    background:rgba(240,217,138,.12);
    border:1px solid var(--gold);
    border-left:4px solid var(--gold);
    border-radius:14px;
    padding:16px 20px;
    text-align:left;font-size:.88rem;color:#e8dcc2;line-height:1.62;
    box-shadow:0 14px 30px -18px rgba(43,16,5,.6);
  }
  .mantra-tip strong{color:var(--gold-light);font-weight:bold;}

  /* ================= SLIDE 6 : TRANSITS & FESTIVALS ================= */
  .transit-list{width:100%;max-width:1140px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:10px;}
  .transit-item{
    display:flex;align-items:center;gap:14px;padding:12px 14px;
    border:1px solid var(--line);border-radius:14px;background:var(--paper);
    box-shadow:0 10px 24px -18px rgba(71,29,11,.35);
  }
  .transit-item>img{
    flex:none;width:40px;height:40px;object-fit:contain;filter:saturate(.9);
    background:var(--parchment);border:1px solid var(--gold);border-radius:50%;padding:8px;
  }
  .transit-body{display:flex;flex-wrap:wrap;align-items:baseline;column-gap:10px;row-gap:3px;flex:1;min-width:0;}
  .transit-body h3{color:var(--ink);}
  .transit-body h3 b{font-weight:700;color:var(--cocoa);}
  .transit-date{
    font-size:.68rem;font-weight:bold;letter-spacing:.04em;text-transform:uppercase;
    color:var(--crimson);background:rgba(174,23,42,.08);border-radius:999px;padding:4px 11px;
  }
  .fest-grid{width:100%;max-width:1140px;margin:0 auto;display:grid;grid-template-columns:1fr;}
  .fest-item{padding:15px 4px;border-bottom:1px solid var(--line);}
  .fest-item h3{color:var(--night);display:flex;flex-wrap:wrap;align-items:center;gap:8px 12px;}
  .fest-date{
    flex:none;font-size:1.02rem;font-weight:bold;letter-spacing:.02em;text-transform:uppercase;
    color:var(--night);background:var(--gold-light);border-radius:999px;padding:6px 16px;
  }
  .fest-item p{font-size:.85rem;color:var(--muted);margin-top:6px;line-height:1.55;}
  .panchang-cta{text-align:center;margin:24px auto 0;max-width:600px;}
  .panchang-cta p{color:var(--muted);font-size:.92rem;margin-bottom:16px;line-height:1.6;}
  @media(min-width:900px){
    .transit-list{grid-template-columns:repeat(2,1fr);column-gap:20px;}
    .fest-grid{grid-template-columns:repeat(2,1fr);column-gap:44px;}
  }

  /* ================= SLIDE 7 : MYTH VS REALITY ================= */
  .myth-cols{width:100%;max-width:940px;margin:0 auto;display:grid;grid-template-columns:1fr;position:relative;}
  .myth-col{padding:4px 4px 20px;}
  .myth-col+.myth-col{border-top:1px solid var(--line);padding-top:22px;}
  .myth-label{
    display:inline-flex;align-items:center;gap:7px;
    font-size:.65rem;font-weight:bold;letter-spacing:.04em;text-transform:uppercase;
    padding:5px 13px;border-radius:999px;
  }
  .myth-no{color:var(--crimson);border:1px solid rgba(174,23,42,.35);}
  .myth-yes{color:var(--olive);border:1px solid rgba(101,78,18,.35);}
  .myth-col p{margin-top:12px;}
  .myth-col p{font-size:.88rem;color:var(--muted);line-height:1.66;}
  .myth-vs{
    display:none;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
    width:46px;height:46px;border-radius:50%;z-index:2;
    background:var(--night);color:var(--gold-light);border:1.5px solid var(--gold);
    align-items:center;justify-content:center;font-style:italic;font-size:.85rem;
    box-shadow:0 10px 26px rgba(43,16,5,.3);
  }
  @media(min-width:900px){
    .myth-cols{grid-template-columns:repeat(2,1fr);}
    .myth-col{padding:6px 34px;}
    .myth-col:first-child{padding-left:4px;}
    .myth-col+.myth-col{border-top:none;border-left:1px solid var(--line);padding-top:6px;}
    .myth-vs{display:flex;}
  }

  /* ================= SLIDE 8 : WATCH & FOLLOW ================= */
  .watch-grid{display:flex;flex-direction:column;gap:clamp(10px,2vh,18px);max-width:1080px;margin:0 auto;width:100%;}
  .yt-frame{
    border:1px solid var(--line);border-radius:16px;overflow:hidden;background:#000;
    box-shadow:0 24px 54px -24px rgba(71,29,11,.4);
    position: relative;
    z-index: 10;
    touch-action: auto;
  }
  .yt-frame iframe{width:100%;aspect-ratio:16/9;display:block;border:0;
  pointer-events: auto;
  touch-action: auto;}
  .channel-links{display:flex;flex-wrap:wrap;gap:11px;margin-top:14px;}
  .insta-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:11px;}
  .insta-tile{position:relative;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#000;cursor:pointer;box-shadow:0 18px 40px -20px rgba(71,29,11,.4);}
  .insta-tile video{width:100%;aspect-ratio:9/16;object-fit:cover;display:block;pointer-events:none;}
  .reel-play{
    position:absolute;inset:0;margin:auto;
    width:52px;height:52px;border-radius:50%;
    background:rgba(43,16,5,.72);color:#fffdf8;
    border:1.5px solid var(--gold-light);
    display:flex;align-items:center;justify-content:center;
    pointer-events:none;transition:opacity .18s,transform .18s;
  }
  .insta-tile.playing .reel-play{opacity:0;transform:scale(.85);}
  .insta-ic{
    position:absolute;top:9px;right:9px;width:27px;height:27px;border-radius:50%;
    background:rgba(43,16,5,.75);color:#fffdf8;
    display:flex;align-items:center;justify-content:center;
  }
  @media(min-width:480px){.insta-grid{grid-template-columns:repeat(4,1fr);}}
  @media(min-width:1000px){
    .watch-grid{display:grid;grid-template-columns:1.35fr 1fr;align-items:start;column-gap:44px;}
  }

  /* ================= SLIDE 9 : DID YOU KNOW + BLOGS ================= */
  .fact{max-width:780px;margin:0 auto;display:flex;gap:16px;align-items:flex-start;}
  .fact-ic{
    flex:none;width:46px;height:46px;border-radius:50%;
    background:var(--gold-light);color:var(--night);
    display:flex;align-items:center;justify-content:center;
    outline:1px solid var(--gold);outline-offset:4px;
  }
  .fact h3{color:var(--night);}
  .fact p{font-size:.89rem;color:var(--muted);margin-top:5px;line-height:1.66;}
  .blog-list{width:100%;max-width:980px;margin:0 auto;display:grid;grid-template-columns:1fr;}
  .blog-item{padding:15px 4px;border-bottom:1px solid var(--line);display:flex;flex-direction:column;align-items:flex-start;}
  .blog-img{width:100%;height:auto;border-radius:10px;margin-bottom:10px;border:1px solid var(--line);}
  .blog-cat{font-size:.63rem;font-weight:bold;letter-spacing:.04em;text-transform:uppercase;color:var(--gold);}
  .blog-item h3{color:var(--night);margin-top:5px;line-height:1.45;}
  .blog-link{
    display:inline-flex;align-items:center;gap:7px;
    min-height:40px;margin-top:6px;font-size:.83rem;font-weight:bold;color:var(--crimson);
  }
  .blog-link:hover{color:var(--crimson-dark);}
  .blog-link svg{transition:transform .15s;}
  .blog-link:hover svg{transform:translateX(3px);}
  @media(min-width:700px){.blog-list{grid-template-columns:repeat(2,1fr);column-gap:44px;}}

  /* ================= SLIDE 10 : TESTIMONIALS ================= */
  .testi-wrap{max-width:380px;margin:0 auto;text-align:center;width:100%;}
  .testi-tile{
    width:100%;max-width:320px;margin:0 auto;border-radius:20px;
    border:1px solid var(--gold);outline:1px solid var(--line);outline-offset:5px;
    box-shadow:0 26px 60px -22px rgba(43,16,5,.45);
  }
  .testi-video{
    width:100%;aspect-ratio:9/16;border-radius:20px;background:#000;object-fit:cover;display:block;
  }
  .stars{display:flex;gap:5px;color:var(--gold);justify-content:center;margin-top:20px;}
  .testi-text{font-size:.97rem;font-style:italic;color:var(--cocoa);margin-top:11px;line-height:1.62;}
  .testi-person{display:flex;align-items:center;gap:12px;margin-top:15px;justify-content:center;}
  .testi-person img{width:44px;height:44px;border-radius:50%;object-fit:cover;outline:2px solid var(--gold);outline-offset:2px;}
  .testi-person strong{display:block;font-size:.88rem;color:var(--night);text-align:left;}
  .testi-person span{font-size:.74rem;color:var(--muted);}

  /* ================= SLIDE 11 : SHARE & COMMENT ================= */
  .share-success{
    max-width:560px;margin:0 auto 16px;text-align:center;
    background:#f2efdc;border:1px solid var(--gold);border-radius:14px;
    padding:14px 18px;color:var(--olive);font-weight:bold;font-size:.9rem;
  }
  .share-form{width:100%;max-width:560px;margin:0 auto;display:flex;flex-direction:column;gap:16px;}
  .share-form label{display:flex;flex-direction:column;gap:7px;font-size:.82rem;font-weight:bold;color:var(--cocoa);letter-spacing:.02em;}
  .share-form label em{font-weight:normal;color:var(--muted);font-style:normal;}
  .share-form input,.share-form textarea{
    font-family:inherit;font-size:16px;color:var(--ink);
    background:var(--paper);border:1px solid var(--line);border-radius:12px;
    min-height:50px;padding:13px 15px;outline:none;width:100%;resize:vertical;
    transition:border-color .15s;
  }
  .share-form input:focus,.share-form textarea:focus{border-color:var(--gold);}
  .share-note{font-size:.75rem;color:var(--muted);text-align:center;line-height:1.5;}

  /* ================= SLIDE 12 : FINAL + FOOTER ================= */
  .final-wrap{text-align:center;max-width:680px;margin:0 auto;position:relative;z-index:1;width:100%;}
  .fstar{position:absolute;color:var(--gold-light);opacity:.9;}
  .fd1{top:8%;left:9%;} .fd2{top:18%;right:8%;} .fd3{bottom:38%;left:20%;}
  .final-wrap h2{color:#fffdf8;line-height:1.14;margin-top:12px;}
  .final-wrap h2 em{color:var(--gold-light);}
  .final-wrap>.eyebrow{color:var(--gold-light);}
  .final-wrap p{color:#d8cbb2;max-width:520px;margin:14px auto 0;font-size:.95rem;line-height:1.65;}
  .final-actions{display:flex;flex-direction:column;width:min(340px,100%);gap:11px;margin:clamp(22px,4vh,32px) auto 0;}
  @media(min-width:640px){.final-actions{flex-direction:row;width:auto;justify-content:center;}}
  .foot{
    border-top:1px solid rgba(240,217,138,.3);
    padding-top:24px;margin-top:clamp(30px,6vh,48px);
    display:flex;flex-direction:column;align-items:center;gap:15px;text-align:center;
  }
  .foot-brand{display:flex;align-items:center;gap:10px;}
  .foot-brand img{height:34px;width:auto;}
  .foot-tag{font-size:.68rem;letter-spacing:.04em;text-transform:uppercase;color:#bfae8d;}
  .foot-links{display:flex;flex-wrap:wrap;justify-content:center;gap:4px 8px;font-size:.82rem;color:#e8dcc2;}
  .foot-links a{padding:8px 10px;border-radius:8px;min-height:36px;display:inline-flex;align-items:center;}
  .foot-links a:hover{color:var(--gold-light);}
  .foot-social{display:flex;gap:11px;}
  .soc{
    width:42px;height:42px;border-radius:50%;
    border:1px solid var(--gold-soft);color:var(--gold-light);
    display:flex;align-items:center;justify-content:center;
    transition:background .15s;
  }
  .soc:hover{background:rgba(240,217,138,.12);}
  .foot-copy{font-size:.72rem;color:#bfae8d;letter-spacing:.04em;}

  /* ================= LANGUAGE SWITCHER ================= */
  .lang-switcher{
    position:fixed;top:var(--chrome-t);left:70px;z-index:340;
    display:flex;align-items:center;gap:0;
    background:var(--night);border:1px solid rgba(240,217,138,.28);
    border-radius:999px;overflow:hidden;
    box-shadow:0 6px 18px -4px rgba(43,16,5,.5), inset 0 1px 0 rgba(240,217,138,.12);
  }
  .lang-ic{
    display:flex;align-items:center;justify-content:center;
    padding:0 0 0 11px;color:var(--gold-light);flex:none;
  }
  .lang-switcher button{
    border:none;background:none;padding:9px 13px;font-size:.68rem;font-weight:600;
    letter-spacing:.08em;cursor:pointer;position:relative;
    color:rgba(232,220,194,.55);transition:color .2s;
    font-family:'Nunito Sans',sans-serif;
  }
  .lang-switcher button:hover{color:var(--gold-light);}
  .lang-switcher button.active{color:var(--night);}
  .lang-switcher button.active::before{
    content:"";position:absolute;inset:3px 2px;border-radius:999px;
    background:var(--gold);z-index:-1;
    animation:langSlide .25s cubic-bezier(.4,0,.2,1);
  }
  @keyframes langSlide{from{opacity:0;transform:scale(.85);}to{opacity:1;transform:none;}}
  .lang-divider{
    width:1px;height:18px;background:rgba(240,217,138,.2);flex:none;
  }
  @media(min-width:540px){
    .lang-switcher button{padding:9px 15px;font-size:.72rem;}
  }

  /* ================= MOTION PREFS ================= */
  @media(prefers-reduced-motion:reduce){
    .deck{transition:none;}
    .rv{opacity:1;transform:none;transition:none;}
    .hero-hint svg{animation:none;}
    *{scroll-behavior:auto!important;}
  }

  /* ================= PAGE LOADER ================= */
  .page-loader{
    position:fixed; inset:0; z-index:1000;
    background:var(--cream);
    display:flex; align-items:center; justify-content:center;
    transition:opacity .35s ease, visibility .35s ease;
  }
  .page-loader.hidden{
    opacity:0; visibility:hidden; pointer-events:none;
  }
  .loader-spinner{
    width:48px; height:48px;
    border:4px solid var(--gold-soft);
    border-top-color:var(--gold);
    border-radius:50%;
    animation:spin 1s linear infinite;
  }
  @keyframes spin{
    to{transform:rotate(360deg);}
  }
</style>
</head>
<body>

<div id="pageLoader" class="page-loader" aria-hidden="true">
  <div class="loader-spinner"></div>
</div>

<div class="progress" aria-hidden="true"><span id="progFill"></span></div>

<button class="chrome-btn share-btn" id="shareBtn" aria-label="Share this newsletter">
  <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/></svg>
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

<button class="chrome-btn menu-btn" id="menuBtn" aria-label="Open menu" aria-expanded="false" aria-controls="menuPanel">
  <svg class="ic-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
  <svg class="ic-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
</button>
<div class="menu-backdrop" id="menuBackdrop"></div>
<nav class="menu-panel" id="menuPanel" aria-label="Newsletter sections">
  <span class="menu-title"><?php echo htmlspecialchars($langContent['menu']['title']); ?></span>
  <ul class="menu-list" id="menuList">
    <?php foreach($langContent['menu']['items'] as $mi => $menuItem): ?>
    <li><a href="#cover" data-slide="<?php echo $mi; ?>"><span class="menu-no"><?php echo str_pad($mi+1, 2, '0', STR_PAD_LEFT); ?></span><?php echo htmlspecialchars($menuItem); ?></a></li>
    <?php endforeach; ?>
  </ul>
</nav>

<!-- ==================== DECK ==================== -->
<main class="deck" id="deck">

  <!-- ============ SLIDE 1 : COVER ============ -->
  <section class="slide s-hero" id="cover" aria-label="Cover">
    <img class="deco-sign ds-l" src="../../assets/signs/Libra.svg" alt="" aria-hidden="true">
    <img class="deco-sign ds-r" src="../../assets/signs/Virgo.svg" alt="" aria-hidden="true">
    <span class="deco-ring rg1"></span>
    <span class="deco-ring rg2"></span>
    <div class="slide-in">
      <div class="hero-wrap">
        <div class="brand-badge rv">
          <img src="../../assets/astrochitra-logo.png" alt="AstroChitra logo">
          <i></i>
          <span><?php echo htmlspecialchars($langContent['hero']['brandName']); ?></span>
        </div>
        <span class="hero-kicker rv" style="--d:.1s;"><i></i><?php echo htmlspecialchars($langContent['hero']['kicker']); ?><i></i></span>
        <h1 class="rv" style="--d:.18s;"><?php echo $langContent['hero']['headline']; ?></h1>
        <span class="hero-hint rv" style="--d:.34s;" aria-hidden="true">
          <?php echo htmlspecialchars($langContent['hero']['swipeHint']); ?>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 2 : GURUJI SPEAKS ============ -->
  <section class="slide s-guruji bg-texture" id="guruji" aria-label="Guruji speaks">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">01</i>
        <span class="eyebrow"><span class="no"><?php echo $langContent['guruji']['eyebrowLabel']; ?></span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['guruji']['eyebrowText']); ?></span>
      </div>
      <div class="guruji-col">
        <div class="guruji-row rv" style="--d:.08s;">
          <img class="guruji-photo" src="../../assets/Guruji2.jpg" alt="Portrait of Guruji" loading="lazy" decoding="async">
          <div>
            <div class="quote-mark" aria-hidden="true">&ldquo;</div>
            <p class="guruji-quote"><?php echo htmlspecialchars($langContent['guruji']['quote']); ?></p>
            <p style="margin-top:14px;">
              <!-- <span class="guruji-name">Guruji</span><br>
              <span class="guruji-role">Founder &amp; Chief Astrologer, AstroChitra</span> -->
            </p>
          </div>
        </div>
        <div class="consult-links rv" style="--d:.2s;">
          <a class="btn btn-gold" href="https://astrochitra.com/consultation" target="_blank" rel="noopener"><?php echo htmlspecialchars($langContent['guruji']['bookBtn']); ?></a>
          <a class="btn btn-outline" href="https://wa.me/919820616655" target="_blank" rel="noopener">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="#25d366"><path d="M12.04 2a9.9 9.9 0 0 0-8.51 14.93L2 22l5.2-1.49A9.9 9.9 0 1 0 12.04 2zm5.77 14.06c-.24.68-1.4 1.3-1.93 1.35-.52.05-1.01.24-3.4-.71-2.87-1.13-4.68-4.06-4.82-4.25-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.26-.28.57-.35.76-.35h.55c.18 0 .42-.07.66.5.24.57.83 2.03.9 2.18.07.14.12.31.02.5-.1.19-.15.3-.29.47-.15.17-.31.38-.44.51-.14.14-.29.29-.12.57.16.28.73 1.2 1.57 1.95 1.08.96 1.99 1.26 2.27 1.4.28.14.44.12.61-.07.16-.19.7-.81.89-1.09.19-.28.38-.23.63-.14.26.09 1.63.77 1.91.91.28.14.47.21.54.33.07.12.07.68-.17 1.35z"/></svg>
            <?php echo htmlspecialchars($langContent['guruji']['whatsappLink']); ?> &nbsp;<b>+91 98206 16655</b>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 3 : RASHIFAL ============ -->
  <section class="slide s-rashifal" id="rashifal" aria-label="Monthly rashifal">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">02</i>
        <span class="eyebrow"><span class="no">02</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['rashifal']['eyebrowText']); ?></span>
        <h2><?php echo $langContent['rashifal']['title']; ?></h2>
        <p><?php echo htmlspecialchars($langContent['rashifal']['subtitle']); ?></p>
      </div>
      <div class="rashi-list">
        <?php
        $signFiles = ['Aries','Taurus','Gemini','Cancer','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces'];
        foreach($langContent['rashifal']['signs'] as $ri => $rashi):
        ?>
        <article class="rashi-item rv"<?php echo $ri > 0 ? ' style="--d:'.sprintf('%.2fs', ($ri % 4) * 0.04).'."' : ''; ?>>
          <div class="rashi-glyph"><img src="../../assets/signs/<?php echo $signFiles[$ri]; ?>.svg" alt="" loading="lazy"></div>
          <div class="rashi-body">
            <div class="rashi-top"><h3><?php echo htmlspecialchars($rashi['name']); ?></h3><span class="rashi-dates"><?php echo htmlspecialchars($rashi['dates']); ?></span></div>
            <p><?php echo htmlspecialchars($rashi['description']); ?></p>
            <div class="hits">
              <?php foreach($rashi['hits'] as $hit): ?>
              <span class="hit<?php echo $hit['hot'] ? ' hit-hot' : ''; ?>"><?php echo htmlspecialchars($hit['text']); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 4 : WEB PRODUCTS ============ -->
  <section class="slide s-products" id="products" aria-label="AstroChitra web products">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">03</i>
        <span class="eyebrow" style="color:var(--gold-light);"><span class="no">03</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['products']['eyebrowText']); ?></span>
        <h2 style="color:#fffdf8;"><?php echo htmlspecialchars($langContent['products']['title']); ?></h2>
        <p style="color:#e8dcc2;"><?php echo htmlspecialchars($langContent['products']['subtitle']); ?></p>
      </div>
      <div class="prod-list">
        <?php
        $prodUrls = ['kundli','matchmaking','insights','journal','panchang'];
        $prodPaths = ['https://astrochitra.com/kundli','https://astrochitra.com/matchmaking','https://astrochitra.com/insights','https://blogs.astrochitra.com/','https://panchang.astrochitra.com/'];
        foreach($langContent['products']['items'] as $pi => $prod):
        ?>
        <a href="<?php echo $prodPaths[$pi]; ?>" target="_blank" rel="noopener" class="prod-item rv"<?php echo $pi > 0 ? ' style="--d:'.sprintf('%.2fs', $pi * 0.05).'."' : ''; ?>>
          <span class="prod-ic"><img src="../../assets/svg_icons/<?php echo $prodUrls[$pi]; ?>_icon.svg" alt="" loading="lazy"></span>
          <span class="prod-txt"><strong><?php echo htmlspecialchars($prod['name']); ?></strong><span><?php echo htmlspecialchars($prod['desc']); ?></span></span>
          <span class="prod-arrow"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 5 : MANTRA ============ -->
  <section class="slide s-mantra" id="mantra" aria-label="Mantra of the month">
    <img class="deco-planet" style="top:-40px;left:-46px;transform:rotate(-14deg);" src="../../assets/planets/Sun.svg" alt="" aria-hidden="true" loading="lazy">
    <span class="deco-ring rg1"></span>
    <span class="deco-ring rg2"></span>
    <div class="slide-in">
      <div class="mantra-wrap">
        <h2 class="mantra-title rv"><?php echo htmlspecialchars($langContent['mantra']['kicker']); ?></h2>
        <p class="mantra-sanskrit rv" style="--d:.1s;"><?php echo $langContent['mantra']['sanskrit']; ?></p>
        <p class="mantra-meaning rv" style="--d:.18s;"><?php echo $langContent['mantra']['meaning']; ?></p>
        <aside class="mantra-tip rv" style="--d:.26s;"><strong><?php echo htmlspecialchars($langContent['mantra']['tipLabel']); ?></strong> <?php echo htmlspecialchars($langContent['mantra']['tipText']); ?></aside>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 6 : TRANSITS & FESTIVALS ============ -->
  <section class="slide s-transits bg-texture" id="transits" aria-label="Transits and festivals">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">04</i>
        <span class="eyebrow"><span class="no">04</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['transits']['eyebrowText']); ?></span>
        <h2><?php echo htmlspecialchars($langContent['transits']['title']); ?></h2>
        <p><?php echo htmlspecialchars($langContent['transits']['subtitle']); ?></p>
      </div>
      <div class="transit-list">
        <?php foreach($langContent['transits']['planets'] as $ti => $transit): ?>
        <div class="transit-item rv"<?php echo ($ti % 4) > 0 ? ' style="--d:'.sprintf('%.2fs', ($ti % 4) * 0.03).'."' : ''; ?>><img src="../../assets/planets/<?php echo $transit['icon']; ?>.svg" alt="" loading="lazy"><div class="transit-body"><h3><b><?php echo htmlspecialchars($transit['name']); ?></b> &middot; <?php echo htmlspecialchars($transit['from']); ?> &rarr; <?php echo htmlspecialchars($transit['to']); ?></h3><span class="transit-date"><?php echo htmlspecialchars($transit['date']); ?></span></div></div>
        <?php endforeach; ?>
      </div>

      <p class="rule-label rv"><?php echo htmlspecialchars($langContent['transits']['festivalsLabel']); ?></p>
      <div class="fest-grid">
        <?php foreach($langContent['transits']['festivals'] as $fi => $fest): ?>
        <article class="fest-item rv"<?php echo $fi > 0 ? ' style="--d:.06s;"' : ''; ?>>
          <h3><?php echo htmlspecialchars($fest['name']); ?> <span class="fest-date"><?php echo htmlspecialchars($fest['date']); ?></span></h3>
          <p><?php echo htmlspecialchars($fest['desc']); ?></p>
        </article>
        <?php endforeach; ?>
      </div>

      <div class="panchang-cta rv" style="--d:.1s;">
        <p><?php echo htmlspecialchars($langContent['transits']['panchangCtaDesc']); ?></p>
        <a href="https://panchang.astrochitra.com/" target="_blank" rel="noopener" class="btn btn-gold"><?php echo htmlspecialchars($langContent['transits']['panchangCtaBtn']); ?></a>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 7 : MYTH VS REALITY ============ -->
  <section class="slide s-myth" id="myth" aria-label="Myth vs reality">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">05</i>
        <span class="eyebrow"><span class="no">05</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['myth']['eyebrowText']); ?></span>
        <h2><?php echo htmlspecialchars($langContent['myth']['title']); ?></h2>
        <p><?php echo htmlspecialchars($langContent['myth']['subtitle']); ?></p>
      </div>
      <div class="myth-cols">
        <span class="myth-vs" aria-hidden="true">vs</span>
        <div class="myth-col rv">
          <span class="myth-label myth-no">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            <?php echo htmlspecialchars($langContent['myth']['mythLabel']); ?>
          </span>
          <p><?php echo htmlspecialchars($langContent['myth']['mythText']); ?></p>
        </div>
        <div class="myth-col rv" style="--d:.1s;">
          <span class="myth-label myth-yes">
            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
            <?php echo htmlspecialchars($langContent['myth']['realityLabel']); ?>
          </span>
          <p><?php echo htmlspecialchars($langContent['myth']['realityText']); ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 8 : WATCH & FOLLOW ============ -->
  <section class="slide s-watch" id="watch" aria-label="Watch and follow">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">06</i>
        <span class="eyebrow"><span class="no">06</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['watch']['eyebrowText']); ?></span>
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
            <a href="https://www.youtube.com/@AstroChitraAstrology" target="_blank" rel="noopener" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4L15.8 12l-6.2 3.6z"/></svg>
              <?php echo htmlspecialchars($langContent['watch']['visitChannel']); ?>
            </a>
          </div>
        </div>
        <div class="rv" style="--d:.1s;">
          <p class="rule-label tight"><?php echo htmlspecialchars($langContent['watch']['instagramLabel']); ?></p>
          <div class="insta-grid">
            <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 1">
              <video src="../../assets/september_2026/featured.mp4" poster="../../assets/september_2026/featured_thumbnail.jpg" muted loop playsinline preload="none"></video>
              <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
              <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
            </div>
            <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 2">
              <video src="../../assets/september_2026/featured_2.mp4" poster="../../assets/september_2026/featured_2_thumbnail.jpg" muted loop playsinline preload="none"></video>
              <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
              <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
            </div>
            <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 3">
              <video src="../../assets/september_2026/featured_3.mp4" poster="../../assets/september_2026/featured_3_thumbnail.jpg" muted loop playsinline preload="none"></video>
              <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
              <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
            </div>
            <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 4">
              <video src="../../assets/september_2026/featured_4.mp4" poster="../../assets/september_2026/featured_4_thumbnail.jpg" muted loop playsinline preload="none"></video>
              <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
              <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
            </div>
          </div>
          <div class="channel-links">
            <a href="https://www.instagram.com/astrochitra.official/" target="_blank" rel="noopener" class="btn btn-outline">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
              <?php echo htmlspecialchars($langContent['watch']['followInsta']); ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 9 : DID YOU KNOW + BLOGS ============ -->
  <section class="slide s-know bg-texture" id="know" aria-label="Did you know and blogs">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">07</i>
        <span class="eyebrow"><span class="no">07</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['know']['eyebrowText']); ?></span>
        <h2><?php echo htmlspecialchars($langContent['know']['title']); ?></h2>
      </div>
      <div class="fact rv lift-none">
        <span class="fact-ic">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2z"/></svg>
        </span>
        <div>
          <h3><?php echo htmlspecialchars($langContent['know']['factTitle']); ?></h3>
          <p><?php echo htmlspecialchars($langContent['know']['factText']); ?></p>
        </div>
      </div>

      <p class="rule-label rv" style="--d:.06s;"><?php echo htmlspecialchars($langContent['know']['blogsLabel']); ?></p>
      <div class="blog-list">
        <?php foreach($langContent['know']['blogs'] as $bi => $blog): ?>
        <article class="blog-item rv"<?php echo $bi > 0 ? ' style="--d:'.sprintf('%.2fs', $bi * 0.05).'."' : ''; ?>>
          <?php if (!empty($blog['image'])): ?>
          <img class="blog-img" src="../../assets/september_2026/blog_featured/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" loading="lazy" decoding="async">
          <?php endif; ?>
          <div class="blog-cat"><?php echo htmlspecialchars($blog['category']); ?></div>
          <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
          <a href="https://blogs.astrochitra.com/" target="_blank" rel="noopener" class="blog-link"><?php echo htmlspecialchars($langContent['know']['readArticle']); ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 10 : TESTIMONIALS ============ -->
  <section class="slide s-testi" id="testimonials" aria-label="Testimonials">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">08</i>
        <span class="eyebrow"><span class="no">08</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['testimonials']['eyebrowText']); ?></span>
        <h2><?php echo htmlspecialchars($langContent['testimonials']['title']); ?></h2>
      </div>
      <div class="testi-wrap">
        <div class="insta-tile testi-tile rv" data-reel role="button" tabindex="0" aria-label="Play testimonial video">
          <video class="testi-video" src="../../assets/september_2026/testimonial.mp4" poster="../../assets/september_2026/testimonial_thumbnail.jpg" muted loop playsinline preload="none"></video>
          <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ SLIDE 11 : SHARE & COMMENT ============ -->
  <section class="slide s-share bg-texture" id="share" aria-label="Share and comment">
    <div class="slide-in">
      <div class="sec-head rv">
        <i class="ghost" aria-hidden="true">09</i>
        <span class="eyebrow"><span class="no">09</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['share']['eyebrowText']); ?></span>
        <h2><?php echo htmlspecialchars($langContent['share']['title']); ?></h2>
        <p><?php echo htmlspecialchars($langContent['share']['subtitle']); ?></p>
      </div>
      <?php if (isset($_GET['shared'])): ?>
        <div class="share-success"><?php echo htmlspecialchars($langContent['share']['successMsg']); ?></div>
      <?php endif; ?>
      <form method="post" action="../../api/interaction.php" class="share-form rv" style="--d:.08s;">
        <input type="hidden" name="slug" value="september-2026">
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars(strtok($_SERVER['REQUEST_URI'] ?? '/2026/september', '?')); ?>">
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
    <svg class="fstar fd1" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
    <svg class="fstar fd2" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
    <svg class="fstar fd3" width="9" height="9" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
    <span class="deco-ring rg1"></span>
    <span class="deco-ring rg2"></span>
    <div class="slide-in">
      <div class="final-wrap">
        <span class="eyebrow rv"><span class="no">10</span>&nbsp;&middot;&nbsp;<?php echo htmlspecialchars($langContent['final']['eyebrowText']); ?></span>
        <h2 class="rv" style="--d:.08s;"><?php echo $langContent['final']['title']; ?></h2>
        <p class="rv" style="--d:.16s;"><?php echo htmlspecialchars($langContent['final']['subtitle']); ?></p>
        <div class="final-actions rv" style="--d:.24s;">
          <a href="tel:+919820616655" class="btn btn-gold">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.24.2 2.45.57 3.57a1 1 0 0 1-.24 1.02l-2.21 2.2z"/></svg>
            <?php echo htmlspecialchars($langContent['final']['callBtn']); ?> &nbsp;+91 98206 16655
          </a>
          <button class="btn btn-primary" data-goto="10"><?php echo htmlspecialchars($langContent['final']['shareBtn']); ?></button>
        </div>
        <footer class="foot rv" style="--d:.3s;">
          <div class="foot-brand">
            <img src="../../assets/astrochitra-logo.png" alt="AstroChitra logo" loading="lazy">
            <span class="foot-tag"><?php echo htmlspecialchars($langContent['final']['footerTag']); ?></span>
          </div>
          <nav class="foot-links" aria-label="Footer">
            <?php foreach($langContent['final']['footerLinks'] as $fl): ?>
            <a href="<?php echo htmlspecialchars($fl['url']); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($fl['text']); ?></a>
            <?php endforeach; ?>
          </nav>
          <div class="foot-social">
            <a href="https://www.youtube.com/@AstroChitraAstrology" target="_blank" rel="noopener" class="soc" aria-label="YouTube">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4L15.8 12l-6.2 3.6z"/></svg>
            </a>
            <a href="https://www.instagram.com/astrochitra.official/" target="_blank" rel="noopener" class="soc" aria-label="Instagram">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            </a>
            <a href="#" class="soc" aria-label="Facebook">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5h1.65V3.6c-.3-.04-1.3-.13-2.45-.13-2.4 0-4.05 1.47-4.05 4.17v2.33H7.5V13h2.7v8h3.3z"/></svg>
            </a>
          </div>
          <p class="foot-copy"><?php echo htmlspecialchars($langContent['final']['footerCopy']); ?></p>
        </footer>
      </div>
    </div>
  </section>

</main>

<!-- ==================== FIXED BOTTOM NAV ==================== -->
<div class="deck-nav" id="deckNav">
  <button class="nav-btn" id="prevBtn" aria-label="Previous section">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
  </button>
  <div class="segs" id="segs" role="tablist" aria-label="Sections"></div>
  <span class="nav-count" aria-hidden="true"><b id="curNo">01</b>/<span id="totNo">12</span></span>
  <button class="nav-btn" id="nextBtn" aria-label="Next section">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
  </button>
</div>

<script>
(function(){
  var deck=document.getElementById('deck'),
      slides=[].slice.call(document.querySelectorAll('.slide')),
      segsWrap=document.getElementById('segs'),
      prevBtn=document.getElementById('prevBtn'),
      nextBtn=document.getElementById('nextBtn'),
      progFill=document.getElementById('progFill'),
      curNo=document.getElementById('curNo'),
      totNo=document.getElementById('totNo'),
      menuBtn=document.getElementById('menuBtn');

  var names=<?php echo json_encode($langContent['menu']['items'], JSON_UNESCAPED_UNICODE); ?>;
  var idx=0;

  totNo.textContent=(slides.length<10?'0':'')+slides.length;

  slides.forEach(function(_,i){
    var s=document.createElement('button');
    s.className='seg';s.type='button';
    s.setAttribute('role','tab');
    s.setAttribute('aria-label','Go to '+ (names[i]||('Section '+(i+1))));
    s.appendChild(document.createElement('i'));
    s.addEventListener('click',function(){goTo(i);});
    segsWrap.appendChild(s);
  });
  var segs=[].slice.call(segsWrap.children);

  function pad(n){return n<10?'0'+n:''+n;}
  function render(){
    deck.style.transform='translateX(-'+(idx*100)+'%)';
    segs.forEach(function(s,i){
      s.classList.toggle('on',i===idx);
      s.classList.toggle('done',i<idx);
      s.setAttribute('aria-selected',i===idx?'true':'false');
    });
    slides.forEach(function(s,i){s.classList.toggle('active',i===idx);});
    prevBtn.disabled=(idx===0);
    nextBtn.disabled=(idx===slides.length-1);
    progFill.style.width=(((idx+1)/slides.length)*100)+'%';
    curNo.textContent=pad(idx+1);
    [].forEach.call(document.querySelectorAll('#menuList a'),function(a){
      a.classList.toggle('current',parseInt(a.dataset.slide,10)===idx);
    });
    if(history.replaceState){
      var newUrl='#'+slides[idx].id;
      var u=new URL(window.location.href);
      u.hash=slides[idx].id;
      history.replaceState(null,'',u.toString());
    }
  }

  function goTo(i,instant){
    i=Math.max(0,Math.min(slides.length-1,i));
    if(instant){deck.style.transition='none';}
    idx=i;
    render();
    var inner=slides[i].querySelector('.slide-in');
    if(inner){inner.scrollTop=0;}
    if(instant){
      void deck.offsetWidth;
      deck.style.transition='';
    }
  }

  prevBtn.addEventListener('click',function(){goTo(idx-1);});
  nextBtn.addEventListener('click',function(){goTo(idx+1);});

  document.querySelectorAll('[data-goto]').forEach(function(b){
    b.addEventListener('click',function(){goTo(parseInt(b.dataset.goto,10));});
  });

  document.querySelectorAll('#menuList a').forEach(function(a){
    a.addEventListener('click',function(e){
      e.preventDefault();
      openMenu(false);
      goTo(parseInt(a.dataset.slide,10));
    });
  });

  function openMenu(o){
    document.body.classList.toggle('menu-open',o);
    menuBtn.setAttribute('aria-expanded',o?'true':'false');
    menuBtn.setAttribute('aria-label',o?'Close menu':'Open menu');
  }
  menuBtn.addEventListener('click',function(){openMenu(!document.body.classList.contains('menu-open'));});
  document.getElementById('menuBackdrop').addEventListener('click',function(){openMenu(false);});
  document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){openMenu(false);}
  });

  /* keyboard navigation */
  document.addEventListener('keydown',function(e){
    var t=e.target;
    if(t&&(t.tagName==='INPUT'||t.tagName==='TEXTAREA'||t.tagName==='SELECT'||t.isContentEditable)){return;}
    if(document.body.classList.contains('menu-open')){
      if(e.key==='ArrowRight'){goTo(idx+1);}
      else if(e.key==='ArrowLeft'){goTo(idx-1);}
      return;
    }
    if(e.key==='ArrowRight'||e.key==='PageDown'){e.preventDefault();goTo(idx+1);}
    else if(e.key==='ArrowLeft'||e.key==='PageUp'){e.preventDefault();goTo(idx-1);}
    else if(e.key==='Home'){e.preventDefault();goTo(0);}
    else if(e.key==='End'){e.preventDefault();goTo(slides.length-1);}
  });

  /* touch swipe with axis lock (vertical scroll inside slides still works) */
  var sx=0,sy=0,st=0,axis=null,ignoreTouch=false;
  function noSwipe(el){
    return el.closest&&el.closest('.deck-nav,.menu-panel');
  }
  deck.addEventListener('touchstart',function(e){
    if(e.touches.length!==1){axis=null;ignoreTouch=true;return;}
    if(noSwipe(e.target)){axis=null;ignoreTouch=true;return;}
    ignoreTouch=false;
    sx=e.touches[0].clientX;sy=e.touches[0].clientY;st=Date.now();
    axis=null;
  },{passive:true});

  deck.addEventListener('touchmove',function(e){
    if(ignoreTouch){return;}
    if(axis==='y'||e.touches.length!==1){return;}
    var dx=e.touches[0].clientX-sx, dy=e.touches[0].clientY-sy;
    if(!axis){
      if(Math.abs(dx)>8||Math.abs(dy)>8){
        axis=Math.abs(dx)>Math.abs(dy)*1.2?'x':'y';
      }
      if(axis!=='x'){return;}
    }
    if((idx===0&&dx>0)||(idx===slides.length-1&&dx<0)){return;}
    e.preventDefault();
    deck.style.transition='none';
    deck.style.transform='translateX(calc(-'+(idx*100)+'% + '+dx+'px))';
  },{passive:false});

  deck.addEventListener('touchend',function(e){
    if(ignoreTouch){return;}
    if(axis!=='x'){axis=null;return;}
    axis=null;
    var dx=(e.changedTouches[0].clientX-sx),
        dt=Date.now()-st;
    deck.style.transition='';
    deck.style.transform='translateX(-'+(idx*100)+'%)';
    if(dt<900&&Math.abs(dx)>56){
      goTo(idx+(dx<0?1:-1));
    }
  });

  /* share button */
  var shareBtn=document.getElementById('shareBtn'),toast=document.getElementById('shareToast');
  function showToast(){
    toast.classList.add('show');
    setTimeout(function(){toast.classList.remove('show');},2200);
  }
  shareBtn.addEventListener('click',function(){
    var url=location.href.split('#')[0];
    if(navigator.share){navigator.share({title:document.title,url:url}).catch(function(){});}
    else if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(url).then(showToast,function(){});}
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
  var reels=document.querySelectorAll('[data-reel]');
  function pauseOthers(cur){
    reels.forEach(function(t){
      var v=t.querySelector('video');
      if(v&&v!==cur){v.pause();}
    });
  }
  reels.forEach(function(tile){
    var video=tile.querySelector('video');
    function toggle(){
      if(!video){return;}
      if(video.paused){pauseOthers(video);video.muted=false;video.play().catch(function(){});}
      else{video.pause();}
    }
    tile.addEventListener('click',toggle);
    tile.addEventListener('keydown',function(e){
      if(e.key==='Enter'||e.key===' '){e.preventDefault();toggle();}
    });
    if(video){
      video.addEventListener('play',function(){tile.classList.add('playing');});
      video.addEventListener('pause',function(){tile.classList.remove('playing');});
    }
  });

  /* no-scroll on slides whose content fits the viewport (cover never scrolls) */
  function checkFit(){
    slides.forEach(function(s){
      var inner=s.querySelector('.slide-in');
      if(!inner){return;}
      s.classList.toggle('fits',inner.scrollHeight<=s.clientHeight+1);
    });
  }
  var rzT=null;
  window.addEventListener('resize',function(){clearTimeout(rzT);rzT=setTimeout(checkFit,120);});
  window.addEventListener('orientationchange',function(){setTimeout(checkFit,250);});
  window.addEventListener('load',checkFit);
  setTimeout(checkFit,350);

  /* init from hash */
  var h=location.hash.replace('#','');
  var start=slides.findIndex(function(s){return s.id===h;});
  if(start<0){start=0;}
  deck.style.transition='none';
  idx=start;render();
  var initInner=slides[start].querySelector('.slide-in');
  if(initInner){initInner.scrollTop=0;}
  requestAnimationFrame(function(){requestAnimationFrame(function(){deck.style.transition='';});});

  // hide page loader
  var loader=document.getElementById('pageLoader');
  if(loader){loader.classList.add('hidden');}
})();
</script>

<script src="../../assets/js/ac-track.js" data-slug="september-2026" defer></script>

</body>
</html>