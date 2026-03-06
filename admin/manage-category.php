<?php
require_once('../config/constants.php');
require_once('partials/login-check.php');

if (!isset($_SESSION['demo_categories'])) {
    $_SESSION['demo_categories'] = [
        ['id' => 1, 'title' => 'Pizza', 'featured' => 'Yes', 'active' => 'Yes'],
        ['id' => 2, 'title' => 'Burger', 'featured' => 'No', 'active' => 'Yes'],
        ['id' => 3, 'title' => 'Tra sua', 'featured' => 'Yes', 'active' => 'No'],
    ];
}

function go_manage_category()
{
    header('location:' . SITEURL . 'admin/manage-category.php');
    exit;
}

function find_category_by_id($categories, $id)
{
    foreach ($categories as $item) {
        if ((int)$item['id'] === (int)$id) {
            return $item;
        }
    }

    return null;
}

$categories = $_SESSION['demo_categories'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $featured = ($_POST['featured'] ?? 'No') === 'Yes' ? 'Yes' : 'No';
    $active = ($_POST['active'] ?? 'No') === 'Yes' ? 'Yes' : 'No';

    if ($title === '') {
        $_SESSION['add'] = 'Vui lòng nhập tên danh mục.';
        go_manage_category();
    }

    if ($action === 'add') {
        $max_id = 0;
        foreach ($categories as $item) {
            if ($item['id'] > $max_id) {
                $max_id = $item['id'];
            }
        }

        $categories[] = [
            'id' => $max_id + 1,
            'title' => $title,
            'featured' => $featured,
            'active' => $active,
        ];

        $_SESSION['demo_categories'] = $categories;
        $_SESSION['add'] = 'Thêm danh mục thành công.';
        go_manage_category();
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);

        foreach ($categories as $index => $item) {
            if ((int)$item['id'] === $id) {
                $categories[$index]['title'] = $title;
                $categories[$index]['featured'] = $featured;
                $categories[$index]['active'] = $active;
                break;
            }
        }

        $_SESSION['demo_categories'] = $categories;
        $_SESSION['update'] = 'Cập nhật danh mục thành công.';
        go_manage_category();
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    foreach ($categories as $index => $item) {
        if ((int)$item['id'] === $delete_id) {
            unset($categories[$index]);
            break;
        }
    }

    $_SESSION['demo_categories'] = array_values($categories);
    $_SESSION['delete'] = 'Xóa danh mục thành công.';
    go_manage_category();
}

$keyword = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? 'all';
$edit_id = (int)($_GET['edit_id'] ?? 0);

$filtered_categories = [];

foreach ($categories as $item) {
    $match_keyword = true;
    $match_status = true;

    if ($keyword !== '' && stripos($item['title'], $keyword) === false) {
        $match_keyword = false;
    }

    if ($status === 'active' && $item['active'] !== 'Yes') {
        $match_status = false;
    }

    if ($status === 'inactive' && $item['active'] !== 'No') {
        $match_status = false;
    }

    if ($match_keyword && $match_status) {
        $filtered_categories[] = $item;
    }
}

$edit_category = null;
if ($edit_id > 0) {
    $edit_category = find_category_by_id($categories, $edit_id);
}

include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <div class="page-wrap">
            <div class="page-head">
                <div class="title">
                    <h1>QUẢN LÝ DANH MỤC MÓN ĂN</h1>
                </div>

                <form class="search-box" method="get">
                    <input type="text" name="q" placeholder="Tìm theo tên danh mục" value="<?php echo htmlspecialchars($keyword); ?>">
                    <?php if ($status !== 'all') { ?>
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                    <?php } ?>
                    <button type="submit">Tìm</button>
                </form>
            </div>

            <div class="form-card">
                <div class="form-card-head">
                    <h2><?php echo $edit_category ? 'Sửa danh mục' : 'Thêm danh mục mới'; ?></h2>
                    <?php if ($edit_category) { ?>
                        <a class="btn btn-ghost" href="manage-category.php">Tạo mới</a>
                    <?php } ?>
                </div>

                <form method="post" class="category-form">
                    <input type="hidden" name="action" value="<?php echo $edit_category ? 'update' : 'add'; ?>">

                    <?php if ($edit_category) { ?>
                        <input type="hidden" name="id" value="<?php echo (int)$edit_category['id']; ?>">
                    <?php } ?>

                    <div class="field">
                        <label>Tên danh mục</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($edit_category['title'] ?? ''); ?>">
                    </div>

                    <div class="field-inline">
                        <div class="field">
                            <label>Nổi bật</label>
                            <select name="featured">
                                <option value="Yes" <?php echo (($edit_category['featured'] ?? 'No') === 'Yes') ? 'selected' : ''; ?>>Có</option>
                                <option value="No" <?php echo (($edit_category['featured'] ?? 'No') === 'No') ? 'selected' : ''; ?>>Không</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Hoạt động</label>
                            <select name="active">
                                <option value="Yes" <?php echo (($edit_category['active'] ?? 'Yes') === 'Yes') ? 'selected' : ''; ?>>Có</option>
                                <option value="No" <?php echo (($edit_category['active'] ?? 'Yes') === 'No') ? 'selected' : ''; ?>>Không</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?php echo $edit_category ? 'Lưu thay đổi' : 'Thêm danh mục'; ?></button>
                    </div>
                </form>
            </div>

            <div class="filters">
                <form class="filters-left" method="get">
                    <div class="field">
                        <label>Trạng thái</label>
                        <select name="status">
                            <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>Tất cả</option>
                            <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Đang hoạt động</option>
                            <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Ngừng hoạt động</option>
                        </select>
                    </div>

                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($keyword); ?>">
                    <button class="btn btn-light" type="submit">Lọc</button>
                </form>
            </div>

            <div class="card">
                <div class="table-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Tên danh mục</th>
                                <th style="width:120px;">Nổi bật</th>
                                <th style="width:120px;">Hoạt động</th>
                                <th style="width:170px; text-align:right;">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($filtered_categories) === 0) { ?>
                                <tr>
                                    <td colspan="5" class="empty">Chưa có danh mục phù hợp.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($filtered_categories as $index => $row) { ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <div class="cell-title">
                                                <div class="name"><?php echo htmlspecialchars($row['title']); ?></div>
                                                <div class="sub">ID: <?php echo (int)$row['id']; ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="dot <?php echo $row['featured'] === 'Yes' ? 'dot-green' : 'dot-gray'; ?>"></span>
                                            <?php echo $row['featured'] === 'Yes' ? 'Có' : 'Không'; ?>
                                        </td>
                                        <td>
                                            <span class="dot <?php echo $row['active'] === 'Yes' ? 'dot-green' : 'dot-red'; ?>"></span>
                                            <?php echo $row['active'] === 'Yes' ? 'Có' : 'Không'; ?>
                                        </td>
                                        <td class="actions">
                                            <a class="btn btn-mini" href="manage-category.php?edit_id=<?php echo (int)$row['id']; ?>">Sửa</a>
                                            <a class="btn btn-mini btn-danger" href="manage-category.php?delete_id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?')">Xóa</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-wrap{padding:20px 0 30px}
.page-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:18px}
.title h1{margin:0;font-size:28px;color:#1e272e}
.title p{margin:6px 0 0;color:#747d8c}
.search-box{display:flex;gap:8px;background:#fff;padding:10px;border-radius:12px;border:1px solid #dfe4ea;min-width:280px}
.search-box input{flex:1;border:none;outline:none;font-size:14px}
.search-box button{border:none;background:#1e90ff;color:#fff;padding:0 14px;border-radius:8px;cursor:pointer}
.form-card,.card{background:#fff;border:1px solid #e9eef3;border-radius:14px;box-shadow:0 6px 18px rgba(0,0,0,.05)}
.form-card{padding:20px;margin-bottom:18px}
.form-card-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px}
.form-card-head h2{margin:0;font-size:20px;color:#1e272e}
.category-form{display:grid;gap:14px}
.field{display:flex;flex-direction:column;gap:6px}
.field label{font-weight:600;color:#57606f}
.field input,.field select{height:40px;padding:0 12px;border:1px solid #dfe4ea;border-radius:10px;font-size:14px;background:#fff}
.field-inline{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
.form-actions{display:flex;justify-content:flex-start}
.filters{display:flex;justify-content:flex-start;margin-bottom:16px}
.filters-left{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
.btn{display:inline-flex;align-items:center;justify-content:center;height:38px;padding:0 14px;border-radius:10px;border:1px solid transparent;text-decoration:none;font-size:14px;font-weight:600;cursor:pointer}
.btn-primary{background:#1e90ff;color:#fff}
.btn-light{background:#fff;border-color:#dfe4ea;color:#2f3542}
.btn-ghost{background:transparent;color:#57606f}
.btn-mini{height:34px;padding:0 12px;background:#fff;border:1px solid #dfe4ea;color:#2f3542}
.btn-danger{background:#fff5f5;border-color:#ffc9c9;color:#c92a2a}
.table-wrap{overflow:auto}
.tbl{width:100%;border-collapse:collapse;min-width:700px}
.tbl thead th{background:#f8fafc;color:#57606f;font-size:13px;text-align:left;padding:14px;border-bottom:1px solid #e9eef3}
.tbl tbody td{padding:14px;border-bottom:1px solid #eef2f7;font-size:14px;color:#2f3542;vertical-align:middle}
.empty{text-align:center;color:#747d8c;padding:22px !important}
.cell-title .name{font-weight:700;color:#1e272e}
.cell-title .sub{font-size:12px;color:#747d8c;margin-top:4px}
.dot{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:8px;vertical-align:middle}
.dot-green{background:#2ed573}
.dot-red{background:#ff4757}
.dot-gray{background:#a4b0be}
.actions{display:flex;justify-content:flex-end;gap:8px}
@media (max-width: 768px){
    .page-head{flex-direction:column}
    .search-box{min-width:100%}
    .field-inline{grid-template-columns:1fr}
}
</style>

<?php include('partials/footer.php'); ?>
