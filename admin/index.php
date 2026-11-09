<?php
header('Content-Type: text/html; charset=utf-8');

session_start();
include "connection.php";

$logged = isset($_SESSION['login_user']);
$name   = $logged ? (string)$_SESSION['login_user'] : '';

/* ---------- آمار زنده ---------- */
function ax_count($db, $sql)
{
    $res = @mysqli_query($db, $sql);
    if (!$res) return 0;
    $row = mysqli_fetch_assoc($res);
    return (int)($row['c'] ?? 0);
}

$totalBooks = ax_count($db, "SELECT COUNT(*) AS c FROM `books`");
$pending    = ax_count($db, "SELECT COUNT(*) AS c FROM `issue_book` WHERE `approve` = '' OR `approve` IS NULL");
$students   = ax_count($db, "SELECT COUNT(*) AS c FROM `student`");
$comments   = ax_count($db, "SELECT COUNT(*) AS c FROM `comments`");
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>پنل مدیریت کتابخانه</title>

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

        body.ax-page{
            background:
                radial-gradient(1100px 520px at 85% -10%, rgba(240,199,94,.10), transparent 60%),
                radial-gradient(900px 480px at 10% 110%, rgba(59,227,196,.07), transparent 60%),
                var(--bg);
            color:var(--txt);
            min-height:100vh;
            padding-bottom:0;
        }

        /* ================= هدر ================= */
        .ax-top{
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

        .ax-brand{
            display:flex;
            align-items:center;
            gap:11px;
            text-decoration:none;
            color:var(--txt);
        }
        .ax-brand img{
            width:44px; height:44px;
            border-radius:13px;
            object-fit:cover;
            border:1px solid rgba(240,199,94,.45);
            box-shadow:0 6px 18px rgba(0,0,0,.45);
        }
        .ax-brand b{
            display:block;
            font-size:16px;
            line-height:1.3;
        }
        .ax-brand small{
            color:var(--muted);
            font-size:11.5px;
            letter-spacing:.2px;
        }

        .ax-nav{
            display:flex;
            align-items:center;
            gap:4px;
            flex-wrap:wrap;
        }
        .ax-nav a{
            padding:9px 14px;
            border-radius:11px;
            color:#cfd6e3;
            font-size:14px;
            text-decoration:none;
            transition:background .2s, color .2s, transform .2s;
        }
        .ax-nav a:hover,
        .ax-nav a.on{
            background:rgba(240,199,94,.13);
            color:#fff;
            text-decoration:none;
        }
        .ax-nav a.on{ box-shadow:inset 0 0 0 1px rgba(240,199,94,.35); }

        .ax-user{
            display:flex;
            align-items:center;
            gap:10px;
        }
        .ax-avatar{
            width:38px; height:38px;
            border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-weight:700;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 6px 16px rgba(201,153,43,.28);
        }
        .ax-user-name{
            font-size:13.5px;
            color:#e6ebf3;
            max-width:150px;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        .ax-ghost{
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
        .ax-ghost:hover{
            background:rgba(240,199,94,.14);
            border-color:rgba(240,199,94,.42);
            color:#fff;
            text-decoration:none;
        }

        /* ================= هیرو ================= */
        .ax-hero{
            position:relative;
            margin:26px auto 0;
            width:min(1180px, 94vw);
            min-height:330px;
            border-radius:26px;
            overflow:hidden;
            border:1px solid var(--line);
            box-shadow:0 30px 70px rgba(0,0,0,.5);
            display:flex;
            align-items:center;
            background:
                linear-gradient(to left, rgba(6,9,14,.96) 0%, rgba(6,9,14,.86) 42%, rgba(6,9,14,.32) 100%),
                url("./images/l1.jpg") center/cover no-repeat;
        }

        .ax-hero-in{
            padding:40px 44px;
            max-width:620px;
            animation:axUp .55s ease both;
        }

        @keyframes axUp{
            from{ opacity:0; transform:translateY(16px); }
            to  { opacity:1; transform:translateY(0); }
        }

        .ax-kicker{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 14px;
            border-radius:999px;
            font-size:12.5px;
            color:#ffe6a8;
            background:rgba(240,199,94,.12);
            border:1px solid rgba(240,199,94,.35);
            margin-bottom:16px;
        }

        .ax-hero h1{
            margin:0 0 10px;
            font-size:38px;
            line-height:1.35;
            letter-spacing:-.4px;
            text-shadow:0 2px 18px rgba(0,0,0,.55);
        }
        .ax-hero p{
            margin:0 0 24px;
            color:#cdd5e2;
            font-size:15.5px;
            line-height:1.9;
            text-shadow:0 1px 12px rgba(0,0,0,.6);
        }

        .ax-actions{ display:flex; gap:12px; flex-wrap:wrap; }

        .ax-btn{
            display:inline-flex;
            align-items:center;
            gap:9px;
            padding:13px 26px;
            border-radius:14px;
            font-size:15px;
            font-weight:700;
            text-decoration:none;
            border:0;
            cursor:pointer;
            transition:transform .18s, box-shadow .18s, background .2s, filter .18s;
        }
        .ax-btn.gold{
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 14px 30px rgba(201,153,43,.34);
        }
        .ax-btn.gold:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            color:#241a05;
            text-decoration:none;
        }
        .ax-btn.line{
            color:#eef2f8;
            background:rgba(10,14,20,.72);
            border:1px solid rgba(255,255,255,.22);
            -webkit-backdrop-filter:blur(8px);
            backdrop-filter:blur(8px);
        }
        .ax-btn.line:hover{
            background:rgba(255,255,255,.12);
            color:#fff;
            text-decoration:none;
        }

        /* ================= آمار ================= */
        .ax-section{
            width:min(1180px, 94vw);
            margin:0 auto;
        }

        .ax-stats{
            display:grid;
            grid-template-columns:repeat(4, 1fr);
            gap:16px;
            margin-top:26px;
        }

        .ax-stat{
            position:relative;
            display:block;
            padding:20px 20px 18px;
            border-radius:20px;
            background:var(--card);
            border:1px solid var(--line);
            text-decoration:none;
            color:inherit;
            overflow:hidden;
            transition:transform .22s, border-color .22s, background .22s, box-shadow .22s;
            animation:axUp .5s ease both;
        }
        .ax-stat::after{
            content:"";
            position:absolute;
            inset-inline-start:0;
            top:0;
            bottom:0;
            width:3px;
            background:linear-gradient(180deg, var(--gold), var(--gold-2));
            opacity:.85;
        }
        .ax-stat:hover{
            transform:translateY(-4px);
            border-color:rgba(240,199,94,.4);
            background:rgba(24,29,39,.85);
            box-shadow:0 22px 44px rgba(0,0,0,.45);
            text-decoration:none;
            color:inherit;
        }

        .ax-stat .ico{
            width:44px; height:44px;
            display:flex; align-items:center; justify-content:center;
            font-size:20px;
            border-radius:13px;
            background:rgba(240,199,94,.12);
            border:1px solid rgba(240,199,94,.25);
            margin-bottom:14px;
        }
        .ax-stat .num{
            font-size:34px;
            font-weight:800;
            line-height:1.1;
            color:#ffffff;
            font-variant-numeric:tabular-nums;
            letter-spacing:.5px;
            text-shadow:0 2px 14px rgba(240,199,94,.22);
        }
        .ax-stat .lbl{
            margin-top:4px;
            font-size:13.5px;
            color:var(--muted);
        }

        /* ================= میان‌برها ================= */
        .ax-head{
            display:flex;
            align-items:baseline;
            justify-content:space-between;
            gap:12px;
            margin:36px 4px 14px;
            flex-wrap:wrap;
        }
        .ax-head h2{
            margin:0;
            font-size:20px;
            font-weight:700;
        }
        .ax-head span{
            font-size:13px;
            color:var(--muted);
        }

        .ax-tiles{
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap:16px;
        }

        .ax-tile{
            display:flex;
            align-items:flex-start;
            gap:14px;
            padding:18px;
            border-radius:18px;
            background:var(--card);
            border:1px solid var(--line);
            text-decoration:none;
            color:inherit;
            transition:transform .2s, border-color .2s, background .2s;
            animation:axUp .55s ease both;
        }
        .ax-tile:hover{
            transform:translateY(-3px);
            border-color:rgba(240,199,94,.4);
            background:rgba(24,29,39,.85);
            text-decoration:none;
            color:inherit;
        }
        .ax-tile .t-ico{
            flex:0 0 auto;
            width:42px; height:42px;
            display:flex; align-items:center; justify-content:center;
            font-size:19px;
            border-radius:12px;
            background:rgba(255,255,255,.06);
            border:1px solid var(--line);
        }
        .ax-tile b{
            display:block;
            font-size:15px;
            margin-bottom:3px;
        }
        .ax-tile small{
            color:var(--muted);
            font-size:12.5px;
            line-height:1.7;
        }

        /* ================= هشدار ورود ================= */
        .ax-note{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
            margin-top:26px;
            padding:16px 20px;
            border-radius:18px;
            background:rgba(240,199,94,.08);
            border:1px solid rgba(240,199,94,.3);
            color:#ffe7ad;
            font-size:14px;
        }

        /* ================= فوتر ================= */
        .ax-foot{
            margin-top:46px;
            border-top:1px solid var(--line);
            background:rgba(7,10,15,.8);
            padding:26px 26px 30px;
        }
        .ax-foot-in{
            width:min(1180px, 94vw);
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
            flex-wrap:wrap;
            color:var(--muted);
            font-size:13px;
        }
        .ax-foot-in strong{ color:#e9eef6; }
        .ax-foot-in a{ color:#ffe0a0; text-decoration:none; }
        .ax-foot-in a:hover{ text-decoration:underline; }

        @media (max-width:980px){
            .ax-stats{ grid-template-columns:repeat(2, 1fr); }
            .ax-tiles{ grid-template-columns:repeat(2, 1fr); }
        }
        @media (max-width:640px){
            .ax-top{ padding:10px 14px; gap:10px; }
            .ax-nav{ order:3; width:100%; }
            .ax-hero{ margin-top:16px; min-height:auto; }
            .ax-hero-in{ padding:26px 22px; }
            .ax-hero h1{ font-size:26px; }
            .ax-stats, .ax-tiles{ grid-template-columns:1fr; }
            .ax-user-name{ display:none; }
        }
    </style>
</head>
<body class="ax-page">

<header class="ax-top">
    <a class="ax-brand" href="index.php">
        <img src="./images/logo.jpg" alt="لوگو">
        <span>
            <b>کتابخانهٔ آنلاین</b>
            <small>پنل مدیریت</small>
        </span>
    </a>

    <nav class="ax-nav">
        <a class="on" href="index.php">خانه</a>
        <a href="books.php">کتاب‌ها</a>
        <a href="request.php">درخواست‌ها</a>
        <a href="student.php">دانشجویان</a>
        <a href="feedback.php">نظرات</a>
    </nav>

    <?php if ($logged): ?>
        <div class="ax-user">
            <span class="ax-user-name"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="ax-avatar"><?php echo htmlspecialchars(mb_substr($name, 0, 1), ENT_QUOTES, 'UTF-8'); ?></span>
            <a class="ax-ghost" href="logout.php">خروج</a>
        </div>
    <?php else: ?>
        <div class="ax-user">
            <a class="ax-ghost" href="admin_login.php">ورود مدیر</a>
        </div>
    <?php endif; ?>
</header>

<section class="ax-hero">
    <div class="ax-hero-in">
        <span class="ax-kicker">✦ سیستم مدیریت کتابخانه</span>
        <h1>همهٔ کتابخانه، در یک نگاه</h1>
        <p>
            کتاب‌ها را مدیریت کنید، درخواست‌های امانت را تأیید کنید و نظرات دانشجویان را ببینید —
            همه از همین پنل.
        </p>

        <div class="ax-actions">
            <a class="ax-btn gold" href="add.php">＋ افزودن کتاب</a>
            <a class="ax-btn line" href="approve.php">تأیید درخواست‌ها</a>
        </div>
    </div>
</section>

<div class="ax-section">

    <?php if (!$logged): ?>
        <div class="ax-note">
            <span>برای دسترسی به امکانات مدیریتی، لطفاً وارد حساب مدیر شوید.</span>
            <a class="ax-btn gold" href="admin_login.php" style="padding:10px 20px;font-size:14px;">ورود مدیر</a>
        </div>
    <?php endif; ?>

    <div class="ax-stats">
        <a class="ax-stat" href="books.php">
            <div class="ico">📚</div>
            <div class="num" data-count="<?php echo (int)$totalBooks; ?>">0</div>
            <div class="lbl">کتاب در کتابخانه</div>
        </a>

        <a class="ax-stat" href="approve.php">
            <div class="ico">⏳</div>
            <div class="num" data-count="<?php echo (int)$pending; ?>">0</div>
            <div class="lbl">درخواست در انتظار تأیید</div>
        </a>

        <a class="ax-stat" href="student.php">
            <div class="ico">🎓</div>
            <div class="num" data-count="<?php echo (int)$students; ?>">0</div>
            <div class="lbl">دانشجوی عضو</div>
        </a>

        <a class="ax-stat" href="feedback.php">
            <div class="ico">💬</div>
            <div class="num" data-count="<?php echo (int)$comments; ?>">0</div>
            <div class="lbl">نظر ثبت‌شده</div>
        </a>
    </div>

    <div class="ax-head">
        <h2>دسترسی سریع</h2>
        <span> کارهای روزمرهٔ مدیریت </span>
    </div>

    <div class="ax-tiles">
        <a class="ax-tile" href="books.php">
            <div class="t-ico">📖</div>
            <div>
                <b>مدیریت کتاب‌ها</b>
                <small>مشاهده، جستجو و ویرایش فهرست کتاب‌ها</small>
            </div>
        </a>

        <a class="ax-tile" href="approve.php">
            <div class="t-ico">✅</div>
            <div>
                <b>تأیید امانت</b>
                <small>بررسی و تأیید درخواست‌های در انتظار</small>
            </div>
        </a>

        <a class="ax-tile" href="request.php">
            <div class="t-ico">📨</div>
            <div>
                <b>درخواست‌ها</b>
                <small>فهرست کامل درخواست‌های امانت کتاب</small>
            </div>
        </a>

        <a class="ax-tile" href="student.php">
            <div class="t-ico">🎓</div>
            <div>
                <b>دانشجویان</b>
                <small>مدیریت اعضا و اطلاعات آن‌ها</small>
            </div>
        </a>

        <a class="ax-tile" href="feedback.php">
            <div class="t-ico">💬</div>
            <div>
                <b>نظرات</b>
                <small>دیدن بازخوردهای کاربران کتابخانه</small>
            </div>
        </a>

        <a class="ax-tile" href="profile.php">
            <div class="t-ico">👤</div>
            <div>
                <b>پروفایل من</b>
                <small>مشاهده و تغییر اطلاعات حساب مدیر</small>
            </div>
        </a>
    </div>

</div>

<footer class="ax-foot">
    <div class="ax-foot-in">
        <div>
            <strong>کتابخانهٔ آنلاین</strong> — سیستم مدیریت کتابخانه
            <br>
            <span>© <?php echo date('Y'); ?> تمام حقوق محفوظ است.</span>
        </div>
        <div style="text-align:left; line-height:1.9; direction:ltr;">
            <a href="mailto:javadattar1364@gmail.com">javadattar1364@gmail.com</a><br>
            +98 935 443 1676
        </div>
    </div>
</footer>

<script>
    (function () {
        var nums = document.querySelectorAll('.num[data-count]');
        if (!nums.length) return;

        function animate(el) {
            var target = parseInt(el.getAttribute('data-count'), 10) || 0;
            var start  = performance.now();
            var dur    = 900;

            function step(now) {
                var p = Math.min((now - start) / dur, 1);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased).toLocaleString('fa-IR');
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        nums.forEach(animate);
    })();
</script>

</body>
</html>
