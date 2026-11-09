<?php
header('Content-Type: text/html; charset=utf-8');

include "connection.php";
session_start();

/* ---------- توکن CSRF ---------- */
if (empty($_SESSION['csrf_admin'])) {
    $_SESSION['csrf_admin'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_admin'];

$logged = isset($_SESSION['login_user']);
$name   = $logged ? (string)$_SESSION['login_user'] : '';

$error  = '';
$notice = '';
$form   = array(
    'bid'        => '',
    'name'       => '',
    'authors'    => '',
    'edition'    => '',
    'status'     => 'Available',
    'quantity'   => '',
    'department' => '',
);

/* ---------- ثبت کتاب ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    foreach ($form as $k => $v) {
        $form[$k] = trim((string)($_POST[$k] ?? ''));
    }

    if (!$logged) {
        $error = 'برای ثبت کتاب باید ابتدا وارد حساب مدیر شوید.';
    } elseif (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
        $error = 'درخواست نامعتبر است. صفحه را دوباره بارگذاری کنید.';
    } else {
        $bid      = (int)$form['bid'];
        $quantity = (int)$form['quantity'];

        if ($bid <= 0) {
            $error = 'شناسهٔ کتاب باید یک عدد مثبت باشد.';
        } elseif ($form['name'] === '' || $form['authors'] === '' || $form['edition'] === '' || $form['department'] === '') {
            $error = 'همهٔ فیلدها را کامل کنید.';
        } elseif ($quantity < 0) {
            $error = 'تعداد نمی‌تواند منفی باشد.';
        } elseif (!in_array($form['status'], array('Available', 'not-available'), true)) {
            $error = 'وضعیت انتخاب‌شده معتبر نیست.';
        } else {
            /* شناسهٔ تکراری؟ */
            $chk = mysqli_prepare($db, "SELECT `bid` FROM `books` WHERE `bid` = ? LIMIT 1");
            mysqli_stmt_bind_param($chk, "i", $bid);
            mysqli_stmt_execute($chk);
            $exists = mysqli_stmt_get_result($chk);
            $dup    = $exists && mysqli_fetch_assoc($exists);
            mysqli_stmt_close($chk);

            if ($dup) {
                $error = 'کتابی با شناسهٔ ' . $bid . ' از قبل وجود دارد.';
            } else {
                $stmt = mysqli_prepare(
                    $db,
                    "INSERT INTO `books` (`bid`, `name`, `authors`, `edition`, `status`, `quantity`, `department`)
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );

                if ($stmt) {
                    mysqli_stmt_bind_param(
                        $stmt, "issssis",
                        $bid,
                        $form['name'],
                        $form['authors'],
                        $form['edition'],
                        $form['status'],
                        $quantity,
                        $form['department']
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        header("Location: add.php?ok=1");
                        exit;
                    }
                    $error = 'ثبت کتاب انجام نشد: ' . mysqli_error($db);
                    mysqli_stmt_close($stmt);
                } else {
                    $error = 'خطا در آماده‌سازی کوئری.';
                }
            }
        }
    }
}

if (isset($_GET['ok'])) {
    $notice = 'کتاب با موفقیت به فهرست اضافه شد.';
    $form   = array_map(function () { return ''; }, $form);
    $form['status'] = 'Available';
}

/* ---------- دانشکده‌های موجود ---------- */
$departments = array();
$dres = mysqli_query($db, "SELECT DISTINCT `department` FROM `books` WHERE `department` <> '' ORDER BY `department`");
while ($dres && ($row = mysqli_fetch_assoc($dres))) {
    $departments[] = $row['department'];
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>افزودن کتاب | پنل مدیریت</title>

    <style>
        :root{
            --gold:#f0c75e;
            --gold-2:#c9992b;
            --bg:#0b0e14;
            --card:rgba(18,22,31,.75);
            --line:rgba(255,255,255,.12);
            --txt:#eef2f8;
            --muted:#a5aec0;
        }

        *{ box-sizing:border-box; }
        html, body{ margin:0; padding:0; }

        body.ad-page{
            background:
                radial-gradient(1100px 520px at 85% -10%, rgba(240,199,94,.10), transparent 60%),
                radial-gradient(900px 480px at 10% 110%, rgba(59,227,196,.07), transparent 60%),
                var(--bg);
            color:var(--txt);
            min-height:100vh;
            padding-bottom:60px;
        }

        /* ================= هدر ================= */
        .ad-top{
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
        .ad-brand{ display:flex; align-items:center; gap:11px; text-decoration:none; color:var(--txt); }
        .ad-brand img{
            width:44px; height:44px;
            border-radius:13px;
            object-fit:cover;
            border:1px solid rgba(240,199,94,.45);
            box-shadow:0 6px 18px rgba(0,0,0,.45);
        }
        .ad-brand b{ display:block; font-size:16px; line-height:1.3; }
        .ad-brand small{ color:var(--muted); font-size:11.5px; }

        .ad-nav{ display:flex; align-items:center; gap:4px; flex-wrap:wrap; }
        .ad-nav a{
            padding:9px 14px;
            border-radius:11px;
            color:#cfd6e3;
            font-size:14px;
            text-decoration:none;
            transition:background .2s, color .2s;
        }
        .ad-nav a:hover{ background:rgba(255,255,255,.07); color:#fff; text-decoration:none; }
        .ad-nav a.on{
            background:rgba(240,199,94,.13);
            color:#fff;
            box-shadow:inset 0 0 0 1px rgba(240,199,94,.35);
            text-decoration:none;
        }

        .ad-user{ display:flex; align-items:center; gap:10px; }
        .ad-avatar{
            width:38px; height:38px;
            border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-weight:700;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 6px 16px rgba(201,153,43,.28);
        }
        .ad-user-name{
            font-size:13.5px;
            color:#e6ebf3;
            max-width:150px;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        .ad-ghost{
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
        .ad-ghost:hover{
            background:rgba(240,199,94,.14);
            border-color:rgba(240,199,94,.42);
            color:#fff;
            text-decoration:none;
        }

        /* ================= محتوا ================= */
        .ad-wrap{
            width:min(880px, 94vw);
            margin:30px auto 0;
        }

        .ad-head{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }
        .ad-head h1{ margin:0; font-size:26px; }
        .ad-head p{ margin:6px 0 0; color:var(--muted); font-size:13.5px; }

        .ad-card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:22px;
            padding:26px;
            box-shadow:0 26px 64px rgba(0,0,0,.45);
        }

        .ad-alert{
            display:flex;
            align-items:center;
            gap:10px;
            padding:13px 16px;
            border-radius:14px;
            font-size:14px;
            margin-bottom:18px;
            border:1px solid transparent;
        }
        .ad-alert.ok{
            background:rgba(52,199,123,.12);
            border-color:rgba(52,199,123,.38);
            color:#a8f0c6;
        }
        .ad-alert.err{
            background:rgba(255,99,99,.12);
            border-color:rgba(255,99,99,.38);
            color:#ffc0bb;
        }
        .ad-alert a{ color:#ffe0a0; }

        .ad-grid{
            display:grid;
            grid-template-columns:repeat(2, 1fr);
            gap:16px;
        }

        .ad-field{ min-width:0; }
        .ad-field.full{ grid-column:1 / -1; }

        .ad-label{
            display:flex;
            align-items:center;
            gap:7px;
            font-size:13px;
            color:#c9d2e0;
            margin:0 2px 7px;
        }
        .ad-label .req{ color:#ff8f86; }

        .ad-input,
        .ad-select{
            width:100%;
            height:48px;
            padding:0 14px;
            border-radius:13px;
            border:1px solid var(--line);
            background:rgba(255,255,255,.05);
            color:var(--txt);
            font-family:inherit;
            font-size:14.5px;
            outline:none;
            transition:border-color .2s, box-shadow .2s, background .2s;
        }
        .ad-input::placeholder{ color:#8b95a7; }
        .ad-input:focus,
        .ad-select:focus{
            border-color:rgba(240,199,94,.6);
            background:rgba(255,255,255,.08);
            box-shadow:0 0 0 4px rgba(240,199,94,.13);
        }

        .ad-select{
            appearance:none;
            -webkit-appearance:none;
            background-image:linear-gradient(45deg, transparent 50%, #9aa5b6 50%),
                             linear-gradient(135deg, #9aa5b6 50%, transparent 50%);
            background-position:calc(14px) calc(50% - 2px), calc(19px) calc(50% - 2px);
            background-size:5px 5px, 5px 5px;
            background-repeat:no-repeat;
            padding-left:36px;
        }
        .ad-select option{ background:#151a24; color:#eef2f8; }

        .ad-hint{
            margin-top:6px;
            font-size:11.5px;
            color:#8b95a7;
        }

        .ad-actions{
            display:flex;
            align-items:center;
            gap:10px;
            flex-wrap:wrap;
            margin-top:24px;
            padding-top:20px;
            border-top:1px solid rgba(255,255,255,.08);
        }

        .ad-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:13px 26px;
            border-radius:13px;
            font-family:inherit;
            font-size:14.5px;
            font-weight:700;
            border:0;
            cursor:pointer;
            text-decoration:none;
            transition:transform .18s, box-shadow .18s, background .2s, filter .18s, color .2s;
        }
        .ad-btn.gold{
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.32);
        }
        .ad-btn.gold:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            color:#241a05;
            text-decoration:none;
        }
        .ad-btn.line{
            color:#e6ebf3;
            background:rgba(255,255,255,.05);
            border:1px solid var(--line);
        }
        .ad-btn.line:hover{ background:rgba(255,255,255,.12); color:#fff; text-decoration:none; }

        @media (max-width:640px){
            .ad-top{ padding:10px 14px; gap:10px; }
            .ad-nav{ order:3; width:100%; }
            .ad-grid{ grid-template-columns:1fr; }
            .ad-card{ padding:18px; border-radius:18px; }
            .ad-head h1{ font-size:21px; }
            .ad-btn{ width:100%; justify-content:center; }
            .ad-user-name{ display:none; }
        }
    </style>
</head>
<body class="ad-page">

<header class="ad-top">
    <a class="ad-brand" href="index.php">
        <img src="./images/logo.jpg" alt="لوگو">
        <span>
            <b>کتابخانهٔ آنلاین</b>
            <small>پنل مدیریت</small>
        </span>
    </a>

    <nav class="ad-nav">
        <a href="index.php">خانه</a>
        <a class="on" href="books.php">کتاب‌ها</a>
        <a href="request.php">درخواست‌ها</a>
        <a href="student.php">دانشجویان</a>
        <a href="feedback.php">نظرات</a>
    </nav>

    <?php if ($logged): ?>
        <div class="ad-user">
            <span class="ad-user-name"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="ad-avatar"><?php echo htmlspecialchars(mb_substr($name, 0, 1), ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="ad-ghost" href="logout.php">خروج</a>
        </div>
    <?php else: ?>
        <div class="ad-user">
            <a class="ad-ghost" href="admin_login.php">ورود مدیر</a>
        </div>
    <?php endif; ?>
</header>

<div class="ad-wrap">

    <div class="ad-head">
        <div>
            <h1>افزودن کتاب جدید</h1>
            <p>اطلاعات کتاب را وارد کنید تا به فهرست کتابخانه اضافه شود.</p>
        </div>
        <a class="ad-btn line" href="books.php">فهرست کتاب‌ها</a>
    </div>

    <div class="ad-card">

        <?php if ($notice !== ''): ?>
            <div class="ad-alert ok"><span>✓</span><span><?php echo htmlspecialchars($notice, ENT_QUOTES, 'UTF-8'); ?></span></div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="ad-alert err">
                <span>!</span>
                <span>
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    <?php if (!$logged): ?>
                        — <a href="admin_login.php">ورود مدیر</a>
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

        <form method="post" action="" autocomplete="off">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="ad-grid">

                <div class="ad-field">
                    <label class="ad-label" for="bid">شناسهٔ کتاب <span class="req">*</span></label>
                    <input class="ad-input" id="bid" name="bid" type="number" min="1" step="1"
                           value="<?php echo htmlspecialchars($form['bid'], ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="مثلاً 4" required>
                    <div class="ad-hint">عدد یکتا؛ شناسهٔ تکراری پذیرفته نمی‌شود.</div>
                </div>

                <div class="ad-field">
                    <label class="ad-label" for="quantity">تعداد موجودی <span class="req">*</span></label>
                    <input class="ad-input" id="quantity" name="quantity" type="number" min="0" step="1"
                           value="<?php echo htmlspecialchars($form['quantity'], ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="مثلاً 5" required>
                </div>

                <div class="ad-field full">
                    <label class="ad-label" for="name">نام کتاب <span class="req">*</span></label>
                    <input class="ad-input" id="name" name="name" type="text" maxlength="100"
                           value="<?php echo htmlspecialchars($form['name'], ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="نام کامل کتاب" required>
                </div>

                <div class="ad-field">
                    <label class="ad-label" for="authors">نویسنده <span class="req">*</span></label>
                    <input class="ad-input" id="authors" name="authors" type="text" maxlength="100"
                           value="<?php echo htmlspecialchars($form['authors'], ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="نام نویسنده" required>
                </div>

                <div class="ad-field">
                    <label class="ad-label" for="edition">ویرایش <span class="req">*</span></label>
                    <input class="ad-input" id="edition" name="edition" type="text" maxlength="100"
                           value="<?php echo htmlspecialchars($form['edition'], ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="مثلاً 3rd" required>
                </div>

                <div class="ad-field">
                    <label class="ad-label" for="department">دانشکده <span class="req">*</span></label>
                    <input class="ad-input" id="department" name="department" type="text" maxlength="100"
                           list="deptList"
                           value="<?php echo htmlspecialchars($form['department'], ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="مثلاً CSE" required>
                    <datalist id="deptList">
                        <?php foreach ($departments as $d): ?>
                            <option value="<?php echo htmlspecialchars($d, ENT_QUOTES, 'UTF-8'); ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="ad-field">
                    <label class="ad-label" for="status">وضعیت <span class="req">*</span></label>
                    <select class="ad-select" id="status" name="status">
                        <option value="Available" <?php echo $form['status'] === 'Available' ? 'selected' : ''; ?>>موجود</option>
                        <option value="not-available" <?php echo $form['status'] === 'not-available' ? 'selected' : ''; ?>>ناموجود</option>
                    </select>
                    <div class="ad-hint">با تغییر تعداد، وضعیت به‌صورت خودکار تنظیم می‌شود.</div>
                </div>

            </div>

            <div class="ad-actions">
                <button class="ad-btn gold" type="submit" name="submit">＋ ثبت کتاب</button>
                <a class="ad-btn line" href="books.php">انصراف</a>
            </div>
        </form>

    </div>
</div>

<script>
    (function () {
        var qty    = document.getElementById('quantity');
        var status = document.getElementById('status');
        if (!qty || !status) return;

        qty.addEventListener('input', function () {
            var v = parseInt(qty.value, 10);
            if (isNaN(v)) return;
            status.value = v > 0 ? 'Available' : 'not-available';
        });
    })();
</script>

</body>
</html>
