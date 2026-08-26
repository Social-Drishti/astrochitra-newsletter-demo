<?php
require_once __DIR__ . '/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM newsletters WHERE id = ?');
$stmt->execute([$id]);
$nl = $stmt->fetch(PDO::FETCH_ASSOC);

$notFound = !$nl;
$rows = [];
$slideRows = [];
$clickRows = [];
$nlPath = null;
$clearMsg = '';
$clearErr = '';

if (!$notFound) {
    $nlPath = ac_newsletter_path($nl['slug']);

    // Handle clear data POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['action'])) {
        if ($_POST['action'] === 'clear_range') {
            $from = $_POST['from_datetime'] ?? null;
            $to   = $_POST['to_datetime'] ?? null;
            if ($from && $to) {
                $deleted = ac_clear_newsletter_data($pdo, $id, $from, $to);
                $clearMsg = "Cleared $deleted records from $from to $to.";
                // Refresh data
                $stmt->execute([$id]);
                $nl = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $clearErr = 'Please select both date/time values.';
            }
        } elseif ($_POST['action'] === 'clear_all') {
            $deleted = ac_clear_newsletter_data($pdo, $id);
            $clearMsg = "All tracking data cleared. $deleted records deleted.";
            $stmt->execute([$id]);
            $nl = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    $log = $pdo->prepare('SELECT * FROM interactions WHERE newsletter_id = ? ORDER BY created_at DESC, id DESC LIMIT 200');
    $log->execute([$id]);
    $rows = $log->fetchAll(PDO::FETCH_ASSOC);

    $slides = $pdo->prepare("
        SELECT COALESCE(NULLIF(e.label,''), 'Slide ' || (e.slide_index + 1)) AS label,
               e.slide_index,
               COUNT(*) AS v,
               COUNT(DISTINCT e.session_id) AS u
        FROM events e
        WHERE e.newsletter_id = ? AND e.event_type = 'slide_view'
        GROUP BY e.slide_index, e.label
        ORDER BY e.slide_index
    ");
    $slides->execute([$id]);
    $slideRows = $slides->fetchAll(PDO::FETCH_ASSOC);

    $clicks = $pdo->prepare("
        SELECT COALESCE(NULLIF(e.label,''), '(unlabelled)') AS label,
               e.target,
               COUNT(*) AS c,
               COUNT(DISTINCT e.session_id) AS u
        FROM events e
        WHERE e.newsletter_id = ? AND e.event_type = 'click'
        GROUP BY e.label, e.target
        ORDER BY c DESC
        LIMIT 50
    ");
    $clicks->execute([$id]);
    $clickRows = $clicks->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $notFound ? 'Not found' : e($nl['title']) ?> | AstroChitra Admin</title>
<style>
  :root{--cream:#faf6ee;--paper:#fffdf8;--ink:#33270e;--muted:#7a6a4f;--gold:#c9a227;--gold-light:#f0d98a;
        --crimson:#ae172a;--night:#2b1005;--cocoa:#471d0b;--line:#e4d9c3;--olive:#654e12;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:Georgia,'Times New Roman',serif;background:var(--cream);color:var(--ink);line-height:1.55;}
  a{text-decoration:none;color:inherit;}
  header.topbar{background:var(--night);color:var(--cream);border-bottom:3px solid var(--gold);padding:16px 24px;
                display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;}
  .brand{color:var(--gold-light);font-weight:bold;letter-spacing:.08em;text-transform:uppercase;font-size:.95rem;}
  .btn{display:inline-flex;align-items:center;padding:9px 18px;border-radius:999px;font-weight:bold;font-size:.85rem;
       border:1.5px solid var(--gold);cursor:pointer;background:var(--gold);color:var(--night);}
  main{max-width:1150px;margin:0 auto;padding:30px 20px 60px;}
  h1{font-size:clamp(1.3rem,3.6vw,1.8rem);color:var(--night);}
  .sub{color:var(--muted);font-size:.9rem;margin-bottom:24px;}
  .stats{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:30px;}
  @media(min-width:760px){.stats{grid-template-columns:repeat(4,1fr);}}
  .stat{background:var(--paper);border:1px solid var(--line);border-top:3px solid var(--gold);border-radius:14px;padding:15px 19px;}
  .stat .num{font-size:1.7rem;font-weight:bold;color:var(--crimson);}
  .stat .lbl{font-size:.66rem;font-weight:bold;letter-spacing:.13em;text-transform:uppercase;color:var(--muted);margin-top:2px;}
  h2.sec{font-size:.98rem;letter-spacing:.1em;text-transform:uppercase;color:var(--cocoa);border-bottom:2px solid var(--gold);
         padding-bottom:8px;margin:28px 0 14px;}
  table{width:100%;border-collapse:collapse;background:var(--paper);border:1px solid var(--line);border-radius:12px;overflow:hidden;}
  th{background:var(--cocoa);color:#f3e3cf;font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;text-align:left;padding:10px 14px;}
  td{padding:10px 14px;border-top:1px solid var(--line);font-size:.87rem;}
  tr:hover td{background:#fbf7ec;}
  .badge{display:inline-block;font-size:.64rem;font-weight:bold;letter-spacing:.08em;text-transform:uppercase;border-radius:999px;padding:3px 9px;}
  .b-view{color:var(--muted);background:var(--cream);border:1px solid var(--line);}
  .b-share{color:var(--crimson);background:#fbeee9;border:1px solid #eec9c0;}
  .b-comment{color:var(--olive);background:#f2efdc;border:1px solid #ddd3ac;}
  .bar-row{display:flex;align-items:center;gap:10px;}
  .bar{flex:1;height:8px;background:var(--cream);border:1px solid var(--line);border-radius:99px;overflow:hidden;min-width:60px;}
  .bar span{display:block;height:100%;background:linear-gradient(90deg,var(--gold),var(--gold-light));}
  .empty{background:var(--paper);border:1px dashed var(--line);border-radius:12px;padding:20px;text-align:center;color:var(--muted);}
  .tgt{font-size:.78rem;color:var(--muted);word-break:break-all;}
</style>
</head>
<body>
<header class="topbar">
  <span class="brand">AstroChitra &middot; Issue Details</span>
  <a class="btn" href="dashboard.php">Back to Dashboard</a>
</header>
<main>
<?php if ($notFound): ?>
  <h1>Newsletter not found</h1>
  <p class="sub">The requested issue does not exist.</p>
<?php else: ?>
  <h1><?= e($nl['title']) ?></h1>
  <p class="sub">Path: <?= $nlPath ? e($nlPath) : '/' . e($nl['slug']) ?> &middot; Registered <?= e($nl['created_at']) ?></p>

  <?php
    $evStmt = $pdo->prepare("SELECT COUNT(*) c, COUNT(DISTINCT session_id) s FROM events WHERE newsletter_id = ?");
    $evStmt->execute([$id]);
    $ev = $evStmt->fetch(PDO::FETCH_ASSOC);
    $clStmt = $pdo->prepare("SELECT COUNT(*) c FROM events WHERE newsletter_id = ? AND event_type='click'");
    $clStmt->execute([$id]);
    $clickTotal = (int)$clStmt->fetchColumn();
    $svTotal = max(0, (int)$ev['c'] - $clickTotal);
  ?>

  <div class="stats">
    <div class="stat"><div class="num"><?= number_format((int)$nl['views']) ?></div><div class="lbl">Page Views</div></div>
    <div class="stat"><div class="num"><?= number_format($svTotal) ?></div><div class="lbl">Slide Views</div></div>
    <div class="stat"><div class="num"><?= number_format($clickTotal) ?></div><div class="lbl">Button Clicks</div></div>
    <div class="stat"><div class="num"><?= number_format((int)$ev['s']) ?></div><div class="lbl">Tracked Sessions</div></div>
  </div>

  <?php if ($clearMsg): ?><div style="background:#f2efdc;border:1px solid #ddd3ac;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:var(--olive);font-size:.9rem;"><?= e($clearMsg) ?></div><?php endif; ?>
  <?php if ($clearErr): ?><div style="background:#fbeee9;border:1px solid #eec9c0;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:var(--crimson);font-size:.9rem;"><?= e($clearErr) ?></div><?php endif; ?>

  <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:24px;">
    <div style="background:var(--paper);border:1px solid var(--line);border-radius:12px;padding:16px 20px;flex:1;min-width:280px;">
      <h3 style="font-size:.88rem;color:var(--cocoa);margin-bottom:10px;">Clear Views by Date Range</h3>
      <form method="POST" style="display:flex;flex-direction:column;gap:8px;">
        <input type="hidden" name="action" value="clear_range">
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <label style="font-size:.8rem;color:var(--muted);flex:1;min-width:160px;">
            From
            <input type="datetime-local" name="from_datetime" required style="display:block;margin-top:3px;padding:7px 10px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.85rem;width:100%;">
          </label>
          <label style="font-size:.8rem;color:var(--muted);flex:1;min-width:160px;">
            To
            <input type="datetime-local" name="to_datetime" required style="display:block;margin-top:3px;padding:7px 10px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.85rem;width:100%;">
          </label>
        </div>
        <button type="submit" onclick="return confirm('Delete tracking data in this range?')" class="btn" style="align-self:start;font-size:.8rem;">Clear Range</button>
      </form>
    </div>
    <div style="background:var(--paper);border:1px solid var(--line);border-radius:12px;padding:16px 20px;">
      <h3 style="font-size:.88rem;color:var(--cocoa);margin-bottom:10px;">Clear All Data</h3>
      <form method="POST" style="display:flex;flex-direction:column;gap:8px;">
        <input type="hidden" name="action" value="clear_all">
        <button type="submit" onclick="return confirm('Delete ALL tracking data for this newsletter? This cannot be undone.')" class="btn" style="background:var(--crimson);color:#fff;border-color:var(--crimson);font-size:.8rem;">Clear All Data</button>
      </form>
    </div>
  </div>

  <h2 class="sec">Views Per Slide</h2>
  <?php if ($slideRows): ?>
    <?php $maxV = max(array_column($slideRows, 'v')) ?: 1; ?>
  <table>
    <thead><tr><th>#</th><th>Slide</th><th>Unique Sessions</th><th>Total Views</th><th style="width:32%;"></th></tr></thead>
    <tbody>
      <?php foreach ($slideRows as $sr): ?>
      <tr>
        <td class="num-cell" style="width:36px;"><?= $sr['slide_index'] !== null ? (int)$sr['slide_index'] + 1 : '&mdash;' ?></td>
        <td><strong><?= e($sr['label']) ?></strong></td>
        <td><?= number_format((int)$sr['u']) ?></td>
        <td class="num-cell"><?= number_format((int)$sr['v']) ?></td>
        <td><div class="bar-row"><div class="bar"><span style="width:<?= round($sr['v'] / $maxV * 100) ?>%"></span></div></div></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="empty">No slide views recorded for this issue yet.</p>
  <?php endif; ?>

  <h2 class="sec">Buttons &amp; Links Clicked</h2>
  <?php if ($clickRows): ?>
  <table>
    <thead><tr><th>Element</th><th>Target</th><th>Sessions</th><th>Clicks</th></tr></thead>
    <tbody>
      <?php foreach ($clickRows as $cr): ?>
      <tr>
        <td><strong><?= e($cr['label']) ?></strong></td>
        <td class="tgt"><?= e($cr['target'] ?: '&mdash;') ?></td>
        <td><?= number_format((int)$cr['u']) ?></td>
        <td class="num-cell"><?= number_format((int)$cr['c']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="empty">No clicks recorded for this issue yet.</p>
  <?php endif; ?>

  <h2 class="sec">Shares &amp; Comments Log</h2>
  <?php if ($rows): ?>
  <table>
    <thead><tr><th>When</th><th>Type</th><th>Email</th><th>Phone</th><th>Message</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td style="white-space:nowrap;color:var(--muted);font-size:.8rem;"><?= e($r['created_at']) ?></td>
        <td><span class="badge b-<?= e($r['kind']) ?>" style="<?= $r['kind']==='view'?'':'' ?>"><?= e($r['kind']) ?></span></td>
        <td><?= e($r['email'] ?: '') ?></td>
        <td><?= e($r['phone'] ?: '') ?></td>
        <td style="font-style:italic;color:var(--muted);"><?= e($r['message'] ?: '') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="empty">No shares or comments yet.</p>
  <?php endif; ?>
<?php endif; ?>
</main>
</body>
</html>
