<?php
$title = 'AstroChitra Monthly Newsletter | September 2026';
require_once __DIR__ . '/../admin/db.php';
if (function_exists('track_view')) {
    track_view($pdo, 'september-2026', 'September 2026 | AstroChitra Monthly Newsletter');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title><?php echo htmlspecialchars($title); ?></title>
<style>
  :root{
    --cream:#faf6ee; --parchment:#f4ecdd; --paper:#fffdf8;
    --ink:#33270e; --muted:#7a6a4f;
    --gold:#c9a227; --gold-light:#f0d98a;
    --terracotta:#8b4513; --crimson:#ae172a; --crimson-dark:#7d0f1d;
    --olive:#654e12; --night:#2b1005; --cocoa:#471d0b;
    --line:#e4d9c3;
    --nav-h:74px; --pad-b:calc(var(--nav-h) + env(safe-area-inset-bottom) + 28px);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html,body{height:100%;}
  body{
    font-family:Georgia,'Times New Roman',serif;
    background:var(--cream); color:var(--ink);
    line-height:1.6; overflow:hidden;
    -webkit-font-smoothing:antialiased;
    overscroll-behavior:none;
  }
  img{max-width:100%;display:block;}
  a{text-decoration:none;color:inherit;}
  button{font-family:inherit;}

  /* ---------- PROGRESS BAR ---------- */
  .progress{position:fixed;top:0;left:0;right:0;height:3px;z-index:300;background:rgba(201,162,39,.18);}
  .progress span{display:block;height:100%;width:0;background:linear-gradient(90deg,var(--gold),var(--gold-light));transition:width .45s ease;}

  /* ---------- DECK ---------- */
  .deck{
    position:fixed; inset:0;
    display:flex;
    transition:transform .55s cubic-bezier(.77,0,.18,1);
    will-change:transform;
  }
  .slide{
    flex:0 0 100%;
    height:100%;
    overflow-y:auto; overflow-x:hidden;
    -webkit-overflow-scrolling:touch;
    overscroll-behavior:contain;
    touch-action:pan-y;
    position:relative;
    scrollbar-width:thin; scrollbar-color:rgba(139,69,19,.35) transparent;
  }
  .slide::-webkit-scrollbar{width:5px;}
  .slide::-webkit-scrollbar-thumb{background:rgba(139,69,19,.3);border-radius:99px;}
  .slide-in{
    min-height:100%;
    display:flex; flex-direction:column; justify-content:center;
    padding:clamp(64px,9vh,90px) clamp(16px,4vw,48px) var(--pad-b);
    position:relative; z-index:1;
  }
  .slide-in.tight{justify-content:flex-start;}

  .s-hero,.s-mantra,.s-final{background:var(--night);color:var(--cream);}
  .s-guruji,.s-transits,.s-know,.s-share{background:var(--parchment);}
  .s-rashifal,.s-myth,.s-watch,.s-testi{background:var(--cream);}
  .s-products{background:var(--cocoa);color:#f3e3cf;}

  .bg-texture::before,
  .s-hero::before,.s-final::before{
    content:""; position:absolute; inset:0;
    background:url('../assets/bg-parchment.png') center/cover;
    opacity:.08; pointer-events:none;
  }
  .s-guruji::before,.s-transits::before,.s-know::before,.s-share::before{opacity:.14;}

  .deco-ring{position:absolute;border:1.5px solid rgba(240,217,138,.25);border-radius:50%;pointer-events:none;}
  .s-hero .rg1{width:280px;height:280px;top:-100px;right:-80px;}
  .s-hero .rg2{width:160px;height:160px;bottom:-50px;left:-40px;}
  .s-mantra .rg1{width:300px;height:300px;top:-140px;right:-110px;border-color:rgba(240,217,138,.2);}
  .s-mantra .rg2{width:220px;height:220px;bottom:-120px;left:-90px;border-color:rgba(240,217,138,.2);}
  .s-final .rg1{width:320px;height:320px;top:-140px;left:-120px;border-color:rgba(240,217,138,.22);}
  .s-final .rg2{width:260px;height:260px;bottom:-130px;right:-100px;border-color:rgba(240,217,138,.22);}
  .deco-sign{position:absolute;opacity:.09;width:min(230px,34vw);pointer-events:none;}
  .ds-l{left:-50px;bottom:-30px;transform:rotate(-8deg);}
  .ds-r{right:-36px;top:70px;transform:rotate(10deg);width:min(170px,26vw);}

  /* ---------- SHARED UI ---------- */
  .btn{
    display:inline-flex;align-items:center;justify-content:center;gap:8px;
    padding:13px 24px;border-radius:999px;font-weight:bold;font-size:.95rem;
    border:2px solid transparent;line-height:1;text-align:center;cursor:pointer;
  }
  .btn-primary{background:var(--crimson);color:#fff;border-color:var(--gold);}
  .btn-primary:hover{background:var(--crimson-dark);}
  .btn-gold{background:var(--gold);color:var(--night);border-color:var(--gold);}
  .btn-gold:hover{background:var(--gold-light);}
  .btn-outline{background:transparent;color:var(--terracotta);border-color:var(--terracotta);}
  .btn-outline:hover{background:var(--paper);}
  .btn-wa{background:#25d366;color:#fff;border-color:#25d366;}
  .btn-wa:hover{background:#1eb85a;}

  .eyebrow{
    display:inline-flex;align-items:center;gap:10px;
    font-size:.72rem;font-weight:bold;letter-spacing:.2em;text-transform:uppercase;
    color:var(--terracotta);
  }
  .eyebrow::before,.eyebrow::after{content:"";width:26px;height:1px;background:var(--gold);}
  .sec-head{text-align:center;max-width:660px;margin:0 auto 30px;}
  .sec-head h2{font-size:clamp(1.5rem,4.6vw,2.15rem);line-height:1.22;margin-top:10px;color:var(--night);}
  .sec-head p{color:var(--muted);margin-top:8px;font-size:.95rem;}
  .card{background:var(--paper);border:1px solid var(--line);border-radius:16px;padding:22px;}

  /* ---------- FIXED CHROME ---------- */
  .menu-btn,.share-btn{
    position:fixed;top:calc(12px + env(safe-area-inset-top));z-index:260;
    width:46px;height:46px;border-radius:50%;
    background:var(--paper);border:2px solid var(--terracotta);color:var(--terracotta);
    display:flex;align-items:center;justify-content:center;cursor:pointer;
  }
  .menu-btn{right:14px;}
  .share-btn{left:14px;}
  .menu-btn:hover,.share-btn:hover{background:var(--gold-light);border-color:var(--gold);color:var(--night);}
  .menu-btn .ic-close{display:none;}
  body.menu-open .menu-btn .ic-open{display:none;}
  body.menu-open .menu-btn .ic-close{display:block;}
  .share-toast{
    position:fixed;left:50%;bottom:calc(var(--nav-h) + 24px);transform:translateX(-50%) translateY(8px);z-index:270;
    background:var(--night);color:var(--cream);border:1px solid var(--gold);
    border-radius:999px;padding:9px 20px;font-size:.82rem;
    opacity:0;visibility:hidden;transition:.25s;
  }
  .share-toast.show{opacity:1;visibility:visible;transform:translateX(-50%) translateY(0);}

  .menu-backdrop{position:fixed;inset:0;z-index:250;background:rgba(43,16,5,.6);opacity:0;visibility:hidden;transition:opacity .2s;}
  body.menu-open .menu-backdrop{opacity:1;visibility:visible;}
  .menu-panel{
    position:fixed;top:0;right:0;bottom:0;z-index:255;width:min(300px,84vw);
    background:var(--paper);border-left:1px solid var(--line);
    transform:translateX(100%);transition:transform .25s ease;
    padding:70px 16px calc(var(--nav-h) + 16px);overflow-y:auto;
  }
  body.menu-open .menu-panel{transform:translateX(0);}
  .menu-title{font-size:.7rem;font-weight:bold;letter-spacing:.2em;text-transform:uppercase;color:var(--terracotta);text-align:center;margin-bottom:12px;}
  .menu-list{display:flex;flex-direction:column;gap:7px;}
  .menu-list a{
    font-size:.83rem;font-weight:bold;color:var(--terracotta);
    background:var(--cream);border:1px solid var(--line);border-radius:10px;
    padding:10px 13px;display:flex;align-items:center;justify-content:space-between;gap:8px;
  }
  .menu-list a:hover,.menu-list a.current{background:var(--gold-light);border-color:var(--gold);color:var(--night);}

  /* ---------- BOTTOM NAV ---------- */
  .deck-nav{
    position:fixed;left:50%;bottom:calc(14px + env(safe-area-inset-bottom));transform:translateX(-50%);
    z-index:240;display:flex;align-items:center;gap:10px;
    background:rgba(43,16,5,.94);border:1.5px solid var(--gold);
    border-radius:999px;padding:8px 12px;
    box-shadow:0 10px 28px rgba(43,16,5,.35);
    max-width:calc(100vw - 24px);
  }
  .nav-btn{
    flex:none;width:44px;height:44px;border-radius:50%;border:none;cursor:pointer;
    background:var(--gold);color:var(--night);
    display:flex;align-items:center;justify-content:center;
    transition:background .15s,transform .15s;
  }
  .nav-btn:hover{background:var(--gold-light);transform:scale(1.06);}
  .nav-btn:disabled{opacity:.32;cursor:default;transform:none;background:var(--gold);}
  .dots{display:flex;align-items:center;gap:6px;padding:0 4px;}
  .dot{
    width:8px;height:8px;border-radius:50%;border:none;cursor:pointer;padding:0;
    background:rgba(240,217,138,.35);transition:.2s;
  }
  .dot.active{background:var(--gold-light);transform:scale(1.45);}
  @media(max-width:420px){
    .dots{gap:5px;} .dot{width:6px;height:6px;}
    .nav-btn{width:40px;height:40px;}
  }
  .slide-label{
    position:fixed;left:50%;bottom:calc(var(--nav-h) + 30px + env(safe-area-inset-bottom));transform:translateX(-50%);
    z-index:235;background:rgba(255,253,248,.95);border:1px solid var(--gold);color:var(--cocoa);
    border-radius:999px;padding:5px 16px;font-size:.72rem;font-weight:bold;letter-spacing:.18em;text-transform:uppercase;
    opacity:0;pointer-events:none;transition:opacity .4s;white-space:nowrap;
  }
  .slide-label.show{opacity:1;}

  /* ---------- SLIDE 1 : HERO ---------- */
  .hero-wrap{text-align:center;max-width:760px;margin:0 auto;display:flex;flex-direction:column;align-items:center;gap:0;}
  .brand-badge{
    display:inline-flex;align-items:center;gap:12px;margin-bottom:26px;
    background:rgba(255,253,248,.06);border:1px solid rgba(240,217,138,.35);
    border-radius:999px;padding:8px 18px 8px 9px;
  }
  .brand-badge img{height:38px;width:auto;}
  .brand-badge span{font-size:.76rem;letter-spacing:.22em;text-transform:uppercase;color:var(--gold-light);font-weight:bold;}
  .hero-kicker{
    display:inline-block;font-size:.7rem;font-weight:bold;letter-spacing:.24em;text-transform:uppercase;
    color:var(--night);background:var(--gold-light);padding:6px 16px;border-radius:999px;margin-bottom:18px;
  }
  .hero-wrap h1{font-size:clamp(2.1rem,8vw,3.6rem);line-height:1.1;color:#fffdf8;}
  .hero-wrap h1 em{font-style:normal;color:var(--gold-light);}
  .hero-byline{margin-top:12px;letter-spacing:.16em;text-transform:uppercase;color:#d8cbb2;font-size:clamp(.85rem,2.4vw,1.05rem);}
  .hero-date{
    margin-top:18px;display:inline-flex;align-items:center;gap:8px;
    font-size:.82rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gold-light);
    border:1px solid rgba(240,217,138,.4);border-radius:999px;padding:8px 18px;
  }
  .hero-cta{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-top:30px;}
  .hero-hint{margin-top:26px;font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:#bfae8d;display:inline-flex;align-items:center;gap:8px;}
  .hero-hint svg{animation:nudge 1.6s ease infinite;}
  @keyframes nudge{0%,100%{transform:translateX(0);}50%{transform:translateX(6px);}}

  /* ---------- SLIDE 2 : GURUJI + CONSULT ---------- */
  .guruji-col{max-width:720px;margin:0 auto;display:flex;flex-direction:column;align-items:center;gap:20px;text-align:center;}
  .guruji-photo{
    width:128px;height:128px;border-radius:50%;object-fit:cover;object-position:center top;
    outline:3px solid var(--gold);outline-offset:4px;
  }
  .quote-mark{color:var(--gold);font-size:3.2rem;line-height:.55;font-family:Georgia,serif;}
  .guruji-quote{font-size:clamp(1.1rem,3.4vw,1.42rem);font-style:italic;color:var(--cocoa);max-width:620px;}
  .guruji-name{font-weight:bold;color:var(--terracotta);letter-spacing:.06em;}
  .guruji-role{font-size:.8rem;color:var(--muted);}
  .consult-band{
    margin-top:14px;width:100%;max-width:720px;
    background:var(--crimson);color:#fff;border-radius:16px;
    border:1.5px solid var(--gold);padding:24px 22px;text-align:left;
    display:flex;flex-wrap:wrap;gap:18px;align-items:center;justify-content:space-between;
  }
  .consult-copy h3{font-size:clamp(1.05rem,3vw,1.3rem);line-height:1.3;color:#fffdf8;}
  .consult-copy p{font-size:.85rem;color:#f3e3cf;margin-top:6px;max-width:380px;}
  .consult-actions{display:flex;flex-wrap:wrap;gap:10px;}

  /* ---------- SLIDE 3 : RASHIFAL ---------- */
  .rashi-grid{display:grid;grid-template-columns:1fr;gap:13px;}
  .rashi-card{display:flex;gap:14px;align-items:flex-start;padding:18px;}
  .rashi-glyph{
    flex:none;width:58px;height:58px;border-radius:50%;
    background:var(--parchment);border:1px solid var(--gold);
    outline:1px solid var(--line);outline-offset:3px;
    display:flex;align-items:center;justify-content:center;padding:8px;
  }
  .rashi-glyph img{width:100%;height:100%;object-fit:contain;}
  .rashi-body h3{font-size:1rem;color:var(--night);}
  .rashi-dates{font-size:.74rem;color:var(--muted);margin:2px 0 6px;}
  .rashi-body p{font-size:.85rem;color:var(--muted);}
  .rashi-tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:9px;}
  .tag{
    font-size:.66rem;font-weight:bold;letter-spacing:.05em;text-transform:uppercase;
    padding:3px 10px;border-radius:999px;border:1px solid var(--line);color:var(--olive);background:var(--cream);
  }
  .tag-hot{color:var(--crimson);border-color:#eec9c0;background:#fbeee9;}
  @media(min-width:700px){.rashi-grid{grid-template-columns:repeat(2,1fr);}}

  /* ---------- SLIDE 4 : PRODUCTS ---------- */
  .prod-wrap{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;max-width:820px;margin:0 auto;}
  .prod-chip{
    display:flex;align-items:center;gap:10px;
    background:rgba(255,253,248,.07);border:1px solid rgba(240,217,138,.4);border-radius:999px;
    padding:11px 20px 11px 12px;font-size:.88rem;font-weight:bold;color:#fffdf8;
    transition:.15s;
  }
  .prod-chip:hover{background:rgba(240,217,138,.16);border-color:var(--gold-light);}
  .prod-ic{
    flex:none;width:32px;height:32px;border-radius:50%;
    background:var(--gold-light);color:var(--night);
    display:flex;align-items:center;justify-content:center;
  }
  .prod-note{text-align:center;margin-top:22px;font-size:.78rem;letter-spacing:.12em;text-transform:uppercase;color:#cbbd9f;}

  /* ---------- SLIDE 5 : MANTRA ---------- */
  .mantra-card{
    position:relative;overflow:hidden;text-align:center;
    max-width:680px;margin:0 auto;
    background:rgba(255,253,248,.04);border:1px solid rgba(240,217,138,.4);border-radius:20px;
    padding:clamp(28px,5vw,44px) clamp(18px,4vw,30px);
  }
  .mantra-kicker{
    display:inline-flex;align-items:center;gap:10px;
    font-size:.7rem;font-weight:bold;letter-spacing:.24em;text-transform:uppercase;color:var(--gold-light);
  }
  .mantra-sanskrit{margin:18px 0 10px;font-size:clamp(1.4rem,5.4vw,2.1rem);line-height:1.45;color:#fffdf8;}
  .mantra-meaning{color:#d8cbb2;font-style:italic;max-width:520px;margin:0 auto;font-size:.93rem;}
  .mantra-tip{
    margin:22px auto 0;max-width:520px;text-align:left;
    background:rgba(250,246,238,.07);border:1px solid rgba(240,217,138,.35);
    border-radius:12px;padding:14px 18px;font-size:.86rem;color:#e8dcc2;
  }

  /* ---------- SLIDE 6 : TRANSITS & FESTIVALS ---------- */
  .transit-list{display:flex;flex-direction:column;gap:11px;max-width:760px;margin:0 auto;}
  .transit-item{display:flex;gap:13px;align-items:center;padding:13px 16px;}
  .transit-planet{
    flex:none;width:46px;height:46px;border-radius:50%;
    background:var(--parchment);border:1px solid var(--gold);
    outline:1px solid var(--line);outline-offset:2px;
    display:flex;align-items:center;justify-content:center;padding:7px;
  }
  .transit-planet img{width:100%;height:100%;object-fit:contain;}
  .transit-body{display:flex;flex-wrap:wrap;align-items:baseline;gap:2px 12px;flex:1;}
  .transit-body h3{font-size:.95rem;color:var(--night);}
  .transit-date{font-size:.74rem;font-weight:bold;letter-spacing:.06em;text-transform:uppercase;color:var(--crimson);}
  .fest-sub{
    max-width:760px;margin:34px auto 14px;text-align:center;
    font-size:.76rem;font-weight:bold;letter-spacing:.16em;text-transform:uppercase;color:var(--terracotta);
    display:flex;align-items:center;gap:14px;
  }
  .fest-sub::before,.fest-sub::after{content:"";flex:1;height:1px;background:var(--gold);}
  .fest-grid{display:grid;grid-template-columns:1fr;gap:12px;max-width:760px;margin:0 auto;}
  @media(min-width:700px){.fest-grid{grid-template-columns:repeat(2,1fr);}}
  .fest-card h3{font-size:.98rem;color:var(--night);display:flex;flex-wrap:wrap;align-items:center;gap:8px 10px;}
  .fest-date{
    flex:none;font-size:.68rem;font-weight:bold;letter-spacing:.06em;text-transform:uppercase;
    color:var(--night);background:var(--gold-light);border-radius:999px;padding:3px 10px;
  }
  .fest-card p{font-size:.85rem;color:var(--muted);margin-top:7px;}
  .panchang-strip{
    max-width:760px;margin:18px auto 0;background:var(--paper);border:1px solid var(--line);border-radius:14px;
    padding:16px 18px;display:flex;flex-direction:column;gap:8px;
  }
  .panchang-title{font-size:.72rem;font-weight:bold;letter-spacing:.18em;text-transform:uppercase;color:var(--terracotta);}
  .pan-row{display:flex;flex-wrap:wrap;align-items:baseline;gap:4px 12px;font-size:.86rem;border-bottom:1px dashed var(--line);padding-bottom:7px;}
  .pan-row:last-child{border-bottom:none;padding-bottom:0;}
  .pan-key{min-width:150px;font-weight:bold;color:var(--cocoa);}
  .pan-val{color:var(--muted);}

  /* ---------- SLIDE 7 : MYTH ---------- */
  .myth-grid{display:grid;grid-template-columns:1fr;gap:14px;max-width:860px;margin:0 auto;}
  @media(min-width:820px){.myth-grid{grid-template-columns:repeat(2,1fr);}}
  .myth-label{
    display:inline-flex;align-items:center;gap:7px;
    font-size:.68rem;font-weight:bold;letter-spacing:.1em;text-transform:uppercase;
    padding:4px 12px;border-radius:999px;margin-bottom:12px;
  }
  .myth-no{color:var(--crimson);background:#fbeee9;border:1px solid #eec9c0;}
  .myth-yes{color:var(--olive);background:#f2efdc;border:1px solid #ddd3ac;}
  .myth-card h3{font-size:1rem;color:var(--night);margin-bottom:8px;line-height:1.4;}
  .myth-card p{font-size:.87rem;color:var(--muted);}
  .myth-card p + p{margin-top:8px;}

  /* ---------- SLIDE 8 : WATCH ---------- */
  .media-sub{
    max-width:760px;margin:0 auto 14px;text-align:center;
    font-size:.76rem;font-weight:bold;letter-spacing:.16em;text-transform:uppercase;color:var(--terracotta);
    display:flex;align-items:center;gap:14px;
  }
  .media-sub::before,.media-sub::after{content:"";flex:1;height:1px;background:var(--gold);}
  .yt-feature{max-width:720px;margin:0 auto;}
  .yt-frame{border:none;background:#000;border-radius:14px;overflow:hidden;}
  .yt-frame iframe{width:100%;aspect-ratio:16/9;display:block;border:0;}
  .channel-links{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-top:18px;}
  .insta-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;max-width:640px;margin:0 auto;}
  @media(min-width:700px){.insta-grid{grid-template-columns:repeat(4,1fr);}}
  .insta-tile{position:relative;border-radius:12px;overflow:hidden;border:1px solid var(--line);background:#000;}
  .insta-tile video{width:100%;aspect-ratio:9/16;object-fit:cover;display:block;}
  .reel-play{
    position:absolute;inset:0;margin:auto;
    width:52px;height:52px;border-radius:50%;
    background:rgba(43,16,5,.72);color:#fffdf8;
    border:2px solid var(--gold-light);
    display:flex;align-items:center;justify-content:center;
    pointer-events:none;transition:opacity .18s,transform .18s;
  }
  .insta-tile.playing .reel-play{opacity:0;transform:scale(.85);}
  .insta-ic{
    position:absolute;top:8px;right:8px;width:26px;height:26px;border-radius:50%;
    background:rgba(43,16,5,.75);color:#fffdf8;
    display:flex;align-items:center;justify-content:center;
  }
  .watch-gap{height:34px;}

  /* ---------- SLIDE 9 : KNOWLEDGE ---------- */
  .fact-card{display:flex;gap:14px;align-items:flex-start;max-width:860px;margin:0 auto;}
  .fact-ic{
    flex:none;width:42px;height:42px;border-radius:50%;
    background:var(--gold-light);color:var(--night);
    display:flex;align-items:center;justify-content:center;
  }
  .fact-card h3{font-size:.98rem;color:var(--night);}
  .fact-card p{font-size:.88rem;color:var(--muted);margin-top:4px;}
  .blog-grid{display:grid;grid-template-columns:1fr;gap:13px;max-width:860px;margin:14px auto 0;}
  @media(min-width:700px){.blog-grid{grid-template-columns:repeat(2,1fr);}}
  .blog-cat{font-size:.66rem;font-weight:bold;letter-spacing:.12em;text-transform:uppercase;color:var(--terracotta);}
  .blog-card h3{font-size:.95rem;color:var(--night);margin-top:5px;line-height:1.45;}
  .blog-link{display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:bold;color:var(--crimson);margin-top:10px;}
  .blog-link:hover{color:var(--crimson-dark);}

  /* ---------- SLIDE 10 : TESTIMONIALS ---------- */
  .testi-wrap{max-width:420px;margin:0 auto;text-align:center;}
  .testi-video{width:100%;aspect-ratio:9/16;border-radius:16px;border:1px solid var(--line);background:#000;object-fit:cover;}
  .stars{display:flex;gap:4px;color:var(--gold);justify-content:center;margin-top:16px;}
  .testi-text{font-size:.92rem;font-style:italic;color:var(--cocoa);margin-top:10px;}
  .testi-person{display:flex;align-items:center;gap:12px;margin-top:14px;justify-content:center;}
  .testi-person img{width:42px;height:42px;border-radius:50%;object-fit:cover;outline:2px solid var(--gold);outline-offset:2px;}
  .testi-person strong{display:block;font-size:.86rem;color:var(--night);text-align:left;}
  .testi-person span{font-size:.75rem;color:var(--muted);}

  /* ---------- SLIDE 11 : SHARE ---------- */
  .share-success{
    max-width:600px;margin:0 auto 16px;text-align:center;
    background:#f2efdc;border:1px solid var(--gold);border-radius:12px;
    padding:13px 18px;color:var(--olive);font-weight:bold;font-size:.9rem;
  }
  .share-card{max-width:600px;margin:0 auto;display:flex;flex-direction:column;gap:13px;}
  .share-card label{display:flex;flex-direction:column;gap:6px;font-size:.82rem;font-weight:bold;color:var(--cocoa);}
  .share-card label em{font-weight:normal;color:var(--muted);font-style:normal;}
  .share-card input,.share-card textarea{
    font-family:inherit;font-size:.94rem;color:var(--ink);
    background:var(--cream);border:1px solid var(--line);border-radius:10px;
    padding:12px 14px;outline:none;width:100%;resize:vertical;
  }
  .share-card input:focus,.share-card textarea:focus{border-color:var(--gold);}
  .share-note{font-size:.76rem;color:var(--muted);text-align:center;}

  /* ---------- SLIDE 12 : FINAL + FOOTER ---------- */
  .final-wrap{text-align:center;max-width:640px;margin:0 auto;position:relative;z-index:1;}
  .fstar{position:absolute;color:var(--gold-light);opacity:.9;}
  .fd1{top:12%;left:10%;} .fd2{top:22%;right:9%;} .fd3{bottom:34%;left:22%;}
  .final-wrap h2{font-size:clamp(1.55rem,5vw,2.35rem);color:#fffdf8;line-height:1.2;}
  .final-wrap>.eyebrow{color:var(--gold-light);}
  .final-wrap p{color:#d8cbb2;max-width:520px;margin:14px auto 0;font-size:.95rem;}
  .final-actions{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-top:26px;}
  .foot{
    margin-top:auto;position:relative;z-index:1;
    border-top:1px solid rgba(240,217,138,.3);
    padding-top:22px;margin-top:34px;
    display:flex;flex-direction:column;align-items:center;gap:14px;text-align:center;
  }
  .foot-brand{display:flex;align-items:center;gap:10px;}
  .foot-brand img{height:32px;width:auto;}
  .foot-links{display:flex;flex-wrap:wrap;justify-content:center;gap:6px 18px;font-size:.82rem;color:#e8dcc2;}
  .foot-links a:hover{color:var(--gold-light);}
  .foot-social{display:flex;gap:10px;}
  .soc{
    width:36px;height:36px;border-radius:50%;
    border:1px solid rgba(240,217,138,.4);color:var(--gold-light);
    display:flex;align-items:center;justify-content:center;
  }
  .soc:hover{background:rgba(240,217,138,.12);}
  .foot-copy{font-size:.74rem;color:#bfae8d;}

  @media(prefers-reduced-motion:reduce){
    .deck{transition:none;}
    .hero-hint svg{animation:none;}
  }
</style>
</head>
<body>

<div class="progress" aria-hidden="true"><span id="progFill"></span></div>

<button class="share-btn" id="shareBtn" aria-label="Share this newsletter">
  <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/></svg>
</button>
<div class="share-toast" id="shareToast">Link copied to clipboard</div>

<button class="menu-btn" id="menuBtn" aria-label="Open menu" aria-expanded="false">
  <svg class="ic-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
  <svg class="ic-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
</button>
<div class="menu-backdrop" id="menuBackdrop"></div>
<nav class="menu-panel" id="menuPanel" aria-label="Newsletter sections">
  <span class="menu-title">In this issue</span>
  <div class="menu-list" id="menuList">
    <a href="#" data-slide="0">Cover</a>
    <a href="#" data-slide="1">Guruji Speaks</a>
    <a href="#" data-slide="2">Monthly Rashifal</a>
    <a href="#" data-slide="3">Web Products</a>
    <a href="#" data-slide="4">Mantra of the Month</a>
    <a href="#" data-slide="5">Transits &amp; Festivals</a>
    <a href="#" data-slide="6">Myth vs Reality</a>
    <a href="#" data-slide="7">Watch &amp; Follow</a>
    <a href="#" data-slide="8">Did You Know &middot; Blogs</a>
    <a href="#" data-slide="9">Testimonials</a>
    <a href="#" data-slide="10">Share &amp; Comment</a>
    <a href="#" data-slide="11">Consult Now</a>
  </div>
</nav>

<!-- ==================== DECK ==================== -->
<main class="deck" id="deck">

  <!-- SLIDE 0 : COVER -->
  <section class="slide s-hero" id="cover" aria-label="Cover">
    <img class="deco-sign ds-l" src="../assets/signs/Libra.svg" alt="">
    <img class="deco-sign ds-r" src="../assets/signs/Virgo.svg" alt="">
    <span class="deco-ring rg1"></span>
    <span class="deco-ring rg2"></span>
    <div class="slide-in">
      <div class="hero-wrap">
        <div class="brand-badge">
          <img src="../assets/astrochitra-logo.png" alt="AstroChitra logo">
          <span>AstroChitra</span>
        </div>
        <span class="hero-kicker">Monthly Edition</span>
        <h1>September<br><em>Newsletter</em></h1>
        <p class="hero-byline">by AstroChitra</p>
        <span class="hero-date">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Sep 1 &ndash; Sep 30, 2026
        </span>
        <div class="hero-cta">
          <button class="btn btn-primary" data-goto="11">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Book Consultation
          </button>
          <button class="btn btn-gold" data-goto="2">Read Rashifal</button>
        </div>
        <span class="hero-hint">
          Swipe to explore
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
      </div>
    </div>
  </section>

  <!-- SLIDE 1 : GURUJI + CONSULT -->
  <section class="slide s-guruji bg-texture" id="guruji" aria-label="Guruji speaks">
    <div class="slide-in tight">
      <div style="text-align:center;" class="sec-head">
        <span class="eyebrow">Guruji's Thought of the Month</span>
      </div>
      <div class="guruji-col">
        <img class="guruji-photo" src="../assets/Guruji2.jpg" alt="Portrait of Guruji">
        <div class="quote-mark">&ldquo;</div>
        <p class="guruji-quote">Better to follow your own path imperfectly than to follow someone else's path perfectly.</p>
        <p>
          <span class="guruji-name">Guruji</span><br>
          <span class="guruji-role">Founder &amp; Chief Astrologer, AstroChitra</span>
        </p>
        <div class="consult-band" id="consult-mini">
          <div class="consult-copy">
            <h3>Confused about your career, marriage or health?</h3>
            <p>Get personal guidance from Guruji with a one-on-one Vedic consultation tailored to your birth chart.</p>
          </div>
          <div class="consult-actions">
            <button class="btn btn-gold" data-goto="11">Book Consultation</button>
            <a href="#" class="btn btn-wa">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2a9.9 9.9 0 0 0-8.51 14.93L2 22l5.2-1.49A9.9 9.9 0 1 0 12.04 2zm5.77 14.06c-.24.68-1.4 1.3-1.93 1.35-.52.05-1.01.24-3.4-.71-2.87-1.13-4.68-4.06-4.82-4.25-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.26-.28.57-.35.76-.35h.55c.18 0 .42-.07.66.5.24.57.83 2.03.9 2.18.07.14.12.31.02.5-.1.19-.15.3-.29.47-.15.17-.31.38-.44.51-.14.14-.29.29-.12.57.16.28.73 1.2 1.57 1.95 1.08.96 1.99 1.26 2.27 1.4.28.14.44.12.61-.07.16-.19.7-.81.89-1.09.19-.28.38-.23.63-.14.26.09 1.63.77 1.91.91.28.14.47.21.54.33.07.12.07.68-.17 1.35z"/></svg>
              WhatsApp Us
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 2 : RASHIFAL -->
  <section class="slide s-rashifal" id="rashifal" aria-label="Monthly rashifal">
    <div class="slide-in tight">
      <div class="sec-head">
        <span class="eyebrow">Monthly Horoscope</span>
        <h2>Rashifal &mdash; September 2026</h2>
        <p>Date-wise impacts for all twelve moon signs this month.</p>
      </div>
      <div class="rashi-grid">
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Aries.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Aries</h3>
            <div class="rashi-dates">Mar 21 &ndash; Apr 19</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Sep 4&ndash;9 Career Push</span><span class="tag">Sep 18 Finance</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Taurus.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Taurus</h3>
            <div class="rashi-dates">Apr 20 &ndash; May 20</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam quis nostrud.</p>
            <div class="rashi-tags"><span class="tag">Sep 2 Family</span><span class="tag tag-hot">Sep 12 Wealth</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Gemini.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Gemini</h3>
            <div class="rashi-dates">May 21 &ndash; Jun 20</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aute irure dolor in reprehenderit.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Sep 6 Communication</span><span class="tag">Sep 21 Travel</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Cancer.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Cancer</h3>
            <div class="rashi-dates">Jun 21 &ndash; Jul 22</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Excepteur sint occaecat cupidatat non proident.</p>
            <div class="rashi-tags"><span class="tag">Sep 3 Emotions</span><span class="tag">Sep 15 Home</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Leo.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Leo</h3>
            <div class="rashi-dates">Jul 23 &ndash; Aug 22</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sunt in culpa qui officia deserunt mollit anim.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Sep 11 Venus Entry</span><span class="tag">Sep 24 Recognition</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Virgo.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Virgo</h3>
            <div class="rashi-dates">Aug 23 &ndash; Sep 22</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste natus error.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Sep 4 &amp; 17 Double Transit</span><span class="tag">Birthday Month</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Libra.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Libra</h3>
            <div class="rashi-dates">Sep 23 &ndash; Oct 22</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nemo enim ipsam voluptatem quia voluptas.</p>
            <div class="rashi-tags"><span class="tag">Sep 8 Relationships</span><span class="tag">Sep 19 Balance</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Scorpio.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Scorpio</h3>
            <div class="rashi-dates">Oct 23 &ndash; Nov 21</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Neque porro quisquam est qui dolorem ipsum.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Sep 10 Transformation</span><span class="tag">Sep 26 Research</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Sagittarius.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Sagittarius</h3>
            <div class="rashi-dates">Nov 22 &ndash; Dec 21</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis autem vel eum iure reprehenderit qui.</p>
            <div class="rashi-tags"><span class="tag">Sep 5 Learning</span><span class="tag">Sep 22 Long Travel</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Capricorn.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Capricorn</h3>
            <div class="rashi-dates">Dec 22 &ndash; Jan 19</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. At vero eos et accusamus et iusto odio.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Sep 14 Discipline Pays</span><span class="tag">Sep 28 Career</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Aquarius.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Aquarius</h3>
            <div class="rashi-dates">Jan 20 &ndash; Feb 18</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Et harum quidem rerum facilis est et expedita.</p>
            <div class="rashi-tags"><span class="tag">Sep 7 Network</span><span class="tag">Sep 20 Ideas</span></div>
          </div>
        </article>
        <article class="card rashi-card">
          <div class="rashi-glyph"><img src="../assets/signs/Pisces.svg" alt=""></div>
          <div class="rashi-body">
            <h3>Pisces</h3>
            <div class="rashi-dates">Feb 19 &ndash; Mar 20</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Temporibus autem quibusdam et aut officiis.</p>
            <div class="rashi-tags"><span class="tag tag-hot">Saturn Retro Watch</span><span class="tag">Sep 16 Intuition</span></div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- SLIDE 3 : WEB PRODUCTS -->
  <section class="slide s-products" id="products" aria-label="AstroChitra web products">
    <div class="slide-in">
      <div class="sec-head">
        <span class="eyebrow" style="color:var(--gold-light);">Free Tools &amp; Services</span>
        <h2 style="color:#fffdf8;">Explore AstroChitra Web Products</h2>
        <p style="color:#e8dcc2;">Everything you need for your jyotish journey, in one place.</p>
      </div>
      <div class="prod-wrap">
        <a href="https://astrochitra.com/kundli" target="_blank" rel="noopener" class="prod-chip">
          <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3M12 12l4-4"/></svg></span>
          Kundli Generator
        </a>
        <a href="https://astrochitra.com/matchmaking" target="_blank" rel="noopener" class="prod-chip">
          <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"/><circle cx="16.5" cy="9.5" r="2.5"/><path d="M3 20c0-3 2.7-4.5 6-4.5s6 1.5 6 4.5M15.5 15.7c2.8.2 5.5 1.5 5.5 4.3"/></svg></span>
          Matchmaking Tool
        </a>
        <a href="https://astrochitra.com/insights" target="_blank" rel="noopener" class="prod-chip">
          <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 12h4l2-7 4 14 2-7h6"/></svg></span>
          AstroChitra Insights
        </a>
        <a href="https://blogs.astrochitra.com/" target="_blank" rel="noopener" class="prod-chip">
          <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4zM4 8h16M8 4v16"/></svg></span>
          AstroChitra Journal
        </a>
        <a href="https://panchang.astrochitra.com/" target="_blank" rel="noopener" class="prod-chip">
          <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
          Panchang App
        </a>
      </div>
      <p class="prod-note">Tap any product to open it</p>
    </div>
  </section>

  <!-- SLIDE 4 : MANTRA -->
  <section class="slide s-mantra" id="mantra" aria-label="Mantra of the month">
    <span class="deco-ring rg1"></span>
    <span class="deco-ring rg2"></span>
    <div class="slide-in">
      <div class="mantra-card">
        <span class="mantra-kicker">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
          Mantra of the Month
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
        </span>
        <p class="mantra-sanskrit">Om Gan Ganpatey Namah</p>
        <p class="mantra-meaning">Om represents the universal cosmic vibration, Gan is the seed sound of Lord Ganesha, Ganapataye means &ldquo;to the lord of all beings,&rdquo; and Namah expresses humble salutations. Chanting it helps clear mental, physical, and spiritual roadblocks to ensure smooth progress whenever you start a new journey or project. Additionally, the rhythmic repetition calms the nervous system, sharpening your focus while dissolving anxiety and fear.</p>
        <div class="mantra-tip">
          <strong>Guruji's tip:</strong> Chant 108 times each morning this month for smooth progress and inner calm.
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 5 : TRANSITS & FESTIVALS -->
  <section class="slide s-transits bg-texture" id="transits" aria-label="Transits and festivals">
    <div class="slide-in tight">
      <div class="sec-head">
        <span class="eyebrow">Planetary Movements</span>
        <h2>September Transits &amp; Festivals</h2>
        <p>Key graha movements through the month, plus sacred dates to remember.</p>
      </div>
      <div class="transit-list">
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Pisces &rarr; Aries</h3><span class="transit-date">1 Sept, 03:23 am (8d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Venus.svg" alt=""></div>
          <div class="transit-body"><h3>Venus &middot; Virgo &rarr; Libra</h3><span class="transit-date">2 Sept, 01:44 pm (9d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Aries &rarr; Taurus</h3><span class="transit-date">3 Sept, 07:25 am (10d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Taurus &rarr; Gemini</h3><span class="transit-date">5 Sept, 10:18 am (12d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Gemini &rarr; Cancer</h3><span class="transit-date">7 Sept, 12:38 pm (14d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Mercury.svg" alt=""></div>
          <div class="transit-body"><h3>Mercury &middot; Leo &rarr; Virgo</h3><span class="transit-date">7 Sept, 01:31 pm (14d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Cancer &rarr; Leo</h3><span class="transit-date">9 Sept, 03:14 pm (16d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Leo &rarr; Virgo</h3><span class="transit-date">11 Sept, 07:08 pm (18d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Virgo &rarr; Libra</h3><span class="transit-date">14 Sept, 01:26 am (21d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Libra &rarr; Scorpio</h3><span class="transit-date">16 Sept, 10:48 am (23d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Sun.svg" alt=""></div>
          <div class="transit-body"><h3>Sun &middot; Leo &rarr; Virgo</h3><span class="transit-date">17 Sept, 07:50 am (24d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Mars.svg" alt=""></div>
          <div class="transit-body"><h3>Mars &middot; Gemini &rarr; Cancer</h3><span class="transit-date">18 Sept, 04:30 pm (25d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Scorpio &rarr; Sagittarius</h3><span class="transit-date">18 Sept, 10:44 pm (25d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Sagittarius &rarr; Capricorn</h3><span class="transit-date">21 Sept, 11:15 am (28d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Capricorn &rarr; Aquarius</h3><span class="transit-date">23 Sept, 09:56 pm (30d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Aquarius &rarr; Pisces</h3><span class="transit-date">26 Sept, 05:33 am (33d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Mercury.svg" alt=""></div>
          <div class="transit-body"><h3>Mercury &middot; Virgo &rarr; Libra</h3><span class="transit-date">26 Sept, 12:37 pm (33d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Pisces &rarr; Aries</h3><span class="transit-date">28 Sept, 10:16 am (35d)</span></div>
        </article>
        <article class="card transit-item">
          <div class="transit-planet"><img src="../assets/planets/Moon.svg" alt=""></div>
          <div class="transit-body"><h3>Moon &middot; Aries &rarr; Taurus</h3><span class="transit-date">30 Sept, 01:13 pm (37d)</span></div>
        </article>
      </div>

      <p class="fest-sub">Festivals &amp; Panchang</p>
      <div class="fest-grid">
        <article class="card fest-card">
          <h3>Krishna Janmashtami <span class="fest-date">04 September</span></h3>
          <p>Celebrate the birth of Lord Krishna with devotion, fasting, and midnight prayers.</p>
        </article>
        <article class="card fest-card">
          <h3>Ganesh Chaturthi <span class="fest-date">14 September</span></h3>
          <p>Welcome Lord Ganesha home; perform puja for removal of obstacles and new beginnings.</p>
        </article>
      </div>
      <div class="panchang-strip">
        <span class="panchang-title">Month-at-a-glance Panchang</span>
        <div class="pan-row"><span class="pan-key">Rahu Kaal (daily)</span><span class="pan-val">Timing window varies by weekday</span></div>
        <div class="pan-row"><span class="pan-key">Shubh Muhurat</span><span class="pan-val">Auspicious windows for griha pravesh, vivah</span></div>
        <div class="pan-row"><span class="pan-key">Vrat Days</span><span class="pan-val">Ekadashi, pradosh and sankashti dates</span></div>
      </div>
    </div>
  </section>

  <!-- SLIDE 6 : MYTH -->
  <section class="slide s-myth" id="myth" aria-label="Myth vs reality">
    <div class="slide-in">
      <div class="sec-head">
        <span class="eyebrow">Myth to Break</span>
        <h2>The Saturn&ndash;Mars Conjunction</h2>
        <p>Clearing superstitions, one belief at a time.</p>
      </div>
      <div class="myth-grid">
        <article class="card myth-card">
          <span class="myth-label myth-no">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            Myth
          </span>
          <h3>In Vedic astrology, the Saturn&ndash;Mars conjunction is considered an inauspicious combination.</h3>
          <p>Saturn is associated with delay and slow movement, whereas Mars takes quick decisions and moves rapidly. Since both are contradictory planets, their conjunction is considered an inauspicious yoga &mdash; one believed to create obstacles, delays, or repeated difficulties in professional life and disturb mental balance.</p>
        </article>
        <article class="card myth-card">
          <span class="myth-label myth-yes">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
            Reality
          </span>
          <h3>It can prove highly beneficial for technical and industrial fields.</h3>
          <p>The Saturn&ndash;Mars conjunction favours Engineering, Construction, Mining, Mechanical Work, Machinery &amp; Manufacturing. It is often seen in the birth charts of big industrialists, factory owners and builders &mdash; turning apparent adversity into disciplined, lasting success.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- SLIDE 7 : WATCH & FOLLOW -->
  <section class="slide s-watch" id="watch" aria-label="Watch and follow">
    <div class="slide-in tight">
      <div class="sec-head">
        <span class="eyebrow">This Month On</span>
        <h2>Watch &amp; Follow</h2>
        <p>Wisdom videos and daily cosmic updates from our channels.</p>
      </div>

      <p class="media-sub">YouTube &middot; Videos from August</p>
      <div class="yt-feature">
        <div class="yt-frame">
          <iframe src="https://www.youtube.com/embed/mHkaw76botE" title="AstroChitra YouTube video" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
      </div>
      <div class="channel-links">
        <a href="https://www.youtube.com/@AstroChitraAstrology" target="_blank" rel="noopener" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4L15.8 12l-6.2 3.6z"/></svg>
          Visit Our Channel
        </a>
      </div>

      <div class="watch-gap"></div>

      <p class="media-sub">Instagram &middot; Featured Reels</p>
      <div class="insta-grid">
        <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 1">
          <video src="../assets/september_2026/featured.mp4" muted loop playsinline preload="metadata"></video>
          <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
          <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
        </div>
        <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 2">
          <video src="../assets/september_2026/featured_2.mp4" muted loop playsinline preload="metadata"></video>
          <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
          <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
        </div>
        <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 3">
          <video src="../assets/september_2026/featured_3.mp4" muted loop playsinline preload="metadata"></video>
          <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
          <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
        </div>
        <div class="insta-tile" data-reel role="button" tabindex="0" aria-label="Play Instagram reel 4">
          <video src="../assets/september_2026/featured_4.mp4" muted loop playsinline preload="metadata"></video>
          <span class="reel-play"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
          <span class="insta-ic"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
        </div>
      </div>
      <div class="channel-links">
        <a href="https://www.instagram.com/astrochitra.official/" target="_blank" rel="noopener" class="btn btn-outline">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
          Follow @astrochitra.official
        </a>
      </div>
    </div>
  </section>

  <!-- SLIDE 8 : DID YOU KNOW + BLOGS -->
  <section class="slide s-know bg-texture" id="know" aria-label="Did you know and blogs">
    <div class="slide-in tight">
      <div class="sec-head">
        <span class="eyebrow">Cosmic Trivia</span>
        <h2>Did You Know?</h2>
      </div>
      <article class="card fact-card">
        <span class="fact-ic">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.4 1 2.3h6c0-.9.4-1.8 1-2.3A7 7 0 0 0 12 2z"/></svg>
        </span>
        <div>
          <h3>Vedic Astrology</h3>
          <p>In Vedic astrology, an ancient science used to predict outcomes based on the combined positions of zodiac signs (Rashi), constellations (Nakshatra), houses (Bhav), and planets at the time of an individual's birth (date, time, and place of birth) by examining their placements in the horoscope, is known as Vedic Astrology.</p>
        </div>
      </article>

      <p class="media-sub" style="margin-top:30px;">Our Blogs &middot; Featured Reads</p>
      <div class="blog-grid">
        <article class="card blog-card">
          <div class="blog-cat">Vedic Wisdom</div>
          <h3>What is Manglik Dosh?</h3>
          <a href="https://blogs.astrochitra.com/what-is-manglik-dosh/" target="_blank" rel="noopener" class="blog-link">Read article
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </article>
        <article class="card blog-card">
          <div class="blog-cat">Remedies</div>
          <h3>Kemdrum Dosha: Effects, Causes &amp; Remedies</h3>
          <a href="https://blogs.astrochitra.com/what-is-kemdrum-dosha-effects-causes-remedies/" target="_blank" rel="noopener" class="blog-link">Read article
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </article>
        <article class="card blog-card">
          <div class="blog-cat">Personal Reflections</div>
          <h3>I Am Saturn Speaking</h3>
          <a href="https://blogs.astrochitra.com/i-am-saturn-speaking/" target="_blank" rel="noopener" class="blog-link">Read article
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </article>
        <article class="card blog-card">
          <div class="blog-cat">Yogas</div>
          <h3>Gaj Kesari Yog &mdash; Its Importance in Human Life</h3>
          <a href="https://blogs.astrochitra.com/gaj-kesari-yog-its-importance-in-human-life/" target="_blank" rel="noopener" class="blog-link">Read article
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </article>
      </div>
    </div>
  </section>

  <!-- SLIDE 9 : TESTIMONIALS -->
  <section class="slide s-testi" id="testimonials" aria-label="Testimonials">
    <div class="slide-in">
      <div class="sec-head">
        <span class="eyebrow">Kind Words</span>
        <h2>What Seekers Say</h2>
      </div>
      <div class="testi-wrap">
        <video class="testi-video" src="../assets/september_2026/testimonial.mp4" controls preload="metadata"></video>
        <div class="stars">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
        </div>
        <p class="testi-text">&ldquo;AstroChitra's guidance transformed my perspective on career timing. The personalized reading was spot-on.&rdquo;</p>
        <div class="testi-person">
          <img src="../assets/person_placeholder.png" alt="">
          <div><strong>Anonymous Seeker</strong><span>Video testimonial</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- SLIDE 10 : SHARE & COMMENT -->
  <section class="slide s-share bg-texture" id="share" aria-label="Share and comment">
    <div class="slide-in">
      <div class="sec-head">
        <span class="eyebrow">Spread the Wisdom</span>
        <h2>Share This Newsletter</h2>
        <p>Know someone who needs cosmic guidance? Share this issue and drop a comment with your email or phone &mdash; Guruji's team will reach out.</p>
      </div>
      <?php if (isset($_GET['shared'])): ?>
        <div class="share-success">Thank you! Your share has been recorded. We will be in touch soon.</div>
      <?php endif; ?>
      <form method="post" action="../api/interaction.php" class="card share-card">
        <input type="hidden" name="slug" value="september-2026">
        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars(strtok($_SERVER['REQUEST_URI'] ?? '/newsletters/september-2026.php', '?')); ?>">
        <label>Email address
          <input type="email" name="email" placeholder="you@example.com" required>
        </label>
        <label>Phone / WhatsApp <em>(optional)</em>
          <input type="tel" name="phone" placeholder="+91 98765 43210">
        </label>
        <label>Comment <em>(optional)</em>
          <textarea name="message" rows="3" placeholder="Tell us what you loved, or ask Guruji a question..."></textarea>
        </label>
        <button type="submit" class="btn btn-gold">Share &amp; Comment</button>
        <p class="share-note">We respect your privacy &mdash; contact details are only used to respond to you.</p>
      </form>
    </div>
  </section>

  <!-- SLIDE 11 : FINAL CTA + FOOTER -->
  <section class="slide s-final" id="consult" aria-label="Consultation and footer">
    <svg class="fstar fd1" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
    <svg class="fstar fd2" width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
    <svg class="fstar fd3" width="9" height="9" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.6L22 12l-7.6 2.4L12 22l-2.4-7.6L2 12l7.6-2.4z"/></svg>
    <span class="deco-ring rg1"></span>
    <span class="deco-ring rg2"></span>
    <div class="slide-in">
      <div class="final-wrap">
        <span class="eyebrow">Limited Slots This Month</span>
        <h2 style="margin-top:10px;">Let the stars guide your next step</h2>
        <p>Book your personal consultation with Guruji before the September slots fill up.</p>
        <div class="final-actions">
          <a href="#" class="btn btn-gold">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.61 21 3 13.39 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.24.2 2.45.57 3.57a1 1 0 0 1-.24 1.02l-2.21 2.2z"/></svg>
            Call / Book Now
          </a>
          <button class="btn btn-primary" data-goto="10">Share This Issue</button>
        </div>
        <div class="foot">
          <div class="foot-brand">
            <img src="../assets/astrochitra-logo.png" alt="AstroChitra logo">
          </div>
          <nav class="foot-links">
            <a href="https://astrochitra.com/kundli" target="_blank" rel="noopener">Kundli</a>
            <a href="https://panchang.astrochitra.com/" target="_blank" rel="noopener">Panchang</a>
            <a href="https://astrochitra.com/matchmaking" target="_blank" rel="noopener">Matchmaking</a>
            <a href="https://astrochitra.com/insights" target="_blank" rel="noopener">Insights</a>
            <a href="https://blogs.astrochitra.com/" target="_blank" rel="noopener">Journal</a>
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
          <p class="foot-copy">&copy; 2026 AstroChitra. All rights reserved.</p>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- ==================== FIXED BOTTOM NAV ==================== -->
<div class="slide-label" id="slideLabel" aria-hidden="true"></div>
<div class="deck-nav" id="deckNav">
  <button class="nav-btn" id="prevBtn" aria-label="Previous section">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
  </button>
  <div class="dots" id="dots" role="tablist" aria-label="Sections"></div>
  <button class="nav-btn" id="nextBtn" aria-label="Next section">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
  </button>
</div>

<script>
(function(){
  var deck=document.getElementById('deck'),
      slides=[].slice.call(document.querySelectorAll('.slide')),
      dotsWrap=document.getElementById('dots'),
      prevBtn=document.getElementById('prevBtn'),
      nextBtn=document.getElementById('nextBtn'),
      progFill=document.getElementById('progFill'),
      label=document.getElementById('slideLabel'),
      menuBtn=document.getElementById('menuBtn'),
      menuPanel=document.getElementById('menuPanel'),
      menuBackdrop=document.getElementById('menuBackdrop');

  var names=['Cover','Guruji Speaks','Monthly Rashifal','Web Products','Mantra of the Month',
             'Transits & Festivals','Myth vs Reality','Watch & Follow','Know & Blogs',
             'Testimonials','Share & Comment','Consult Now'];
  var idx=0,labelTimer=null;

  slides.forEach(function(_,i){
    var d=document.createElement('button');
    d.className='dot';d.type='button';
    d.setAttribute('role','tab');
    d.setAttribute('aria-label','Go to '+ (names[i]||('Section '+(i+1))));
    d.addEventListener('click',function(){goTo(i);});
    dotsWrap.appendChild(d);
  });
  var dots=[].slice.call(dotsWrap.children);

  function pad(n){return n<10?'0'+n:''+n;}
  function render(){
    deck.style.transform='translateX(-'+(idx*100)+'%)';
    dots.forEach(function(d,i){
      d.classList.toggle('active',i===idx);
      d.setAttribute('aria-selected',i===idx?'true':'false');
    });
    prevBtn.disabled=(idx===0);
    nextBtn.disabled=(idx===slides.length-1);
    progFill.style.width=(((idx+1)/slides.length)*100)+'%';
    label.textContent=pad(idx+1)+' \u00B7 '+(names[idx]||'');
    label.classList.remove('show');
    void label.offsetWidth;
    label.classList.add('show');
    clearTimeout(labelTimer);
    labelTimer=setTimeout(function(){label.classList.remove('show');},1800);
    [].forEach.call(document.querySelectorAll('#menuList a'),function(a){
      a.classList.toggle('current',parseInt(a.dataset.slide,10)===idx);
    });
    if(history.replaceState){history.replaceState(null,'','#'+slides[idx].id);}
  }

  function goTo(i,instant){
    i=Math.max(0,Math.min(slides.length-1,i));
    if(instant){deck.style.transition='none';}
    var prev=slides[idx];
    idx=i;
    render();
    slides[i].scrollTop=0;
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
      closeMenu();
      goTo(parseInt(a.dataset.slide,10));
    });
  });

  function openMenu(o){
    document.body.classList.toggle('menu-open',o);
    menuBtn.setAttribute('aria-expanded',o?'true':'false');
  }
  menuBtn.addEventListener('click',function(){openMenu(!document.body.classList.contains('menu-open'));});
  menuBackdrop.addEventListener('click',function(){openMenu(false);});
  document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){openMenu(false);}
  });

  /* keyboard navigation */
  document.addEventListener('keydown',function(e){
    var t=e.target;
    if(t&&(t.tagName==='INPUT'||t.tagName==='TEXTAREA'||t.tagName==='SELECT'||t.isContentEditable)){return;}
    if(document.body.classList.contains('menu-open')){return;}
    if(e.key==='ArrowRight'||e.key==='PageDown'){e.preventDefault();goTo(idx+1);}
    else if(e.key==='ArrowLeft'||e.key==='PageUp'){e.preventDefault();goTo(idx-1);}
    else if(e.key==='Home'){e.preventDefault();goTo(0);}
    else if(e.key==='End'){e.preventDefault();goTo(slides.length-1);}
  });

  /* touch swipe with axis lock (vertical scroll inside slides still works) */
  var sx=0,sy=0,st=0,axis=null,baseIdx=0;
  function noSwipe(el){
    return el.closest&&el.closest('.deck-nav,input,textarea,select,a[href],button,[data-reel],.menu-panel');
  }
  deck.addEventListener('touchstart',function(e){
    if(e.touches.length!==1){axis=null;return;}
    if(noSwipe(e.target)){axis=null;return;}
    sx=e.touches[0].clientX;sy=e.touches[0].clientY;st=Date.now();
    axis=null;baseIdx=idx;
  },{passive:true});

  deck.addEventListener('touchmove',function(e){
    if(axis==='y'||e.touches.length!==1){return;}
    var dx=e.touches[0].clientX-sx, dy=e.touches[0].clientY-sy;
    if(!axis){
      if(Math.abs(dx)>12||Math.abs(dy)>12){
        axis=Math.abs(dx)>Math.abs(dy)*1.4?'x':'y';
      }
      if(axis!=='x'){return;}
    }
    if((idx===0&&dx>0)||(idx===slides.length-1&&dx<0)){return;}
    e.preventDefault();
    deck.style.transition='none';
    deck.style.transform='translateX(calc(-'+(idx*100)+'% + '+dx+'px))';
  },{passive:false});

  deck.addEventListener('touchend',function(e){
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
      if(video.paused){pauseOthers(video);video.play();}
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

  /* init from hash */
  var h=location.hash.replace('#','');
  var start=slides.findIndex(function(s){return s.id===h;});
  if(start<0){start=0;}
  deck.style.transition='none';
  idx=start;render();slides[start].scrollTop=0;
  requestAnimationFrame(function(){requestAnimationFrame(function(){deck.style.transition='';});});
})();
</script>

<script src="../assets/js/ac-track.js" data-slug="september-2026"></script>

</body>
</html>
