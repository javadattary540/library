<?php
header('Content-Type: text/html; charset=utf-8');

include "connection.php";
session_start();

/* ---------- توکن CSRF ---------- */
if (empty($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_admin'];

/* ---------- حذف کتاب ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

    if (!isset($_SESSION['login_user'])) {
        header("Location: books.php?err=login");
        exit;
    }

    if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
        header("Location: books.php?err=csrf");
        exit;
    }

    $bid = (int)($_POST['bid'] ?? 0);
    if ($bid > 0) {
        $stmt = mysqli_prepare($db, "DELETE FROM `books` WHERE `bid` = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $bid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header("Location: books.php?deleted=1");
    exit;
}

/* ---------- پیام‌ها ---------- */
$notice = '';
$error  = '';

if (isset($_GET['deleted'])) $notice = 'کتاب با موفقیت حذف شد.';
if (isset($_GET['err'])) {
    $error = ($_GET['err'] === 'login')
        ? 'برای حذف کتاب باید وارد حساب مدیر شوید.'
        : 'درخواست نامعتبر بود. دوباره تلاش کنید.';
}

/* ---------- جستجو ---------- */
$q     = trim((string)($_GET['q'] ?? ''));
$books = array();

if ($q !== '') {
    $stmt = mysqli_prepare($db, "SELECT * FROM `books` WHERE `name` LIKE ? ORDER BY `bid` ASC");
    if ($stmt) {
        $like = '%' . $q . '%';
        mysqli_stmt_bind_param($stmt, "s", $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        while ($res && ($row = mysqli_fetch_assoc($res))) {
            $books[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $res = mysqli_query($db, "SELECT * FROM `books` ORDER BY `bid` ASC");
    while ($res && ($row = mysqli_fetch_assoc($res))) {
        $books[] = $row;
    }
}

$total = count($books);
$logged = isset($_SESSION['login_user']);
$name   = $logged ? (string)$_SESSION['login_user'] : '';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>کتاب‌ها | پنل مدیریت</title>

    <style>
        :root{
            --gold:#f0c75e;
            --gold-2:#c9992b;
            --bg:#0b0e14;
            --card:rgba(18,22,31,.72);
            --line:rgba(255,255,255,.12);
            --txt:#eef2f8;
            --muted:#a5aec0;
        }

        *{ box-sizing:border-box; }
        html, body{ margin:0; padding:0; }

        body.bk-page{
            background:
                radial-gradient(1100px 520px at 85% -10%, rgba(240,199,94,.10), transparent 60%),
                radial-gradient(900px 480px at 10% 110%, rgba(59,227,196,.07), transparent 60%),
                var(--bg);
            color:var(--txt);
            min-height:100vh;
        }

        /* ================= هدر ================= */
        .bk-top{
            position:sticky;
            top:0;
            z-index:50;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
            padding:12px 26px;
            background:rgba(9,12,17,.78);
            border-bottom:1px solid var(--line);
            -webkit-backdrop-filter:blur(14px) saturate(140%);
            backdrop-filter:blur(14px) saturate(140%);
            flex-wrap:wrap;
        }
        .bk-brand{
            display:flex;
            align-items:center;
            gap:11px;
            text-decoration:none;
            color:var(--txt);
        }
        .bk-brand img{
            width:44px; height:44px;
            border-radius:13px;
            object-fit:cover;
            border:1px solid rgba(240,199,94,.45);
            box-shadow:0 6px 18px rgba(0,0,0,.45);
        }
        .bk-brand b{ display:block; font-size:16px; line-height:1.3; }
        .bk-brand small{ color:var(--muted); font-size:11.5px; }

        .bk-nav{ display:flex; align-items:center; gap:4px; flex-wrap:wrap; }
        .bk-nav a{
            padding:9px 14px;
            border-radius:11px;
            color:#cfd6e3;
            font-size:14px;
            text-decoration:none;
            transition:background .2s, color .2s;
        }
        .bk-nav a:hover{ background:rgba(255,255,255,.07); color:#fff; text-decoration:none; }
        .bk-nav a.on{
            background:rgba(240,199,94,.13);
            color:#fff;
            box-shadow:inset 0 0 0 1px rgba(240,199,94,.35);
            text-decoration:none;
        }

        .bk-user{ display:flex; align-items:center; gap:10px; }
        .bk-avatar{
            width:38px; height:38px;
            border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-weight:700;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 6px 16px rgba(201,153,43,.28);
        }
        .bk-user-name{
            font-size:13.5px;
            color:#e6ebf3;
            max-width:150px;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        .bk-ghost{
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:9px 16px;
            border-radius:11px;
            border:1px solid var(--line);
            background:rgba(255,255,255,.05);
            color:#e6ebf3;
            font-size:13.5px;
            text-decoration:none;
            transition:background .2s, border-color .2s, color .2s;
        }
        .bk-ghost:hover{
            background:rgba(240,199,94,.14);
            border-color:rgba(240,199,94,.42);
            color:#fff;
            text-decoration:none;
        }

        /* ================= محتوا ================= */
        .bk-wrap{
            width:min(1180px, 94vw);
            margin:26px auto 60px;
        }

        .bk-head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }
        .bk-head h1{
            margin:0;
            font-size:27px;
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
        }
        .bk-count{
            font-size:13px;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            border-radius:999px;
            padding:3px 12px;
            font-weight:700;
        }

        .bk-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:12px 22px;
            border-radius:13px;
            font-family:inherit;
            font-size:14.5px;
            font-weight:700;
            border:0;
            cursor:pointer;
            text-decoration:none;
            transition:transform .18s, box-shadow .18s, background .2s, filter .18s;
        }
        .bk-btn.gold{
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.32);
        }
        .bk-btn.gold:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            color:#241a05;
            text-decoration:none;
        }
        .bk-btn.line{
            color:#e6ebf3;
            background:rgba(255,255,255,.05);
            border:1px solid var(--line);
        }
        .bk-btn.line:hover{ background:rgba(255,255,255,.12); color:#fff; text-decoration:none; }

        /* ================= پیام‌ها ================= */
        .bk-alert{
            display:flex;
            align-items:center;
            gap:10px;
            padding:13px 16px;
            border-radius:14px;
            font-size:14px;
            margin-bottom:16px;
            border:1px solid transparent;
            animation:bkUp .35s ease both;
        }
        .bk-alert.ok{
            background:rgba(52,199,123,.12);
            border-color:rgba(52,199,123,.38);
            color:#a8f0c6;
        }
        .bk-alert.err{
            background:rgba(255,99,99,.12);
            border-color:rgba(255,99,99,.38);
            color:#ffc0bb;
        }

        /* ================= جستجو ================= */
        .bk-search{
            display:flex;
            gap:10px;
            align-items:center;
            background:var(--card);
            border:1px solid var(--line);
            border-radius:16px;
            padding:12px;
            margin-bottom:18px;
            flex-wrap:wrap;
        }
        .bk-search .field{
            position:relative;
            flex:1 1 260px;
        }
        .bk-search .field span{
            position:absolute;
            top:50%;
            right:14px;
            transform:translateY(-50%);
            color:#8f9aac;
            font-size:15px;
        }
        .bk-search input{
            width:100%;
            height:46px;
            padding:0 42px 0 14px;
            border-radius:12px;
            border:1px solid var(--line);
            background:rgba(255,255,255,.05);
            color:var(--txt);
            font-family:inherit;
            font-size:14.5px;
            outline:none;
            transition:border-color .2s, box-shadow .2s, background .2s;
        }
        .bk-search input::placeholder{ color:#8b95a7; }
        .bk-search input:focus{
            border-color:rgba(240,199,94,.6);
            background:rgba(255,255,255,.08);
            box-shadow:0 0 0 4px rgba(240,199,94,.13);
        }

        /* ================= جدول ================= */
        .bk-table-wrap{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 24px 60px rgba(0,0,0,.42);
            animation:bkUp .45s ease both;
        }
        .bk-scroll{ overflow-x:auto; }

        table.bk-table{
            width:100%;
            border-collapse:collapse;
            min-width:820px;
            font-size:14px;
        }
        table.bk-table thead th{
            background:rgba(255,255,255,.045);
            color:#cdd5e3;
            font-size:12.5px;
            font-weight:700;
            text-align:right;
            padding:14px 18px;
            white-space:nowrap;
            border-bottom:1px solid var(--line);
        }
        table.bk-table tbody td{
            padding:14px 18px;
            border-bottom:1px solid rgba(255,255,255,.06);
            color:#dfe5ef;
            vertical-align:middle;
        }
        table.bk-table tbody tr{ transition:background .18s; }
        table.bk-table tbody tr:hover{ background:rgba(240,199,94,.055); }
        table.bk-table tbody tr:last-child td{ border-bottom:0; }

        .bk-id{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:34px;
            padding:3px 9px;
            border-radius:9px;
            background:rgba(255,255,255,.07);
            border:1px solid var(--line);
            color:#cfd6e3;
            font-size:12.5px;
            font-variant-numeric:tabular-nums;
        }
        .bk-title{ font-weight:700; color:#fff; }
        .bk-muted{ color:var(--muted); font-size:13px; }

        .bk-badge{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:5px 12px;
            border-radius:999px;
            font-size:12.5px;
            font-weight:700;
            white-space:nowrap;
        }
        .bk-badge.ok{
            color:#a8f0c6;
            background:rgba(52,199,123,.13);
            border:1px solid rgba(52,199,123,.35);
        }
        .bk-badge.no{
            color:#ffc0bb;
            background:rgba(255,99,99,.13);
            border:1px solid rgba(255,99,99,.35);
        }

        .bk-qty{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-width:38px;
            padding:4px 10px;
            border-radius:10px;
            background:rgba(240,199,94,.1);
            border:1px solid rgba(240,199,94,.28);
            color:#ffe3a1;
            font-size:13px;
            font-weight:700;
            font-variant-numeric:tabular-nums;
        }

        .bk-del{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:8px 14px;
            border-radius:10px;
            border:1px solid rgba(255,99,99,.32);
            background:rgba(255,99,99,.1);
            color:#ffb3ac;
            font-family:inherit;
            font-size:12.5px;
            font-weight:700;
            cursor:pointer;
            transition:background .2s, border-color .2s, color .2s, transform .15s;
        }
        .bk-del:hover{
            background:rgba(255,99,99,.2);
            border-color:rgba(255,99,99,.55);
            color:#fff;
            transform:translateY(-1px);
        }

        .bk-empty{
            text-align:center;
            padding:54px 20px;
            color:var(--muted);
        }
        .bk-empty .ico{ font-size:38px; display:block; margin-bottom:12px; opacity:.85; }
        .bk-empty b{ color:#e8edf5; display:block; font-size:17px; margin-bottom:6px; }

        /* ================= مودال حذف ================= */
        .bk-overlay{
            position:fixed;
            inset:0;
            z-index:100;
            background:rgba(4,7,11,.66);
            -webkit-backdrop-filter:blur(4px);
            backdrop-filter:blur(4px);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
            opacity:0;
            visibility:hidden;
            transition:opacity .22s, visibility .22s;
        }
        .bk-overlay.show{ opacity:1; visibility:visible; }

        .bk-modal{
            width:min(420px, 100%);
            background:#131822;
            border:1px solid var(--line);
            border-radius:20px;
            padding:26px 24px 22px;
            text-align:center;
            box-shadow:0 30px 70px rgba(0,0,0,.6);
            transform:translateY(10px) scale(.98);
            transition:transform .22s;
        }
        .bk-overlay.show .bk-modal{ transform:translateY(0) scale(1); }

        .bk-modal .m-ico{
            width:56px; height:56px;
            margin:0 auto 14px;
            display:flex; align-items:center; justify-content:center;
            font-size:24px;
            border-radius:17px;
            background:rgba(255,99,99,.14);
            border:1px solid rgba(255,99,99,.35);
        }
        .bk-modal h3{ margin:0 0 8px; font-size:18px; }
        .bk-modal p{
            margin:0 0 20px;
            color:var(--muted);
            font-size:13.5px;
            line-height:1.9;
        }
        .bk-modal p b{ color:#fff; }
        .bk-modal-btns{ display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }

        .bk-btn.danger{
            color:#fff;
            background:linear-gradient(135deg, #e05a4f, #b53a31);
            box-shadow:0 12px 26px rgba(181,58,49,.32);
        }
        .bk-btn.danger:hover{ transform:translateY(-2px); filter:brightness(1.06); }

        @keyframes bkUp{
            from{ opacity:0; transform:translateY(12px); }
            to  { opacity:1; transform:translateY(0); }
        }

        @media (max-width:640px){
            .bk-top{ padding:10px 14px; gap:10px; }
            .bk-nav{ order:3; width:100%; }
            .bk-head h1{ font-size:22px; }
            .bk-user-name{ display:none; }
            .bk-btn{ width:100%; justify-content:center; }
            .bk-head .bk-btn{ width:auto; }
        }
    </style>
</head>
<body class="bk-page">

<header class="bk-top">
    <a class="bk-brand" href="index.php">
        <img src="./images/logo.jpg" alt="لوگو">
        <span>
            <b>کتابخانهٔ آنلاین</b>
            <small>پنل مدیریت</small>
        </span>
    </a>

    <nav class="bk-nav">
        <a href="index.php">خانه</a>
        <a class="on" href="books.php">کتاب‌ها</a>
        <a href="request.php">درخواست‌ها</a>
        <a href="student.php">دانشجویان</a>
        <a href="feedback.php">نظرات</a>
    </nav>

    <?php if ($logged): ?>
        <div class="bk-user">
            <span class="bk-user-name"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="bk-avatar"><?php echo htmlspecialchars(mb_substr($name, 0, 1), ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="bk-ghost" href="logout.php">خروج</a>
        </div>
    <?php else: ?>
        <div class="bk-user">
            <a class="bk-ghost" href="admin_login.php">ورود مدیر</a>
        </div>
    <?php endif; ?>
</header>

<div class="bk-wrap">

    <div class="bk-head">
        <h1>
            مدیریت کتاب‌ها
            <span class="bk-count"><?php echo (int)$total; ?> کتاب</span>
        </h1>
        <a class="bk-btn gold" href="add.php">＋ افزودن کتاب</a>
    </div>

    <?php if ($notice !== ''): ?>
        <div class="bk-alert ok"><span>✓</span><span><?php echo htmlspecialchars($notice, ENT_QUOTES, 'UTF-8'); ?></span></div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="bk-alert err"><span>!</span><span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span></div>
    <?php endif; ?>

    <form class="bk-search" method="get" action="books.php">
        <div class="field">
            <span>🔍</span>
            <input type="text" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="جستجوی کتاب بر اساس نام…" autocomplete="off">
        </div>
        <button class="bk-btn gold" type="submit">جستجو</button>
        <?php if ($q !== ''): ?>
            <a class="bk-btn line" href="books.php">نمایش همه</a>
        <?php endif; ?>
    </form>

    <div class="bk-table-wrap">

        <?php if ($total === 0): ?>

            <div class="bk-empty">
                <span class="ico"><?php echo $q !== '' ? '🔍' : '📚'; ?></span>
                <?php if ($q !== ''): ?>
                    <b>کتابی با این نام پیدا نشد</b>
                    عبارت دیگری را امتحان کنید یا <a href="books.php" style="color:#ffe0a0;">همهٔ کتاب‌ها</a> را ببینید.
                <?php else: ?>
                    <b>هنوز کتابی ثبت نشده است</b>
                    با دکمهٔ «افزودن کتاب» اولین کتاب را اضافه کنید.
                <?php endif; ?>
            </div>

        <?php else: ?>

            <div class="bk-scroll">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>نام کتاب</th>
                            <th>نویسنده</th>
                            <th>ویرایش</th>
                            <th>وضعیت</th>
                            <th>موجودی</th>
                            <th>دانشکده</th>
                            <th style="text-align:center;">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $b): ?>
                            <?php $available = ((int)$b['quantity'] > 0); ?>
                            <tr>
                                <td><span class="bk-id">#<?php echo (int)$b['bid']; ?></span></td>
                                <td><span class="bk-title"><?php echo htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td class="bk-muted"><?php echo htmlspecialchars($b['authors'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="bk-muted"><?php echo htmlspecialchars($b['edition'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if ($available): ?>
                                        <span class="bk-badge ok">● موجود</span>
                                    <?php else: ?>
                                        <span class="bk-badge no">● ناموجود</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="bk-qty"><?php echo (int)$b['quantity']; ?></span></td>
                                <td class="bk-muted"><?php echo htmlspecialchars($b['department'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <button type="button" class="bk-del"
                                            data-bid="<?php echo (int)$b['bid']; ?>"
                                            data-name="<?php echo htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                        🗑 حذف
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>

</div>

<!-- مودال تأیید حذف -->
<div class="bk-overlay" id="delOverlay">
    <div class="bk-modal">
        <div class="m-ico">🗑️</div>
        <h3>حذف کتاب</h3>
        <p>آیا از حذف «<b id="delName">—</b>» مطمئن هستید؟<br>این عمل قابل بازگشت نیست.</p>
        <div class="bk-modal-btns">
            <button type="button" class="bk-btn danger" id="delConfirm">بله، حذف کن</button>
            <button type="button" class="bk-btn line" id="delCancel">انصراف</button>
        </div>
    </div>
</div>

<form method="post" action="books.php" id="delForm" style="display:none;">
    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="bid" id="delBid" value="">
    <input type="hidden" name="delete" value="1">
</form>

<script>
    (function () {
        var overlay = document.getElementById('delOverlay');
        var nameEl  = document.getElementById('delName');
        var bidEl   = document.getElementById('delBid');
        var form    = document.getElementById('delForm');

        document.querySelectorAll('.bk-del').forEach(function (btn) {
            btn.addEventListener('click', function () {
                nameEl.textContent = btn.getAttribute('data-name') || 'این کتاب';
                bidEl.value = btn.getAttribute('data-bid');
                overlay.classList.add('show');
            });
        });

        document.getElementById('delCancel').addEventListener('click', function () {
            overlay.classList.remove('show');
        });

        document.getElementById('delConfirm').addEventListener('click', function () {
            form.submit();
        });

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('show');
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') overlay.classList.remove('show');
        });
    })();
</script>

</body>
</html>
