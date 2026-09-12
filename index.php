<?php
require_once __DIR__ . '/admin/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscribe to AstroChitra Newsletter</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Saira:wght@400;500;600;700&family=Cormorant+Garamond:wght@500;600;700&display=swap" rel="stylesheet">
<style>
  @font-face {
    font-family: "AstroChitra";
    src: url("assets/AstroChitra.ttf") format("truetype");
    font-display: swap;
  }
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
    --font-display:"AstroChitra","Cormorant Garamond",Georgia,serif;
    --font-body:"Saira",-apple-system,sans-serif;
    --radius-md:12px;--radius-lg:20px;
    --shadow-card:0 10px 30px rgba(101,78,18,.12);
    --focus-ring:0 0 0 3px rgba(201,162,39,.35);
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  html,body{height:100%;overflow:hidden;
    font-family:var(--font-body);color:var(--ink);
    background:var(--cream) url("assets/bg-parchment.png") repeat-y top center/100% auto;
  }
  .page{display:flex;align-items:center;justify-content:center;height:100%;padding:1.25rem;}
  .card{
    background:var(--parchment);
    border:1px solid var(--line);
    border-radius:var(--radius-lg);
    box-shadow:var(--shadow-card);
    padding:2.25rem 2rem;
    width:100%;
    max-width:460px;
    text-align:center;
  }
  .logo{display:inline-flex;align-items:center;gap:.6rem;margin-bottom:.5rem;}
  .logo img{height:40px;width:auto;}
  .logo span{
    font-family:var(--font-display);
    font-size:1.5rem;
    color:var(--crimson);
    font-weight:600;
    letter-spacing:-0.1em;
  }
  h1{
    font-family:var(--font-display);
    font-size:clamp(2.5rem,4.5vw,2rem);
    color:var(--night);
    margin-bottom:.25rem;
    letter-spacing:-10%;
    font-weight: 300;
  }
  .subtitle{font-size:.95rem;color:var(--muted);margin-bottom:1.75rem;line-height:1.5;}
  .msg{margin-bottom:1rem;padding:.85rem 1rem;border-radius:8px;font-size:.9rem;font-weight:600;text-align:center;}
  .msg.error{background:#fbeee9;color:var(--crimson-dark);border:1px solid #eec9c0;}
  .msg.success{background:#f2efdc;color:var(--olive);border:1px solid #ddd3ac;}
  .form-row{display:grid;grid-template-columns:1fr;gap:1rem;}
  .form-group{display:flex;flex-direction:column;gap:.35rem;text-align:left;}
  .form-group label{
    font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--olive);
  }
  .form-group input,
  .form-group select{
    width:100%;
    padding:.3rem 1rem;
    font-size:1rem;
    font-family:var(--font-body);
    border:1.5px solid var(--line);
    border-radius:var(--radius-md);
    background:var(--cream);
    color:var(--ink);
    transition:border-color .2s,box-shadow .2s;
  }
  .form-group input:focus,
  .form-group select:focus{
    outline:none;
    border-color:var(--gold);
    box-shadow:var(--focus-ring);
  }
  .helper{font-size:.7rem;color:var(--muted);margin-bottom:.5rem;}
  .btn{
    display:inline-flex;align-items:center;justify-content:center;gap:.5rem;
    width:100%;
    padding:1rem 1.5rem;
    font-family:var(--font-body);
    font-size:1rem;
    font-weight:700;
    color:#fff;
    background:var(--crimson);
    border:2px solid var(--gold);
    border-radius:999px;
    cursor:pointer;
    box-shadow:0 8px 22px rgba(174,23,42,.3);
    transition:transform .15s ease,background .15s ease,box-shadow .15s ease;
  }
  .btn:hover{background:var(--crimson-dark);border-color:var(--gold-light);transform:translateY(-2px);box-shadow:0 12px 28px rgba(174,23,42,.4);}
  .btn:active{transform:translateY(0);}
  .btn:focus-visible{outline:none;box-shadow:var(--focus-ring),0 8px 22px rgba(174,23,42,.3);}
  .note{margin-top:1rem;font-size:.75rem;color:var(--muted);}
  .whatsapp-box{margin-top:1.5rem;padding:1rem 1.15rem;border:1.5px dashed var(--line);border-radius:14px;background:rgba(37,211,102,.07);text-align:left;}
  .whatsapp-box p{margin:0 0 .85rem;font-size:.85rem;color:var(--olive);line-height:1.5;}
  .btn.wa{background:#17935C;border-color:#117A4B;box-shadow:0 8px 22px rgba(23,147,92,.35);}
  .btn.wa:hover{background:#116B44;border-color:#0A5235;box-shadow:0 12px 28px rgba(23,147,92,.45);}
  .btn.wa:focus-visible{box-shadow:var(--focus-ring),0 8px 22px rgba(23,147,92,.35);}
  @media (min-width:560px){
    .form-row.two-col{grid-template-columns:1fr 1fr;}
  }
  @media (max-width:480px){
    .card{padding:1.75rem 1.25rem;}
  }
</style>
</head>
<body>
<div class="page">
  <div class="card" role="region" aria-label="Newsletter subscription">
    <div class="logo">
      <img src="assets/astrochitra-logo.png" alt="AstroChitra logo">
      <span>AstroChitra</span>
    </div>
    <h1>Subscribe to AstroChitra Newsletter</h1>
    <p class="subtitle">Receive planetary alignments, transits, personal remedies and wisdom directly from Guruji.</p>

    <div class="whatsapp-box">
      <p>To subscribe to our newsletter, join this community group.</p>
      <a href="https://chat.whatsapp.com/ELhfV9OyIldBs6H4mjMLW5" target="_blank" rel="noopener" class="btn wa">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-8.5 8.5 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.7a8.5 8.5 0 1 1 16.1-3.8z"/><path d="M9 10a5 5 0 0 0 5 5c.7 0 1.2-1 1.5-2"/></svg>
        Join WhatsApp Group
      </a>
    </div>

    <p class="note">No spam. Unsubscribe anytime.</p>
  </div>
</div>
</body>
</html>