<?php
require_once 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rashi = trim($_POST['rashi'] ?? '');

    if (empty($name) || empty($email) || empty($rashi)) {
        $message = "Please fill in all fields to complete registration.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please provide a valid email address.";
        $message_type = "error";
    } else {
        $result = add_subscriber($name, $email, $rashi);
        $message = $result['message'];
        $message_type = $result['status'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AstroChitra — Divine Vedic Insights</title>
<style>
  :root{
    --cream:#faf6ee;
    --parchment:#f4ecdd;
    --paper:#fffdf8;
    --ink:#33270e;
    --muted:#7a6a4f;
    --gold:#c9a227;
    --gold-light:#f0d98a;
    --terracotta:#8b4513;
    --crimson:#ae172a;
    --crimson-dark:#7d0f1d;
    --olive:#654e12;
    --night:#2b1005;
    --cocoa:#471d0b;
    --line:#e4d9c3;
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html,body{overflow-x:clip;}
  body{
    font-family:Georgia,'Times New Roman',serif;
    background:var(--cream);
    color:var(--ink);
    line-height:1.65;
    -webkit-font-smoothing:antialiased;
  }
  img{max-width:100%;display:block;}
  a{text-decoration:none;color:inherit;}

  .wrap{width:100%;max-width:1060px;margin:0 auto;padding:0 16px;}

  .btn{
    display:inline-flex;align-items:center;justify-content:center;gap:8px;
    padding:13px 22px;border-radius:999px;font-weight:bold;font-size:.95rem;
    border:2px solid transparent;line-height:1;text-align:center;
  }
  .btn svg{flex:none;}
  .btn-primary{background:var(--crimson);color:#fff;border-color:var(--gold);}
  .btn-primary:hover{background:var(--crimson-dark);border-color:var(--gold-light);}
  .btn-gold{background:var(--gold);color:var(--night);border-color:var(--gold);}
  .btn-gold:hover{background:var(--gold-light);border-color:var(--gold-light);}
  .btn-outline{background:transparent;color:var(--terracotta);border-color:var(--terracotta);}
  .btn-outline:hover{background:var(--parchment);}
  .btn-wa{background:#25d366;color:#fff;border-color:#25d366;}
  .btn-wa:hover{background:#1eb85a;border-color:#1eb85a;}

  .eyebrow{
    display:inline-flex;align-items:center;gap:8px;
    font-size:.72rem;font-weight:bold;letter-spacing:.18em;text-transform:uppercase;
    color:var(--terracotta);
  }
  .eyebrow::before,.eyebrow::after{content:"";width:26px;height:1px;background:var(--gold);}

  .section{padding:52px 0;}
  .section-alt{background:var(--parchment);border-top:1px solid var(--line);border-bottom:1px solid var(--line);}
  .sec-head{text-align:center;max-width:640px;margin:0 auto 34px;}
  .sec-head h2{font-size:clamp(1.55rem,4.5vw,2.15rem);line-height:1.25;margin-top:10px;color:var(--night);}
  .sec-head p{color:var(--muted);margin-top:10px;font-size:.98rem;}

  .card{
    background:var(--paper);
    border:1px solid var(--line);
    border-radius:14px;
    padding:22px;
  }

  /* ---------- HERO ---------- */
  .hero{
    background:var(--night);
    color:var(--cream);
    padding:60px 0 72px;
    position:relative;
    border-bottom:4px solid var(--gold);
  }
  .hero::before{
    content:"";position:absolute;inset:0;
    background:url('assets/bg-parchment.png') center/cover;
    opacity:.12;pointer-events:none;
  }
  .hero .wrap{position:relative;}
  .hero-top{display:flex;justify-content:center;margin-bottom:28px;}
  .brand-badge{
    display:inline-flex;align-items:center;gap:12px;
    background:rgba(255,253,248,.06);
    border:1px solid rgba(240,217,138,.35);
    border-radius:999px;padding:9px 18px 9px 10px;
  }
  .brand-badge img{height:44px;width:auto;}
  .brand-badge span{font-size:.78rem;letter-spacing:.22em;text-transform:uppercase;color:var(--gold-light);font-weight:bold;}
  .hero-inner{text-align:center;max-width:720px;margin:0 auto;}
  .hero-kicker{
    display:inline-block;font-size:.72rem;font-weight:bold;letter-spacing:.24em;text-transform:uppercase;
    color:var(--night);background:var(--gold-light);
    padding:6px 16px;border-radius:999px;margin-bottom:18px;
  }
  .hero h1{font-size:clamp(2.2rem,7vw,3.6rem);line-height:1.1;color:#fffdf8;}
  .hero h1 em{font-style:normal;color:var(--gold-light);}
  .hero-byline{
    margin-top:14px;font-size:clamp(1rem,2.8vw,1.2rem);
    letter-spacing:.14em;text-transform:uppercase;color:#d8cbb2;
  }
  .hero-cta{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-top:30px;}
  .trust-row{
    display:flex;flex-wrap:wrap;justify-content:center;gap:10px 22px;
    margin-top:28px;font-size:.8rem;color:#cbbd9f;
  }
  .trust-row span{display:inline-flex;align-items:center;gap:7px;}

  /* ---------- FEATURED NEWSLETTER ---------- */
  .featured{
    background:var(--crimson);color:#fff;
    padding:48px 0;border-top:3px solid var(--gold);border-bottom:3px solid var(--gold);
    outline:1px solid rgba(240,217,138,.55);outline-offset:-10px;
  }
  .featured-inner{display:flex;flex-direction:column;gap:22px;align-items:center;text-align:center;}
  .featured-badge{
    display:inline-flex;align-items:center;gap:8px;
    font-size:.7rem;font-weight:bold;letter-spacing:.2em;text-transform:uppercase;
    color:var(--night);background:var(--gold-light);border-radius:999px;padding:6px 14px;
  }
  .featured h2{font-size:clamp(1.6rem,5vw,2.4rem);line-height:1.2;color:#fffdf8;}
  .featured p{color:#f3e3cf;max-width:560px;}
  .featured-actions{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;}

  /* ---------- SUBSCRIPTION FORM ---------- */
  .form-card{background:var(--paper);border:1px solid var(--line);border-radius:16px;padding:32px 24px;}
  .form-title{font-size:clamp(1.4rem,4vw,1.9rem);color:var(--night);text-align:center;margin-bottom:6px;}
  .form-desc{text-align:center;color:var(--muted);margin-bottom:24px;}
  .form-row{display:grid;grid-template-columns:1fr;gap:16px;}
  .form-group{display:flex;flex-direction:column;gap:6px;}
  .form-group label{
    font-size:.72rem;font-weight:bold;letter-spacing:.1em;text-transform:uppercase;color:var(--olive);
  }
  .form-group input,.form-group select{
    width:100%;padding:13px 14px;
    border:1.5px solid var(--line);border-radius:10px;
    font-family:inherit;font-size:1rem;color:var(--ink);background:var(--cream);
    transition:border-color .2s;
  }
  .form-group input:focus,.form-group select:focus{outline:none;border-color:var(--gold);}
  .form-submit{margin-top:8px;}
  .banner{
    padding:12px 16px;border-radius:10px;margin-bottom:20px;
    font-size:.9rem;text-align:center;font-weight:600;
  }
  .banner-error{background:#fbeee9;color:var(--crimson-dark);border:1px solid #eec9c0;}
  .banner-success{background:#f2efdc;color:var(--olive);border:1px solid #ddd3ac;}
  .banner-exists{background:#fef9f0;color:#92400e;border:1px solid #fde8c4;}

  /* ---------- PRODUCTS ---------- */
  .prod-wrap{display:flex;flex-wrap:wrap;justify-content:center;gap:12px;}
  .prod-chip{
    display:flex;align-items:center;gap:10px;
    background:var(--paper);border:1px solid var(--line);border-radius:999px;
    padding:10px 18px 10px 12px;font-size:.88rem;font-weight:bold;color:var(--cocoa);
  }
  .prod-chip:hover{border-color:var(--gold);background:var(--cream);}
  .prod-ic{
    flex:none;width:32px;height:32px;border-radius:50%;
    background:var(--gold-light);color:var(--night);
    display:flex;align-items:center;justify-content:center;
  }

  /* ---------- FOOTER ---------- */
  footer{background:var(--cocoa);color:#e8dcc2;padding:36px 0;border-top:1px solid rgba(240,217,138,.3);}
  .foot-inner{display:flex;flex-direction:column;align-items:center;gap:14px;text-align:center;}
  .foot-brand{display:flex;align-items:center;gap:10px;}
  .foot-brand img{height:34px;width:auto;}
  .foot-links{display:flex;flex-wrap:wrap;justify-content:center;gap:8px 18px;font-size:.84rem;}
  .foot-links a:hover{color:var(--gold-light);}
  .foot-copy{font-size:.76rem;color:#bfae8d;}

  /* ---------- TABLET ---------- */
  @media (min-width:640px){
    .section{padding:64px 0;}
    .form-row{grid-template-columns:1fr 1fr;}
    .featured-inner{flex-direction:row;align-items:center;justify-content:space-between;text-align:left;}
    .featured-copy{max-width:55%;}
  }

  /* ---------- DESKTOP ---------- */
  @media (min-width:1024px){
    .hero{padding:80px 0 96px;}
  }
</style>
</head>
<body>

<!-- ================= HERO ================= -->
<header class="hero">
  <div class="wrap">
    <div class="hero-top">
      <div class="brand-badge">
        <img src="assets/astrochitra-logo.png" alt="AstroChitra logo">
        <span>AstroChitra</span>
      </div>
    </div>
    <div class="hero-inner">
      <span class="hero-kicker">Divine Vedic Insights</span>
      <h1>AstroChitra<br><em>Guidance · Growth · Grace</em></h1>
      <p class="hero-byline">Monthly wisdom from Guruji</p>
      <div class="hero-cta">
        <a href="#subscribe" class="btn btn-primary">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Subscribe
        </a>
      </div>
      <div class="trust-row">
        <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> 25+ Years Practice</span>
        <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> 50,000+ Seekers</span>
        <span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> Authentic Methods</span>
      </div>
    </div>
  </div>
</header>



<!-- ================= SUBSCRIBE ================= -->
<section class="section" id="subscribe">
  <div class="wrap">
    <div class="sec-head">
      <span class="eyebrow">Monthly Wisdom</span>
      <h2>Subscribe to the Newsletter</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Receive planetary alignments, transits, personal remedies and wisdom directly from Guruji.</p>
    </div>
    <div class="card form-card" style="max-width:580px;margin:0 auto;">
      <?php if (!empty($message)): ?>
        <div class="banner banner-<?= $message_type ?>">
          <?= $message ?>
        </div>
      <?php endif; ?>
      <form method="POST" action="">
        <h3 class="form-title">Join the Circle</h3>
        <p class="form-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor.</p>
        <div class="form-row">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your first name">
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="name@example.com">
          </div>
        </div>
        <div class="form-row" style="grid-template-columns:1fr;">
          <div class="form-group">
            <label for="rashi">Moon Sign (Rashi)</label>
            <select id="rashi" name="rashi" required>
              <option value="" disabled selected>Choose your Zodiac sign</option>
              <option value="Aries">Aries (Mesh)</option>
              <option value="Taurus">Taurus (Vrishabh)</option>
              <option value="Gemini">Gemini (Mithun)</option>
              <option value="Cancer">Cancer (Karka)</option>
              <option value="Leo">Leo (Simha)</option>
              <option value="Virgo">Virgo (Kanya)</option>
              <option value="Libra">Libra (Tula)</option>
              <option value="Scorpio">Scorpio (Vrishchik)</option>
              <option value="Sagittarius">Sagittarius (Dhanu)</option>
              <option value="Capricorn">Capricorn (Makar)</option>
              <option value="Aquarius">Aquarius (Kumbh)</option>
              <option value="Pisces">Pisces (Meen)</option>
            </select>
          </div>
        </div>
        <button type="submit" name="subscribe" class="btn btn-primary form-submit" style="width:100%;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          Subscribe to Wisdom
        </button>
      </form>
    </div>
  </div>
</section>

<!-- ================= FREE TOOLS ---------->
<section class="section section-alt">
  <div class="wrap">
    <div class="sec-head">
      <span class="eyebrow">Free Tools</span>
      <h2>Explore AstroChitra Products</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Everything you need, in one place.</p>
    </div>
    <div class="prod-wrap">
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 3v3M12 18v3M3 12h3M18 12h3M12 12l4-4"/></svg></span>
        Generate Kundli
      </a>
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span>
        Daily Panchang
      </a>
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4zM4 8h16M8 4v16"/></svg></span>
        Newsletter
      </a>
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/></svg></span>
        Ask Guruji
      </a>
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"/><circle cx="16.5" cy="9.5" r="2.5"/><path d="M3 20c0-3 2.7-4.5 6-4.5s6 1.5 6 4.5M15.5 15.7c2.8.2 5.5 1.5 5.5 4.3"/></svg></span>
        Matchmaking
      </a>
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.5A4.5 4.5 0 0 0 3.5 9c0 4.5 8.5 10.5 8.5 10.5S20.5 13.5 20.5 9A4.5 4.5 0 0 0 12 6.5z"/></svg></span>
        Blogs
      </a>
      <a href="#" class="prod-chip">
        <span class="prod-ic"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 12h4l2-7 4 14 2-7h6"/></svg></span>
        Insights
      </a>
    </div>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
  <div class="wrap foot-inner">
    <div class="foot-brand">
      <img src="assets/astrochitra-logo.png" alt="AstroChitra logo">
    </div>
    <nav class="foot-links">
      <a href="#">Kundli</a>
      <a href="#">Panchang</a>
      <a href="#">Matchmaking</a>
      <a href="#">Ask Guruji</a>
      <a href="#">Blogs</a>
      <a href="#">Privacy</a>
    </nav>
    <p class="foot-copy">&copy; 2026 AstroChitra. Siddharth Nagar, Goregaon (West), Mumbai – 400104.</p>
  </div>
</footer>

</body>
</html>