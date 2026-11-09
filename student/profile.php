<?php
header('Content-Type: text/html; charset=utf-8');

include "connection.php";
session_start();

/* ---------- محافظت از صفحه ---------- */
if (!isset($_SESSION['login_user'])) {
    header("Location: student_login.php");
    exit;
}

$login = (string)$_SESSION['login_user'];

/* ---------- اطلاعات دانشجو ---------- */
$stmt = mysqli_prepare($db, "SELECT * FROM `student` WHERE `username` = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);
$res  = mysqli_stmt_get_result($stmt);
$user = $res ? mysqli_fetch_assoc($res) : null;
mysqli_stmt_close($stmt);

if (!$user) {
    session_destroy();
    header("Location: student_login.php");
    exit;
}

/* ---------- آمار امانت ---------- */
function pr_count($db, $sql, $login)
{
    $stmt = mysqli_prepare($db, $sql);
    if (!$stmt) return 0;
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
    return (int)($row['c'] ?? 0);
}

$active  = pr_count($db, "SELECT COUNT(*) AS c FROM `issue_book` WHERE `username` = ? AND `approve` = 'Yes' AND (`return` = '' OR `return` IS NULL)", $login);
$pending = pr_count($db, "SELECT COUNT(*) AS c FROM `issue_book` WHERE `username` = ? AND (`approve` = '' OR `approve` IS NULL)", $login);
$totalLoans = pr_count($db, "SELECT COUNT(*) AS c FROM `issue_book` WHERE `username` = ?", $login);

/* ---------- عکس پروفایل ---------- */
$pic = trim((string)($user['pic'] ?? ''));
$hasPic = ($pic !== '' && is_file(__DIR__ . '/images/' . $pic));

$initial = mb_substr((string)($user['first'] ?: $login), 0, 1, 'UTF-8');
$fullName = trim(($user['first'] ?? '') . ' ' . ($user['last'] ?? ''));
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>پروفایل من</title>

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

        body.pf-page{
            background:
                radial-gradient(1100px 520px at 85% -10%, rgba(240,199,94,.10), transparent 60%),
                radial-gradient(900px 480px at 10% 110%, rgba(59,227,196,.07), transparent 60%),
                var(--bg);
            color:var(--txt);
            min-height:100vh;
            padding-bottom:60px;
        }

        /* ================= هدر ================= */
        .pf-top{
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
        .pf-brand{ display:flex; align-items:center; gap:11px; text-decoration:none; color:var(--txt); }
        .pf-brand img{
            width:44px; height:44px;
            border-radius:13px;
            object-fit:cover;
            border:1px solid rgba(240,199,94,.45);
            box-shadow:0 6px 18px rgba(0,0,0,.45);
        }
        .pf-brand b{ display:block; font-size:16px; line-height:1.3; }
        .pf-brand small{ color:var(--muted); font-size:11.5px; }

        .pf-nav{ display:flex; align-items:center; gap:4px; flex-wrap:wrap; }
        .pf-nav a{
            padding:9px 14px;
            border-radius:11px;
            color:#cfd6e3;
            font-size:14px;
            text-decoration:none;
            transition:background .2s, color .2s;
        }
        .pf-nav a:hover{ background:rgba(255,255,255,.07); color:#fff; text-decoration:none; }
        .pf-nav a.on{
            background:rgba(240,199,94,.13);
            color:#fff;
            box-shadow:inset 0 0 0 1px rgba(240,199,94,.35);
            text-decoration:none;
        }

        .pf-ghost{
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
        .pf-ghost:hover{
            background:rgba(255,99,99,.16);
            border-color:rgba(255,99,99,.4);
            color:#fff;
            text-decoration:none;
        }

        /* ================= کارت پروفایل ================= */
        .pf-wrap{
            width:min(920px, 94vw);
            margin:28px auto 0;
            animation:pfUp .5s ease both;
        }

        @keyframes pfUp{
            from{ transform:translateY(14px); }
            to  { transform:translateY(0); }
        }

        .pf-card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 28px 70px rgba(0,0,0,.5);
        }

        /* کاور */
        .pf-cover{
            position:relative;
            height:190px;
            background:
                linear-gradient(180deg, rgba(6,9,14,.35) 0%, rgba(6,9,14,.85) 100%),
                url("./images/l1.jpg") center/cover no-repeat;
        }
        .pf-cover::after{
            content:"";
            position:absolute;
            inset:0;
            background:linear-gradient(90deg, rgba(240,199,94,.16), transparent 45%);
            pointer-events:none;
        }

        .pf-avatar-wrap{
            position:relative;
            margin-top:-58px;
            padding:0 34px;
            display:flex;
            align-items:flex-end;
            gap:18px;
            flex-wrap:wrap;
        }

        .pf-avatar{
            position:relative;
            width:116px; height:116px;
            border-radius:34px;
            overflow:hidden;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:44px;
            font-weight:800;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            border:4px solid #141922;
            box-shadow:0 18px 40px rgba(0,0,0,.55);
        }
        .pf-avatar img{ width:100%; height:100%; object-fit:cover; }

        .pf-id{ padding-bottom:10px; }
        .pf-id h1{
            margin:0;
            font-size:25px;
            line-height:1.4;
        }
        .pf-id .sub{
            margin-top:4px;
            color:var(--muted);
            font-size:13.5px;
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap:wrap;
        }
        .pf-chip{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:3px 11px;
            border-radius:999px;
            font-size:12px;
            color:#ffe3a1;
            background:rgba(240,199,94,.12);
            border:1px solid rgba(240,199,94,.32);
        }

        /* آمار */
        .pf-stats{
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap:12px;
            padding:22px 34px 4px;
        }
        .pf-stat{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            border-radius:16px;
            background:rgba(255,255,255,.045);
            border:1px solid rgba(255,255,255,.09);
            transition:background .2s, border-color .2s;
        }
        .pf-stat:hover{
            background:rgba(255,255,255,.08);
            border-color:rgba(240,199,94,.28);
        }
        .pf-stat .ico{
            width:40px; height:40px;
            display:flex; align-items:center; justify-content:center;
            border-radius:12px;
            font-size:18px;
            background:rgba(240,199,94,.12);
            border:1px solid rgba(240,199,94,.25);
        }
        .pf-stat .n{
            font-size:21px;
            font-weight:800;
            line-height:1.2;
            font-variant-numeric:tabular-nums;
        }
        .pf-stat .t{
            font-size:12px;
            color:var(--muted);
        }

        /* اطلاعات */
        .pf-body{ padding:24px 34px 30px; }

        .pf-section-title{
            display:flex;
            align-items:center;
            gap:8px;
            margin:0 0 14px;
            font-size:16px;
            font-weight:700;
            color:#f2f5fa;
        }
        .pf-section-title::after{
            content:"";
            flex:1;
            height:1px;
            background:linear-gradient(90deg, rgba(240,199,94,.35), transparent);
        }

        .pf-grid{
            display:grid;
            grid-template-columns:repeat(2, 1fr);
            gap:12px;
        }

        .pf-field{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            border-radius:15px;
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.08);
            transition:background .2s, border-color .2s;
            min-width:0;
        }
        .pf-field:hover{
            background:rgba(255,255,255,.075);
            border-color:rgba(240,199,94,.25);
        }
        .pf-field .f-ico{
            flex:0 0 auto;
            width:36px; height:36px;
            display:flex; align-items:center; justify-content:center;
            border-radius:11px;
            font-size:15px;
            background:rgba(255,255,255,.06);
            border:1px solid var(--line);
        }
        .pf-field .f-body{ min-width:0; }
        .pf-field .f-k{
            display:block;
            font-size:11.5px;
            color:var(--muted);
            margin-bottom:2px;
        }
        .pf-field .f-v{
            display:block;
            font-size:14.5px;
            color:#eef2f8;
            overflow-wrap:anywhere;
        }
        .pf-field .f-v.ltr{ direction:ltr; text-align:right; }

        .pf-pw{ display:flex; align-items:center; gap:8px; }
        .pf-pw .dots{ letter-spacing:2px; color:#cfd6e3; }
        .pf-eye{
            border:0;
            background:rgba(240,199,94,.12);
            border:1px solid rgba(240,199,94,.3);
            color:#ffe3a1;
            font-family:inherit;
            font-size:11.5px;
            padding:3px 10px;
            border-radius:8px;
            cursor:pointer;
            transition:background .2s, color .2s;
        }
        .pf-eye:hover{ background:rgba(240,199,94,.22); color:#fff; }

        /* دکمه‌ها */
        .pf-actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            margin-top:22px;
            padding-top:20px;
            border-top:1px solid rgba(255,255,255,.08);
        }

        .pf-btn{
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
            transition:transform .18s, box-shadow .18s, background .2s, filter .18s, color .2s;
        }
        .pf-btn.gold{
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.32);
        }
        .pf-btn.gold:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            color:#241a05;
            text-decoration:none;
        }
        .pf-btn.line{
            color:#e6ebf3;
            background:rgba(255,255,255,.05);
            border:1px solid var(--line);
        }
        .pf-btn.line:hover{ background:rgba(255,255,255,.12); color:#fff; text-decoration:none; }

        .pf-hint{
            display:flex;
            align-items:center;
            gap:8px;
            margin-top:16px;
            font-size:12.5px;
            color:var(--muted);
        }

        @media (max-width:760px){
            .pf-cover{ height:150px; }
            .pf-avatar-wrap{ padding:0 20px; margin-top:-48px; gap:14px; }
            .pf-avatar{ width:92px; height:92px; border-radius:26px; font-size:34px; }
            .pf-id h1{ font-size:20px; }
            .pf-stats{ grid-template-columns:1fr; padding:18px 20px 0; }
            .pf-grid{ grid-template-columns:1fr; }
            .pf-body{ padding:20px 20px 26px; }
            .pf-top{ padding:10px 14px; gap:10px; }
            .pf-nav{ order:3; width:100%; }
        }
    </style>
</head>
<body class="pf-page">

<header class="pf-top">
    <a class="pf-brand" href="index.php">
        <img src="./images/logo.jpg" alt="لوگو">
        <span>
            <b>کتابخانهٔ آنلاین</b>
            <small>پنل دانشجو</small>
        </span>
    </a>

    <nav class="pf-nav">
        <a href="index.php">خانه</a>
        <a href="books.php">کتاب‌ها</a>
        <a href="request.php">درخواست‌ها</a>
        <a href="feedback.php">نظرات</a>
        <a class="on" href="profile.php">پروفایل</a>
    </nav>

    <a class="pf-ghost" href="logout.php">خروج</a>
</header>

<div class="pf-wrap">
    <div class="pf-card">

        <div class="pf-cover"></div>

        <div class="pf-avatar-wrap">
            <div class="pf-avatar">
                <?php if ($hasPic): ?>
                    <img src="./images/<?php echo htmlspecialchars($pic, ENT_QUOTES, 'UTF-8'); ?>" alt="عکس پروفایل">
                <?php else: ?>
                    <?php echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </div>

            <div class="pf-id">
                <h1><?php echo htmlspecialchars($fullName !== '' ? $fullName : $login, ENT_QUOTES, 'UTF-8'); ?></h1>
                <div class="sub">
                    <span>@<?php echo htmlspecialchars((string)$user['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="pf-chip">🎓 شماره دانشجویی: <?php echo (int)$user['roll']; ?></span>
                </div>
            </div>
        </div>

        <div class="pf-stats">
            <div class="pf-stat">
                <div class="ico">📖</div>
                <div>
                    <div class="n"><?php echo (int)$active; ?></div>
                    <div class="t">کتاب امانت‌گرفته‌شده</div>
                </div>
            </div>

            <div class="pf-stat">
                <div class="ico">⏳</div>
                <div>
                    <div class="n"><?php echo (int)$pending; ?></div>
                    <div class="t">درخواست در انتظار</div>
                </div>
            </div>

            <div class="pf-stat">
                <div class="ico">📚</div>
                <div>
                    <div class="n"><?php echo (int)$totalLoans; ?></div>
                    <div class="t">کل درخواست‌ها</div>
                </div>
            </div>
        </div>

        <div class="pf-body">

            <h2 class="pf-section-title">اطلاعات حساب</h2>

            <div class="pf-grid">

                <div class="pf-field">
                    <div class="f-ico">👤</div>
                    <div class="f-body">
                        <span class="f-k">نام</span>
                        <span class="f-v"><?php echo htmlspecialchars((string)$user['first'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>

                <div class="pf-field">
                    <div class="f-ico">👤</div>
                    <div class="f-body">
                        <span class="f-k">نام خانوادگی</span>
                        <span class="f-v"><?php echo htmlspecialchars((string)$user['last'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>

                <div class="pf-field">
                    <div class="f-ico">🆔</div>
                    <div class="f-body">
                        <span class="f-k">نام کاربری</span>
                        <span class="f-v ltr"><?php echo htmlspecialchars((string)$user['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>

                <div class="pf-field">
                    <div class="f-ico">🎓</div>
                    <div class="f-body">
                        <span class="f-k">شماره دانشجویی</span>
                        <span class="f-v"><?php echo (int)$user['roll']; ?></span>
                    </div>
                </div>

                <div class="pf-field">
                    <div class="f-ico">📧</div>
                    <div class="f-body">
                        <span class="f-k">ایمیل</span>
                        <span class="f-v ltr"><?php echo htmlspecialchars((string)$user['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>

                <div class="pf-field">
                    <div class="f-ico">📱</div>
                    <div class="f-body">
                        <span class="f-k">شماره تماس</span>
                        <span class="f-v ltr"><?php echo htmlspecialchars((string)$user['contact'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>

                <div class="pf-field" style="grid-column:1 / -1;">
                    <div class="f-ico">🔒</div>
                    <div class="f-body" style="flex:1;">
                        <span class="f-k">رمز عبور</span>
                        <span class="f-v pf-pw">
                            <span class="dots" id="pwDots">••••••</span>
                            <span class="dots" id="pwReal" style="display:none;letter-spacing:0;">
                                <?php echo htmlspecialchars((string)$user['password'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <button type="button" class="pf-eye" id="pwToggle">نمایش</button>
                        </span>
                    </div>
                </div>

            </div>

            <div class="pf-actions">
                <a class="pf-btn gold" href="update_password.php">🔑 تغییر رمز عبور</a>
                <a class="pf-btn line" href="request.php">📨 درخواست کتاب</a>
                <a class="pf-btn line" href="index.php">بازگشت به خانه</a>
            </div>

            <div class="pf-hint">
                <span>ℹ️</span>
                <span>برای تغییر اطلاعات شخصی یا عکس پروفایل با مدیر کتابخانه در تماس باشید.</span>
            </div>

        </div>

    </div>
</div>

<script>
    (function () {
        var btn   = document.getElementById('pwToggle');
        var dots  = document.getElementById('pwDots');
        var real  = document.getElementById('pwReal');
        if (!btn || !dots || !real) return;

        btn.addEventListener('click', function () {
            var hidden = real.style.display === 'none';
            real.style.display = hidden ? 'inline' : 'none';
            dots.style.display = hidden ? 'none' : 'inline';
            btn.textContent = hidden ? 'مخفی' : 'نمایش';
        });
    })();
</script>

</body>
</html>
