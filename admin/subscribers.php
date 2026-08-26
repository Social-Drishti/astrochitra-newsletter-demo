<?php
require_once __DIR__ . '/db.php';
require_admin();

$msg = '';
$err = '';

// ─── Handle POST actions ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $phone  = trim($_POST['phone'] ?? '');
        $rashi  = trim($_POST['rashi'] ?? '');
        $source = trim($_POST['source'] ?? '');
        if ($name === '') {
            $err = 'Name is required.';
        } elseif ($email === '' && $phone === '') {
            $err = 'Provide at least an email or WhatsApp number.';
        } else {
            $r = ac_add_subscriber($pdo, $name, $email, $phone, $rashi, $source);
            $r['status'] === 'success' ? $msg = $r['message'] : $err = $r['message'];
        }
    }

    if ($action === 'import') {
        if (empty($_FILES['csv_file']['tmp_name'])) {
            $err = 'No file uploaded.';
        } else {
            $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
            if (!$handle) { $err = 'Cannot read uploaded file.'; }
            else {
                $header = fgetcsv($handle);
                if (!$header) { $err = 'CSV file is empty.'; fclose($handle); }
                else {
                    // Normalise header
                    $header = array_map('strtolower', array_map('trim', $header));
                    $nameIdx   = array_search('name', $header);
                    $emailIdx  = array_search('email', $header);
                    $phoneIdx  = array_search('phone', $header);
                    $whatsappIdx = array_search('whatsapp', $header);
                    $rashiIdx  = array_search('rashi', $header);
                    $sourceIdx = array_search('source', $header);
                    if ($phoneIdx === false && $whatsappIdx !== false) $phoneIdx = $whatsappIdx;

                    if ($nameIdx === false) {
                        $err = 'CSV must have a "name" column.';
                        fclose($handle);
                    } else {
                        $added = 0; $skipped = 0; $dupes = 0;
                        while (($row = fgetcsv($handle)) !== false) {
                            $name   = trim($row[$nameIdx] ?? '');
                            $email  = trim($row[$emailIdx] ?? '');
                            $phone  = trim($row[$phoneIdx] ?? '');
                            $rashi  = trim($row[$rashiIdx] ?? '');
                            $source = ($sourceIdx !== false) ? trim($row[$sourceIdx] ?? '') : '';
                            if ($name === '') { $skipped++; continue; }
                            if ($email === '' && $phone === '') { $skipped++; continue; }

                            $r = ac_add_subscriber($pdo, $name, $email, $phone, $rashi, $source);
                            if ($r['status'] === 'success') $added++;
                            else $dupes++;
                        }
                        fclose($handle);
                        $msg = "Import complete: $added added, $dupes duplicates, $skipped skipped.";
                    }
                }
            }
        }
    }

    if ($action === 'delete_selected') {
        $ids = $_POST['sub_ids'] ?? [];
        $ids = array_map('intval', $ids);
        $deleted = ac_delete_subscribers($pdo, $ids);
        $msg = "Deleted $deleted subscriber(s).";
    }
}

// ─── Handle export / template actions ────────────────────────────────────
if (isset($_GET['action'])) {
    $ea = $_GET['action'];

    if ($ea === 'download_template') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="subscriber-import-template.csv"');
        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['name', 'email', 'phone', 'rashi', 'source']);
        fputcsv($fp, ['Rahul Sharma', 'rahul@example.com', '', 'Mesha', 'Instagram']);
        fputcsv($fp, ['Priya Patel', '', '+919876543210', 'Vrishabha', 'Referral']);
        fputcsv($fp, ['Amit Kumar', 'amit@test.com', '+919999999999', 'Mithuna', 'Google']);
        fclose($fp);
        exit;
    }

    if ($ea === 'export') {
        $exportType   = $_GET['type'] ?? '';
        $exportFormat = $_GET['format'] ?? '';
        $exportFrom   = $_GET['from'] ?? null;
        $exportTo     = $_GET['to'] ?? null;
        $exportIds    = $_GET['ids'] ?? '';

        $idFilter = null;
        if ($exportIds !== '') {
            $idFilter = array_filter(array_map('intval', explode(',', $exportIds)));
        }

        $subs = ac_get_subscribers($pdo, $exportType, '', 'name', 'ASC', $exportFrom, $exportTo, 10000);
        if ($idFilter !== null) {
            $subs = array_values(array_filter($subs, function($s) use ($idFilter) {
                return in_array((int)$s['id'], $idFilter, true);
            }));
        }

        if ($exportFormat === 'json' && $exportType === 'email') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="subscribers-email.json"');
            $out = [];
            foreach ($subs as $s) {
                $out[] = ['name' => $s['name'], 'email' => $s['email'], 'rashi' => $s['rashi'] ?? '', 'source' => $s['source'] ?? '', 'subscribed_at' => $s['subscribed_at']];
            }
            echo json_encode($out, JSON_PRETTY_PRINT);
            exit;
        }

        if ($exportFormat === 'csv' && $exportType === 'email') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="subscribers-email.csv"');
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['name', 'email', 'rashi', 'source', 'subscribed_at']);
            foreach ($subs as $s) {
                fputcsv($fp, [$s['name'], $s['email'], $s['rashi'] ?? '', $s['source'] ?? '', $s['subscribed_at']]);
            }
            fclose($fp);
            exit;
        }

        if ($exportFormat === 'csv' && $exportType === 'whatsapp') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="subscribers-whatsapp.csv"');
            $fp = fopen('php://output', 'w');
            fputcsv($fp, ['name', 'phone', 'rashi', 'source', 'subscribed_at']);
            foreach ($subs as $s) {
                fputcsv($fp, [$s['name'], $s['phone'], $s['rashi'] ?? '', $s['source'] ?? '', $s['subscribed_at']]);
            }
            fclose($fp);
            exit;
        }

        if ($exportFormat === 'vcf' && $exportType === 'whatsapp') {
            $batchId = date('Ymd-His');
            header('Content-Type: text/vcard; charset=utf-8');
            header('Content-Disposition: attachment; filename="subscribers-whatsapp-batch' . $batchId . '.vcf"');
            foreach ($subs as $idx => $s) {
                $safeName = 'newsletter-' . $batchId . '-' . ($idx + 1) . '-' . preg_replace('/[^a-zA-Z0-9]/', '-', $s['name']);
                $phone = preg_replace('/[^0-9+]/', '', $s['phone']);
                echo "BEGIN:VCARD\r\n";
                echo "VERSION:3.0\r\n";
                echo "FN:" . $safeName . "\r\n";
                echo "TEL;TYPE=CELL:" . $phone . "\r\n";
                echo "END:VCARD\r\n";
            }
            exit;
        }
    }
}

// ─── Read current filters ────────────────────────────────────────────────
$filterType   = $_GET['filter'] ?? '';
$filterSearch = $_GET['search'] ?? '';
$sortBy       = $_GET['sort'] ?? 'subscribed_at';
$sortDir      = $_GET['dir'] ?? 'DESC';
$dateFrom     = $_GET['from'] ?? '';
$dateTo       = $_GET['to'] ?? '';
$filterSource = $_GET['source'] ?? '';

// Get distinct sources for filter dropdown
$allSources = $pdo->query("SELECT DISTINCT source FROM subscribers WHERE source != '' ORDER BY source")->fetchAll(PDO::FETCH_COLUMN);

$subs  = ac_get_subscribers($pdo, $filterType, $filterSearch, $sortBy, $sortDir, $dateFrom ?: null, $dateTo ?: null, 500, $filterSource);
$totalEmail = ac_count_subscribers($pdo, 'email');
$totalWa    = ac_count_subscribers($pdo, 'whatsapp');
$totalAll   = ac_count_subscribers($pdo);

function sortLink(string $label, string $field, string $currentSort, string $currentDir, array $extra = []): string {
    $newDir = ($currentSort === $field && $currentDir === 'ASC') ? 'DESC' : 'ASC';
    $params = array_merge($extra, ['sort' => $field, 'dir' => $newDir]);
    $qs = http_build_query($params);
    $arrow = '';
    if ($currentSort === $field) {
        $arrow = $currentDir === 'ASC' ? ' &#9650;' : ' &#9660;';
    }
    return '<a href="?'.$qs.'" style="color:inherit;text-decoration:none;">'.$label.$arrow.'</a>';
}

$filterQs = http_build_query(array_filter([
    'filter' => $filterType,
    'search' => $filterSearch,
    'from'   => $dateFrom,
    'to'     => $dateTo,
    'source' => $filterSource,
    'sort'   => $sortBy,
    'dir'    => $sortDir,
]));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subscribers | AstroChitra Admin</title>
<style>
  :root{--cream:#faf6ee;--paper:#fffdf8;--ink:#33270e;--muted:#7a6a4f;--gold:#c9a227;--gold-light:#f0d98a;
        --crimson:#ae172a;--crimson-dark:#7d0f1d;--night:#2b1005;--cocoa:#471d0b;--line:#e4d9c3;--olive:#654e12;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:Georgia,'Times New Roman',serif;background:var(--cream);color:var(--ink);line-height:1.55;}
  a{text-decoration:none;color:inherit;}
  header.topbar{background:var(--night);color:var(--cream);border-bottom:3px solid var(--gold);padding:16px 24px;
                display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;}
  .brand{color:var(--gold-light);font-weight:bold;letter-spacing:.08em;text-transform:uppercase;font-size:.95rem;}
  .topbar nav{display:flex;gap:10px;flex-wrap:wrap;}
  .btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:999px;font-weight:bold;font-size:.85rem;border:1.5px solid var(--gold);cursor:pointer;text-decoration:none;}
  .btn-gold{background:var(--gold);color:var(--night);}
  .btn-ghost{background:transparent;color:var(--gold-light);}
  .btn-ghost:hover{background:rgba(240,217,138,.15);}
  .btn-sm{padding:6px 14px;font-size:.78rem;}
  .btn-danger{background:var(--crimson);color:#fff;border-color:var(--crimson);}
  .btn-danger:hover{background:var(--crimson-dark);}
  main{max-width:1200px;margin:0 auto;padding:30px 20px 60px;}
  h1{font-size:clamp(1.4rem,4vw,2rem);color:var(--night);margin-bottom:6px;}
  .sub{color:var(--muted);font-size:.92rem;margin-bottom:26px;}
  .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:30px;}
  @media(min-width:700px){.stats{grid-template-columns:repeat(3,1fr);}}
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
  .b-email{color:#1c5a7a;background:#e8f2f7;border:1px solid #bcd9e8;}
  .b-wa{color:#128c7e;background:#e6f9f1;border:1px solid #b2e0d3;}
  .empty{background:var(--paper);border:1px dashed var(--line);border-radius:12px;padding:22px;text-align:center;color:var(--muted);font-size:.9rem;}
  .card{background:var(--paper);border:1px solid var(--line);border-radius:14px;padding:20px;margin-bottom:20px;}
  .card h3{font-size:.92rem;color:var(--cocoa);margin-bottom:12px;}
  .form-row{display:flex;gap:10px;flex-wrap:wrap;align-items:end;}
  .form-group{display:flex;flex-direction:column;gap:3px;flex:1;min-width:140px;}
  .form-group label{font-size:.78rem;color:var(--muted);font-weight:bold;letter-spacing:.05em;}
  .form-group input,.form-group select{padding:8px 12px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:.88rem;background:var(--cream);}
  .toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:14px;}
  .toolbar form{display:flex;gap:8px;flex-wrap:wrap;align-items:end;}
  .chk{width:16px;height:16px;accent-color:var(--gold);}
  .pill{display:inline-flex;align-items:center;gap:4px;padding:4px 12px;border-radius:999px;font-size:.76rem;font-weight:bold;border:1px solid var(--line);cursor:pointer;text-decoration:none;color:var(--ink);background:var(--cream);}
  .pill:hover,.pill.active{background:var(--gold);border-color:var(--gold);color:var(--night);}
  .msg-ok{background:#f2efdc;border:1px solid #ddd3ac;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:var(--olive);font-size:.9rem;}
  .msg-err{background:#fbeee9;border:1px solid #eec9c0;border-radius:10px;padding:12px 16px;margin-bottom:14px;color:var(--crimson);font-size:.9rem;}
  .export-group{display:flex;gap:6px;flex-wrap:wrap;}
  .two-col{display:grid;grid-template-columns:1fr;gap:20px;}
  @media(min-width:860px){.two-col{grid-template-columns:1fr 1fr;}}
</style>
</head>
<body>
<header class="topbar">
  <span class="brand">AstroChitra &middot; Subscribers</span>
  <nav>
    <a class="btn btn-ghost" href="dashboard.php">Dashboard</a>
    <a class="btn btn-gold" href="logout.php">Logout</a>
  </nav>
</header>

<main>
  <h1>Subscriber Management</h1>
  <p class="sub">Manage email and WhatsApp subscribers, import CSV, and export lists.</p>

  <?php if ($msg): ?><div class="msg-ok"><?= e($msg) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg-err"><?= e($err) ?></div><?php endif; ?>

  <div class="stats">
    <div class="stat"><div class="num"><?= number_format($totalAll) ?></div><div class="lbl">Total Subscribers</div></div>
    <div class="stat"><div class="num"><?= number_format($totalEmail) ?></div><div class="lbl">Email Subscribers</div></div>
    <div class="stat"><div class="num"><?= number_format($totalWa) ?></div><div class="lbl">WhatsApp Subscribers</div></div>
  </div>

  <!-- ─── Two-col: Add + Import ──────────────────────────────────────── -->
  <div class="two-col">
    <div class="card">
      <h3>Add Subscriber</h3>
      <form method="POST" style="display:flex;flex-direction:column;gap:10px;">
        <input type="hidden" name="action" value="add">
        <div class="form-row">
          <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
          <div class="form-group"><label>Rashi</label><input type="text" name="rashi"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Email</label><input type="email" name="email"></div>
          <div class="form-group"><label>WhatsApp Number</label><input type="tel" name="phone" placeholder="+91..."></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Source</label><input type="text" name="source" placeholder="e.g. Instagram, Referral, Event..."></div>
        </div>
        <div style="font-size:.76rem;color:var(--muted);">If both email and WhatsApp are given, two separate subscribers are created.</div>
        <div><button type="submit" class="btn btn-gold btn-sm">Add Subscriber</button></div>
      </form>
    </div>

    <div class="card">
      <h3>Import CSV</h3>
      <form method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:10px;">
        <input type="hidden" name="action" value="import">
        <div style="font-size:.82rem;color:var(--muted);margin-bottom:4px;">
          CSV columns: <code>name</code> (required), <code>email</code>, <code>phone</code> or <code>whatsapp</code>, <code>rashi</code>, <code>source</code>
        </div>
        <div class="form-group"><label>CSV File</label><input type="file" name="csv_file" accept=".csv" required></div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <button type="submit" class="btn btn-gold btn-sm">Import</button>
          <a href="?action=download_template" class="btn btn-ghost btn-sm">Download Template</a>
        </div>
      </form>
    </div>
  </div>

  <!-- ─── Filters + Sort + Export ────────────────────────────────────── -->
  <h2 class="sec">All Subscribers (<?= number_format(count($subs)) ?>)</h2>

  <div class="toolbar">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;align-items:end;">
      <div class="form-group" style="min-width:100px;flex:0;">
        <label>Type</label>
        <select name="filter">
          <option value="">All</option>
          <option value="email" <?= $filterType==='email'?'selected':'' ?>>Email</option>
          <option value="whatsapp" <?= $filterType==='whatsapp'?'selected':'' ?>>WhatsApp</option>
        </select>
      </div>
      <div class="form-group" style="flex:0 0 150px;">
        <label>Source</label>
        <select name="source">
          <option value="">All</option>
          <?php foreach ($allSources as $src): ?>
          <option value="<?= e($src) ?>" <?= $filterSource===$src?'selected':'' ?>><?= e($src) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group" style="flex:0 0 180px;">
        <label>Search</label>
        <input type="text" name="search" value="<?= e($filterSearch) ?>" placeholder="Name, email, phone...">
      </div>
      <div class="form-group" style="flex:0 0 160px;">
        <label>From</label>
        <input type="date" name="from" value="<?= e($dateFrom) ?>">
      </div>
      <div class="form-group" style="flex:0 0 160px;">
        <label>To</label>
        <input type="date" name="to" value="<?= e($dateTo) ?>">
      </div>
      <input type="hidden" name="sort" value="<?= e($sortBy) ?>">
      <input type="hidden" name="dir" value="<?= e($sortDir) ?>">
      <div><button type="submit" class="btn btn-gold btn-sm">Filter</button></div>
      <?php if ($filterType || $filterSearch || $dateFrom || $dateTo || $filterSource): ?>
        <a href="?" class="btn btn-ghost btn-sm">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- ─── Sort + Bulk Actions ────────────────────────────────────────── -->
  <div class="toolbar">
    <span style="font-size:.82rem;color:var(--muted);">Sort:</span>
    <a href="?<?= http_build_query(array_merge(array_filter(['filter'=>$filterType,'source'=>$filterSource,'search'=>$filterSearch,'from'=>$dateFrom,'to'=>$dateTo]), ['sort'=>'name','dir'=>($sortBy==='name'&&$sortDir==='ASC')?'DESC':'ASC'])) ?>" class="pill <?= $sortBy==='name'?'active':'' ?>">Name <?= $sortBy==='name'?($sortDir==='ASC'?'&#9650;':'&#9660;'):'' ?></a>
    <a href="?<?= http_build_query(array_merge(array_filter(['filter'=>$filterType,'source'=>$filterSource,'search'=>$filterSearch,'from'=>$dateFrom,'to'=>$dateTo]), ['sort'=>'subscribed_at','dir'=>($sortBy==='subscribed_at'&&$sortDir==='ASC')?'DESC':'ASC'])) ?>" class="pill <?= $sortBy==='subscribed_at'?'active':'' ?>">Date <?= $sortBy==='subscribed_at'?($sortDir==='ASC'?'&#9650;':'&#9660;'):'' ?></a>
    <a href="?<?= http_build_query(array_merge(array_filter(['filter'=>$filterType,'source'=>$filterSource,'search'=>$filterSearch,'from'=>$dateFrom,'to'=>$dateTo]), ['sort'=>'source','dir'=>($sortBy==='source'&&$sortDir==='ASC')?'DESC':'ASC'])) ?>" class="pill <?= $sortBy==='source'?'active':'' ?>">Source <?= $sortBy==='source'?($sortDir==='ASC'?'&#9650;':'&#9660;'):'' ?></a>

    <span style="margin-left:auto;"></span>

    <span style="font-size:.82rem;color:var(--muted);">Export:</span>
    <div class="export-group">
      <a href="?action=export&type=email&format=json&<?= $filterQs ?>" class="btn btn-ghost btn-sm">Email JSON</a>
      <a href="?action=export&type=email&format=csv&<?= $filterQs ?>" class="btn btn-ghost btn-sm">Email CSV</a>
      <a href="?action=export&type=whatsapp&format=csv&<?= $filterQs ?>" class="btn btn-ghost btn-sm">WA CSV</a>
      <a href="?action=export&type=whatsapp&format=vcf&<?= $filterQs ?>" class="btn btn-ghost btn-sm">WA VCF</a>
    </div>
  </div>

  <!-- ─── Subscriber Table ───────────────────────────────────────────── -->
  <?php if ($subs): ?>
  <form method="POST" id="subs-form">
    <input type="hidden" name="action" value="delete_selected">
    <div style="margin-bottom:10px;display:flex;gap:8px;align-items:center;">
      <label style="font-size:.82rem;display:flex;align-items:center;gap:5px;cursor:pointer;">
        <input type="checkbox" class="chk" id="select-all"> Select all on this page
      </label>
      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete selected subscribers?')" id="delete-btn" disabled>Delete Selected</button>
      <span id="sel-count" style="font-size:.82rem;color:var(--muted);"></span>
    </div>
    <div style="overflow-x:auto;">
    <table>
      <thead>
        <tr>
          <th style="width:36px;"><input type="checkbox" class="chk" id="check-all"></th>
          <th><?= sortLink('Name', 'name', $sortBy, $sortDir, array_filter(['filter'=>$filterType,'source'=>$filterSource,'search'=>$filterSearch,'from'=>$dateFrom,'to'=>$dateTo])) ?></th>
          <th>Email</th>
          <th>WhatsApp</th>
          <th>Rashi</th>
          <th>Source</th>
          <th>Type</th>
          <th><?= sortLink('Subscribed', 'subscribed_at', $sortBy, $sortDir, array_filter(['filter'=>$filterType,'source'=>$filterSource,'search'=>$filterSearch,'from'=>$dateFrom,'to'=>$dateTo])) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($subs as $s): ?>
        <tr>
          <td><input type="checkbox" class="chk sub-chk" name="sub_ids[]" value="<?= (int)$s['id'] ?>"></td>
          <td><strong><?= e($s['name']) ?></strong></td>
          <td><?= e($s['email'] ?: '—') ?></td>
          <td><?= e($s['phone'] ?: '—') ?></td>
          <td><?= e($s['rashi'] ?: '—') ?></td>
          <td><?= e($s['source'] ?: '—') ?></td>
          <td><span class="badge <?= $s['sub_type']==='email'?'b-email':'b-wa' ?>"><?= e($s['sub_type']) ?></span></td>
          <td style="color:var(--muted);font-size:.82rem;white-space:nowrap;"><?= e($s['subscribed_at']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </form>
  <?php else: ?>
    <p class="empty">No subscribers found.</p>
  <?php endif; ?>
</main>

<script>
(function(){
  var all = document.getElementById('select-all');
  var ca  = document.getElementById('check-all');
  var chks = document.querySelectorAll('.sub-chk');
  var btn  = document.getElementById('delete-btn');
  var cnt  = document.getElementById('sel-count');

  function updateBtn(){
    var n = document.querySelectorAll('.sub-chk:checked').length;
    btn.disabled = n === 0;
    cnt.textContent = n > 0 ? n + ' selected' : '';
  }

  function sync(state){
    for(var i=0;i<chks.length;i++) chks[i].checked = state;
    updateBtn();
  }

  if(all) all.addEventListener('change', function(){ sync(this.checked); });
  if(ca)  ca.addEventListener('change',  function(){ sync(this.checked); });
  for(var i=0;i<chks.length;i++) chks[i].addEventListener('change', updateBtn);
})();
</script>
</body>
</html>
