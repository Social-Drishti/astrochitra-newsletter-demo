<?php
require_once __DIR__ . '/db.php';
require_admin();

// Handle clear data POST
$clearMsg = '';
$clearErr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
    if ($_POST['action'] === 'clear_range') {
        $nlId = (int)($_POST['newsletter_id'] ?? 0);
        $from = $_POST['from_datetime'] ?? null;
        $to   = $_POST['to_datetime'] ?? null;
        if ($nlId > 0 && $from && $to) {
            $deleted = ac_clear_newsletter_data($pdo, $nlId, $from, $to);
            $clearMsg = "Cleared $deleted records from $from to $to.";
        } else {
            $clearErr = 'Please select a newsletter and both date/time values.';
        }
    } elseif ($_POST['action'] === 'clear_all') {
        $nlId = (int)($_POST['newsletter_id'] ?? 0);
        if ($nlId > 0) {
            $deleted = ac_clear_newsletter_data($pdo, $nlId);
            $clearMsg = "All tracking data cleared. $deleted records deleted.";
        } else {
            $clearErr = 'Please select a newsletter.';
        }
    }
}

$totals = $pdo->query("
    SELECT COUNT(*) AS nl,
           COALESCE(SUM(views),0)          AS views,
           COALESCE(SUM(comments_count),0) AS comments,
           COALESCE(SUM(shares),0)         AS shares
    FROM newsletters
")->fetch(PDO::FETCH_ASSOC);

$evTotals = $pdo->query("
    SELECT COUNT(*)                    AS events,
           COUNT(DISTINCT session_id)  AS sessions,
           COALESCE(SUM(CASE WHEN event_type='click' THEN 1 ELSE 0 END),0)     AS clicks,
           COALESCE(SUM(CASE WHEN event_type='slide_view' THEN 1 ELSE 0 END),0) AS slide_views
    FROM events
")->fetch(PDO::FETCH_ASSOC);

$newsletters = $pdo->query('SELECT * FROM newsletters ORDER BY created_at DESC, id DESC')->fetchAll(PDO::FETCH_ASSOC);

// Resolve /year/month path for each newsletter
foreach ($newsletters as &$n) {
    $n['path'] = ac_newsletter_path($n['slug']);
}
unset($n);

$topSlides = $pdo->query("
    SELECT n.slug, n.title, e.slide_index, e.label,
           COUNT(*) AS v, COUNT(DISTINCT e.session_id) AS u
    FROM events e JOIN newsletters n ON n.id = e.newsletter_id
    WHERE e.event_type = 'slide_view'
    GROUP BY e.newsletter_id, e.slide_index, e.label
    ORDER BY v DESC LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$topClicks = $pdo->query("
    SELECT n.slug, n.title, e.label, e.target,
           COUNT(*) AS c, COUNT(DISTINCT e.session_id) AS u
    FROM events e JOIN newsletters n ON n.id = e.newsletter_id
    WHERE e.event_type = 'click'
    GROUP BY e.newsletter_id, e.label, e.target
    ORDER BY c DESC LIMIT 12
")->fetchAll(PDO::FETCH_ASSOC);

$stream = $pdo->query("
    SELECT e.*, n.title AS nl_title, n.slug
    FROM events e JOIN newsletters n ON n.id = e.newsletter_id
    ORDER BY e.id DESC LIMIT 15
")->fetchAll(PDO::FETCH_ASSOC);

$recent = $pdo->query("
    SELECT i.*, n.title AS newsletter_title, n.slug
    FROM interactions i
    JOIN newsletters n ON n.id = i.newsletter_id
    WHERE i.kind IN ('share','comment')
    ORDER BY i.created_at DESC, i.id DESC
    LIMIT 8
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Newsletter Dashboard | AstroChitra</title>
<style>
  :root{--cream:#faf6ee;--paper:#fffdf8;--ink:#33270e;--muted:#7a6a4f;--gold:#c9a227;--gold-light:#f0d98a;
        --crimson:#ae172a;--crimson-dark:#7d0f1d;--night:#2b1005;--cocoa:#471d0b;--line:#e4d9c3;--olive:#654e12;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:Georgia,'Times New Roman',serif;background:var(--cream);color:var(--ink);line-height:1.55;}
  a{text-decoration:none;color:inherit;}
  header.topbar{background:var(--night);color:var(--cream);border-bottom:3px solid var(--gold);padding:16px 24px;
                display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;}
  .brand{display:flex;align-items:center;gap:10px;font-weight:bold;letter-spacing:.08em;text-transform:uppercase;font-size:.95rem;color:var(--gold-light);}
  .topbar nav{display:flex;gap:10px;flex-wrap:wrap;}
  .btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:999px;font-weight:bold;font-size:.85rem;border:1.5px solid var(--gold);cursor:pointer;}
  .btn-gold{background:var(--gold);color:var(--night);}
  .btn-ghost{background:transparent;color:var(--gold-light);}
  .btn-ghost:hover{background:rgba(240,217,138,.15);}
  main{max-width:1150px;margin:0 auto;padding:30px 20px 60px;}
  h1{font-size:clamp(1.4rem,4vw,2rem);color:var(--night);margin-bottom:6px;}
  .sub{color:var(--muted);font-size:.92rem;margin-bottom:26px;}
  .stats{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:34px;}
  @media(min-width:900px){.stats{grid-template-columns:repeat(6,1fr);}}
  @media(min-width:640px) and (max-width:899px){.stats{grid-template-columns:repeat(3,1fr);}}
  .stat{background:var(--paper);border:1px solid var(--line);border-top:3px solid var(--gold);border-radius:14px;padding:16px 18px;}
  .stat .num{font-size:1.7rem;font-weight:bold;color:var(--crimson);line-height:1.15;}
  .stat .lbl{font-size:.66rem;font-weight:bold;letter-spacing:.13em;text-transform:uppercase;color:var(--muted);margin-top:4px;}
  h2.sec{font-size:1.02rem;letter-spacing:.1em;text-transform:uppercase;color:var(--cocoa);
         border-bottom:2px solid var(--gold);padding-bottom:8px;margin:36px 0 16px;}
  table{width:100%;border-collapse:collapse;background:var(--paper);border:1px solid var(--line);border-radius:12px;overflow:hidden;}
  th{background:var(--cocoa);color:#f3e3cf;font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;text-align:left;padding:11px 14px;}
  td{padding:11px 14px;border-top:1px solid var(--line);font-size:.9rem;vertical-align:middle;}
  tr:hover td{background:#fbf7ec;}
  .num-cell{font-weight:bold;color:var(--crimson);}
  .badge{display:inline-block;font-size:.64rem;font-weight:bold;letter-spacing:.08em;text-transform:uppercase;border-radius:999px;padding:3px 9px;white-space:nowrap;}
  .b-live{color:var(--olive);background:#f2efdc;border:1px solid #ddd3ac;}
  .b-draft{color:var(--muted);background:var(--cream);border:1px solid var(--line);}
  .b-share{color:var(--crimson);background:#fbeee9;border:1px solid #eec9c0;}
  .b-comment{color:var(--olive);background:#f2efdc;border:1px solid #ddd3ac;}
  .b-click{color:var(--terracotta,#8b4513);background:#f7efe4;border:1px solid #e4d0b8;}
  .b-slide{color:#1c5a7a;background:#e8f2f7;border:1px solid #bcd9e8;}
  .bar-row{display:flex;align-items:center;gap:10px;}
  .bar{flex:1;height:8px;background:var(--cream);border:1px solid var(--line);border-radius:99px;overflow:hidden;min-width:60px;}
  .bar span{display:block;height:100%;background:linear-gradient(90deg,var(--gold),var(--gold-light));}
  .actions{display:flex;gap:10px;flex-wrap:wrap;}
  .link{color:var(--crimson);font-weight:bold;font-size:.84rem;border-bottom:1px dashed var(--crimson);}
  .link:hover{color:var(--crimson-dark);}
  .feed{list-style:none;display:flex;flex-direction:column;gap:9px;}
  .feed li{background:var(--paper);border:1px solid var(--line);border-left:4px solid var(--gold);border-radius:10px;
           padding:11px 15px;font-size:.86rem;display:flex;flex-wrap:wrap;gap:5px 12px;align-items:baseline;}
  .feed .who{font-weight:bold;color:var(--night);}
  .feed .msg{flex-basis:100%;color:var(--muted);font-style:italic;}
  .feed .when,.feed .nl{color:var(--muted);font-size:.76rem;}
  .empty{background:var(--paper);border:1px dashed var(--line);border-radius:12px;padding:22px;text-align:center;color:var(--muted);font-size:.9rem;}
  .two-col{display:grid;grid-template-columns:1fr;gap:30px;}
  @media(min-width:980px){.two-col{grid-template-columns:1fr 1fr;align-items:start;}}
</style>
</head>
<body>
<header class="topbar">
  <span class="brand">AstroChitra &middot; Newsletter Admin</span>
  <nav>
    <?php
      $latestNl = $newsletters[0] ?? null;
      $latestPath = $latestNl && $latestNl['path'] ? $latestNl['path'] : '#';
    ?>
    <a class="btn btn-ghost" href="<?= e($latestPath) ?>" target="_blank" rel="noopener">View Latest Issue</a>
    <a class="btn btn-ghost" href="subscribers.php">Subscribers</a>
    <a class="btn btn-gold" href="logout.php">Logout</a>
  </nav>
</header>

<main>
  <h1>Dashboard</h1>
  <p class="sub">Performance and engagement across every published issue.</p>

  <div class="stats">
    <div class="stat"><div class="num"><?= number_format((int)$totals['nl']) ?></div><div class="lbl">Newsletters</div></div>
    <div class="stat"><div class="num"><?= number_format((int)$totals['views']) ?></div><div class="lbl">Page Views</div></div>
    <div class="stat"><div class="num"><?= number_format((int)$evTotals['sessions']) ?></div><div class="lbl">Tracked Sessions</div></div>
    <div class="stat"><div class="num"><?= number_format((int)$evTotals['slide_views']) ?></div><div class="lbl">Slide Views</div></div>
    <div class="stat"><div class="num"><?= number_format((int)$evTotals['clicks']) ?></div><div class="lbl">Button Clicks</div></div>
    <div class="stat"><div class="num"><?= number_format((int)$totals['comments'] + 0) ?>/<?= number_format((int)$totals['shares'] + 0) ?></div><div class="lbl">Comments / Shares</div></div>
  </div>

  <h2 class="sec">All Newsletters</h2>
  <?php if ($newsletters): ?>
  <table>
    <thead>
      <tr><th>Issue</th><th>Status</th><th>Views</th><th>Comments</th><th>Shares</th><th>Created</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($newsletters as $n): ?>
      <tr>
        <td>
          <strong><?= e($n['title']) ?></strong><br>
          <span style="color:var(--muted);font-size:.78rem;"><?= $n['path'] ? e($n['path']) : '/' . e($n['slug']) ?></span>
        </td>
        <td><span class="badge <?= $n['published'] ? 'b-live' : 'b-draft' ?>"><?= $n['published'] ? 'Live' : 'Draft' ?></span></td>
        <td class="num-cell"><?= number_format((int)$n['views']) ?></td>
        <td class="num-cell"><?= number_format((int)$n['comments_count']) ?></td>
        <td class="num-cell"><?= number_format((int)$n['shares']) ?></td>
        <td style="color:var(--muted);font-size:.82rem;"><?= e(substr($n['created_at'], 0, 10)) ?></td>
        <td>
          <div class="actions">
            <a class="link" href="newsletter.php?id=<?= (int)$n['id'] ?>">Details</a>
            <?php if ($n['path']): ?>
            <a class="link" href="<?= e($n['path']) ?>" target="_blank" rel="noopener">Open</a>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="empty">No newsletters yet.</p>
  <?php endif; ?>

  <h2 class="sec">Clear Tracking Data</h2>
  <?php if ($clearMsg): ?><div style="background:#f2efdc;border:1px solid #ddd3ac;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:var(--olive);font-size:.9rem;"><?= e($clearMsg) ?></div><?php endif; ?>
  <?php if ($clearErr): ?><div style="background:#fbeee9;border:1px solid #eec9c0;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:var(--crimson);font-size:.9rem;"><?= e($clearErr) ?></div><?php endif; ?>

  <div class="two-col" style="gap:20px;">
    <div style="background:var(--paper);border:1px solid var(--line);border-radius:14px;padding:20px;">
      <h3 style="font-size:.92rem;color:var(--cocoa);margin-bottom:12px;">Clear Views by Date Range</h3>
      <form method="POST" style="display:flex;flex-direction:column;gap:10px;max-width:600px;">
        <input type="hidden" name="action" value="clear_range">
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
          <label style="font-size:.82rem;color:var(--muted);">
            Newsletter
            <select name="newsletter_id" required style="display:block;margin-top:4px;padding:8px 12px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.88rem;background:var(--cream);width:100%;">
              <?php foreach ($newsletters as $n): ?>
              <option value="<?= (int)$n['id'] ?>"><?= e($n['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;">
          <label style="font-size:.82rem;color:var(--muted);flex:1;min-width:180px;">
            From
            <input type="datetime-local" name="from_datetime" required style="display:block;margin-top:4px;padding:8px 12px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.88rem;width:100%;">
          </label>
          <label style="font-size:.82rem;color:var(--muted);flex:1;min-width:180px;">
            To
            <input type="datetime-local" name="to_datetime" required style="display:block;margin-top:4px;padding:8px 12px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.88rem;width:100%;">
          </label>
        </div>
        <div>
          <button type="submit" onclick="return confirm('This will permanently delete tracking data in the selected date range. Continue?')" class="btn btn-gold" style="font-size:.82rem;">Clear Range</button>
        </div>
      </form>
    </div>

    <div style="background:var(--paper);border:1px solid var(--line);border-radius:14px;padding:20px;">
      <h3 style="font-size:.92rem;color:var(--cocoa);margin-bottom:12px;">Clear All View Data</h3>
      <form method="POST" style="display:flex;flex-direction:column;gap:10px;max-width:600px;">
        <input type="hidden" name="action" value="clear_all">
        <label style="font-size:.82rem;color:var(--muted);">
          Newsletter
          <select name="newsletter_id" required style="display:block;margin-top:4px;padding:8px 12px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.88rem;background:var(--cream);width:100%;">
            <?php foreach ($newsletters as $n): ?>
            <option value="<?= (int)$n['id'] ?>"><?= e($n['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <div>
          <button type="submit" onclick="return confirm('This will permanently delete ALL tracking data (events, views, shares, comments) for this newsletter. Continue?')" class="btn" style="background:var(--crimson);color:#fff;border-color:var(--crimson);font-size:.82rem;">Clear All Data</button>
        </div>
      </form>
    </div>
  </div>

  <h2 class="sec">Slide Performance &mdash; Top Across All Issues</h2>
  <?php if ($topSlides): ?>
  <table>
    <thead><tr><th>#</th><th>Slide</th><th>Issue</th><th>Unique Sessions</th><th>Total Views</th><th></th></tr></thead>
    <tbody>
      <?php $maxV = max(array_column($topSlides, 'v')) ?: 1; foreach ($topSlides as $s): ?>
      <tr>
        <td class="num-cell"><?= (int)$s['slide_index'] + 1 ?></td>
        <td><strong><?= e($s['label']) ?></strong></td>
        <td style="font-size:.82rem;color:var(--muted);"><?= e($s['title']) ?></td>
        <td><?= number_format((int)$s['u']) ?></td>
        <td class="num-cell"><?= number_format((int)$s['v']) ?></td>
        <td style="width:28%;"><div class="bar-row"><div class="bar"><span style="width:<?= round($s['v'] / $maxV * 100) ?>%"></span></div></div></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="empty">No slide views recorded yet &mdash; open a newsletter issue to start tracking.</p>
  <?php endif; ?>

  <h2 class="sec">Top Buttons &amp; Links</h2>
  <?php if ($topClicks): ?>
  <table>
    <thead><tr><th>Element</th><th>Target</th><th>Issue</th><th>Sessions</th><th>Clicks</th></tr></thead>
    <tbody>
      <?php foreach ($topClicks as $c): ?>
      <tr>
        <td><strong><?= e($c['label']) ?></strong></td>
        <td style="font-size:.8rem;color:var(--muted);word-break:break-all;"><?= e($c['target'] ?: '&mdash;') ?></td>
        <td style="font-size:.82rem;color:var(--muted);"><?= e($c['title']) ?></td>
        <td><?= number_format((int)$c['u']) ?></td>
        <td class="num-cell"><?= number_format((int)$c['c']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="empty">No button or link clicks recorded yet.</p>
  <?php endif; ?>

  <div class="two-col">
    <div>
      <h2 class="sec">Live Event Stream</h2>
      <?php if ($stream): ?>
      <ul class="feed">
        <?php foreach ($stream as $r): ?>
        <li>
          <span class="badge <?= $r['event_type'] === 'click' ? 'b-click' : 'b-slide' ?>">
            <?= $r['event_type'] === 'click' ? 'Click' : 'Slide View' ?>
          </span>
          <span class="who"><?= e($r['label'] ?: '(unlabelled)') ?></span>
          <span class="nl">&middot; <?= e($r['nl_title']) ?></span>
          <?php if ($r['slide_index'] !== null): ?><span class="when">&middot; slide <?= (int)$r['slide_index'] + 1 ?></span><?php endif; ?>
          <span class="when" style="margin-left:auto;"><?= e($r['created_at']) ?></span>
          <?php if (!empty($r['target'])): ?><span class="when" style="flex-basis:100%;word-break:break-all;">&rarr; <?= e($r['target']) ?></span><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
        <p class="empty">No events captured yet.</p>
      <?php endif; ?>
    </div>

    <div>
      <h2 class="sec">Recent Shares &amp; Comments</h2>
      <?php if ($recent): ?>
      <ul class="feed">
        <?php foreach ($recent as $r): ?>
        <li>
          <span class="badge <?= $r['kind'] === 'comment' ? 'b-comment' : 'b-share' ?>"><?= e($r['kind']) ?></span>
          <span class="who"><?= e($r['email'] ?: 'Anonymous') ?></span>
          <?php if (!empty($r['phone'])): ?><span class="when">&middot; <?= e($r['phone']) ?></span><?php endif; ?>
          <span class="nl">&middot; <?= e($r['newsletter_title']) ?></span>
          <span class="when" style="margin-left:auto;"><?= e($r['created_at']) ?></span>
          <?php if (!empty($r['message'])): ?><span class="msg">&ldquo;<?= e($r['message']) ?>&rdquo;</span><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
        <p class="empty">No shares or comments yet.</p>
      <?php endif; ?>
    </div>
  </div>
</main>
</body>
</html>
