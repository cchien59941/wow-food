<?php
$categories = [
  ['id'=>1,'title'=>'Bánh mì','image'=>'Food_Category_65.jpg','featured'=>1,'active'=>1,'created_at'=>'2026-02-01'],
  ['id'=>2,'title'=>'Cơm niêu','image'=>'Food_Category_88.avif','featured'=>0,'active'=>1,'created_at'=>'2026-02-02'],
  ['id'=>3,'title'=>'Sủi cảo','image'=>'Food_Category_235.jpg','featured'=>1,'active'=>0,'created_at'=>'2026-02-03'],
  ['id'=>4,'title'=>'Xúc xích','image'=>'Food_Category_296.jpg','featured'=>0,'active'=>1,'created_at'=>'2026-02-04']
];

$date_from = $_GET['date_from'] ?? '';
$date_to   = $_GET['date_to'] ?? '';
$status    = $_GET['status'] ?? 'all';
$q         = trim($_GET['q'] ?? '');

$filtered = array_filter($categories, function($row) use ($date_from,$date_to,$status,$q){
  if ($status === 'active' && (int)$row['active'] !== 1) return false;
  if ($status === 'inactive' && (int)$row['active'] !== 0) return false;

  if ($q !== '') {
    $hay = mb_strtolower($row['title']);
    $needle = mb_strtolower($q);
    if (mb_strpos($hay, $needle) === false) return false;
  }

  if ($date_from !== '' && $row['created_at'] < $date_from) return false;
  if ($date_to   !== '' && $row['created_at'] > $date_to) return false;

  return true;
});

$per_page = 8;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = count($filtered);
$total_pages = max(1, (int)ceil($total / $per_page));
$page = min($page, $total_pages);

$offset = ($page - 1) * $per_page;
$rows = array_slice(array_values($filtered), $offset, $per_page);

function build_query(array $extra = []) {
  $params = array_merge($_GET, $extra);
  foreach ($params as $k=>$v) if ($v === '' || $v === null) unset($params[$k]);
  return http_build_query($params);
}
?>

<div class="page-wrap">

  <div class="page-head">
    <div class="title">
      <h1>QUẢN LÝ DANH MỤC MÓN ĂN</h1>
      <p>Danh sách danh mục — lọc, tìm kiếm, thao tác nhanh.</p>
    </div>

    <form class="search-box" method="get">  
      <input type="text" name="q" placeholder="Search..." value="<?= htmlspecialchars($q) ?>">
      <?php if ($date_from !== ''): ?><input type="hidden" name="date_from" value="<?= htmlspecialchars($date_from) ?>"><?php endif; ?>
      <?php if ($date_to   !== ''): ?><input type="hidden" name="date_to" value="<?= htmlspecialchars($date_to) ?>"><?php endif; ?>
      <?php if ($status !== 'all'): ?><input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>"><?php endif; ?>
      <button type="submit" aria-label="Search">🔎</button>
    </form>
  </div>

  <div class="filters">
    <form class="filters-left" method="get">
      <div class="field">
        <label>Từ ngày</label>
        <input type="date" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
      </div>

      <div class="field">
        <label>Đến ngày</label>
        <input type="date" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
      </div>

      <div class="field">
        <label>Trạng thái</label>
        <select name="status">
          <option value="all" <?= $status==='all'?'selected':'' ?>>Tất cả</option>
          <option value="active" <?= $status==='active'?'selected':'' ?>>Đang hoạt động</option>
          <option value="inactive" <?= $status==='inactive'?'selected':'' ?>>Ngừng hoạt động</option>
        </select>
      </div>

      <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">

      <button class="btn btn-light" type="submit">Lọc</button>
      <a class="btn btn-ghost" href="manage-category.php">Reset</a>
    </form>

    <div class="filters-right">
      <a class="btn btn-primary" href="#">＋ Add Category</a>
      <a class="btn btn-light" href="#">⬇ Export</a>
    </div>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th style="width:60px;">#</th>
            <th>Danh mục</th>
            <th style="width:140px;">Ảnh</th>
            <th style="width:120px;">Nổi bật</th>
            <th style="width:140px;">Trạng thái</th>
            <th style="width:140px;">Ngày tạo</th>
            <th style="width:140px; text-align:right;">Action</th>
          </tr>
        </thead>

        <tbody>
        <?php if (count($rows) === 0): ?>
          <tr>
            <td colspan="7" class="empty">Không có dữ liệu phù hợp bộ lọc.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($rows as $i => $row): ?>
            <?php
              $index = $offset + $i + 1;
              $isActive = ((int)$row['active'] === 1);
              $isFeatured = ((int)$row['featured'] === 1);
              $img = $row['image'];
            ?>
            <tr>
              <td><?= $index ?></td>

              <td>
                <div class="cell-title">
                  <div class="name"><?= htmlspecialchars($row['title']) ?></div>
                  <div class="sub">ID: <?= (int)$row['id'] ?></div>
                </div>
              </td>

              <td>
                <?php if ($img !== ''): ?>
                  <div class="thumb"><img src="../image/category/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($row['title']) ?>"></div>
                <?php else: ?>
                  <div class="thumb thumb-empty">No image</div>
                <?php endif; ?>
              </td>

              <td>
                <span class="dot <?= $isFeatured ? 'dot-green' : 'dot-gray' ?>"></span>
                <?= $isFeatured ? 'Featured' : 'Normal' ?>
              </td>

              <td>
                <span class="dot <?= $isActive ? 'dot-green' : 'dot-red' ?>"></span>
                <?= $isActive ? 'Active' : 'Inactive' ?>
              </td>

              <td><?= htmlspecialchars($row['created_at']) ?></td>

              <td class="actions">
                <a class="btn btn-mini" href="#">View</a>

                <div class="kebab">
                  <button type="button" class="kebab-btn" onclick="toggleMenu(this)">⋮</button>
                  <div class="kebab-menu">
                    <a href="#">Sửa</a>
                    <a href="#"><?= $isActive ? 'Tắt' : 'Bật' ?></a>
                    <a class="danger" href="#" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="pager">
      <div class="pager-left">
        Hiển thị <b><?= count($rows) ?></b> / <b><?= $total ?></b> bản ghi
      </div>

      <div class="pager-right">
        <?php $prev = max(1, $page - 1); $next = min($total_pages, $page + 1); ?>
        <a class="pg" href="?<?= build_query(['page'=>$prev]) ?>" aria-disabled="<?= $page===1?'true':'false' ?>">‹</a>

        <?php for($p=1;$p<=$total_pages;$p++): ?>
          <a class="pg <?= $p===$page?'active':'' ?>" href="?<?= build_query(['page'=>$p]) ?>"><?= $p ?></a>
        <?php endfor; ?>

        <a class="pg" href="?<?= build_query(['page'=>$next]) ?>" aria-disabled="<?= $page===$total_pages?'true':'false' ?>">›</a>
      </div>
    </div>
  </div>

</div>

<style>
.page-wrap{max-width:1100px;margin:0 auto;padding:22px 18px;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;}
.page-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:14px}
.title h1{margin:0;font-size:20px;font-weight:700;color:#1f2a37}
.title p{margin:6px 0 0;color:#6b7280;font-size:13px}

.search-box{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:8px 10px;min-width:260px}
.search-box input{border:none;outline:none;flex:1;font-size:13px}
.search-box button{border:none;background:#f3f4f6;border-radius:8px;padding:6px 8px;cursor:pointer}

.filters{display:flex;align-items:flex-end;justify-content:space-between;gap:14px;margin:12px 0 14px}
.filters-left{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
.field{display:flex;flex-direction:column;gap:6px}
.field label{font-size:12px;color:#6b7280}
.field input,.field select{height:36px;padding:0 10px;border:1px solid #e5e7eb;border-radius:10px;outline:none;font-size:13px;background:#fff;min-width:160px}
.field select{min-width:180px}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;height:36px;padding:0 12px;border-radius:10px;border:1px solid transparent;text-decoration:none;font-size:13px;font-weight:600;cursor:pointer;user-select:none}
.btn-primary{background:#2563eb;color:#fff}
.btn-light{background:#fff;border-color:#e5e7eb;color:#111827}
.btn-ghost{background:transparent;border-color:transparent;color:#6b7280}
.btn-mini{height:30px;padding:0 10px;border-radius:9px;background:#fff;border:1px solid #e5e7eb;color:#111827;font-weight:600;font-size:12px}

.card{background:#fff;border:1px solid #eef2f7;border-radius:14px;box-shadow:0 8px 24px rgba(15,23,42,.06)}
.table-wrap{overflow:auto}
.tbl{width:100%;border-collapse:collapse;min-width:860px}
.tbl thead th{background:#f8fafc;color:#6b7280;font-size:12px;font-weight:700;text-align:left;padding:12px 14px;border-bottom:1px solid #eef2f7}
.tbl tbody td{padding:12px 14px;border-bottom:1px solid #f1f5f9;font-size:13px;color:#111827;vertical-align:middle}
.tbl tbody tr:hover{background:#fbfdff}
.empty{text-align:center;color:#6b7280;padding:22px !important}

.cell-title .name{font-weight:700;color:#111827}
.cell-title .sub{font-size:12px;color:#6b7280;margin-top:3px}

.thumb{display:inline-flex;align-items:center;justify-content:center;height:80px;padding:0;border-radius:8px;background:#f3f4f6;color:#374151;font-size:12px;border:1px solid #e5e7eb;overflow:hidden}
.thumb img{width:80px;height:80px;object-fit:cover;border-radius:8px;display:block}
.thumb-empty{background:#fff;color:#9ca3af;border-style:dashed;min-width:80px;height:80px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px}

.dot{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:8px;vertical-align:middle}
.dot-green{background:#22c55e}
.dot-red{background:#ef4444}
.dot-gray{background:#9ca3af}

.actions{display:flex;justify-content:flex-end;gap:10px;align-items:center}

.kebab{position:relative}
.kebab-btn{height:30px;width:34px;border-radius:9px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;font-size:18px;line-height:1}
.kebab-menu{position:absolute;right:0;top:38px;min-width:160px;background:#fff;border:1px solid #eef2f7;border-radius:12px;box-shadow:0 12px 30px rgba(15,23,42,.12);padding:6px;display:none;z-index:20}
.kebab-menu a{display:flex;align-items:center;padding:10px 10px;border-radius:10px;text-decoration:none;color:#111827;font-size:13px}
.kebab-menu a:hover{background:#f8fafc}
.kebab-menu a.danger{color:#dc2626}
.kebab.open .kebab-menu{display:block}

.pager{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px}
.pager-left{color:#6b7280;font-size:13px}
.pager-right{display:flex;gap:6px;align-items:center}
.pg{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;border:1px solid #e5e7eb;background:#fff;text-decoration:none;color:#111827;font-size:13px}
.pg:hover{background:#f8fafc}
.pg.active{background:#2563eb;color:#fff;border-color:#2563eb}
.pg[aria-disabled="true"]{opacity:.45;pointer-events:none}
</style>

